<?php

namespace tests\codeception\_support;

use tests\codeception\fixtures\UserFixture;
use tests\codeception\fixtures\BillFixture;
use Codeception\Module;
use yii\test\FixtureTrait;
use yii\test\InitDbFixture;

/**
 * This helper is used to populate the database with needed fixtures before any tests are run.
 * In this example, the database is populated with the demo login user, which is used in acceptance
 * and functional tests.  All fixtures will be loaded before the suite is started and unloaded after it
 * completes.
 */
class FixtureHelper extends Module
{
    /**
     * Redeclare visibility because codeception includes all public methods that do not start with "_"
     * and are not excluded by module settings, in actor class.
     */
    use FixtureTrait {
        loadFixtures as protected;
        fixtures as protected;
        globalFixtures as protected;
        unloadFixtures as protected;
        getFixtures as protected;
        getFixture as protected;
    }

    public function _beforeSuite($settings = [])
    {
        $this->loadFixtures();
    }

    public function _afterSuite()
    {
        $this->unloadFixtures();
    }

    public function globalFixtures()
    {
        return [
            InitDbFixture::className(),
        ];
    }

    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/fixtures/data/user.php',
            ],
            'bill' => [
                'class' => BillFixture::className(),
                'dataFile' => '@tests/codeception/fixtures/data/bill.php',
            ],
        ];
    }
}