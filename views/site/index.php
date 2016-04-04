<?php

use kartik\grid\GridView;

$this->title = 'Главная';

?>
<div class="site-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'export' => false,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => [
                [
                     'class'=>'\kartik\grid\DataColumn',
                     'attribute'=>'id',
                ],
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'nickname',
                ],
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'balance'
                ],
            ],
        ]) ?>
    </div>
</div>
