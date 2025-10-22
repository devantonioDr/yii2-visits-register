<?php

namespace console\controllers;

use common\components\MyHelpers;
use Exception;
use common\models\combo_user_slot1\ComboUserFileSlot1;
use common\models\combo_user_slot1\ComboUserListSlot1;
use Yii;
use yii\console\Controller;

class ComboUploadSlot1Controller extends Controller
{
    public function actionIndex()
    {

        $combo_user_file = ComboUserFileSlot1::find()
            ->where([
                'or',
                ['status' => ComboUserFileSlot1::IS_PENDING],
                ['and', ['status' => ComboUserFileSlot1::IS_BEING_PROCESSED], ['<', 'updated_at', strtotime('-2 minutes')]],
            ])
            ->one();

        if (!$combo_user_file) {
            echo "No hay combos por subir" . PHP_EOL;
            return;
        };

        $lines = file($this->getFilePath($combo_user_file['fileName']), FILE_SKIP_EMPTY_LINES);
        $amountOfLines = count($lines);
        $lastProcessedIdx = $combo_user_file['lastProcessedIdx'];
        $combo_user_file->status = ComboUserFileSlot1::IS_BEING_PROCESSED;


        while (true) {
            if ($lastProcessedIdx >= $amountOfLines) {
                $combo_user_file->status = ComboUserFileSlot1::IS_FINISHED;
                $combo_user_file->save();
                echo "Combo fue finalizado...." . PHP_EOL;
                break;
            };

            echo "Procesando linea $lastProcessedIdx " . PHP_EOL;
            $this->saveLine($combo_user_file->combo_user_id, $lines[$lastProcessedIdx]);


            $lastProcessedIdx++;

            $combo_user_file->lastProcessedIdx = $lastProcessedIdx;
            $combo_user_file->save();
        };
    }

    private function getFilePath($fileName)
    {
        $uploadPath = Yii::getAlias('@frontend/web/uploads/combos');
        return $uploadPath . '/' . $fileName;
    }


    private function lineToKeyValuePair($input)
    {
        // Initialize an empty array to hold the result
        $result = [];

        // Split the input string by the '|' delimiter
        $pairs = explode('|', $input);

        // Iterate through each pair
        foreach ($pairs as $pair) {
            // Split each pair by the '=' delimiter
            list($key, $value) = explode('=', $pair);

            // Trim whitespace from the key and value
            $key = trim(strtolower($key)); // Convert key to lowercase to standardize array keys
            $value = trim($value);

            // Add the trimmed key-value pair to the result array
            $result[$key] = $value;
        }

        return $result;
    }

    private function saveLine($combo_user_id, $line, $logged = null)
    {

        try {
            $formatted_line = $this->lineToKeyValuePair($line);

            if (count($formatted_line) < 3) return false;

            if (!MyHelpers::hasAtLeastTenDigits($formatted_line['number'])) return false;

            $email = $formatted_line['email'];
            $number = MyHelpers::normalizePhoneNumber($formatted_line['number']);
            $pass = $formatted_line['password'];

            $comboUserList = new ComboUserListSlot1();
            $comboUserList->combo_user_id = $combo_user_id;
            $comboUserList->full_email_pass =  "$email:$pass";
            $comboUserList->email = $email;
            $comboUserList->phone = $number;
            $comboUserList->pass = $pass;
            $comboUserList->logged = $logged;

            if ($comboUserList->save()) {
                echo "$email $number $pass guardado..".PHP_EOL;
            };

            return true;
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }
}
