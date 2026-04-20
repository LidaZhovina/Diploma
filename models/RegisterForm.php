<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * ContactForm is the model behind the contact form.
 */
class RegisterForm extends Model
{
    public $name;
    public $email;
    public $surname;
    public $password;
    public $patronymic;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password', 'surname', 'name'], 'required', 'message' => 'Данно поле обязательно для заполнения'],
            [['email', 'password', 'surname', 'name', 'patronymic'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 6],
            [['password'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Ланинские буквы и цифры'],
            [['password'], 'match', 'pattern' => '/\d+/', 'message' => 'Хотя-бы одна цифра'],
            [['password'], 'match', 'pattern' => '/[a-zA-Z]+/', 'message' => 'Хотя-бы одна латинская буква'],
            [['surname', 'name', 'patronymic'], 'match', 'pattern' => '/^[а-яё\s]+$/iu', 'message' => 'Символы Кирилицы'],
            [['email'], 'unique', 'targetClass' => User::class],
            ['email', 'email', 'message' => 'Почта должна иметь вид: example@index.com'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Адрес электронной почты',
            'password' => 'Пароль',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function register(bool $isUser): ?User
    {
        if ($this->validate()) {
            $user = new User;
            $user->load($this->attributes, '');
            $user->password = Yii::$app->security->generatePasswordHash($user->password);
            $user->auth_key = Yii::$app->security->generateRandomString();
            $defoultRole = Role::findOne(['title' => 'User']);
            $RoleReception = Role::findOne(['title' => 'Reception']);
            if($isUser) {
                $user->role_id = $defoultRole->id;
            } else {
                $user->role_id = $RoleReception->id;
            }
            

            if(!$user->save()){
                VarDumper::dump($user->errors);
            }

            return $user;
        }
        return null;
    }
}
