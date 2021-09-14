<?php
session_start();
require_once('includes/DB.php');

if (!isset($_GET['id'])) {
    header('Location: 404.php');
}

$db = new DB();

if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $_GET['id'])) {
    header('Location: 404.php');
}

else {
    if ($db->segnalaAutore($_GET['id'])) {
        header('Location: autori.php?id=' . $_GET['id']);
    }
    else {
        header('Location: 404.php');
    }
}

?>