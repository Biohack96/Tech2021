<?php
session_start();
require_once('includes/DB.php');
require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();


if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
  header('Location: 404.php');
}

if($_GET['id'] == $_SESSION['user_id']){
  header('Location: 404.php');
}

$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$info_utente = createInfoUtente($db);
$page_body = str_replace('<info_utente />', $info_utente, $page_body);


$utente = $db->getProfilo($_GET['id']);
$content = file_get_contents('includes/content_lascia_recensione.html');

$title = 'Lascia una recensione a ' . $utente['nome'] . ' ' . $utente['cognome'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['recensione'])) {
 
    if( $db->setRecensione($_POST['comm'], $_POST['voto'], date("Y-m-d"), $_SESSION['user_id'], $_POST['id_us'])){
   
     header('Location: profilo.php?id=' . $_POST['id_us']);
    } else {
      header('Location: 404.php');
    }
}

}
$page_head = str_replace('<keyword/>', "recensione,voto,lavoro, recensione, privato, professione", $page_head);
$page_head = str_replace('<metatitle/>', "Recensisci l'utente". $utente['nome']." ". $utente['cognome']." - WorkerAdvisor trova il lavoratore che fa per te ", $page_head);
$content = str_replace('<nome />', $utente['nome'], $content);
$content = str_replace('<cognome />', $utente['cognome'], $content);
$content = str_replace('<id />', $utente['id'], $content);


$page_head = str_replace('<titolo />', $title, $page_head);
$page_head = str_replace('<scripts />', '', $page_head);


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head . $page_body;
?>