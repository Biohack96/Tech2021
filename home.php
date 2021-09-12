<?php
session_start();
// require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
// $db = new DB();

// Titolo della pagina
$title = 'Share Arts';

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);
$page_head = str_replace("<page_description/>", "Panoramica principale del sito Share Arts", $page_head);
$page_head = str_replace("<keywords/>", "arte, autore, opere, condividere, pubblicare, esplorare, vedere, immagine", $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);
$page_body = str_replace("<breadcrumb />", "Home", $page_body);  	// da aggiungere


$page_body = str_replace("<tab2 />", "1", $page_body);
$page_body = str_replace("<tab3 />", "2", $page_body);
$page_body = str_replace("<tab4 />", "3", $page_body);
/////gestione login/logout
$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');

if(isset($_SESSION['user_id']))
{
    $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
    $profile_button = str_replace("<tab1 />", "4", $profile_button);
    $profile_button = str_replace("<tab2 />", "5", $profile_button);
    $page_body = str_replace("<utente />", $profile_button, $page_body);			
}
else
{
    $login_button = str_replace("<tab />", "4", $login_button);
    $page_body = str_replace("<utente />",$login_button, $page_body);			
}
////

$page_body = str_replace("<content />", "", $page_body);		// da aggiungere

// Disattiva link circolare
$page_body = str_replace('<li><a href="home.php" tabindex="<tab1 />"><span xml:lang="en">Home</span></a></li>', '<li><span xml:lang="en">Home</span></li>', $page_body);		// da aggiungere dinamicamente



echo $page_head  . $page_body  ;
?>