<?php

namespace common\models;

use Yii;
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
 * @property integer $is_hot
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
            [['tab_id', 'parent_id', 'is_hot', 'use_bg', 'need_login', 'sort', 'created'], 'integer'],
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
            'is_hot' => '是否热门节点',
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
     * 获取节点下面的主题
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasMany(Topic::className(), ['node_id' => 'id']);
    }

    /**
     * 热门节点
     * @param int $num 获取几条热门主题
     * @return array|\yii\db\ActiveRecord[]
     */
    static function HotNode($num = 20)
    {
        return Node::find()->where(['is_hot' => 1])->limit($num)->all();
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

            if(isset($this->tab_id)) Yii::$app->cache->delete('subnodeid'.Tab::findOne($this->tab_id)->enname);

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

            if(isset($this->tab_id)) Yii::$app->cache->delete('subnodeid'.Tab::findOne($this->tab_id)->enname);

            return $this->save();
        }
        return false;
    }
}
