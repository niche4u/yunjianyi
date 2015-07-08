<?php
use common\models\Tab;

$tab = Tab::Tab();
$tabActive = Yii::$app->session->get('tab');
$subtab = Tab::SubTab($tabActive);
$agent = \common\components\Helper::agent();
?>

<?php if($agent == 'is_iphone' || $agent == 'is_android'):?>

<ul id="Tabs" class="tab nav-pills nav<?php if(empty($subtab['nodes'])):?> no-sub-nav<?php endif?>" style="padding:10px 10px;">
<?php foreach($tab as $t):?>
    <li<?php if($t['enname'] == $tabActive) echo ' class="active"'?>><a href="?tab=<?= $t['enname']?>" style="padding:5px 6px;margin:0  0"><?= $t['name']?></a></li>
<?php endforeach?>
</ul>
<?php if(!empty($subtab['nodes'])):?>
<nav id="sub-nav" class="hidden-xs">
    <div class="pull-right">
    <?php foreach($subtab['tabRight'] as $s):?>
        <a href="<?= $s['link']?>"><?= $s['name']?></a>
    <?php endforeach?>
    </div>
    <?php foreach($subtab['nodes'] as $sub):?>
    <a href="/node/<?= $sub['enname']?>"><?= $sub['name']?></a> &nbsp; &nbsp;
    <?php endforeach?>
</nav>
<?php endif?>

<?php else: ?>

<ul id="Tabs" class="tab nav-pills nav<?php if(empty($subtab['nodes'])):?> no-sub-nav<?php endif?>">
<?php foreach($tab as $t):?>
    <li<?php if($t['enname'] == $tabActive) echo ' class="active"'?>><a href="?tab=<?= $t['enname']?>"><?= $t['name']?></a></li>
<?php endforeach?>
</ul>
<?php if(!empty($subtab['nodes'])):?>
<nav id="sub-nav" class="hidden-xs">
    <div class="pull-right">
    <?php foreach($subtab['tabRight'] as $s):?>
        <a href="<?= $s['link']?>"><?= $s['name']?></a>
    <?php endforeach?>
    </div>
    <?php foreach($subtab['nodes'] as $sub):?>
    <a href="/node/<?= $sub['enname']?>"><?= $sub['name']?></a> &nbsp; &nbsp;
    <?php endforeach?>
</nav>
<?php endif?>

<?php endif?>
