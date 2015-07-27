<?php

use yii\helpers\Markdown;

$this->params['breadcrumbs'][] = $model->title;
?>
<div class="row">
    <div class="col-md-9">
        <section>
            <?= yii\widgets\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <article class="<?= $model->route?>">
                <div class="row">
                    <div class="col-lg-9 markdown-content">
                        <?= $model->content?>
                    </div>
                </div>
            </article>
        </section>
    </div>

    <div class="col-md-3 sidebar">
        <?= $this->render('@frontend/views/weight/user')?>
        <?= $this->render('@frontend/views/weight/hot-topic')?>
        <?= $this->render('@frontend/views/weight/hot-node')?>
        <?= $this->render('@frontend/views/weight/stat')?>
    </div>

</div>
