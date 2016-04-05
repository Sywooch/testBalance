<?php

namespace tests\codeception\unit\models;

use Yii;
use yii\codeception\TestCase;
use tests\codeception\fixtures\UserFixture;
use tests\codeception\fixtures\BillFixture;
use app\models\User;
use app\models\bill\Process;

class BillProcessTest extends TestCase
{

    protected $process;

    public function setUp()
    {

        parent::setUp();

        $this->process = new Process();

        $user = User::findByUsername($this->user[0]['nickname']);
        Yii::$app->user->login($user);
    }

    public function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }

    /**
     * @expectedException \Exception
     */
    public function testPayNotExistsBill()
    {
        $this->process->pay(5);
    }

    /**
     * @expectedException \Exception
     */
    public function testCancelNotExistsBill()
    {
        $this->process->cancel(5);
    }

    /**
     * @expectedException \Exception
     */
    public function testPayNotMyBill()
    {
        $this->process->pay(2);
    }

    /**
     * @expectedException \Exception
     */
    public function testCancelNotMyBill()
    {
        $this->process->cancel(2);
    }

    /**
     * @expectedException \Exception
     */
    public function testPayAlreadyPaid()
    {
        $this->process->pay(3);
    }

    /**
     * @expectedException \Exception
     */
    public function testPayAlreadyCanceled()
    {
        $this->process->pay(4);
    }

    /**
     * @expectedException \Exception
     */
    public function testCancelAlreadyPaid()
    {
        $this->process->cancel(3);
    }

    /**
     * @expectedException \Exception
     */
    public function testCancelAlreadyCanceled()
    {
        $this->process->cancel(4);
    }

    public function testPaySuccess()
    {
        $this->process->pay(1);
        $this->assertTrue(true);
    }

    public function testCancelSuccess()
    {
        $this->process->cancel(1);
        $this->assertTrue(true);
    }

    public function fixtures() {

        Yii::$app->db->createCommand('TRUNCATE TABLE user')->execute();
        Yii::$app->db->createCommand('TRUNCATE TABLE balance')->execute();
        Yii::$app->db->createCommand('TRUNCATE TABLE bill')->execute();

        return [
            'user' => [
                'class' => UserFixture::className()
            ],
            'bill' => [
                'class' => BillFixture::className()
            ]
        ];
    }
}
