<?php
$ParentNode = \common\models\Node::Info($node->parent_id);
$RelatedNode = \common\models\Node::RelatedNode($node->id, $node->parent_id, false);
$SubNode = \common\models\Node::SubNode($node->id, false);

if(!empty($ParentNode) || !empty($RelatedNode) || !empty($SubNode)):?>
<section>

    <?php if(!empty($ParentNode)):?>
    <div class="block-header">
        <small><b>父节点</b></small>
        <div class="mt10"></div>
        <img src="<?= Yii::$app->params['nodeUrl'].'/24/'.$ParentNode['logo']?>" class="img-rounded">&nbsp;&nbsp;<a href="/node/<?= $ParentNode['enname']?>"><?= $ParentNode['name']?></a>
    </div>
    <?php endif?>

    <?php if(!empty($RelatedNode)):?>
    <article class="sidebar block-content">
        <small><b>相关节点</b></small>
        <div class="mt10"></div>
        <?php foreach($RelatedNode as $r):?>
            <img width="24" src="<?= Yii::$app->params['nodeUrl'].'/24/'.$r['logo']?>" class="img-rounded">&nbsp;&nbsp;<a href="/node/<?= $r['enname']?>"><?= $r['name']?></a>
            <div class="mt10"></div>
        <?php endforeach?>
    </article>
    <?php endif?>

    <?php if(!empty($SubNode)):?>
    <article class="sidebar block-content">
        <small><b>子节点</b></small>
        <div class="mt10"></div>
        <?php foreach($SubNode as $n):?>
            <img width="24" src="<?= Yii::$app->params['nodeUrl'].'/24/'.$n['logo']?>" class="img-rounded">&nbsp;&nbsp;<a href="/node/<?= $n['enname']?>"><?= $n['name']?></a>
            <div class="mt10"></div>
        <?php endforeach?>
    </article>
    <?php endif?>

</section>
<?php endif?>