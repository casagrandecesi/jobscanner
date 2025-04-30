<?php
// Inizia la sessione per gestire il parametro "state" usato per la validazione CSRF
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use Facebook\Facebook;

// Inizializza il client Facebook
$fb = new Facebook([
    'app_id' => '1004570211088665',
    'app_secret' => '920509e484011562b8272e8b142bb7c2',
    'default_graph_version' => 'v19.0',
]);

// Prende il helper per il login e la validazione CSRF
$helper = $fb->getRedirectLoginHelper();

try {
    // Scambia il codice di autorizzazione con un access token (usa lo stesso redirect URI configurato nell'app FB)
    $accessToken = $helper->getAccessToken('https://www.casagrande-cesi.it/jobscanner/fb-callback.php');
} catch(\Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (! isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Errore: " . $helper->getError() . "\n";
        echo "Codice: " . $helper->getErrorCode() . "\n";
        echo "Razionale: " . $helper->getErrorReason() . "\n";
        echo "Descrizione: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Usa il token per chiamate successive
$oAuth2Client = $fb->getOAuth2Client();

// Se il token è a breve durata, richiedi uno long-lived
if (! $accessToken->isLongLived()) {
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (\Facebook\Exceptions\FacebookSDKException $e) {
        echo "Errore nel prolungare il token: " . $e->getMessage();
        exit;
    }
}

// Salva il token per sessione (o database)
$_SESSION['fb_access_token'] = (string) $accessToken;

// Richiedi i post dell'utente (con paginazione)
try {
    $response = $fb->get('/me/posts?fields=id,message,created_time', $accessToken);
} catch(\Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$postsEdge = $response->getGraphEdge();
$allPosts = [];
do {
    foreach ($postsEdge as $post) {
        $allPosts[] = $post->asArray();
    }
    $postsEdge = $fb->next($postsEdge);
} while ($postsEdge);

header('Content-Type: application/json');
$_SESSION['allPosts'] = $allPosts;
header('Location analizza_post.php');
