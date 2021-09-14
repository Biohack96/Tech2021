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

$a = $db->getAutoreById($_GET['id']);

$title = $a['username'] . " - Share Arts";
$page_head = str_replace("<titolo />", $title, $page_head);

$page_head = str_replace("<page_description/>", "Profilo e opere di " . $a['username'], $page_head);
$page_head = str_replace("<keywords/>", "arte, autore, immagine, opera, condividere, " . $a['username'], $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);

$counter = 1;

// Creazione breadcrumb
$link = file_get_contents('includes/link.html');
$link = str_replace("<path />", "admin.php", $link);
$link = str_replace("<nome_link />", "Pannello amministratore", $link);
$link = str_replace("<tab />", $counter++, $link);

$link2 = file_get_contents('includes/link.html');
$link2 = str_replace("<path />", "autori_segnalati.php", $link2);
$link2 = str_replace("<nome_link />", "Autori segnalati", $link2);
$link2 = str_replace("<tab />", $counter++, $link2);

$page_body = str_replace("<breadcrumb />", $link . " > " . $link2 . " > " . $a['username'], $page_body);

$page_body = str_replace("<tab1 />", $counter++, $page_body);
$page_body = str_replace("<tab2 />", $counter++, $page_body);
$page_body = str_replace("<tab3 />", $counter++, $page_body);
$page_body = str_replace("<tab4 />", $counter++, $page_body);


$admin_button = file_get_contents('includes/usr_zone_admin.html');
$admin_button = str_replace("<tab1 />", $counter++, $admin_button);
$admin_button = str_replace("<tab2 />", $counter++, $admin_button);
$page_body = str_replace("<utente />",  $admin_button, $page_body);

$content = file_get_contents('includes/content_pagina_autore_segnalato.html');

$content = str_replace("<username />", $a['username'], $content);
$content = str_replace("<informazioni />", $a['bio'], $content);
$content = str_replace("<id_aut />", $_GET['id'], $content);
$content = str_replace("<tab1 />", $counter++, $content);
$content = str_replace("<tab2 />", $counter++, $content);


$opere = $db->getMyOpere($_GET['id']);

$opere_content = file_get_contents('includes/opere_list.html');
$opere_content = str_replace("<section_name />", "", $opere_content);


$lista_opere = '';


if($opere != null) {

    foreach($opere as $opera){

        $op = file_get_contents('includes/opere_card.html');
        $op = str_replace("<id_opera/>", $opera['id'], $op);
        $op = str_replace("<from/>", "autore", $op);
        $op = str_replace("<Path/>", $opera['img_path'], $op);
        $op = str_replace("<Titolo/>", $opera['titolo'], $op);
        $op = str_replace("<descrizione/>", $opera['descrizione_short'], $op);
        $op = str_replace("<Nomeutente/>", "", $op);
        $op = str_replace("<Categoria/>", $opera['nome_categoria'], $op);
        $op = str_replace('<tabindex/>', $counter, $op);
        $lista_opere .= $op;
        $counter++;
}

$opere_content = str_replace("<opere/>", $lista_opere, $opere_content);
}

else {
    $opere_content = str_replace("<opere/>", "<p>Nessuna opera</p>", $opere_content);
}

    $content = str_replace("<opere />", $opere_content, $content);




$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>