<?php

namespace frontend\modules\v1;


class Api extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        // Disable session for API
        \Yii::$app->user->enableSession = false;
        
        // Disable CSRF validation for API requests
        \Yii::$app->request->enableCsrfValidation = false;
        
        // Set response format to JSON
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }
}
