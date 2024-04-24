<?php
require_once("models/MainManager.model.php");

class MainController
{
    private $MainManager;

    public function __construct()
    {
        $this->MainManager = new MainManager();
    }
    private function genererPage($data)
    {
        extract($data);
        ob_start();
        require_once($view);
        $page_content = ob_get_clean();
        require_once($template);
    }


    public function welcome()
    {
        $data_page = [
            "page_title" => "Welcome",
            "page_description" => "Description de la page welcome",
            "view" => "./views/welcome.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function accueil()
    {
        $data_page = [
            "page_title" => "Accueil",
            "page_description" => "Description de la page d'accueil",
            "view" => "./views/accueil.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function Apropos()
    {
        $data_page = [
            "page_title" => "A propos",
            "page_description" => "Description de la page A propos",
            "view" => "./views/Apropos.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function programmes()
    {
        $data_page = [
            "page_title" => "Programmes et Stages",
            "page_description" => "Description de la page Programmes et Stages",
            "view" => "./views/programmes.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function Evenements()
    {
        $data_page = [
            "page_title" => "Evenements",
            "page_description" => "Description de la page Evenements",
            "view" => "./views/Evenements.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function galeries()
    {
        $data_page = [
            "page_title" => "Galeries",
            "page_description" => "Description de la page Galerie",
            "view" => "./views/galeries.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function contact()
    {
        $data_page = [
            "page_title" => "Contact",
            "page_description" => "Description de la page Contact",
            "view" => "./views/contact.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function inscription()
    {
        $data_page = [
            "page_title" => "Inscription",
            "page_description" => "Description de la page Inscription",
            "view" => "./views/inscription.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function connexion()
    {
        $data_page = [
            "page_title" => "Connexion",
            "page_description" => "Description de la page Connexion",
            "view" => "./views/connexion.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function confirmation()
    {
        $data_page = [
            "page_title" => "Confirmation",
            "page_description" => "Description de la page Connexion",
            "view" => "./views/confirmation.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }


    public function utilisateur()
    {
        $data_page = [
            "page_title" => "Utilisateur",
            "page_description" => "Description de la page utilisateur",
            "view" => "./views/utilisateur.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function password_recovery()
    {
        $data_page = [
            "page_title" => "password_recovery",
            "page_description" => "Description de la page password_recovery",
            "view" => "./views/password_recovery.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function password_modification()
    {
        $data_page = [
            "page_title" => "password_modification",
            "page_description" => "Description de la page password_modification",
            "view" => "./views/password_modification.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function pageErreur($msg)
    {
        $data_page = [
            "page_title" => "Erreur",
            "page_description" => "Description de la page d'erreur",
            "msg" => $msg,
            "view" => "./views/erreur.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }
}