<?php
$page_title = 'utilisateur'; // Définition de la variable pour menu.php
require_once(__DIR__ . '/../views/common/menu.php');
require_once(__DIR__ . '/../controllers/crud.php');
?>

<?php
require_once(__DIR__ . '/../views/common/header.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../models/User.class.php');

if (!isset($_SESSION['user'])) {
    header('Location: ../controllers/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['field'])) {
    require_once(__DIR__ . '/../controllers/crud.php');

    // Récupérer l'instance de l'utilisateur courant
    $user = $_SESSION['user'];
    $userId = $user->getId();
    $field = $_POST['field'];
    $value = $_POST[$field];

    $crud = new CRUD();
    if ($crud->updateUserField($userId, $field, $value)) {
        echo json_encode(['success' => true, 'message' => 'Mise à jour réussie']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Échec de la mise à jour']);
    }
    exit;
}

$user = $_SESSION['user'];
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
</head>

<body>
    <div class="container">
        <h2 class="mb-4">Vos informations personnelles</h2>

        <div class="form-group">
            <label for="prenom" class="form-label">Prénom:</label>
            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user->getPrenom()) ?>" readonly>
            <button class="edit-btn" data-field="prenom"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="nom" class="form-label">Nom : </label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user->getNom()) ?>" readonly>
            <button class="edit-btn" data-field="nom"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email : </label>
            <input type="text" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" readonly>
            <button class="edit-btn" data-field="email"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="tel" class="form-label">Téléphone : </label>
            <input type="text" id="tel" name="tel" value="<?= htmlspecialchars($user->getTel()) ?>" readonly>
            <button class="edit-btn" data-field="tel"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="date_naissance" class="form-label">Date de naissance : </label>
            <input type="text" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($user->getDateNaissance()) ?>" readonly>
            <button class="edit-btn" data-field="date_naissance"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="genre" class="form-label">Genre : </label>
            <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($user->getGenre()) ?>" readonly>
            <button class="edit-btn" data-field="genre"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="taille" class="form-label">Taille : </label>
            <input type="text" id="taille" name="taille" value="<?= htmlspecialchars($user->getTaille()) ?>" readonly>
            <button class="edit-btn" data-field="taille"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="poids" class="form-label">Poids : </label>
            <input type="text" id="poids" name="poids" value="<?= htmlspecialchars($user->getPoids()) ?>" readonly>
            <button class="edit-btn" data-field="poids"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="club" class="form-label">Club : </label>
            <input type="text" id="club" name="club" value="<?= htmlspecialchars($user->getClub()) ?>" readonly>
            <button class="edit-btn" data-field="club"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="niveau_championnat" class="form-label">Niveau de championnat : </label>
            <input type="text" id="niveau_championnat" name="niveau_championnat" value="<?= htmlspecialchars($user->getNiveauChampionnat()) ?>" readonly>
            <button class="edit-btn" data-field="niveau_championnat"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="poste" class="form-label">Poste : </label>
            <input type="text" id="poste" name="poste" value="<?= htmlspecialchars($user->getPoste()) ?>" readonly>
            <button class="edit-btn" data-field="poste"><i class="fas fa-pen"></i></button>
        </div>

        <div class="form-group">
            <label for="objectifs" class="form-label">Objectifs : </label>
            <input type="text" id="objectifs" name="objectifs" value="<?= htmlspecialchars($user->getObjectifs()) ?>" readonly>
            <button class="edit-btn" data-field="objectifs"><i class="fas fa-pen"></i></button>
        </div>

    </div>

    <?php
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

        // Effectuez la mise à jour des informations utilisateur
        // Utilisez votre méthode updateUserField ou une autre méthode appropriée de votre classe CRUD/UserController
        $crud = new CRUD(); // Remplacez par votre instanciation

        // Remplacez $userId par l'ID de l'utilisateur (vous devez définir $userId)
        $userId = $_SESSION['user']->getId(); // Supposons que vous stockez l'ID de l'utilisateur dans la session

        // Mettez à jour chaque champ individuellement
        $result = $crud->updateUserField($userId, 'prenom', $prenom);
        $result .= $crud->updateUserField($userId, 'nom', $nom);
        $result .= $crud->updateUserField($userId, 'email', $email);
        $result .= $crud->updateUserField($userId, 'tel', $tel);
        $result .= $crud->updateUserField($userId, 'date_naissance', $date_naissance);
        $result .= $crud->updateUserField($userId, 'genre', $genre);
        $result .= $crud->updateUserField($userId, 'taille', $taille);
        $result .= $crud->updateUserField($userId, 'poids', $poids);
        $result .= $crud->updateUserField($userId, 'club', $club);
        $result .= $crud->updateUserField($userId, 'niveau_championnat', $niveau_championnat);
        $result .= $crud->updateUserField($userId, 'poste', $poste);
        $result .= $crud->updateUserField($userId, 'objectifs', $objectifs);

        // Affichez les résultats de la mise à jour ou les messages de succès/erreur
        echo $result; // Vous pouvez personnaliser cette sortie en fonction de la réponse de la méthode updateUserField
    }
    ?>
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
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                let inputField = this.previousElementSibling;
                inputField.readOnly = false;
                inputField.focus();
                let oldButtonText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-save"></i>';

                this.onclick = function() {
                    let data = new URLSearchParams();
                    data.append(inputField.name, inputField.value);
                    data.append('field', inputField.name);

                    fetch('../controllers/crud.php', {
                            method: 'POST',
                            body: data,
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            }
                        })
                        .then(response => response.text())
                        .then(result => {
                            if (result.includes('success')) {
                                alert('Mise à jour réussie');
                                inputField.readOnly = true;
                                this.innerHTML = oldButtonText;
                            } else {
                                alert('Échec de la mise à jour');
                            }
                        })
                        .catch(error => alert('Erreur : ' + error));
                };
            });
        });
    </script>

</body>

</html>

<?php
require_once(__DIR__ . '/../views/common/footer.php');
?>