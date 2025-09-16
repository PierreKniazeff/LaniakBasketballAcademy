<?php
// Affichage des erreurs : À désactiver en production !
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion du modèle User
require_once __DIR__ . '/../models/User.class.php';

// Démarrage session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Définition (ou non) de la constante URL
if (!defined('URL')) {
    define('URL', 'https://levelnext.fr/');
}

// Vérification de connexion utilisateur
if (empty($_SESSION['user_logged_in'])) {
    header('Location: ' . URL . 'connexion');
    exit;
}

// Désérialisation de l'utilisateur
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
} else {
    echo "Erreur : Impossible de récupérer les informations de l'utilisateur de la session.";
    exit;
}

// Protection CSRF (génération du token à l'affichage)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveButton'])) {
    // Vérification CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errorMessage = "Erreur de sécurité : veuillez recharger la page et réessayer.";
    } else {
        require_once(__DIR__ . '/../controllers/login.php');

        // Création d'un tableau de champs à mettre à jour
        $fields = [
            'prenom' => $_POST['prenom'] ?? '',
            'nom' => $_POST['nom'] ?? '',
            // 'email' => $_POST['email'] ?? '', // non modifiable ici
            'tel' => $_POST['tel'] ?? '',
            'date_naissance' => $_POST['date_naissance'] ?? '',
            'genre' => $_POST['genre'] ?? '',
            'taille' => $_POST['taille'] ?? '',
            'poids' => $_POST['poids'] ?? '',
            'club' => $_POST['club'] ?? '',
            'niveau_championnat' => $_POST['niveau_championnat'] ?? '',
            'poste' => $_POST['poste'] ?? '',
            'objectifs' => $_POST['objectifs'] ?? ''
        ];

        $email = $_SESSION['email'];
        $LoginController = new LoginController();

        // Mise à jour des champs en une requête
        $result = $LoginController->updateUser($email, $fields);

        if ($result) {
            $successMessage = "Les informations ont été mises à jour avec succès.";

            // Maj locale User + session
            $user->setPrenom($fields['prenom']);
            $user->setNom($fields['nom']);
            $user->setTel($fields['tel']);
            $user->setDateNaissance($fields['date_naissance']);
            $user->setGenre($fields['genre']);
            $user->setTaille($fields['taille']);
            $user->setPoids($fields['poids']);
            $user->setClub($fields['club']);
            $user->setNiveauChampionnat($fields['niveau_championnat']);
            $user->setPoste($fields['poste']);
            $user->setObjectifs($fields['objectifs']);

            $_SESSION['user'] = serialize($user);
        } else {
            $errorMessage = "Une erreur s'est produite lors de la mise à jour des informations. Veuillez réessayer.";
        }
    }
}

// inclusions après logique
// require_once __DIR__ . '/../views/common/menu.php';
// require_once __DIR__ . '/../views/common/header.php';
// ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Espace Membre</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5F5F5;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .form-label {
            flex: 0 0 120px;
            font-weight: bold;
            margin-right: 10px;
            color: #333;
        }
        .container {
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Vos Informations Personnelles</h2>

        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label for="prenom" class="form-label">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user->getPrenom()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user->getNom()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="tel" class="form-label">Téléphone :</label>
                <input type="text" id="tel" name="tel" value="<?= htmlspecialchars($user->getTel()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="date_naissance" class="form-label">Date de Naissance :</label>
                <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($user->getDateNaissance()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="genre" class="form-label">Genre :</label>
                <select id="genre" name="genre" class="form-control">
                    <option value="masculin" <?= ($user->getGenre() == 'masculin') ? 'selected' : '' ?>>Masculin</option>
                    <option value="féminin" <?= ($user->getGenre() == 'féminin') ? 'selected' : '' ?>>Féminin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="taille" class="form-label">Taille :</label>
                <input type="number" id="taille" name="taille" value="<?= htmlspecialchars($user->getTaille()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="poids" class="form-label">Poids :</label>
                <input type="number" id="poids" name="poids" value="<?= htmlspecialchars($user->getPoids()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="club" class="form-label">Club :</label>
                <input type="text" id="club" name="club" value="<?= htmlspecialchars($user->getClub()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="niveau_championnat" class="form-label">Niveau Championnat :</label>
                <input type="text" id="niveau_championnat" name="niveau_championnat" value="<?= htmlspecialchars($user->getNiveauChampionnat()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="poste" class="form-label">Poste :</label>
                <input type="text" id="poste" name="poste" value="<?= htmlspecialchars($user->getPoste()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="objectifs" class="form-label">Objectifs :</label>
                <textarea id="objectifs" name="objectifs" class="form-control"><?= htmlspecialchars($user->getObjectifs()) ?></textarea>
            </div>
            <button type="submit" name="saveButton" class="btn btn-primary">Modifier</button>
            <?php if (isset($errorMessage)) : ?>
                <div class="alert alert-danger mt-3"><?= $errorMessage ?></div>
            <?php endif; ?>
            <?php if (isset($successMessage)) : ?>
                <div class="alert alert-success mt-3"><?= $successMessage ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
<?php
require_once __DIR__ . '/../views/common/footer.php';
?>
