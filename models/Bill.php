<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill".
 *
 * @property integer $id
 * @property integer $user_id_from
 * @property integer $user_id_to
 * @property integer $amount
 * @property string $status
 * @property string $operation_date
 *
 * @property User $userIdTo
 * @property User $userIdFrom
 */
class Bill extends \yii\db\ActiveRecord
{

    const STATUS_NEW = 'new';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELED = 'canceled';

    public static function tableName()
    {
        return 'bill';
    }

    public function rules()
    {
        return [

            // REQUIRED
            [
                [
                    'user_id_from',
                    'user_id_to',
                    'amount'
                ],
                'required'
            ],

            // INTEGER
            [
                [
                    'user_id_from',
                    'user_id_to',
                    'amount'
                ],
                'integer'
            ],

            // STRING
            [
                [
                    'status'
                ],
                'string'
            ],

            // SAVE
            [
                [
                    'operation_date'
                ],
                'safe'
            ],

            // EXISTS
            [
                ['user_id_to'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id_to' => 'id']
            ],
            [
                ['user_id_from'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id_from' => 'id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'user_id_from'   => 'Отправитель',
            'user_id_to'     => 'Получатель',
            'amount'         => 'Сумма',
            'status'         => 'Статус',
            'operation_date' => 'Дата',
        ];
    }

    public function getUserIdTo()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id_to']);
    }

    public function getUserIdFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id_from']);
    }
}
