<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

class LoginPage extends BasePage
{
    public $route = 'auth/login';

    public function login($nickname)
    {
        $this->actor->fillField('input[name="LoginForm[nickname]"]', $nickname);
        $this->actor->click('login-button');
    }
}
