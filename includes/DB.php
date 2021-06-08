<?php
class DB extends mysqli{


	//public function __construct($host="localhost:8889", $user="root", $pass="root", $db="db")
	//public function __construct($host="localhost", $user="dbrescia", $pass="kainoolay9ojaeQu", $db="db")
	public function __construct($host="localhost", $user="root", $pass="", $db="cyh2")
	{
        parent::__construct($host, $user, $pass, $db);

		if (mysqli_connect_error()) {
           	 die();
        	}
	}


	public function getProfilo($id = NULL)
	{
		$sql = "SELECT id,email,nome,cognome,telefono, DATE_FORMAT(datanascita,'%d/%m/%Y') as datanascita,cf,img_path FROM `utente` WHERE id=?";
		$query = $this->prepare($sql);
		$query->bind_param("i", $id);
		$query->execute();
		$result = $query->get_result();

		/*preparo la query, la eseguo e ottengo i risultati*/

		if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		$usr = $result->fetch_assoc(); /*traformo il risultato della query in un array associativo*/

		/*foreach($usr as $key => $value)
		{echo "\n".$key."  ".$usr["$key"];} */ /*ciclo per il debug*/

		$query->close();
		$result->free();

		return $usr;

    }

	public function getCategoria($id = NULL)
	{
		$sql = "SELECT nome FROM categoria JOIN possiede ON categoria.id = possiede.id_categoria WHERE id_utente=?";
		$query = $this->prepare($sql);
		$query->bind_param("i", $id);
		$query->execute();
		$result = $query->get_result();

		/*preparo la query, la eseguo e ottengo i risultati*/

		if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		$cat = array();
		/*foreach($usr as $key => $value)
		{echo "\n".$key."  ".$usr["$key"];} */ /*ciclo per il debug*/
		while ($row = $result->fetch_assoc())
			{
				$cat[] = $row;
			}
		
		$query->close();
		$result->free();

		return $cat;

    }

	public function getRecensioni($id = NULL)
	{
		$sql = "SELECT descrizione, voto, nome, cognome  FROM recensione JOIN utente ON recensione.id_autore = utente.id WHERE id_utente=? ORDER BY recensione.id";
		$query = $this->prepare($sql);
		$query->bind_param("i", $id);
		$query->execute();
		$result = $query->get_result();

		/*preparo la query, la eseguo e ottengo i risultati*/

		if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		$rec = array();
		/*foreach($usr as $key => $value)
		{echo "\n".$key."  ".$usr["$key"];} */ /*ciclo per il debug*/
		while ($row = $result->fetch_assoc())
			{
				$rec[] = $row;
			}
		
		$query->close();
		$result->free();

		return $rec;

    }
 
    public function setRecensione($descrizione, $voto, $id_autore, $id_utente){
        
            $sql = "INSERT INTO recensione VALUES (NULL,?,?,?,?);";
            
            $query = $this->prepare($sql);
            $query->bind_param("siii",$descrizione, $voto, $id_autore, $id_utente);
        
         if($query->execute())
		{
			$res = $this->affected_rows;
			$query->close();
			return (bool)$res;
		}
        else return false;
            
    }
    
	public function getMedia($id_utente = NULL){

		$sql = "SELECT FORMAT(AVG(voto), 1) AS media FROM recensione WHERE id_utente = ?";

		$query = $this->prepare($sql);
        $query->bind_param("i", $id_utente);
		$query->execute();
        $result = $query->get_result();

		$media = $result->fetch_assoc();

		$query->close();
		$result->free();

		return $media;
	}


	public function login($username, $password)
	{
		

		$sql = "SELECT id FROM `utente` WHERE email = ? AND password = ? LIMIT 1;";
		$query = $this->prepare($sql);
		$query->bind_param("ss", $username,$password);
		if(!$query->execute()) {return NULL;}
		$result = $query->get_result();

		if($result->num_rows === 0) return FALSE;

		$row = $result->fetch_assoc();

		$query->close();
		$result->free();

		$_SESSION['user_id'] = $row['id'];
		return TRUE;

	}

	
}
?>
