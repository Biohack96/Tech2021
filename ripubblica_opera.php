<?php
session_start();
require_once('includes/DB.php');

if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    header('Location: 404.php');
}

$db = new DB();


$auth = $db->getAutoreById($_SESSION['user_id']);

if ($auth['isAdmin'] == false) {
    header('Location: 404.php');
}

    if ($db->republishOpera($_GET['id'])){
    header('Location: opere_segnalate.php');
    }
    else {
    header('Location: 404.php');
    }

?>