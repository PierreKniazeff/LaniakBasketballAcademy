<?php
// Rediriger vers le contenu de welcome.view.php tout en utilisant le menuEn
$page_content = "./views/welcome.view.php"; // Définissez le contenu de la page ici
include($page_content); // Inclure directement le contenu
?>
<nav class="navbar navbar-expand-lg align-items-center border-bottom-0 welcome-page">
    <?php if (isset($page_title)): ?>
        <img src="<?= BASE_URL ?>public/assets/images/logo1.PNG" width="140px" height="140px" alt="Logo" style="margin-right: 20px;" class="logolaniak move-down">
    <?php endif; ?>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item <?php if ($page_title === 'Welcome') echo 'active'; ?>">
                <a class="nav-link" href="https://levelnext.fr/welcome">Welcome</a>
            </li>
            <li class="nav-item <?php if ($page_title === 'Home') echo 'active'; ?>">
                <a class="nav-link" href="https://levelnext.fr/accueil">Laniak</a>
            </li>
            <li class="nav-item <?php if ($page_title === 'About') echo 'active'; ?>">
                <a class="nav-link" href="https://levelnext.fr/Apropos">About</a>
            </li>
            <li class="nav-item <?php if ($page_title === 'Programs and Internships') echo 'active'; ?>">
                <a class="nav-link" href="https://levelnext.fr/programmes">Programs</a>
            </li>
            <li class="nav-item <?php if ($page_title === 'Events') echo 'active'; ?>">
                <a class="nav-link" href="https://levelnext.fr/Evenements">News</a>
            </li>
            <li class="nav-item <?php if ($page_title === 'Equipment') echo 'active'; ?>">
                <a class="nav-link" href="https://levelnext.fr/Equipements">Equipment</a>
            </li>
            <li class="nav-item <?php if ($page_title === 'Gallery') echo 'active'; ?>">
                <a class="nav-link" href="https://levelnext.fr/galeries">Galleries</a>
            </li>
            <li class="nav-item <?php if ($page_title === 'Contact') echo 'active'; ?>">
                <a class="nav-link" href="https://levelnext.fr/contact">Contact</a>
            </li>
        </ul>
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary" onclick="window.location.href='index.php?page=welcome'" style="margin-right: 10px;">FR</button>
            <?php
            session_start();

            // Convert screen width to a number
            $screenWidth = isset($_COOKIE['screen_width']) ? intval($_COOKIE['screen_width']) : null;

            // Check if the user is logged in
            $userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'];

            // Check if screen width is 990px or less
            $screenWidthBelow990 = $screenWidth !== null && $screenWidth <= 990;

            if ($userLoggedIn) {
                // L'utilisateur est connecté
                echo '<a href="profile.php" class="me-2 text-success"><i class="bi bi-person"></i>' . htmlspecialchars($_SESSION['user_prenom']) . '</a>';
                echo '<a href="../controllers/logout.php" class="text-link"><i class="bi bi-box-arrow-right"></i>Logout</a>';
            } elseif (!($page_title === 'Welcome' && $screenWidthBelow990)) {
                // L'utilisateur n'est pas connecté et la condition pour ne pas afficher les liens est remplie
                echo '<a href="inscription" class="me-2 text-danger"><i class="bi bi-person-plus"></i>Sign Up</a>';
                echo '<a href="connexion" class="text-link"><i class="bi bi-person"></i>Login</a>';
            }
            ?>
        </div>
    </div>
</nav>

<style>
    /* Common styles for the navigation bar */
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

    /* Style for active navigation links */
    .navbar-nav .nav-item.active .nav-link {
        background-color: #000000;
        color: #ffffff;
    }

    /* Specific styles for the welcome page */
    .welcome-page .navbar-nav .nav-item.active .nav-link {
        background-color: transparent;
        color: white;
    }

    .logolaniak {
        margin-bottom: -25px; /* Style for logo */
    }

    /* Media queries if necessary */
    @media (max-width: 1200px) {
        .navbar-nav .nav-link {
            font-size: 16px; /* Adjust text size */
        }

        .navbar-toggler {
            font-size: 16px; /* Adjust size of nav button */
        }

        .navbar-toggler-icon {
            font-size: 20px; /* Adjust size of the nav button icon */
        }
    }

    /* Styles for pages other than welcome */
    .welcome-page .navbar-nav .nav-item.active .nav-link {
        background-color: #000000;
        color: #ffffff; /* Active link color */
    }

    /* Adjust text size of nav items to avoid line breaks */
    @media (min-width: 992px) and (max-width: 1200px) {
        .navbar-nav .nav-link {
            font-size: 0.65em; /* Adjust text size as needed */
        }
    }

    @media (min-width: 1200px) and (max-width: 1400px) {
        .navbar-nav .nav-link {
            font-size: 0.9em; /* Adjust text size as needed */
        }
    }
</style>
