<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

if(!isset($_GET['id'])) {
    header('Location: 404.php');
}

// Oggetto di accesso al database
$db = new DB();

$auth = $db->getAutoreById($_SESSION['user_id']);

if ($auth['isAdmin'] == false) {
    header('Location: 404.php');
}

$admin = true;

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$content = file_get_contents('includes/content_opera_segnalata.html');

$page_head = str_replace('<scripts />', "", $page_head);

$opera = $db->getOperaById($_GET['id']);

$title = $opera['titolo'] . ' - Share Arts';
$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);

$counter = 1;

// Creazione breadcrumb
$link = file_get_contents('includes/link.html');
$link = str_replace("<path />", "admin.php", $link);
$link = str_replace("<nome_link />", "Pannello amministratore", $link);
$link = str_replace("<tab />", $counter++, $link);

$link2 = file_get_contents('includes/link.html');
$link2 = str_replace("<path />", "opere_segnalate.php", $link2);
$link2 = str_replace("<nome_link />", "Opere segnalate", $link2);
$link2 = str_replace("<tab />", $counter++, $link2);

$page_body = str_replace("<breadcrumb />", $link . " > " . $link2 . " > " . $opera['titolo'], $page_body);

$page_body = str_replace("<tab1 />", $counter++, $page_body);
$page_body = str_replace("<tab2 />", $counter++, $page_body);
$page_body = str_replace("<tab3 />", $counter++, $page_body);
$page_body = str_replace("<tab4 />", $counter++, $page_body);


$admin_button = file_get_contents('includes/usr_zone_admin.html');
$admin_button = str_replace("<tab1 />", $counter++, $admin_button);
$admin_button = str_replace("<tab2 />", $counter++, $admin_button);
$page_body = str_replace("<utente />",  $admin_button, $page_body);

////

$page_head = str_replace("<page_description/>", "Opera di " . $opera['username'] . " intitolata " . $opera['titolo'], $page_head);
$page_head = str_replace("<keywords/>", "arte, autore, opera, immagine, condividere, " . $opera['nome_categoria'], $page_head);

$content = str_replace("<id_opera />", $_GET['id'], $content);
$content = str_replace("<tab1 />", $counter++, $content);
$content = str_replace("<tab2 />", $counter++, $content);

// Dettagli opera
$content = str_replace("<Path/>", $opera['img_path'], $content);
$content = str_replace("<img_description/>", $opera['descrizione_short'], $content);
$content = str_replace("<descrizione />", $opera['descrizione'], $content);
$content = str_replace("<titolo />", $opera['titolo'], $content);
$content = str_replace("<autore />", $opera['username'], $content);
$content = str_replace("<link_autore />", "autori.php?id=" . $opera['id_autore'], $content);
$content = str_replace("<tab_aut />", $counter++, $content);
$content = str_replace("<data_creazione />", $opera['data_creazione'], $content);
$content = str_replace("<categoria />", $opera['nome_categoria'], $content);
$content = str_replace("<tab_cat />", $counter++, $content);
$content = str_replace("<link_cat />", "categorie.php?id=" . $opera['id_categoria'], $content);



$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>