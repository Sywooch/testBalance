<?php

use tests\codeception\_pages\LoginPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that login works');

$loginPage = LoginPage::openBy($I);

$I->see('Вход', 'h1');

// EMPTY CREDENTIALS
$I->amGoingTo('try to login with empty credentials');
$loginPage->login('', '');
$I->expectTo('see validations errors');
$I->see('Введите ник');

// WRONG CREDENTIALS
$I->amGoingTo('try to login with wrong credentials');
$loginPage->login('user3');
$I->expectTo('see validations errors');
$I->see('Пользователь не найден');

// CORRECT CREDENTIALS
$I->amGoingTo('try to login with correct credentials');
$loginPage->login('user1');
$I->expectTo('see user info');
$I->see('Выход (user1)');
$I->see('Баланс');
$I->see('Счета');
