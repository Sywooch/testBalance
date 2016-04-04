<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\search\UserSearch;

class SiteController extends Controller
{

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return '';
        }

        // STATUS CODE
        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        }
        else {
            $code = $exception->getCode();
        }

        // ERROR NAME
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        }
        else {
            if(isset($exception->statusCode) && $exception->statusCode == 404) {
                $name = '404';
            }
            else {
                $name = 'Ошибка';
            }
        }

        // MESSAGE
        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        }
        else {
            if(isset($exception->statusCode) && $exception->statusCode == 404) {
                $message = 'Страница не найдена';
            }
            else {
                $message = 'Произошла ошибка';
            }
        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        }

        return $this->render('error', [
            'name'      => $name,
            'message'   => $message,
            'exception' => $exception,
        ]);
    }
}
