<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

if(!isset($_GET['id'])) {
    header('Location: lista_opere.php');
}

// Oggetto di accesso al database
$db = new DB();

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

$content = file_get_contents('includes/content_opera.html');

$page_head = str_replace('<scripts />', "", $page_head);

$opera = $db->getOperaById($_GET['id']);

$title = $opera['titolo'] . ' - Share Arts';
$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);


// Creazione breadcrumb

if ($_GET['from'] == "autore") {
    $link2 = file_get_contents('includes/link.html');
    $link2 = str_replace("<path />", "autori.php?id=".$opera['id_autore'], $link2);    

    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $opera['id_autore']) {
        $link2 = str_replace("<nome_link />", "La tua pagina", $link2);
        $link2 = str_replace("<tab />", "1", $link2);
        $page_body = str_replace("<breadcrumb />", $link2 . " > " . $opera['titolo'], $page_body);
        $counter = 2;
    }
    else {
        $link = file_get_contents('includes/link.html');
        $link = str_replace("<path />", "autori.php", $link);
        $link = str_replace("<nome_link />", "Autori", $link);
        $link = str_replace("<tab />", "1", $link);
        $link2 = str_replace("<nome_link />", $opera['username'], $link2);
        $link2 = str_replace("<tab />", "2", $link2);
        $page_body = str_replace("<breadcrumb />", $link . " > " . $link2 . " > " . $opera['titolo'], $page_body);
        $counter = 3;
    }
}

else if ($_GET['from'] == "all") {
    $link = file_get_contents('includes/link.html');
    $link = str_replace("<path />", "lista_opere.php", $link);
    $link = str_replace("<nome_link />", "Tutte le opere", $link);
    $link = str_replace("<tab />", "1", $link);
    $page_body = str_replace("<breadcrumb />", $link . " > " . $opera['titolo'], $page_body);
    $counter = 2;
}

else if ($_GET['from'] == "cat") {
    $link = file_get_contents('includes/link.html');
    $link = str_replace("<path />", "categorie.php", $link);
    $link = str_replace("<nome_link />", "Categorie", $link);
    $link = str_replace("<tab />", "1", $link);
    $link2 = file_get_contents('includes/link.html');
    $link2 = str_replace("<path />", "categorie.php?id=".$opera['id_categoria'], $link2);
    $link2 = str_replace("<nome_link />", $opera['nome_categoria'], $link2);
    $link2 = str_replace("<tab />", "2", $link2);
    $page_body = str_replace("<breadcrumb />", $link . " > " . $link2 . " > " . $opera['titolo'], $page_body);
    $counter = 3;
}


$page_body = str_replace("<tab1 />", $counter++, $page_body);
$page_body = str_replace("<tab2 />", $counter++, $page_body);
$page_body = str_replace("<tab3 />", $counter++, $page_body);
$page_body = str_replace("<tab4 />", $counter++, $page_body);

/////gestione login/logout
$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');

if(isset($_SESSION['user_id']))
{
    if ($admin) {
        $admin_button = file_get_contents('includes/usr_zone_admin.html');
        $admin_button = str_replace("<tab1 />", $counter++, $admin_button);
        $admin_button = str_replace("<tab2 />", $counter++, $admin_button);
        $page_body = str_replace("<utente />",  $admin_button, $page_body);
        $counter = 6;
    }
    else {
        $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
        $profile_button = str_replace("<tab1 />", $counter++, $profile_button);
        $profile_button = str_replace("<tab2 />", $counter++, $profile_button);
        $page_body = str_replace("<utente />", $profile_button, $page_body);			
    }
}
else
{
    $login_button = str_replace("<tab />", $counter++, $login_button);
    $page_body = str_replace("<utente />",$login_button, $page_body);			
}
////

$page_head = str_replace("<page_description/>", "Opera di " . $opera['username'] . " intitolata " . $opera['titolo'], $page_head);
$page_head = str_replace("<keywords/>", "arte, autore, opera, immagine, condividere, " . $opera['nome_categoria'], $page_head);

$content = str_replace("<section_name />", "Tutte le opere", $content);

// Button elimina opera
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $opera['id_autore']){
    $button_elimina = file_get_contents('includes/button_elimina_opera.html');
    $button_elimina = str_replace("<id_opera />", $_GET['id'], $button_elimina);
    $button_elimina = str_replace("<tab />", $counter++, $button_elimina);
    $content = str_replace("<button_elimina />", $button_elimina, $content);
}

else if (isset($_SESSION['user_id']) && $admin) {
    $button_elimina = file_get_contents('includes/button_elimina_opera.html');
    $button_elimina = str_replace("<id_opera />", $_GET['id'], $button_elimina);
    $button_elimina = str_replace("<tab />", $counter++, $button_elimina);
    $content = str_replace("<button_elimina />", $button_elimina, $content);
}

else {
    $content = str_replace("<button_elimina />", "", $content);
}

// button segnala

if ($opera['segnalata'] == true) {
    if ($admin) {
    $content = str_replace("<button_segnala />", "<p class=\"avviso_segnalata\">Quest'opera è stata segnalata ed è in attesa di revisione, pertanto al momento non è visibile al pubblico.</p>", $content);
    }
    else {
    $content = str_replace("<button_segnala />", "<p class=\"avviso_segnalata\">Quest'opera è stata segnalata ed è in attesa di revisione da parte degli amministratori, pertanto al momento non è visibile al pubblico.</p>", $content);
    }
}

else if (!isset($_SESSION['user_id']) || ($_SESSION['user_id'] != $opera['id_autore'] && $admin==false)) {
    $button_segnala = file_get_contents('includes/button_segnala_opera.html');
    $button_segnala = str_replace("<id_opera />", $_GET['id'], $button_segnala);
    $button_segnala = str_replace("<tab />", $counter++, $button_segnala);
    $content = str_replace("<button_segnala />", $button_segnala, $content);
    }
else {
    $content = str_replace("<button_segnala />", "", $content);
}


// Dettagli opera
$content = str_replace("<Path/>", $opera['img_path'], $content);
$content = str_replace("<img_description/>", $opera['descrizione_short'], $content);
$content = str_replace("<descrizione />", $opera['descrizione'], $content);
$content = str_replace("<titolo />", $opera['titolo'], $content);
$content = str_replace("<autore />", $opera['username'], $content);
$content = str_replace("<link_autore />", "autori.php?id=" . $opera['id_autore'], $content);
$content = str_replace("<tab_aut />", $counter++, $content);
$content = str_replace("<data_creazione />", $opera['data_creazione'], $content);
$content = str_replace("<categoria />", $opera['nome_categoria'], $content);
$content = str_replace("<tab_cat />", $counter++, $content);
$content = str_replace("<link_cat />", "categorie.php?id=" . $opera['id_categoria'], $content);


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>