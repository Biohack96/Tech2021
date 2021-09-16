<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

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

$page_head = str_replace("<scripts />", "", $page_head);

/////gestione login/logout
$login_button = file_get_contents('includes/login_button.html');
$profile_button = file_get_contents('includes/usr_zone_logged.html');

if(isset($_SESSION['user_id']))
{
    if ($admin == true) {
        $admin_button = file_get_contents('includes/usr_zone_admin.html');
        $admin_button = str_replace("<tab1 />", "4", $admin_button);
        $admin_button = str_replace("<tab2 />", "5", $admin_button);
        $page_body = str_replace("<utente />",  $admin_button, $page_body);
    }
    else {
        
        $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
        $profile_button = str_replace("<tab1 />", "4", $profile_button);
        $profile_button = str_replace("<tab2 />", "5", $profile_button);
        if(!isset($_GET['id']) || (isset($_GET['id']) && $_SESSION['user_id'] != $_GET['id'])) //lasciamo stare va
        {$page_body = str_replace("<utente />", $profile_button, $page_body);}			
    }
}
else
{
    $login_button = str_replace("<tab />", "4", $login_button);
    $page_body = str_replace("<utente />",$login_button, $page_body);			
}

$counter = 2;
////

if (!isset($_GET['id'])){

    $title = 'Autori - Share Arts';
    $page_head = str_replace("<titolo />", $title, $page_head);

    $page_head = str_replace("<page_description/>", "Elenco degli autori presenti nel sito", $page_head);
    $page_head = str_replace("<keywords/>", "arte, opera, selezione, esplorare, autori", $page_head);
    $page_head = str_replace("<metatitle/>", $title, $page_head);

    $page_body = str_replace("<tab1 />", $counter++, $page_body);
    $page_body = str_replace("<tab2 />", $counter++, $page_body);
    $page_body = str_replace("<tab3 />", $counter++, $page_body);
    $content = file_get_contents('includes/autori_list.html');

    if(isset($_GET['trova_autore']) && !empty($_GET['autore']))
    {
        $link = file_get_contents('includes/link.html');
        $link = str_replace("<path />", "autori.php", $link);
        $link = str_replace("<nome_link />", "Autori", $link);
        $link = str_replace("<tab />", "1", $link);
        $page_body = str_replace("<breadcrumb />", $link . " > Ricerca: " . $_GET['autore'], $page_body);
        $page_body = str_replace("<tab4 />", $counter++, $page_body);

        if(isset($_SESSION['user_id'])) {
            $auth = $db->getAutoreById($_SESSION['user_id']);
            if ($auth['isAdmin']) {
                $admin_button = file_get_contents('includes/usr_zone_admin.html');
                $admin_button = str_replace("<tab1 />", $counter++, $admin_button);
                $admin_button = str_replace("<tab2 />", $counter++, $admin_button);
                $page_body = str_replace("<utente />",  $admin_button, $page_body);
            }
            else {
                $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
                $profile_button = str_replace("<tab1 />", $counter++, $profile_button);
                $profile_button = str_replace("<tab2 />", $counter++, $profile_button);
                $page_body = str_replace("<utente />",  $profile_button, $page_body);
            }
        }
        else {
            $login_button = str_replace("<tab />", $counter++, $login_button);
            $page_body = str_replace("<utente />",$login_button, $page_body);
        }
    
        
        if (isset($_SESSION['user_id'])) {
            $autori = $db->getAutoriS($_GET['autore'],$_SESSION['user_id']);
            
        }

        else {
            $autori = $db->getAutoriS($_GET['autore']);
        }   
        if (empty($autori)) {
            $no_result = file_get_contents('includes/ricerca_autori_no_results.html');
            $no_result = str_replace("<tab_nores />", "12", $no_result);
            $content.= $no_result;
        }
    }
    else
    {
    $page_body = str_replace('<li><a href="autori.php" tabindex="<tab4 />">Autori</a></li>', '<li>Autori</li>', $page_body);
        if (isset($_SESSION['user_id'])) {
            $autori = $db->getAutoriLogged($_SESSION['user_id']);
        
        }

        else {
            $autori = $db->getAutori();
        }
    }
    

    if(isset($_GET['trova_autore']) && !empty($_GET['autore']))
    {
        $content = str_replace("<cercato/>", " risultati per ".htmlentities($_GET['autore']), $content);
    }
    else
    {
        $content = str_replace("<cercato/>", "", $content);
    }

    $page_body = str_replace("<breadcrumb />", "Autori", $page_body);


    $lista_autori = '';

    if($autori != null) {
        $counter = 12;
        // Per ogni autore aggiunge un elemento alla lista
        foreach($autori as $autore){

            $aut = file_get_contents('includes/nome_autore_inlist.html');
            $aut = str_replace("<username />", $autore['username'], $aut);
            $aut = str_replace("<id_aut />", $autore['id'], $aut);
            $aut = str_replace("<tab />", $counter, $aut);
            $lista_autori .= $aut;
            $counter++;
    }

    $content = str_replace("<authors/>", $lista_autori, $content);
    }
    else
    {
    $content = str_replace("<authors/>", "<li/>", $content);
    }
}

