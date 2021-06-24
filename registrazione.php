<?php
session_start();
require_once('includes/DB.php');
require_once('includes/error.php');

// Oggetto di accesso al database
$db = new DB();

if (isset($_SESSION['user_id'])) {
  $db->logout();
  header('Location: index.php');
}
$content = file_get_contents('includes/registrazione.html');

$content = str_replace('<titolo />', "Registrazione", $content);
$content = str_replace('<conferma_button />', "Conferma registrazione", $content);

$content = str_replace('<nome />', '', $content);
  $content = str_replace('<cognome />', '', $content);
  $content = str_replace('<email />', '', $content);
  $content = str_replace('<telefono />', '', $content);
  $content = str_replace('<data_nascita />', '', $content);
  $content = str_replace('<cf />', '', $content);
  $content = str_replace('<titolo_studio />', '', $content);
  $content = str_replace('<bio />', '', $content);
  $content = str_replace('hidden', '', $content);

// Inserimento nel database ed eventuale generazione di stringhe di errore
$error_login = '';
$error_registrazione = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['registrazione'])) {
    $img_path = '';

    if (isset($_FILES['img']) && is_uploaded_file($_FILES['img']['tmp_name'])) {
        $img_path = $_FILES['img']['tmp_name'];
    }

  
     $result = $db->setProfilo( $_POST['email'],
                                $_POST['password'],
								$_POST['conferma_password'],
                                $_POST['nome'],
                                $_POST['cognome'],
                                $_POST['telefono'],
                                $_POST['data_di_nascita'],
                                $_POST['cf'],
                                $_POST['titolo_di_studio'],
                                $_POST['bio'],
                                $img_path
                               );

    }  
        if (is_numeric($result)) 
        header('Location: profilo.php?id=' . $_SESSION['user_id']);
		else
		{
		$content .= printError($result);
		}
  }


  $page_head = file_get_contents('includes/head.html');
  $page_body = file_get_contents('includes/body.html');
 
  $title = 'Registrati';
  $page_head = str_replace('<title />', "<title>$title - WorkerAdvisor</title>", $page_head);
  $page_body = str_replace('<content />', $content, $page_body);
  echo $page_head . $page_body ;
  ?>

