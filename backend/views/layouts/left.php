<?php
use yii\bootstrap\Nav;
use \backend\models\Menu;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::$app->user->identity->avatar48?>" class="img-rounded" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <?php
        $item = [];
        $menu = Menu::find()->orderBy('order')->all();
        foreach ($menu as $k => $m) {
            $item[$k]['label'] = $m->data.'<span>'.$m->name.'</span>';
            $item[$k]['url'] = [$m->route];
        }
        ?>
        <?=
        Nav::widget(
            [
                'encodeLabels' => false,
                'activateItems' => true,
                'options' => ['class' => 'sidebar-menu'],
                'items' => $item,
//                'items' => [
//                    ['label' => '<i class="fa fa-pencil"></i><span>建议管理</span>', 'url' => ['/topic/index']],
//                    ['label' => '<i class="fa fa-reply"></i><span>回复管理</span>', 'url' => ['/reply/index']],
//                    ['label' => '<i class="fa fa-user"></i><span>用户管理</span>', 'url' => ['/user/index']],
//                    ['label' => '<i class="fa fa-tags"></i><span>节点管理</span>', 'url' => ['/node/index']],
//                    ['label' => '<i class="fa fa-navicon"></i><span>Tab管理</span>', 'url' => ['/tab/index']],
//                    ['label' => '<i class="fa fa-server"></i><span>页面管理</span>', 'url' => ['/page/index']],
//                    ['label' => '<i class="fa fa-trash"></i><span>清除缓存</span>', 'url' => ['/site/clean-cache'], 'linkOptions' => ['data-confirm' => '确定要清除所有缓存']],
//                ],
            ]
        );
        ?>

    </section>

</aside>
