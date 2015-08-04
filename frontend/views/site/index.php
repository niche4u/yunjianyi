<?= $this->registerLinkTag([
    'title' => 'RSS',
    'rel' => 'alternate',
    'type' => 'application/rss+xml',
    'href' => Yii::$app->params['domain'].'rss.xml',
]);?>

<?= \frontend\widgets\Alert::widget() ?>
    <div class="row">

        <div class="col-md-9">
            <section>
                <?= $this->render('@frontend/views/weight/tab')?>
                <?= $this->render('@frontend/views/weight/topic_list', ['topic' => $topic])?>
                <div class="block-footer"><small><a href="/recent">更多建议</a></small></div>
            </section>
        </div>

        <div class="col-md-3 sidebar">
            <?= $this->render('@frontend/views/weight/user')?>
            <?= $this->render('@frontend/views/weight/hot-topic')?>
            <?= $this->render('@frontend/views/weight/tab-ad')?>
            <?= $this->render('@frontend/views/weight/hot-node')?>
            <?= $this->render('@frontend/views/weight/new-node')?>
            <?= $this->render('@frontend/views/weight/ranking')?>
            <?= $this->render('@frontend/views/weight/count')?>
        </div>

    </div>


