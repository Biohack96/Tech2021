<?php
session_start();
require_once('includes/DB.php');

if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    header('Location: lista_opere.php');
}

$db = new DB();

$opera = $db->getOperaById($_GET['id']);
$auth = $db->getAutoreById($_SESSION['user_id']);

if (($_SESSION['user_id'] != $opera['id_autore']) && $auth['isAdmin'] == false) {
    header('Location: lista_opere.php');
}

else {
    $autore = $opera['id_autore'];
    $db->deleteOpera($_GET['id']);
    header('Location: autori.php?id=' . $autore);
}

?>