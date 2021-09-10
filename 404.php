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
$page_body = str_replace("<breadcrumb />", "", $page_body);  	// da aggiungere

/////gestione login/logout
$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');
$content = file_get_contents('includes/404.html');

if(isset($_SESSION['user_id']))
{
    $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
    $page_body = str_replace("<utente />", $profile_button, $page_body);			
}
else
{
    $page_body = str_replace("<utente />",$login_button, $page_body);			
}
////
// TODO fixare tabindex

$page_body = str_replace('<content />', $content, $page_body);


echo $page_head  . $page_body  ;
?>