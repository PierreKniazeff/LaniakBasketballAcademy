<?php
class User {
    // Champs existants
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
    private $confirm_password;
    private $created_at;
    private $verification_code;
    

    public function __construct($prenom, $nom, $email, $tel, $date_naissance, $genre, $taille, $poids, $club, $niveau_championnat, $poste, $objectifs, $password, $confirm_password, $created_at = null, $verification_code = null) {
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
        $this->confirm_password = $confirm_password;
        $this->created_at = $created_at;
        $this->verification_code = $verification_code;
    }

    // Fonctions Getter pour accéder aux valeurs des champs
    public function getPrenom() {
        return $this->prenom;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTel() {
        return $this->tel;
    }
    public function getDateNaissance() {
        return $this->date_naissance;
    }
    public function getGenre() {
        return $this->genre;
    }

    public function getTaille() {
        return $this->taille;
    }

    public function getPoids() {
        return $this->poids;
    }

    public function getClub() {
        return $this->club;
    }

    public function getNiveauChampionnat() {
        return $this->niveau_championnat;
    }

    public function getPoste() {
        return $this->poste;
    }

    public function getObjectifs() {
        return $this->objectifs;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getConfirmPassword() {
        return $this->confirm_password;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
    public function getVerificationCode() {
        return $this->verification_code;
    }
}

?>