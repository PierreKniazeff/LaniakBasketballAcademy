<?php
// Inclusion de la config centrale (adaptée à l'arborescence)
require_once __DIR__ . '/../../config/config.php';

// Démarrer la session si elle n'est pas déjà active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Gestion du changement de langue
if (isset($_POST['lang'])) {
    $_SESSION['lang'] = $_POST['lang'];
    // Redirection vers la même page avec la langue mise à jour
    header("Location: " . URL . "index.php?page=" . (isset($_POST['currentPage']) ? $_POST['currentPage'] : 'welcome'));
    exit;
}

// Définir la langue active (français par défaut)
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';

// Vérifier si l'utilisateur est connecté
$userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'];
$userPrenom = $_SESSION['user_prenom'] ?? '';

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
</head>

<body>
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg align-items-center border-bottom-0 welcome-page">
        <?php if (isset($page_title) && $page_title !== 'welcome' && $page_title !== 'WelcomeEn') : ?>
            <img src="<?= URL ?>public/assets/images/logo1.PNG" width="140px" height="140px" alt="Logo" style="margin-right: 20px;" class="logolaniak move-down">
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($lang === 'fr'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=welcome">Bienvenue</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=accueil">Laniak</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=Apropos">À propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=programmes">Programmes</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=Evenements">Actualités</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=Equipements">Équipements</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=galeries">Galeries</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=contact">Contact</a></li>
                <?php elseif ($lang === 'en'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=welcomeEn">Welcome</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=accueilEn">Laniak</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=AproposEn">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=programmesEn">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=EvenementsEn">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=EquipementsEn">Equipment</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=galeriesEn">Galleries</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL ?>index.php?page=contactEn">Contact</a></li>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center">
                <form method="post" action="">
                    <input type="hidden" name="currentPage" value="<?= htmlspecialchars($_GET['page'] ?? 'welcome'); ?>">
                    <?php if ($lang === 'fr') : ?>
                        <button type="submit" name="lang" value="en" class="btn btn-outline-primary" style="margin-right: 10px;">EN</button>
                    <?php elseif ($lang === 'en') : ?>
                        <button type="submit" name="lang" value="fr" class="btn btn-outline-warning" style="margin-right: 10px;">FR</button>
                    <?php endif; ?>
                </form>

                <?php
                // User login state
                if ($userLoggedIn) {
                    // Lien profil utilisateur (ajuste avec la route de profil si besoin)
                    echo '<a href="' . URL . 'index.php?page=utilisateur" class="me-2 text-success"><i class="bi bi-person"></i> ' . htmlspecialchars($userPrenom) . '</a>';
                    // Lien déconnexion (à ajuster selon ta logique)
                    echo '<a href="' . URL . 'controllers/logout.php" class="text-link"><i class="bi bi-box-arrow-right"></i> ' . ($lang === 'fr' ? 'Déconnexion' : 'Logout') . '</a>';
                } else {
                    // Utilisateur non connecté
                    if ($lang === 'fr') {
                        echo '<a href="' . URL . 'index.php?page=inscription" class="me-2 text-danger"><i class="bi bi-person-plus"></i>Inscription</a>';
                        echo '<a href="' . URL . 'index.php?page=connexion" class="text-link"><i class="bi bi-person"></i>Connexion</a>';
                    } elseif ($lang === 'en') {
                        echo '<a href="' . URL . 'index.php?page=inscriptionEn" class="me-2 text-danger"><i class="bi bi-person-plus"></i>Sign Up</a>';
                        echo '<a href="' . URL . 'index.php?page=connexionEn" class="text-link"><i class="bi bi-person"></i>Login</a>';
                    }
                }
                ?>
            </div>
        </div>
    </nav>
</div>
    <script>
        // Script JavaScript pour enregistrer la largeur de l'écran dans un cookie
        document.addEventListener('DOMContentLoaded', function() {
            document.cookie = "screen_width=" + screen.width;
        });
    </script>

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

        /* Media queries si nécessaire */
        @media (min-width: 992px) and (max-width: 1200px) {

            /* Ajuster la taille du texte des nav items pour éviter les sauts de ligne */
            .navbar-nav .nav-link {
                font-size: 0.65em;
                /* Ajustez la taille du texte selon vos besoins */
            }
        }

        @media (min-width: 1200px) and (max-width: 1400px) {

            /* Ajuster la taille du texte des nav items pour éviter les sauts de ligne */
            .navbar-nav .nav-link {
                font-size: 0.9em;
                /* Ajustez la taille du texte selon vos besoins */
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