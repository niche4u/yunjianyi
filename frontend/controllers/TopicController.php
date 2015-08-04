<?php
namespace frontend\controllers;

use common\components\Helper;
use common\models\Reply;
use common\models\Node;
use common\models\Topic;
use common\models\TopicContent;
use common\models\User;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\HtmlPurifier;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Markdown;

/**
 * Site controller
 */
class TopicController extends FrontendController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionCreate($node = null)
    {
        if(Yii::$app->user->isGuest) {
            Yii::$app->getSession()->setFlash('danger', '你需要登陆之后才能提建议');
            return $this->redirect('/account/login?next=/topic/create');
        }

        if(Yii::$app->user->identity->email_status == 0) $this->goHome();

        $this->title = '提建议 - '.Yii::$app->name;
        $this->description = '';

        if (!empty($node)) {
            if (($model = Node::findOne(['enname' => $node])) !== null) {
                $node = $model;
            }
            else {
                $node = null;
            }
        }
        $model = new Topic();
        if (!empty($node)) $model->node_id = $node->id;
        $topicContent = new TopicContent();

        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            $this->redirect('/topic/'.$model->id);
        } else {
            return $this->render('create', [
                'model' => $model,
                'node' => $node,
                'topicContent' => $topicContent,
            ]);
        }
    }

    /**
     * Displays a single Topic model.
     * @param integer $id
     * @return mixed
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if($model->need_login == 1 && Yii::$app->user->isGuest) {
            Yii::$app->getSession()->setFlash('danger', '你访问的建议需要登陆之后才能查看');
            return $this->redirect('/account/login?next=/topic/'.$id);
        }

        if(!empty($model->node->bg) && $model->node->use_bg == 1) $this->bg = $model->node->bg;
        if(!empty($model->node->bg_color)) $this->bg_color = $model->node->bg_color;

        $this->title = $model->title.' - '.Yii::$app->name;
        if(isset($model->content->content)) $this->description = $model->node->name.' - '.$model->user->username.' - '.Helper::truncateUtf8String($model->content->content, 200);
        else $this->description = $model->node->name.' - '.$model->user->username.Helper::truncateUtf8String($model->title, 200);
        $this->canonical = Yii::$app->params['domain'].'topic/'.$id;

        $replyQuery = (new Query())
            ->select('reply.*, user.username, user.avatar, user.role')
            ->from(Reply::tableName())
            ->leftJoin(User::tableName(), 'user.id = reply.user_id')
            ->where(['reply.topic_id' => $id]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['ReplyPageSize'],
            'totalCount' => $replyQuery->count()
        ]);
        $replyList = $replyQuery->offset($pagination->offset)->limit($pagination->limit)->all();

        $reply = new Reply();

        if (!Yii::$app->user->isGuest) {
            $model->updateCounters(['click' => 1]);

            $reply = new Reply();
            if ($reply->load(Yii::$app->request->post()) && $reply->save()) {
                Yii::$app->cache->delete('ReplyCount');
                $this->redirect('/topic/'.$id.'#Reply');
            }
            return $this->render('view', [
                'model' => $model,
                'reply' => $reply,
                'replyList' => $replyList,
                'pagination'=> $pagination,

            ]);

        }
        else {
            return $this->render('view', [
                'model' => $model,
                'reply' => $reply,
                'replyList' => $replyList,
                'pagination'=> $pagination
            ]);
        }
    }

    /**
     * Displays a single Topic Preview.
     * @param integer $id
     * @return mixed
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionPreview()
    {
        $content = Yii::$app->request->post('content');
        if(Yii::$app->user->isGuest || $content == null) {
            return false;
        }
        else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return HtmlPurifier::process(Markdown::process($content, 'gfm-comment'));
        }
    }

    public function actionAppend($id)
    {
        if(Yii::$app->user->isGuest) {
            Yii::$app->getSession()->setFlash('danger', '你需要登陆之后才能添加附言');
            return $this->redirect('/account/login?next=/topic/append?id='.$id);
        }

        $model = $this->findModel($id);
        $this->title = $model->title.' - 添加附言 - '.Yii::$app->name;
        $this->description = '';

        if(time() - $model->created <= 300) {
            Yii::$app->getSession()->setFlash('danger', '你没有权限为本建议添加附言');
            return $this->redirect('/topic/'.$model->id);
        }

        $topicContent = new TopicContent();
        if ($model->user_id != Yii::$app->user->id) {
            Yii::$app->getSession()->setFlash('danger', '你没有权限为本建议添加附言');
            return $this->redirect('/topic/'.$model->id);
        }

        if (null != Yii::$app->request->post() && $model->append()) {
            $this->redirect('/topic/'.$model->id);
        } else {
            return $this->render('append', [
                'model' => $model,
                'topicContent' => $topicContent,
            ]);
        }
    }

    /**
     * Finds the Topic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Topic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Topic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
