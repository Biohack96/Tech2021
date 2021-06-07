<?php
/**
* Richiede che la sessione sia attiva e ha come imput un oggetto di
* tipo DB per relazionarsi con il database.
*
* Restituisce lo snippet di codice HTML del div con id="info_utente" se c'Ã¨ un
* utente loggato altrimenti restituisce ''.
*/
function createInfoUtente($db) {
  $result = '';

  if (isset($_SESSION['user_id']) === true) {
    $usr = $db->getProfilo($_SESSION['user_id']);

    $result = '<div id="name_log">
    <p id="utente"><a href="profilo.php?id='. $_SESSION['user_id'] .'">' . $usr['nome'] . ' ' . $usr['cognome'] . '</a></p>	
    <p id="logout"><a href="">Logout</a></p>
  </div>
  <a href=""><img src="' . $usr['img_path'] . '" alt="foto del profilo utente" /></a>
';
  }

  return $result;
}
?>
