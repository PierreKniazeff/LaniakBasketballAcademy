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
    private $created_at;
    private $confirmed;
    private $token;

    

    public function __construct($prenom, $nom, $email, $tel, $date_naissance, $genre, $taille, $poids, $club, $niveau_championnat, $poste, $objectifs, $password, $created_at = null, $confirmed = 0, $token = null) {
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

    public function getCreatedAt() {
        return $this->created_at;
    }
    public function getConfirmed() {
        return $this->confirmed;
    }
    public function setToken($token) {
        $this->token = $token;
    }
    
    public function getToken() {
        return $this->token;
    }
}

