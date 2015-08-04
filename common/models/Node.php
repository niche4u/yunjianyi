<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "node".
 *
 * @property integer $id
 * @property integer $tab_id
 * @property string $name
 * @property string $enname
 * @property integer $parent_id
 * @property string $desc
 * @property string $logo
 * @property integer $is_hidden
 * @property integer $need_login
 * @property string $bg
 * @property string $use_bg
 * @property string $bg_color
 * @property integer $sort
 * @property integer $created
 *
 * @property Node $parent
 * @property Node[] $nodes
 * @property Tab $tab
 */
class Node extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tab_id', 'parent_id', 'is_hidden', 'use_bg', 'need_login', 'sort', 'created'], 'integer'],
            [['name', 'enname', 'desc', 'use_bg', 'created'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['enname'], 'string', 'max' => 45],
            [['desc'], 'string', 'max' => 150],
            ['logo', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024 * 1024 * 2, 'skipOnEmpty' => true],
            ['bg', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024 * 1024 * 2, 'skipOnEmpty' => true],
            ['bg_color', 'string', 'max' => 20, 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '节点ID',
            'tab_id' => '所属tab',
            'name' => '节点名称',
            'enname' => '英文名称',
            'parent_id' => '父节点',
            'desc' => '节点描述',
            'logo' => 'logo',
            'is_hidden' => '是否隐藏节点',
            'need_login' => '需要登陆',
            'bg' => '背景图',
            'use_bg' => '启用背景图片',
            'bg_color' => '背景颜色',
            'sort' => '排序',
            'created' => '创建时间',
        ];
    }

    public function getLogo80()
    {
        return Yii::$app->params['nodeUrl'].'/80/'.$this->logo;
    }

    public function getLogo48()
    {
        return Yii::$app->params['nodeUrl'].'/48/'.$this->logo;
    }

    public function getLogo24()
    {
        return Yii::$app->params['nodeUrl'].'/24/'.$this->logo;
    }

    public function getLogo80Show()
    {
        return Html::img(Yii::$app->params['nodeUrl'].'/80/'.$this->logo);
    }

    public function getLogo48Show()
    {
        return Html::img(Yii::$app->params['nodeUrl'].'/48/'.$this->logo);
    }

    public function getLogo24Show()
    {
        return Html::img(Yii::$app->params['nodeUrl'].'/24/'.$this->logo);
    }

    /**
     * 获取所在tab
     * @return \yii\db\ActiveQuery
     */
    public function getTab()
    {
        return $this->hasOne(Tab::className(), ['id' => 'tab_id']);
    }

    /**
     * 获取父节点
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Node::className(), ['id' => 'parent_id']);
    }

    /**
     * 获取子节点
     * @return \yii\db\ActiveQuery
     */
    public function getNodes()
    {
        return $this->hasMany(Node::className(), ['parent_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * 获取相关节点
     * @return \yii\db\ActiveQuery
     */
    public function getRelated()
    {
        return $this->hasMany(Node::className(), ['parent_id' => 'parent_id'])->where('id != '.$this->id)->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * 获取节点下面的建议
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasMany(Topic::className(), ['node_id' => 'id']);
    }

    /**
     * 热门节点
     * @param int $num 获取热门节点
     * @return array|\yii\db\ActiveRecord[]
     */
    static function HotNode($num = 15)
    {
        if(!$hotNode = Yii::$app->cache->get('hotNode'.$num))
        {
            $hotNode = (new Query())->select('node.enname, node.name')->from(Topic::tableName().' topic')->leftJoin(Node::tableName().' node', 'node.id = topic.node_id')->where('node.is_hidden = 0')->groupBy('topic.node_id')->orderBy('topic.node_id')->limit($num)->all();
            Yii::$app->cache->set('hotNode'.$num, $hotNode, 86400);
        }
        return $hotNode;
    }

    /**
     * 最新节点
     * @param int $num 获取最新节点
     * @return array|\yii\db\ActiveRecord[]
     */
    static function NewNode($num = 20)
    {
        if(!$NewNode = Yii::$app->cache->get('NewNode'.$num))
        {
            $NewNode = (new Query())->select('node.enname, node.name')->from(Node::tableName().' node')->orderBy('node.id DESC')->limit($num)->all();
            Yii::$app->cache->set('NewNode'.$num, $NewNode, 86400);
        }
        return $NewNode;
    }

    //获取节点信息，有缓存就获取缓存
    static function Info($id)
    {
        if(!$nodeInfo = Yii::$app->cache->get('node'.$id))
        {
            $nodeInfo = Node::find()->where(['id' => $id])->asArray()->one();
            Yii::$app->cache->set('node'.$id, $nodeInfo, 0);
        }
        return $nodeInfo;
    }

    public function edit()
    {
        if ($this->validate()) {

            $this->logo = UploadedFile::getInstance($this, 'logo');
            if ($this->logo != null) {
                $filename = date('Ymdhis') . rand(1000, 9999);
                $this->logo->saveAs(Yii::getAlias('@node').'/origin/' . $filename . '.' . $this->logo->extension);
                $extension = $this->logo->extension;
                $this->logo = $filename . '.' . $extension;
                Image::thumbnail(Yii::getAlias('@node').'/origin/' . $filename . '.' . $extension, 80, 80)->save(Yii::getAlias('@node').'/80/' . $filename . '.' . $extension, ['quality' => 80]);
                Image::thumbnail(Yii::getAlias('@node').'/origin/' . $filename . '.' . $extension, 48, 48)->save(Yii::getAlias('@node').'/48/' . $filename . '.' . $extension, ['quality' => 80]);
                Image::thumbnail(Yii::getAlias('@node').'/origin/' . $filename . '.' . $extension, 24, 24)->save(Yii::getAlias('@node').'/24/' . $filename . '.' . $extension, ['quality' => 80]);
            }
            if (!empty($this->logo)) {
                if(!empty($this->oldAttributes['logo'])) {
                    if(file_exists(Yii::getAlias('@node').'/origin/'.$this->oldAttributes['logo'])) unlink(Yii::getAlias('@node').'/origin/'.$this->oldAttributes['logo']);
                    if(file_exists(Yii::getAlias('@node').'/80/'.$this->oldAttributes['logo'])) unlink(Yii::getAlias('@node').'/80/'.$this->oldAttributes['logo']);
                    if(file_exists(Yii::getAlias('@node').'/48/'.$this->oldAttributes['logo'])) unlink(Yii::getAlias('@node').'/48/'.$this->oldAttributes['logo']);
                    if(file_exists(Yii::getAlias('@node').'/24/'.$this->oldAttributes['logo'])) unlink(Yii::getAlias('@node').'/24/'.$this->oldAttributes['logo']);
                }
            }
            else {
                $this->logo = $this->oldAttributes['logo'];
            }

            $this->bg = UploadedFile::getInstance($this, 'bg');
            if ($this->bg != null && $this->validate()) {
                $filename = date('Ymdhis') . rand(1000, 9999);
                $this->bg->saveAs(Yii::getAlias('@bg').'/' . $filename . '.' . $this->bg->extension);
                $extension = $this->bg->extension;
                $this->bg = $filename . '.' . $extension;
            }
            if (!empty($this->bg)) {
                if(!empty($this->oldAttributes['bg'])) {
                    if(file_exists(Yii::getAlias('@bg').'/'.$this->oldAttributes['bg'])) unlink(Yii::getAlias('@bg').'/'.$this->oldAttributes['bg']);
                }
            }
            else {
                $this->bg = $this->oldAttributes['bg'];
            }

            if(!empty($this->tab_id)) Yii::$app->cache->delete('subnodeid'.Tab::findOne($this->tab_id)->enname);

            return $this->save();
        }
        return false;
    }


    public function create()
    {
        $this->created = time();
        if ($this->validate()) {
            $this->logo = UploadedFile::getInstance($this, 'logo');
            if ($this->logo != null) {
                $filename = date('Ymdhis') . rand(1000, 9999);
                $this->logo->saveAs(Yii::getAlias('@node').'/origin/' . $filename . '.' . $this->logo->extension);
                $extension = $this->logo->extension;
                $this->logo = $filename . '.' . $extension;
                Image::thumbnail(Yii::getAlias('@node').'/origin/' . $filename . '.' . $extension, 80, 80)->save(Yii::getAlias('@node').'/80/' . $filename . '.' . $extension, ['quality' => 80]);
                Image::thumbnail(Yii::getAlias('@node').'/origin/' . $filename . '.' . $extension, 48, 48)->save(Yii::getAlias('@node').'/48/' . $filename . '.' . $extension, ['quality' => 80]);
                Image::thumbnail(Yii::getAlias('@node').'/origin/' . $filename . '.' . $extension, 24, 24)->save(Yii::getAlias('@node').'/24/' . $filename . '.' . $extension, ['quality' => 80]);
            }
            else {
                $this->addError('logo', 'logo不能为空');
                return false;
            }

            $this->bg = UploadedFile::getInstance($this, 'bg');
            if ($this->bg != null) {
                $filename = date('Ymdhis') . rand(1000, 9999);
                $this->bg->saveAs(Yii::getAlias('@bg').'/' . $filename . '.' . $this->bg->extension);
                $extension = $this->bg->extension;
                $this->bg = $filename . '.' . $extension;
            }

            if(!empty($this->tab_id)) Yii::$app->cache->delete('subnodeid'.Tab::findOne($this->tab_id)->enname);

            return $this->save();
        }
        return false;
    }

    /**
     * 获取子节点
     * @param $parent_id
     * @param bool $onlyId
     * @return array|\yii\db\ActiveRecord[]
     */
    static function SubNode($parent_id, $onlyId = true)
    {
        if($onlyId) {
            if(!$NodeSubNode = Yii::$app->cache->get('NodeSubNodeId'.$parent_id))
            {
                $NodeSubNode = ArrayHelper::map(Node::find()->select('id')->where(['parent_id' => $parent_id])->asArray('id')->all(), 'id', 'id');
                Yii::$app->cache->set('NodeSubNodeId'.$parent_id, $NodeSubNode, 0);
            }
        }
        else {
            if(!$NodeSubNode = Yii::$app->cache->get('NodeSubNode'.$parent_id))
            {
                $NodeSubNode = (new Query())->from(Node::tableName())->select('node.enname, node.name, node.logo')->where(['parent_id' => $parent_id])->all();
                Yii::$app->cache->set('NodeSubNode'.$parent_id, $NodeSubNode, 0);
            }
        }

        return $NodeSubNode;
    }

    /**
     * 获取全部节点
     * @return array|\yii\db\ActiveRecord[]
     */
    static function AllNode()
    {

        if(!$NodeAllNode = Yii::$app->cache->get('NodeAllNode'))
        {
            $NodeAllNode = ArrayHelper::map(Node::find()->select('id, name')->asArray()->all(), 'id', 'name');
            Yii::$app->cache->set('NodeAllNode', $NodeAllNode, 0);
        }

        return $NodeAllNode;
    }

    /**
     * 获取相关节点
     * @param $node_id
     * @param $parent_id
     * @param bool $onlyId
     * @return array|\yii\db\ActiveRecord[]
     */
    static function RelatedNode($node_id, $parent_id, $onlyId = true)
    {
        if($onlyId) {
            if(!$NodeRelatedNode = Yii::$app->cache->get('NodeRelatedNodeId'.$node_id))
            {
                $NodeRelatedNode = ArrayHelper::map(Node::find()->select('id')->where(['parent_id' => $parent_id])->andWhere('id != '.$node_id)->asArray('id')->all(), 'id', 'id');
                Yii::$app->cache->set('NodeRelatedNodeId'.$node_id, $NodeRelatedNode, 0);
            }
        }
        else {
            if(!$NodeRelatedNode = Yii::$app->cache->get('NodeRelatedNode'.$node_id))
            {
                $NodeRelatedNode = (new Query())->from(Node::tableName())->select('enname, name, logo')->where(['parent_id' => $parent_id])->andWhere('id != '.$node_id)->all();
                Yii::$app->cache->set('NodeRelatedNode'.$node_id, $NodeRelatedNode, 0);
            }
        }
        return $NodeRelatedNode;
    }
}
