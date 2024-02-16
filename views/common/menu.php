<link href="public/css/.css" rel="stylesheet">

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg align-items-center border-bottom-0">
        <?php
        // Vérifie si la page actuelle n'est pas la page welcome
        if ($page_title !== 'Welcome') {
            // Affiche le logo à gauche de la barre de navigation
            echo '<img src="' . URL . 'public/assets/images/logo.png" width="90px" height="90px" alt="Logo" style="margin-right: 20px;">';
        }
        ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="welcome">Welcome</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="accueil">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="page1">A propos</a>
                </li>
                <!-- Ajoutez ici les autres éléments de la barre de navigation -->
                <li class="nav-item">
                    <a class="nav-link" href="page2">Programmes et Stages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="page3">Evénements et actualités</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="page4">Galerie</a>
                </li>
            </ul>
            <div class="d-flex">
                <img src="<?php echo URL; ?>public/assets/images/USflag.png" alt="US Flag" width="30px" height="20px"
                    style="margin-right: 10px;">
                <a href="inscription" class="me-2 text-danger"><i class="bi bi-person-plus"></i>Inscription</a>
                <a href="connexion" class="text-link"><i class="bi bi-person"></i>Connexion</a>
            </div>
        </div>
    </nav>
</div>
</html>