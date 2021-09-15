<?php
session_start();
require_once('includes/DB.php');
require_once('includes/error.php');

if (!isset($_SESSION['user_id'])){
    header('Location: login.php');
}
// Oggetto di accesso al database
$db = new DB();

$content = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['registrazione_op'])) {

        $img_path = '';

        if (isset($_FILES['img']) && is_uploaded_file($_FILES['img']['tmp_name'])) {
            $img_path = $_FILES['img']['tmp_name'];
        }
    

    $result = $db->setOpera(htmlentities($_POST['titolo']), htmlentities($_POST['desc_breve']), htmlentities($_POST['desc']), htmlentities($_POST['anno_creazione']),$_SESSION['user_id'], $_POST['category'], $img_path);

    }

if (is_numeric($result)) {
    header('Location: opera.php?id=' . $result . "&from=autore");
}
else {
    $content .= printError($result);
}
}

// Titolo della pagina

$title = 'Carica opera - Share Arts';

$autore = $db->getAutoreById($_SESSION['user_id']);

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_head = str_replace("<page_description/>", "Upload nuova opera di " . $autore['username'], $page_head);
$page_head = str_replace("<keywords/>", "arte, opera, selezione, autore, caricamento, upload, immagine, informazioni", $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);

$page_body = file_get_contents('includes/body.html');

$content .= file_get_contents('includes/registrazione_opera.html');

$page_head = str_replace("<titolo />", $title, $page_head);
$scripts = file_get_contents('includes/script_carica_opera.html');
$page_head = str_replace('<scripts />', $scripts, $page_head);

$link = file_get_contents('includes/link.html');
$link = str_replace("<path />", "autori.php?id=" . $_SESSION['user_id'], $link);
$link = str_replace("<nome_link />", "La tua pagina", $link);
$link = str_replace("<tab />", "1", $link);
$page_body = str_replace("<breadcrumb />", $link . " > Carica opera", $page_body);

$profile_button = file_get_contents('includes/usr_zone_logged.html');
$profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
$profile_button = str_replace("<tab1 />", "6", $profile_button);
$profile_button = str_replace("<tab2 />", "7", $profile_button);
$page_body = str_replace("<utente />", $profile_button, $page_body);

$page_body = str_replace("<tab1 />", "2", $page_body);
$page_body = str_replace("<tab2 />", "3", $page_body);
$page_body = str_replace("<tab3 />", "4", $page_body);
$page_body = str_replace("<tab4 />", "5", $page_body);

$content = str_replace("<section_name />", "Carica opera", $content);

$categorie = $db->getListaCategorie();
$lista_categorie = '';

if($categorie != null) {

    // Per ogni categoria aggiunge un elemento alla lista
    foreach($categorie as $categoria){

        $cat = file_get_contents('includes/categorie_options.html');
        $cat = str_replace("<id_cat/>", $categoria['id'], $cat);
        $cat = str_replace("<nome_cat/>", $categoria['nome_categoria'], $cat);
        $lista_categorie .= $cat;
    }
    $content = str_replace("<options />", $lista_categorie, $content);
}
    


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>