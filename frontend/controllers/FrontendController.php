<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\components\Helper;

/**
 * Frontend controller
 */
class FrontendController extends Controller
{
    public $title = '';
    public $description = '';
    public $keyword = '';
    public $bg = null;
    public $bg_color = null;
    public $agent = null;
    public $canonical = 'http://yunjianyi.com';

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->agent = Helper::agent();

        return true; // or false to not run the action
    }

}

