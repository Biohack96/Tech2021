<?php
session_start();
require_once('includes/DB.php');

if(!isset($_SESSION['user_id'])) {
    header('Location: 404.php');
}

// Oggetto di accesso al database
$db = new DB();

$auth = $db->getAutoreById($_SESSION['user_id']);

if ($auth['isAdmin'] == false) {
    header('Location: 404.php');
}


// Titolo della pagina
$title = 'Admin - Share Arts';

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);

$link = file_get_contents('includes/link.html');
$link = str_replace("<path />", "admin.php", $link);
$link = str_replace("<nome_link />", "Pannello amministratore", $link);
$link = str_replace("<tab />", "1", $link);

$page_body = str_replace("<breadcrumb />", $link . " > Autori segnalati", $page_body);
$page_head = str_replace("<page_description/>", "Pannello amministratore Share Arts", $page_head);
$page_head = str_replace("<keywords/>", "amministratore, admin, revisione, profili, autori", $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);

$page_body = str_replace("<tab1 />", "2", $page_body);
$page_body = str_replace("<tab2 />", "3", $page_body);
$page_body = str_replace("<tab3 />", "4", $page_body);
$page_body = str_replace("<tab4 />", "5", $page_body);

/////gestione login/logout
$admin_button = file_get_contents('includes/usr_zone_admin.html');
$admin_button = str_replace("<tab1 />", "6", $admin_button);
$admin_button = str_replace("<tab2 />", "7", $admin_button);
$counter = 8;

$page_body = str_replace("<utente />", $admin_button, $page_body);


$content = file_get_contents('includes/autori_segnalati.html');

$autori = $db->getAutoriSegnalati();

$lista_autori = '';

    if($autori != null) {

        // Per ogni autore aggiunge un elemento alla lista
        foreach($autori as $autore){

            $aut = file_get_contents('includes/nome_aut_segn_inlist.html');
            $aut = str_replace("<username />", $autore['username'], $aut);
            $aut = str_replace("<id_aut />", $autore['id'], $aut);
            $aut = str_replace("<tab />", $counter, $aut);
            $lista_autori .= $aut;
            $counter++;
    }

    $content = str_replace("<authors/>", $lista_autori, $content);
    }

    $page_body = str_replace('<content />', $content, $page_body);


echo $page_head  . $page_body  ;
?>