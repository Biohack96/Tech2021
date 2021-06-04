<?php
session_start();
require_once('includes/DB.php');

// Oggetto di accesso al database
$db = new DB();


if (!isset($_GET['id'])) {
  header('Location: 404.php');
}

$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');


$utente = $db->getProfilo($_GET['id']);
$autore = $db->getProfilo($_SESSION['user_id']);
$content = file_get_contents('includes/content_lascia_recensione.html');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['recensione'])) {
 
    if( $db->setRecensione($_POST['comm'], $_POST['voto'], $_POST['id_aut'], $_POST['id_us'])){
   
     header('Location: profilo.php?id=1');
    } else {
      header('Location: 404.php');
    }
}

}

$content = str_replace('<nome />', $utente['nome'], $content);
$content = str_replace('<cognome />', $utente['cognome'], $content);
$content = str_replace('<id />', $utente['id'], $content);
$content = str_replace('<id_aut />', $autore['id'], $content);



$page_body = str_replace('<content />', $content, $page_body);

echo $page_head . $page_body;
?>