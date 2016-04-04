<?php

namespace app\models\bill;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Bill;

class BillForm extends Model
{
    public $nickname;
    public $amount;

    protected $recipient = false;

    public function rules()
    {
        return [

            // REQUIRED
            [['nickname'], 'required', 'message' => 'Введите ник'],
            [['amount'], 'required', 'message' => 'Введите сумму'],

            // INTEGER
            [['amount'], 'integer', 'message' => 'Сумма должна быть целым числом'],

            // USER EXISTS
            ['nickname', 'validateNickname'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nickname'    => 'Ник',
            'amount'      => 'Сумма',
        ];
    }

    public function validateNickname($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $recipient = $this->getRecipient();
            if (!$recipient) {
                $this->addError($attribute, 'Пользователь не найден');
            }
            if($recipient->id == Yii::$app->user->id) {
                $this->addError($attribute, 'Нельзя выставлять себе счет');
            }
        }
    }

    public function getRecipient()
    {
        if ($this->recipient === false) {
            $this->recipient = User::findByUsername($this->nickname);
        }

        return $this->recipient;
    }

    public function sendBill()
    {

        if (!$this->validate()) {
            return false;
        }

        // SEND BILL
        $bill = new Bill();
        $bill->user_id_from = Yii::$app->user->id;
        $bill->user_id_to   = $this->recipient->id;
        $bill->amount       = (int) $this->amount;
        $bill->status       = Bill::STATUS_NEW;
        $bill->save();

        return true;
    }
}
