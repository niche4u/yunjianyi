<section>
    <div class="block-header">统计
    </div>
    <div class="block-content">
        会员 <b><?= \common\models\User::Count()?></b>
        <div class="mt5"></div>
        主题 <b><?= \common\models\Topic::TopicStat()?></b>
        <div class="mt5"></div>
        回复 <b><?= \common\models\Reply::Count()?></b>
    </div>
</section>