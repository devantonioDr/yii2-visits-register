<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=db_test',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'wadeshuler\sendgrid\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'apiKey' => '',
        ],
    ],
];


   