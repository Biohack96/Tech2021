<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
    $db = new DB();
    $db->logout();
    header('Location: home.php');
?>