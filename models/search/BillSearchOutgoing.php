<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\models\Bill;

class BillSearchOutgoing extends Bill
{

    public function rules()
    {
        return [

            // INTEGER
            [
                [
                    'id',
                    'user_id_from',
                    'user_id_to',
                    'amount'
                ],
                'integer'
            ],

            // SAFE
            [
                [
                    'status',
                    'operation_date'
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
        $query = Bill::find()->with(['userIdTo']);
        $query->andFilterWhere(['user_id_from' => Yii::$app->user->id]);

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
            'id'         => $this->id,
            'user_id_to' => $this->user_id_to,
            'amount'     => $this->amount,
            'status'     => $this->status
        ]);

        if($this->operation_date) {
            $query->andFilterWhere(['=', new Expression('DATE(operation_date)'), date("Y-m-d", strtotime($this->operation_date))]);
        }

        return $dataProvider;
    }
}
