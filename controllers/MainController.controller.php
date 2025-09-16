<?php
// Inclusion de la config centrale MVC
require_once __DIR__ . '/../config/config.php';
// Inclusion du modèle principal
require_once __DIR__ . '/../models/MainManager.model.php';

class MainController {
    private $MainManager;

    public function __construct() {
        $this->MainManager = new MainManager();
    }

    private function genererPage($data) {
        extract($data);
        if (!file_exists($view)) {
            throw new Exception("La vue n'existe pas : " . $view);
        }
        ob_start();
        require_once($view);
        $page_content = ob_get_clean();
        require_once($template);
    }

    // Changement de langue
    public function redirectToLanguagePage($currentPage) {
        $pageMapping = [
            'welcome' => 'welcomeEn',
            'welcomeEn' => 'welcome',
            'accueil' => 'accueilEn',
            'accueilEn' => 'accueil',
            'Apropos' => 'AproposEn',
            'AproposEn' => 'Apropos',
            'programmes' => 'programmesEn',
            'programmesEn' => 'programmes',
            'Evenements' => 'EvenementsEn',
            'EvenementsEn' => 'Evenements',
            'Equipements' => 'EquipementsEn',
            'EquipementsEn' => 'Equipements',
            'galeries' => 'galeriesEn',
            'galeriesEn' => 'galeries',
            'contact' => 'contactEn',
            'contactEn' => 'contact',
            'inscription' => 'inscriptionEn',
            'inscriptionEn' => 'inscription',
            'connexion' => 'connexionEn',
            'connexionEn' => 'connexion',
            'password_recovery' => 'password_recoveryEn',
            'password_recoveryEn' => 'password_recovery',
            'password_modification' => 'password_modificationEn',
            'password_modificationEn' => 'password_modification',
        ];

        if (array_key_exists($currentPage, $pageMapping)) {
            $newPage = $pageMapping[$currentPage];
            header("Location: " . URL . "index.php?page=$newPage");
            exit;
        }
        header("Location: " . URL . "index.php?page=welcome");
        exit;
    }

    public function welcome() {
        $data_page = [
            "page_title" => "welcome",
            "page_description" => "welcome page description",
            "view" => __DIR__ . '/../views/welcome.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function welcomeEn() {
        $data_page = [
            "page_title" => "WelcomeEn",
            "page_description" => "WelcomeEn page description",
            "view" => __DIR__ . '/../views/welcomeEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function accueil() {
        $data_page = [
            "page_title" => "Accueil",
            "page_description" => "Accueil page description",
            "view" => __DIR__ . '/../views/accueil.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function accueilEn() {
        $data_page = [
            "page_title" => "Home",
            "page_description" => "Home page description",
            "view" => __DIR__ . '/../views/accueilEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function Apropos() {
        $data_page = [
            "page_title" => "À propos",
            "page_description" => "À propos page description",
            "view" => __DIR__ . '/../views/Apropos.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function AproposEn() {
        $data_page = [
            "page_title" => "About Us",
            "page_description" => "About Us page description",
            "view" => __DIR__ . '/../views/AproposEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function programmes() {
        $data_page = [
            "page_title" => "Programmes et Stages",
            "page_description" => "Programmes et Stages page description",
            "view" => __DIR__ . '/../views/programmes.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function programmesEn() {
        $data_page = [
            "page_title" => "Programs",
            "page_description" => "Programs page description",
            "view" => __DIR__ . '/../views/programmesEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function Evenements() {
        $data_page = [
            "page_title" => "Événements et actualités",
            "page_description" => "Événements et actualités page description",
            "view" => __DIR__ . '/../views/Evenements.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function EvenementsEn() {
        $data_page = [
            "page_title" => "Events & News",
            "page_description" => "Events & News page description",
            "view" => __DIR__ . '/../views/EvenementsEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function Equipements() {
        $data_page = [
            "page_title" => "Équipements associatifs",
            "page_description" => "Équipements associatifs page description",
            "view" => __DIR__ . '/../views/Equipements.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function EquipementsEn() {
        $data_page = [
            "page_title" => "Equipment",
            "page_description" => "Equipment page description",
            "view" => __DIR__ . '/../views/EquipementsEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function galeries() {
        $data_page = [
            "page_title" => "Galeries",
            "page_description" => "Galeries page description",
            "view" => __DIR__ . '/../views/galeries.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function galeriesEn() {
        $data_page = [
            "page_title" => "Galleries",
            "page_description" => "Galleries page description",
            "view" => __DIR__ . '/../views/galeriesEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function contact() {
        $data_page = [
            "page_title" => "Contact",
            "page_description" => "Contact page description",
            "view" => __DIR__ . '/../views/contact.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function contactEn() {
        $data_page = [
            "page_title" => "Contact",
            "page_description" => "Contact page description",
            "view" => __DIR__ . '/../views/contactEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function inscription() {
        $data_page = [
            "page_title" => "Inscription",
            "page_description" => "Inscription page description",
            "view" => __DIR__ . '/../views/inscription.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function inscriptionEn() {
        $data_page = [
            "page_title" => "Registration",
            "page_description" => "Registration page description",
            "view" => __DIR__ . '/../views/inscriptionEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function connexion() {
        $data_page = [
            "page_title" => "Connexion",
            "page_description" => "Connexion page description",
            "view" => __DIR__ . '/../views/connexion.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function connexionEn() {
        $data_page = [
            "page_title" => "Login",
            "page_description" => "Login page description",
            "view" => __DIR__ . '/../views/connexionEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function confirmation() {
        $data_page = [
            "page_title" => "Confirmation",
            "page_description" => "Confirmation page description",
            "view" => __DIR__ . '/../views/confirmation.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function utilisateur() {
        if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
            $_SESSION['redirect_after_login'] = 'utilisateur';
            header('Location: ' . URL . 'index.php?page=connexion');
            exit();
        }
        $data_page = [
            "page_title" => "Utilisateur",
            "page_description" => "Utilisateur page description",
            "view" => __DIR__ . '/../views/utilisateur.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function utilisateurEn() {
        if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
            $_SESSION['redirect_after_login'] = 'utilisateurEn';
            header('Location: ' . URL . 'index.php?page=connexionEn');
            exit();
        }
        $data_page = [
            "page_title" => "User",
            "page_description" => "User page description",
            "view" => __DIR__ . '/../views/utilisateurEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function password_recovery() {
        $data_page = [
            "page_title" => "Password Recovery",
            "page_description" => "Password recovery page description",
            "view" => __DIR__ . '/../views/password_recovery.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function password_recoveryEn() {
        $data_page = [
            "page_title" => "Password Recovery",
            "page_description" => "Password recovery page description",
            "view" => __DIR__ . '/../views/password_recoveryEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function password_modification() {
        $data_page = [
            "page_title" => "Modify Password",
            "page_description" => "Password modification page description",
            "view" => __DIR__ . '/../views/password_modification.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function password_modificationEn() {
        $data_page = [
            "page_title" => "Modify Password",
            "page_description" => "Password modification page description",
            "view" => __DIR__ . '/../views/password_modificationEn.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }

    public function pageErreur($msg) {
        $data_page = [
            "page_title" => "Erreur",
            "page_description" => "Une erreur s'est produite.",
            "msg" => $msg,
            "view" => __DIR__ . '/../views/erreur.view.php',
            "template" => __DIR__ . '/../views/common/template.php'
        ];
        $this->genererPage($data_page);
    }
}
