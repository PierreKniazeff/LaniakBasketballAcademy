<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Effets d'animation</title>
    <style>
        /* Styles pour l'effet d'animation au survol */
        .grid h3:hover {
            color: blue; /* Changement de couleur au survol */
            text-decoration: underline; /* Ajout d'un soulignement au survol */
            transition: color 0.3s ease-in-out; /* Transition douce de la couleur du texte */
        }

        /* Styles pour l'effet d'animation au clic */
        .clicked-effect {
            animation: bounce 0.3s; /* Utilisation de l'animation "bounce" pendant 0.3s */
        }

        /* Définition de l'animation "bounce" */
        @keyframes bounce {
            0% {
                transform: translateY(0); /* Position de départ */
            }
            50% {
                transform: translateY(-10px); /* Premier rebondissement vers le haut */
            }
            100% {
                transform: translateY(0); /* Retour à la position initiale */
            }
        }

        /* Styles pour l'image en plein écran */
        .fullscreen-image {
            display: none; /* Par défaut, l'image est cachée */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Fond semi-transparent */
            z-index: 9999; /* Assurez-vous que l'image est au-dessus de tout le reste */
        }

        .fullscreen-image img {
            display: block;
            max-width: 100%;
            max-height: 100%;
            margin: auto;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
</head>

<body>
    <link href="public/css/.css" rel="stylesheet">

    <!-- <h1 class="roboto-font">A propos</h1> -->

    <div class="grid text-center roboto-font text-justify">
        <br><div class="g-col-6"><h3>Nos valeurs</h3></div><br>
        <div class="g-col-6"><h3>Notre approche du coaching</h3></div><br>
        <div class="g-col-6"><h3>Notre Equipe</h3></div><br>
        <div class="g-col-6"><h3>Joueurs coachés</h3></div><br>
        <div class="g-col-6"><h3>Partenaires</h3></div><br><br>
    </div>

    <footer>
        <div class="container-fluid">
            <?php include_once("views/common/footer.php"); ?>
        </div>
    </footer>

    <!-- Image en plein écran -->
    <div class="fullscreen-image" id="fullscreenImage">
        <img src="" alt="Image en plein écran">
    </div>

    <script>
        // Tableau des chemins d'accès des images
        const images = [
            "public/assets/images/NosValeurs.png",
            "public/assets/images/ApprocheCoaching.png",
            "public/assets/images/Equipe.png",
            "public/assets/images/JoueursCoachés.png",
            "public/assets/images/Partenaires.png"
        ];

        // Sélectionner tous les éléments h3 dans la classe "grid"
        const headers = document.querySelectorAll('.grid h3');

        // Fonction pour réinitialiser la classe "clicked-effect"
        const resetClickedEffect = (element) => {
            setTimeout(() => {
                element.classList.remove('clicked-effect');
            }, 300);
        };

        // Parcourir tous les éléments h3
        headers.forEach((header, index) => {
            // Ajouter un écouteur d'événements "click" pour afficher l'image en plein écran correspondante
            header.addEventListener('click', () => {
                // Ajouter la classe "clicked-effect" pour l'effet d'animation bounce
                header.classList.add('clicked-effect');
                resetClickedEffect(header);
                // Ajouter un délai avant d'afficher l'image en plein écran
                setTimeout(() => {
                    // Afficher l'image en plein écran correspondante
                    document.getElementById('fullscreenImage').style.display = 'block';
                    document.getElementById('fullscreenImage').querySelector('img').src = images[index];
                }, 300); // Délai de 300 millisecondes (correspondant à la durée de l'animation bounce)
            });
        });

        // Ajouter un événement de clic sur l'image en plein écran pour le masquer
        document.getElementById('fullscreenImage').addEventListener('click', () => {
            // Masquer l'image en plein écran
            document.getElementById('fullscreenImage').style.display = 'none';
        });
    </script>
</body>

</html>
