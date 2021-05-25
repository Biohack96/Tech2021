<?php
session_start();
require_once('includes/DB.php');

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
//$info_utente = createInfoUtente($db);

// Dati del profilo
$profilo = $db->getProfilo($_GET['id']);
$categorie = $db->getCategoria($_GET['id']);

$page_body = str_replace('<img_utente />', $profilo['img_path'], $page_body);
$page_body = str_replace('<nome />', $profilo['nome'], $page_body);
$page_body = str_replace('<cognome />', $profilo['cognome'], $page_body);


$content = file_get_contents('includes/content_profilo.html');
$content = str_replace('<immagine_profilo />', $profilo['img_path'], $content);
$content = str_replace('<nome />', $profilo['nome'], $content);
$content = str_replace('<cognome />', $profilo['cognome'], $content);
$content = str_replace('<data_di_nascita />', $profilo['datanascita'], $content); 

$content = str_replace('<email />', $profilo['email'], $content);
$content = str_replace('<telefono />', $profilo['telefono'], $content);

$lista_cat='';
foreach($categorie as $categoria) {
  $c = file_get_contents("includes/categoria.html");

 $c = str_replace("<categoria />", $categoria['nome'], $c);
 $lista_cat .= $c;
}

$page_body = str_replace('<content />', $content, $page_body);
$page_body = str_replace('<cat />', $lista_cat, $page_body);


echo $page_head . $page_body ;
?>