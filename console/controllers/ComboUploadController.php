<?php

namespace console\controllers;

use common\components\MyHelpers;
use common\models\cookies\Cookies;
use frontend\models\ComboUserFile;
use Yii;
use yii\console\Controller;

class ComboUploadController extends Controller
{

    public function actionIndex()
    {
        $combo_user_file = ComboUserFile::find()
            ->where([
                'or',
                ['status' => ComboUserFile::IS_PENDING],
                ['and', ['status' => ComboUserFile::IS_BEING_PROCESSED], ['<', 'updated_at', strtotime('-2 minutes')]],
            ])
            ->one();

        if (!$combo_user_file) {
            echo "No hay combos por subir" . PHP_EOL;
            return;
        };

        // Obtener el user_id desde ComboUser
        $comboUser = \frontend\models\ComboUser::findOne($combo_user_file->combo_user_id);
        $user_id = $comboUser ? $comboUser->userId : null;

        $lines = file($this->getFilePath($combo_user_file['fileName']), FILE_SKIP_EMPTY_LINES);
        $amountOfLines = count($lines);
        $lastProcessedIdx = $combo_user_file['lastProcessedIdx'];
        $combo_user_file->status = ComboUserFile::IS_BEING_PROCESSED;

        $batch = [];
        $batchSize = 1000;

        while ($lastProcessedIdx < $amountOfLines) {
            $line = $lines[$lastProcessedIdx];

            if (count(explode(':', $line)) < 2) {
                $lastProcessedIdx++;
                continue;
            }

            list($user, $pass) = explode(':', $line);

            // Normaliza y valida el teléfono
            $user = MyHelpers::normalizePhoneNumber(trim($user));
            if (!MyHelpers::hasAtLeastTenDigits($user)) {
                $lastProcessedIdx++;
                continue;
            }



            // Chequea si el teléfono ya existe en cookies
            if (!Cookies::find()->where(['phone' => $user])->exists()) {

                $pass = trim($pass);
                $batch[] = [
                    'combo_user_id' => $combo_user_file->combo_user_id,
                    'user_id' => $user_id,
                    'full_email_pass' => "$user:$pass",
                    'email' => $user,
                    'pass' => trim($pass),
                    'logged' => null,
                ];
            }

            $lastProcessedIdx++;
            $combo_user_file->lastProcessedIdx = $lastProcessedIdx;

            // Procesa el batch cuando llegue al tamaño definido
            if (count($batch) >= $batchSize) {
                $this->processBatchInsert($batch);
                $batch = [];
                $combo_user_file->save();
            }
        }

        // Inserta cualquier batch restante
        if (!empty($batch)) {
            $this->processBatchInsert($batch);
            $combo_user_file->save();
        }

        $combo_user_file->status = ComboUserFile::IS_FINISHED;
        $combo_user_file->save();
        echo "Combo fue finalizado...." . PHP_EOL;
    }

    private function processBatchInsert($batch)
    {
        if (empty($batch)) return;

        $db = Yii::$app->db;
        $sql = $db->queryBuilder->batchInsert(
            'combo_user_list',
            ['combo_user_id', 'user_id', 'full_email_pass', 'email', 'pass', 'logged'],
            $batch
        );

        // Si es MySQL, usa INSERT IGNORE
        if ($db->driverName === 'mysql') {
            $sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $sql);
        }

        $db->createCommand($sql)->execute();
    }


    private function getFilePath($fileName)
    {
        $uploadPath = Yii::getAlias('@frontend/web/uploads/combos');
        return $uploadPath . '/' . $fileName;
    }
}
