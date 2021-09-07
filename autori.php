<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();

// Titolo della pagina

$title = 'Autori - Share Arts';

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$page_body = str_replace('<errors />', "", $page_body);
$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);

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

if (!isset($_GET['id'])){

// Disattiva link circolare
$page_body = str_replace('<li><a href="autori.php">Autori</a></li>', '<li>Autori</li>', $page_body);

$autori = $db->getAutori();

$content = file_get_contents('includes/autori_list.html');
$page_body = str_replace("<breadcrumb />", "Autori", $page_body);

$counter = 5; // TODO: esempio, da cambiare
$lista_autori = '';

    if($autori != null) {

        // Per ogni autore aggiunge un elemento alla lista
        foreach($autori as $autore){

            $aut = file_get_contents('includes/nome_autore_inlist.html');
            $aut = str_replace("<username />", $autore['username'], $aut);
            $aut = str_replace("<id_aut />", $autore['id'], $aut);
            $lista_autori .= $aut;
            $counter++;
    }

    $content = str_replace("<authors/>", $lista_autori, $content);
    }
}

else {

    $content = file_get_contents('includes/content_pagina_autore.html');

    $a = $db->getAutoreById($_GET['id']);
    $content = str_replace("<username />", $a['username'], $content);
    $content = str_replace("<informazioni />", $a['bio'], $content);
    $link = file_get_contents('includes/link.html');
    $link = str_replace("<path />", "autori.php", $link);
    $link = str_replace("<nome_link />", "Autori", $link);
    $page_body = str_replace("<breadcrumb />", $link . " > " . $a['username'], $page_body);

    $opere = $db->getOpereByAuthor($_GET['id']);
    $opere_content = file_get_contents('includes/opere_list.html');

    $lista_opere = '';
    $counter = 5; // TODO da cambiare

// TODO rimpiazzare tutti i tag button

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
        $opere_content = str_replace("<opere/>", "Nessuna opera", $opere_content);
    }

    $content = str_replace("<opere />", $opere_content, $content);
}


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>