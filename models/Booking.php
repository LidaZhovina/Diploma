<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "booking".
 *
 * @property int $id
 * @property int $room_id
 * @property string $arrival_date
 * @property string $departure_date
 * @property string $contact_phone 
 * @property float $price
 * @property int $status_booking_id
 * @property int $amount_residents
 * @property int $pay_type_id 
 * @property int $payment_status
 * @property float $payment_amount 
 * @property string $comment
 *
 * @property BookingUser[] $bookingUsers
 * @property PayType $payType 
 * @property PaymentStatus $paymentStatus
 * @property Reason $reason 
 * @property Review[] $reviews
 * @property Room $room
 * @property StatusBooking $statusBooking
 */
class Booking extends \yii\db\ActiveRecord
{
    // Поля для пошагового сбора данных
    public $guests = [];      // массив гостей (для третьего шага)
    public $guests_count;     // временное поле для количества гостей (из первого шага)


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'booking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        return [
            [['room_id', 'arrival_date', 'departure_date', 'contact_phone', 'pay_type_id', 'price', 'status_booking_id', 'amount_residents', 'payment_amount',], 'required'],
            [['room_id', 'status_booking_id',], 'integer'],
            [['contact_phone'], 'string', 'max' => 20],
            ['amount_residents', 'integer', 'max' => '5', 'message' => 'Максимально число гостей - 5 человек'],

            // [['arrival_date', 'departure_date'], 'safe'],
            [['arrival_date', 'departure_date'], 'date', 'format' => 'php:Y-m-d'],
            ['arrival_date', 'compare', 'compareValue' => $tomorrow, 'operator' => '>=', 'message' => 'Дата заезда не может быть раньше ' . $tomorrow],
            ['departure_date', 'compare', 'compareAttribute' => 'arrival_date', 'operator' => '>', 'message' => 'Дата выезда должна быть позже даты заезда'],

            [['guests_count'], 'integer', 'min' => 1, 'max' => 5],
            ['guests', 'validateGuests'],

            [['price', 'payment_amount',], 'number'],
            [['comment'], 'string'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::class, 'targetAttribute' => ['room_id' => 'id']],
            [['status_booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusBooking::class, 'targetAttribute' => ['status_booking_id' => 'id']],
            [['pay_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PayType::class, 'targetAttribute' => ['pay_type_id' => 'id']],
            [['payment_status'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentStatus::class, 'targetAttribute' => ['payment_status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'room_id' => 'Номер',
            'arrival_date' => 'Дата заселения',
            'departure_date' => 'Дата выселения',
            'contact_phone' => 'Контактный телефон',
            'price' => 'Полная стоимость',
            'payment' => 'Предоплата',
            'pay_type' => 'Способ оплаты',
            'payment_amount' => 'Предоплата',
            'payment_status' => 'Статус оплаты',
            'status_booking_id' => 'Статус бронирования',
            'amount_residents' => 'Количество гостей',
            'comment' => 'Пожелания',
        ];
    }

    // ------------------ Связи ------------------
    /**
     * Gets query for [[BookingUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookingUsers()
    {
        return $this->hasMany(BookingUser::class, ['booking_id' => 'id']);
    }

    /** 
     * Gets query for [[PayType]]. 
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getPayType()
    {
        return $this->hasOne(PayType::class, ['id' => 'pay_type_id']);
    }

    /**
     * Gets query for [[PaymentStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentStatus()
    {
        return $this->hasOne(PaymentStatus::class, ['id' => 'payment_status']);
    }

    /**
     * Gets query for [[Reason]]. 
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getReason()
    {
        return $this->hasOne(Reason::class, ['booking_id' => 'id']);
    }

    /**
     * Gets query for [[Room]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::class, ['id' => 'room_id']);
    }


    /**
     * Gets query for [[StatusBooking]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusBooking()
    {
        return $this->hasOne(StatusBooking::class, ['id' => 'status_booking_id']);
    }

    /** 
     * Gets query for [[Reviews]]. 
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['booking_id' => 'id']);
    }

    // ------------------ Методы ------------------
    public static function getStatusId($alias)
    {
        $status = StatusBooking::findOne(['alias' => $alias]);
        if (!$status) {
            Yii::error("Статус с alias '$alias' не найден");
            return null;
        }
        return $status->id;
    }

    public static function isAvailable($roomId, $arrival, $departure, $excludeBookingId = null)
    {
        $cancelledId = self::getStatusId('cancelled');
        $query = self::find()->where(['room_id' => $roomId]);
        if ($cancelledId !== null) {
            $query->andWhere(['not in', 'status_booking_id', $cancelledId]);
        }
        $query->andWhere(['<', 'arrival_date', $departure])
            ->andWhere(['>', 'departure_date', $arrival]);
        if ($excludeBookingId) {
            $query->andWhere(['!=', 'id', $excludeBookingId]);
        }
        return $query->count() == 0;
    }

    public function calculatePrice($room, $route = null)
    {
        $night = (new \DateTime($this->arrival_date))->diff(new \DateTime($this->departure_date))->days;
        $price = $room->price_per_day * $night;


        return $price;
    }

    public function validateGuests($attribute, $params)
    {
        if (count($this->guests) != $this->guests_count) {
            $this->addError($attribute, 'Количество гостей не совпадает.');
        }
        foreach ($this->guests as $i => $guest) {
            if (empty($guest['surname']) || empty($guest['name']) || empty($guest['birth_date'])) {
                $this->addError($attribute, "У гостя №" . ($i + 1) . " не заполнены обязательные поля (фамилия, имя, дата рождения).");
            }
        }
    }

    public function saveWithGuests($guestData, $userId, $guestPrograms)
    {
        if (!$this->validate()) {
            Yii::error('Ошибки валидации Booking: ' . print_r($this->errors, true));
            Yii::$app->session->setFlash('error', 'Ошибка валидации: ' . print_r($this->errors, true));
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$this->save()) {
                throw new \Exception('Ошибка сохранения бронирования' . print_r($this->errors, true));
            }

            foreach ($guestData as $i => $guest) {
                $resident = new Resident();
                $resident->user_id = ($i == 0) ? $userId : null;
                $resident->surname = $guest['surname'];
                $resident->name = $guest['name'];
                $resident->patronymic = $guest['patronymic'] ?? '';
                $resident->birth_date = $guest['birth_date'];
                $resident->is_main_guest = ($i == 0) ? 1 : 0;
                $resident->wellness_program_id = $guestPrograms[$i] ?? null;
                if (!$resident->save()) {
                    throw new \Exception('Ошибка сохранения гостя: ' . print_r($resident->errors, true));
                }

                $bookingUser = new BookingUser();
                $bookingUser->booking_id = $this->id;
                $bookingUser->resident_id = $resident->id;
                if (!$bookingUser->save()) {
                    throw new \Exception('Ошибка сохранения связи бронирования и гостя');
                }
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
            // Yii::error($e->getMessage(), __METHOD__);
            // Yii::$app->session->setFlash('error', 'Ошибка: ' . $e->getMessage());
            // return false;
        }
    }

    public static function sendMail($data)
    {
        Yii::$app->mailer->htmlLayout = "@app/mail/layouts/html";

        // var_dump($data); exit;

        return Yii::$app->mailer
            ->compose('mail', [
                "data" => $data
            ])
            ->setFrom("lida.zhovina@mail.ru")
            ->setTo("lida.zhovina@mail.ru")
            ->setSubject('Предоплата по заказу')
            ->send();
    }
}
