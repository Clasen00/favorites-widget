<?php

namespace \widgets\favorites;

use favorites\models\UserFavorites;
use favorites\assets\FavoritesAssetBundle;
use common\components\widgets\Widget;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Виджет "Избранное"
 * 
 * @return html 
 */
class FavoritesWidget extends Widget {
    
     public $pageType = '';


     public function init() {
         
        $this->setPageType();
        
        parent::init();
    }

    public function run() {
        $model = UserFavorites::find()->where(['user_id' => Yii::$app->user->id])->orderBy('cdate_int')->all();
        $urlTypeArray = $this->collectUserUrlTypes($model);
        
        FavoritesAssetBundle::register($this->view);
        
        $currentModelId = $this->getCurrentModelId($model);
        
        
        return $this->render('favoritesWidget', [
            'urlTypeArray' => $urlTypeArray,
            'model' => $model,
            'currentModel' => $currentModelId,
            'pageType' => $this->pageType,
        ]);
    }
    
    private function collectUserUrlTypes($model)
    {
        $urlTypeArray = [];
        
        foreach ($model as $key => $value) {
            $urlTypeArray[] = $value->url_type;
        }
        
        return array_unique($urlTypeArray);
    }
    
    private function getCurrentModelId($model)
    {
        $requestWithoutAmpersand = stristr(Yii::$app->request->url, '&', true);
        $request = urldecode($requestWithoutAmpersand) ?: urldecode(Yii::$app->request->url);

        foreach ($model as $key => $value) {
            if ($value->url ===  $request) {
                return $value->id;
            }
        }
    }


    private function setPageType()
    {
        $requestUrl = Yii::$app->request->url;
        $controllerId = Yii::$app->controller->id;

        $allowableViews = ['chat/view', 'chat-theme/update', 'answer', 'voting/update', 'texttranslationpost', 'texttranslation/update', 'news/', 'news/add', 'news/subject', 'newslist/subject', 'subject/update'];
        
        foreach ($allowableViews as $viewsName) {
            if (strpos($requestUrl, $viewsName) !== false || strpos($controllerId, $viewsName) !== false) {
                return $this->pageType = $viewsName;
            }
        }
    }
}
