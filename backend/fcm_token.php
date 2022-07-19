<?php

function fcm_notify($firebaseToken, $title, $body = '', $data = null)
{
    try {
        $SERVER_API_KEY = env('FIREBASE_SERVER_API_KEY');
        $dataString = [
            "registration_ids" => [$firebaseToken],
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],
            "data" => $data
        ];
        $dataString = json_encode($dataString);
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
        return curl_exec($ch);
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
    }
}
