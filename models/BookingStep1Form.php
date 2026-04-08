<?php
namespace app\models;

use yii\base\Model;

class BookingStep1Form extends Model
{
    public $room_id;
    public $arrival_date;
    public $departure_date;
    public $guests_count;
    public $contact_phone   ;
    public $comment;

    public function rules()
    {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        return [
            [['room_id', 'arrival_date', 'departure_date', 'guests_count', 'contact_phone'], 'required'],
            [['room_id', 'guests_count'], 'integer'],
            ['guests_count', 'integer', 'min' => 1, 'max' => 5],
            
            [['arrival_date', 'departure_date'], 'date', 'format' => 'php:Y-m-d'],
            ['arrival_date', 'compare', 'compareValue' => $tomorrow, 'operator' => '>=', 'message' => 'Дата заезда не может быть раньше завтрашнего дня'],
            ['departure_date', 'compare', 'compareAttribute' => 'arrival_date', 'operator' => '>', 'message' => 'Дата выезда должна быть позже даты заезда'],
            
            ['comment', 'string'],

            // ['contact_phone', 'match', 'pattern' => '/^\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}$/', 'message' => 'Номер телефона должен быть в формате +7(999)999-99-99'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'arrival_date' => 'Дата заезда',
            'departure_date' => 'Дата выезда',
            'guests_count' => 'Количество гостей',
            'contact_phone' => 'Контактный телефон',
            'comment' => 'Комментарий',
        ];
    }
}