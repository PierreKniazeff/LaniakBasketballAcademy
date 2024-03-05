<?php
require_once('config/database.php');
require_once('models/User.class.php');

class CRUD
{
    private $pdo;

    public function __construct()
    {
        $config = require('config/database.php');
        $this->pdo = new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['database'], $config['user'], $config['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec("set names utf8");
    }

    public function createUser(User $user, $userId)
    {
        // Génération et stockage du code de vérification
        $verification_code = $this->generateVerificationCode();
        $this->storeVerificationCodeInDatabase($userId, $verification_code);

        if ($user->getPassword() !== $user->getConfirmPassword()) {
            return "Les mots de passe ne correspondent pas.";
        }

        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO inscription (prenom, nom, email, tel, date_naissance, genre, taille, poids, club, niveau_championnat, poste, objectifs, password, created_at, verification_code) VALUES (:prenom, :nom, :email, :tel, :date_naissance, :genre, :taille, :poids, :club, :niveau_championnat, :poste, :objectifs, :password, NOW(), :verification_code)");

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
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':verification_code', $verification_code);

        if ($stmt->execute()) {
            $this->sendVerificationEmail($email, $verification_code);
            return "Le formulaire a été soumis avec succès. Vous allez recevoir un mail avec un code de vérification à saisir pour finaliser votre inscription";
        } else {
            return "Une erreur s'est produite lors de l'inscription, veuillez réessayer.";
        }
    }

    function generateVerificationCode() {
        // Génération aléatoire d'un code de vérification à 6 chiffres
        $verificationCode = rand(100000, 999999);
        return $verificationCode;
    }

    public function storeVerificationCodeInDatabase($userId, $verification_code)
    {
        $stmt = $this->pdo->prepare("UPDATE inscription SET verification_code = :verification_code WHERE id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->execute();
    }

    public function sendVerificationEmail($email, $verification_code)
    {
        $to = $email;
        $subject = 'Code de vérification pour votre inscription';
        $message = 'Votre code de vérification est : ' . $verification_code;
        $headers = "From: laniakbasketballacademy@gmail.com";

        mail($to, $subject, $message, $headers);
    }
    public function verifyVerificationCode($userId, $verificationCode)
{
    $stmt = $this->pdo->prepare("SELECT verification_code FROM inscription WHERE id = :userId");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $storedCode = $stmt->fetchColumn();

    if ($storedCode == $verificationCode) {
        // Code de vérification correct, marquer l'utilisateur comme confirmé (par exemple)
        return "Code de vérification correct. Votre inscription est confirmée.";
    } else {
        // Code de vérification incorrect
        return "Code de vérification incorrect. Veuillez réessayer.";
    }
}

}
?>
