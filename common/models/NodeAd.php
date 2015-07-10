<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "node_ad".
 *
 * @property integer $id
 * @property integer $node_id
 * @property string $content
 * @property integer $status
 */
class NodeAd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node_ad';
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
            'content' => '广告内容',
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

    //获取节点广告信息，有缓存就获取缓存
    static function NodeAd($node)
    {
        if(!$NodeAd = Yii::$app->cache->get('NodeAd'.$node))
        {
            $NodeAd = NodeAd::find()->where(['node_id' => $node])->asArray()->all();
            Yii::$app->cache->set('NodeAd'.$node, $NodeAd, 0);
        }
        return $NodeAd;
    }
}
