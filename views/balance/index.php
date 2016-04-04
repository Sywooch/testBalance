<?php

use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Balance;

$this->title = 'Баланс';
$this->params['breadcrumbs'][] = $this->title;

?>

<p>
    <a href="<?= Url::to(['balance/transfer']) ?>" class="btn btn-success">Перевод</a>
    <span>Мой баланс: <?= Yii::$app->user->identity->balance ?></span>
</p>

<div class="balance-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'export' => false,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => [

                // ID
                [
                     'class'=>'\kartik\grid\DataColumn',
                     'attribute'=>'id',
                ],

                // INCOME
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'amount',
                    'label' => 'Приход',
                    'value' => function($model) {
                        if($model->type == Balance::TYPE_INCOME) {
                            return $model->amount;
                        }

                        return '';
                    }
                ],

                // OUTLAY
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'amount',
                    'label' => 'Расход',
                    'value' => function($model) {
                        if($model->type == Balance::TYPE_OUTLAY) {
                            return $model->amount;
                        }

                        return '';
                    }
                ],

                // DATE
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'operation_date',
                    'filterType' => \kartik\grid\GridView::FILTER_DATE,
                    'filterWidgetOptions' => [
                        'removeButton' => [
                            'icon' => 'trash',
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                    ],
                    'filterInputOptions'  => ['placeholder' => ''],
                    'options' => [
                        'style' => 'width:200px;'
                    ]
                ],
            ],
        ]) ?>
    </div>
</div>
