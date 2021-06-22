<?php
/**
* Richiede che la sessione sia attiva e ha come imput un oggetto di
* tipo DB per relazionarsi con il database.
*
*/
function createRecensioni($db, $id) {
  $lista_recensioni = '';
  
  $recensioni = $db->getRecensioni($id);

  foreach($recensioni as $recensione) {
    $recensioni = file_get_contents("includes/recensioni.html");
  
    if ($recensione['id_autore'] == $_SESSION['user_id']){
        
        $e = file_get_contents("includes/elimina_recensione_button.html");
        $e = str_replace("<id_recensione />", $recensione['id'] , $e);
        $recensioni = str_replace("<elimina_button />", $e , $recensioni);
    }

    $recensioni = str_replace("<autore />", $recensione['nome'] . " " . $recensione['cognome'] , $recensioni);
    $recensioni = str_replace("<date_recensione />", $recensione['data_recensione'] , $recensioni);
  
   if($recensione['voto'] < 2){
    $recensioni = str_replace("<img_voto />", "img/Star_rating_1_of_5.png", $recensioni);
  }
  
   elseif($recensione['voto'] >= 2 && $recensione['voto'] < 3){
      $recensioni = str_replace("<img_voto />", "img/Star_rating_2_of_5.png", $recensioni);
   }
  
   elseif($recensione['voto'] >= 3 && $recensione['voto'] < 4){
    $recensioni = str_replace("<img_voto />", "img/Star_rating_3_of_5.png", $recensioni);
  }
  
   elseif($recensione['voto'] >= 4 && $recensione['voto'] < 5){
    $recensioni = str_replace("<img_voto />", "img/Star_rating_4_of_5.png", $recensioni);
  }
  
   elseif($recensione['voto'] == 5){
    $recensioni = str_replace("<img_voto />", "img/Star_rating_5_of_5.png", $recensioni);
  }
  
   $recensioni = str_replace("<descrizione />", $recensione['descrizione'], $recensioni);
  
   $lista_recensioni .= $recensioni;
  }

  return $lista_recensioni;
}
?>
