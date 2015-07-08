<?php

namespace xj\sitemap\actions;

use Yii;
use yii\base\Action;
use yii\base\ErrorException;
use xj\sitemap\models\Url;
use xj\sitemap\formaters\UrlsetResponseFormatter;

class SitemapUrlsetAction extends Action {

    /**
     * dataProvider
     * @var \yii\data\ActiveDataProvider
     */
    public $dataProvider;

    /**
     * remap type
     * @var bool
     */
    private $isClosure;

    /**
     * Remap Data to Url
     * @var Closure | []
     */
    public $remap;

    /**
     * gzip package.
     * @var bool
     */
    public $gzip = false;

    public function init() {

        if (is_array($this->remap)) {
            $this->isClosure = false;
        } elseif (is_callable($this->remap)) {
            $this->isClosure = true;
        } else {
            throw new ErrorException('remap is wrong type!.');
        }

        //init dataProvider
        $this->dataProvider->prepare();

        return parent::init();
    }

    /**
     * execute run()
     * @return []Url
     */
    public function run() {
        //setFormat
        $this->setFormatters();

        //return Url models
        return $this->getFromDataProvider();
    }

    private function setFormatters() {
        $currentPage = $this->dataProvider->getPagination()->getPage() + 1;
        $response = Yii::$app->response;
        $response->formatters[UrlsetResponseFormatter::FORMAT_URLSET] = new UrlsetResponseFormatter([
            'gzip' => $this->gzip,
            'gzipFilename' => 'sitemap.' . $currentPage . '.xml.gz',
        ]);
        $response->format = UrlsetResponseFormatter::FORMAT_URLSET;
    }

    /**
     * getFromDataProvider
     * @return []Url
     */
    private function getFromDataProvider() {
        $remap = $this->remap;
        $models = $this->dataProvider->getModels();
        $oModels = [];
        foreach ($models as $model) {
            if ($this->isClosure) {
                //function($model)
                //return Url
                $oModels[] = call_user_func($remap, $model);
            } else {
                $oModels[] = $this->remapModel($model, $this->remap);
            }
        }
        return $oModels;
    }

    /**
     * SourceModel Remap to SitemapModel
     * @param Model $model SourceModel
     * @param [] $remap Remap Table
     * @reutrn Url
     */
    private function remapModel($model, $remap) {
        $oModel = new Url();
        foreach ($remap as $dst => $src) {
            if (is_callable($src)) {
                //function($model)
                //return xj\sitemap\models\Sitemap
                $oModel->$dst = call_user_func($src, $model);
            } else {
                $oModel->$dst = $model->$src;
            }
        }
        return $oModel;
    }

}
