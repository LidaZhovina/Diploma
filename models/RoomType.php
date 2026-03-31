<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "room_type".
 *
 * @property int $id
 * @property string $name
 *
 * @property Room[] $rooms
 */
class RoomType extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Rooms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::class, ['room_type_id' => 'id']);
    }


    public static function getTypes(): array { 
       return self::find() 
       ->select('name') 
       ->indexBy('id') 
       ->column(); 
   }

}
