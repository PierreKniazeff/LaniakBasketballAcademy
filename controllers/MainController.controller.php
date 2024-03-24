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

    public function page1()
    {
        $data_page = [
            "page_title" => "A propos",
            "page_description" => "Description de la page A propos",
            "view" => "./views/page1.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function page2()
    {
        $data_page = [
            "page_title" => "Programmes et Stages",
            "page_description" => "Description de la page Programmes et Stages",
            "view" => "./views/page2.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function page3()
    {
        $data_page = [
            "page_title" => "Evénements et actualités",
            "page_description" => "Description de la page Evénements et actualités",
            "view" => "./views/page3.view.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function page4()
    {
        $data_page = [
            "page_title" => "Galerie",
            "page_description" => "Description de la page Galerie",
            "view" => "./views/page4.view.php",
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

    public function profile()
    {
        $data_page = [
            "page_title" => "Profile",
            "page_description" => "Description de la page profil",
            "view" => "./controllers/profile.php",
            "template" => "views/common/template.php"
        ];
        $this->genererPage($data_page);
    }

    public function utilisateur()
    {
        $data_page = [
            "page_title" => "Utilisateur",
            "page_description" => "Description de la page utilisateur",
            "view" => "./views/utilisateur.views.php",
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
