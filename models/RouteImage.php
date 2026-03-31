<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "route_image".
 *
 * @property int $id
 * @property int $route_id
 * @property string $image
 *
 * @property Route $route
 */
class RouteImage extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'route_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['route_id', 'image'], 'required'],
            [['route_id'], 'integer'],
            [['image'], 'string', 'max' => 255],
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
            'image' => 'Image',
        ];
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
