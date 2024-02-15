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
            "page_title" => "Page 1",
            "page_description" => "Description de la page 1",
            "view" => "./views/page1.view.php",
            "template" => "views/common/template.php"

        ];
        $this->genererPage($data_page);
    }

    public function page2()
    {
        $data_page = [
            "page_title" => "Page 2",
            "page_description" => "Description de la page 2",
            "view" => "./views/page2.view.php",
            "template" => "views/common/template.php"

        ];
        $this->genererPage($data_page);
    }

    public function page3()
    {
        $data_page = [
            "page_title" => "Page 3",
            "page_description" => "Description de la page 3",
            "view" => "./views/page3.view.php",
            "template" => "views/common/template.php"

        ];
        $this->genererPage($data_page);
    }

    public function page4()
    {
        $data_page = [
            "page_title" => "Page 4",
            "page_description" => "Description de la page 4",
            "view" => "./views/page4.view.php",
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
?>
