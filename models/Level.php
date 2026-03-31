<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "level".
 *
 * @property int $id
 * @property string $title
 *
 * @property Route[] $routes
 */
class Level extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * Gets query for [[Routes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::class, ['level_id' => 'id']);
    }

    public static function getItems(): array {
        return self::find()
        ->select('title')
        ->indexBy('id')
        ->column();
    }
}
