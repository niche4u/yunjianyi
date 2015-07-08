<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\assets\AppAsset;
use common\models\Notice;
/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$sendJs = "\n
  $('html').on('click', function (e) {
    $(\".search_input\").popover('hide');
      $('[rel=\"popover\"]').each(function () {
          if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('[rel=popover]').has(e.target).length === 0) {
              $(this).popover('hide');
          }
      });
  });
  $('.search_input_submit').click(function(){
     var keyword = $('.search_input').val();
     if (!/\\S/.test(keyword)){
        $(\".search_input\").popover('show');
        return false;
     }
     $('#search_form').submit();
  });
  $('.search_input').keydown(function(event){
     if(event.keyCode == 13) {
        var keyword = $(this).val();
        if (!/\\S/.test(keyword)){
           $(\".search_input\").popover('show');
           return false;
        }
        return true;
     }
  });";
$this->registerJs($sendJs, \yii\web\View::POS_READY);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title><?= Html::encode($this->context->title) ?><?php if(Notice::Count() > 0) echo ' ('.Notice::Count().')'?></title>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
    <?= Html::csrfMetaTags() ?>    <?= $this->registerMetaTag(['name' => 'description', 'content' => $this->context->description]);?><?= $this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->params['domain']]);?>
    <?php $this->head() ?>

</head>
<body>
    <?php $this->beginBody() ?>

    <header id="Top">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-default',
            ],
        ]);
        $keyword = isset($this->context->keyword) ? $this->context->keyword : '';
        echo '<form class="navbar-form navbar-left" role="search" action="/search" method="get">
                <div class="form-group">
                    <input type="text" value="'.$keyword.'" name="keyword" class="form-control search_input" id="navbar-search" placeholder="搜索..." data-placement="bottom" data-content="请输入要搜索的关键词！">
                </div>
            </form>';

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '注册', 'url' => ['/account/signup']];
            $menuItems[] = ['label' => '登录', 'url' => ['/account/login']];
        } else {
            $menuItems[] = ['label' => Yii::$app->user->identity->username, 'url' => ['/member/'.Yii::$app->user->identity->username]];
            if(Yii::$app->user->identity->email_status == 1) $menuItems[] = ['label' => '创作新主题', 'url' => ['/topic/create']];
            $menuItems[] = ['label' => '通知 <span class="badge badge-important">'.Notice::Count().'</span>', 'url' => ['/account/notice']];
            $menuItems[] = ['label' => '设置', 'url' => ['/account/setting']];
            $menuItems[] = ['label' => '退出', 'url' => ['/account/logout'], 'linkOptions' => ['data-method' => 'post']];
        }
        echo Nav::widget([
            'encodeLabels' => false,
            'activateItems' => false,
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>
    </header>
    <main id="Wrapper" style="<?php if(!empty($this->context->bg)) echo 'background-image:url('.Yii::$app->params['bgUrl'].$this->context->bg.');background-position: 0 0, 0 0;background-repeat: repeat;'; if(!empty($this->context->bg_color)) echo 'background-color:'.$this->context->bg_color?>">
        <div class="container">
            <?= $this->render('@frontend/views/weight/email-status')?>
            <?= $content ?>
        </div>
    </main>

    <footer id="Bottom">
        <div class="container">
        <p class="pull-left">
	        <a href="/about">关于</a>
	    </p>
        <p class="pull-right">
	        &copy; <?= Yii::$app->name?> <?= date('Y') ?>
	    </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
