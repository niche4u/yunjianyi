<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TabNavSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tab Navs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tab-nav-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Tab Nav'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tab_id',
            'name',
            'link',
            'sort',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
