<?php
session_start();
require_once('includes/DB.php');
require_once('includes/create_info_utente.php');
// Oggetto di accesso al database
$db = new DB();

if (!isset($_GET['id'])){
    header("Location: index.php");
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $_GET['id']){
    header("Location: index.php");
}

// Contengono l'HTML dei tag <head> e <body> che verranno stampati
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

// Contiene lo snippet di codice per visualizzare l'utente loggato in alto a destra

  $info_utente = createInfoUtente($db);
  $page_body = str_replace('<info_utente />', $info_utente, $page_body);

// Gestione POST e chiamata al db  

 
  $content = file_get_contents('includes/registrazione.html');
  $profilo = $db->getProfilo($_SESSION['user_id']);

  $content = str_replace('<titolo />', "Modifica profilo", $content);
  $content = str_replace('<conferma_button />', "Conferma modifica", $content);


  $content = str_replace('<nome />', $profilo['nome'], $content);
  $content = str_replace('<cognome />', $profilo['cognome'], $content);
  $content = str_replace('<email />', $profilo['email'], $content);
  $content = str_replace('<telefono />', $profilo['telefono'], $content);
  $content = str_replace('<data_nascita />', $profilo['datanascita'], $content);
  $content = str_replace('<cf />', $profilo['cf'], $content);
  $content = str_replace('<titolo_studio />', $profilo['professione'], $content);
  $content = str_replace('<bio />', $profilo['bio'], $content);

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['registrazione'])) {
      $img_path = '';
  
      if (isset($_FILES['img']) && is_uploaded_file($_FILES['img']['tmp_name'])) {
          $img_path = $_FILES['img']['tmp_name'];
      }
  
      else {
          $img_path = $profilo['img_path'];
      }


       $result = $db->updateProfilo( $_SESSION['user_id'],
                                  $_POST['email'],
                                  $_POST['password'],
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
          if ($result) {
          header('Location: profilo.php?id=' . $_SESSION['user_id']);
    }
  }






  $page_body = str_replace('<content />', $content, $page_body);


echo $page_head . $page_body ;

?>
