<?php
class DB extends mysqli{


	private $imgDir = 'img/upload/';
	private $max_img_size = 3000000; // 3MB
	private $perm_img_format = array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG);


	//public function __construct($host="localhost:8889", $user="root", $pass="root", $db="db")
	//public function __construct($host="localhost", $user="dbrescia", $pass="kainoolay9ojaeQu", $db="db")
	public function __construct($host="localhost", $user="root", $pass="", $db="workeradvisor")
	{
        parent::__construct($host, $user, $pass, $db);

		if (mysqli_connect_error()) {
           	 die();
        	}
	}
	
	
	public function getcards($limit = 1,$offset=0)
	{
		$cards = array();

		$sql = "SELECT FORMAT(AVG(voto), 1) as voto,nome,cognome,titolostudio,img_path FROM utente u join recensione r on u.id = r.id_utente group by u.id limit $limit offset $offset";
		$query = $this->prepare($sql);
		$query->execute();
		$result = $query->get_result();

		/*preparo la query, la eseguo e ottengo i risultati*/

		if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		while ($row = $result->fetch_assoc())
			{
					$cards[] = $row;
			}
		
		$query->close();
		$result->free();

		return $cards;

    }

	public function getProfilo($id = NULL)
	{
		$sql = "SELECT id,email,nome,cognome,telefono, DATE_FORMAT(datanascita,'%d/%m/%Y') as datanascita,cf,titolostudio,bio,img_path FROM `utente` WHERE id=?";
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

	public function setProfilo($email, $password, $nome, $cognome, $telefono, $datanascita, $cf, $titolostudio, $bio, $img)
{

	$hashed_pass = hash('sha256', $password);

	if(!empty($img))
		{
			$img_format = exif_imagetype($img);
			if(!in_array($img_format , $this->perm_img_format)) {$error[] = 'Formato immagine errato, inserire un immagine in formato PNG o JPEG';} 	 // verifica se è un immagine
			if (filesize($img) > $this->max_img_size) {$error[] = 'Immagine troppo grande (max: 3MB)';}
			$hash = hash_file('sha256', $img);
			if (!move_uploaded_file($img, $this->imgDir.$hash)) {$error[] = "Impossibile spostare l'immagine";}
			$img_path = $this->imgDir.$hash;
			//$this->crop($img_path,1);
		}

	if(!empty($img_path)) 
		{

		$sql = "INSERT INTO utente (email,password,nome,cognome,telefono,datanascita,cf,titolostudio,bio,img_path) VALUES (?,?,?,?,?,?,?,?,?,?)";

				$query = $this->prepare($sql);
				$query->bind_param("ssssssssss", $email, $hashed_pass, $nome, $cognome, $telefono, $datanascita, $cf, $titolostudio, $bio, $img_path);

				if($query->execute())
				{
					$new_id = $this->insert_id;
					$_SESSION['user_id']= $new_id;
					$query->close();
					return $new_id;
				}
				else {return NULL;}
		}
	else {

		$sql = "INSERT INTO utente (email,password,nome,cognome,telefono,datanascita,cf,titolostudio,bio) VALUES (?,?,?,?,?,?,?,?,?)";

				$query = $this->prepare($sql);
				$query->bind_param("sssssssss", $email, $hashed_pass, $nome, $cognome, $telefono, $datanascita, $cf, $titolostudio, $bio);

				if($query->execute())
				{
					$new_id = $this->insert_id;
					$_SESSION['user_id']= $new_id;
					$query->close();
					return $new_id;
				}
				else {return NULL;}

	}

	}

	public function getRecensioni($id = NULL)
	{
		$sql = "SELECT recensione.id, descrizione, voto, DATE_FORMAT(data_recensione, '%d/%m/%Y') AS data_recensione, nome, cognome, id_autore  FROM recensione JOIN utente ON recensione.id_autore = utente.id WHERE id_utente=? ORDER BY recensione.id";
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
 
    public function setRecensione($descrizione, $voto, $data_recensione, $id_autore, $id_utente){
        
            $sql = "INSERT INTO recensione VALUES (NULL,?,?,?,?,?);";
            
            $query = $this->prepare($sql);
            $query->bind_param("sisii",$descrizione, $voto, $data_recensione, $id_autore, $id_utente);
        
         if($query->execute())
		{
			$res = $this->affected_rows;
			$query->close();
			return (bool)$res;
		}
        else return false;
            
    }

	public function getRecensione($id = NULL)
	{
		$sql = "SELECT id, descrizione, voto, DATE_FORMAT(data_recensione, '%d/%m/%Y') AS data_recensione, id_autore, id_utente  FROM recensione WHERE id=?";
		$query = $this->prepare($sql);
		$query->bind_param("i", $id);
		$query->execute();
		$result = $query->get_result();

		/*preparo la query, la eseguo e ottengo i risultati*/

		if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		$rec = $result->fetch_assoc(); /*traformo il risultato della query in un array associativo*/

		/*foreach($usr as $key => $value)
		{echo "\n".$key."  ".$usr["$key"];} */ /*ciclo per il debug*/

		$query->close();
		$result->free();

		return $rec;

    }

	public function deleteRecensione($id = NULL, $id_autore = NULL){

		$sql = "DELETE FROM recensione WHERE id = ? AND id_autore = ? ";
		$query = $this->prepare($sql);
		$query->bind_param("ii", $id, $id_autore);

		if($query->execute())
		{
			$res=$this->affected_rows;
			$query->close();
			return (bool)$res;
		}

		return NULL;

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
		$hashed_pass = hash('sha256', $password);

		$sql = "SELECT id FROM `utente` WHERE email = ? AND password = ? LIMIT 1;";
		$query = $this->prepare($sql);
		$query->bind_param("ss", $username,$hashed_pass);
		if(!$query->execute()) {return NULL;}
		$result = $query->get_result();

		if($result->num_rows === 0) return FALSE;

		$row = $result->fetch_assoc();

		$query->close();
		$result->free();

		$_SESSION['user_id'] = $row['id'];
		return TRUE;

	}

	public function logout()
	{
		unset($_SESSION['user_id']);
	}
	
}
?>
