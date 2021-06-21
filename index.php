<?php
session_start();
require_once('includes/DB.php');
require_once('includes/create_info_utente.php');

// Oggetto di accesso al database
$db = new DB();

// Titolo della pagina
$title = 'Dashboard';


// Contengono l'HTML dei tag <head> e <body> che verranno stampati
$page_head = file_get_contents('includes/head.html');
$ricerca = file_get_contents('includes/ricerca.html');
$page_body = file_get_contents('includes/body.html');
$card_t = file_get_contents('includes/card.html');
$card_list_t = file_get_contents('includes/cardlist.html');

// Contiene lo snippet di codice per visualizzare l'utente loggato in alto a destra

$info_utente = createInfoUtente($db);
$page_body = str_replace('<info_utente />', $info_utente, $page_body);



$cards = "";
$cardlist="";

$cards_data  = $db->getcards(2); //da scegliere


foreach($cards_data as $key => $val){ //array di array 

		$temp = str_replace('<NomeCognome/>', ($val['nome']." ".$val['cognome']), $card_t);
		//$temp = str_replace('<Luogo/>', $val['luogo'], $temp);	
		//$temp = str_replace('<Professione/>', $val['titolostudio'], $temp);	TODO
		$temp = str_replace('<Path/>', "".$val['img_path'], $temp);		
		$temp = str_replace('<Voto/>', $val['voto'], $temp);	
		$cards .= $temp;
	
			
}

//var_dump($cards);

$cardlist = str_replace('<cards/>', $cards, $card_list_t);	

//var_dump($cardlist);

$content = $ricerca . $cardlist;

//var_dump($content);

$page_body = str_replace('<content />', $content, $page_body);

echo $page_head  . $page_body  ;
?>