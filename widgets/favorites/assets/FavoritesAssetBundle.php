<?php
namespace widgets\favorites\assets {
    final class FavoritesAssetBundle extends \yii\web\AssetBundle
    {
        public
                $basePath = "@webroot",
                $baseUrl = "@backendUrl",
                $css = [
//                    "css/favorites.css",
                        ],
                $js = [
                    'js/favorites.js'
                ],
                $jsOptions = ['position' => \yii\web\View::POS_END];

    }
}