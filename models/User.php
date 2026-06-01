<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @property int $role_id
 * @property string $auth_key
 *
 * @property GuestProfile $guestProfile
 * @property Review[] $reviews 
 * @property Resident[] $residents
 * @property Role $role
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password', 'surname', 'name', 'patronymic', 'role_id', 'auth_key'], 'required'],
            [['role_id'], 'integer'],
            [['email', 'password', 'surname', 'name', 'patronymic', 'auth_key'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Почта',
            'password' => 'Пароль',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'role_id' => 'Роль',
            'auth_key' => 'Auth Key',
        ];
    }


    /**
     * Gets query for [[GuestProfile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGuestProfile()
    {
        return $this->hasOne(GuestProfile::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]]. 
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Residents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResidents()
    {
        return $this->hasMany(Resident::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public static function findByLogin($login): User | null
    {
        return static::findOne(['email' => $login]);
    }

    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getIsAdmin()
    {
        return $this->role_id === 1;
    }
    public function getIsClient()
    {
        return $this->role_id === 2;
    }
    public function getIsReception()
    {
        return $this->role_id === 3;
    }
}
