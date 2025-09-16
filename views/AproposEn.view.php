<?php
// Protéger contre l'accès direct à la vue
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="public/css/.css" rel="stylesheet"> <!-- À remplacer par le vrai nom du fichier CSS -->

    <style>
        .grid h3:hover {
            cursor: pointer;
            color: blue;
            transition: color 0.3s ease-in-out;
        }
        .clicked-effect { animation: bounce 0.3s; }
        @keyframes bounce {
            0%   { transform: translateY(0); }
            50%  { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }
        .fullscreen-page {
            display: none;
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999; color: white; padding: 20px; overflow-y: auto;
        }
        @media (max-width: 600px) {
            .fullscreen-page { font-size: 14px; }
        }
    </style>
</head>

<body>
    <div class="grid text-center roboto-font text-justify">
        <br>
        <div class="g-col-6"><h3>Our Values</h3></div><br>
        <div class="g-col-6"><h3>Our Coaching Approach</h3></div><br>
        <div class="g-col-6"><h3>Our Team</h3></div><br>
        <div class="g-col-6"><h3>Coached Players</h3></div><br>
        <div class="g-col-6"><h3>Partners</h3></div><br><br>
    </div>

    <!-- Fullscreen page -->
    <div class="fullscreen-page" id="fullscreenPage">
        <!-- The content of accueil.view.php will be displayed here -->
    </div>

    <footer>
        <div class="container-fluid">
            <?php include_once __DIR__ . '/common/footer.php'; ?>
        </div>
    </footer>

    <?php include __DIR__ . '/../public/js/script2.php'; ?>
</body>
</html>
