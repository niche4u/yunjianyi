<?php

namespace xj\sitemap\actions;

use Yii;
use yii\base\Action;
use yii\helpers\Url;
use xj\sitemap\models\Sitemap;
use xj\sitemap\formaters\IndexResponseFormatter;

class SitemapIndexAction extends Action {

    /**
     * dataProvider
     * @var ActiveDataProvider
     */
    public $dataProvider;

    /**
     * default route to Sitemap urlset
     * @var []
     */
    public $route = ['site/sitemap'];

    /**
     * remap type
     * @var bool
     */
    private $isClosure;

    /**
     * Custom Loc Index
     * @var Closure
     * @example
     * function($currentPage, $pageParam) {return new Sitemap();}
     */
    public $remap;

    public function init() {

        if (is_callable($this->remap)) {
            $this->isClosure = true;
        } else {
            $this->isClosure = false;
            $this->remap = null;
        }

        //init dataProvider
        $this->dataProvider->prepare();

        return parent::init();
    }

    /**
     * 
     * @return []Sitemap
     */
    public function run() {
        //set format
        $this->setFormatters();

        //return Sitemap models
        return $this->getFromDataProvider();
    }

    private function setFormatters() {
        $response = Yii::$app->response;
        $response->formatters[IndexResponseFormatter::FORMAT_INDEX] = new IndexResponseFormatter();
        $response->format = IndexResponseFormatter::FORMAT_INDEX;
    }

    /**
     * getFromDataProvider
     * @return []Sitemap
     */
    private function getFromDataProvider() {
        $pagination = $this->dataProvider->getPagination();
        $pageCount = $pagination->pageCount;
        $pageParam = $pagination->pageParam;

        $indexModels = [];
        for ($i = 0; $i < $pageCount; ++$i) {
            $currentPage = $i + 1;
            if ($this->isClosure) {
                $indexModels[] = call_user_func($this->remap, $currentPage, $pageParam);
            } else {
                $indexModels[] = $this->getModel($currentPage, $this->route, $pageParam);
            }
        }

        return $indexModels;
    }

    /**
     * get Default Model
     * @param int $currentPage
     * @param array $route
     * @param int $pageParam
     * @return Sitemap
     */
    private function getModel($currentPage, $route, $pageParam) {
        $route[$pageParam] = $currentPage;
        $loc = Url::toRoute($route, true);
        $lastmod = date(DATE_W3C);
        return Sitemap::create($loc, $lastmod);
    }

}
