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

$content = file_get_contents('includes/autori_list.html');

$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);

$page_body = str_replace("<breadcrumb />", "", $page_body);  	// da aggiungere
$page_body = str_replace("<utente />", "", $page_body);			// da aggiungere


$autori = $db->getAutori();

$lista_autori = '';

    if($autori != null) {

        // Per ogni autore aggiunge un elemento alla lista
        foreach($autori as $autore){

            $aut = file_get_contents('includes/nome_autore_inlist.html');
            $aut = str_replace("<username />", $autore['username'], $aut);
            $aut = str_replace("<id_aut />", $autore['id'], $aut);
            $lista_autori .= $aut;
    }

    $content = str_replace("<authors/>", $lista_autori, $content);
    }


// Disattiva link circolare
$page_body = str_replace('<li><a href="autori.php">Autori</a></li>', '<li>Autori</li>', $page_body);		// da aggiungere dinamicamente


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>