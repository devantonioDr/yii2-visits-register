<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'America/Santo_Domingo',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
        ],
        'eventTracker' => [
            'class' => 'common\components\EventTracker',
        ],
    ],
    'modules' => [
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
        ],
        'logreader' => [
            'class' => 'zhuravljov\yii\logreader\Module',
            'aliases' => [
                'Frontend Errors' => '@frontend/runtime/logs/app.log',
                'Backend Errors' => '@backend/runtime/logs/app.log',
                'Console Errors' => '@console/runtime/logs/app.log',
            ],
        ],
    ],


];
