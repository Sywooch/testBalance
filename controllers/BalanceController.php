<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\balance\TransferForm;
use app\models\search\BalanceSearch;

class BalanceController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    return $this->redirect(Url::to(['auth/login']));
                }
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new BalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTransfer()
    {
        $model = new TransferForm();
        if($model->load(Yii::$app->request->post()) && $model->transfer()) {
            Yii::$app->getSession()->setFlash('success', 'Перевод успешно совершен');
            return $this->redirect(Url::to(['/balance/']));
        }

        return $this->render('transfer', [
            'model' => $model
        ]);
    }
}