<?php
namespace console\controllers;

use yii\console\Controller;

class RedisController extends Controller
{

    public function actionIndex()
    {
        // \Yii::$app->cache->redis->hset('mykey','somevalue');
        // $value = \Yii::$app->cache->redis->hget('mykey');

        \Yii::$app->cache->set('fdjjh9tuertewcxzbvcbmnfdjjh9tuertewcxzbvcbmn', array(['data'=>'hello']),10);

        // Retrieve data from cache
        $cachedData = \Yii::$app->cache->get('fdjjh9tuertewcxzbvcbmnfdjjh9tuertewcxzbvcbmn');

        var_dump( $cachedData);
    }

}