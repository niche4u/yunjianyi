<?php

namespace common\models;

use common\components\Helper;
use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Markdown;

/**
 * This is the model class for table "reply".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $topic_id
 * @property string $content
 * @property integer $created
 */
class Reply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['topic_id', 'content'], 'required'],
            [['topic_id', 'created'], 'integer'],
            ['user_id', 'default', 'value' => Yii::$app->user->id],
            ['created', 'default', 'value' => time()],
            [['content'], 'string', 'max' => 20000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '回复ID',
            'user_id' => '回复者',
            'topic_id' => '建议',
            'content' => '回复内容',
            'created' => '创建时间',
        ];
    }

    /**
     * @param boolean $insert
     * @param array $changedAttributes
     * @return bool
     */
    public function afterSave($insert, $changedAttributes)
    {
        $topic = Topic::findOne($this->topic_id);
        $topic->reply = $topic->reply + 1;
        $topic->last_reply_time = time();
        $topic->updated_at = time();
        $topic->last_reply_user = Yii::$app->user->identity->username;
        $rst = $topic->update();
        Yii::$app->cache->delete('HotTopic8');

        //给用户发回复或者@通知,回复自己的不通知
        if($rst && Yii::$app->user->id != $topic->user_id)
        {
            $notice = new Notice();
            $notice->from_user_id = $this->user_id;
            $notice->to_user_id = $topic->user_id;
            $notice->topic_id = $this->topic_id;
            $notice->type = 1;
            $notice->msg = $this->content;
            $notice->created = time();
            $notice->save();
        }

        //回复中提到其他人，通知其他人
        if(strstr($this->content, '@'))
        {
            preg_match_all('/@(.*?)\s/', $this->content, $match);
            if(isset($match[1]) && count($match[1]) > 0)
            {
                $notice_user = array_unique($match[1]);
                foreach ($notice_user as $v) {
                    $to_user = User::findOne(['username' => $v]);
                    if($v == $topic->user->username || $v == Yii::$app->user->identity->username || empty($to_user->id)) continue;
                    $notice = new Notice();
                    $notice->from_user_id = $this->user_id;
                    $notice->to_user_id = $to_user->id;
                    $notice->topic_id = $this->topic_id;
                    $notice->type = 2;
                    $notice->msg = $this->content;
                    $notice->created = time();
                    $notice->save();
                }
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'topic_id']);
    }

    static function ReplyCount()
    {
        if(!$ReplyCount = Yii::$app->cache->get('ReplyCount'))
        {
            $ReplyCount = Reply::find()->count();
            Yii::$app->cache->set('ReplyCount', $ReplyCount, 86400);
        }
        return $ReplyCount;
    }

    public function afterFind()
    {
        $this->content  = Helper::autoLink(HtmlPurifier::process(Markdown::process($this->content, 'gfm-comment')));
        parent::afterFind();
    }
}