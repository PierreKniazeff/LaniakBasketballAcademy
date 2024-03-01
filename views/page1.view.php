<link href="public/css/.css" rel="stylesheet">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A propos</title>

    <style>
        /* Styles pour l'effet d'animation au survol */
        .grid h3:hover {
            cursor: pointer;
            /* Ajoute un effet curseur pointeur */
            color: blue;
            /* Changement de couleur au survol */
            transition: color 0.3s ease-in-out;
            /* Transition douce de la couleur du texte */
        }

        /* Styles pour l'effet d'animation au clic */
        .clicked-effect {
            animation: bounce 0.3s;
            /* Utilisation de l'animation "bounce" pendant 0.3s */
        }

        /* Définition de l'animation "bounce" */
        @keyframes bounce {
            0% {
                transform: translateY(0);
                /* Position de départ */
            }

            50% {
                transform: translateY(-10px);
                /* Premier rebondissement vers le haut */
            }

            100% {
                transform: translateY(0);
                /* Retour à la position initiale */
            }
        }

        /* Styles pour la page en plein écran */
        .fullscreen-page {
            display: none;
            /* Par défaut, la page est cachée */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            /* Fond semi-transparent */
            z-index: 9999;
            /* Assurez-vous que la page est au-dessus de tout le reste */
            color: white;
            padding: 20px;
            overflow-y: auto;
            /* Permet le défilement vertical si le contenu est plus long que l'écran */
        }

        /* Media queries pour les écrans de petite taille */
        @media (max-width: 600px) {
            .fullscreen-page {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="grid text-center roboto-font text-justify">
        <br>
        <div class="g-col-6">
            <h3>Nos valeurs</h3>
        </div><br>
        <div class="g-col-6">
            <h3>Notre approche du coaching</h3>
        </div><br>
        <div class="g-col-6">
            <h3>Notre Equipe</h3>
        </div><br>
        <div class="g-col-6">
            <h3>Joueurs coachés</h3>
        </div><br>
        <div class="g-col-6">
            <h3>Partenaires</h3>
        </div><br><br>
    </div>



    <!-- Page en plein écran -->
    <div class="fullscreen-page" id="fullscreenPage">
        <!-- Le contenu de la page accueil.view.php sera affiché ici -->
    </div>

    <footer>
        <div class="container-fluid">
            <?php include_once("views/common/footer.php"); ?>
        </div>
    </footer>


    <?php include("public/js/script.php") ?>

</body>

</html>