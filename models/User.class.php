<?php
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
    private $reset_mdp_token; // Nouvelle variable pour le jeton de réinitialisation du mot de passe

    public function __construct($prenom, $nom, $email, $tel, $date_naissance, $genre, $taille, $poids, $club, $niveau_championnat, $poste, $objectifs, 
    $password, $created_at = null, $confirmed = 0, $token = null, $reset_mdp_token = null) // Ajoutez $reset_mdp_token à votre constructeur
    {
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
        $this->reset_mdp_token = $reset_mdp_token; 
    }

    // Fonctions Getter pour accéder aux valeurs des champs
    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTel()
    {
        return $this->tel;
    }
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }
    public function getGenre()
    {
        return $this->genre;
    }

    public function getTaille()
    {
        return $this->taille;
    }

    public function getPoids()
    {
        return $this->poids;
    }

    public function getClub()
    {
        return $this->club;
    }

    public function getNiveauChampionnat()
    {
        return $this->niveau_championnat;
    }

    public function getPoste()
    {
        return $this->poste;
    }

    public function getObjectifs()
    {
        return $this->objectifs;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function getConfirmed()
    {
        return $this->confirmed;
    }
    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getTokenExpiration()
    {
        return $this->token_expiration;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getResetMdpToken()
    {
        return $this->reset_mdp_token;
    }
    // Méthodes Setter pour mettre à jour les propriétés de l'utilisateur

    public function setPrenom($prenom)
    {
        // Ajouter une validation ici si nécessaire
        $this->prenom = $prenom;
    }

    public function setNom($nom)
    {
        // Gestion des erreurs
        if (!empty($nom)) {
            $this->nom = $nom;
        } else {
            throw new Exception("Le nom ne peut pas être vide");
        }
    }

    public function setEmail($email)
    {
        // Ajouter une validation ici si nécessaire
        $this->email = $email;
    }

    public function setTel($tel)
    {
        // Ajouter une validation ici si nécessaire
        $this->tel = $tel;
    }

    public function setDateNaissance($date_naissance)
    {
        // Ajouter une validation ici si nécessaire
        $this->date_naissance = $date_naissance;
    }

    public function setGenre($genre)
    {
        // Ajouter une validation ici si nécessaire
        $this->genre = $genre;
    }

    public function setTaille($taille)
    {
        // Ajouter une validation ici si nécessaire
        $this->taille = $taille;
    }

    public function setPoids($poids)
    {
        // Ajouter une validation ici si nécessaire
        $this->poids = $poids;
    }

    public function setClub($club)
    {
        // Ajouter une validation ici si nécessaire
        $this->club = $club;
    }

    public function setNiveauChampionnat($niveau_championnat)
    {
        // Ajouter une validation ici si nécessaire
        $this->niveau_championnat = $niveau_championnat;
    }

    public function setPoste($poste)
    {
        // Ajouter une validation ici si nécessaire
        $this->poste = $poste;
    }

    public function setObjectifs($objectifs)
    {
        // Ajouter une validation ici si nécessaire
        $this->objectifs = $objectifs;
    }

    public function setPassword($password)
    {
        // Ajouter une validation ici si nécessaire
        $this->password = $password;
    }

    public function setCreatedAt($created_at)
    {
        // Ajouter une validation ici si nécessaire
        $this->created_at = $created_at;
    }

    public function setConfirmed($confirmed)
    {
        // Ajouter une validation ici si nécessaire
        $this->confirmed = $confirmed;
    }
    public function setResetMdpToken($reset_mdp_token)
    {
        $this->reset_mdp_token = $reset_mdp_token;
    }
    public function setTokenExpiration($token_expiration)
    {
        // Vous pouvez ajouter une validation ici si nécessaire
        $this->token_expiration = $token_expiration;
    }

    // Ajoutez d'autres méthodes setter au besoin...


}
