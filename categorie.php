<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();

// Titolo della pagina

$title = 'Categorie - Share Arts';

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html');

$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);

$page_body = str_replace("<utente />", "", $page_body);			// da aggiungere

// Disattiva link circolare
$page_body = str_replace('<li><a href="categorie.php">Categorie</a></li>', '<li>Categorie</li>', $page_body);		// da aggiungere dinamicamente


// Se non è settato un id mostra la lista delle categorie
if (!isset($_GET['id'])) {

    $page_body = str_replace("<breadcrumb />", "Categorie", $page_body);
    $content = file_get_contents('includes/categorie_list.html');
    $categorie = $db->getListaCategorie();
    $lista_categorie = '';

    if($categorie != null) {

        // Per ogni categoria aggiunge un elemento alla lista
        foreach($categorie as $categoria){

            $cat = file_get_contents('includes/categorie_list_element.html');
            $cat = str_replace("<categoria_element />", $categoria['nome_categoria'], $cat);
            $cat = str_replace("<id_cat />", $categoria['id'], $cat);
            $lista_categorie .= $cat;
    }

    $content = str_replace("<categorie />", $lista_categorie, $content);
    }

}

// Se è settato un id mostra la lista delle opere filtrate per la categoria passata in GET
else {

    $counter = 5; // TODO: esempio, da cambiare
    $content = file_get_contents('includes/opere_list.html');

    $nome_categoria = $db->getCategoriaName($_GET['id']);
    $page_body = str_replace("<breadcrumb />", "Categorie > " . $nome_categoria['nome_categoria'], $page_body);
    $content = str_replace("<section_name />", $nome_categoria['nome_categoria'], $content);

    $opere = $db->getOpereByCategoria($_GET['id']);
    $lista_opere = '';

    if($opere != null) {

        foreach($opere as $opera){

            $op = file_get_contents('includes/opere_card.html');
            $op = str_replace("<id_opera/>", $opera['id'], $op);
            $op = str_replace("<Path/>", $opera['img_path'], $op);
            $op = str_replace("<Titolo/>", $opera['titolo'], $op);
            $op = str_replace("<Nomeutente/>", $opera['username'], $op);
            $op = str_replace('<tabindex/>', $counter, $op);
            $lista_opere .= $op;
			$counter++;
    }

    $content = str_replace("<opere/>", $lista_opere, $content);
    }

}


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>