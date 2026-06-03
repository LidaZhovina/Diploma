<?php

namespace app\controllers;

use app\models\Booking;
use app\models\AdminSearch;
use app\models\PaymentStatus;
use app\models\Room;
use app\models\RoomType;
use app\models\StatusBooking;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for Booking model.
 */
class AdminController extends Controller
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

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if (!Yii::$app->user->identity?->isAdmin) {
            return $this->redirect('/');
        }


        return true;
    }

    /**
     * Lists all Booking models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AdminSearch();
        $searchModel->status_alias = Yii::$app->request->get('status', 'pending');
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Статистика по статусам
        $stats = [];
        $statuses = ['pending', 'new', 'active', 'past', 'cancelled'];
        foreach ($statuses as $status) {
            $id = Booking::getStatusId($status);
            $stats[$status] = Booking::find()->where(['status_booking_id' => $id])->count();
        }

        // Выручка за текущий месяц (по оплаченным бронированиям)   
        $monthRevenue = Booking::find()
            ->where(['>=', 'arrival_date', date('Y-m-01')])
            ->andWhere(['payment_status' => PaymentStatus::getStatusId('paid')])
            ->sum('price') ?? 0;

        $roomOccupation = [];
        // Получаем все номера с группировкой по типу и количеству мест
        $rooms = Room::find()
            ->select(['room_type_id', 'number_guests', 'COUNT(*) as total'])
            ->groupBy(['room_type_id', 'number_guests'])
            ->asArray()
            ->all();
        // Получаем ID активных бронирований (status = 'active')
        $activeBookingRoomIds = Booking::find()
            ->select(['room_id'])
            ->where(['status_booking_id' => Booking::getStatusId('active')])
            ->distinct()
            ->column();
        // Подсчитываем количество занятых номеров по каждой группе
        $occupiedCounts = [];
        foreach ($activeBookingRoomIds as $roomId) {
            $room = Room::findOne($roomId);
            if ($room) {
                $key = $room->room_type_id . '_' . $room->number_guests;
                if (!isset($occupiedCounts[$key])) {
                    $occupiedCounts[$key] = 0;
                }
                $occupiedCounts[$key]++;
            }
        }
        // Формируем массив для вывода
        foreach ($rooms as $room) {
            $typeName = RoomType::findOne($room['room_type_id'])->name;
            $guests = $room['number_guests'];
            $total = $room['total'];
            $key = $room['room_type_id'] . '_' . $guests;
            $occupied = $occupiedCounts[$key] ?? 0;
            $roomOccupation["{$typeName} {$guests}-местный"] = [
                'total' => $total,
                'occupied' => $occupied,
            ];
        }

        // Последние пользователи 
        $recentUsers = User::find()
            ->orderBy(['id' => SORT_DESC])
            ->limit(3)
            ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stats' => $stats,
            'roomOccupation' => $roomOccupation,
            'monthRevenue' => $monthRevenue,
            'recentUsers' => $recentUsers,
        ]);
    }

    /**
     * Displays a single Booking model.
     * @param int $id ID
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

    /* Смена статуса бронирования*/

    public function actionChangeStatus($id, $alias)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $model->status_booking_id = StatusBooking::getStatusId($alias);

            if ($model->save()) {
                Yii::$app->session->setFlash('warning', 'Статус обновлён!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->redirect('/admin');
    }


    /**
     * Updates an existing Booking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
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
     * @param int $id ID
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
     * @param int $id ID
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
