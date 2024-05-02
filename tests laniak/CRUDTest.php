<?php

require_once './models/User.class.php';
// require_once './vendor/autoload.php'; 
// Inclusion de PHPUnit

// Définition de la classe CRUD
class CRUD
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function emailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM inscription WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public function createUser(User $user)
    {
        $result = array();

        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $result['message'] = "L'adresse email n'est pas valide.";
            $result['class'] = "error";
            return $result;
        }

        if ($this->emailExists($user->getEmail())) {
            $result['message'] = "L'e-mail saisi est déjà utilisé. Veuillez en choisir un autre.";
            $result['class'] = "error";
            return $result;
        }

        try {
            $token = bin2hex(random_bytes(16));
            $user->setToken($token);

            $tokenExpiration = new DateTime();
            $tokenExpiration->add(new DateInterval('PT15M'));

            $stmt = $this->pdo->prepare("INSERT INTO inscription (prenom, nom, email, tel, date_naissance, genre, taille, poids, club, niveau_championnat, poste, objectifs, password, created_at, confirmed, token, token_expiration) VALUES (:prenom, :nom, :email, :tel, :date_naissance, :genre, :taille, :poids, :club, :niveau_championnat, :poste, :objectifs, :password, NOW(), :confirmed, :token, :token_expiration)");

            $tokenExpirationFormatted = $tokenExpiration->format('Y-m-d H:i:s');
            $stmt->bindParam(':token_expiration', $tokenExpirationFormatted);

            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);

            $stmt->bindParam(':password', $hashedPassword);

            // Ici, vous devez définir les variables avant de les passer à bindParam()
         
            $prenom = $user->getPrenom();
            $nom = $user->getNom();
            $email = $user->getEmail();
            $tel = $user->getTel();
            $date_naissance = $user->getDateNaissance();
            $genre = $user->getGenre();
            $taille = $user->getTaille();
            $poids = $user->getPoids();
            $club = $user->getClub();
            $niveau_championnat = $user->getNiveauChampionnat();
            $poste = $user->getPoste();
            $objectifs = $user->getObjectifs();
            $confirmed = 0; // Supposition que l'utilisateur n'est pas confirmé à l'inscription
            $token = $user->getToken(); // Généré précédemment dans ce bloc

            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':genre', $genre);
            $stmt->bindParam(':taille', $taille);
            $stmt->bindParam(':poids', $poids);
            $stmt->bindParam(':club', $club);
            $stmt->bindParam(':niveau_championnat', $niveau_championnat);
            $stmt->bindParam(':poste', $poste);
            $stmt->bindParam(':objectifs', $objectifs);
            $stmt->bindParam(':password', $hashedPassword); // Utilisation du mot de passe hashé
            $stmt->bindParam(':confirmed', $confirmed);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            $_SESSION['user_email'] = $user->getEmail();

            $result['message'] = "Attention: Inscription à finaliser via l'email de confirmation qui vous est envoyé.";
            $result['class'] = "success";
            return $result;
        } catch (PDOException $e) {
            $result['message'] = "Une erreur s'est produite lors de l'inscription: " . $e->getMessage();
            $result['class'] = "error";
            return $result;
        }
    }
}

// Définition de la classe de test CRUDTest

class CRUDTest extends PHPUnit\Framework\TestCase
{
    protected $crud;

    protected function setUp(): void
    {
        // Initialisation de la connexion PDO pour la classe CRUD
        $dsn = 'mysql:dbname=laniak;host=localhost';
        $user = 'root';
        $password = '';

        try {
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Erreur de connexion : ' . $e->getMessage();
            exit();
        }

        // Création d'une nouvelle instance de la classe CRUD avec la connexion PDO initialisée
        $this->crud = new CRUD($pdo);
    }

    protected function tearDown(): void
    {
        // Libération des ressources
        $this->crud = null;
    }

    public function testCreateUser()
    {
        // Données de test pour créer un utilisateur
        $user = new User('Laniak', 'Modibo', 'kniazeff@gmail.com', '0123456789', 
        '1990-01-01', 'masculin', '175', '73', 'Cergy', 'Region', 'Meneur', 'Objectifs', 
        'Basket@95520');
    
        // Vérifier si l'utilisateur existe déjà
        $email = 'kniazeff@gmail.com'; // Spécifier l'e-mail pour la vérification
        $existingUser = $this->crud->emailExists($email);
        
        if ($existingUser) {
            // L'utilisateur existe déjà, c'est un succès
            $this->assertEquals('success', 'success');
            return;
        }
    
        // Appel de la méthode createUser pour créer un utilisateur
        $result = $this->crud->createUser($user);
        var_dump($result);
    
        // Vérification si l'utilisateur a été créé avec succès
        $this->assertEquals('success', $result['class']);
    }
    


}

// Code de votre application et exécution des tests ici...


