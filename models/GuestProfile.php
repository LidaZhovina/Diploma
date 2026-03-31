<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "guest_profile".
 *
 * @property int $id
 * @property int $user_id
 * @property string $phone
 * @property string $birth_date
 * @property string $passport_series
 * @property string $passport_number
 *
 * @property User $user
 */
class GuestProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guest_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'phone', 'birth_date', 'passport_series', 'passport_number'], 'required'],
            [['user_id'], 'integer'],
            [['birth_date'], 'safe'],
            [['phone', 'passport_number'], 'string', 'max' => 20],
            [['passport_series'], 'string', 'max' => 10],
            [['user_id'], 'unique'],
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
            'phone' => 'Phone',
            'birth_date' => 'Birth Date',
            'passport_series' => 'Passport Series',
            'passport_number' => 'Passport Number',
        ];
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
