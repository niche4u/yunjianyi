<?php if(Yii::$app->user->isGuest):?>
    <section>
        <div class="block-content">
            <strong><?= Yii::$app->name?></strong>
            <div class="mt6"></div>
            <small>一个收集建议的地方。</small>
        </div>
    </section>
<?php endif?>
