<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $booking_id
 * @property int|null $route_id
 * @property int $stars
 * @property string $comment
 * @property string $created_at
 *
 * @property Booking $booking
 * @property Route $route
 * @property User $user
 */
class Review extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'comment'], 'required'],
            [['user_id', 'booking_id', 'route_id', 'stars'], 'integer'],
            ['stars', 'integer', 'min' => 1, 'max' => 5],
            [['comment'], 'string'],
            [['booking_id', 'route_id'], 'default', 'value' => null],
            ['booking_id', 'validateOneTarget'],
            [
                'booking_id',
                'unique',
                'targetAttribute' => ['user_id', 'booking_id'],
                'when'            => fn($m) => !empty($m->booking_id),
                'message'         => 'Вы уже оставили отзыв на это бронирование.',
            ],
            [
                'route_id',
                'unique',
                'targetAttribute' => ['user_id', 'route_id'],
                'when'            => fn($m) => !empty($m->route_id),
                'message'         => 'Вы уже оставили отзыв на этот маршрут.',
            ],
            [['created_at'], 'safe'],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::class, 'targetAttribute' => ['booking_id' => 'id']],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::class, 'targetAttribute' => ['route_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'booking_id' => 'Бронирование',
            'route_id'  => 'Маршрут',
            'stars' => 'Оценка',
            'comment' => 'Комментарий',
            'created_at' => 'Дата',
        ];
    }

    /** Должен быть заполнен ровно один из booking_id / route_id */
    public function validateOneTarget(string $attribute, array $params): void
    {
        $hasBk = !empty($this->booking_id);
        $hasRt = !empty($this->route_id);
        if ($hasBk && $hasRt) {
            $this->addError($attribute, 'Укажите только один объект отзыва.');
        }
        if (!$hasBk && !$hasRt) {
            $this->addError($attribute, 'Не указан объект отзыва.');
        }
    }

    /**
     * Gets query for [[Booking]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::class, ['id' => 'booking_id']);
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    // ------------------ Методы ------------------
    /**
     * Проверяет, оставил ли пользователь отзыв на данное бронирование.
     */
    public static function hasBookingReview(int $userId, int $bookingId): bool
    {
        return static::find()
            ->where(['user_id' => $userId, 'booking_id' => $bookingId])
            ->exists();
    }

    /**
     * Проверяет, оставил ли пользователь отзыв на данный маршрут.
     */
    public static function hasRouteReview(int $userId, int $routeId): bool
    {
        return static::find()
            ->where(['user_id' => $userId, 'route_id' => $routeId])
            ->exists();
    }
}
