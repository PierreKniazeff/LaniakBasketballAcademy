<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <!-- Style pour le fond -->
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-image: url('public/assets/images/PageAccueil.jpg');
            background-size: 100% 100%;
            /* Taille initiale de l'image pour les écrans supérieurs à 992px */
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }

        .content {
            /* Ajoutez du style pour positionner le contenu de la page */
            padding: 20px;
        }

        footer {
            /* Ajoutez les mêmes classes et styles que ceux de votre barre de navigation */
            background-color: transparent !important;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .container-fluid {
            /* Ajoutez les mêmes styles pour la classe container-fluid que ceux de votre barre de navigation */
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .nav-link {
            color: black !important;
            /* Assurez-vous que cette règle a la priorité */
        }

        /* Style pour ajuster la position de la barre de navigation uniquement sur la page welcome */
        .welcome-page .navbar {
            /* Ajustez la marge supérieure pour déplacer la barre de navigation vers le haut */
            margin-top: -90px;
            /* Remplacez XX par le nombre de pixels que vous souhaitez ajuster */
        }

        .navbar-nav {
            margin-bottom: 40px;
            /* Ajustez cette valeur selon vos besoins */
        }

        .navbar-collapse {
            margin-right: 50px;
            /* Espacement vers la droite */
        }

        .d-flex {
            margin-left: 140px;
            /* Espacement vers la droite */
        }

        @media (max-width: 600px) {

            /* Pour les écrans inférieurs à 992px, ajustez la taille de l'image pour qu'elle soit entièrement visible sans être déformée */
            body {
                background-size: contain;
            }

            .d-flex {
                margin-left: 0;
                /* Réinitialiser la marge à gauche */
                margin-top: 10px;
                /* Ajuster la marge supérieure */
                text-align: center;
                /* Centrer les éléments */
            }

            .d-flex a {
                display: block;
                /* Afficher les liens comme des blocs */
                margin-bottom: 10px;
                /* Espacement entre les liens */
            }

            .d-flex img {
                margin: 0 auto;
                /* Centrer l'icône USFlag */
            }
        }
    </style>
    <link href="public/css/.css" rel="stylesheet">
</head>

<body>
    <div class="content">
        <!-- Ajoutez ici votre contenu principal -->
    </div>

    <!-- Ajoutez la barre de navigation ici -->
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg align-items-center border-bottom-0 welcome-page">
            <!-- Ajoutez ici le contenu de la barre de navigation -->
            <?php require_once("views/common/menu.php") ?>
        </nav>
    </div>

    <!-- Ajoutez le footer ici -->
    <footer>
        <div class="container-fluid">
            <?php include_once("views/common/footer.php"); ?>
        </div>
    </footer>
</body>

</html>