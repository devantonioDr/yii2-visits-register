<?php
$params = array_merge(
  require __DIR__ . '/../../common/config/params.php',
  require __DIR__ . '/../../common/config/params-local.php',
  require __DIR__ . '/params.php',
  require __DIR__ . '/params-local.php'
);

return [
  'id' => 'app-frontend',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'controllerNamespace' => 'frontend\controllers',
  'modules' => [
    'v1' => [
      'class' => 'frontend\modules\v1\Api',
    ]
  ],
  'components' => [
    'request' => [
      'csrfParam' => '_csrf-frontend',
    ],
    'user' => [
      'identityClass' => 'common\models\User',
      'enableAutoLogin' => true,
      'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
    ],
    'session' => [
      // this is the name of the session cookie used for login on the frontend
      'name' => 'advanced-frontend',
    ],
    'log' => [
      'traceLevel' => YII_DEBUG ? 3 : 0,
      'targets' => [
        [
          'class' => \yii\log\FileTarget::class,
          'levels' => ['error', 'warning'],
        ],
      ],
    ],
    'errorHandler' => [
      'errorAction' => 'site/error',
    ],

    'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [
        // API v1 routes
        ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/event', 'pluralize' => false],
        'v1/event/track' => 'v1/event/track',
        'v1/event/stats' => 'v1/event/stats',
      ],
    ],

  ],
  'homeUrl' => '/site/index',
  'params' => $params,
];
