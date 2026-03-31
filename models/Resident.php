<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resident".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $birth_date
 * @property int $is_main_guest главный гость в бронировании
 * @property string $created_at
 *
 * @property BookingUser[] $bookingUsers
 * @property User $user
 */
class Resident extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resident';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_main_guest'], 'default', 'value' => 0],
            [['user_id', 'name', 'surname', 'patronymic', 'birth_date'], 'required'],
            [['user_id', 'is_main_guest'], 'integer'],
            [['birth_date', 'created_at'], 'safe'],
            [['name', 'surname', 'patronymic'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'patronymic' => 'Patronymic',
            'birth_date' => 'Birth Date',
            'is_main_guest' => 'Is Main Guest',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[BookingUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookingUsers()
    {
        return $this->hasMany(BookingUser::class, ['resident_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
