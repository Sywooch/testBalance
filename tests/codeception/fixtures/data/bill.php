<?php

return [

    // Новый счет
    [
        'id'           => 1,
        'user_id_from' => 2,
        'user_id_to'   => 1,
        'amount'       => 5,
        'status'       => app\models\Bill::STATUS_NEW
    ],

    // Чужой счет, наш пользователь ID=1
    [
        'id'           => 2,
        'user_id_from' => 1,
        'user_id_to'   => 2,
        'amount'       => 5,
        'status'       => app\models\Bill::STATUS_NEW
    ],

    // Оплачен, для проверки, что с ним нельзя больше ничего делать
    [
        'id'           => 3,
        'user_id_from' => 2,
        'user_id_to'   => 1,
        'amount'       => 5,
        'status'       => app\models\Bill::STATUS_PAID
    ],

    // Отменен, для проверки, что с ним нельзя больше ничего делать
    [
        'id'           => 4,
        'user_id_from' => 2,
        'user_id_to'   => 1,
        'amount'       => 5,
        'status'       => app\models\Bill::STATUS_CANCELED
    ],
];
