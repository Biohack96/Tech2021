<?php
session_start();
require_once('includes/DB.php');

// Oggetto di accesso al database
$db = new DB();

// Titolo della pagina
$title = 'Home - Share Arts';

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
$page_head = str_replace("<page_description/>", "Panoramica principale del sito Share Arts", $page_head);
$page_head = str_replace("<keywords/>", "arte, autore, opere, condividere, pubblicare, esplorare, vedere, immagine", $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);
$page_body = str_replace("<breadcrumb />", "Home", $page_body);


$page_body = str_replace("<tab2 />", "1", $page_body);
$page_body = str_replace("<tab3 />", "2", $page_body);
$page_body = str_replace("<tab4 />", "3", $page_body);
/////gestione login/logout
$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');

if(isset($_SESSION['user_id']))
{
    if ($admin == true) {
        $admin_button = file_get_contents('includes/usr_zone_admin.html');
        $admin_button = str_replace("<tab1 />", "4", $admin_button);
        $admin_button = str_replace("<tab2 />", "5", $admin_button);
        $page_body = str_replace("<utente />",  $admin_button, $page_body);
    }
    else {
        $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
        $profile_button = str_replace("<tab1 />", "4", $profile_button);
        $profile_button = str_replace("<tab2 />", "5", $profile_button);
        $page_body = str_replace("<utente />", $profile_button, $page_body);			
    }
}
else
{
    $login_button = str_replace("<tab />", "4", $login_button);
    $page_body = str_replace("<utente />",$login_button, $page_body);			
}
////

$content = file_get_contents('includes/home.html');



$page_body = str_replace("<content />", $content, $page_body);		// da aggiungere

// Disattiva link circolare
$page_body = str_replace('<li><a href="home.php" tabindex="<tab1 />"><span xml:lang="en">Home</span></a></li>', '<li><span xml:lang="en">Home</span></li>', $page_body);		// da aggiungere dinamicamente



echo $page_head  . $page_body  ;
?>