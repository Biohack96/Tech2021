<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();

if(!isset($_SESSION['user_id']))
{
    header('Location: Login.php');
}
$content = "";
$a = $db->getAutoreById($_SESSION['user_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = $db->updateProfilo($_POST['username'], $_POST['password'], $_POST['conferma_password'],$_POST['bio']);

if (is_numeric($result)) {
    header('Location: autori.php?id=' . $_SESSION['user_id']);
  } else {
   foreach($result as $key =>$varl)
   {
    $content .=  '<ul>';
    $content .=  '<li class="error">'. $result[$key] . ' </li>';
    $content .=  '</ul>';
   }
  }
}
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$page_head = str_replace("<scripts />", "", $page_head);

/////gestione login/logout
$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');


$title = $a['username'] . " - Share Arts";
$page_head = str_replace("<titolo />", $title, $page_head);

$page_head = str_replace("<page_description/>", "Modifica profilo di " . $a['username'], $page_head);
$page_head = str_replace("<keywords/>", "arte, autore, immagine, opera, condividere, " . $a['username'], $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);

$content .= file_get_contents('includes/content_modifica_pagina.html');

$content = str_replace("<user_edit/>", $a['username'], $content);
$content = str_replace("<bio/>", $a['bio'], $content);

if (isset($_SESSION['user_id'])) {
    $auth = $db->getAutoreById($_SESSION['user_id']);
}

$content = str_replace("<button_aggiungi_opera />", "", $content);
            $content = str_replace("<button_modifica_profilo />", "", $content);
            $page_body = str_replace("<breadcrumb />", "Modifica profilo", $page_body);
            $page_body = str_replace("<tab1 />", "2", $page_body);
            $page_body = str_replace("<tab2 />", "3", $page_body);
            $page_body = str_replace("<tab3 />", "4", $page_body);
            $page_body = str_replace("<tab4 />", "5", $page_body);
            $counter = 6+4;


            $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
            $profile_button = str_replace("<tab1 />", $counter++, $profile_button);
            $profile_button = str_replace("<tab2 />", $counter++, $profile_button);
            $page_body = str_replace("<utente />",  $profile_button, $page_body);

$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>