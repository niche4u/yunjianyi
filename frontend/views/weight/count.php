<section>
    <div class="block-header"><small>统计</small>
    </div>
    <div class="block-content">
        会员 <b><?= \common\models\User::UserCount()?></b>
        <div class="mt5"></div>
        建议 <b><?= \common\models\Topic::TopicCount()?></b>
        <div class="mt5"></div>
        回复 <b><?= \common\models\Reply::ReplyCount()?></b>
    </div>
</section>