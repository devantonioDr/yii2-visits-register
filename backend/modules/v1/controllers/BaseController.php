<?php

namespace backend\modules\v1\controllers;

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
        
        // CORS filter must be the first behavior to run
        // This ensures preflight OPTIONS requests are handled correctly
        $behaviors = array_merge([
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => false,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => [],
                ],
            ],
        ], $behaviors);
        
        // Add content negotiator for JSON responses
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        
        return $behaviors;
    }
    
    /**
     * Handle OPTIONS preflight requests
     * @return array
     */
    public function actionOptions()
    {
        return [];
    }
}
