<?php
session_start();
require_once('includes/DB.php');

if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    header('Location: lista_opere.php');
}

$db = new DB();

$auth = $db->getAutoreById($_SESSION['user_id']);

if (($_SESSION['user_id'] != $_GET['id']) && $auth['isAdmin'] == false) {
    header('Location: lista_opere.php');
}

else {
    $delete = $db->deleteProfilo($_SESSION['user_id']);

    if ($delete) {
        $db->logout();
        header('Location: home.php');
      }
}

?>