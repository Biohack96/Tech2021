<?php
 require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
// $db = new DB();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $error = array();
    $db = new DB();
    
    $result = $db->setProfilo(htmlentities($_POST['username']), $_POST['password'], $_POST['conferma_password'],htmlentities($_POST['bio']));

    if (is_numeric($result)) {
        header('Location: Home.php');
      } else {
        var_dump($result);
      }
}
?>

