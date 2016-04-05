<?php

namespace tests\codeception\unit\models;

use Yii;
use yii\codeception\TestCase;
use tests\codeception\fixtures\UserFixture;
use app\models\auth\LoginForm;

class LoginFormTest extends TestCase
{

    protected $loginForm;

    protected function setUp()
    {
        parent::setUp();
        $this->loginForm = new LoginForm();
    }

    protected function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }

    public function testValidateEmpty()
    {
        $this->loginForm->nickname = '';
        $this->assertFalse($this->loginForm->validate(), 'validate empty nickname');
        $this->assertArrayHasKey('nickname', $this->loginForm->getErrors());
    }

    public function testValidateRememberIsNotBoolean()
    {
        $this->loginForm->nickname   = 'user1';
        $this->loginForm->rememberMe = '123';
        $this->assertFalse($this->loginForm->validate(), 'validate not boolean rememberMe');
        $this->assertArrayHasKey('rememberMe', $this->loginForm->getErrors());
    }

    public function testLoginByNotExistsUser()
    {
        $this->loginForm->nickname = '';
        $this->loginForm->rememberMe = 1;
        $this->assertFalse($this->loginForm->validate(), 'validate not exists user');
        $this->assertArrayHasKey('nickname', $this->loginForm->getErrors());
    }

    public function testLoginSuccess()
    {
        $this->loginForm->nickname   = 'user1';
        $this->loginForm->rememberMe = 1;
        $this->assertTrue($this->loginForm->validate(), 'login success');
    }

    public function fixtures() {

        Yii::$app->db->createCommand('TRUNCATE TABLE user')->execute();
        Yii::$app->db->createCommand('TRUNCATE TABLE balance')->execute();
        Yii::$app->db->createCommand('TRUNCATE TABLE bill')->execute();

        return [
            'user' => [
                'class' => UserFixture::className()
            ]
        ];
    }
}
