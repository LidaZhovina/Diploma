<?php

namespace app\models;

use Yii;

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
 * @property int $wellness_program_id
 * @property int $route_id
 * @property int $amount_residents
 * @property string $comment
 *
 * @property BookingUser[] $bookingUsers
 * @property Room $room
 * @property Route $route
 * @property StatusBooking $statusBooking
 * @property WellnessProgram $wellnessProgram
 */
class Booking extends \yii\db\ActiveRecord
{
    // Виртуальные поля для пошагового сбора данных
    public $guests = [];      // массив гостей (для третьего шага)
    public $guests_count;     // временное поле для количества гостей (из первого шага)
    public $agreement;        // чекбокс согласия (если нужен)

    public $arrival_date;
    public $departure_date;
    public $comment;
    public $room_id;

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
            [['room_id', 'arrival_date', 'departure_date', 'contact_phone', 'price', 'status_booking_id', 'wellness_program_id', 'route_id', 'amount_residents'], 'required'],
            [['room_id', 'status_booking_id', 'wellness_program_id', 'route_id',], 'integer'],
            [['contact_phone'], 'string', 'max' => 20],
            ['amount_residents','integer', 'max' => '5', 'message' => 'Максимально число гостей - 5 человек'],

            // [['arrival_date', 'departure_date'], 'safe'],
            [['arrival_date', 'departure_date'], 'date', 'format' => 'php:Y-m-d'],
            ['arrival_date', 'compare', 'compareValue' => $tomorrow, 'operator' => '>=', 'message' => 'Дата заезда не может быть раньше ' . $tomorrow],
            ['departure_date', 'compare', 'compareAttribute' => 'arrival_date', 'operator' => '>', 'message' => 'Дата выезда должна быть позже даты заезда'],
            
            [['price'], 'number'],
            [['comment'], 'string'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::class, 'targetAttribute' => ['room_id' => 'id']],
            [['status_booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusBooking::class, 'targetAttribute' => ['status_booking_id' => 'id']],
            [['wellness_program_id'], 'exist', 'skipOnError' => true, 'targetClass' => WellnessProgram::class, 'targetAttribute' => ['wellness_program_id' => 'id']],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::class, 'targetAttribute' => ['route_id' => 'id']],
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
            'route_id' => 'Маршрут',
            'arrival_date' => 'Дата заселения',
            'departure_date' => 'Дата выселения',
            'contact_phone' => 'Контактный телефон',
            'price' => 'Цена',
            'status_booking_id' => 'Статус бронирования',
            'wellness_program_id' => 'Оздоровительные программы',
            'amount_residents' => 'Количество гостей',
            'comment' => 'Комментарий',
            // 'agreement' => 'Я даю согласие на обработку персональных данных и подтверждаю ознакомление с пользовательским соглашением и политикой конфиденциальности ',
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
     * Gets query for [[Room]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::class, ['id' => 'room_id']);
    }

    /**
     * Gets query for [[Route]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoute()
    {
        return $this->hasOne(Route::class, ['id' => 'route_id']);
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
     * Gets query for [[WellnessProgram]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWellnessProgram()
    {
        return $this->hasOne(WellnessProgram::class, ['id' => 'wellness_program_id']);
    }
}
