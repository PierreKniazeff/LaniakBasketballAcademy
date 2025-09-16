<?php
// Sécurité optionnelle : empêcher l'accès direct au header (recommandé)
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}
// Inclusion config (adapter le chemin à l'arborescence !)
require_once __DIR__ . '/../../config/config.php';
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    <?= isset($page_title) ? htmlspecialchars($page_title) : 'Titre par défaut'; ?>
  </title>
  <meta name="description" content="<?= isset($page_description) ? htmlspecialchars($page_description) : ''; ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <!-- <link href="<?= URL ?>public/css/.css" rel="stylesheet"> -->
</head>

<header class="d-flex flex-wrap justify-content-center py-3 mb-4 sticky-top">
  <div class="container-fluid custom-container">
    <?php require_once __DIR__ . '/menu.php'; ?> <!-- Inclure le menu avec chemin relatif correct -->
  </div>
</header>

<style>
  .navbar {
    background-color: transparent !important;
  }

  header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
  }

  .custom-container {
    max-width: 1200px;
    margin: 0 auto;
  }
</style>
