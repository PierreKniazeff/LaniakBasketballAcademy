<?php
session_start();
// Clear all session data
$_SESSION = array();
// Destroy the session
session_destroy();
// Redirect to the login page (MVC - always via index.php?page=connexion)
require_once __DIR__ . '/../config/config.php'; // Pour avoir la constante URL où qu'on soit
header('Location: ' . URL . 'index.php?page=connexion');
exit;
