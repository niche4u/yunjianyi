<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Backend controller
 */
class BackendController extends Controller
{

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if(Yii::$app->controller->action->id != 'login' && Yii::$app->controller->action->id != 'logout') {
            if(Yii::$app->user->identity->role != 20) {
                Yii::$app->user->logout();
                $this->redirect(Yii::$app->params['domain']);
            }
        }

        return true; // or false to not run the action
    }

}

