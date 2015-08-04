<?php
$this->title = '收藏节点的建议';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-9">
        <section>
            <?= yii\widgets\Breadcrumbs::widget([
                'options' => [
                    'class' => 'breadcrumb mb0',
                ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $this->render('@frontend/views/weight/topic_list', ['topic' => $model])?>
            <article>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination'=>$pagination,
                ]);?>
            </article>
        </section>
    </div>

    <div class="col-md-3 sidebar">
        <?= $this->render('@frontend/views/weight/user')?>
        <?= $this->render('@frontend/views/weight/node')?>
    </div>
</div>
