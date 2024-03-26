<?php require_once("config.php"); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Titre de Page</title>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg align-items-center border-bottom-0 welcome-page">
            <?php if (isset($page_title) && $page_title !== 'Welcome') : ?>
                <img src="<?= BASE_URL ?>public/assets/images/logo1.PNG" width="140px" height="140px" alt="Logo" style="margin-right: 20px;" class="logolaniak move-down">
            <?php endif; ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item <?php if ($page_title === 'Welcome') echo 'active'; ?>">
                        <a class="nav-link" href="welcome">Welcome</a>
                    </li>
                    <li class="nav-item <?php if ($page_title === 'Accueil') echo 'active'; ?>">
                        <a class="nav-link" href="accueil">Accueil</a>
                    </li>
                    <!-- Ajoutez ici les autres éléments de la barre de navigation -->
                    <li class="nav-item <?php if ($page_title === 'A propos') echo 'active'; ?>">
                        <a class="nav-link" href="page1">A propos</a>
                    </li>
                    <li class="nav-item <?php if ($page_title === 'Programmes et Stages') echo 'active'; ?>">
                        <a class="nav-link" href="page2">Programmes</a>
                    </li>
                    <li class="nav-item <?php if ($page_title === 'Evénements et actualités') echo 'active'; ?>">
                        <a class="nav-link" href="page3">Evénements et actualités</a>
                    </li>
                    <li class="nav-item <?php if ($page_title === 'Galerie') echo 'active'; ?>">
                        <a class="nav-link" href="page4">Galerie</a>
                    </li>
                    <li class="nav-item <?php if ($page_title === 'Contact') echo 'active'; ?>">
                        <a class="nav-link" href="contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <img src="<?= BASE_URL ?>public/assets/images/USflag.PNG" alt="US Flag" width="30px" height="20px" style="margin-right: 10px;">

                    <?php
                        session_start();

                        // Convertir la largeur de l'écran en nombre
                        $screenWidth = isset($_COOKIE['screen_width']) ? intval($_COOKIE['screen_width']) : null;

                        // Vérifier si l'utilisateur est connecté
                        $userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'];

                        // Vérifier si la largeur de l'écran est inférieure ou égale à 990px
                        $screenWidthBelow990 = $screenWidth !== null && $screenWidth <= 990;

                        if ($userLoggedIn) {
                            // L'utilisateur est connecté
                            echo '<a href="profile.php" class="me-2 text-success"><i class="bi bi-person"></i>' . htmlspecialchars($_SESSION['user_prenom']) . '</a>';
                            echo '<a href="../controllers/logout.php" class="text-link"><i class="bi bi-box-arrow-right"></i>Déconnexion</a>';
                        } elseif (!($page_title === 'Welcome' && $screenWidthBelow990)) {
                            // L'utilisateur n'est pas connecté et la condition pour ne pas afficher les liens est remplie
                            echo '<a href="inscription" class="me-2 text-danger"><i class="bi bi-person-plus"></i>Inscription</a>';
                            echo '<a href="connexion" class="text-link"><i class="bi bi-person"></i>Connexion</a>';
                        }
                    
                    ?>

                </div>

                <script>
                    // Script JavaScript pour enregistrer la largeur de l'écran dans un cookie
                    document.addEventListener('DOMContentLoaded', function() {
                        document.cookie = "screen_width=" + screen.width;
                    });
                </script>

            </div>
        </nav>
    </div>

    </div>


    <style>
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
            margin-top: -20px;
        }

        /* Style pour les liens de navigation actifs */
        .navbar-nav .nav-item.active .nav-link {
            background-color: #000000;
            color: #ffffff;
        }

        /* Styles spécifiques à la page welcome */
        .welcome-page .navbar-nav .nav-item.active .nav-link {
            background-color: transparent;
            color: white;
        }

        /* Styles spécifiques à la page accueil */


        .logolaniak {
            /* Ajoutez les styles CSS que vous voulez appliquer à cette classe */
            margin-bottom: -25px;
        }



        /* Media queries si nécessaire */
        @media (max-width: 1200px) {
            .navbar-nav .nav-link {
                font-size: 16px;
                /* Remplacez par la valeur de taille de texte souhaitée */
            }

            .navbar-toggler {
                font-size: 16px;
                /* Ajustez également la taille du bouton nav */
            }

            .navbar-toggler-icon {
                font-size: 20px;
                /* Ajustez la taille de l'icône du bouton nav si nécessaire */
            }
        }

        /* Styles pour les pages autres que welcome */
        .welcome-page .navbar-nav .nav-item.active .nav-link {
            /* Ajoutez ici les styles que vous souhaitez appliquer aux liens de navigation actifs sur les pages autres que welcome */
            background-color: #000000;
            color: #ffffff;
        }
    </style>


</body>

</html>