<?php
namespace App\Traits;

use GuzzleHttp\Client;
use Log;

/**
 * trait NotificationTrait
 */
trait NotificationTrait
{
    public $API_KEY = 'CjwKCAjwkYGVBhArEiwA4sZLuPo7Bm9DE01vi87Hc4aj_I-NUUi5o3ezXtkVCigvGn3REWEb5TaDVxoC1lkQAvD_BwE';
    public $title = 'كيان';

    public function notificationAllClient($tokens,$body,$title,$type){

        $SERVER_API_KEY = $this->API_KEY;

        $data = [

            "registration_ids" => $tokens,

            "data" => [
                'type' => $type,
            ],

            "notification" => [

                "title" => $title,

                "body" => $body,

                "sound" => "default", // required for sound on ios
                "click_action"=> "FLUTTER_NOTIFICATION_CLICK"

            ],

        ];

        $dataString = json_encode($data);

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        return $response;
    }
}
