<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Room;
use app\models\Booking;

/**
 * CatalogSearch represents the model behind the search form of `app\models\Room`.
 */
class CatalogSearch extends Room
{
    public $arrival_date;
    public $departure_date;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        return [
            [['id', 'room_type_id', 'number', 'floor', 'status_room_id', 'price_per_day', 'number_guests'], 'integer'],
            [['description', 'arrival_date', 'departure_date'], 'safe'],
            [['arrival_date', 'departure_date'], 'date', 'format' => 'php:Y-m-d'],
            [
                'arrival_date',
                'compare',
                'compareValue'   => $tomorrow,
                'operator'       => '>=',
                'type'           => 'date',
                'message'        => 'Дата заезда не может быть раньше ' . $tomorrow,
                'skipOnEmpty'    => true,
            ],
            [
                'departure_date',
                'compare',
                'compareAttribute' => 'arrival_date',
                'operator'         => '>',
                'type'             => 'date',
                'message'          => 'Дата выезда должна быть позже даты заезда',
                'skipOnEmpty'      => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Room::find()->with('roomType', 'roomImages');;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        // $query->andFilterWhere([
        //     'id' => $this->id,
        //     'room_type_id' => $this->room_type_id,
        //     'number' => $this->number,
        //     'floor' => $this->floor,
        //     'status_room_id' => $this->status_room_id,
        //     'price_per_day' => $this->price_per_day,
        //     'number_guests' => $this->number_guests,
        // ]);

        // $query->andFilterWhere(['like', 'description', $this->description]);

        $query->andFilterWhere(['room_type_id' => $this->room_type_id]);

        if (!empty($this->arrival_date) && !empty($this->departure_date)) {
            $allRooms = (clone $query)->select('id')->column();

            $availableIds = array_filter($allRooms, function ($roomId) {
                return Booking::isAvailable($roomId, $this->arrival_date, $this->departure_date);
            });

            $query->andWhere(['id' => array_values($availableIds)]);
        }

        // $query->joinWith('roomType')
        //     ->orderBy([
        //         'room_type.id'       => SORT_ASC,   // класс: эконом → стандарт → премиум
        //         'room.number_guests' => SORT_ASC,   // места: 1 → 2 → 3...
        //     ]);

        return $dataProvider;
    }
}
