<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\cookies\Cookies;

class CookieJobController extends Controller
{
    /**
     * Convierte el campo cookie_json a string JSON válido (con comillas dobles externas).
     */
    public function actionConvertCookieJson()
    {
        $cookies = Cookies::find()->all();
        $count = 0;

        foreach ($cookies as $cookie) {
            $json = $cookie->cookie_json;

            // Si es array, conviértelo a string JSON
            if (is_array($json)) {
                $cookie->cookie_json = json_encode($json);
                if ($cookie->save(false, ['cookie_json'])) {
                    $count++;
                }
                continue;
            }

            // Si es string pero no tiene comillas dobles externas, intenta decodificar y volver a codificar
            if (is_string($json)) {
                $decoded = json_decode($json, true);
                if (is_array($decoded)) {
                    $cookie->cookie_json = json_encode($decoded);
                    if ($cookie->save(false, ['cookie_json'])) {
                        $count++;
                    }
                }
            }
        }

        echo "Convertidos $count registros.\n";
    }
}