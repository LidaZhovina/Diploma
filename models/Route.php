<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "route".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $length
 * @property string $duration
 * @property string $outfit
 * @property string $date_start
 * @property string $time_start
 * @property int $number_participant
 * @property int $level_id
 * @property int $price
 * @property float $stars 
 * @property int $route_status
 * @property string $created_at
 *
 * @property Level $level
 * @property Review[] $reviews
 * @property RouteImage $routeImage
 * @property RouteResident[] $routeResidents 
 * @property RouteStatus $routeStatus
 */
class Route extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'route';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'length', 'duration', 'outfit', 'date_start', 'time_start', 'number_participant', 'level_id', 'price', 'route_status'], 'required'],
            [['description', 'outfit'], 'string'],
            [['length', 'number_participant', 'level_id', 'price', 'route_status'], 'integer'],
            [['date_start', 'time_start', 'created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['stars'], 'number'],
            [['duration'], 'string', 'max' => 25],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => Level::class, 'targetAttribute' => ['level_id' => 'id']],
            [['route_status'], 'exist', 'skipOnError' => true, 'targetClass' => RouteStatus::class, 'targetAttribute' => ['route_status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'length' => 'Протяженность',
            'date_start' => 'Дата',
            'time_start' => 'Время начала',
            'duration' => 'Продолжительность',
            'outfit' => 'Рекомендуемая экипировка',
            'number_participant' => 'Количество участников',
            'level_id' => 'Сложность',
            'created_at' => 'Дата создания',
            'price' => 'Цена',
            'stars' => 'Рейтинг',
            'route_status' => 'Статус',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::class, ['route_id' => 'id']);
    }

    /**
     * Gets query for [[Level]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::class, ['id' => 'level_id']);
    }

    /** 
     * Gets query for [[Reviews]]. 
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['route_id' => 'id']);
    }

    /**
     * Gets query for [[RouteImage]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRouteImage()
    {
        return $this->hasOne(RouteImage::class, ['route_id' => 'id']);
    }

    /** 
     * Получить путь к изображению (для удобства в представлениях) 
     */
    public function getImageUrl()
    {
        $image = $this->routeImage;
        return $image ? Yii::getAlias('@web/' . $image->image) : null;
    }

    /**
     * Gets query for [[RouteResidents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRouteResidents()
    {
        return $this->hasMany(RouteResident::class, ['route_id' => 'id']);
    }

    /** 
     * Gets query for [[RouteStatus]]. 
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getRouteStatus()
    {
        return $this->hasOne(RouteStatus::class, ['id' => 'route_status']);
    }
}
