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
$page_body = str_replace("<breadcrumb />", "Pannello amministratore", $page_body);
$page_head = str_replace("<page_description/>", "Pannello amministratore Share Arts", $page_head);
$page_head = str_replace("<keywords/>", "amministratore, admin, revisione, opere, profili", $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);

$page_body = str_replace("<tab1 />", "1", $page_body);
$page_body = str_replace("<tab2 />", "2", $page_body);
$page_body = str_replace("<tab3 />", "3", $page_body);
$page_body = str_replace("<tab4 />", "4", $page_body);

/////gestione login/logout
$admin_button = file_get_contents('includes/usr_zone_admin.html');
$admin_button = str_replace("<tab2 />", "5", $admin_button);
$content = file_get_contents('includes/admin.html');

$content = str_replace("<tab1 />", "6", $content);
$content = str_replace("<tab2 />", "7", $content);

$admin_button = str_replace('<a id="nome_utente" href="admin.php" tabindex="<tab1 />">Pannello amministratore</a>', "Pannello amministratore", $admin_button);
$page_body = str_replace("<utente />", $admin_button, $page_body);			

////
// TODO fixare tabindex

$page_body = str_replace('<content />', $content, $page_body);


echo $page_head  . $page_body  ;
?>