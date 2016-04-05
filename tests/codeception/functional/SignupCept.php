<?php

use tests\codeception\_pages\SignupPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that signup works');

$signupPage = SignupPage::openBy($I);

$I->see('Регистрация', 'h1');

// EMPTY CREDENTIALS
$I->amGoingTo('try to login with empty credentials');
$signupPage->signup('');
$I->expectTo('see validations errors');
$I->see('Введите ник');

// WRONG CREDENTIALS
$I->amGoingTo('try to login with wrong credentials');
$signupPage->signup('user1');
$I->expectTo('see validations errors');
$I->see('Ник занят');

// CORRECT CREDENTIALS
$I->amGoingTo('try to login with correct credentials');
$signupPage->signup('user3');
$I->expectTo('see user info');
$I->see('Выход (user3)');
$I->see('Баланс');
$I->see('Счета');
