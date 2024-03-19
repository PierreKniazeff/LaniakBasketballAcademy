<?php
session_start();
define("URL", str_replace("index.php", "", (isset($_SERVER['https']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']));
require_once("./controllers/MainController.controller.php");
$mc = new MainController();
try {
    if (empty($_GET['page'])) {
        $page = "welcome";
    } else {
        $url = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));
        $page = $url[0];
        //   var_dump($page);
    }
    switch ($page) {
        case 'welcome':
            $mc->welcome();
            break;
        case 'accueil':
            $mc->accueil();
            break;
        case 'page1':
            $mc->page1();
            break;
        case 'page2':
            $mc->page2();
            break;
        case 'page3':
            $mc->page3();
            break;
        case 'page4':
            $mc->page4();
            break;
        case 'contact':
            $mc->contact();
            break;
        case 'inscription':
            $mc->inscription();
            break;
        case 'connexion':
            $mc->connexion();
            break;
        case 'confirmation':
            $mc->confirmation();
            break;
        case 'utilisateur':
            $mc->utilisateur();
            break;

        default:
            throw new Exception("<h2>La page n'existe pas</h2>");
    }
} catch (Exception $e) {
    $mc->pageErreur($e->getMessage());
}

require_once("views/common/template.php");
