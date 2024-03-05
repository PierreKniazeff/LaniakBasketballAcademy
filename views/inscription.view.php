<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="public/css/.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

    <?php
    require_once("views/common/header.php");
    require_once('models/User.class.php');
    require_once('controllers/crud.php');

    $db = new CRUD();
    $verification_code = ""; // Variable pour stocker le code de vérification

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Vérifier si le formulaire est soumis pour la validation du code de vérification
            if(isset($_POST['verification_code'])){
                $userId = $_SESSION['userId'];
                $verificationCode = $_POST['verification_code'];
                
                $verifyMessage = $db->verifyVerificationCode($userId, $verificationCode);
                echo htmlspecialchars($verifyMessage);
            } else {
                // Si le formulaire est soumis pour l'inscription (non validation du code de vérification)
                $user = new User(
                    $_POST['prenom'],
                    $_POST['nom'],
                    $_POST['email'],
                    $_POST['tel'],
                    $_POST['date_naissance'],
                    $_POST['genre'],
                    $_POST['taille'],
                    $_POST['poids'],
                    $_POST['club'],
                    $_POST['niveau_championnat'],
                    $_POST['poste'],
                    $_POST['objectifs'],
                    $_POST['password'],
                    $_POST['confirm_password']
                );
        
                $message = $db->createUser($user, $userId);
                echo htmlspecialchars($message);
            }
        }
    ?>

    <div class="container">
        <h2 class="mb-4">Formulaire d'Inscription</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" id="prenom" name="prenom" class="form-control border-dark" required>
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" id="nom" name="nom" class="form-control border-dark" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control border-dark" required>
            </div>
            <div class="mb-3">
                <label for="tel" class="form-label">Tel</label>
                <input type="tel" id="tel" name="tel" class="form-control border-dark" required>
            </div>
            <!-- Ajoutez les autres champs requis et non obligatoires ici -->
            <div class="mb-3">
                <label for="date_naissance" class="form-label">Date de naissance</label>
                <input type="date" id="date_naissance" name="date_naissance" class="form-control border-dark" required>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <select id="genre" name="genre" class="form-control border-dark" required>
                    <option value="masculin">Masculin</option>
                    <option value="féminin">Féminin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="taille" class="form-label">Taille (cm)</label>
                <input type="number" id="taille" name="taille" class="form-control border-dark" required>
            </div>
            <div class="mb-3">
                <label for="poids" class="form-label">Poids (kg)</label>
                <input type="number" id="poids" name="poids" class="form-control border-dark" required>
            </div>
            <div class="mb-3">
                <label for="club" class="form-label">Club</label>
                <input type="text" id="club" name="club" class="form-control border-dark">
            </div>
            <div class="mb-3">
                <label for="niveau_championnat" class="form-label">Niveau de championnat</label>
                <input type="text" id="niveau_championnat" name="niveau_championnat" class="form-control border-dark" required>
            </div>
            <div class="mb-3">
                <label for="poste" class="form-label">Poste</label>
                <input type="text" id="poste" name="poste" class="form-control border-dark">
            </div>
            <div class="mb-3">
                <label for="objectifs" class="form-label">Objectifs</label>
                <textarea input type="text" id="objectifs" name="objectifs" class="form-control border-dark" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control border-dark" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmation mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control border-dark" required>
            </div>

            <button type="submit" class="btn btn-primary">Soumettre le formulaire</button>
        </form>
    </div>

    <!-- Après le formulaire -->

    <!-- Affichage du message de vérification -->
    <?php
if ($message === "Le formulaire a été soumis avec succès. Vous allez recevoir un mail avec un code de vérification à saisir pour finaliser votre inscription") {
    ?>
    <div id="overlay" class="overlay">
        <div class="overlay-content">
            <h2>Code de vérification</h2>
            <form action="" method="POST">
                <label for="verification_code">Entrez le code de vérification reçu par email :</label>
                <input type="text" id="verification_code" name="verification_code" required>
                <button type="submit" class="btn btn-primary">Valider le code</button>
            </form>
        </div>
    </div>
<?php
}
?>

<script>
// Afficher l'overlay lorsque le message est égal au message de succès
document.addEventListener('DOMContentLoaded', function() {
    var successMessage = "<?php echo addslashes($message); ?>";
    if (successMessage === "Le formulaire a été soumis avec succès. Vous allez recevoir un mail avec un code de vérification à saisir pour finaliser votre inscription") {
        document.getElementById('overlay').style.display = 'block';
    }
});
</script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5F5F5;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none; /* Pour cacher par défaut */
        }

        .overlay-content {
            width: 50%;
            max-width: 400px;
            margin: 20% auto;
            padding: 20px;
            background: #fff;
        }
    </style>
    
    <?php require_once("views/common/footer.php"); ?>

</body>
</html>