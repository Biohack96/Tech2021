<?php
session_start();
require_once('includes/DB.php');
require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();

$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');


$info_utente = createInfoUtente($db);
$page_body = str_replace('<info_utente />', $info_utente, $page_body);


$content = file_get_contents('includes/info.html');

$title = 'Info';

$page_head = str_replace('<titolo />', $title, $page_head);
 
$page_body = str_replace('<content />', $content, $page_body);

echo $page_head . $page_body;
?>