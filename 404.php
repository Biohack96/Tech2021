<?php
session_start();
require_once('includes/DB.php');

// Oggetto di accesso al database
$db = new DB();

// Titolo della pagina
$title = '404 - Share Arts';

$admin = false;

if(isset($_SESSION['user_id'])) {
    $auth = $db->getAutoreById($_SESSION['user_id']);
    if ($auth['isAdmin']) {
        $admin = true;
    }
}

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);
$page_body = str_replace("<breadcrumb />", "Contenuto non trovato", $page_body);

$page_head = str_replace("<page_description/>", "Contenuto non trovato Share Arts", $page_head);
$page_head = str_replace("<keywords/>", "arte, autore, opere, condividere, pubblicare, esplorare, vedere, immagine, errore", $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);

$page_body = str_replace("<tab1 />", "1", $page_body);
$page_body = str_replace("<tab2 />", "2", $page_body);
$page_body = str_replace("<tab3 />", "3", $page_body);
$page_body = str_replace("<tab4 />", "4", $page_body);

if(isset($_SESSION['user_id']))
{
    if ($admin == true) {
        $admin_button = file_get_contents('includes/usr_zone_admin.html');
        $admin_button = str_replace("<tab1 />", "5", $admin_button);
        $admin_button = str_replace("<tab2 />", "6", $admin_button);
        $page_body = str_replace("<utente />",  $admin_button, $page_body);
        $counter = 7;
    }
    else {
        $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
        $profile_button = str_replace("<tab1 />", "5", $profile_button);
        $profile_button = str_replace("<tab2 />", "6", $profile_button);
        $page_body = str_replace("<utente />", $profile_button, $page_body);
        $counter = 7;
    }
}
else
{
    $login_button = str_replace("<tab />", "5", $login_button);
    $page_body = str_replace("<utente />",$login_button, $page_body);
    $counter = 6;		
}

$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');
$content = file_get_contents('includes/404.html');
$content = str_replace("<tab />",$counter, $content);

$page_body = str_replace('<content />', $content, $page_body);


echo $page_head  . $page_body  ;
?>