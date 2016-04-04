<?php

namespace app\models\bill;

use Yii;
use app\models\Bill;

class Status
{
    public static function getStatusName($statusCode)
    {
        $status = '';
        switch($statusCode) {
            case Bill::STATUS_NEW:
                $status = 'Новый';
                break;
            case Bill::STATUS_PAID:
                $status = 'Оплачен';
                break;
            case Bill::STATUS_CANCELED:
                $status = 'Отменен';
                break;
        }

        return $status;
    }

    public static function getStatusList()
    {
        return [
            Bill::STATUS_NEW      => 'Новый',
            Bill::STATUS_PAID     => 'Оплачен',
            Bill::STATUS_CANCELED => 'Отменен',
        ];
    }
}
