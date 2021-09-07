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
$page_head = str_replace('<scripts />', "", $page_head);

/////gestione login/logout
$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');

if(isset($_SESSION['user_id']))
{
    $page_body = str_replace("<utente />", $profile_button, $page_body);			
}
else
{
    $page_body = str_replace("<utente />",$login_button, $page_body);			
}
////
$page_body = str_replace('<errors />', "", $page_body);


if (!isset($_SESSION['user_id'])){
    $content = str_replace("<button_elimina />", "", $content);
}

$opera = $db->getOperaById($_GET['id']);


if ($_GET['from'] == "autore") {
    $link = file_get_contents('includes/link.html');
    $link = str_replace("<path />", "autori.php", $link);
    $link = str_replace("<nome_link />", "Autori", $link);
    $link2 = file_get_contents('includes/link.html');
    $link2 = str_replace("<path />", "autori.php?id=".$opera['id_autore'], $link2);
    $link2 = str_replace("<nome_link />", $opera['username'], $link2);
    $page_body = str_replace("<breadcrumb />", $link . " > " . $link2 . " > " . $opera['titolo'], $page_body);
}

else if ($_GET['from'] == "all") {
    $link = file_get_contents('includes/link.html');
    $link = str_replace("<path />", "lista_opere.php", $link);
    $link = str_replace("<nome_link />", "Tutte le opere", $link);
    $page_body = str_replace("<breadcrumb />", $link . " > " . $opera['titolo'], $page_body);
}

else if ($_GET['from'] == "cat") {
    $link = file_get_contents('includes/link.html');
    $link = str_replace("<path />", "categorie.php", $link);
    $link = str_replace("<nome_link />", "Categorie", $link);
    $link2 = file_get_contents('includes/link.html');
    $link2 = str_replace("<path />", "categorie.php?id=".$opera['id_categoria'], $link2);
    $link2 = str_replace("<nome_link />", $opera['nome_categoria'], $link2);
    $page_body = str_replace("<breadcrumb />", $link . " > " . $link2 . " > " . $opera['titolo'], $page_body);
}

$content = str_replace("<section_name />", "Tutte le opere", $content);

$counter = 5; // TODO: esempio, da cambiare

// TODO: aggiungere gestione del button elimina

$content = str_replace("<Path/>", $opera['img_path'], $content);
$content = str_replace("<img_description/>", $opera['descrizione_short'], $content);
$content = str_replace("<descrizione />", $opera['descrizione'], $content);
$content = str_replace("<titolo />", $opera['titolo'], $content);
$content = str_replace("<autore />", $opera['username'], $content);
$content = str_replace("<link_autore />", "autori.php?id=" . $opera['id_autore'], $content);
$content = str_replace("<data_creazione />", $opera['data_creazione'], $content);
$content = str_replace("<categoria />", $opera['nome_categoria'], $content);
$content = str_replace("<link_cat />", "categorie.php?id=" . $opera['id_categoria'], $content);

$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>