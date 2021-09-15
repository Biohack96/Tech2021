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

$page_head = str_replace("<scripts />", "", $page_head);


$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');

$admin = false;

if(isset($_SESSION['user_id'])) {
    $auth = $db->getAutoreById($_SESSION['user_id']);
    if ($auth['isAdmin']) {
        $admin = true;
    }
}


// Se non è settato un id mostra la lista delle categorie
if (!isset($_GET['id'])) {

    $title = 'Categorie - Share Arts';
    $page_head = str_replace("<titolo />", $title, $page_head);

    $page_head = str_replace("<page_description/>", "Elenco delle categorie di opere presenti nel sito", $page_head);
    $page_head = str_replace("<keywords/>", "arte, opera, selezione, esplorare, categorie", $page_head);
    $page_head = str_replace("<metatitle/>", $title, $page_head);

    // Disattiva link circolare
    $page_body = str_replace('<li><a href="categorie.php" tabindex="<tab3 />">Categorie</a></li>', '<li>Categorie</li>', $page_body);		// da aggiungere dinamicamente

    $page_body = str_replace("<tab1 />", "1", $page_body);
    $page_body = str_replace("<tab2 />", "2", $page_body);
    $page_body = str_replace("<tab4 />", "3", $page_body);

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
    $counter = 5;			
    }

    $page_body = str_replace("<breadcrumb />", "Categorie", $page_body);
    $content = file_get_contents('includes/categorie_list.html');
    
    if(isset($_GET['trova_categoria']) && !empty($_GET['nome']))
    {
            $categorie = $db->getListaCategorieS($_GET['nome']);
            $lista_categorie = '';

            if($categorie != null) {

                // Per ogni categoria aggiunge un elemento alla lista
                foreach($categorie as $categoria){

                    $cat = file_get_contents('includes/categorie_list_element.html');
                    $cat = str_replace("<categoria_element />", $categoria['nome_categoria'], $cat);
                    $cat = str_replace("<id_cat />", $categoria['id'], $cat);
                    $cat = str_replace("<tab />", $counter, $cat);
                    $lista_categorie .= $cat;
                    $counter++;
            }

        }
                    

        $content = str_replace("<categorie />", empty($lista_categorie)?"<li/>":$lista_categorie, $content);

        $content = str_replace("<cercato/>", " risultati per " . htmlentities($_GET['nome']), $content);
    }
    else
    {
            $categorie = $db->getListaCategorie();
            $lista_categorie = '';

            if($categorie != null) {

                // Per ogni categoria aggiunge un elemento alla lista
                foreach($categorie as $categoria){

                    $cat = file_get_contents('includes/categorie_list_element.html');
                    $cat = str_replace("<categoria_element />", $categoria['nome_categoria'], $cat);
                    $cat = str_replace("<id_cat />", $categoria['id'], $cat);
                    $cat = str_replace("<tab />", $counter, $cat);
                    $lista_categorie .= $cat;
                    $counter++;
                     }

         }

         $content = str_replace("<categorie />", empty($lista_categorie)?"<li/>":$lista_categorie, $content);

        $content = str_replace("<cercato/>", "", $content);
    }

}

// Se è settato un id mostra la lista delle opere filtrate per la categoria passata in GET
else {

    
    $content = file_get_contents('includes/opere_list.html');
    $link = file_get_contents('includes/link.html');
    $link = str_replace("<path />", "categorie.php", $link);
    $link = str_replace("<nome_link />", "Categorie", $link);
    $link = str_replace("<tab />", "1", $link);
    
    $nome_categoria = $db->getCategoriaName($_GET['id']);
    $title = $nome_categoria['nome_categoria'] . " - Share Arts";
    $page_head = str_replace("<titolo />", $title, $page_head);
    $page_head = str_replace("<page_description/>", "Panoramica delle opere appartenenti alla categoria " . $nome_categoria['nome_categoria'], $page_head);
    $page_head = str_replace("<keywords/>", "arte, opera, panoramica, esplorare, categoria, " . $nome_categoria['nome_categoria'] . ", immagine", $page_head);
    $page_head = str_replace("<metatitle/>", $title, $page_head);
    $page_body = str_replace("<breadcrumb />", $link . " > " . $nome_categoria['nome_categoria'], $page_body);
    $content = str_replace("<section_name />", $nome_categoria['nome_categoria'], $content);

    $page_body = str_replace("<tab1 />", "2", $page_body);
    $page_body = str_replace("<tab2 />", "3", $page_body);
    $page_body = str_replace("<tab3 />", "4", $page_body);
    $page_body = str_replace("<tab4 />", "5", $page_body);

    if(isset($_SESSION['user_id'])) {
        if ($admin) {
            $admin_button = file_get_contents('includes/usr_zone_admin.html');
            $admin_button = str_replace("<tab1 />", "6", $admin_button);
            $admin_button = str_replace("<tab2 />", "7", $admin_button);
            $page_body = str_replace("<utente />",  $admin_button, $page_body);
            $counter = 8+2;
        }
        else {
            $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
            $profile_button = str_replace("<tab1 />", "6", $profile_button);
            $profile_button = str_replace("<tab2 />", "7", $profile_button);
            $page_body = str_replace("<utente />", $profile_button, $page_body);
            $counter = 8+2;
        }	
    }

    else {
    $login_button = str_replace("<tab />", "6", $login_button);
    $page_body = str_replace("<utente />",$login_button, $page_body);			
    $counter = 7+3;			
    }

    $opere = $db->getOpereByCategoria($_GET['id']);
    $lista_opere = '';

    if($opere != null) {

        foreach($opere as $opera){

            $op = file_get_contents('includes/opere_card.html');
            $op = str_replace("<id_opera/>", $opera['id'], $op);
            $op = str_replace("<from/>", "cat", $op);
            $op = str_replace("<Path/>", $opera['img_path'], $op);
            $op = str_replace("<Titolo/>", $opera['titolo'], $op);
            $op = str_replace("<descrizione/>", $opera['descrizione_short'], $op);
            $op = str_replace("<Nomeutente/>", $opera['username'], $op);
            $op = str_replace("<Categoria/>", "", $op);
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