<section class="mt20">

    <?php if(!empty($node->parent)):?>
    <div class="block-header">
        <small><b>父节点</b></small>
        <div class="mt10"></div>
        <img src="<?= $node->parent->logo24?>" class="img-rounded">&nbsp;&nbsp;<a href="/node/<?= $node->parent->enname?>"><?= $node->parent->name?></a>
    </div>
    <?php endif?>

    <?php if(!empty($node->related)):?>
    <article class="sidebar block-content">
        <small><b>相关节点</b></small>
        <div class="mt10"></div>
        <?php foreach($node->related as $r):?>
            <img width="24" src="<?= $r->logo24?>" class="img-rounded">&nbsp;&nbsp;<a href="/node/<?= $r->enname?>"><?= $r->name?></a>
            <div class="mt10"></div>
        <?php endforeach?>
    </article>
    <?php endif?>

    <?php if(!empty($node->nodes)):?>
    <article class="sidebar block-content">
        <small><b>子节点</b></small>
        <div class="mt10"></div>
        <?php foreach($node->nodes as $n):?>
            <img width="24" src="<?= $n->logo24?>" class="img-rounded">&nbsp;&nbsp;<a href="/node/<?= $n->enname?>"><?= $n->name?></a>
            <div class="mt10"></div>
        <?php endforeach?>
    </article>
    <?php endif?>

</section>