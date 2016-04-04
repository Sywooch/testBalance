<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\bill\BillForm;
use app\models\bill\Process;
use app\models\search\BillSearchIncoming;
use app\models\search\BillSearchOutgoing;

class BillController extends Controller
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
        return $this->redirect(Url::to(['bill/incoming']));
    }

    public function actionIncoming()
    {
        $searchModel = new BillSearchIncoming();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('incoming', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOutgoing()
    {
        $searchModel = new BillSearchOutgoing();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('outgoing', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSendBill()
    {
        $model = new BillForm();
        if($model->load(Yii::$app->request->post()) && $model->sendBill()) {
            Yii::$app->getSession()->setFlash('success', 'Счет выставлен');
            return $this->redirect(Url::to(['/bill/outgoing']));
        }

        return $this->render('send-bill', [
            'model' => $model
        ]);
    }

    public function actionPay($id)
    {
        try {
            $billProcess = new Process();
            $billProcess->pay($id);
            Yii::$app->getSession()->setFlash('success', 'Счет оплачен');
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
        }

        return $this->redirect(Url::to(['/bill/incoming']));
    }

    public function actionCancel($id)
    {
        try {
            $billProcess = new Process();
            $billProcess->cancel($id);
            Yii::$app->getSession()->setFlash('success', 'Счет отменен');
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
        }

        return $this->redirect(Url::to(['/bill/incoming']));
    }
}
