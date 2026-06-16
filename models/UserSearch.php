<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    public $fio;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'role_id'], 'integer'],
            [['email', 'password', 'surname', 'name', 'patronymic', 'fio', 'auth_key'], 'safe'],
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
    public function search($params): ActiveDataProvider
    {
        $query = User::find()->with('role');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['surname' => SORT_ASC]],
        ]);

        $this->load($params);

        $query->andFilterWhere(['role_id' => $this->role_id ?: null]);

        if ($this->fio) {
            $query->andWhere([
                'or',
                ['like', 'surname', $this->fio],
                ['like', 'name',    $this->fio],
                ['like', 'email',   $this->fio],
            ]);
        }

        return $dataProvider;
    }
}
