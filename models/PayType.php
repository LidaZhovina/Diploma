<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pay_type".
 *
 * @property int $id
 * @property string $title
 *
 * @property Booking[] $bookings
 */
class PayType extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pay_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::class, ['pay_type_id' => 'id']);
    }

    public static function getItems(): array { 
       return self::find() 
       ->select('title') 
       ->indexBy('id') 
       ->column(); 
   }

}
