<?php
session_start();
require_once('includes/DB.php');

if (!isset($_GET['id'])) {
    header('Location: 404.php');
}

$db = new DB();

$opera = $db->getOperaById($_GET['id']);

if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $opera['id_autore'])) {
    header('Location: 404.php');
}

else {
    $db->segnalaOpera($_GET['id']);
    header('Location: opera.php?id=' . $_GET['id'] . '&from=all');
}

?>