<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();

// Titolo della pagina

$title = 'Opere - Share Arts';

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

$content = file_get_contents('includes/opere_list.html');

$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);

$page_head = str_replace("<page_description/>", "Panoramica di tutte le opere caricate nel sito", $page_head);
$page_head = str_replace("<keywords/>", "arte, opera, panoramica, esplorare, condividere, immagine", $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);

// Disattiva link circolare
$page_body = str_replace('<li><a href="lista_opere.php" tabindex="<tab2 />">Tutte le opere</a></li>', '<li>Tutte le opere</li>', $page_body);

$page_body = str_replace("<tab1 />", "1", $page_body);
$page_body = str_replace("<tab3 />", "2", $page_body);
$page_body = str_replace("<tab4 />", "3", $page_body);

/////gestione login/logout
$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');


    if(isset($_SESSION['user_id'])) {
        if ($admin) {
            $admin_button = file_get_contents('includes/usr_zone_admin.html');
            $admin_button = str_replace("<tab1 />", "4", $admin_button);
            $admin_button = str_replace("<tab2 />", "5", $admin_button);
            $page_body = str_replace("<utente />",  $admin_button, $page_body);
            $counter = 6+4;
        }
        else {
            $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
            $profile_button = str_replace("<tab1 />", "4", $profile_button);
            $profile_button = str_replace("<tab2 />", "5", $profile_button);
            $page_body = str_replace("<utente />", $profile_button, $page_body);
            $counter = 6+4;	
        }		
    }

    else {
    $login_button = str_replace("<tab />", "4", $login_button);
    $page_body = str_replace("<utente />",$login_button, $page_body);			
    $counter = 5+5;			
    }
////

$page_body = str_replace("<breadcrumb />", "Tutte le opere", $page_body);
$content = str_replace("<section_name />", "Tutte le opere", $content);


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
else
{
    $content = str_replace("<opere/>", "<span class=\"no_results\">Non sono presenti opere al momento</span>", $content);
}


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>