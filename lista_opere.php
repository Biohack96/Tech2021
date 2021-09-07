<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();

// Titolo della pagina

$title = 'Opere - Share Arts';

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$content = file_get_contents('includes/opere_list.html');

$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);

/////gestione login/logout
$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');

if(isset($_SESSION['user_id']))
{
    $page_body = str_replace("<utente />", $profile_button, $page_body);			
}
else
{
    $page_body = str_replace("<utente />",$login_button, $page_body);			
}
////
$page_body = str_replace('<errors />', "", $page_body);

$page_body = str_replace("<breadcrumb />", "Tutte le opere", $page_body);
$content = str_replace("<section_name />", "Tutte le opere", $content);

$counter = 5; // TODO: esempio, da cambiare

$opere = $db->getAllOpere();
$lista_opere = '';

if($opere != null) {

    foreach($opere as $opera){

        $op = file_get_contents('includes/opere_card.html');
        $op = str_replace("<id_opera/>", $opera['id'], $op);
        $op = str_replace("<from/>", "all", $op);
        $op = str_replace("<Path/>", $opera['img_path'], $op);
        $op = str_replace("<Titolo/>", $opera['titolo'], $op);
        $op = str_replace("<descrizione/>", $opera['descrizione_short'], $op);
        $op = str_replace("<Nomeutente/>", $opera['username'], $op);
        $op = str_replace("<Categoria/>", $opera['nome_categoria'], $op);
        $op = str_replace('<tabindex/>', $counter, $op);
        $lista_opere .= $op;
        $counter++;
}

$content = str_replace("<opere/>", $lista_opere, $content);
}

// Disattiva link circolare
$page_body = str_replace('<li><a href="lista_opere.php">Tutte le opere</a></li>', '<li>Tutte le opere</li>', $page_body);		// da aggiungere dinamicamente


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>