<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/User.class.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!defined('URL')) {
    define('URL', 'https://levelnext.fr/');
}

// Véritable protection accès utilisateur
if (empty($_SESSION['user_logged_in'])) {
    header('Location: ' . URL . 'connexionEn');
    exit;
}

// Désérialisation User
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
} else {
    echo "Error: Unable to retrieve user information from the session.";
    exit;
}

// Protection CSRF à l'affichage
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveButton'])) {
    // Vérification CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errorMessage = "Security error: please reload the page and try again.";
    } else {
        require_once(__DIR__ . '/../controllers/loginEn.php');

        // Regroupement des champs à mettre à jour
        $fields = [
            'prenom' => $_POST['prenom'] ?? '',
            'nom' => $_POST['nom'] ?? '',
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

        $result = $LoginController->updateUser($email, $fields);

        if ($result) {
            $successMessage = "Your information has been successfully updated.";

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
            $errorMessage = "An error occurred while updating your information. Please try again.";
        }
    }
}

// **NE PAS** inclure le menu ou le header ici : ils doivent venir du template global
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Member Area</title>
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
        <h2 class="mb-4">Your Personal Information</h2>
        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label for="prenom" class="form-label">First Name:</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user->getPrenom()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="nom" class="form-label">Last Name:</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user->getNom()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="tel" class="form-label">Phone:</label>
                <input type="text" id="tel" name="tel" value="<?= htmlspecialchars($user->getTel()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="date_naissance" class="form-label">Date of Birth:</label>
                <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($user->getDateNaissance()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="genre" class="form-label">Gender:</label>
                <select id="genre" name="genre" class="form-control">
                    <option value="masculin" <?= ($user->getGenre() == 'masculin') ? 'selected' : '' ?>>Male</option>
                    <option value="féminin" <?= ($user->getGenre() == 'féminin') ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="taille" class="form-label">Height:</label>
                <input type="number" id="taille" name="taille" value="<?= htmlspecialchars($user->getTaille()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="poids" class="form-label">Weight:</label>
                <input type="number" id="poids" name="poids" value="<?= htmlspecialchars($user->getPoids()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="club" class="form-label">Club:</label>
                <input type="text" id="club" name="club" value="<?= htmlspecialchars($user->getClub()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="niveau_championnat" class="form-label">Championship Level:</label>
                <input type="text" id="niveau_championnat" name="niveau_championnat" value="<?= htmlspecialchars($user->getNiveauChampionnat()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="poste" class="form-label">Position:</label>
                <input type="text" id="poste" name="poste" value="<?= htmlspecialchars($user->getPoste()) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="objectifs" class="form-label">Goals:</label>
                <textarea id="objectifs" name="objectifs" class="form-control"><?= htmlspecialchars($user->getObjectifs()) ?></textarea>
            </div>
            <button type="submit" name="saveButton" class="btn btn-primary">Modify</button>
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
// Pas d'inclusion du menu ou header ici !
// Footer : le mettre seulement dans le template global
?>
