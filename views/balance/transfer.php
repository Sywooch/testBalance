<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Перевод';

// BREADCRUMBS
$this->params['breadcrumbs'][] = [
    'label' => 'Баланс',
    'url'   => Url::to(['/balance'])
];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'transfer-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'nickname')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'amount')->textInput(['autofocus' => true]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Перевести', ['class' => 'btn btn-primary', 'name' => 'transfer-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
