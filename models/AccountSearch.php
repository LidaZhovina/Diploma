<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Booking;
use Yii;

/**
 * AccountSearch represents the model behind the search form of `app\models\Booking`.
 */
class AccountSearch extends Booking
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'room_id', 'status_booking_id', 'amount_residents'], 'integer'],
            [['arrival_date', 'departure_date', 'contact_phone', 'comment'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Booking::find()
            ->joinWith(['bookingUsers.resident'])
            ->where(['resident.user_id' => Yii::$app->user->id])
            ->distinct();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 3,
            ],
        ]);

        $this->load($params, $formName);

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
            'amount_residents' => $this->amount_residents,
        ]);

        $query->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
