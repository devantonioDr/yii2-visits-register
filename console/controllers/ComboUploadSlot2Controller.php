<?php

namespace console\controllers;

use common\components\MyHelpers;
use Exception;
use common\models\combo_user_slot2\ComboUserFileSlot2;
use common\models\combo_user_slot2\ComboUserListSlot2;
use common\models\combo_user_slot2\ComboUserTypes;
use Yii;
use yii\console\Controller;

class ComboUploadSlot2Controller extends Controller
{
    public function actionIndex()
    {

        $combo_user_file = ComboUserFileSlot2::find()
            ->where([
                'or',
                ['status' => ComboUserFileSlot2::IS_PENDING],
                ['and', ['status' => ComboUserFileSlot2::IS_BEING_PROCESSED], ['<', 'updated_at', strtotime('-2 minutes')]],
            ])
            ->one();

        if (!$combo_user_file) {
            echo "No hay combos por subir" . PHP_EOL;
            return;
        };

        $lines = file($this->getFilePath($combo_user_file['fileName']), FILE_SKIP_EMPTY_LINES);
        $amountOfLines = count($lines);
        $lastProcessedIdx = $combo_user_file['lastProcessedIdx'];
        $combo_user_file->status = ComboUserFileSlot2::IS_BEING_PROCESSED;


        while (true) {
            if ($lastProcessedIdx >= $amountOfLines) {
                $combo_user_file->status = ComboUserFileSlot2::IS_FINISHED;
                $combo_user_file->save();
                echo "Combo fue finalizado...." . PHP_EOL;
                break;
            };

            echo "Procesando linea $lastProcessedIdx " . PHP_EOL;
            if($combo_user_file->combo_type == ComboUserTypes::$TYPE_EMAIL_PASS){
                $this->saveEmailPassLine($combo_user_file->combo_user_id, $lines[$lastProcessedIdx]);
            }

            if($combo_user_file->combo_type == ComboUserTypes::$TYPE_USER_PASS){
                $this->saveUserPassLine($combo_user_file->combo_user_id, $lines[$lastProcessedIdx]);
            }


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

    private function saveEmailPassLine($combo_user_id, $line, $logged = null)
    {

        try {

            if (count(explode(':', $line)) < 2) return false;

            list($user, $pass) = explode(':', $line);

            if (!MyHelpers::validateEmail($user)) return false;


            $user = trim($user);
            $pass = trim($pass);

            $full_email_pass = "$user:$pass";

            // Store individual user and pass in their respective arrays
            $comboUserList = new ComboUserListSlot2();
            $comboUserList->combo_user_id = $combo_user_id;

            $comboUserList->full_email_pass =  $full_email_pass;

            $comboUserList->email = $user;
            $comboUserList->pass = $pass;
            
            $comboUserList->logged = $logged;
            if ($comboUserList->save()) {
                echo "$user $pass guardado..";
            };

            return true;
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }


    private function saveUserPassLine($combo_user_id, $line, $logged = null)
    {

        try {

            if (count(explode(':', $line)) < 2) return false;

            list($user, $pass) = explode(':', $line);

            


            $user = trim($user);
            $pass = trim($pass);

            if (empty($user) or empty($pass)) return false;

            $full_email_pass = "$user:$pass";

            // Store individual user and pass in their respective arrays
            $comboUserList = new ComboUserListSlot2();
            $comboUserList->combo_user_id = $combo_user_id;

            $comboUserList->full_email_pass =  $full_email_pass;

            $comboUserList->email = $user;
            $comboUserList->pass = $pass;
            
            $comboUserList->logged = $logged;
            if ($comboUserList->save()) {
                echo "$user $pass guardado..";
            };

            return true;
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }
}
