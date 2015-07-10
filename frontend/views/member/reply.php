<?php
use common\components\Helper;

$this->title = '发布的回复';
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => '/member/'.$user->username];
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
            <?php foreach($model as $c):?>
                <?php $topicInfo = \common\models\Topic::Info($c->topic_id);?>
                <article class="member-reply">
                    <div class="member-reply-body">
                        <div class="member-reply-author">
                            <aside><small>回复了 <a href="/member/<?= $topicInfo['username']?>"><?= $topicInfo['username']?></a> 创建的主题 <a href="/topic/<?= $c->topic_id?>"> <?= $topicInfo['title']?></a> &nbsp;&nbsp;<?= Yii::$app->formatter->asRelativeTime($c->created)?></small></aside>
                        </div>
                        <div class="mt10"><p><?= Helper::autolink(nl2br($c->content))?></p></div>
                        <div class="clearfix"></div>
                    </div>
                </article>
            <?php endforeach?>
            <article class="item">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination'=>$pagination,
                ]);?>
            </article>
        </section>
    </div>

    <div class="col-md-3 sidebar">
        <?= $this->render('@frontend/views/weight/user')?>
    </div>
</div>
