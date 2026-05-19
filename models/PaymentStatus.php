<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_status".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 *
 * @property Booking[] $bookings
 */
class PaymentStatus extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_status';
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
        return $this->hasMany(Booking::class, ['payment_status' => 'id']);
    }

    public static function getStatusId(string $alias): int
    {
        return static::findOne(['title' => $alias])->id;
    }
}