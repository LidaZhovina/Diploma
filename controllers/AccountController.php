<?php

namespace app\controllers;

use app\models\Booking;
use app\models\AccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Room;
use app\models\WellnessProgram;
use Yii;

/**
 * AccountController implements the CRUD actions for Booking model.
 */
class AccountController extends Controller
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
                    'class' => VerbFilter::class,
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
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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

    /**
     * Шаг 1: Форма ввода дат, количества гостей и комментария.
     * @param int $room_id ID номера из каталога
     */
    public function actionCreate($room_id)
    {
        $room = Room::findOne($room_id);

        if (!$room) {
            throw new NotFoundHttpException('Номер не найден.');
        }

        $model = new Booking();
        $model->room_id = $room->id;

        if ($model->load($this->request->post()) && $model->validate()) {
            //Проверка доступности номера через метод в модели Booking
            if (!Booking::isAviable($room->id, $model->arrival_date, $model->departure_date)) {
                Yii::$app->session->setFlash('error', 'Номер уже забронирован на выбранные даты.');
                return $this->render('create', [
                    'model' => $model,
                    'room' => $room,
                ]);
            }

            //Сохраняем данные в сессию
            Yii::$app->session->set('booking_step1', [
                'room_id' => $room->id,
                'arrival_date' => $model->arrival_date,
                'departure_date' => $model->departure_date,
                'guests_count' => $model->guests_count,
                'comment' => $model->comment,
            ]);

            return $this->redirect(['account/confirm-program']);
        }

        return $this->render('create', [
            'model' => $model,
            'room' => $room,
        ]);
    }

    /**
     * Шаг 2: Страница с модальным окном "Хотите выбрать программу?"
     * Просто отображает представление, данные берутся из сессии.
     */
    public function actionConfirmProgram()
    {
        // Проверяем, что первый шаг пройден
        if (!Yii::$app->session->get('booking_step1')) {
            return $this->redirect(['catalog/index']);
        }
        return $this->render('confirm-program');
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
