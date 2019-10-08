<?php

namespace favorites\controllers;

use favorites\models\UserFavorites;
use Yii;

class FavoritesController extends \yii\web\Controller
{
    public $id = '';
    private $userUrl = '';
    private $userUrlName = '';
    private $pageType = '';
    
    public function beforeAction($action)
    {
        
        $this->userUrl = Yii::$app->request->get('pageUrl');
        $this->userUrlName = Yii::$app->request->get('pageName');
        $this->pageType = Yii::$app->request->get('pageType');
        
        return parent::beforeAction($action);
    }

    public function actionCreate()
    {
        $model = new UserFavorites();
        $model->userUrl = $this->userUrl;
        $model->userUrlName = $this->userUrlName;
        $model->pageType = $this->pageType;
        
        $model->saveFavorite();
        
        $this->id = $model->id;

        return $this->getAnswerFromModel($model);
    }

    public function actionDelete($id)
    {
        $model = UserFavorites::findOne($id);
        
        $this->id = $model->id;
        $this->userUrl = $model->url;
        $this->userUrlName = $model->url_name;
        $this->pageType = $model->url_type;

        $model->delete();

        return $this->getAnswerFromModel($model);
    }

    private function getAnswerFromModel()
    {

        $jsonArray[] = [
            "id"          => $this->id,
            "userUrl"     => $this->userUrl,
            "userUrlName" => $this->userUrlName,
            "pageType"    => $this->pageType,
        ];

        return json_encode($jsonArray);
    }
}