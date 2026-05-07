<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "route_resident".
 *
 * @property int $id
 * @property int $route_id
 * @property int $resident_id
 * @property string $created_at
 *
 * @property Resident $resident
 * @property Route $route
 */
class RouteResident extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'route_resident';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['route_id', 'resident_id'], 'required'],
            [['route_id', 'resident_id'], 'integer'],
            [['created_at'], 'safe'],
            [['resident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resident::class, 'targetAttribute' => ['resident_id' => 'id']],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::class, 'targetAttribute' => ['route_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route_id' => 'Route ID',
            'resident_id' => 'Resident ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Resident]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResident()
    {
        return $this->hasOne(Resident::class, ['id' => 'resident_id']);
    }

    /**
     * Gets query for [[Route]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoute()
    {
        return $this->hasOne(Route::class, ['id' => 'route_id']);
    }

}
