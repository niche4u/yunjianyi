<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\Helper;
use yii\helpers\Markdown;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->params['breadcrumbs'][] = ['label' => $model->node['name'], 'url' => '/node/'.$model->node['enname']];

$js = '$(".reply_link").click(function() {
    reply_textarea = $("#reply-content");
      old_reply_textarea = reply_textarea.val();
      var name = $(this).attr("data_id");
    append = "@" + name + " ";
    new_reply_textarea = "";
    if(old_reply_textarea.indexOf("@" + name) == -1){
      if(old_reply_textarea.length > 0){
          if (old_reply_textarea != new_reply_textarea) {
              new_reply_textarea = old_reply_textarea + " " + append;
          }
      } else {
          new_reply_textarea = append
      }
      reply_textarea.val(new_reply_textarea);
    }
    reply_textarea.focus();
    moveEnd($(".reply_textarea"));
  });';

$this->registerJs($js);

$agent = \common\components\Helper::agent();
?>
<div class="row">
    <div class="col-md-9">
        <section>
            <div class="header topic">
                <div class="pull-right">
                    <?php if($agent == 'is_iphone' || $agent == 'is_android'):?>
                    <a href="/member/<?= $model->user->username?>"><img src="<?= $model->user->avatar48?>" class="img-rounded" style="width: 48px;height: 48px"></a>
                    <?php else:?>
                    <a href="/member/<?= $model->user->username?>"><img src="<?= $model->user->avatar80?>" class="img-rounded"></a>
                    <?php endif?>
                </div>
                <?= $this->render('@views/weight/breadcrumbs.php', ['params' => $this->params])?>
                <h1><?= Html::encode($model->title)?></h1>
                <aside>
                    <small>
                        <a href="/member/<?= $model->user->username?>"><?= $model->user->username?></a>
                        · <?= Yii::$app->formatter->asRelativeTime($model->created)?> · <?= $model->click?> 次点击
                    </small>
                    <?php if($model->user_id == Yii::$app->user->id && time() - $model->created > 3600):?>
                    · <a href="/topic/append/<?= $model->id?>" class="node">附加</a>
                    <?php endif?>
                </aside>
            </div>

            <?php if(!empty($model->content->content)):?>
            <article class="topic-body">
                <?= \frontend\widgets\Alert::widget() ?>
                <?php echo Helper::autolink(Markdown::process($model->content->content, 'gfm'))?>
            </article>
            <?php endif?>

            <?php if(!empty($model->append)):?>
                <?php $k = 1?>
                <?php foreach($model->append as $a):?>
                <div class="mt10 ml15"><small>第 <?= $k?> 条附言 · <?= Yii::$app->formatter->asRelativeTime($a->created)?></small></div>
                <article class="topic-body">
                    <?= Helper::autolink(Markdown::process($a->content, 'gfm'))?>
                </article>
                    <?php $k++?>
                <?php endforeach?>
            <?php endif?>

            <div class="topic-footer">
                <div class="pull-right"><small><?= $model->click?> 次点击 &nbsp;&nbsp; <?= $model->follow?> 人收藏</small></div>
                <aside>
                    <small>
                        <?php if(!Yii::$app->user->isGuest):?>
                        <?php if(\common\models\Follow::findOne(['user_id' => Yii::$app->user->id, 'follow_id' => $model->id, 'type' => 3]) === null):?>
                            <a href="javascript:;" onclick="location.href = '/follow/do/3/<?= $model->id?>?next=/topic/<?= $model->id?>';">加入收藏</a>
                        <?php else:?>
                            <a href="javascript:;" onclick="location.href = '/follow/undo/3/<?= $model->id?>?next=/topic/<?= $model->id?>';">取消收藏</a>
                        <?php endif?>
                        <?php endif?>
                    </small></aside>
            </div>
        </section>
        <?php if($model->reply > 0):?>
        <section class="mt20" id="Reply">
            <div class="block-header">
                <?= $model->reply?> 回复 | 直到 <?= Yii::$app->formatter->asRelativeTime($model->last_reply_time)?>
            </div>
            <?php
            $page = Yii::$app->request->get('page');
            $floor = ((empty($page) ? 1 : $page) - 1) * Yii::$app->params['pageSize'] + 1?>
            <?php foreach($replyList as $c):?>
                <?php $userInfo = \common\models\User::Info($c->user_id);?>
            <article class="topic-reply">
                <div class="topic-reply-avatar pull-left">
                    <?php if($agent == 'is_iphone' || $agent == 'is_android'):?>
                        <img class="img-rounded" src="<?= $userInfo['avatar24']?>">
                    <?php else:?>
                        <img class="img-rounded" src="<?= $userInfo['avatar48']?>">
                    <?php endif?>
                </div>
                <?php if($agent == 'is_iphone' || $agent == 'is_android'):?>
                <div class="topic-reply-body" style="margin-left: 36px;">
                <?php else:?>
                    <div class="topic-reply-body">
                <?php endif?>
                    <div class="topic-reply-author">
                        <aside><small><strong><a href="/member/<?= $userInfo['username']?>"><?= $userInfo['username']?></a></strong>&nbsp;&nbsp;<?php if($userInfo['role'] == 20):?><span class="badge">Mod</span>&nbsp;&nbsp;<?php endif?><?= Yii::$app->formatter->asRelativeTime($c->created)?></small>
                        <div class="pull-right">
                           <?php if(Yii::$app->user->id):?> <span><small><a href="javascript:;" class="reply_link" data_id="<?= $userInfo['username']?>"><i class="glyphicon glyphicon-share-alt"></i></a></small></span><?php endif?>
                            <span class="badge"><?php echo $floor?></span>
                        </div>
                        </aside>
                    </div>
                    <div class="mt3"><p><?= Helper::autolink($c->content)?></p></div>
                    <div class="clearfix"></div>
                </div>
            </article>
                <?php $floor++;?>
            <?php endforeach?>
            <article>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination'=>$pagination,
                ]);?>
            </article>
        </section>
        <?php endif?>

        <section class="mt20">
            <div class="block-header">
                回复<a class="pull-right" href="#Top">回到顶部</a>
            </div>
            <div class="block-content">
                <?php if(!Yii::$app->user->isGuest):?>
                    <?php if(Yii::$app->user->identity->email_status == 1):?>
                    <?php $form = ActiveForm::begin(['id' => 'reply-form']); ?>
                    <?= Html::activeHiddenInput($reply, 'topic_id', ['value' => $model->id])?>
                    <?= $form->field($reply, 'content')->textarea(['rows' => 6])->label(false) ?>
                    <div class="form-group">
                        <?= Html::submitButton('提交回复', ['class' => 'btn btn-primary', 'name' => 'reply-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <?php else:?>
                        您需要激活邮箱后才可以回复。
                    <?php endif?>
                <?php else:?>
                    您需要登录后才可以回复。<a href="/login">登录</a> | <a href="/signup">注册</a>
                <?php endif?>
            </div>
        </section>

    </div>

    <div class="col-md-3 sidebar hidden-xs hidden-sm">
        <?= $this->render('@frontend/views/weight/user')?>
    </div>

</div>
