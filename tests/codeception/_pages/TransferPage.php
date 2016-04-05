<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

class TransferPage extends BasePage
{
    public $route = 'balance/transfer';

    public function transfer($nickname, $amount)
    {
        $this->actor->fillField('input[name="TransferForm[nickname]"]', $nickname);
        $this->actor->fillField('input[name="TransferForm[amount]"]', $amount);
        $this->actor->click('transfer-button');
    }
}
