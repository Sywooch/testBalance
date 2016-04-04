<?php

use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\bill\Status;
use app\models\service\SelectData;

$this->title = 'Исходящие счета';
$this->params['breadcrumbs'][] = $this->title;

// RECIPIENTS
$recipients = SelectData::getBillRecipientsBySender(Yii::$app->user->id);

?>

<p>
    <a href="<?= Url::to(['/bill/send-bill']) ?>" class="btn btn-success">Выставить счет</a>
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

                // RECIPIENT
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'user_id_to',
                    'value' => function($model) {
                        return $model->userIdTo->nickname;
                    },
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filter' => ['' => ''] + $recipients,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => TRUE]
                    ],
                    'options' => [
                        'style' => 'width:190px;',
                    ],
                    'filterInputOptions'  => ['placeholder' => ''],
                ],

                // AMOUNT
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'amount',
                ],

                // STATUS
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'status',
                    'value' => function($model) {
                        return Status::getStatusName($model->status);
                    },
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filter' => ['' => ''] + Status::getStatusList(),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => TRUE]
                    ],
                    'options' => [
                        'style' => 'width:190px;',
                    ],
                    'filterInputOptions'  => ['placeholder' => ''],
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
