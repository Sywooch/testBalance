<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

class UserSearch extends User
{

    public function rules()
    {
        return [

            // INTEGER
            [
                [
                    'id',
                    'balance'
                ],
                'integer'
            ],

            // SAFE
            [
                [
                    'nickname'
                ],
                'safe'
            ],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'balance' => $this->balance
        ]);

        $query->andFilterWhere(['like', 'nickname', $this->nickname]);

        return $dataProvider;
    }
}
