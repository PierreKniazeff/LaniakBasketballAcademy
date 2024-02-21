

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    <?= $page_title; ?>
  </title>
  <meta name="description" content="<?= $page_description; ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="public/css/.css" rel="stylesheet">
</head>

<body>
  <header class="d-flex flex-wrap justify-content-center py-3 mb-4 sticky-top">
    <div class="container-fluid custom-container">
      <?php require_once("menu.php") ?> <!-- Inclure le contenu du fichier menu.php -->
    </div>
  </header>
</body>
<style>
  .navbar {
    background-color: transparent !important;
  }

  /* Ajoutez cette règle pour positionner le header fixe en haut de la page */
  header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000; /* Assurez-vous que le header est au-dessus de tout le contenu */
  }

  /* Ajoutez cette règle pour limiter la largeur du container */
  .custom-container {
    max-width: 1200px; /* Définissez la largeur maximale que vous souhaitez */
    margin: 0 auto; /* Centrez le container */
  }
</style>

</html>