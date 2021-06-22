<?php
session_start();
require_once('includes/DB.php');

// Oggetto di accesso al database
$db = new DB();

// Effettua il logout e reindirizza a index.php
if (isset($_SESSION['user_id'])) {
    $db->logout();
      header('Location: index.php');
  }

else {header('Location: index.php');}

?>