else {
    $a = $db->getAutoreById($_GET['id']);

    $title = $a['username'] . " - Share Arts";
    $page_head = str_replace("<titolo />", $title, $page_head);

    $page_head = str_replace("<page_description/>", "Profilo e opere di " . $a['username'], $page_head);
    $page_head = str_replace("<keywords/>", "arte, autore, immagine, opera, condividere, " . $a['username'], $page_head);
    $page_head = str_replace("<metatitle/>", $title, $page_head);

    $content = file_get_contents('includes/content_pagina_autore.html');
    

    $content = str_replace("<username />", $a['username'], $content);
    $content = str_replace("<informazioni />", $a['bio'], $content);
    $link = file_get_contents('includes/link.html');
    $link = str_replace("<path />", "autori.php", $link);
    $link = str_replace("<nome_link />", "Autori", $link);
    $link = str_replace("<tab />", "1", $link);

    if (isset($_SESSION['user_id'])) {
        $auth = $db->getAutoreById($_SESSION['user_id']);
    }
        if(isset ($_SESSION['user_id']) && ($_SESSION['user_id'] == $_GET['id'])) { //sono io
            $page_body = str_replace("<tab1 />", "1", $page_body);
            $page_body = str_replace("<tab2 />", "2", $page_body);
            $page_body = str_replace("<tab3 />", "3", $page_body);
            $page_body = str_replace("<tab4 />", "4", $page_body);

            $page_body = str_replace("<breadcrumb />", "La tua pagina", $page_body);
            //var_dump($profile_button);
            $profile_button = str_replace('<a id="nome_utente" href="autori.php?id='.$_SESSION['user_id'].'" tabindex="4">La tua pagina</a>', 'La tua pagina', $profile_button);
            //var_dump($page_body);
            $profile_button = str_replace("<tab2 />", "5", $profile_button);
            $page_body = str_replace("<utente />", $profile_button, $page_body);
            
            $button_aggiungi_opera = file_get_contents('includes/button_aggiungi_opera.html');
            $button_aggiungi_opera = str_replace("<tab />", "6", $button_aggiungi_opera);
            $content = str_replace("<button_aggiungi_opera />", $button_aggiungi_opera, $content);

             
            $button_modifica_profilo = file_get_contents('includes/button_modifica_profilo.html');
            $button_modifica_profilo = str_replace("<tab />", "7", $button_modifica_profilo);
            $content = str_replace("<button_modifica_profilo />", $button_modifica_profilo, $content);

            $button_elimina_profilo = file_get_contents('includes/button_elimina_profilo.html');
            $button_elimina_profilo = str_replace("<tab />", "8", $button_elimina_profilo);
            $button_elimina_profilo = str_replace("<id_aut />", $_GET['id'], $button_elimina_profilo);
            $content = str_replace("<button_elimina_profilo />", $button_elimina_profilo, $content);
            
           
            
            $counter = 8+2;
            
        }
        else {
            $content = str_replace("<button_aggiungi_opera />", "", $content);
            $content = str_replace("<button_modifica_profilo />", "", $content);
            $page_body = str_replace("<breadcrumb />", $link . " > " . $a['username'], $page_body);
            $page_body = str_replace("<tab1 />", "2", $page_body);
            $page_body = str_replace("<tab2 />", "3", $page_body);
            $page_body = str_replace("<tab3 />", "4", $page_body);
            $page_body = str_replace("<tab4 />", "5", $page_body);
            $counter = 6+4;

            if(isset ($_SESSION['user_id'])) {
                if ($auth['isAdmin']) {
                    $admin_button = file_get_contents('includes/usr_zone_admin.html');
                    $admin_button = str_replace("<tab1 />", $counter++, $admin_button);
                    $admin_button = str_replace("<tab2 />", $counter++, $admin_button);
                    $page_body = str_replace("<utente />",  $admin_button, $page_body);
                }
                
                else {
                    $profile_button = str_replace("<id_aut />", $_SESSION['user_id'], $profile_button);
                    $profile_button = str_replace("<tab1 />", $counter++, $profile_button);
                    $profile_button = str_replace("<tab2 />", $counter++, $profile_button);
                    $page_body = str_replace("<utente />",  $profile_button, $page_body);
                }
            }
            
            else {
                $login_button = str_replace("<tab />", "6", $login_button);
                $page_body = str_replace("<utente />",$login_button, $page_body);
                $counter = 7+3;
            }

            if (isset ($_SESSION['user_id']) && $auth['isAdmin']) {
                $button_elimina_profilo = file_get_contents('includes/button_elimina_profilo.html');
                $button_elimina_profilo = str_replace("<tab />", $counter++, $button_elimina_profilo);
                $button_elimina_profilo = str_replace("<id_aut />", $_GET['id'], $button_elimina_profilo);
                $content = str_replace("<button_elimina_profilo />", $button_elimina_profilo, $content);

                if ($a['segnalato'] == true) {

                } 
            }
            else {
                $content = str_replace("<button_elimina_profilo />", "", $content);
            }

        }

        if ($a['segnalato'] == true) {
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']) {
                $content = str_replace("<button_segnala_profilo />", "<p class=\"avviso_segnalata\">Il tuo profilo è stato segnalato ed è in attesa di revisione da parte degli amministratori, pertanto al momento non è visibile al pubblico.</p>", $content);
            }
            else {
                $content = str_replace("<button_segnala_profilo />", "<p class=\"avviso_segnalata\">Questo profilo è stato segnalato ed è in attesa di revisione da parte degli amministratori, pertanto al momento non è visibile al pubblico.</p>", $content);
            }
        }

        else {
            if (!isset($_SESSION['user_id']) || ($_SESSION['user_id'] != $_GET['id'] && $admin==false)){
                $button_segnala = file_get_contents('includes/button_segnala_profilo.html');
                $button_segnala = str_replace("<id_aut />", $_GET['id'], $button_segnala);
                $button_segnala = str_replace("<tab />", $counter++, $button_segnala);
                $content = str_replace("<button_segnala_profilo />", $button_segnala, $content);
            }
            else {
                $content = str_replace("<button_segnala_profilo />", "", $content);
            }
        }

        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']) {
            $opere = $db->getMyOpere($_GET['id']);
        }
        else {
            $opere = $db->getOpereByAuthor($_GET['id']);
        }
        $opere_content = file_get_contents('includes/opere_list.html');
        $opere_content = str_replace('<h2 class="titolo_sezione"><section_name /></h2>', "", $opere_content);
        $opere_content = str_replace("<cerca_opere />", "", $opere_content);
        $opere_content = str_replace("<section_description />", "", $opere_content);


    $lista_opere = '';

    if($opere != null) {

        foreach($opere as $opera){

            $op = file_get_contents('includes/opere_card.html');
            $op = str_replace("<id_opera/>", $opera['id'], $op);
            $op = str_replace("<from/>", "autore", $op);
            $op = str_replace("<Path/>", $opera['img_path'], $op);
            $op = str_replace("<Titolo/>", $opera['titolo'], $op);
            $op = str_replace("<descrizione/>", $opera['descrizione_short'], $op);
            $op = str_replace("<Nomeutente/>", "", $op);
            $op = str_replace("<Categoria/>", $opera['nome_categoria'], $op);
            $op = str_replace('<tabindex/>', $counter, $op);
            $lista_opere .= $op;
            $counter++;
    }

    $opere_content = str_replace("<opere/>", $lista_opere, $opere_content);
    }

    else {
        $opere_content = str_replace("<opere/>", "<p class=\"no_results\">Nessuna opera</p>", $opere_content);
    }

    $content = str_replace("<opere />", $opere_content, $content);
}


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>