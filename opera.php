<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

if(!isset($_GET['id'])) {
    header('Location: lista_opere.php');
}

// Oggetto di accesso al database
$db = new DB();

// Titolo della pagina

$title = 'Opere - Share Arts';

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$content = file_get_contents('includes/content_opera.html');

$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);

$page_body = str_replace("<utente />", "", $page_body);			// da aggiungere

$opera = $db->getOperaById($_GET['id']);

$page_body = str_replace("<breadcrumb />", "Categorie > " . $opera['nome_categoria'], $page_body);
$content = str_replace("<section_name />", "Tutte le opere", $content);

$counter = 5; // TODO: esempio, da cambiare

// TODO: aggiungere gestione del button elimina

$content = str_replace("<Path/>", $opera['img_path'], $content);
$content = str_replace("<img_description/>", $opera['descrizione_short'], $content);
$content = str_replace("<descrizione />", $opera['descrizione'], $content);
$content = str_replace("<titolo />", $opera['titolo'], $content);
$content = str_replace("<autore />", $opera['username'], $content);
$content = str_replace("<data_creazione />", $opera['data_creazione'], $content);
$content = str_replace("<categoria />", $opera['nome_categoria'], $content);

$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>