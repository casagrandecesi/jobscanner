<?php
session_start();
$allPosts = $_SESSION['allPosts'] ?? [];
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>JobScanner - Analisi Post Facebook</title>
  <link rel="icon" type="image/png" href="/jobscanner/favicon-96x96.png" sizes="96x96">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    .hero {
      background-image: url('hero.jpg');
      background-size: cover;
      background-position: center;
      padding: 100px 0;
      color: white;
      text-align: center;
    }

    .hero img.logo-img {
      width: 50%;
      max-width: 200px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <header class="hero">
    <div class="container hero-content">
      <img src="logo-white.png" alt="JobScanner Logo" class="logo-img">
      <h1 class="display-4">Analisi Post Facebook</h1>
      <p class="lead">Ecco i tuoi post estratti!</p>
      <a href="index.php" class="btn btn-light btn-lg mt-3">Torna alla Home</a>
    </div>
  </header>

  <div class="container my-5">
    <div class="row">
      <?php if (empty($allPosts)): ?>
        <div class="col-12 text-center">
          <p class="fs-4">Nessun post disponibile.</p>
        </div>
      <?php else: ?>
        <?php foreach ($allPosts as $post): ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
              <div class="card-body d-flex flex-column">
                <?php if (!empty($post['created_time']['date'])): ?>
                  <h6 class="card-subtitle mb-2 text-muted"><?php echo date('d/m/Y H:i', strtotime($post['created_time']['date'])); ?> UTC</h6>
                <?php endif; ?>
                <?php if (!empty($post['message'])): ?>
                  <p class="card-text flex-grow-1"><?php echo nl2br(htmlspecialchars($post['message'])); ?></p>
                <?php else: ?>
                  <p class="card-text text-muted flex-grow-1">Nessun contenuto testuale.</p>
                <?php endif; ?>
                <?php if (!empty($post['id'])): ?>
                  <a href="https://facebook.com/<?php echo htmlspecialchars($post['id']); ?>" target="_blank" class="mt-auto btn btn-primary">Vedi su Facebook</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <footer class="bg-light py-4">
    <div class="container text-center">
      <p class="mb-0">&copy; <?php echo date('Y'); ?> JobScanner - Tutti i diritti riservati</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
