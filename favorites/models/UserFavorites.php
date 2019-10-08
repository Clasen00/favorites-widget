<?php

namespace favorites\models;

use common\components\Date;
use Yii;

/**
 * This is the model class for table "user_favorites".
 *
 * @property int $id
 * @property string $url
 * @property string $url_name
 * @property string $url_type
 * @property int $user_id
 * @property int $cdate_int
 */
class UserFavorites extends \yii\db\ActiveRecord
{
    const URL_TYPE_DEFAULT = '';
    
    public $userUrl;
    public $userUrlName;
    public $pageType;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_favorites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'url_name', 'user_id', 'cdate_int', 'url_type'], 'required'],
            [['url', 'url_name', 'url_type'], 'string'],
            [['user_id', 'cdate_int'], 'integer'],
        ];
    }
    
    public function attributes()
    {
        return [
            'id',
            'url',
            'url_name',
            'url_type',
            'user_id',
            'cdate_int',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'url_name' => 'Имя ссылки',
            'url_type' => 'Тип ссылки',
            'user_id' => 'Пользователь',
            'cdate_int' => 'Дата добавления',
        ];
    }
    
    public function saveFavorite()
    {
        $this->url = $this->userUrl;
        $this->url_name = $this->userUrlName;
        $this->url_type = $this::URL_TYPE_DEFAULT;
        $this->user_id = Yii::$app->user->id;
        $this->cdate_int = Date::now();
        $this->save();
    }
        
        return $urlType;
    }

}
