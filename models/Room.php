<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "room".
 *
 * @property int $id
 * @property int $room_type_id
 * @property int $number
 * @property int $floor
 * @property int $status_room_id
 * @property string $description
 * @property int $price_per_day
 * @property int $number_guests
 *
 * @property Booking[] $bookings
 * @property RoomImage[] $roomImages
 * @property RoomType $roomType
 * @property StatusRoom $statusRoom
 */
class Room extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_type_id', 'number', 'floor', 'status_room_id', 'description', 'price_per_day', 'number_guests'], 'required'],
            [['room_type_id', 'number', 'floor', 'status_room_id', 'price_per_day', 'number_guests'], 'integer'],
            [['description'], 'string'],
            [['room_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoomType::class, 'targetAttribute' => ['room_type_id' => 'id']],
            [['status_room_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusRoom::class, 'targetAttribute' => ['status_room_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_type_id' => 'Тип номера',
            'number' => 'Номер',
            'floor' => 'Этаж',
            'status_room_id' => 'Статус',
            'description' => 'Описание',
            'price_per_day' => 'Цена за ночь',
            'number_guests' => 'Количество гостей',
            'arrival_date' => 'Дата заселения',
            'departure_date' => 'Дата выселения',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::class, ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomImages()
    {
        return $this->hasMany(RoomImage::class, ['room_id' => 'id']);
    }

    /**
     * Получение первого изображения на превью
     */
    public function getFirstImage()
    {
        return $this->getImages()->one();
    }

    /**
     * Gets query for [[RoomType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomType()
    {
        return $this->hasOne(RoomType::class, ['id' => 'room_type_id']);
    }

    /**
     * Gets query for [[StatusRoom]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusRoom()
    {
        return $this->hasOne(StatusRoom::class, ['id' => 'status_room_id']);
    }
}
