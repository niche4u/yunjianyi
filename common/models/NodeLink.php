<?php

namespace common\models;

use common\components\Helper;
use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Markdown;

/**
 * This is the model class for table "node_link".
 *
 * @property integer $id
 * @property integer $node_id
 * @property string $content
 * @property integer $status
 */
class NodeLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['node_id', 'content'], 'required'],
            [['node_id', 'status'], 'integer'],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'node_id' => '节点',
            'content' => '链接正文',
            'status' => '启用',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(Node::className(), ['id' => 'node_id']);
    }

    //获取节点推荐链接信息，有缓存就获取缓存
    static function NodeLink($node)
    {
        $NodeLink = Yii::$app->cache->get('NodeLink'.$node);
        if(!isset($NodeLink))
        {
            $NodeLink = NodeLink::find()->where(['node_id' => $node])->asArray()->all();
            Yii::$app->cache->set('NodeLink'.$node, $NodeLink, 0);
        }
        return $NodeLink;
    }

    public function afterFind()
    {
        $this->content  = Helper::autoLink(HtmlPurifier::process(Markdown::process($this->content, 'gfm-comment')));
        parent::afterFind();
    }
}
