<?php

namespace app\models\service;

use app\models\User;
use app\models\Bill;

class SelectData
{

    public static function getBillRecipientsBySender($senderId)
    {

        // RECIPIENT IDS
        $rows = Bill::find()
                ->select([
                    'user_id_to',
                ])
                ->andFilterWhere(['user_id_from' => $senderId])
                ->asArray()
                ->all();
        if(!count($rows)) {
            return array();
        }

        $recipientIds = [];
        foreach($rows as $row) {
            $recipientIds[] = $row['user_id_to'];
        }
        $recipientIds = array_unique($recipientIds);

        // RECIPIENT ID=>NICKNAME
        $rows = User::find()
                ->select([
                    'id',
                    'nickname'
                ])
                ->andFilterWhere(['in', 'id', $recipientIds])
                ->asArray()
                ->all();
        if(!count($rows)) {
            return array();
        }

        $recipients = [];
        foreach($rows as $row) {
            $recipients[$row['id']] = $row['nickname'];
        }

        return $recipients;
    }

    public static function getBillSendersByRecipient($recipientId)
    {

        // RECIPIENT IDS
        $rows = Bill::find()
                ->select([
                    'user_id_from'
                ])
                ->andFilterWhere(['user_id_to' => $recipientId])
                ->asArray()
                ->all();
        if(!count($rows)) {
            return array();
        }

        $senderIds = [];
        foreach($rows as $row) {
            $senderIds[] = $row['user_id_from'];
        }
        $senderIds = array_unique($senderIds);

        // RECIPIENT ID=>NICKNAME
        $rows = User::find()
                ->select([
                    'id',
                    'nickname'
                ])
                ->andFilterWhere(['in', 'id', $senderIds])
                ->asArray()
                ->all();
        if(!count($rows)) {
            return array();
        }

        $senders = [];
        foreach($rows as $row) {
            $senders[$row['id']] = $row['nickname'];
        }

        return $senders;
    }
}
