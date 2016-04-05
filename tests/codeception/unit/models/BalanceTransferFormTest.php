<?php

namespace tests\codeception\unit\models;

use Yii;
use yii\codeception\TestCase;
use tests\codeception\fixtures\UserFixture;
use app\models\balance\TransferForm;
use app\models\User;

class BalanceTransferFormTest extends TestCase
{

    protected $transferForm;

    public function setUp()
    {
        parent::setUp();

        $user = User::findByUsername($this->user[0]['nickname']);
        Yii::$app->user->login($user);

        $this->transferForm = new TransferForm();
    }

    public function tearDown()
    {
        $this->transferForm = NULL;
        Yii::$app->user->logout();

        parent::tearDown();
    }

    public function testValidateEmpty()
    {
        $this->transferForm->nickname = '';
        $this->transferForm->amount = '';
        $this->assertFalse($this->transferForm->validate(), 'validate empty values');
        $this->assertArrayHasKey('nickname', $this->transferForm->getErrors(), 'check empty nickname error');
        $this->assertArrayHasKey('amount', $this->transferForm->getErrors(), 'check empty amount error');
    }

    public function testValidateAmountNotDigit()
    {
        $this->transferForm->nickname = $this->user[1]['nickname'];
        $this->transferForm->amount = 'amount_not_digit';
        $this->assertFalse($this->transferForm->validate(), 'validate amount not digit values');
        $this->assertArrayHasKey('amount', $this->transferForm->getErrors(), 'check amount not digit error');
    }

    public function testTransferToMeWrong()
    {
        $this->transferForm->nickname = $this->user[0]['nickname'];
        $this->transferForm->amount = 10;
        $this->assertFalse($this->transferForm->validate(), 'can not transfer to me');
        $this->assertArrayHasKey('nickname', $this->transferForm->getErrors(), 'check nickname can not transfer to me error');
    }

    public function testTransferSuccess()
    {
        $this->transferForm->nickname = $this->user[1]['nickname'];
        $this->transferForm->amount = 10;
        $this->assertTrue($this->transferForm->validate(), 'transfer form is valid');
        $this->assertTrue($this->transferForm->transfer(), 'transfer success');
    }

    public function testTransferToNotExistsUser()
    {
        $this->transferForm->nickname = 'user3';
        $this->transferForm->amount = 10;
        $this->assertTrue($this->transferForm->validate(), 'transfer form is valid');
        $this->assertTrue($this->transferForm->transfer(), 'transfer success to not exists user');
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
