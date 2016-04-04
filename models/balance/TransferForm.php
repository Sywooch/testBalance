<?php

namespace app\models\balance;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Balance;

class TransferForm extends Model
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

            // USER IS NOT ME
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
            if($recipient && $recipient->id == Yii::$app->user->id) {
                $this->addError($attribute, 'Нельзя делать себе перевод');
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

    public function transfer()
    {

        if (!$this->validate()) {
            return false;
        }

        $db = Yii::$app->db;
        $amount = (int) $this->amount;

        // INCREASE USER BALANCE
        if(!$this->recipient) {
            $recipient = new User();
            $recipient->nickname = $this->nickname;
            $recipient->balance  = $this->amount;
            $recipient->generateAuthKey();
            $recipient->save();
        }
        else {
            $recipient = $this->recipient;
            $userId = $recipient->id;
            $db->createCommand("UPDATE user SET balance = balance + :amount WHERE id = :user_id")
                    ->bindParam('amount', $amount)
                    ->bindParam('user_id', $userId)
                    ->execute();
        }

        // DECREASE MY BALANCE
        $userId = Yii::$app->user->id;
        $db->createCommand("UPDATE user SET balance = balance - :amount WHERE id = :user_id")
                ->bindParam('amount', $amount)
                ->bindParam('user_id', $userId)
                ->execute();

        // USER BALANCE HISTORY
        $userId = $recipient->id;
        $type   = Balance::TYPE_INCOME;
        $db->createCommand("INSERT INTO balance (user_id, amount, type) VALUES (:user_id, :amount, :type)")
                ->bindParam('user_id', $userId)
                ->bindParam('amount', $amount)
                ->bindParam('type', $type)
                ->execute();

        // MY BALANCE HISTORY
        $userId = Yii::$app->user->id;
        $type   = Balance::TYPE_OUTLAY;
        $db->createCommand("INSERT INTO balance (user_id, amount, type) VALUES (:user_id, :amount, :type)")
                ->bindParam('user_id', $userId)
                ->bindParam('amount', $amount)
                ->bindParam('type', $type)
                ->execute();

        return true;
    }
}
