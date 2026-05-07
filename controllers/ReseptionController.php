<?php

namespace app\controllers;

use app\models\Booking;
use app\models\GuestProfile;
use app\models\ReseptionSearch;
use app\models\StatusRoom;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReseptionController implements the CRUD actions for Booking model.
 */
class ReseptionController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Booking models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ReseptionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        $publicActions = ['display-image'];
        if (in_array($action->id, $publicActions)) {
            return true;
        }

        if (!Yii::$app->user->identity?->isReception) {
            return $this->redirect('/');
        }


        return true;
    }

    /**
     * Displays a single Booking model.
     * @param int $id №
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCheckIn($id)
    {
        $booking = Booking::findOne($id);
        if (!$booking || $booking->status_booking_id != Booking::getStatusId('new')) {
            Yii::$app->session->setFlash('error', 'Бронирование не найдено или не готово к заселению.');
            return $this->redirect(['index']);
        }

        $residents = [];
        foreach ($booking->bookingUsers as $bu) {
            $residents[] = $bu->resident;
        }

        $profiles = [];
        foreach ($residents as $resident) {
            $profile = GuestProfile::findOne(['user_id' => $resident->user_id]);
            if (!$profile) {
                $profile = new GuestProfile();
                $profile->user_id = $resident->user_id;
                $profile->birth_date = $resident->birth_date;
                if ($resident->is_main_guest) {
                    $profile->phone = $booking->contact_phone;
                }
            }
            $profiles[] = $profile;
        }

        if (Yii::$app->request->isPost) {
            $valid = true;
            $postData = Yii::$app->request->post('GuestProfile', []);
            foreach ($profiles as $i => $profile) {
                if (isset($postData[$i])) {
                    $profile->load($postData[$i], '');
                }
                if (!$profile->validate()) {
                    $valid = false;
                }
            }
            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($profiles as $profile) {
                        if (!$profile->save()) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', 'Ошибка сохранения профиля.');
                            return $this->render('check-in', compact('booking', 'residents', 'profiles'));
                        }
                    }
                    $booking->status_booking_id = Booking::getStatusId('active');
                    $booking->save(false);
                    $room = $booking->room;
                    $room->status_room_id = StatusRoom::getStatusId('occupied');
                    $room->save(false);
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Гости заселены.');
                    return $this->redirect(['index']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Ошибка при заселении: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Пожалуйста, исправьте ошибки в форме.');
            }
        }

        return $this->render('check-in', compact('booking', 'residents', 'profiles'));
    }
    

    /**
     * Creates a new Booking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Booking();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Booking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id №
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Booking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id №
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id №
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Booking::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
