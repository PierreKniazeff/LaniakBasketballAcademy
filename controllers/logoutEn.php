<?php
session_start();
// Effacer toutes les données de session
$_SESSION = array();
// Détruire la session
session_destroy();
// Rediriger vers la page de connexion ou d'accueil
header('Location: https://levelnext.fr/connexionEn');
exit;

