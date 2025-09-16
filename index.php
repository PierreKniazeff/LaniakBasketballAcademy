<?php
session_start();

// Affichage des erreurs (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définition de l'URL de base SANS index.php (propre à tout environnement)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/';
if (!defined('URL')) define('URL', $base_url);

// Inclusion du contrôleur principal
require_once(__DIR__ . "/controllers/MainController.controller.php");

$mc = new MainController();

try {
    // Gestion changement de langue
    if (isset($_POST['lang'])) {
        $_SESSION['lang'] = $_POST['lang'];
        $currentPage = $_POST['currentPage'] ?? 'welcome';

        // Mapping entre chaque page et sa version alternative
        $pageMapping = [
            'welcome' => 'welcomeEn',      'welcomeEn' => 'welcome',
            'accueil' => 'accueilEn',      'accueilEn' => 'accueil',
            'Apropos' => 'AproposEn',      'AproposEn' => 'Apropos',
            'programmes' => 'programmesEn','programmesEn' => 'programmes',
            'Evenements' => 'EvenementsEn','EvenementsEn' => 'Evenements',
            'Equipements' => 'EquipementsEn','EquipementsEn' => 'Equipements',
            'galeries' => 'galeriesEn',    'galeriesEn' => 'galeries',
            'contact' => 'contactEn',      'contactEn' => 'contact',
            'inscription' => 'inscriptionEn','inscriptionEn' => 'inscription',
            'connexion' => 'connexionEn',  'connexionEn' => 'connexion',
            'password_recovery' => 'password_recoveryEn',  'password_recoveryEn' => 'password_recovery',
            'password_modification' => 'password_modificationEn', 'password_modificationEn' => 'password_modification'
        ];

        // Redirection selon le mapping
        $newPage = $pageMapping[$currentPage] ?? 'welcome';
        header("Location: index.php?page=$newPage");
        exit;
    }

    // Sécurisation du paramètre GET "page" (filtrage + valeur par défaut)
    $page = isset($_GET['page']) && !empty($_GET['page']) ? filter_var($_GET['page'], FILTER_SANITIZE_STRING) : 'welcome';

    // Contrôleur de routing (chaque case = une page gérée)
    switch ($page) {
        case 'welcome':            $mc->welcome(); break;
        case 'welcomeEn':          $mc->welcomeEn(); break;
        case 'accueil':            $mc->accueil(); break;
        case 'accueilEn':          $mc->accueilEn(); break;
        case 'Apropos':            $mc->Apropos(); break;
        case 'AproposEn':          $mc->AproposEn(); break;
        case 'programmes':         $mc->programmes(); break;
        case 'programmesEn':       $mc->programmesEn(); break;
        case 'Evenements':         $mc->Evenements(); break;
        case 'EvenementsEn':       $mc->EvenementsEn(); break;
        case 'Equipements':        $mc->Equipements(); break;
        case 'EquipementsEn':      $mc->EquipementsEn(); break;
        case 'galeries':           $mc->galeries(); break;
        case 'galeriesEn':         $mc->galeriesEn(); break;
        case 'contact':            $mc->contact(); break;
        case 'contactEn':          $mc->contactEn(); break;
        case 'inscription':        $mc->inscription(); break;
        case 'inscriptionEn':      $mc->inscriptionEn(); break;
        case 'connexion':          $mc->connexion(); break;
        case 'connexionEn':        $mc->connexionEn(); break;
        case 'confirmation':       $mc->confirmation(); break;
        case 'password_recovery':  $mc->password_recovery(); break;
        case 'password_recoveryEn':$mc->password_recoveryEn(); break;
        case 'password_modification': $mc->password_modification(); break;
        case 'password_modificationEn': $mc->password_modificationEn(); break;
        case 'utilisateur':
            // Sécurité: accès uniquement pour utilisateurs connectés
            if (empty($_SESSION['user_logged_in'])) {
                $_SESSION['redirect_after_login'] = 'utilisateur';
                header('Location: ' . URL . 'connexion');
                exit;
            }
            $mc->utilisateur();
            break;
        case 'utilisateurEn':
            if (empty($_SESSION['user_logged_in'])) {
                $_SESSION['redirect_after_login'] = 'utilisateurEn';
                header('Location: ' . URL . 'connexionEn');
                exit;
            }
            $mc->utilisateurEn();
            break;
        default:
            throw new Exception("Page non trouvée");
    }
} catch (Exception $e) {
    $mc->pageErreur($e->getMessage());
}

// Inclusion du template unique = à conserver si structure adaptative
require_once("views/common/template.php");
