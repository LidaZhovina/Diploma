<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Booking;

/**
 * ReseptionSearch represents the model behind the search form of `app\models\Booking`.
 */
class ReseptionSearch extends Booking
{
    public $fullname; // виртуальное поле для поиска по ФИО

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'room_id', 'status_booking_id', 'amount_residents'], 'integer'],
            [['arrival_date', 'departure_date', 'contact_phone', 'comment', 'fullname'], 'safe'],
            [['price'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '№',
            'fullname' => '',
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
    public function search($params, $activeTab = 'new')
    {
        $query = Booking::find()
            ->joinWith(['bookingUsers.resident'])
            ->groupBy('booking.id');

        // Фильтр по вкладке
        $statusId = Booking::getStatusId($activeTab);
        if ($statusId !== null) {
            $query->where(['booking.status_booking_id' => $statusId]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 3],
            'sort' => ['defaultOrder' => ['arrival_date' => SORT_ASC]],
        ]);

        $this->load($params, $formName ?? null);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'               => $this->id,
            'room_id'          => $this->room_id,
            'arrival_date'     => $this->arrival_date,
            'departure_date'   => $this->departure_date,
            'price'            => $this->price,
            'amount_residents' => $this->amount_residents,
        ]);

        $query->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        if ($this->fullname) {
            $query->andWhere([
                'or',
                ['like', 'resident.surname', $this->fullname],
                ['like', 'resident.name', $this->fullname],
                ['like', 'CONCAT(resident.surname, " ", resident.name)', $this->fullname],
            ]);
        }

        return $dataProvider;
    }
}
