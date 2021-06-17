<?php
session_start();
require_once('includes/DB.php');
require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();

//Controllo sicurezza
if (!isset($_GET['id'])) {
  header('Location: 404.php');
}

// Titolo della pagina
$title = 'Profilo';

// Contengono l'HTML dei tag <head> e <body> che verranno stampati
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

// Contiene lo snippet di codice per visualizzare l'utente loggato in alto a destra
if (isset($_SESSION['user_id'])){
  $info_utente = createInfoUtente($db);
  $page_body = str_replace('<info_utente />', $info_utente, $page_body);
}

else {
  $page_body = str_replace('<info_utente />', '<a class="button" href="login.php">Accedi</a>', $page_body);
}

// Dati del profilo
$profilo = $db->getProfilo($_GET['id']);
$recensioni = $db->getRecensioni($_GET['id']);

$content = file_get_contents('includes/content_profilo.html');
$content = str_replace('<immagine_profilo />', $profilo['img_path'], $content);
$content = str_replace('<nome />', $profilo['nome'], $content);
$content = str_replace('<cognome />', $profilo['cognome'], $content);
$content = str_replace('<data_di_nascita />', $profilo['datanascita'], $content); 
$content = str_replace('<titolo_studio />', $profilo['titolostudio'], $content); 
$content = str_replace('<bio />', $profilo['bio'], $content); 


$content = str_replace('<email />', $profilo['email'], $content);
$content = str_replace('<telefono />', $profilo['telefono'], $content);

if (isset($_SESSION['user_id'])){
  if($_SESSION['user_id'] != $_GET['id']){
    $content = str_replace('<button_recensione />', '<a class="button" href="recensione.php?id=' . $_GET['id'] .'">Lascia una recensione</a>' , $content);
  }
}

$lista_recensioni='';

if($recensioni != NULL){

  $content = str_replace('<div_recensioni />', '<div id="media">
    <p id="media_numero"> <img src="<media_img /> " height= 30px> <media /> </p>
    </div>

    <div id="lista_recensioni">
    <recensioni />
    </div>' , $content); 

 $media = $db->getMedia($_GET['id']);
 $content = str_replace('<media />', $media['media'] . " su 5", $content); 


 if($media['media'] >= 1 && $media['media'] < 1.5){
  $content = str_replace("<media_img />", "img/Star_rating_2.5_of_5.png" , $content);
}

 elseif($media['media'] >= 1.5 && $media['media'] < 2){
  $content = str_replace("<media_img />", "img/Star_rating_1.5_of_5.png" , $content);
 }

 elseif($media['media'] >= 2 && $media['media'] < 2.5){
    $content = str_replace("<media_img />", "img/Star_rating_2_of_5.png" , $content);
  }

  elseif($media['media'] >= 2.5 && $media['media'] < 3){
    $content = str_replace("<media_img />", "img/Star_rating_2.5_of_5.png" , $content);
  }

  elseif($media['media'] >= 3 && $media['media'] < 3.5){
    $content = str_replace("<media_img />", "img/Star_rating_3_of_5.png" , $content);
  }

  elseif($media['media'] >= 3.5 && $media['media'] < 4){
    $content = str_replace("<media_img />", "img/Star_rating_3.5_of_5.png" , $content);
  }

  elseif($media['media'] >= 4 && $media['media'] < 4.5){
    $content = str_replace("<media_img />", "img/Star_rating_4_of_5.png" , $content);
  }

  elseif($media['media'] >= 4.5 && $media['media'] < 5){
    $content = str_replace("<media_img />", "img/Star_rating_4.5_of_5.png" , $content);
  }

  else{
    $content = str_replace("<media_img />", "img/Star_rating_5_of_5.png" , $content);
  }


foreach($recensioni as $recensione) {
  $r = file_get_contents("includes/recensioni.html");

 $r = str_replace("<autore />", $recensione['nome'] . " " . $recensione['cognome'] , $r);
 $r = str_replace("<date_recensione />", $recensione['data_recensione'] , $r);

 if($recensione['voto'] < 2){
  $r = str_replace("<img_voto />", "img/Star_rating_1_of_5.png", $r);
}

 elseif($recensione['voto'] >= 2 && $recensione['voto'] < 3){
    $r = str_replace("<img_voto />", "img/Star_rating_2_of_5.png", $r);
 }

 elseif($recensione['voto'] >= 3 && $recensione['voto'] < 4){
  $r = str_replace("<img_voto />", "img/Star_rating_3_of_5.png", $r);
}

 elseif($recensione['voto'] >= 4 && $recensione['voto'] < 5){
  $r = str_replace("<img_voto />", "img/Star_rating_4_of_5.png", $r);
}

 elseif($recensione['voto'] == 5){
  $r = str_replace("<img_voto />", "img/Star_rating_5_of_5.png", $r);
}

 $r = str_replace("<descrizione />", $recensione['descrizione'], $r);

 $lista_recensioni .= $r;
}
}

else {
  $content = str_replace('<div_recensioni />', '<p> Nessuna recensione </p>', $content);
}

$page_body = str_replace('<content />', $content, $page_body);
$page_body = str_replace('<recensioni />', $lista_recensioni, $page_body);


echo $page_head . $page_body ;
?>