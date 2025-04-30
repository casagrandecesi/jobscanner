<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

if (!isset($_SESSION['fb_access_token'])) {
    echo "Non sei autenticato.";
    exit;
}

$fb = new \Facebook\Facebook([
    'app_id' => '1004570211088665',
    'app_secret' => '920509e484011562b8272e8b142bb7c2',
    'default_graph_version' => 'v19.0',
]);

try {
    $response = $fb->get('/me/posts?fields=message,story,created_time', $_SESSION['fb_access_token']);
    $posts = $response->getGraphEdge();

    echo "<h2>I tuoi ultimi post:</h2>";
    foreach ($posts as $post) {
        $text = $post['message'] ?? $post['story'] ?? 'Senza contenuto';
        echo "<div style='margin-bottom:10px;'><strong>{$post['created_time']->format('d/m/Y H:i')}</strong>: {$text}</div>";
    }

} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Errore Graph: ' . $e->getMessage();
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Errore SDK: ' . $e->getMessage();
}
?>