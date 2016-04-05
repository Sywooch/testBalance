<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

class BillSendPage extends BasePage
{
    public $route = '/bill/send-bill';

    public function send($nickname, $amount)
    {
        $this->actor->fillField('BillForm[nickname]', $nickname);
        $this->actor->fillField('BillForm[amount]', $amount);
        $this->actor->click('send-bill-button');
    }
}
