<?php
session_start();

// Codice di prova da commentare in produzione
if (false) {
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="style.css">
  <script>
    // URL del video (usala anche in openVideoPopup)
    const videoURL = 'https://www.youtube.com/watch?v=YYwlrkMBZAg';

    function openVideoPopup() {
      if (window.innerWidth > 768) {
        const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
        document.getElementById('videoFrame').src = videoURL + '?autoplay=1';
        videoModal.show();
      } else {
        window.location.href = 'https://www.youtube.com/watch?v=YYwlrkMBZAg';
      }
    }
  </script>

</head>

<body>

  <header class="hero">
    <div class="container hero-content">
      <img src="logo-white.png" style="width:50%" alt="JobScanner Logo" class="logo-img">
      <p class="lead"> Prepara il tuo profilo social per il prossimo colloquio: il nostro filtro intelligente ti aiuta a individuare post potenzialmente imbarazzanti prima di candidarti.</p>
      <button onclick="openVideoPopup()" class="btn btn-primary btn-lg">Guarda il video di presentazione</button>
    </div>
  </header>
  <?php include 'disclaimer.php'; ?>
  <div class="container text-center mt-5">
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <!-- Pulsante Facebook -->
      <a
        href="<?php echo htmlspecialchars($loginUrl); ?>"
        class="btn btn-fb btn-custom d-flex align-items-center">
        <i class="bi bi-facebook fs-4 me-2"></i>
        Analizza post Facebook
      </a>

      <!-- Pulsante Instagram -->
      <button
        class="btn btn-ig btn-custom d-flex align-items-center"
        onclick="alert('La funzionalità di analisi dei post Instagram sarà disponibile a breve!')">
        <i class="bi bi-instagram fs-4 me-2"></i>
        Analizza post Instagram
      </button>
    </div>
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
  <script>
    // Al close del modal, azzera la src per fermare il video
    document
      .getElementById('videoModal')
      .addEventListener('hidden.bs.modal', function() {
        document.getElementById('videoFrame').src = '';
      });
  </script>
</body>

</html>