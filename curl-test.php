<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
if(curl_errno($ch)) {
    echo 'Errore cURL: ' . curl_error($ch);
} else {
    echo 'Risposta cURL: ' . $response;
}
curl_close($ch);
