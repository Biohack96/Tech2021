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
        header('Location: Home.php');
    }
    else
    $content .=  '<span class="error">Credenziali non valide</span>';
}
// Titolo della pagina
$title = 'Share Arts';

// Include i file html
$page_head = file_get_contents('includes/head.html');
$page_body = file_get_contents('includes/body.html'); 
$content  .= file_get_contents('includes/login.html'); //login
$content  .= file_get_contents('includes/registrazione.html'); //registrazione

$page_head = str_replace("<titolo />", $title, $page_head);
$page_head = str_replace("<scripts />", "", $page_head);

$page_body = str_replace("<breadcrumb />", "", $page_body);  	// da aggiungere
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


$page_body = str_replace("<content />", $content, $page_body);
$page_body = str_replace("<nome />","", $page_body);
$page_body = str_replace("<bio />", "", $page_body);

// Disattiva link circolare
$page_body = str_replace('<li><a href="login.php">Accedi o Registrati</a></li>', '<li><span>Accedi o Registrati</span></li>', $page_body);		// da aggiungere dinamicamente


echo $page_head  . $page_body  ;
?>