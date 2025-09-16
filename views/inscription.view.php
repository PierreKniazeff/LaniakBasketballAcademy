<?php
// Sécurité : empêcher l’accès direct à la vue
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header("Location: /");
    exit;
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/../models/User.class.php';
require_once __DIR__ . '/../controllers/crud.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="public/css/.css" rel="stylesheet"><!-- À corriger si besoin -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>inscription</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0; padding: 0;
            background-color: #F5F5F5;
        }
        .overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        .overlay-content {
            width: 50%; max-width: 400px; margin: 20% auto;
            padding: 20px; background: #fff;
        }
        .invalid { color: red; }
        .valid { color: green; }
        .error { color: #cc0000; font-weight:bold; margin-top:10px;}
        .success { color: #006600; font-weight:bold; margin-top:10px;}
    </style>
</head>
<body>
<?php
$db = new CRUD();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    if ($password !== $confirmPassword) {
        echo "<div class='error'>Les mots de passe ne correspondent pas!</div>";
    } else {
        $user = new User(
            $_POST['prenom'], $_POST['nom'], $_POST['email'], $_POST['tel'],
            $_POST['date_naissance'], $_POST['genre'], $_POST['taille'], $_POST['poids'],
            $_POST['club'], $_POST['niveau_championnat'], $_POST['poste'], $_POST['objectifs'],
            $password
        );
        $result = $db->createUser($user);
        echo "<div class='{$result['class']}'>{$result['message']}</div>";
    }
}
?>
<div class="container">
    <h2 class="mb-4">Formulaire d'Inscription</h2>
    <p style="font-style: italic; font-weight: bold; margin-bottom: 15px;">
        <strong>Les données fournies sont utilisées uniquement pour personnaliser les programmes d'entraînement et améliorer votre expérience,
        conformément à l'article 6 du RGPD (<a href="index.php?page=MentionsLegales">mention légale ici</a>).</strong>
    </p>
    <form action="" method="POST" accept-charset="UTF-8">
        <!-- Tous tes champs inchangés, logic de pré-remplissage -->
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
            <input type="text" id="prenom" name="prenom" class="form-control border-dark"
                required value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" id="nom" name="nom" class="form-control border-dark"
                required value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" class="form-control border-dark"
                required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <small id="emailHelp" class="form-text text-muted">Veuillez entrer une adresse e-mail valide.</small>
        </div>
        <div class="mb-3">
            <label for="tel" class="form-label">Tel <span class="text-danger">*</span></label>
            <input type="tel" id="tel" name="tel" class="form-control border-dark" required
                value="<?php echo isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
            <input type="date" id="date_naissance" name="date_naissance" class="form-control border-dark" required
                value="<?php echo isset($_POST['date_naissance']) ? htmlspecialchars($_POST['date_naissance']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
            <select id="genre" name="genre" class="form-control border-dark" required>
                <option value="masculin" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'masculin') ? 'selected' : ''; ?>>Masculin</option>
                <option value="féminin" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'féminin') ? 'selected' : ''; ?>>Féminin</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="taille" class="form-label">Taille (cm) <span class="text-danger">*</span></label>
            <input type="number" id="taille" name="taille" class="form-control border-dark" required
                value="<?php echo isset($_POST['taille']) ? htmlspecialchars($_POST['taille']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="poids" class="form-label">Poids (kg) <span class="text-danger">*</span></label>
            <input type="number" id="poids" name="poids" class="form-control border-dark" required
                value="<?php echo isset($_POST['poids']) ? htmlspecialchars($_POST['poids']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="club" class="form-label">Club</label>
            <input type="text" id="club" name="club" class="form-control border-dark"
                value="<?php echo isset($_POST['club']) ? htmlspecialchars($_POST['club']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="niveau_championnat" class="form-label">Niveau de championnat <span class="text-danger">*</span></label>
            <input type="text" id="niveau_championnat" name="niveau_championnat" class="form-control border-dark" required
                value="<?php echo isset($_POST['niveau_championnat']) ? htmlspecialchars($_POST['niveau_championnat']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="poste" class="form-label">Poste</label>
            <input type="text" id="poste" name="poste" class="form-control border-dark"
                value="<?php echo isset($_POST['poste']) ? htmlspecialchars($_POST['poste']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="objectifs" class="form-label">Objectifs</label>
            <textarea id="objectifs" name="objectifs" class="form-control border-dark" rows="4"><?php echo isset($_POST['objectifs']) ? htmlspecialchars($_POST['objectifs']) : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
            <input type="password" id="password" name="password" class="form-control border-dark" required>
            <span toggle="#password" class="fa fa-fw fa-eye field-icon"></span><br>
            <div id="passwordCriteria" style="margin-top: 10px;">
                Votre mot de passe doit contenir :
                <ul>
                    <li id="length" class="invalid">Au moins 8 caractères</li>
                    <li id="uppercase" class="invalid">Une majuscule</li>
                    <li id="number" class="invalid">Un chiffre</li>
                    <li id="special" class="invalid">Un caractère spécial (ex: !, @, #)</li>
                </ul>
            </div>
            <script>
                document.getElementById('password').addEventListener('input', function () {
                    const criteria = {
                        length: document.getElementById('length'),
                        uppercase: document.getElementById('uppercase'),
                        number: document.getElementById('number'),
                        special: document.getElementById('special')
                    };
                    const val = this.value;
                    criteria.length.className = val.length >= 8 ? 'valid' : 'invalid';
                    criteria.uppercase.className = /[A-Z]/.test(val) ? 'valid' : 'invalid';
                    criteria.number.className = /[0-9]/.test(val) ? 'valid' : 'invalid';
                    criteria.special.className = /[^\w]/.test(val) ? 'valid' : 'invalid';
                });
                $(document).ready(function() {
                    $('.field-icon').on('click', function(e) {
                        e.preventDefault();
                        var $this = $(this);
                        var $input = $($this.attr('toggle'));
                        if ($input.attr('type') === 'password') {
                            $input.attr('type', 'text');
                            $this.removeClass('fa-eye');
                            $this.addClass('fa-eye-slash');
                        } else {
                            $input.attr('type', 'password');
                            $this.removeClass('fa-eye-slash');
                            $this.addClass('fa-eye');
                        }
                    });
                });
            </script>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmation mot de passe <span class="text-danger">*</span></label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control border-dark" required>
            <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon"></span>
        </div>
        <button type="submit" class="btn btn-primary">Soumettre le formulaire</button>
    </form>
</div>
<footer>
    <div class="container-fluid">
        <?php include_once __DIR__ . '/common/footer.php'; ?>
    </div>
</footer>
</body>
</html>
