<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\models\Balance;

class BalanceSearch extends Balance
{

    public function rules()
    {
        return [

            // INTEGER
            [
                [
                    'id',
                    'user_id',
                    'amount'
                ],
                'integer'
            ],

            // SAFE
            [
                [
                    'type',
                    'operation_date'
                ],
                'safe'
            ]
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Balance::find();
        $query->andFilterWhere(['user_id' => Yii::$app->user->id]);

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
            'id'     => $this->id,
            'amount' => $this->amount,
            'type'   => $this->type
        ]);

        if($this->operation_date) {
            $query->andFilterWhere(['=', new Expression('DATE(operation_date)'), date("Y-m-d", strtotime($this->operation_date))]);
        }

        return $dataProvider;
    }
}
