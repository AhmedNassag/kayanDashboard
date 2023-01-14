<?php
namespace App\Traits;

use GuzzleHttp\Client;
use Log;

/**
 * trait NotificationTrait
 */

trait NotificationTrait
{
    public $API_KEY = 'AAAAKPVhqRA:APA91bGNPBH2tNstGxsKWmO7iErKXpS-d-vL4qnrV_28kk2JKhVRl1TcDFyK43_xML3cwUOWZZgHsOOOLJ0HVzV-zJPpC0wH_vuC8EUkKKzuKKLEh61yU_0npzQIBMGpWlEERA18mWYy';
    public $title = 'كيان';

    public function notificationAllClient($tokens,$body,$title,$type,$image){

        $SERVER_API_KEY = $this->API_KEY;

        $data = [

            "registration_ids" => $tokens,

            "data" => [
                'type' => $type,
            ],

            "notification" => [

                "title" => $title,

                "body" => $body,
                "image" =>$image,
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
