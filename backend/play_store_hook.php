<?php

/*
 * Required package
 *
 * google/apiclient
 */


// helper

function get_play_store_subscription_details($packageName, $subscriptionId, $purchaseToken)
{
    $configFile = base_path('service_account.json'); //Credentials file
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $configFile);
    $client = new Google\Client();
    $client->addScope(AndroidPublisher::ANDROIDPUBLISHER);
    $client->useApplicationDefaultCredentials();
    $client->fetchAccessTokenWithAssertion();
    $authorization = 'Authorization: Bearer ' . $client->getAccessToken()['access_token'];
    $url = "https://androidpublisher.googleapis.com/androidpublisher/v3/applications/$packageName/purchases/subscriptions/$subscriptionId/tokens/$purchaseToken";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    $res = json_decode($response);
    if (isset($res->error)) {
        return false;
    }
    $milliseconds = $res->expiryTimeMillis;
    $d = \DateTime::createFromFormat('U.u', number_format($milliseconds / 1000, 3, '.', ''));
    return Carbon::parse($d->format("Y-m-d H:i:s.u"))->toDateTimeString();
}

// event handler

public function handle($event)
{
    $data = $event->data;
    $packageName = $data->packageName;
    $purchaseToken = $data->subscriptionNotification->purchaseToken;
    $subscriptionId = $data->subscriptionNotification->subscriptionId;
    $type = $data->subscriptionNotification->notificationType;
}


// Hook in Controller

public function playHook(Request $request)
{
    try {
        $data = $request->message['data'] ?? "";
        $data = json_decode(base64_decode($data));
        $subscriptionId = $data->subscriptionNotification->subscriptionId;
        if ($subscriptionId === '{play_store_package_name}') {
            event(new PlayWebhookEvent($data)); // publish event listener to avoid failures and multi hooks
        }
    } catch (\Exception $exception) {
        \Log::error('webhookGoogle' . $exception->getMessage());
    }

    return response()->json('success');
}