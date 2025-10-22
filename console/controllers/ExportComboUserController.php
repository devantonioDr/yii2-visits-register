<?php

namespace console\controllers;
use Yii;
use yii\console\Controller;
use frontend\models\ComboUserList;
use frontend\models\ComboUser;
use frontend\models\User;
use yii\helpers\FileHelper;

class ExportComboUserController extends Controller
{
    const LOGGED_MAP = [
        1 => 'USED',
        2 => 'HIT_VENMO',
        3 => 'HIT_AFFIRM',
        4 => 'BAD',
        5 => 'OTRO_COMBO_MANAGER',
        6 => 'FUTURE_CALL',
        7 => 'HIT_KLARNA',
    ];

    public function actionExport()
    {
        ini_set('memory_limit', '2G');
        $basePath = Yii::getAlias('@frontend/web/uploads/downloads');

        $query = ComboUserList::find()
            ->select(['combo_user_id', 'logged', 'full_email_pass'])
            ->where(['not', ['full_email_pass' => null]])
            ->asArray();

        $total = ComboUserList::find()->where(['not', ['full_email_pass' => null]])->count();
        $processed = 0;

        // Pequeños caches para evitar consultas repetidas en el mismo batch
        $comboUserCache = [];
        $userCache = [];

        foreach ($query->batch(1000) as $rows) {
            foreach ($rows as $row) {
                $comboUserId = $row['combo_user_id'];

                // Cache comboUserId => userId
                if (!isset($comboUserCache[$comboUserId])) {
                    $comboUser = ComboUser::find()
                        ->select(['userId'])
                        ->where(['id' => $comboUserId])
                        ->asArray()
                        ->one();
                    if (!$comboUser) continue;
                    $comboUserCache[$comboUserId] = $comboUser['userId'];
                }
                $userId = $comboUserCache[$comboUserId];

                // Cache userId => username
                if (!isset($userCache[$userId])) {
                    $user = User::find()
                        ->select(['username'])
                        ->where(['id' => $userId])
                        ->asArray()
                        ->one();
                    if (!$user) continue;
                    $userCache[$userId] = $user['username'];
                }
                $username = $userCache[$userId];

                $logged = $row['logged'];
                $desc = self::LOGGED_MAP[$logged] ?? 'UNKNOWN';

                $userPath = $basePath . DIRECTORY_SEPARATOR . $username;
                FileHelper::createDirectory($userPath);

                $filePath = $userPath . DIRECTORY_SEPARATOR . $desc . '.txt';

                if (!file_exists($filePath)) {
                    file_put_contents($filePath, "# $desc\n", FILE_APPEND | LOCK_EX);
                }

                file_put_contents($filePath, $row['full_email_pass'] . "\n", FILE_APPEND | LOCK_EX);

                $processed++;
                if ($processed % 10000 === 0) {
                    echo "Procesados: $processed / $total\n";
                }
            }
            // Limpiar caché cada batch para no crecer en memoria
            $comboUserCache = [];
            $userCache = [];
        }
        echo "Exportación finalizada. Total procesados: $processed\n";
    }
}