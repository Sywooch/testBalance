<?php

namespace tests\codeception\unit\models;

use Yii;
use yii\codeception\TestCase;
use tests\codeception\fixtures\UserFixture;
use app\models\User;
use app\models\bill\BillForm;

class BillFormTest extends TestCase
{

    protected $billForm;

    public function setUp()
    {

        parent::setUp();

        $user = User::findByUsername($this->user[0]['nickname']);
        Yii::$app->user->login($user);

        $this->billForm = new BillForm();
    }

    public function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }

    public function testValidateEmpty()
    {
        $this->billForm->nickname = '';
        $this->billForm->amount = '';
        $this->assertFalse($this->billForm->validate(), 'validate empty values');
        $this->assertArrayHasKey('nickname', $this->billForm->getErrors(), 'check empty nickname error');
        $this->assertArrayHasKey('amount', $this->billForm->getErrors(), 'check empty amount error');
    }

    public function testValidateAmountNotDigit()
    {
        $this->billForm->nickname = $this->user[1]['nickname'];
        $this->billForm->amount = 'amount_not_digit';
        $this->assertFalse($this->billForm->validate(), 'validate amount not digit values');
        $this->assertArrayHasKey('amount', $this->billForm->getErrors(), 'check amount not digit error');
    }

    public function testBillToMe()
    {
        $this->billForm->nickname = $this->user[0]['nickname'];
        $this->billForm->amount = 10;
        $this->assertFalse($this->billForm->validate(), 'can not send money yoursenf');
    }

    public function testBillSuccess()
    {
        $this->billForm->nickname = $this->user[1]['nickname'];
        $this->billForm->amount = 10;
        $this->assertTrue($this->billForm->validate(), 'validate bill success');
        $this->assertTrue($this->billForm->sendBill(), 'send bill success');
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
