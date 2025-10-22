<?php

namespace console\controllers;

use common\components\MyHelpers;
use common\models\twilio\TwilioResourceProvider;
use Exception;
use frontend\models\TwilioConfig;
use frontend\models\TwilioConfigCompradorNumeros;
use frontend\models\TwilioConfigCompradorNumerosLog;
use yii\console\Controller;

class TwilioConfigCompradorNumerosController extends Controller
{

    // private function apagarCompradoresSinActividad()
    // {
    //     $compradores = TwilioConfigCompradorNumeros::find()->where(['status' => TwilioConfigCompradorNumeros::Encendido])->all();

    //     // Apagar si la última actividad hace más tiempo que el rate.
    //     foreach ($compradores as $comprador) {

    //         $frecuencia = $comprador->rate;
    //         // Calcular la diferencia en minutos
    //         $ultimaActividad = MyHelpers::timeAgoInMinutes($comprador->last_logged_activity_at);

    //         // Verificar si la diferencia es mayor que la frecuencia
    //         if ($ultimaActividad >= $frecuencia) {

    //             TwilioConfigCompradorNumerosLog::registerApagadoAuto($comprador->user_id);
    //             $comprador->status = TwilioConfigCompradorNumeros::Apagado;
    //             $comprador->save();
    //             echo "Apagado automatico para user_id:$comprador->user_id" . PHP_EOL;
    //         }
    //     }
    // }


    // private function comprarNumeros(TwilioConfig $config, TwilioConfigCompradorNumeros $comprador)
    // {

    //     // SID of the twilio account
    //     $config->account_sid;
    //     // AUTH TOKEN
    //     $config->auth_token;
    //     // The quantity of numbers to be purchased
    //     $comprador->quantity;
    //     // An array of comma sperated
    //     $comprador->prefixes;


    //     try {

    //         $twilio = new TwilioResourceProvider();

    //         $twilio->setTwilioAccount($config->account_sid, $config->auth_token);

    //         $availableNumbers = $twilio->getAvailableNumbersByPrefix($comprador->prefixes, $comprador->quantity,$comprador->number_type);

    //         $purchasedNumbers =  implode(',', $twilio->purchaseNumbers($availableNumbers, $comprador->quantity));

    //         TwilioConfigCompradorNumerosLog::registerNumerosComprados($comprador->user_id, $purchasedNumbers);

    //         return $purchasedNumbers;
    //     } catch (Exception $e) {
    //         // Handle Twilio API request failure
    //         echo 'Error: ' . $e->getMessage();
    //         return null;
    //     }
    // }

    // private function asignarNumerosAConfigs(TwilioConfigCompradorNumeros $comprador)
    // {


    //     $configs = TwilioConfig::find()->where(['userId' => $comprador['user_id'], 'active' => 1])->all();
    //     $oathToken = [];

    //     // Comprar numero a cada auth token.


    //     foreach ($configs as $config) {
    //         if (!isset($oathToken[$config['auth_token']])) {

    //             $oathToken[$config['auth_token']] = $this->comprarNumeros($config, $comprador);
    //             $comprador->last_purchase_at = time();
    //             $comprador->save();
    //             echo "Compra de números para user_id:$comprador->user_id" . PHP_EOL;
    //         }
    //     }

    //     // TODO hacer otro foreach para asignar los numeros a las configs correspondientes.
    //     foreach ($configs as $config) {

    //         if (isset($oathToken[$config['auth_token']]) && !empty($oathToken[$config['auth_token']])) {

    //             $config->last_purchase_time = time();
    //             $config->numbers = $oathToken[$config['auth_token']];
    //             if ($config->save()) {
    //                 echo "Numeros " . $oathToken[$config['auth_token']] . " asignados a " . $config['etiqueta'] . PHP_EOL;
    //             }
    //         }
    //     }
    // }


    // private function revisarCompradores()
    // {

    //     $compradores = TwilioConfigCompradorNumeros::find()->where(['status' => TwilioConfigCompradorNumeros::Encendido])->all();

    //     foreach ($compradores as $comprador) {

    //         $frecuencia = $comprador->rate;
    //         // Calcular la diferencia en minutos
    //         $ultimaCompra = MyHelpers::timeAgoInMinutes($comprador->last_purchase_at);

    //         if ($ultimaCompra >= $frecuencia) {

    //             $this->asignarNumerosAConfigs($comprador);
    //             continue;
    //         }

    //         echo "User id: $comprador->user_id no requiere comprar." . PHP_EOL;
    //     };
    // }



    // public function actionIndex()
    // {
    //     return "";
    //     $this->apagarCompradoresSinActividad();

    //     $this->revisarCompradores();
    // }


    // public function actionSimulacroLogged()
    // {
    //     $compradores = TwilioConfigCompradorNumeros::find()->where(['status' => TwilioConfigCompradorNumeros::Encendido])->all();



    //     foreach ($compradores as $comprador) {


    //         $comprador->last_logged_activity_at = time();
    //         $comprador->save();
    //         echo "User id: $comprador->user_id no requiere comprar." . PHP_EOL;
    //     };
    // }
}
