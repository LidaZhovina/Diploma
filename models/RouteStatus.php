<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "route_status".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 *
 * @property Route[] $routes
 */
class RouteStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'route_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'alias'], 'required'],
            [['title', 'alias'], 'string', 'max' => 255],
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
            'alias' => 'Alias',
        ];
    }

    /**
     * Gets query for [[Routes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::class, ['route_status' => 'id']);
    }

    public static function getStatusId(string $alias): int
    {
        return static::findOne(['alias' => $alias])->id;
    }
}
