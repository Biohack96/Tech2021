<?php
session_start();
require_once('includes/DB.php');
// require_once('includes/create_info_utente.php');

$content = "";
// Oggetto di accesso al database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new DB();

    if($db->login($_POST['username'],$_POST['password']))
    {
        $admin = false;

        if(isset($_SESSION['user_id'])) {
        $auth = $db->getAutoreById($_SESSION['user_id']);
            if ($auth['isAdmin']) {
                $admin = true;
            }
        }
        if ($admin==true) {
            header('Location: admin.php');
        }
        else {
            header('Location: autori.php?id=' . $_SESSION['user_id']);
        }
    }
    else
    $content .=  '<span class="error">Credenziali non valide</span>';
}
// Titolo della pagina
$title = 'Share Arts';

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_head = str_replace("<page_description/>", "Login o registrazione al sito Share Arts", $page_head);
$page_head = str_replace("<keywords/>", "arte, opera, accesso, login, registrazione, autore, utente, informazioni", $page_head);
$page_head = str_replace("<metatitle/>", $title, $page_head);

$page_body = file_get_contents('includes/body.html'); 
$content  .= file_get_contents('includes/login.html'); //login
$content  .= file_get_contents('includes/registrazione.html'); //registrazione

$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);

$page_body = str_replace("<breadcrumb />", "Accedi o Registrati", $page_body);  	// da aggiungere
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

$page_body = str_replace("<tab1 />", "1", $page_body);
$page_body = str_replace("<tab2 />", "2", $page_body);
$page_body = str_replace("<tab3 />", "3", $page_body);
$page_body = str_replace("<tab4 />", "4", $page_body);

$content = str_replace("<tab1 />", "8", $content);
$content = str_replace("<tab2 />", "9", $content);
$content = str_replace("<tab3 />", "10", $content);
$content = str_replace("<tab4 />", "11", $content);
$content = str_replace("<tab5 />", "12", $content);

$page_body = str_replace("<content />", $content, $page_body);
$page_body = str_replace("<nome />","", $page_body);
$page_body = str_replace("<bio />", "", $page_body);

// Disattiva link circolare
$page_body = str_replace('<li id="loggati"><a href="login.php" tabindex="<tab />">Accedi o Registrati</a></li>', '<li><span>Accedi o Registrati</span></li>', $page_body);		// da aggiungere dinamicamente


echo $page_head  . $page_body  ;
?>