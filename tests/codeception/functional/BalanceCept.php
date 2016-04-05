<?php

use tests\codeception\_pages\LoginPage;
use tests\codeception\_pages\TransferPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that login works');

$loginPage = LoginPage::openBy($I);

// LOGIN
$I->amGoingTo('login');
$loginPage->login('user1');
$I->expectTo('see user info');
$I->see('Выход (user1)');
$I->see('Баланс');
$I->see('Счета');

// GO TO BALANCE
$I->click('Баланс');
$I->see('Перевод');
$I->see('Мой баланс');

// TRY TO DO TRANSFER
$I->click('Перевод');
$I->see('Перевод');

$transferPage = TransferPage::openBy($I);

// WITH EMPTY VALUES
$I->amGoingTo('Try transfer with empty values');
$transferPage->transfer('', '');
$I->expectTo('see validation errors');
$I->see('Введите ник');
$I->see('Введите сумму');

// AMOUNT IS NOT DIGIT
$I->amGoingTo('Put amount not digit');
$transferPage->transfer('user2', 'amount_not_digit');
$I->expectTo('see validation errors');
$I->see('Сумма должна быть целым числом');

// TRY TRANSFER TO ME
$I->amGoingTo('Make transfer to me');
$transferPage->transfer('user1', 5);
$I->expectTo('see validation errors');
$I->see('Нельзя делать себе перевод');

// CORRECT VALUES
$I->amGoingTo('Try transfer with correct values');
$transferPage->transfer('user2', 5);
$I->expectTo('transfer success message');
$I->see('Перевод успешно совершен');
