<?php
session_start();
require_once('includes/DB.php');
require_once('includes/create_info_utente.php');
require_once('includes/create_recensioni.php');

// Oggetto di accesso al database
$db = new DB();

//Controllo sicurezza
if (!isset($_GET['id'])) {
  header("Location: index.php");
}

// Titolo della pagina
$title = 'Profilo';

// Contengono l'HTML dei tag <head> e <body> che verranno stampati
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

// Contiene lo snippet di codice per visualizzare l'utente loggato in alto a destra

  $info_utente = createInfoUtente($db);
  $page_body = str_replace('<info_utente />', $info_utente, $page_body);

// Dati del profilo
$profilo = $db->getProfilo($_GET['id']);

$title = 'Profilo di ' . $profilo['nome'] . ' ' . $profilo['cognome'];

if($profilo['id'] == NULL){
  header("Location: index.php");
}

$content = file_get_contents('includes/content_profilo.html');
$content = str_replace('<immagine_profilo />', $profilo['img_path'], $content);
$content = str_replace('<nome />', $profilo['nome'], $content);
$content = str_replace('<cognome />', $profilo['cognome'], $content);
$content = str_replace('<data_di_nascita />', $profilo['data_nascita'], $content); 
$content = str_replace('<professione />', $profilo['professione'], $content); 
$content = str_replace('<luogo />', $profilo['luogo'], $content); 
$content = str_replace('<bio />', $profilo['bio'], $content); 


$content = str_replace('<email />', $profilo['email'], $content);
$content = str_replace('<telefono />', $profilo['telefono'], $content);

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $_GET['id']){

    $b = file_get_contents('includes/button_lascia_recensione.html');
    $b = str_replace('<id />', $_GET['id'], $b);
    
    $content = str_replace('<button_recensione />', $b , $content);
  
}

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']){

    $m = file_get_contents('includes/modifica_profilo_button.html');
    $m = str_replace('<id />', $_GET['id'], $m);

    $content = str_replace('<modifica />', $m , $content);

}


// Crea l'intestazione delle recensioni e la media

$r = file_get_contents('includes/intestazione_recensioni.html');

if($db->getRecensioni($_GET['id'])){

 $media = $db->getMedia($_GET['id']);

 $r = str_replace('<media />', $media['media'] . " su 5", $r); 


 if($media['media'] >= 1 && $media['media'] < 1.5){
  $r = str_replace("<media_img />", "img/Star_rating_2.5_of_5.png" , $r);
}

 elseif($media['media'] >= 1.5 && $media['media'] < 2){
  $r = str_replace("<media_img />", "img/Star_rating_1.5_of_5.png" , $r);
 }

 elseif($media['media'] >= 2 && $media['media'] < 2.5){
    $r = str_replace("<media_img />", "img/Star_rating_2_of_5.png" , $r);
  }

  elseif($media['media'] >= 2.5 && $media['media'] < 3){
    $r = str_replace("<media_img />", "img/Star_rating_2.5_of_5.png" , $r);
  }

  elseif($media['media'] >= 3 && $media['media'] < 3.5){
    $r = str_replace("<media_img />", "img/Star_rating_3_of_5.png" , $r);
  }

  elseif($media['media'] >= 3.5 && $media['media'] < 4){
    $r = str_replace("<media_img />", "img/Star_rating_3.5_of_5.png" , $r);
  }

  elseif($media['media'] >= 4 && $media['media'] < 4.5){
    $r = str_replace("<media_img />", "img/Star_rating_4_of_5.png" , $r);
  }

  elseif($media['media'] >= 4.5 && $media['media'] < 5){
    $r = str_replace("<media_img />", "img/Star_rating_4.5_of_5.png" , $r);
  }

  else{
    $r = str_replace("<media_img />", "img/Star_rating_5_of_5.png" , $r);
  }

// Crea la lista delle recensioni

$lista_recensioni='';
$lista_recensioni = createRecensioni($db, $_GET['id']);

$r = str_replace('<recensioni />', $lista_recensioni , $r);
$content = str_replace('<div_recensioni />', $r , $content);

}

else {
  $content = str_replace('<div_recensioni />', 'Nessuna recensione', $content);
}

$page_head = str_replace('<titolo />', $title, $page_head);

$page_body = str_replace('<content />', $content, $page_body);


echo $page_head . $page_body ;
?>