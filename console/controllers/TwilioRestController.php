<?php

namespace console\controllers;

use common\models\twilio\TwilioResourceProvider;
use Exception;
use frontend\models\TwilioConfig;
use Twilio\Rest\Client;
use yii\console\Controller;
use GuzzleHttp\Client as GuzzleHttp;

class TwilioRestController extends Controller
{
    private $twilioResourceProvider;

    // Injecting dependencies through the constructor
    public function __construct($id, $module, TwilioResourceProvider $twilioResourceProvider, $config = [])
    {
        $this->twilioResourceProvider = $twilioResourceProvider;
        parent::__construct($id, $module, $config);
    }

    // Obtener todo el balance consumido de un subaccount.
    public function actionObtenerGasto()
    {
        $allTwilioConfigs = TwilioConfig::find()->where(['active' => 1])->all();

        foreach ($allTwilioConfigs as $config) {
            try {
                $this->twilioResourceProvider->setTwilioAccount(
                    $config['account_sid'],
                    $config['auth_token']
                );
                $totalSpentToday = $this->twilioResourceProvider->getTotalSpentToday();
                echo "Total spent today: $" . number_format($totalSpentToday, 2);
                $config->spent_today = (float) $totalSpentToday;
                $config->save();
                var_dump($config->errors);
                continue;
            } catch (Exception $e) {
                echo "An exception occurred: " . $e->getMessage();
            }
        }
    }


    public function actionComprarNumero()
    {
        $allTwilioConfigs = TwilioConfig::find()->all();

        foreach ($allTwilioConfigs as $config) {

            if ($config->active == 0) continue;

            try {

                $this->twilioResourceProvider->setTwilioAccount(
                    $config['account_sid'],
                    $config['auth_token']
                );
                $available_numbers = $this->twilioResourceProvider->getAvailableNumbersByPrefix("850",300,"mobile");

        
                if (count($available_numbers) > 0) {
                   
                    for ($i = 0; $i < count($available_numbers); $i++) {
                        $number = $available_numbers[$i]->phoneNumber;
                        // Purchase the number (adjust as needed)
                        // Note: You need to replace 'your_sms_application_sid' and 'your_voice_application_sid'
                        var_dump($number);
                    }

                    // Purchase the first available toll-free number
                    // $phoneNumber = $this->twilioResourceProvider->getTwilio()->incomingPhoneNumbers
                    //     ->create([
                    //         'phoneNumber' => $availableNumbers[0]->phoneNumber,
                    //     ]);

                    // Output the purchased phone number details
                    // echo 'Phone Number SID: ' . $phoneNumber->sid;
                    // echo 'Phone Number: ' . $phoneNumber->phoneNumber;
                } else {
                    echo 'No available toll-free numbers with the specified prefix.'.PHP_EOL;
                }
            } catch (Exception $e) {
                echo "An exception occurred: " . $e->getMessage();
                // $config->spent_today = 0;
                // $config->save();
            }
        }
    }



    // Obtener todo el balance consumido de un subaccount.
    public function actionBalanceDisponible()
    {
        $allTwilioConfigs = TwilioConfig::find()->all();

        foreach ($allTwilioConfigs as $config) {
        }
    }
}
