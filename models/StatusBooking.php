<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status_booking".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 *
 * @property Booking[] $bookings
 */
class StatusBooking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status_booking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'alias'], 'required'],
            [['title', 'alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'alias' => 'Alias',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::class, ['status_booking_id' => 'id']);
    }

    public static function getItems(): array { 
       return self::find() 
       ->select('title') 
       ->indexBy('id') 
       ->column(); 
   }
}
