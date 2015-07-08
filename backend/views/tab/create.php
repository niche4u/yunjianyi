<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Tab */

$this->title = Yii::t('app', 'Create Tab');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tabs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tab-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
