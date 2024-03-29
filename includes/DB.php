<?php
class DB extends mysqli{

	private $imgDir = 'img/upload/';
	private $max_img_size = 3000000; // 3MB
	private $perm_img_format = array(IMAGETYPE_GIF , IMAGETYPE_JPEG , IMAGETYPE_PNG);
	private $yearPattern = '/^[0-9]{1,4}$/';
	private $passPattern = '/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/' ; // Almeno 8 caratteri con almeno una maiuscola e un numero


	public function __construct($host="localhost", $user="ccinnire", $pass="Iyu6so3Ohr3sei8o", $db="ccinnire")
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

	public function getListaCategorieS($nome)
	{
		$sql = "SELECT nome_categoria, id FROM categoria  where nome_categoria like '%" . $this->real_escape_string($nome) ."%' ORDER BY nome_categoria";
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
		$sql = "SELECT titolo, img_path, o.id, username, descrizione_short, nome_categoria FROM (opera o JOIN autore a ON o.id_autore=a.id) JOIN categoria c ON o.id_categoria=c.id WHERE segnalata=false and segnalato=false";
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

	public function getAllOpereS($search)
	{
		$sql = "SELECT titolo, img_path, o.id, username, descrizione_short, nome_categoria FROM (opera o JOIN autore a ON o.id_autore=a.id) JOIN categoria c ON o.id_categoria=c.id WHERE o.segnalata=false and a.segnalato=false and (titolo like '%". $this->real_escape_string($search) . "%' or descrizione_short like '%" . $this->real_escape_string($search) . "%')";
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


	public function getOpereSegnalate()
	{
		$sql = "SELECT titolo, img_path, o.id, username, descrizione_short, nome_categoria FROM (opera o JOIN autore a ON o.id_autore=a.id) JOIN categoria c ON o.id_categoria=c.id WHERE segnalata=true";
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
		$sql = "SELECT titolo, img_path, o.id, username, descrizione_short FROM opera o JOIN autore a ON o.id_autore=a.id WHERE o.id_categoria=? AND o.segnalata=false";
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
		$sql = "SELECT titolo, img_path, o.id, descrizione_short, nome_categoria FROM opera o JOIN categoria c ON o.id_categoria=c.id WHERE o.id_autore=? AND o.segnalata=false";
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

	public function getMyOpere($autore)
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
        $sql = "SELECT nome_categoria, cat_description FROM categoria WHERE id=?";
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
        $sql = "SELECT titolo, descrizione_short, descrizione, data_creazione, img_path, username, nome_categoria, id_categoria, id_autore, segnalata 
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

		$sql = "SELECT * FROM autore WHERE isAdmin=false AND segnalato=false ORDER BY username";
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

	public function getAutoriSegnalati(){

		$sql = "SELECT * FROM autore WHERE isAdmin=false AND segnalato=true ORDER BY username";
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

		$sql = "SELECT * FROM autore WHERE id!=? AND isAdmin=false AND segnalato=false ORDER BY username";
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

	public function segnalaOpera($id = null){

		$sql = "UPDATE opera SET segnalata=true WHERE id=?";
		$query = $this->prepare($sql);
		$query->bind_param("i", $id);

        if ($query->execute()) {
			return true;
		}
		return false;

	}

	public function segnalaAutore($id = null){

		$sql = "UPDATE autore SET segnalato=true WHERE id=?";
		$query = $this->prepare($sql);
		$query->bind_param("i", $id);

        if ($query->execute()) {
			return true;
		}
		return false;

	}

	public function republishOpera($id = null){

		$sql = "UPDATE opera SET segnalata=false WHERE id=?";
		$query = $this->prepare($sql);
		$query->bind_param("i", $id);

        if ($query->execute()) {
			return true;
		}
		return false;

	}

	public function riabilitaAutore($id = null){

		$sql = "UPDATE autore SET segnalato=false WHERE id=?";
		$query = $this->prepare($sql);
		$query->bind_param("i", $id);

        if ($query->execute()) {
			return true;
		}
		return false;

	}

	public function setOpera($titolo, $sht_dsc, $descrizione, $data, $id_autore, $id_categoria, $img){

		$error = array();
		if (strlen($titolo) > 200) {$error[] = "Titolo troppo lungo, lunghezza massima 200 caratteri";}
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
			$sql = "INSERT INTO opera VALUES (NULL,?,?,?,?,?,?,?,false);";
			
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

		if (strlen($username) > 30) {$error[] = '<span xml:lang="en">Username</span> tropppo lunga (Massimo: 50 caratteri)';}

		if (!preg_match($this->passPattern,$password))	{$error[] = '<span xml:lang="en">Password</span> in formato errato, la <span xml:lang="en">password</span> deve essere rispettare i seguenti requisiti: deve essere di almeno 8 caratteri con almeno una maiuscola e un numero';}

		If ($password !== $conf_password) {$error[] = 'Le <span xml:lang="en">password</span> non coincidono';}
		if (strlen($bio) > 2000) {$error[] = "Biografia troppo lunga (massimo: 65535 caratteri)";}
		if (strlen($bio) === 0) {$error[] = "Biografia mancante, inserire una biografia";}
		if ( $this->alreadyReg($username))	{$error[] = '<span xml:lang="en">Username</span> già utilizzato';	}

		$hashed_pass = hash('sha256', $password);

		if(count($error)) {return $error;}

		$bio=htmlentities($bio);
		$username=htmlentities($username);
		

		$register = "INSERT INTO autore(username,password,bio,segnalato,isAdmin) VALUES (?,?,?,false, false)";

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

	public function updateProfilo($username, $password, $conf_password,$bio)
	{

		$error = array();

		$a = $this->getAutoreById($_SESSION['user_id']);

		if (strlen($username) > 30) {$error[] = '<span xml:lang="en">Username</span> tropppo lunga (Massimo: 50 caratteri)';}

		if (!empty($password) && !preg_match($this->passPattern,$password))	{$error[] = '<span xml:lang="en">Password</span> in formato errato, la <span xml:lang="en">password</span> deve essere rispettare i seguenti requisiti: deve essere di almeno 8 caratteri con almeno una maiuscola e un numero';}

		
		If (!empty($password) && $password !== $conf_password) {$error[] = 'Le <span xml:lang="en">password</span> non coincidono';}


		if (strlen($bio) > 2000) {$error[] = "Biografia troppo lunga (massimo: 65535 caratteri)";}
		if (strlen($bio) === 0) {$error[] = "Biografia mancante, inserire una biografia";}
		if ( $a['username']!= $username && $this->alreadyReg($username))	{$error[] = '<span xml:lang="en">Username</span> già utilizzato';	}

		if(!empty($password))
		{$hashed_pass = hash('sha256', $password);}
		else
		{
			$hashed_pass = $a['password'];
		}

		if(count($error)) {return $error;}

		$bio=htmlentities($bio);
		$username=htmlentities($username);
		

		$register = "UPDATE autore SET username = ? , password = ? , bio = ? where id = ?";

		$query = $this->prepare($register);
		$query->bind_param("sssi", $username, $hashed_pass,$bio,$_SESSION['user_id']);
		if($query->execute())
			{
				
				$query->close();
				return $_SESSION['user_id'];
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

	public function getAutoriS($search,$id = null) {

		if(is_numeric($id))
		{
			$sql = "SELECT * FROM autore WHERE id!=? AND isAdmin=false AND segnalato=false and username like '%".  $this->real_escape_string($search)  ."%' ORDER BY username";
			$query = $this->prepare($sql);
			$query->bind_param("i", $id);
		}
		else
		{
			$sql = "SELECT * FROM autore where isAdmin=false AND segnalato=false and username like '%".  $this->real_escape_string($search)  ."%' ORDER BY username";
			$query = $this->prepare($sql);

		
		}
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
}



