<?php
// Inclusion de la config centrale pour avoir la constante URL et autres partout
require_once __DIR__ . '/../../config/config.php';

// (optionnel) Protection anti-accès direct – à activer selon choix d’architecture
// if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
//     header("Location: /");
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Titre par défaut'; ?></title>
    <meta name="description" content="<?= isset($page_description) ? htmlspecialchars($page_description) : ''; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Menu -->
    <?php require_once __DIR__ . '/menu.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <?php require_once __DIR__ . '/header.php'; ?>
                <!-- Display Alert -->
                <?php if (!empty($_SESSION['alert'])): ?>
                    <div class="alert <?= htmlspecialchars($_SESSION['alert']['type']); ?>" role="alert">
                        <?= htmlspecialchars($_SESSION['alert']['message']); ?>
                    </div>
                    <?php unset($_SESSION['alert']); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <!-- Page Content -->
            <?= $page_content; ?>
        </div>
    </div>
    <!-- Footer -->
    <?php require_once __DIR__ . '/footer.php'; ?>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
