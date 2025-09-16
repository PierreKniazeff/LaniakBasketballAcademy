<?php
session_start();
// Clear all session data
$_SESSION = array();
// Destroy the session
session_destroy();
// Include the MVC config for base URL
require_once __DIR__ . '/../config/config.php';
// Redirect to the English login page via central controller
header('Location: ' . URL . 'index.php?page=connexionEn');
exit;
