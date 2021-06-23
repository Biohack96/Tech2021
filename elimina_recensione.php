<?php
session_start();
require_once('includes/DB.php');
require_once('includes/create_info_utente.php');
// Oggetto di accesso al database
$db = new DB();

if (!isset($_GET['id'])){
    header("Location: index.php");
}

// Contengono l'HTML dei tag <head> e <body> che verranno stampati
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

// Contiene lo snippet di codice per visualizzare l'utente loggato in alto a destra

  $info_utente = createInfoUtente($db);
  $page_body = str_replace('<info_utente />', $info_utente, $page_body);


  $content = file_get_contents('includes/content_elimina_recensione.html');
  $recensione = $db->getRecensione($_GET['id']);

  if ($recensione['id_autore'] != $_SESSION['user_id']){
    header("Location: index.php");
}


  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['elimina'])) {

    if( $db->deleteRecensione($_POST['id_rec'], $_SESSION['user_id'])){
   
     header('Location: profilo.php?id=' . $_POST['id_utente']);
    } else {
      header('Location: 404.php');
    }
}

}

  $content = str_replace("<autore />", $recensione['nome'] . " " . $recensione['cognome'] , $content);


  $content = str_replace("<id_utente />", $recensione['id_utente'] , $content);
  
  $content = str_replace("<id_rec />", $recensione['id'] , $content);

  $content = str_replace("<date_recensione />", $recensione['data_recensione'] , $content);

 if($recensione['voto'] < 2){
  $content = str_replace("<img_voto />", "img/Star_rating_1_of_5.png", $content);
}

 elseif($recensione['voto'] >= 2 && $recensione['voto'] < 3){
    $content = str_replace("<img_voto />", "img/Star_rating_2_of_5.png", $content);
 }

 elseif($recensione['voto'] >= 3 && $recensione['voto'] < 4){
  $content = str_replace("<img_voto />", "img/Star_rating_3_of_5.png", $content);
}

 elseif($recensione['voto'] >= 4 && $recensione['voto'] < 5){
  $content = str_replace("<img_voto />", "img/Star_rating_4_of_5.png", $content);
}

 elseif($recensione['voto'] == 5){
  $content = str_replace("<img_voto />", "img/Star_rating_5_of_5.png", $content);
}

 $content = str_replace("<descrizione />", $recensione['descrizione'], $content);


$page_body = str_replace('<content />', $content, $page_body);


echo $page_head . $page_body ;

?>
