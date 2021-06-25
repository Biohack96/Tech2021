<?php
session_start();
require_once('includes/DB.php');
require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();

// Titolo della pagina
$title = 'WorkerAdvisor';


// Contengono l'HTML dei tag <head> e <body> che verranno stampati
$page_head = file_get_contents('includes/head.html');
$ricerca = file_get_contents('includes/ricerca.html');
$page_body = file_get_contents('includes/body.html');
$card_t = file_get_contents('includes/card.html');
$card_list_t = file_get_contents('includes/cardlist.html');

// Contiene lo snippet di codice per visualizzare l'utente loggato in alto a destra

$page_head = str_replace('<keyword/>', "lavoro, recensione, privato, professione", $page_head);
$page_head = str_replace('<metatitle/>', "WorkerAdvisor trova il lavoratore che fa per te", $page_head);

$info_utente = createInfoUtente($db);
$page_body = str_replace('<info_utente />', $info_utente, $page_body);



$cards = "";
$cardlist="";

if(!empty($_GET['nome']) || !empty($_GET['luogo']) || !empty($_GET['professione']))
{

	$title = 'Ricerca';

	$nome= !empty($_GET['nome'])?$_GET['nome']:null;
	$luogo= !empty($_GET['luogo'])?$_GET['luogo']:null;
	$professione= !empty($_GET['professione'])?$_GET['professione']:null;
	
	$ricerca = str_replace('<nomecercato/>',$_GET['nome'], $ricerca);
	$ricerca = str_replace('<luogocercato/>',$_GET['luogo'], $ricerca);
	$ricerca = str_replace('<lavorocercato/>',$_GET['professione'], $ricerca);
	
	$risultato = file_get_contents('includes/ris_ricerca.html');
	
	$input = (!empty($_GET['nome'])?"NOME/COGNOME= ".$_GET['nome']:"") . (!empty($_GET['luogo'])?" LUOGO= ".$_GET['luogo']:"") . (!empty($_GET['professione'])?" PROFESSIONE= ".$_GET['professione']:"");
	
	$ricerca = $ricerca . str_replace('<inputs/>',$input, $risultato);
		
	$cards_data  = $db->getcardsR($nome,$luogo,$professione);
}
else	{
	
	$ricerca = str_replace('<nomecercato/>',"", $ricerca);
	$ricerca = str_replace('<luogocercato/>',"", $ricerca);
	$ricerca = str_replace('<lavorocercato/>',"", $ricerca);

	$cards_data  = $db->getcards(); //da scegliere

}

$counter = 20;

if(!empty($cards_data))
{
	foreach($cards_data as $key => $val){ //array di array 

			$temp = str_replace('<NomeCognome/>', ($val['nome']." ".$val['cognome']), $card_t);
			$temp = str_replace('<Luogo/>', $val['luogo'], $temp);	
			$temp = str_replace('<Professione/>', $val['professione'], $temp);	
			$temp = str_replace('<Path/>', "".$val['img_path'], $temp);		
			$temp = str_replace('<Professione/>', $val['professione'], $temp);
			$temp = str_replace('<Voto/>', $val['voto'], $temp);
			$temp = str_replace('</id_profilo>', $val['id'], $temp);
			$temp = str_replace('<tabindex/>', $counter, $temp);
			$cards .= $temp;
			$counter++;
		
				
	}
}
//var_dump($cards);

$cardlist = str_replace('<cards/>', $cards, $card_list_t);	

//var_dump($cardlist);

$content = $ricerca . $cardlist;

//var_dump($content);

$page_head = str_replace('<titolo />', $title, $page_head);
$page_head = str_replace('<scripts />', '', $page_head);


$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>