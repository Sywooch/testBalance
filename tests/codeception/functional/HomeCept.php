<?php

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('Проект "Баланс"');
$I->seeLink('Вход');
$I->click('Вход');
$I->see('Вход');
$I->see('Регистрация');
