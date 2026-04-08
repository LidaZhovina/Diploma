<?php

namespace app\models;

use yii\base\Model;

class BookingGuestsForm extends Model
{
    // public $name;
    // public $surname;
    // public $patronymic;
    // public $birth_date;
    // public $agreement;
    public $guests = [];

    public function rules()
    {
        return [
            ['guests', 'validateGuests'],
        ];
    }

    public function validateGuests($attribute, $params)
    {
        $step1 = \Yii::$app->session->get('booking_step1');
        $guestsCount = $step1['guests_count'] ?? 0;

        if (count($this->guests) != $guestsCount) {
            $this->addError($attribute, 'Количество гостей не совпадает.');
            return;
        }

        foreach ($this->guests as $i => $guest) {
            if (empty($guest['surname']) || empty($guest['name']) || empty($guest['birth_date'])) {
                $this->addError($attribute, "У гостя №" . ($i + 1) . " не заполнены обязательные поля (фамилия, имя, дата рождения).");
            }
        }
    }
}
