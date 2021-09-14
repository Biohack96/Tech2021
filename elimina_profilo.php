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

$admin = true;
if (($_SESSION['user_id'] != $_GET['id']) && $admin == false) {
    header('Location: 404.php');
}

else {
    $delete = $db->deleteProfilo($_GET['id']);

    if ($delete) {
        if ($admin==false) {
            $db->logout();
            header('Location: home.php');
        }
        else {
            header('Location: admin.php');
        }
    }
}

?>