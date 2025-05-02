<?php
session_start();

// Codice di prova da commentare in produzione
if (true) {
  $postsJson = file_get_contents('post-di-prova.json');
  $allPosts = json_decode($postsJson, true);
  $_SESSION['allPosts'] = $allPosts;
  $loginUrl =  'analizza_post.php';
} else {
  require_once __DIR__ . '/vendor/autoload.php';
  $fb = new \Facebook\Facebook([
    'app_id' => '1004570211088665',
    'app_secret' => '920509e484011562b8272e8b142bb7c2',
    'default_graph_version' => 'v19.0',
  ]);
  $helper = $fb->getRedirectLoginHelper();
  $_SESSION['FBRLH_state'] = $_GET['state'] ?? null;
  $permissions = ['user_posts'];
  $loginUrl = $helper->getLoginUrl('https://www.casagrande-cesi.it/jobscanner/fb-callback.php', $permissions);
}


?>
<!doctype html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>JobScanner - Scan your social accunts for embarassing posts before job interviews</title>
  <link rel="icon" type="image/png" href="/jobscanner/favicon-96x96.png" sizes="96x96">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script>
    function openVideoPopup() {
      if (window.innerWidth > 768) {
        const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
        document.getElementById('videoFrame').src = 'https://www.youtube.com/embed/dQw4w9WgXcQ';
        videoModal.show();
      } else {
        window.location.href = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
      }
    }
  </script>
</head>

<body>

  <header class="hero">
    <div class="container hero-content">
      <img src="logo-white.png" style="width:50%" alt="JobScanner Logo" class="logo-img">
      <p class="lead">Prepara il tuo profilo social per il prossimo colloquio!</p>
      <button onclick="openVideoPopup()" class="btn btn-primary btn-lg">Guarda il video di presentazione</button>
    </div>
  </header>

  <div class="container text-center mt-5">
    <a href="<?php echo htmlspecialchars($loginUrl); ?>" class="btn btn-outline-primary btn-custom">Analizza post Facebook</a>
    <button class="btn btn-outline-info btn-custom" onclick="alert('La funzionalità di analisi dei post Instagram sarà disponibile a breve!')">Analizza post Instagram</button>
  </div>

  <div class="container text-center mt-4">

  </div>

  <!-- Modal YouTube -->
  <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="videoModalLabel">Video di presentazione</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
          <div class="ratio ratio-16x9">
            <iframe id="videoFrame" src="" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>

 <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>