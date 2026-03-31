<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status_room".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 *
 * @property Room[] $rooms
 */
class StatusRoom extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status_room';
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
     * Gets query for [[Rooms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::class, ['status_room_id' => 'id']);
    }

    public static function getStatusId(string $alias): int {
        return static::findOne(['alias' => $alias])->id;
    }
}
