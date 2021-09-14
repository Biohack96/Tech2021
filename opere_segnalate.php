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

$page_body = str_replace("<breadcrumb />", $link . " > Opere segnalate", $page_body);
$page_head = str_replace("<page_description/>", "Pannello amministratore Share Arts", $page_head);
$page_head = str_replace("<keywords/>", "amministratore, admin, revisione, opere, profili", $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);

$page_body = str_replace("<tab1 />", "2", $page_body);
$page_body = str_replace("<tab2 />", "3", $page_body);
$page_body = str_replace("<tab3 />", "4", $page_body);
$page_body = str_replace("<tab4 />", "5", $page_body);

/////gestione login/logout
$admin_button = file_get_contents('includes/usr_zone_admin.html');
$admin_button = str_replace("<tab1 />", "6", $admin_button);
$admin_button = str_replace("<tab2 />", "7", $admin_button);


$page_body = str_replace("<utente />", $admin_button, $page_body);


$content = file_get_contents('includes/opere_segnalate.html');

$opere_segnalate = $db->getOpereSegnalate();

$lista_opere = '';

if ($opere_segnalate != null) {
    $counter = 8;
    foreach ($opere_segnalate as $opera_segnalata) {

        $op = file_get_contents('includes/opere_segnalate_card.html');
        $op = str_replace("<id_opera/>", $opera_segnalata['id'], $op);
        $op = str_replace("<Path/>", $opera_segnalata['img_path'], $op);
        $op = str_replace("<Titolo/>", $opera_segnalata['titolo'], $op);
        $op = str_replace("<descrizione/>", $opera_segnalata['descrizione_short'], $op);
        $op = str_replace("<Nomeutente/>", $opera_segnalata['username'], $op);
        $op = str_replace("<Categoria/>", $opera_segnalata['nome_categoria'], $op);
        $op = str_replace('<tabindex/>', $counter, $op);
        $lista_opere .= $op;
        $counter++;

    }
    $content = str_replace("<opere_segnalate />", $lista_opere, $content);
}

else {
    $content = str_replace("<opere_segnalate />", '<p id="no_opere_segnalate">Nessun opera segnalata</p>', $content);
}

$page_body = str_replace('<content />', $content, $page_body);


echo $page_head  . $page_body  ;
?>