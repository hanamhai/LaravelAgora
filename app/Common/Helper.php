<?php

function prx($arr){
    echo"<pre>";print_r($arr);die();
}
function cretaAgoraProject($name){
    $CustomerKey = env('CustomerKey');

    $CustomerSecret = env('CustomerSecret');

    $credentials = $CustomerKey . ":" . $CustomerSecret;

    $base64Credentials = base64_encode($credentials);

    $arr_header = "Authorization: Basic " . $base64Credentials;

    $curl = curl_init();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.agora.io/dev/v1/project',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{"name": "'. $name . '", "enable_sign_key": true}',
        CURLOPT_HTTPHEADER => array(
            $arr_header,
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response);
        prx($result);
    return $result;
}