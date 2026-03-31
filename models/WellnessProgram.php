<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wellness_program".
 *
 * @property int $id
 * @property string $title
 * @property string $duration 
 * @property string $description
 *
 * @property Booking[] $bookings
 * @property WellnessImage[] $wellnessImages
 */
class WellnessProgram extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wellness_program';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'duration', 'description'], 'required'],
            [['description'], 'string'],
            [['title', 'duration',], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'duration' => 'Длительность',
            'description' => 'Описание',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::class, ['wellness_program_id' => 'id']);
    }

    /**
     * Gets query for [[WellnessImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWellnessImage()
    {
        return $this->hasOne(WellnessImage::class, ['wellness_id' => 'id']);
    }

    /**
     * Получить путь к изображению (для удобства в представлениях)
     */
    public function getImageUrl()
    {
        $image = $this->wellnessImage;
        return $image ? Yii::getAlias('@web/' . $image->image) : null;
    }

}
