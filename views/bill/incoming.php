<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Bill;
use app\models\bill\Status;
use app\models\service\SelectData;

$this->title = 'Входящие счета';
$this->params['breadcrumbs'][] = $this->title;

// SENDERS
$senders = SelectData::getBillSendersByRecipient(Yii::$app->user->id);

?>

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

                // SENDER
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'user_id_from',
                    'value' => function($model) {
                        return $model->userIdFrom->nickname;
                    },
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filter' => ['' => ''] + $senders,
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

                // ACTIONS
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{pay}&nbsp;&nbsp;{cancel}',
                    'dropdown' => false,
                    'vAlign'=>'middle',
                    'header' => 'Действия',
                    'buttons' => [
                        'pay' => function ($url, $model) {
                            if($model->status == Bill::STATUS_NEW) {
                                return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, [
                                            'title' => 'Оплатить',
                                ]);
                            }

                            return '';
                        },
                        'cancel' => function ($url, $model) {
                            if($model->status == Bill::STATUS_NEW) {
                                return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                                            'title' => 'Отменить',
                                ]);
                            }

                            return '';
                        },
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if($model->status == Bill::STATUS_NEW) {
                            if ($action === 'pay') {
                                $url = Url::to(['/bill/pay', 'id' => $model->id]);
                                return $url;
                            }
                            elseif ($action === 'cancel') {
                                $url = Url::to(['/bill/cancel', 'id' => $model->id]);
                                return $url;
                            }
                        }
                    }
                ]
            ],
        ]) ?>
    </div>
</div>
