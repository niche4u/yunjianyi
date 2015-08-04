<?php

$this->params['breadcrumbs'][] = '最近的建议';
?>
<?php $agent = \common\components\Helper::agent();?>

    <div class="row">
        <div class="col-md-9">
            <section>
                <?= yii\widgets\Breadcrumbs::widget([
                    'options' => [
                        'class' => 'breadcrumb mb0',
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= $this->render('@frontend/views/weight/topic_list', ['topic' => $topic])?>
                <article>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination'=>$pagination,
                ]);?>
                </article>
            </section>
        </div>

        <div class="col-md-3 sidebar">
            <?= $this->render('@frontend/views/weight/user')?>
            <?= $this->render('@frontend/views/weight/hot-topic')?>
            <?= $this->render('@frontend/views/weight/hot-node')?>
            <?= $this->render('@frontend/views/weight/new-node')?>
            <?= $this->render('@frontend/views/weight/count')?>
        </div>

    </div>


