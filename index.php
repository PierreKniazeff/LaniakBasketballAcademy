<?php
session_start();

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définir l'URL de base
define("URL", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']));

require_once("./controllers/MainController.controller.php"); // Inclure le contrôleur
$mc = new MainController();

try {
    // Gestion du changement de langue
    if (isset($_POST['lang'])) {
        $_SESSION['lang'] = $_POST['lang'];
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 'welcome';

        // Mappage des pages
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
            'password_modification' => 'password_moficationEn',
            'password_modificationEn' => 'password_modification',
           
        ];

        // Redirection vers la page correspondante selon la langue
        if (array_key_exists($currentPage, $pageMapping)) {
            $newPage = $pageMapping[$currentPage];
            header("Location: index.php?page=$newPage");
            exit;
        }
        header("Location: index.php?page=welcome");
        exit;
    }

    // Vérifier la page actuellement demandée
    $page = isset($_GET['page']) && !empty($_GET['page']) ? filter_var($_GET['page'], FILTER_SANITIZE_URL) : 'welcome';

    // Appeler la méthode du contrôleur correspondante
    switch ($page) {
        case 'welcome':
            $mc->welcome();
            break;
        case 'welcomeEn':
            $mc->welcomeEn();
            break;
        case 'accueil':
            $mc->accueil();
            break;
        case 'accueilEn':
            $mc->accueilEn();
            break;
        case 'Apropos':
            $mc->Apropos();
            break;
        case 'AproposEn':
            $mc->AproposEn();
            break;
        case 'programmes':
            $mc->programmes();
            break;
        case 'programmesEn':
            $mc->programmesEn();
            break;
        case 'Evenements':
            $mc->Evenements();
            break;
        case 'EvenementsEn':
            $mc->EvenementsEn();
            break;
        case 'Equipements':
            $mc->Equipements();
            break;
        case 'EquipementsEn':
            $mc->EquipementsEn();
            break;
        case 'galeries':
            $mc->galeries();
            break;
        case 'galeriesEn':
            $mc->galeriesEn();
            break;
        case 'contact':
            $mc->contact();
            break;
        case 'contactEn':
            $mc->contactEn();
            break;
        case 'inscription':
            $mc->inscription();
            break;
        case 'inscriptionEn':
            $mc->inscriptionEn();
            break;
        case 'connexion':
            $mc->connexion();
            break;
        case 'connexionEn':
            $mc->connexionEn();
            break;
        case 'confirmation':
            $mc->confirmation();
            break;
        case 'password_recovery':
            $mc->password_recovery();
            break;
        case 'password_recoveryEn':
            $mc->password_recoveryEn();
            break;
            case 'password_modification':
                $mc->password_modification();
                break;
            case 'password_modificationEn':
                $mc->password_modificationEn();
                break;
            case 'utilisateur':
                // Vérification de connexion
                if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
                    header('Location: ' . URL . 'connexion'); // Redirection vers la page de connexion
                    exit();
                }
                $mc->utilisateur();
                break;
            case 'utilisateurEn':
                // Vérification de connexion
                if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
                    header('Location: ' . URL . 'connexionEn'); // Redirection vers la page de connexion en anglais
                    exit();
                }
                $mc->utilisateurEn();
                break;
            default:
                throw new Exception("Page non trouvée");
        }
    } catch (Exception $e) {
        $mc->pageErreur($e->getMessage()); // Gestion des erreurs
    }
    
    // Inclure le template
    require_once("views/common/template.php");
    ?>
    