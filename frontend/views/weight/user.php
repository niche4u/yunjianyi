<?php if(Yii::$app->user->isGuest):?>
    <section>
        <div class="block-content">
            <strong><?= Yii::$app->name?></strong>
            <div class="mt6"></div>
            <small>一个收集建议的地方，生活中不便利的事情，某个现有产品不好用，欢迎来云建议提建议。</small>
        </div>
    </section>
<?php endif?>
