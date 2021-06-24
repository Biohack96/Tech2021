<?php
session_start();
require_once('includes/DB.php');
// Oggetto di accesso al database
$db = new DB();

if (isset($_SESSION['user_id'])) {
  //$db->logout();
	header('Location: index.php');
}

// Inserimento nel database ed eventuale generazione di stringhe di errore
$error_login = '';
$error_registrazione = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['login'])) {

    if ($db->login($_POST['email'], $_POST['password'])) {
      header('Location: profilo.php?id=' . $_SESSION['user_id']);
    } else header('Location: 404.php');

  } 
}

// Titolo della pagina
$title = 'Accedi';

// Contengono l'HTML dei tag <head> e <body> che verranno stampati
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');



// Codice HTML del content
$content = file_get_contents('includes/login.html');

$content = str_replace('<reg />', file_get_contents('includes/registrazione.html'), $content);

$content = str_replace('<profiloEditTitolo />', "Registrati", $content);
$content = str_replace('<testoBottone />', "invia", $content);
$content = str_replace('<nomeSubmit />', "registrazione", $content);
$content = str_replace('<delButton />', "", $content);

$content = str_replace('<goto />', 'login.php', $content);

$content = str_replace('<errorLogin />', $error_login, $content);


if (isset($_POST['registrazione'])) {
  $content = str_replace('<nome />', $_POST['nome'], $content);
  $content = str_replace('<cognome />', $_POST['cognome'], $content);
  $content = str_replace('<datanascita />', $_POST['datanascita'], $content);
  $content = str_replace('<cf />', $_POST['cf'], $content);
  $content = str_replace('<email />', $_POST['email'], $content);
  $content = str_replace('<telefono />', $_POST['telefono'], $content);
  $content = str_replace('<bio />', $_POST['bio'], $content);
} else {
  $content = str_replace('<nome />', '', $content);
  $content = str_replace('<cognome />', '', $content);
  $content = str_replace('<datanascita />', '', $content);
  $content = str_replace('<cf />', '', $content);
  $content = str_replace('<email />', '', $content);
  $content = str_replace('<telefono />', '', $content);
  $content = str_replace('<bio />', '', $content);
}

// Rimpiazzo dei segnaposto sull'intera pagina
$page_head = str_replace('<title />', "<title>$title - WorkerAdvisor</title>", $page_head);

$page_body = str_replace('<content />', $content, $page_body);

echo $page_head . $page_body;
?>
