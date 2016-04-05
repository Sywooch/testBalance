<?php

use tests\codeception\_pages\LoginPage;
use tests\codeception\_pages\BillSendPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that bill works');

$loginPage = LoginPage::openBy($I);

// LOGIN
$I->amGoingTo('login');
$loginPage->login('user1');
$I->expectTo('see user info');
$I->see('Выход (user1)');
$I->see('Баланс');
$I->see('Счета');

// DROP DOWN BILL LINGS
$I->click('Счета');
$I->see('Исходящие');
$I->see('Входящие');

// GO TO INCOMING
$I->click('Входящие');
$I->see('Новый');
$I->click('//a[@title="Оплатить"]');
$I->see('Счет оплачен');

// GO TO OUTGOING
$I->click('Счета');
$I->click('Исходящие');
$I->see('Выставить счет');

// SEND THE BILL
$I->click('Выставить счет');
$I->see('Выставление счета');

$billSendPage = BillSendPage::openBy($I);

// WITH EMPTY VALUES
$billSendPage->send('', '');
$I->see('Введите ник');
$I->see('Введите сумму');

// WITH CORRECT VALUES
$billSendPage->send('user2', 5);
$I->see('Счет выставлен');
