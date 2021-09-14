<?php
class DB extends mysqli{

	private $imgDir = 'img/upload/';
	private $max_img_size = 3000000; // 3MB
	private $perm_img_format = array(IMAGETYPE_GIF , IMAGETYPE_JPEG , IMAGETYPE_PNG);
	private $yearPattern = '/^[0-9]{1,4}$/';
	private $passPattern = '/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/' ; // Almeno 8 caratteri con almeno una maiuscola e un numero


	public function __construct($host="localhost", $user="root", $pass="", $db="sharearts")
	{
        parent::__construct($host, $user, $pass, $db);

		if (mysqli_connect_error()) {
           	 die();
        	}
	}

    public function getListaCategorie()
	{
		$sql = "SELECT nome_categoria, id FROM categoria  ORDER BY nome_categoria";
		$query = $this->prepare($sql);
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

	public function getAllOpere()
	{
		$sql = "SELECT titolo, img_path, o.id, username, descrizione_short, nome_categoria FROM (opera o JOIN autore a ON o.id_autore=a.id) JOIN categoria c ON o.id_categoria=c.id";
		$query = $this->prepare($sql);
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


    public function getOpereByCategoria($categoria)
	{
		$sql = "SELECT titolo, img_path, o.id, username, descrizione_short FROM opera o JOIN autore a ON o.id_autore=a.id WHERE o.id_categoria=?";
		$query = $this->prepare($sql);
        $query->bind_param("i", $categoria);
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

	public function getOpereByAuthor($autore)
	{
		$sql = "SELECT titolo, img_path, o.id, descrizione_short, nome_categoria FROM opera o JOIN categoria c ON o.id_categoria=c.id WHERE o.id_autore=?";
		$query = $this->prepare($sql);
        $query->bind_param("i", $autore);
		$query->execute();
		$result = $query->get_result();

		/*preparo la query, la eseguo e ottengo i risultati*/

		if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		$op = array();
		/*foreach($usr as $key => $value)
		{echo "\n".$key."  ".$usr["$key"];} */ /*ciclo per il debug*/
		while ($row = $result->fetch_assoc())
			{
				$op[] = $row;
			}
		
		$query->close();
		$result->free();

		return $op;

    }


    public function getCategoriaName($id = null)
	{
        $sql = "SELECT nome_categoria FROM categoria WHERE id=?";
		$query = $this->prepare($sql);
        $query->bind_param("i", $id);
		$query->execute();
		$result = $query->get_result();

        if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		$cat = $result->fetch_assoc(); /*traformo il risultato della query in un array associativo*/

		/*foreach($usr as $key => $value)
		{echo "\n".$key."  ".$usr["$key"];} */ /*ciclo per il debug*/

		$query->close();
		$result->free();

		return $cat;
    }


	public function getOperaById($id = null)
	{
        $sql = "SELECT titolo, descrizione_short, descrizione, data_creazione, img_path, username, nome_categoria, id_categoria, id_autore 
		FROM (opera o JOIN autore a ON o.id_autore=a.id) JOIN categoria c ON o.id_categoria=c.id WHERE o.id=?";
		$query = $this->prepare($sql);
        $query->bind_param("i", $id);
		$query->execute();
		$result = $query->get_result();

        if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		$cat = $result->fetch_assoc(); /*traformo il risultato della query in un array associativo*/

		/*foreach($usr as $key => $value)
		{echo "\n".$key."  ".$usr["$key"];} */ /*ciclo per il debug*/

		$query->close();
		$result->free();

		return $cat;
    }


	public function deleteOpera($id = null)
	{
        $sql = "DELETE FROM opera WHERE id=?";
		$query = $this->prepare($sql);
        $query->bind_param("i", $id);
		
		if ($query->execute()) {
			return true;
		}
		return false;
    }

	public function deleteProfilo($id = null)
	{
		$sql1 = "DELETE FROM opera WHERE id_autore=?";
        $sql2 = "DELETE FROM autore WHERE id=?";

		$query1 = $this->prepare($sql1);
		$query2 = $this->prepare($sql2);

        $query1->bind_param("i", $id);
        $query2->bind_param("i", $id);
		
		if (!$query1->execute()) {
			return false;
		}
		$query1->close();

		if($query2->execute())
		{
			$res = $this->affected_rows;
			$query2->close();
			return (bool)$res;
		}
		return NULL;
    }

	public function getAutori(){

		$sql = "SELECT * FROM autore WHERE isAdmin=false ORDER BY username";
		$query = $this->prepare($sql);
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

	public function getAutoriLogged($id = null) {

		$sql = "SELECT * FROM autore WHERE id!=? AND isAdmin=false";
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

	public function getAutoreById($id = null){

		$sql = "SELECT * FROM autore WHERE id=?";
		$query = $this->prepare($sql);
		$query->bind_param("i", $id);
		$query->execute();
		$result = $query->get_result();

        if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		$aut = $result->fetch_assoc(); /*traformo il risultato della query in un array associativo*/

		/*foreach($usr as $key => $value)
		{echo "\n".$key."  ".$usr["$key"];} */ /*ciclo per il debug*/

		$query->close();
		$result->free();

		return $aut;

	}

	public function setOpera($titolo, $sht_dsc, $descrizione, $data, $id_autore, $id_categoria, $img){

		$error = array();
		if (strlen($titolo) === 0) {$error[] = "Titolo mancante, inserire un titolo";}
		if (strlen($sht_dsc) === 0) {$error[] = "Descrizione breve mancante, inserire una descrizione breve";}
		if (strlen($sht_dsc) > 200) {$error[] = "Descrizione breve troppo lunga, massimo 200 caratteri";}
		if (strlen($descrizione) === 0) {$error[] = "Descrizione mancante, inserire una descrizione";}
		if (strlen($descrizione) > 2000) {$error[] = "Descrizione troppo lunga, massimo 2000 caratteri";}
		if ($data > date("Y")) {$error[] = "Inserire un anno passato";}
		if (strlen($data) === 0) {$error[] = "Anno mancante, inserire un anno";}
		if (!preg_match($this->yearPattern, $data) && strlen($data) > 0) {$error[] = "Anno in formato errato";}
		if ($id_categoria === "-1") {$error[] = "Categoria mancante, selezionare una categoria";}
		if(empty($img)) {$error[] = "Immagine mancante, inserire un'immagine";}
		

		if(!empty($img))
		{
			$img_format = exif_imagetype($img);
			if(!in_array($img_format , $this->perm_img_format)) {$error[] = 'Formato immagine errato, inserire un immagine in formato PNG o JPEG';} 	 // verifica se è un immagine
			if (filesize($img) > $this->max_img_size) {$error[] = 'Immagine troppo grande (max: 3MB)';}
			$hash = hash_file('sha256', $img);
			if (!move_uploaded_file($img, $this->imgDir.$hash)) {$error[] = "Impossibile spostare l'immagine";}
			$img_path = $this->imgDir.$hash;
			
		}

		if(count($error)) {return $error;}

        if(!empty($img_path)){
			$sql = "INSERT INTO opera VALUES (NULL,?,?,?,?,?,?,?);";
			
			$query = $this->prepare($sql);
			$query->bind_param("ssssiis",$titolo, $sht_dsc, $descrizione, $data, $id_autore, $id_categoria, $img_path);
		
			if($query->execute())
			{
				$new_id = $this->insert_id;
				$res = $this->affected_rows;
				$query->close();
				return $new_id;
			}
		}
	else return false;
	}	

	public function login($username, $password)
	{
		$hashed_pass = hash('sha256', $password);

		$sql = "SELECT id FROM `autore` WHERE username = ? AND password = ? LIMIT 1;";
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

	public function setProfilo($username, $password, $conf_password,$bio)
	{

		$error = array();

		if (strlen($username) > 30) {$error[] = "Username tropppo lunga (Max: 50 caratteri)";}

		if (!preg_match($this->passPattern,$password))	{$error[] = "Password in formato errato, la password deve essere rispettare i seguenti requisiti: deve essere di almeno 8 caratteri con almeno una maiuscola e un numero";}

		If ($password !== $conf_password) {$error[] = "Le password non coincidono";}
		if (strlen($bio) > 2000) {$error[] = "Biografia troppo lunga (max: 65535 caratteri)";}
		if (strlen($bio) === 0) {$error[] = "Biografia mancante, inserire una biografia";}
		if ( $this->alreadyReg($username))	{$error[] = "Username già utilizzato";	}

		$hashed_pass = hash('sha256', $password);

		if(count($error)) {return $error;}

		$bio=htmlentities($bio);

		

		$register = "INSERT INTO autore(username,password,bio,isAdmin) VALUES (?,?,?, false)";

		$query = $this->prepare($register);
		$query->bind_param("sss", $username, $hashed_pass,$bio);
		if($query->execute())
			{
				$new_id = $this->insert_id;
				$_SESSION['user_id']= $new_id;
				$query->close();
				return $new_id;
			}
			else {return NULL;}

		
		
	}
	public function alreadyReg($username)
	{
		$sql = "SELECT id FROM autore WHERE username = ?;";
		$query = $this->prepare($sql);
		$query->bind_param("s", $username);

		if($query->execute())
		{
			if($query->get_result()->num_rows)
			{
				return TRUE;
			}
		}

		return FALSE;
	}
}



