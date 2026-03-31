<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wellness_image".
 *
 * @property int $id
 * @property int $wellness_id
 * @property string $image
 *
 * @property WellnessProgram $wellness
 */
class WellnessImage extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wellness_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wellness_id', 'image'], 'required'],
            [['wellness_id'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['wellness_id'], 'exist', 'skipOnError' => true, 'targetClass' => WellnessProgram::class, 'targetAttribute' => ['wellness_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wellness_id' => 'Wellness ID',
            'image' => 'Image',
        ];
    }

    /**
     * Gets query for [[Wellness]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWellness()
    {
        return $this->hasOne(WellnessProgram::class, ['id' => 'wellness_id']);
    }

}
