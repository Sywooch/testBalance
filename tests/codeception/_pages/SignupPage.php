<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

class SignupPage extends BasePage
{
    public $route = 'auth/signup';

    public function signup($nickname)
    {
        $this->actor->fillField('input[name="SignupForm[nickname]"]', $nickname);
        $this->actor->click('signup-button');
    }
}
