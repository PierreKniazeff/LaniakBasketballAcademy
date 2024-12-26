<?php
$page_title = 'utilisateur'; // Définition de la variable pour menu.php
require_once __DIR__ . '/../views/common/menu.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../views/common/header.php';
require_once __DIR__ . '/../models/User.class.php';

// Démarrer la session si elle n'est pas déjà active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérification de l'état de connexion de l'utilisateur
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    echo "<script>window.location.href = '" . URL . "views/connexion.view.php';</script>"; // Rediriger vers la page de connexion
    exit;
}

// Désérialisation de l'objet User
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
} else {
    echo "Erreur: Impossible de récupérer les informations de l'utilisateur de la session.";
    exit;
}

// Traitement du formulaire lorsque celui-ci est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveButton'])) {
    require_once(__DIR__ . '/../controllers/login.php');

    // Récupérez les données du formulaire
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $tel = $_POST['tel'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $taille = $_POST['taille'] ?? '';
    $poids = $_POST['poids'] ?? '';
    $club = $_POST['club'] ?? '';
    $niveau_championnat = $_POST['niveau_championnat'] ?? '';
    $poste = $_POST['poste'] ?? '';
    $objectifs = $_POST['objectifs'] ?? '';

    // Récupération de l'email de l'utilisateur dans la session
    $email = $_SESSION['email'];

    // Effectuez la mise à jour des informations personnelles de l'utilisateur
    $LoginController = new LoginController(); // Instanciation de votre contrôleur

    // Mettez à jour chaque champ individuellement
    $result = $LoginController->updateUserField($email, 'prenom', $prenom);
    $result .= $LoginController->updateUserField($email, 'nom', $nom);
    $result .= $LoginController->updateUserField($email, 'tel', $tel);
    $result .= $LoginController->updateUserField($email, 'date_naissance', $date_naissance);
    $result .= $LoginController->updateUserField($email, 'genre', $genre);
    $result .= $LoginController->updateUserField($email, 'taille', $taille);
    $result .= $LoginController->updateUserField($email, 'poids', $poids);
    $result .= $LoginController->updateUserField($email, 'club', $club);
    $result .= $LoginController->updateUserField($email, 'niveau_championnat', $niveau_championnat);
    $result .= $LoginController->updateUserField($email, 'poste', $poste);
    $result .= $LoginController->updateUserField($email, 'objectifs', $objectifs);

    // Gestion des messages de succès ou d'erreur
    if ($result) {
        $successMessage = "Les informations ont été mises à jour avec succès.";

        // Mise à jour dans la session
        $user->setPrenom($prenom);
        $user->setNom($nom);
        $user->setTel($tel);
        $user->setDateNaissance($date_naissance);
        $user->setGenre($genre);
        $user->setTaille($taille);
        $user->setPoids($poids);
        $user->setClub($club);
        $user->setNiveauChampionnat($niveau_championnat);
        $user->setPoste($poste);
        $user->setObjectifs($objectifs);

        // Enregistrer les nouvelles données de l'utilisateur dans la session
        $_SESSION['user'] = serialize($user);
    } else {
        $errorMessage = "Une erreur s'est produite lors de la mise à jour des informations. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
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
        .edit-btn {
            border: 1px solid #007bff;
            color: #007bff;
            background: transparent;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            margin-left: 10px;
            transition: all 0.3s ease;
        }
        .edit-btn:hover,
        .edit-btn:focus {
            background: #007bff;
            color: white;
            outline: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mb-4">Vos Informations Personnelles</h2>

        <form method="post" action="">
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
                <div class="alert alert-danger"><?= $errorMessage ?></div>
            <?php endif; ?>
            <?php if (isset($successMessage)) : ?>
                <div class="alert alert-success"><?= $successMessage ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>

<?php
require_once __DIR__ . '/../views/common/footer.php';
?>
