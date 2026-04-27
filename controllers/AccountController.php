<?php

namespace app\controllers;

use app\models\Booking;
use app\models\AccountSearch;
use app\models\BookingGuestsForm;
use app\models\BookingStep1Form;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Room;
use app\models\StatusBooking;
use app\models\WellnessProgram;
use Yii;
use yii\helpers\VarDumper;

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

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        $publicActions = ['display-image'];
        if (in_array($action->id, $publicActions)) {
            return true;
        }

        if (!Yii::$app->user->identity?->isClient) {
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

        $model = new BookingStep1Form();
        $model->room_id = $room->id;

        if ($model->load($this->request->post()) && $model->validate()) {
            //Проверка доступности номера через метод в модели Booking
            if (!Booking::isAvailable($room->id, $model->arrival_date, $model->departure_date)) {
                Yii::$app->session->setFlash('error', 'Номер уже забронирован на выбранные даты.');
                return $this->render('create', [
                    'model' => $model,
                    'room' => $room,
                ]);
            }

            //Сохраняем данные в сессию
            Yii::$app->session->set('booking_step1', $model->attributes);

            return $this->redirect(['account/select-program']);
        }

        return $this->render('create', [
            'model' => $model,
            'room' => $room,
        ]);
    }

    /**
     * Шаг 2а: Каталог оздоровительных программ (если пользователь ответил "Да")
     */
    public function actionSelectProgram()
    {
        $step1 = Yii::$app->session->get('booking_step1');
        if (!$step1) {
            return $this->redirect(['catalog/index']);
        }
        $guestsCount = $step1['guests_count'];

        // Если форма отправлена (выбраны программы)
        if (Yii::$app->request->isPost) {
            $selectedPrograms = Yii::$app->request->post('program', []);
            // Проверяем, что выбраны программы для всех гостей
            if (count($selectedPrograms) != $guestsCount) {
                Yii::$app->session->setFlash('error', 'Пожалуйста, выберите программу для каждого гостя.');
                return $this->render('select-program', [
                    'guestsCount' => $guestsCount,
                    'programs' => WellnessProgram::find()->all(),
                ]);
            }
            // Сохраняем выбранные программы в сессию (индекс 0 для первого гостя, 1 для второго...)
            Yii::$app->session->set('guest_programs', $selectedPrograms);
            return $this->redirect(['account/guests-data']);
        }

        return $this->render('select-program', [
            'guestsCount' => $guestsCount,
            'programs' => WellnessProgram::find()->all(),
        ]);
    }

    /**
     * Шаг 2б: Сохраняем ID выбранной программы в сессию и переходим к форме гостей
     * @param int $id ID программы
     */
    public function actionSetProgram($id)
    {
        $step1 = Yii::$app->session->get('booking_step1');
        if (!$step1) {
            return $this->redirect(['catalog/index']);
        }

        $program = WellnessProgram::findOne($id);
        if (!$program) {
            throw new NotFoundHttpException('Программа не найдена.');
        }

        Yii::$app->session->set('wellness_program_id', $id);
        return $this->redirect(['account/guests-data']);
    }


    public function actionGuestsData()
    {
        // var_dump(Yii::$app->request->post());
        // exit;
        $step1 = Yii::$app->session->get('booking_step1');
        if (!$step1) return $this->redirect(['catalog/index']);

        $room = Room::findOne($step1['room_id']);
        if (!$room) throw new NotFoundHttpException('Номер не найден.');

        $model = new BookingGuestsForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // $contactPhone = $step1['contact_phone'] ?? null;

            $booking = new Booking();
            $booking->room_id = $step1['room_id'];
            $booking->contact_phone = $step1['contact_phone'];
            $booking->arrival_date = $step1['arrival_date'];
            $booking->departure_date = $step1['departure_date'];
            $booking->amount_residents = $step1['guests_count']; // или amount_residents
            $booking->comment = $step1['comment'];
            $booking->route_id = null; // или значение из сессии, если есть
            $pendingId = Booking::getStatusId('pending');
            if (!$pendingId) {
                Yii::$app->session->setFlash('error', 'Ошибка конфигурации: статус "pending" не найден.');
                return $this->render('guests-data', [
                    'model' => $model,
                    'room' => $room,
                    'step1' => $step1,
                ]);
            }
            $booking->status_booking_id = $pendingId;
            $booking->price = $booking->calculatePrice($room);
            $prepayment = $booking->price * 0.10; // 10% от итоговой суммы

            // var_dump($booking->contact_phone); exit;

            // if ($booking->saveWithGuests($model->guests, Yii::$app->user->id)) {
            //     Yii::$app->session->remove('booking_step1');
            //     Yii::$app->session->remove('wellness_program_id');
            //     Yii::$app->session->setFlash('success', 'Бронирование создано.');
            //     return $this->redirect(['account/index']);
            // } else {
            //     $errors = $booking->getErrors();
            //     Yii::$app->session->setFlash('error', 'Ошибка сохранения бронирования:' . print_r($errors, true));
            // }

            $guestPrograms = Yii::$app->session->get('guest_programs', []);
            if (count($guestPrograms) != $step1['guests_count']) {
                Yii::$app->session->setFlash('error', 'Не выбраны программы для всех гостей.');
                return $this->redirect(['account/select-program']);
            }

            try {
                if ($booking->saveWithGuests($model->guests, Yii::$app->user->id, $guestPrograms)) {
                    // успех
                    Yii::$app->session->remove('booking_step1');
                    Yii::$app->session->remove('guest_programs');
                    Yii::$app->session->setFlash('success', 'Бронирование создано.');
                    return $this->redirect(['account/index']);
                } else {
                    // Сюда не попадём, потому что при ошибке будет исключение
                    Yii::$app->session->setFlash('error', 'Неизвестная ошибка');
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Ошибка: ' . $e->getMessage());
                // Остаёмся на той же странице
                return $this->render('guests-data', [
                    'model' => $model,
                    'room' => $room,
                    'step1' => $step1,
                ]);
            }
        }


        return $this->render('guests-data', [
            'model' => $model,
            'room' => $room,
            'step1' => $step1,
        ]);
    }

    /**
     * Отмена бронирования пользователем.
     * @param int $id ID бронирования
     */
    public function actionCancelBooking($id)
    {
        $booking = Booking::findOne($id);
        if (!$booking) {
            throw new NotFoundHttpException('Бронирование не найдено.');
        }

        // Проверяем, что текущий пользователь – владелец бронирования
        $userId = Yii::$app->user->id;
        $isOwner = Booking::find()
            ->joinWith('bookingUsers.resident')
            ->where(['booking.id' => $id, 'resident.user_id' => $userId, 'resident.is_main_guest' => 1])
            ->exists();

        if (!$isOwner) {
            Yii::$app->session->setFlash('error', 'У вас нет прав на отмену этого бронирования.');
            return $this->redirect(['account/index']);
        }

        // Отменить можно только бронирования со статусом 'pending' или 'upcoming'
        $pendingId = Booking::getStatusId('pending');
        $upcomingId = Booking::getStatusId('upcoming');
        if (in_array($booking->status_booking_id, [$pendingId, $upcomingId])) {
            $booking->status_booking_id = Booking::getStatusId('cancelled');
            $booking->save(false);
            Yii::$app->session->setFlash('success', 'Бронирование отменено.');
        } else {
            Yii::$app->session->setFlash('error', 'Невозможно отменить бронирование с текущим статусом.');
        }

        return $this->redirect(['account/index']);
    }

    /* Смена статуса */

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
