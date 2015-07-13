<?php $HotNode = \common\models\Node::NewNode();?>
<?php if(!empty($HotNode)):?>
    <section>
        <div class="block-header">最新节点</div>
        <article class="node block-content">
            <?php $HotNode = \common\models\Node::NewNode();?>
            <?php foreach ($HotNode as $n):?>
                <a href="/node/<?= $n['enname']?>"><?= $n['name']?></a>
            <?php endforeach?>
        </article>
    </section>
<?php endif?>