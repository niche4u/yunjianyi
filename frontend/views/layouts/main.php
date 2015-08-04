<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\assets\AppAsset;
use common\models\Notice;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$noticeCount = Notice::Count();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title><?= Html::encode($this->context->title) ?><?php if($noticeCount > 0) echo ' ('.$noticeCount.')'?></title>
    <link rel="shortcut icon" href="http://cdn.yunjianyi.com/favicon.ico"/>
    <link rel="apple-touch-icon" href="http://cdn.yunjianyi.com/apple-touch-icon.png" />
    <?= Html::csrfMetaTags() ?>    <?= $this->registerMetaTag(['name' => 'description', 'content' => $this->context->description]);?><?= $this->registerLinkTag(['rel' => 'canonical', 'href' => $this->context->canonical]);?>
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
            if(Yii::$app->user->identity->email_status == 1) $menuItems[] = ['label' => '<i class="glyphicon glyphicon-pencil"></i> 提建议', 'url' => ['/topic/create']];
            $menuItems[] = [
                'label' => '<img src="'.Yii::$app->user->identity->avatar48.'" class="img-rounded"> '.Yii::$app->user->identity->username.' <span class="badge badge-important">'.$noticeCount.'</span>',
                'items' => [
                    ['label' => '<span class="glyphicon glyphicon-home"></span> 我的主页', 'url' => ['/member/'.Yii::$app->user->identity->username]],
                    '<li class="divider"></li>',
                    ['label' => '<span class="glyphicon glyphicon-bell"></span> 通知中心 <span class="badge badge-important">'.$noticeCount.'</span>', 'url' => ['/account/notice']],
                    ['label' => '<span class="glyphicon glyphicon-tags"></span> 收藏节点', 'url' => ['/account/node']],
                    ['label' => '<span class="glyphicon glyphicon-star"></span> 收藏建议', 'url' => ['/account/topic']],
                    ['label' => '<span class="glyphicon glyphicon-user"></span> 关注的人', 'url' => ['/account/follow']],
                    ['label' => '<span class="glyphicon glyphicon-cog"></span> 账户设置', 'url' => ['/account/setting']],
                    '<li class="divider"></li>',
                    ['label' => '<span class="glyphicon glyphicon-log-out"></span> 退出登陆', 'url' => ['/account/logout'], 'linkOptions' => ['data-method' => 'post']],
                ],
                'linkOptions'=>['class'=>'avatar'],
            ];
        }
        echo Nav::widget([
            'encodeLabels' => false,
            'activateItems' => true,
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
	    </p>
        <p class="pull-right">
            <small>&copy; <?= Yii::$app->name?> <?= date('Y') ?>&nbsp;•&nbsp; <?= floor(Yii::getLogger()->getElapsedTime() * 1000).' ms';?></small>
	    </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
