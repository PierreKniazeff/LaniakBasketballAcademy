<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <!-- <link href="public/css/.css" rel="stylesheet"> -->
    <!-- Style pour le fond -->


<style>
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
        background-size: 100% 100%;
        /* Taille initiale de l'image pour tous les écrans */
        background-position: center;
        background-repeat: no-repeat;
    }

    /* Media queries pour adapter l'image de fond en fonction de la largeur de l'écran */
    @media (max-width: 990px) {
        body {
            background-image: url('./public/assets/images/NewAccueil4.jpg');
            /* Pour écrans <= 990px */
        }

       .navbar-nav .nav-link {
            color: white !important;
            /* Définir la couleur du texte en blanc pour les nav links sur la page welcome pour les écrans inférieurs à 990px */
        }
    }

    @media (min-width: 991px) {
        body {
            background-image: url('./public/assets/images/PageAccueil4.png');
            /* Pour écrans > 990px */
        }
    }

    .content {
        padding: 20px;
    }

    footer {
        background-color: transparent !important;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    .container-fluid {
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    .nav-link {
        color: black !important;
        /* Assurez-vous que cette règle a la priorité */
    }

    .welcome-page .navbar {
        margin-top: -90px;
        /* Ajustez selon le besoin */
    }

    .navbar-nav {
        margin-bottom: 40px;
        /* Ajustez selon le besoin */
    }

    .navbar-collapse {
        margin-right: 50px;
        /* Espacement à droite */
    }

    .d-flex {
        margin-left: 140px;
        /* Espacement à droite */
    }

    /* Styles communs pour la barre de navigation */
    .navbar-nav .nav-link {
        font-size: 1em;
    }

    .navbar-toggler {
        font-size: 1.5em;
    }

    .navbar-toggler-icon {
        font-size: 2em;
    }

    .navbar {
        margin-top: -25px;
    }

    body:not(.welcome-page) .navbar-nav .nav-item.active .nav-link {
        background-color: #000000 !important;
        color: #ffffff !important;
    }

    .welcome-page .nav-link {
        color: black;
        /* Couleur par défaut */
    }

    /* Styles pour les écrans de moins de 600px */
    @media (max-width: 600px) {
        body {
            background-attachment: local;
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
            /* Centrer l'icône */
        }
    }


    
</style>


</head>

<body>
    <div class="content">
        <!-- Ajoutez ici votre contenu principal -->
    </div>

    <!-- Ajoutez la barre de navigation ici -->
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg align-items-center border-bottom-0 welcome-page">
            <!-- Ajoutez ici le contenu de la barre de navigation -->
            <?php require_once "views/common/menu.php" ?>
        </nav>
    </div>

    <!-- Ajoutez le footer ici -->
    <footer>
        <div class="container-fluid">
            <?php include_once "views/common/footer.php"; ?>
        </div>
    </footer>
</body>

</html>