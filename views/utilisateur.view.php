<?php
$page_title = 'utilisateur'; // Définition de la variable pour menu.php
require_once(__DIR__ . '/../views/common/menu.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
require_once(__DIR__ . '/../views/common/header.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
        // Désérialisation de l'objet User
        $user = unserialize($_SESSION['user']);
        // Ici, vous pouvez utiliser $user comme un objet de la classe User
        echo "Bienvenue, " . htmlspecialchars($user->getPrenom());
        // Affichez d'autres détails comme désiré

        // Par exemple, si vous avez besoin de lire le nom :
        echo "Nom : " . htmlspecialchars($user->getNom());
    } else {
        // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion.
        echo "<script>window.location.href = 'https://levelnext.fr/views/connexion.view.php';</script>";
        exit;
    }
}
require_once(__DIR__ . '/../models/User.class.php');


// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    echo "<script>window.location.href = 'https://levelnext.fr/controllers/login.php';</script>";
    exit;
}

// Récupérer les informations de l'utilisateur de la session
$user = unserialize($_SESSION['user']);
// $user = $_SESSION['user'];

// Traitement du formulaire lorsque celui-ci est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveButton'])) {
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

    // Vérifiez si l'utilisateur est connecté via son email
    if (!isset($_SESSION['user_email'])) {
        // Redirigez l'utilisateur vers la page de connexion s'il n'est pas connecté
        echo "<script>window.location.href = 'https://levelnext.fr/controllers/login.php';</script>";
        exit;
    }

    // Récupération de l'email de l'utilisateur dans la session
    $email = $_SESSION['user_email'];

    // Effectuez la mise à jour des informations personnelles de l'utilisateur
    // Utilisez votre méthode updateUserField ou une autre méthode appropriée de votre classe CRUD/UserController
    $LoginController = new LoginController(); // Remplacez par votre instanciation

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

    // Affichez les résultats de la mise à jour ou les messages de succès/erreur
    echo $result; // Vous pouvez personnaliser cette sortie en fonction de la réponse de la méthode updateUserField
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
        /* Styles existants */
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

        .form-content {
            flex: 1;
        }

        .field-icon {
            cursor: pointer;
        }

        /* Nouveaux styles pour centrer la div container */
        .container {
            max-width: 600px;
            /* Ajustez la largeur maximale selon vos besoins */
            background-color: #fff;
            /* Ajoutez une couleur de fond pour la div container */
            padding: 20px;
            /* Ajoutez un espacement intérieur pour le contenu */
            border-radius: 10px;
            /* Facultatif : ajoutez des bordures arrondies */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Facultatif : ajoutez une ombre */
            margin: 50px auto;
            /* Centre la div container horizontalement avec une marge supérieure */
        }

        .edit-btn {
            border: 1px solid #007bff;
            color: #007bff;
            background: transparent;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            margin-left: 10px;
            /* Pour un peu d'espacement autour du bouton */
            transition: all 0.3s ease;
        }

        .edit-btn:hover,
        .edit-btn:focus {
            background: #007bff;
            color: white;
            outline: none;
        }

        .form-control[readonly] {
            background-color: transparent;
            border-color: rgba(0, 0, 0, 0.1);
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mb-4">Vos informations personnelles</h2>

        <form method="post" action="">

            <div class="form-group">
                <label for="prenom" class="form-label">Prénom:</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user->getPrenom()) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="nom" class="form-label">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user->getNom()) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="tel" class="form-label">Téléphone:</label>
                <input type="text" id="tel" name="tel" value="<?= htmlspecialchars($user->getTel()) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="date_naissance" class="form-label">Date de Naissance:</label>
                <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($user->getDateNaissance()) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="genre" class="form-label">Genre:</label>
                <select id="genre" name="genre" class="form-control">
                    <option value="masculin" <?= ($user->getGenre() == 'masculin') ? 'selected' : '' ?>>Masculin</option>
                    <option value="féminin" <?= ($user->getGenre() == 'féminin') ? 'selected' : '' ?>>Féminin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="taille" class="form-label">Taille:</label>
                <input type="number" id="taille" name="taille" value="<?= htmlspecialchars($user->getTaille()) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="poids" class="form-label">Poids:</label>
                <input type="number" id="poids" name="poids" value="<?= htmlspecialchars($user->getPoids()) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="club" class="form-label">Club:</label>
                <input type="text" id="club" name="club" value="<?= htmlspecialchars($user->getClub()) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="niveau_championnat" class="form-label">Niveau de Championnat:</label>
                <input type="text" id="niveau_championnat" name="niveau_championnat" value="<?= htmlspecialchars($user->getNiveauChampionnat()) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="poste" class="form-label">Poste:</label>
                <input type="text" id="poste" name="poste" value="<?= htmlspecialchars($user->getPoste()) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label for="objectifs" class="form-label">Objectifs:</label>
                <textarea id="objectifs" name="objectifs" class="form-control"><?= htmlspecialchars($user->getObjectifs()) ?></textarea>
            </div>

            <button type="submit" name="saveButton" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                let inputField = this.previousElementSibling;
                inputField.readOnly = false;
                inputField.focus();
                let originalValue = inputField.value;
                let fieldName = this.getAttribute('data-field');

                this.innerHTML = '<i class="fas fa-save"></i>';

                this.onclick = function() {
                    let value = inputField.value;

                    fetch('../controllers/login.php', {
                            method: 'POST',
                            body: JSON.stringify({
                                field: fieldName,
                                value: value
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                alert('Mise à jour réussie!');
                                inputField.readOnly = true;
                                this.innerHTML = '<i class="fas fa-pen"></i>';
                                // Réinitialiser le bouton à l'icône d'édition
                            } else {
                                alert('Erreur lors de la mise à jour. Veuillez réessayer.');
                                inputField.value = originalValue;
                                // Restaurer la valeur précédente en cas d'erreur
                            }
                        })
                        .catch(error => {
                            alert('Une erreur s\'est produite : ' + error);
                            inputField.value = originalValue;
                            // Gérer les erreurs du serveur
                        });
                };
            });
        });
    </script>
</body>

</html>

</html>

<?php
require_once(__DIR__ . '/../views/common/footer.php');
?>