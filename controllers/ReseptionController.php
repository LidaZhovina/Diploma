<?php

namespace app\controllers;

use app\models\Booking;
use app\models\GuestProfile;
use app\models\Reason;
use app\models\ReseptionSearch;
use app\models\StatusRoom;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

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
        $activeTab = Yii::$app->request->get('tab', 'new');

        $searchModel  = new ReseptionSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            $activeTab
        );

        // Счётчики по вкладкам
        $aliases   = ['new', 'active', 'past', 'cancelled'];
        $tabCounts = [];
        foreach ($aliases as $alias) {
            $tabCounts[$alias] = Booking::find()
                ->joinWith(['statusBooking'])
                ->where(['status_booking.alias' => $alias])
                ->count();
        }

        // Выезды сегодня
        $todayCheckouts = Booking::find()
            ->joinWith(['statusBooking'])
            ->where(['status_booking.alias' => 'active'])
            ->andWhere(['booking.departure_date' => date('Y-m-d')])
            ->all();

        // Активные брони — для сайдбара берём сами брони, а не резидентов
        $activeBookings = Booking::find()
            ->joinWith(['statusBooking'])
            ->where(['status_booking.alias' => 'active'])
            ->all();

        return $this->render('index', [
            'searchModel'    => $searchModel,
            'dataProvider'   => $dataProvider,
            'activeTab'      => $activeTab,
            'tabCounts'      => $tabCounts,
            'todayCheckouts' => $todayCheckouts,
            'activeBookings' => $activeBookings,
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
    /** Заселение гостей */
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
                    Yii::$app->session->setFlash('toast', ['type' => 'success', 'message' => 'Гости заселены']);
                    return $this->redirect(['index']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('toast', ['type' => 'error', 'message' => 'Ошибка при выселении' . $e->getMessage()]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Пожалуйста, исправьте ошибки в форме.');
            }
        }

        return $this->render('check-in', compact('booking', 'residents', 'profiles'));
    }

    /** Выселение гостей */
    public function actionCheckOut($id)
    {
        $booking = Booking::findOne($id);
        if (!$booking || $booking->status_booking_id != Booking::getStatusId('active')) {
            Yii::$app->session->setFlash('error', 'Бронирование не активно или не найдено.');
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        $success = true;

        $booking->status_booking_id = Booking::getStatusId('past');
        if (!$booking->save(false)) {
            $success = false;
        } else {
            $room = $booking->room;
            $room->status_room_id = StatusRoom::getStatusId('unbound');
            if (!$room->save(false)) {
                $success = false;
            }
        }

        if ($success) {
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Гость выселен, поездка закрыта.');
        } else {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Ошибка при выселении.');
        }
        return $this->redirect(['index']);
    }

    /** Причина отмены */
    public function actionReason($id)
    {
        $booking = $this->findModel($id);
        $model = new Reason();
        $model->booking_id = $id;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $booking->status_booking_id = Booking::getStatusId('cancelled');

            if ($model->save() && $booking->save()) {
                Yii::$app->session->setFlash('warning', 'Причина записана');
                return $this->redirect(['view', 'id' => $model->booking_id]);
            } else {
                VarDumper::dump($model->errors);
                die;
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
