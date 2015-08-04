<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "follow".
 *
 * @property integer $user_id
 * @property integer $follow_id
 */
class Follow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'follow';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'follow_id'], 'required'],
            [['user_id', 'follow_id', 'type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用户',
            'follow_id' => '关注了',
            'type' => '1用户;2节点;3建议',
        ];
    }

    /**
     * 获取关注的人用户信息
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'follow_id']);
    }

    /**
     * 获取关注的节点信息
     * @return \yii\db\ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(Node::className(), ['id' => 'follow_id']);
    }

    /**
     * 获取关注的建议信息
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'follow_id']);
    }

    /**
     * 获取关注的人的总数
     * @return array|\yii\db\ActiveRecord[]
     */
    static function UserCount()
    {
        $userId = Yii::$app->user->id;
        $UserCount = Yii::$app->cache->get('UserCount'.$userId);
        if($UserCount == '')
        {
            $UserCount = Follow::find()->where(['user_id' => $userId, 'type' => 1])->count();
            Yii::$app->cache->set('UserCount'.$userId, $UserCount, 0);
        }
        return $UserCount;
    }

    /**
     * 获取收藏节点的总数
     * @return array|\yii\db\ActiveRecord[]
     */
    static function NodeCount()
    {
        $userId = Yii::$app->user->id;
        $NodeCount = Yii::$app->cache->get('NodeCount'.$userId);
        if($NodeCount == '')
        {
            $NodeCount = Follow::find()->where(['user_id' => $userId, 'type' => 2])->count();
            Yii::$app->cache->set('NodeCount'.$userId, $NodeCount, 0);
        }
        return $NodeCount;
    }

    /**
     * 获取收藏建议的总数
     * @return array|\yii\db\ActiveRecord[]
     */
    static function TopicCount()
    {
        $userId = Yii::$app->user->id;
        $TopicCount = Yii::$app->cache->get('TopicCount'.$userId);
        if($TopicCount == '')
        {
            $TopicCount = Follow::find()->where(['user_id' => $userId, 'type' => 3])->count();
            Yii::$app->cache->set('TopicCount'.$userId, $TopicCount, 0);
        }
        return $TopicCount;
    }

    /**
     * 获取我关注的人
     * @param bool $onlyId
     * @return array|\yii\db\ActiveRecord[]
     */
    static function User($onlyId = true)
    {
        if($onlyId) {
            if (!$FollowUser = Yii::$app->cache->get('FollowUserId' . Yii::$app->user->id)) {
                $FollowUser = ArrayHelper::map(Follow::find()->select('follow_id')->where(['user_id' => Yii::$app->user->id, 'type' => 1])->asArray()->all(), 'follow_id', 'follow_id');
                Yii::$app->cache->set('FollowUserId' . Yii::$app->user->id, $FollowUser, 0);
            }
        }
        else {
            if (!$FollowUser = Yii::$app->cache->get('FollowUser' . Yii::$app->user->id)) {
                $FollowUser = (new Query())->from(User::tableName())->where(['in', 'id', Follow::User()])->all();
                Yii::$app->cache->set('FollowUser' . Yii::$app->user->id, $FollowUser, 0);
            }
        }
        return $FollowUser;
    }

    /**
     * 获取我收藏的节点
     * @param bool $onlyId
     * @return array|\yii\db\ActiveRecord[]
     */
    static function Node($onlyId = true)
    {
        if($onlyId) {
            if(!$FollowNode = Yii::$app->cache->get('FollowNodeId'.Yii::$app->user->id))
            {
                $FollowNode = ArrayHelper::map(Follow::find()->select('follow_id')->where(['user_id' => Yii::$app->user->id, 'type' => 2])->asArray('follow_id')->all(), 'follow_id', 'follow_id');
                Yii::$app->cache->set('FollowNodeId'.Yii::$app->user->id, $FollowNode, 0);
            }
        }
        else {
            if(!$FollowNode = Yii::$app->cache->get('FollowNode'.Yii::$app->user->id))
            {
                $FollowNode = (new Query())->from(Node::tableName())->select('node.enname, node.name, node.logo')->where(['in', 'id', Follow::Node()])->all();
                Yii::$app->cache->set('FollowNode'.Yii::$app->user->id, $FollowNode, 0);
            }
        }

        return $FollowNode;
    }

    /**
     * 获取我收藏的建议id
     * @return array|\yii\db\ActiveRecord[]
     */
    static function Topic()
    {
        if(!$FollowTopic = Yii::$app->cache->get('FollowTopic'.Yii::$app->user->id))
        {
            $FollowTopic = ArrayHelper::map(Follow::find()->select('follow_id')->where(['user_id' => Yii::$app->user->id, 'type' => 3])->asArray()->all(), 'follow_id', 'follow_id');
            Yii::$app->cache->set('FollowTopic'.Yii::$app->user->id, $FollowTopic, 0);
        }
        return $FollowTopic;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if($this->type == 1) {
            Yii::$app->cache->delete('UserCount'.$this->user_id);
            Yii::$app->cache->delete('FollowUserId'.Yii::$app->user->id);
            Yii::$app->cache->delete('FollowUser'.Yii::$app->user->id);
        }
        if($this->type == 2) {
            Yii::$app->cache->delete('NodeCount'.$this->user_id);
            Yii::$app->cache->delete('FollowNodeId'.Yii::$app->user->id);
            Yii::$app->cache->delete('FollowNode'.Yii::$app->user->id);
        }
        if($this->type == 3) {
            Yii::$app->cache->delete('TopicCount'.$this->user_id);
            Yii::$app->cache->delete('NoticeCount'.$this->follow_id);
            Yii::$app->cache->delete('FollowTopic'.Yii::$app->user->id);

            $topic = Topic::findOne($this->follow_id);
            $topic->follow = $topic->follow + 1;
            $topic->save();
            if($topic->user_id != $this->user_id) {
                $notice = new Notice();
                $notice->from_user_id = $this->user_id;
                $notice->to_user_id = $topic->user_id;
                $notice->topic_id = $this->follow_id;
                $notice->type = 3;
                $notice->msg = '';
                $notice->created = time();
                $notice->save();
            }
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        if($this->type == 1) {
            Yii::$app->cache->delete('UserCount'.$this->user_id);
            Yii::$app->cache->delete('FollowUserId'.Yii::$app->user->id);
            Yii::$app->cache->delete('FollowUser'.Yii::$app->user->id);
        }
        if($this->type == 2) {
            Yii::$app->cache->delete('NodeCount'.$this->user_id);
            Yii::$app->cache->delete('FollowNodeId'.Yii::$app->user->id);
            Yii::$app->cache->delete('FollowNode'.Yii::$app->user->id);
        }
        if($this->type == 3) {
            Yii::$app->cache->delete('TopicCount'.$this->user_id);
            Yii::$app->cache->delete('NoticeCount'.$this->follow_id);
            Yii::$app->cache->delete('FollowTopic'.Yii::$app->user->id);

            $topic = Topic::findOne($this->follow_id);
            $topic->follow = $topic->follow - 1;
            $topic->save();
        }
        return parent::afterDelete();
    }
}