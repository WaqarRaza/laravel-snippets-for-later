<?php

//voip device token provided by ios dev
//bundle id with suffix voip
//voip certificate and convert it into pem file

trait VoIPNotification
{
    function sendVoIPNotification($data)
    {
        $token = $this->voip_device_token;
        $build_id = "com.renesis.Eternity.voip";
        $ch = curl_init("https://api.development.push.apple.com/3/device/$token");
        $body ['aps'] = array(
            "alert" => array(
                "status" => 1,
                "title" => "Eternity",
                "body" => $data['message'],
            ),
            "data" => $data,
            "badge" => 1,
            "sound" => "default"
        );
        $headers = array("apns-topic: $build_id", "apns-expiration: 0");
        $curlconfig = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_SSLCERT => storage_path('VoIPCertificate.pem'),
            CURLOPT_SSLCERTPASSWD => " ",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => $headers
        );
        curl_setopt_array($ch, $curlconfig);
        $res = curl_exec($ch);
        if ($res === FALSE) {
            echo('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
    }
}
