<?php
/**
* Richiede che la sessione sia attiva e ha come imput un oggetto di
* tipo DB per relazionarsi con il database.
*
* Restituisce lo snippet di codice HTML del div con id="info_utente" se c'Ã¨ un
* utente loggato altrimenti restituisce ''.
*/
function createInfoUtente($db) {
  

  if (isset($_SESSION['user_id']) === true) {
	$info_utente = file_get_contents('includes/info_utente.html');
    $usr = $db->getProfilo($_SESSION['user_id']);

    $result = str_replace('</id_user>', $usr['id'], $info_utente);
	$result = str_replace('</nome_cognome>', $usr['nome'] . " " . $usr['cognome'], $result);
	$result = str_replace('</path>', $usr['img_path'], $result);
	
  }

  else{
    $result = file_get_contents('includes/pulsante_accedi.html');
  }

  return $result;
}
?>
