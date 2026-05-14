<?php

namespace app\controllers;

use app\models\Booking;
use app\models\AccountSearch;
use app\models\BookingGuestsForm;
use app\models\BookingStep1Form;
use app\models\PaymentStatus;
use app\models\Resident;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Room;
use app\models\Route;
use app\models\RouteResident;
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

        $userId = Yii::$app->user->id;

        $bookingIds = Booking::find()
            ->joinWith('bookingUsers.resident')
            ->where(['resident.user_id' => $userId, 'resident.is_main_guest' => 1])
            ->select('booking.id')
            ->column();
        // Получаем всех resident_id, связанных с этими бронированиями (всех гостей)
        $residentIds = (new \yii\db\Query())
            ->select('resident_id')
            ->from('booking_user')
            ->where(['booking_id' => $bookingIds])
            ->column();
        $residentIds = array_unique($residentIds);


        // Забронированные маршруты (уже записан)
        $routeResidents = RouteResident::find()
            ->with(['route', 'resident'])
            ->where(['resident_id' => $residentIds])
            ->all();

        // Группируем по route_id
        $bookedRoutesGrouped = [];
        $bookedRouteIds = [];
        foreach ($routeResidents as $rr) {
            $routeId = $rr->route_id;
            $bookedRouteIds[] = $routeId;
            if (!isset($bookedRoutesGrouped[$routeId])) {
                $bookedRoutesGrouped[$routeId] = [
                    'route' => $rr->route,
                    'residents' => []
                ];
            }
            $bookedRoutesGrouped[$routeId]['residents'][] = [
                'id' => $rr->resident_id,
                'name' => $rr->resident->surname . ' ' . $rr->resident->name
            ];
        }
        $bookedRouteIds = array_unique($bookedRouteIds);

        // Доступные маршруты – те, на которые нет записей
        $availableRoutes = Route::find()
            ->where(['not in', 'id', $bookedRouteIds])
            ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'bookedRoutesGrouped' => $bookedRoutesGrouped,
            'availableRoutes' => $availableRoutes,
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
            $booking->amount_residents = $step1['guests_count'];
            $booking->pay_type_id = $model->pay_type;
            $booking->comment = $step1['comment'];
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
            $prepayment = $booking->price * 0.30; // 30% от итоговой суммы
            $booking->payment_amount = $prepayment;

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

            // Устанавливаем предоплату (30% от итоговой цены)
            $booking->payment_status = PaymentStatus::getStatusId('pending');
            $booking->pay_type_id = $model->pay_type;

            try {
                if ($booking->saveWithGuests($model->guests, Yii::$app->user->id, $guestPrograms)) {
                    // успех
                    Yii::$app->session->remove('booking_step1');
                    Yii::$app->session->remove('guest_programs');

                    $booking->save(false);
                    

                    // Сохраняем ID бронирования в сессию для страницы оплаты
                    Yii::$app->session->set('booking_id', $booking->id);

                    // В зависимости от способа оплаты перенаправляем
                    if ($model->pay_type == '1') {
                        return $this->redirect(['account/payment']);
                    } elseif ($model->pay_type == '2') {
                        // Например, на страницу оплаты картой (пока заглушка)
                        return $this->redirect(['account/payment-sbp']);
                    } elseif ($model->pay_type == '3') {
                        // Например, на страницу оплаты картой (пока заглушка)
                        return $this->redirect(['account/payment-card']);
                    } else {
                        // Если способ не распознан, можно в ЛК или с ошибкой
                        return $this->redirect(['account/index']);
                    }
                } else {
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
     * Страница оплаты по QR-коду
     */
    public function actionPayment()
    {
        $bookingId = Yii::$app->session->get('booking_id');
        if (!$bookingId) {
            return $this->redirect(['index']);
        }

        $booking = Booking::findOne($bookingId);
        if (!$booking) {
            Yii::$app->session->remove('booking_id');
            return $this->redirect(['index']);
        }

        // Если уже оплачено – сразу в ЛК
        if ($booking->payment_status == PaymentStatus::getStatusId('paid')) {
            Yii::$app->session->remove('booking_id');
            return $this->redirect(['index']);
        }

        return $this->render('qrCode', ['booking' => $booking]);
    }

    /**
     * AJAX-проверка статуса оплаты
     * @param int $id ID бронирования
     * @return \yii\web\Response (JSON)
     */
    public function actionCheckPaymentStatus($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $booking = Booking::findOne($id);
        if (!$booking) {
            return ['paid' => false, 'error' => 'Бронирование не найдено'];
        }
        return ['paid' => ($booking->payment_status === PaymentStatus::getStatusId('paid'))];
    }

    /**
     * Подтверждение оплаты (меняет статус на "оплачено")
     * @param int $id ID бронирования
     */
    public function actionConfirmPayment($id)
    {
        $booking = Booking::findOne($id);
        if (!$booking) {
            throw new NotFoundHttpException('Бронирование не найдено.');
        }

        $booking->payment_status = PaymentStatus::getStatusId('paid');
        $booking->save(false);

        Yii::$app->session->remove('booking_id');
        Yii::$app->session->setFlash('success', 'Оплата прошла успешно!');
        // Yii::$app->session->setFlash('success', 'Бронирование создано.');
        return $this->redirect(['account/index']);
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

    /* Запись на маршрут */
    public function actionBookRoute($id)
    {
        // 1. Находим маршрут
        $route = Route::findOne($id);
        if (!$route) {
            Yii::$app->session->setFlash('error', 'Маршрут не найден.');
            return $this->redirect(['index']);
        }

        $userId = Yii::$app->user->id;

        // 2. Находим активное бронирование пользователя (ищем по user_id через Residents)
        $resident = Resident::findOne(['user_id' => $userId]);
        if (!$resident) {
            Yii::$app->session->setFlash('error', 'Ваш профиль гостя не найден.');
            return $this->redirect(['index']);
        }

        // 3. Находим бронирование, где этот гость является главным или просто связан
        $booking = Booking::find()
            ->innerJoin('booking_user', 'booking.id = booking_user.booking_id')
            ->where(['booking_user.resident_id' => $resident->id])
            ->andWhere(['in', 'booking.status_booking_id', [Booking::getStatusId('new'), Booking::getStatusId('active')]])
            ->one();
        if (!$booking) {
            Yii::$app->session->setFlash('error', 'Нет активного бронирования.');
            return $this->redirect(['index']);
        }

        // 4. Все гости из этого бронирования
        $residents = [];
        foreach ($booking->bookingUsers as $bu) {
            $residents[] = $bu->resident;
        }

        // Обработка POST-запроса (когда форма выбора гостей отправлена)
        if (empty($residents)) {
            Yii::$app->session->setFlash('error', 'Нет гостей для записи.');
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->isPost) {
            $selectedIds = Yii::$app->request->post('resident_ids', []);
            if (empty($selectedIds)) {
                Yii::$app->session->setFlash('error', 'Не выбран ни один гость.');
                return $this->redirect(['index']);
            }

            $validIds = array_intersect($selectedIds, array_column($residents, 'id'));
            if (count($selectedIds) !== count($validIds)) {
                Yii::$app->session->setFlash('error', 'Выбраны некорректные гости.');
                return $this->redirect(['index']);
            }

            // Проверка свободных мест
            $currentCount = RouteResident::find()->where(['route_id' => $route->id])->count();
            if ($currentCount + count($selectedIds) > $route->number_participant) {
                Yii::$app->session->setFlash('error', 'Недостаточно свободных мест на маршруте.');
                return $this->redirect(['index']);
            }

            // Сохраняем записи
            $success = true;
            foreach ($selectedIds as $rid) {
                $rr = new RouteResident();
                $rr->route_id = $route->id;
                $rr->resident_id = $rid;
                if (!$rr->save()) {
                    $success = false;
                    break;
                }
            }
            if ($success) {
                Yii::$app->session->setFlash('success', 'Успешная запись маршрут.');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при записи.');
            }
            return $this->redirect(['index']);
        }

        // 5. Если гостей несколько — показываем форму выбора
        if (count($residents) > 1) {
            return $this->render('choose-guests', [
                'route' => $route,
                'residents' => $residents,
            ]);
        }

        // 6. Если гость один — пытаемся записать
        $currentCount = RouteResident::find()->where(['route_id' => $route->id])->count();
        if ($currentCount >= $route->number_participant) {
            Yii::$app->session->setFlash('error', 'Мест нет.');
            return $this->redirect(['index']);
        }

        $rr = new RouteResident();
        $rr->route_id = $route->id;
        $rr->resident_id = $residents[0]->id;
        if ($rr->save()) {
            Yii::$app->session->setFlash('success', 'Записан.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка.');
        }
        return $this->redirect(['index']);
    }

    /* Отмена записи на маршрут */
    public function actionCancelRoute($route_id, $resident_id)
    {
        $rr = RouteResident::findOne(['route_id' => $route_id, 'resident_id' => $resident_id]);
        $rr && $rr->delete(); // короткая запись
        Yii::$app->session->setFlash('success', 'Отменено.');
        return $this->redirect(['index']);
    }

    /* Отправка почты */
    public function actionMail()
    {
        $data = "text mail";

        $res = Booking::sendMail($data);

        if ($res) {
            Yii::$app->session->setFlash("success", "Письмо успешно отправлено!");
        } else {
            Yii::$app->session->setFlash("error", "Ошибка отправки письма!");
        }

        return $this->redirect("/account");
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
