<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\Html;

/**
 * This is the model class for table "topic".
 *
 * @property integer $id
 * @property string $title
 * @property integer $user_id
 * @property integer $node_id
 * @property integer $need_login
 * @property integer $click
 * @property integer $reply
 * @property string $last_reply_user
 * @property integer $last_reply_time
 * @property integer $updated_at
 * @property integer $created
 *
 * @property TopicContent $topicContent
 */
class Topic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created', 'title', 'node_id', 'updated_at'], 'required'],
            [['user_id', 'node_id', 'need_login', 'click', 'follow', 'reply', 'last_reply_time', 'updated_at', 'created'], 'integer'],
            [['title'], 'string', 'max' => 300],
            [['last_reply_user'], 'string', 'max' => 255],
            [['title'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'user_id' => '作者',
            'node_id' => '节点',
            'need_login' => '需要登录',
            'click' => '点击数',
            'follow' => '收藏人数',
            'reply' => '回复数',
            'last_reply_user' => '最后回复',
            'last_reply_time' => '最后回复时间',
            'updated_at' => '最后更新',
            'created' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(TopicContent::className(), ['topic_id' => 'id'])->where(['is_append' => 0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppend()
    {
        return $this->hasMany(TopicContent::className(), ['topic_id' => 'id'])->where(['is_append' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastReplyUser()
    {
        if(empty($this->last_reply_user)) return null;
        return $this->hasOne(User::className(), ['username' => 'last_reply_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplyList()
    {
        return $this->hasMany(Reply::className(), ['topic_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(Node::className(), ['id' => 'node_id']);
    }

    public function create()
    {
        $this->created = time();
        $this->user_id = Yii::$app->user->id;
        $this->need_login = Node::findOne($this->node_id)->need_login;
        $this->updated_at = time();

        if ($this->validate()) {
            if($this->save())
            {
                Yii::$app->cache->delete('TopicCount');
                Yii::$app->cache->delete('Ranking');
                Yii::$app->cache->delete('HotNode15');
                $topicContent = new TopicContent();
                if ($topicContent->load(Yii::$app->request->post())) {

                    $search = new Search;
                    $search->topic_id = $this->id;
                    $search->title = $this->title;
                    $search->content = $topicContent->content;
                    $search->save();

                    $topicContent->topic_id = $this->id;
                    $topicContent->created = time();
                    return $topicContent->save();
                }
            }
        }
        return false;
    }

    public function append()
    {
        if ($this->validate()) {
            $this->updated_at = time();
            if($this->save())
            {
                $topicContent = new TopicContent();
                if ($topicContent->load(Yii::$app->request->post())) {

                    $search = Search::findOne($this->id);
                    if(!empty($search)) {
                        $search->content .= $topicContent->content;
                        $search->save();
                    }

                    $topicContent->topic_id = $this->id;
                    $topicContent->is_append = 1;
                    $topicContent->created = time();
                    return $topicContent->save();
                }
            }
        }
        return false;
    }

    /**
     * 热门建议
     * @param int $num 获取几条热门建议
     * @return array|\yii\db\ActiveRecord[]
     */
    static function HotTopic($num = 8)
    {
        $HotTopic = Yii::$app->cache->get('HotTopic'.$num);
        if(!isset($HotTopic)) {
            $HotTopic = (new Query())->select('topic.*, user.username, user.avatar')->from(Topic::tableName() . ' topic')->leftJoin(User::tableName() . ' user', 'user.id = topic.user_id')->leftJoin(Node::tableName(), 'topic.node_id = node.id')->where(['node.is_hidden' => 0])->andWhere('topic.reply > 0')->andWhere(['between', 'topic.created', strtotime(date('Y-m-d H:i', time())) - 86400, strtotime(date('Y-m-d H:i', time()))])->orderBy(['topic.reply' => SORT_DESC])->limit($num)->all();
            Yii::$app->cache->set('HotTopic'.$num, $HotTopic, 86400);
        }
        return $HotTopic;
    }

    /**
     * 建议统计
     * @return array|\yii\db\ActiveRecord[]
     */
    static function TopicCount()
    {
        if(!$TopicCount = Yii::$app->cache->get('TopicCount'))
        {
            $TopicCount = Topic::find()->leftJoin(Node::tableName(), 'topic.node_id = node.id')->where(['node.is_hidden' => 0])->count();
            Yii::$app->cache->set('TopicCount', $TopicCount, 86400);
        }
        return $TopicCount;
    }

    //获取建议信息，有缓存就获取缓存
    static function Info($id)
    {
        if(!$topicInfo = Yii::$app->cache->get('topic'.$id))
        {
            $topicInfo = Topic::find()->select('topic.id, topic.title, topic.user_id, topic.node_id, topic.created, topic.last_reply_time, topic.last_reply_user, user.username, node.name as nodename, node.enname as nodeenname')->leftJoin('user','user.id = topic.user_id')->leftJoin('node','node.id = topic.node_id')->where(['topic.id' => $id])->asArray()->one();
            Yii::$app->cache->set('topic'.$id, $topicInfo, 0);
        }
        return $topicInfo;
    }


    //贡献排行榜，有缓存就获取缓存
    static function Ranking()
    {
        if(!$Ranking = Yii::$app->cache->get('Ranking'))
        {
            $Ranking = (new Query())->select('count(topic.id) topic_count, user.username, user.avatar')->from(Topic::tableName().' topic')->leftJoin(Node::tableName(), 'node.id = topic.node_id')->leftJoin(User::tableName().' user', 'user.id = topic.user_id')->where(['node.is_hidden' => 0])->groupBy(['topic.user_id'])->orderBy(['topic_count' => SORT_DESC])->limit(10)->all();
            Yii::$app->cache->set('Ranking', $Ranking, 86400);
        }
        return $Ranking;
    }

    /**
     * @inheritdoc
     */
    public function getNeedLoginLabel()
    {
        $statuses = $this->arrayNeedLogin;
        $this->need_login = $statuses[$this->need_login];
        return $this->need_login;
    }

    /**
     * @inheritdoc
     */
    public function getArrayNeedLogin()
    {
        return [
            '1' => Yii::t('app', 'YES'),
            '0' => Yii::t('app', 'NO'),
        ];
    }

    public function afterFind()
    {
        $this->title  = Html::encode($this->title);
        parent::afterFind();
    }
}