<?php

namespace frontend\modules\v1\controllers;

use yii\rest\Controller;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;

/**
 * Base API Controller
 * 
 * Provides API endpoints for all controllers
 * No authentication required
 */
class BaseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Remove authentication requirement
        unset($behaviors['authenticator']);
        
        // Add CORS support
        $behaviors['cors'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age' => 86400,
            ],
        ];
        
        // Add content negotiator for JSON responses
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        
        return $behaviors;
    }
}
