<?php
/**
 * Classe User : modèle utilisateur pour MVC
 */
class User
{
    private $id;
    private $prenom;
    private $nom;
    private $email;
    private $tel;
    private $date_naissance;
    private $genre;
    private $taille;
    private $poids;
    private $club;
    private $niveau_championnat;
    private $poste;
    private $objectifs;
    private $password;
    private $created_at;
    private $confirmed;
    private $token;
    private $token_expiration;
    private $reset_mdp_token; // Token pour reset password

    public function __construct(
        $prenom, $nom, $email, $tel, $date_naissance, $genre, $taille, $poids, $club,
        $niveau_championnat, $poste, $objectifs, $password,
        $created_at = null, $confirmed = 0, $token = null, $reset_mdp_token = null, $token_expiration = null, $id = null
    ) {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->tel = $tel;
        $this->date_naissance = $date_naissance;
        $this->genre = $genre;
        $this->taille = $taille;
        $this->poids = $poids;
        $this->club = $club;
        $this->niveau_championnat = $niveau_championnat;
        $this->poste = $poste;
        $this->objectifs = $objectifs;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->confirmed = $confirmed;
        $this->token = $token;
        $this->token_expiration = $token_expiration;
        $this->reset_mdp_token = $reset_mdp_token;
        $this->id = $id;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getPrenom() { return $this->prenom; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getTel() { return $this->tel; }
    public function getDateNaissance() { return $this->date_naissance; }
    public function getGenre() { return $this->genre; }
    public function getTaille() { return $this->taille; }
    public function getPoids() { return $this->poids; }
    public function getClub() { return $this->club; }
    public function getNiveauChampionnat() { return $this->niveau_championnat; }
    public function getPoste() { return $this->poste; }
    public function getObjectifs() { return $this->objectifs; }
    public function getPassword() { return $this->password; }
    public function getCreatedAt() { return $this->created_at; }
    public function getConfirmed() { return $this->confirmed; }
    public function getToken() { return $this->token; }
    public function getTokenExpiration() { return $this->token_expiration; }
    public function getResetMdpToken() { return $this->reset_mdp_token; }

    // Setters
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    public function setNom($nom) {
        if (!empty($nom)) { $this->nom = $nom;
        } else { throw new Exception("Le nom ne peut pas être vide"); }
    }
    public function setEmail($email) { $this->email = $email; }
    public function setTel($tel) { $this->tel = $tel; }
    public function setDateNaissance($date_naissance) { $this->date_naissance = $date_naissance; }
    public function setGenre($genre) { $this->genre = $genre; }
    public function setTaille($taille) { $this->taille = $taille; }
    public function setPoids($poids) { $this->poids = $poids; }
    public function setClub($club) { $this->club = $club; }
    public function setNiveauChampionnat($niveau_championnat) { $this->niveau_championnat = $niveau_championnat; }
    public function setPoste($poste) { $this->poste = $poste; }
    public function setObjectifs($objectifs) { $this->objectifs = $objectifs; }
    public function setPassword($password) { $this->password = $password; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function setConfirmed($confirmed) { $this->confirmed = $confirmed; }
    public function setToken($token) { $this->token = $token; }
    public function setTokenExpiration($token_expiration) { $this->token_expiration = $token_expiration; }
    public function setResetMdpToken($reset_mdp_token) { $this->reset_mdp_token = $reset_mdp_token; }
}

