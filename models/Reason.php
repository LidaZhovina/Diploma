<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reason".
 *
 * @property int $booking_id
 * @property string $comment
 *
 * @property Booking $booking
 */
class Reason extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reason';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'required'],
            [['comment'], 'string'],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::class, 'targetAttribute' => ['booking_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'booking_id' => 'Booking ID',
            'comment' => 'Укажите причину отмены:',
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

}
