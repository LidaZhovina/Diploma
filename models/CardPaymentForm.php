<?php
namespace app\models;
use yii\base\Model;

class CardPaymentForm extends Model
{
    public $card_number;
    public $cvv;
    public $expiry;
    public $card_holder;


    public function rules() {
        return [
            [['card_number', 'cvv', 'expiry', 'card_holder'], 'required'],
            ['card_number', 'match', 'pattern' => '/^[\d]{4} [\d]{4} [\d]{4} [\d]{4}$/', 'message' => 'Верный формат: XXXX XXXX XXXX XXXX'],
            ['cvv', 'match', 'pattern' => '/^\d{3}$/', 'message' => 'CVV должен состоять из 3 цифр'],
            ['expiry', 'match', 'pattern' => '/^(0[1-9]|1[0-2])\/[\d]{2}$/', 'message' => 'ММ/ГГ'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'card_number' => 'Номер карты',
            'expiry' => 'Срок действия',
            'cvv' => 'CVV',
            'card_holder' => 'Имя владельца',
        ];
    }
}
