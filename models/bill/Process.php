<?php

namespace app\models\bill;

use Yii;
use app\models\Bill;
use app\models\Balance;

class Process
{

    public function pay($billId)
    {
        $bill = Bill::findOne(['id' => $billId]);
        if(NULL == $bill) {
            throw new \Exception('Неверно указан счет для оплаты');
        }

        $checkRecipient = $this->checkRecipient($bill->user_id_to);
        if(!$checkRecipient) {
            throw new \Exception('Нельзя оплачивать чужой счет');
        }

        if($bill->status != Bill::STATUS_NEW) {
            throw new \Exception('С этим счетом нельзя проводить операции');
        }

        $db = Yii::$app->db;
        $amount = (int) $bill->amount;

        // DECREASE RECIPIENT BALANCE
        $userId = $bill->user_id_to;
        $db->createCommand("UPDATE user SET balance = balance - :amount WHERE id = :user_id")
                ->bindParam('amount', $amount)
                ->bindParam('user_id', $userId)
                ->execute();

        // INCREASE SENDER BALANCE
        $userId = $bill->user_id_from;
        $db->createCommand("UPDATE user SET balance = balance + :amount WHERE id = :user_id")
                ->bindParam('amount', $amount)
                ->bindParam('user_id', $userId)
                ->execute();

        // RECIPIENT BALANCE HISTORY
        $userId = $bill->user_id_to;
        $type   = Balance::TYPE_OUTLAY;
        $db->createCommand("INSERT INTO balance (user_id, amount, type) VALUES (:user_id, :amount, :type)")
                ->bindParam('user_id', $userId)
                ->bindParam('amount', $amount)
                ->bindParam('type', $type)
                ->execute();

        // SENDER BALANCE HISTORY
        $userId = $bill->user_id_from;
        $type   = Balance::TYPE_INCOME;
        $db->createCommand("INSERT INTO balance (user_id, amount, type) VALUES (:user_id, :amount, :type)")
                ->bindParam('user_id', $userId)
                ->bindParam('amount', $amount)
                ->bindParam('type', $type)
                ->execute();

        // BILL CHANGE STATUS
        $bill->status = Bill::STATUS_PAID;
        $bill->save();
    }

    public function cancel($billId)
    {
        $bill = Bill::findOne(['id' => $billId]);
        if(NULL == $bill) {
            throw new \Exception('Неверно указан счет для отмены');
        }

        $checkRecipient = $this->checkRecipient($bill->user_id_to);
        if(!$checkRecipient) {
            throw new \Exception('Нельзя отменять чужой счет');
        }

        if($bill->status != Bill::STATUS_NEW) {
            throw new \Exception('С этим счетом нельзя проводить операции');
        }

        // BILL CHANGE STATUS
        $bill->status = Bill::STATUS_CANCELED;
        $bill->save();
    }

    protected function checkRecipient($recipientId)
    {
        if($recipientId == Yii::$app->user->id) {
            return true;
        }

        return false;
    }
}
