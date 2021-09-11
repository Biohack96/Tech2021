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


////

if (!isset($_GET['id'])){

// Disattiva link circolare
$page_body = str_replace('<li><a href="autori.php" tabindex="<tab4 />">Autori</a></li>', '<li>Autori</li>', $page_body);

    if (isset($_SESSION['user_id'])) {
        $autori = $db->getAutoriLogged($_SESSION['user_id']);
    
    }

    else {
        $autori = $db->getAutori();
    }

$content = file_get_contents('includes/autori_list.html');
$page_body = str_replace("<breadcrumb />", "Autori", $page_body);

    $page_body = str_replace("<tab1 />", "1", $page_body);
    $page_body = str_replace("<tab2 />", "2", $page_body);
    $page_body = str_replace("<tab3 />", "3", $page_body);

    if(isset($_SESSION['user_id'])) {
        $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
        $profile_button = str_replace("<tab1 />", "4", $profile_button);
        $profile_button = str_replace("<tab2 />", "5", $profile_button);
        $page_body = str_replace("<utente />",  $profile_button, $page_body);
        $counter = 6;
    }
    else {
        $login_button = str_replace("<tab />", "6", $login_button);
        $page_body = str_replace("<utente />",$login_button, $page_body);
        $counter = 7;		
    }


$lista_autori = '';

    if($autori != null) {

        // Per ogni autore aggiunge un elemento alla lista
        foreach($autori as $autore){

            $aut = file_get_contents('includes/nome_autore_inlist.html');
            $aut = str_replace("<username />", $autore['username'], $aut);
            $aut = str_replace("<id_aut />", $autore['id'], $aut);
            $aut = str_replace("<tab />", $counter, $aut);
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
    $link = str_replace("<tab />", "1", $link);

        if(isset ($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']) {
            $page_body = str_replace("<tab1 />", "1", $page_body);
            $page_body = str_replace("<tab2 />", "2", $page_body);
            $page_body = str_replace("<tab3 />", "3", $page_body);
            $page_body = str_replace("<tab4 />", "4", $page_body);

            $page_body = str_replace("<breadcrumb />", "La tua pagina", $page_body);
            $profile_button = str_replace('<li><a id="nome_utente" href="autori.php?id=<id_aut />" tabindex="<tab1 />">La tua pagina</a></li>', '<li>La tua pagina</li>', $profile_button);
            $profile_button = str_replace("<tab2 />", "5", $profile_button);
            $page_body = str_replace("<utente />", $profile_button, $page_body);
            
            $button_aggiungi_opera = file_get_contents('includes/button_aggiungi_opera.html');
            $button_aggiungi_opera = str_replace("<tab />", "6", $button_aggiungi_opera);
            $content = str_replace("<button_aggiungi_opera />", $button_aggiungi_opera, $content);

            $button_elimina_profilo = file_get_contents('includes/button_elimina_profilo.html');
            $button_elimina_profilo = str_replace("<tab />", "7", $button_elimina_profilo);
            $button_elimina_profilo = str_replace("<id_aut />", $_GET['id'], $button_elimina_profilo);
            $content = str_replace("<button_elimina_profilo />", $button_elimina_profilo, $content);
            
            $counter = 8;
            
        }
        else {
            $content = str_replace("<button_aggiungi_opera />", "", $content);
            $page_body = str_replace("<breadcrumb />", $link . " > " . $a['username'], $page_body);
            $page_body = str_replace("<tab1 />", "2", $page_body);
            $page_body = str_replace("<tab2 />", "3", $page_body);
            $page_body = str_replace("<tab3 />", "4", $page_body);
            $page_body = str_replace("<tab4 />", "5", $page_body);

            if(isset ($_SESSION['user_id'])) {
                $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
                $profile_button = str_replace("<tab1 />", "6", $profile_button);
                $profile_button = str_replace("<tab2 />", "7", $profile_button);
                $page_body = str_replace("<utente />",  $profile_button, $page_body);
                $counter = 8;
            }

            else {
                $login_button = str_replace("<tab />", "6", $login_button);
                $page_body = str_replace("<utente />",$login_button, $page_body);
                $counter = 7;
            }
        }
    $opere = $db->getOpereByAuthor($_GET['id']);
    $opere_content = file_get_contents('includes/opere_list.html');

    $lista_opere = '';

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
        $opere_content = str_replace("<opere/>", "<p>Nessuna opera</p>", $opere_content);
    }

    $content = str_replace("<opere />", $opere_content, $content);
}


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>