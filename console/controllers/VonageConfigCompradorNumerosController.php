<?php

namespace console\controllers;

use common\components\MyHelpers;
use common\models\vonage\VonageResourceProvider;
use Exception;
use frontend\models\VonageConfig;
use frontend\models\VonageConfigCompradorNumerosLog;
use frontend\models\VonageConfigCompradorNumeros;
use yii\console\Controller;

class VonageConfigCompradorNumerosController extends Controller
{

    private function apagarCompradoresSinActividad()
    {
        $compradores = VonageConfigCompradorNumeros::find()->where(['status' => VonageConfigCompradorNumeros::Encendido])->all();

        // Apagar si la última actividad hace más tiempo que el rate.
        foreach ($compradores as $comprador) {

            $frecuencia = $comprador->rate;
            // Calcular la diferencia en minutos
            $ultimaActividad = MyHelpers::timeAgoInMinutes($comprador->last_logged_activity_at);

            // Verificar si la diferencia es mayor que la frecuencia
            if ($ultimaActividad >= $frecuencia) {

                VonageConfigCompradorNumerosLog::registerApagadoAuto($comprador->user_id);
                $comprador->status = VonageConfigCompradorNumeros::Apagado;
                $comprador->save();
                echo "Apagado automatico para user_id:$comprador->user_id" . PHP_EOL;
                continue;
            }
            echo "No necesita apagado para user_id:$comprador->user_id" . PHP_EOL;
        }
    }


    private function comprarNumeros(VonageConfig $config, VonageConfigCompradorNumeros $comprador)
    {
        try {

            $vonageProvider = new VonageResourceProvider();

            $vonageProvider->setVonageAccount(
                $config->app_id,
                $config->api_secret
            );

            $availableNumbers = $vonageProvider->getAvailableNumbersByPrefix("US", $comprador->prefixes, $comprador->number_type);

            $purchasedNumber = implode(',', $vonageProvider->purchaseNumbers($availableNumbers, 1));

            if (!empty($purchasedNumber)) {
                VonageConfigCompradorNumerosLog::registerNumerosComprados($comprador->user_id, $purchasedNumber);
            } else {
                VonageConfigCompradorNumerosLog::registerError($comprador->user_id, " APP_ID: $config->app_id, No hay Números para comprar.");
            };

            return $purchasedNumber;
        } catch (Exception $e) {
            // Handle Twilio API request failure
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    private function asignarNumerosAConfigs(
        VonageConfigCompradorNumeros $comprador
    ) {


        $configs = VonageConfig::find()->where(['user_id' => $comprador['user_id'], 'active' => 1])->all();
        $oathToken = [];

        // Comprar numero a cada auth token.
        echo "[asignarNumerosAConfigs]" . PHP_EOL;

        foreach ($configs as $config) {
            if (!isset($oathToken[$config['app_id']])) {
                //
                $oathToken[$config['app_id']] = $this->comprarNumeros($config, $comprador);
                // $oathToken[$config['app_id']] = "896786";
                $comprador->last_purchase_at = time();
                $comprador->save();
                echo "Compra de números para user_id:$comprador->user_id" . PHP_EOL;
            }
        }

        // TODO hacer otro foreach para asignar los numeros a las configs correspondientes.
        foreach ($configs as $config) {

            if (isset($oathToken[$config['app_id']]) && !empty($oathToken[$config['app_id']])) {

                $config->last_purchase_date = time();
                $config->numbers = $oathToken[$config['app_id']];

                if ($config->save()) {
                    echo "Numeros " . $oathToken[$config['app_id']] . " asignados a " . $config['etiqueta'] . PHP_EOL;
                }
                var_dump($config->errors);
            }
        }
    }


    private function revisarCompradores()
    {

        $compradores = VonageConfigCompradorNumeros::find()->where(['status' => VonageConfigCompradorNumeros::Encendido])->all();
        $encendidos = count($compradores);

        echo "Compradores encendidos $encendidos" . PHP_EOL;

        foreach ($compradores as $comprador) {

            $frecuencia = $comprador->rate;
            // Calcular la diferencia en minutos
            $ultimaCompra = MyHelpers::timeAgoInMinutes($comprador->last_purchase_at);

            if ($ultimaCompra >= $frecuencia) {

                $this->asignarNumerosAConfigs($comprador);
                continue;
            }

            echo "User id: $comprador->user_id no requiere comprar." . PHP_EOL;
        };
    }



    public function actionIndex()
    {
        $this->apagarCompradoresSinActividad();

        $this->revisarCompradores();
    }


    public function actionTest()
    {



        $vonageProvider = new VonageResourceProvider();

        // // Set Vonage account credentials
        $vonageProvider->setVonageAccount('bfa5c912', 'hwwFymF2IbxoZdNU');

        // Use Vonage methods as needed
        $availableNumbers = $vonageProvider->getAvailableNumbersByPrefix("US", "1844");
        var_dump($availableNumbers);
        // $purchasedNumber = $vonageProvider->purchaseNumbers($availableNumbers,1);
        // echo "Comprado ". $purchasedNumber[0].PHP_EOL;
        // $totalSpentToday = $vonageProvider->getBalance();
        // var_dump($totalSpentToday);



    }

    public function actionSimulacroLogged()
    {
        $compradores = VonageConfigCompradorNumeros::find()->where(['status' => VonageConfigCompradorNumeros::Encendido])->all();



        foreach ($compradores as $comprador) {

            $comprador->last_logged_activity_at = time();
            $comprador->save();
            echo "User id: $comprador->user_id no requiere comprar." . PHP_EOL;
        };
    }
}
