<?php

namespace common\models;

use common\components\Helper;
use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Markdown;

/**
 * This is the model class for table "notice".
 *
 * @property integer $id
 * @property integer $topic_id
 * @property integer $from_user_id
 * @property integer $to_user_id
 * @property string $msg
 * @property integer $is_read
 * @property integer $type
 * @property integer $created
 */
class Notice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['topic_id', 'from_user_id', 'to_user_id', 'created'], 'required'],
            [['topic_id', 'from_user_id', 'to_user_id', 'is_read', 'type', 'created'], 'integer'],
            [['msg'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'topic_id' => '建议id',
            'from_user_id' => '来自用户',
            'to_user_id' => '传至用户',
            'msg' => '消息内容',
            'is_read' => '是否已读',
            'type' => '1:回复;2:回复@;3:关注了建议',
            'created' => '创建时间',
        ];
    }

    /**
     * 获取当前登陆的用户的通知总数
     * @return array|\yii\db\ActiveRecord[]
     */
    static function Count()
    {
        $NoticeCount = Notice::find()->where(['to_user_id' => Yii::$app->user->id, 'is_read' => 0])->count();
        $NoticeCount = empty($NoticeCount) ? null : $NoticeCount;

        return $NoticeCount;
    }

    public function afterFind()
    {
        $this->msg  = Helper::autoLink(HtmlPurifier::process(Markdown::process($this->msg, 'gfm-comment')));
        parent::afterFind();
    }
}