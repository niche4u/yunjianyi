<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

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
                <h1><?= $model->title?></h1>
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
                <article class="topic-body markdown-content">
                    <?= \frontend\widgets\Alert::widget() ?>
                    <?php echo $model->content->content?>
                </article>
            <?php endif?>

            <?php if(!empty($model->append)):?>
                <?php $k = 1?>
                <?php foreach($model->append as $a):?>
                    <div class="mt10 ml15"><small>第 <?= $k?> 条附言 · <?= Yii::$app->formatter->asRelativeTime($a->created)?></small></div>
                    <article class="topic-body markdown-content">
                        <?= $a->content?>
                    </article>
                    <?php $k++?>
                <?php endforeach?>
            <?php endif?>

            <div class="topic-footer">
                <div class="pull-right"><small><?= $model->click?> 次点击 &nbsp;&nbsp; <?= $model->follow?> 人收藏</small></div>
                <aside id="Follow">
                    <small>
                        <?php if(!Yii::$app->user->isGuest):?>
                            <?php if(\common\models\Follow::findOne(['user_id' => Yii::$app->user->id, 'follow_id' => $model->id, 'type' => 3]) === null):?>
                                <a href="javascript:;" onclick="location.href = '/follow/do/3/<?= $model->id?>?next=/topic/<?= $model->id?>#Follow';">加入收藏</a>
                            <?php else:?>
                                <a href="javascript:;" onclick="location.href = '/follow/undo/3/<?= $model->id?>?next=/topic/<?= $model->id?>#Follow';">取消收藏</a>
                            <?php endif?>
                        <?php endif?>
                    </small></aside>
            </div>
        </section>
        <?php if($model->reply > 0):?>
            <section class="mt20" id="Reply">
                <div class="block-header">
                    <small><?= $model->reply?> 回复 | 直到 <?= Yii::$app->formatter->asRelativeTime($model->last_reply_time)?></small>
                </div>
                <?php
                $page = Yii::$app->request->get('page');
                $floor = ((empty($page) ? 1 : $page) - 1) * Yii::$app->params['pageSize'] + 1?>
                <?php foreach($replyList as $c):?>
                    <article class="topic-reply">
                        <?php if($this->context->agent == 'is_iphone' || $this->context->agent == 'is_android'):?>
                        <div class="topic-reply-avatar pull-left">
                            <img class="img-rounded" src="<?= Yii::$app->params['avatarUrl'].'/24/'.$c['avatar']?>">
                        </div>
                        <div class="topic-reply-body" style="margin-left: 36px;">
                            <?php else:?>
                            <div class="topic-reply-avatar pull-left">
                                <img class="img-rounded" src="<?= Yii::$app->params['avatarUrl'].'/48/'.$c['avatar']?>">
                            </div>
                            <div class="topic-reply-body">
                                <?php endif?>
                                <div class="topic-reply-author">
                                    <aside><small><strong><a href="/member/<?= $c['username']?>"><?= $c['username']?></a></strong>&nbsp;&nbsp;<?php if($c['role'] == 20):?><span class="badge">Mod</span>&nbsp;&nbsp;<?php endif?><?= Yii::$app->formatter->asRelativeTime($c['created'])?></small>
                                        <div class="pull-right">
                                            <?php if(Yii::$app->user->id):?> <span><small><a href="javascript:;" class="reply_link" data_id="<?= $c['username']?>"><i class="glyphicon glyphicon-share-alt"></i></a></small></span><?php endif?>
                                            <span class="badge"><?php echo $floor?></span>
                                        </div>
                                    </aside>
                                </div>
                                <div class="mt3 markdown-content"><?= \common\components\Helper::autoLink(\yii\helpers\HtmlPurifier::process(\yii\helpers\Markdown::process($c['content'], 'gfm-comment')))?></div>
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

        <section>
            <div class="block-header">
                <small>回复<a class="pull-right" href="#Top">回到顶部</a></small>
            </div>
            <div class="block-content">
                <?php if(!Yii::$app->user->isGuest):?>
                    <?php if(Yii::$app->user->identity->email_status == 1):?>
                        <?php $form = ActiveForm::begin(['id' => 'reply-form']); ?>
                        <?= Html::activeHiddenInput($reply, 'topic_id', ['value' => $model->id])?>
                        <?= $form->field($reply, 'content')->textarea(['rows' => 6])->label(false) ?>
                        <div class="form-group">
                            <?= Html::submitButton('提交回复', ['class' => 'btn btn-success', 'name' => 'reply-button']) ?>
                            <?= Html::button('预览回复', ['class' => 'btn btn-primary preview']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    <?php else:?>
                        您需要激活邮箱后才可以回复。
                    <?php endif?>
                <?php else:?>
                    您需要登录后才可以回复。<a href="/login">登录</a> | <a href="/signup">注册</a>
                <?php endif?>
            </div>
            <div class="row" id="preview"></div>
        </section>

    </div>

    <div class="col-md-3 sidebar">
        <?= $this->render('@frontend/views/weight/user')?>
        <?= $this->render('@frontend/views/weight/node-link', ['node' => $model->node['id']])?>
        <?= $this->render('@frontend/views/weight/node-ad', ['node' => $model->node['id']])?>
    </div>

</div>
<?php
$sendJs = "\n
  $('.preview').click(function(){
     $('#reply-content').val();
     $.ajax({
      url:'/topic/preview',
      type: 'POST',
      data: {content:$('#reply-content').val()},
      success: function(json) {
          $('#preview').html('<div class=\"col-lg-12\"><article class=\"header reply-body markdown-content\">'+json+'</article></div>')
        },
    });
  });";
$this->registerJs($sendJs, \yii\web\View::POS_READY);
?>