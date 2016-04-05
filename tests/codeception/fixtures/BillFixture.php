<?php

namespace tests\codeception\fixtures;

use yii\test\ActiveFixture;

class BillFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Bill';
    public $dataFile = '@tests/codeception/fixtures/data/bill.php';
}
