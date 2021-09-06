<?php
session_start();
require_once('includes/DB.php');
require_once('includes/error.php');
// require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();

// Titolo della pagina

$title = 'Carica opera - Share Arts';

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$content = file_get_contents('includes/registrazione_opera.html');

$page_head = str_replace("<titolo />", $title, $page_head);
$scripts = file_get_contents('includes/script_carica_opera.html');
$page_head = str_replace('<scripts />', $scripts, $page_head);

$page_body = str_replace("<utente />", "", $page_body);			// da aggiungere

$page_body = str_replace("<breadcrumb />", "Carica opera", $page_body);
$content = str_replace("<section_name />", "Carica opera", $content);

$counter = 5; // TODO: esempio, da cambiare

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        if (isset($_POST['registrazione_op'])) {

            $img_path = '';

            if (isset($_FILES['img']) && is_uploaded_file($_FILES['img']['tmp_name'])) {
                $img_path = $_FILES['img']['tmp_name'];
            }
        
    
        $result = $db->setOpera($_POST['titolo'], $_POST['desc_breve'], $_POST['desc'], $_POST['anno_creazione'], 2, $_POST['category'], $img_path);
    
        }

    if (is_numeric($result)) {
        header('Location: opera.php?id=' . $result);
    }
    else {
        $page_body = str_replace('<p></p>', printError($result), $page_body);
    }
}
    


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>