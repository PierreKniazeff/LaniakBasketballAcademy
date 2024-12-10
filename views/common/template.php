<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title; ?></title>
    <meta name="description" content="<?= $page_description; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>

    <!-- Navigation Menu -->
    <?php require_once "./views/common/menu.php"; ?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <?php require_once "./views/common/header.php" ?>
                <!-- Display Alert -->
                <?php if (!empty($_SESSION['alert'])): ?>
                    <div class="alert <?= $_SESSION['alert']['type']; ?>" role="alert">
                        <?= $_SESSION['alert']['message']; ?>
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
    <?php require_once "./views/common/footer.php"; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>