<?php

namespace tests\codeception\unit\models;

use Yii;
use yii\codeception\TestCase;
use tests\codeception\fixtures\UserFixture;
use app\models\auth\SignupForm;

class SignupFormTest extends TestCase
{

    protected $signupForm;

    protected function setUp()
    {
        parent::setUp();
        $this->signupForm = new SignupForm();
    }

    protected function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }

    public function testValidateEmpty()
    {
        $this->signupForm->nickname = '';
        $this->assertFalse($this->signupForm->validate(), 'validate empty nickname');
        $this->assertArrayHasKey('nickname', $this->signupForm->getErrors());
    }

    public function testValidateRememberIsNotBoolean()
    {
        $this->signupForm->nickname   = 'user1';
        $this->signupForm->rememberMe = '123';
        $this->assertFalse($this->signupForm->validate(), 'validate not boolean rememberMe');
        $this->assertArrayHasKey('rememberMe', $this->signupForm->getErrors());
    }

    public function testSignupByExistsUser()
    {
        $this->signupForm->nickname = $this->user[0]['nickname'];
        $this->signupForm->rememberMe = 1;
        $this->assertFalse($this->signupForm->validate(), 'validate signup by exists user');
    }

    public function testSignupSuccess()
    {
        $this->signupForm->nickname = 'user3';
        $this->signupForm->rememberMe = 1;
        $this->assertInstanceOf('\app\models\User', $this->signupForm->signup());
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
