<!DOCTYPE html>
<html lang="fr">

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

</html>