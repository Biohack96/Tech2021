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

    } else header('Location: /login.php?error=1');


  } 
}

// Titolo della pagina
$title = 'Accedi';

// Contengono l'HTML dei tag <head> e <body> che verranno stampati
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$page_head = str_replace('<keyword/>', "lavoro, recensione, privato, professione, login, profilo, accedi", $page_head);
$page_head = str_replace('<metatitle/>', "Pagina di Login - WorkerAdvisor trova il lavoratore che fa per te", $page_head);

$scripts = file_get_contents('includes/script_login.html');

// Codice HTML del content
$content = file_get_contents('includes/login.html');


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
$page_head = str_replace('<titolo />', $title, $page_head);
$page_head = str_replace('<scripts />', $scripts, $page_head);

if(isset($_GET['error']))
	$content .= file_get_contents('includes/login_error.html');

$page_body = str_replace('<content />', $content, $page_body);

echo $page_head . $page_body;
?>
