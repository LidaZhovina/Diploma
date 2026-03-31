<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "booking_user".
 *
 * @property int $id
 * @property int $resident_id
 * @property int $booking_id
 *
 * @property Booking $booking
 * @property Resident $resident
 */
class BookingUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'booking_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resident_id', 'booking_id'], 'required'],
            [['resident_id', 'booking_id'], 'integer'],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::class, 'targetAttribute' => ['booking_id' => 'id']],
            [['resident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resident::class, 'targetAttribute' => ['resident_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resident_id' => 'Resident ID',
            'booking_id' => 'Booking ID',
        ];
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
     * Gets query for [[Resident]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResident()
    {
        return $this->hasOne(Resident::class, ['id' => 'resident_id']);
    }
}
