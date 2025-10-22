<?php

namespace console\controllers;

use frontend\models\Votp;
use Yii;
use yii\console\Controller;
use common\models\User;
use yii\db\Expression;

class DeleteOldDataController extends Controller
{
    public function actionIndex()
    {
        $tenMinutesAgo = new Expression('NOW() - INTERVAL 10 MINUTE');

       // Delete records older than 10 minutes
       $deletedRows = Votp::deleteAll(['<', 'timeorder', $tenMinutesAgo]);

       echo "$deletedRows votp fueron borrados".PHP_EOL;
    }


    public function actionTest()
    {
        $llamadas = Votp::find()->count();

        echo "$llamadas llamadas....".PHP_EOL;

       
    }
}
