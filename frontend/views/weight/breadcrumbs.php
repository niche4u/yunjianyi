<?= yii\widgets\Breadcrumbs::widget([
    'tag' => 'span',
    'itemTemplate' => "<span>{link}</span>\n",
    'activeItemTemplate' => "<span>{link}</span>\n",
    'links' => isset($params['breadcrumbs']) ? $params['breadcrumbs'] : [],
]) ?>
<div class="mt15"></div>