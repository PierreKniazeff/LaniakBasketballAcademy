<?php require_once("config.php"); ?>

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<div class="container-fluid">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a class="nav-link" href="https://www.instagram.com/laniakbasketballacademy/?next=%2Fneozaka%2Ffeed%2F&hl=fr"><img src="<?= BASE_URL ?>public/assets/images/instagram.PNG" alt="Instagram"></a></li>
            <li class="nav-item"><a class="nav-link" href="https://www.tiktok.com/@laniakworkout"><img src="<?= BASE_URL ?>public/assets/images/tiktok.PNG" alt="Tiktok"></a></li>
            <li class="nav-item"><a class="nav-link" href="https://www.youtube.com/watch?v=hpxM3jMlIVs"><img src="<?= BASE_URL ?>public/assets/images/youtube.PNG" alt="Youtube"></a></li>
            <li class="nav-item"><a class="nav-link" href="https://www.facebook.com/Laniak"><img src="<?= BASE_URL ?>public/assets/images/facebook.PNG" alt="Facebook"></a></li>
            <li class="nav-item"><a class="nav-link" href="https://www.snapchat.com/add/laniak95?share_id=B30dGYh7NA0&locale=fr-FR"><img src="<?= BASE_URL ?>public/assets/images/snapchat.PNG" alt="Snapchat"></a></li>
        </ul>
        <style>
            .nav-link img {
                max-width: 5rem;
                /* Taille initiale des icônes */
            }

            @media (max-width: 1200px) {
                .nav-link img {
                    max-width: 5rem;
                    /* Réduire la taille des icônes pour les petits écrans */
                }
            }

            @media (max-width: 768px) {
                .nav-link img {
                    max-width: 4rem;
                    /* Réduire la taille des icônes pour les écrans encore plus petits */
                }
            }
        </style>
    </footer>
</div>