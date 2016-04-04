<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "balance".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $amount
 * @property string $type
 * @property string $operation_date
 *
 * @property User $user
 */
class Balance extends \yii\db\ActiveRecord
{

    const TYPE_INCOME = 'income';
    const TYPE_OUTLAY = 'outlay';

    public static function tableName()
    {
        return 'balance';
    }

    public function rules()
    {
        return [

            // REQUIRED
            [
                [
                    'user_id',
                    'amount',
                    'type'
                ],
                'required'
            ],

            // INTEGER
            [
                [
                    'user_id',
                    'amount'
                ],
                'integer'
            ],

            // STRING
            [
                [
                    'type'
                ],
                'string'
            ],

            // SAFE
            [
                [
                    'operation_date'
                ],
                'safe'
            ],

            // EXISTS
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'user_id'        => 'Пользователь',
            'amount'         => 'Сумма',
            'type'           => 'Тип',
            'operation_date' => 'Дата',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
