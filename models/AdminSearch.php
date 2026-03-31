<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Booking;

/**
 * AdminSearch represents the model behind the search form of `app\models\Booking`.
 */
class AdminSearch extends Booking
{
     public $wellness_program_id;
     public $route_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'room_id', 'status_booking_id', 'wellness_program_id', 'route_id', 'amount_residents'], 'integer'],
            [['arrival_date', 'departure_date', 'comment'], 'safe'],
            [['price'], 'number'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Booking::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'room_id' => $this->room_id,
            'arrival_date' => $this->arrival_date,
            'departure_date' => $this->departure_date,
            'price' => $this->price,
            'status_booking_id' => $this->status_booking_id,
            'wellness_program_id' => $this->wellness_program_id,
            'route_id' => $this->route_id,
            'amount_residents' => $this->amount_residents,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
