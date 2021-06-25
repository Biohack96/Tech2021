<?php
class DB extends mysqli{


	private $imgDir = 'img/upload/';
	private $max_img_size = 3000000; // 3MB
	private $perm_img_format = array(IMAGETYPE_GIF , IMAGETYPE_JPEG , IMAGETYPE_PNG);
	private $namePattern = "/^[a-zA-Z \\'\\s\é\è\ò\à\ù\ì]{2,30}$/" ;
	private $mailPattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/' ;
	private $passPattern = '/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/' ; // Almeno 8 caratteri con almeno una maiuscola e un numero
	private $cfPattern = '/^[a-zA-Z]{6}[0-9]{2}[abcdehlmprstABCDEHLMPRST]{1}[0-9]{2}([a-zA-Z]{1}[0-9]{3})[a-zA-Z]{1}$/' ;
	private $cellPattern = '/^[0-9]{7,12}$/';


	public function __construct($host="localhost", $user="root", $pass="", $db="workeradvisor")
	{
        parent::__construct($host, $user, $pass, $db);

		if (mysqli_connect_error()) {
           	 die();
        	}
	}
	
	public function alreadyReg($mail , $cf)
	{
		$error = array();

		$sql = "SELECT id FROM utente WHERE email = ?;";
		$query = $this->prepare($sql);
		$query->bind_param("s", $mail);

		if($query->execute())
		{
			if($query->get_result()->num_rows)
			{
				$query->close();
				$error[] = "Mail già presente";
			}


		}
		else
		{
			$error[] = "Impossibile contattare il db per verificare l'unicità dell'account";
		}

		$sql1 = "SELECT id FROM utente WHERE cf = ?;";
		$query = $this->prepare($sql1);
		$query->bind_param("s", $cf);

		if($query->execute())
		{
			if($query->get_result()->num_rows)
			{
				$query->close();
				$error[] = "Codice fiscale già presente";
			}


		}
		else
		{
			$error[] = "Impossibile contattare il db per verificare l'unicità dell'account";
		}

		if(count($error)) {return $error;}

		return FALSE;
	}
	
	public function getcards($limit = 1,$offset=0)
	{
		$cards = array();

		$sql = "SELECT FORMAT(AVG(voto), 1) as voto,u.id,nome,cognome,professione,luogo,img_path FROM utente u left join recensione r on u.id = r.id_utente group by u.id limit $limit offset $offset";
		$query = $this->prepare($sql);
		$query->execute();
		$result = $query->get_result();

		/*preparo la query, la eseguo e ottengo i risultati*/

		if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		while ($row = $result->fetch_assoc())
			{
				if(empty($row['voto'])){
						$row['voto'] == "non";
						//echo "AAAAAAAAAAA";
						}
					
					$cards[] = $row;
					
			}
		
		$query->close();
		$result->free();

		return $cards;

    }
	
	public function getcardsR($limit = 1,$offset=0,$nome='',$luogo='',$professione='')
	{
		
		$nome= $this->real_escape_string($nome);
		$luogo= $this->real_escape_string($luogo);
		$professione=$this->real_escape_string($professione);
		
		$cards = array();

		$sql = "SELECT FORMAT(AVG(voto), 1) as voto,u.id,nome,cognome,professione,luogo,img_path FROM utente u left join recensione r on u.id = r.id_utente where (nome  like '%".$nome . "%' or cognome  like '%".$nome . "%') and professione like '%".$professione . "%' and luogo like '%".$luogo . "%' group by u.id limit $limit offset $offset";
		//var_dump($sql);
		$query = $this->prepare($sql);
		$query->execute();
		$result = $query->get_result();

		/*preparo la query, la eseguo e ottengo i risultati*/

		if($result->num_rows === 0) return NULL; /*check sul risultato ritornato*/

		while ($row = $result->fetch_assoc())
			{
				if(empty($row['voto'])){
						$row['voto'] == "non";
						//echo "AAAAAAAAAAA";
						}
					
					$cards[] = $row;
					
			}
		
		$query->close();
		$result->free();

		return $cards;

    }

	public function getProfilo($id = NULL)
	{
		$sql = "SELECT id,email,nome,cognome,telefono, datanascita, DATE_FORMAT(datanascita,'%d/%m/%Y') as data_nascita,cf,professione,luogo,bio,img_path FROM `utente` WHERE id=?";

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


	public function setProfilo($email, $password, $conf_password, $nome, $cognome, $telefono, $datanascita, $cf, $professione, $luogo, $bio, $img)

	{
		$error = array();
		if (strlen($email) > 50) {$error[] = "Mail tropppo lunga (Max: 50 caratteri)";}
		if (!preg_match($this->mailPattern,$email)) {$error[] = "Mail in formato errato";}

		if (!preg_match($this->passPattern,$password))
		{
			$error[] = "Password in formato errato, la password deve essere rispettare i seguenti requisiti: deve essere di almeno 8 caratteri con almeno una maiuscola e un numero";
		}

		if ($password !== $conf_password) {$error[] = "Le password non coincidono";}
		if (!preg_match($this->namePattern, $nome)) {$error[] = "Nome non valido, non sono concesse lettere accentate (min: 2 caratteri, max: 30 caratteri)";};
		if (!preg_match($this->namePattern, $cognome)) {$error[] = "Cognome non valido, non sono concesse lettere accentate (min: 2 caratteri, max: 30 caratteri)";}
		if($datanascita > date('Y-m-d H:i:s')) {$error[] = "Devi mettere una data passata";}
		if(empty($datanascita)) {$error[] = "Devi specificare una data di nascita";}
		//else if((int)($date_now - $datanascita) < 3) {$error[] = "Sei un prodigio per essere un bebè";}
		//else if((int)($date_now - $datanascita) < 13) {$error[] = "Apprezziamo la buona voltà ma sei troppo giovane per iscriverti a questo sito :(";}
		if (strlen($cf) !== 16) {$error[] = 'Codice fiscale non valido';}
		if (strlen($bio) > 65535) {$error[] = "Biografia troppo lunga (max: 65535 caratteri)";}
		if (strlen($bio) === 0) {$error[] = "Biografia mancante, inserire una biografia";}
		if (!preg_match($this->cellPattern,$telefono)) {$error[] = "Numero di telefono non valido, inserire solo numeri (min: 7 numeri, max: 12 numeri)";}
		if (strlen($luogo) <2) {$error[] = "Luogo non valido, almeno due caratteri";}
		if (strlen($professione) < 2) {$error[] = "Professione non valida, almeno 2 caratteri";}
		if($t = $this->alreadyReg($email,$cf)) 
		{
			foreach($t as $e)
			$error[] = $e;
		}
		
		$hashed_pass = hash('sha256', $password);

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
	
	if(!empty($img_path)) 
		{

		$sql = "INSERT INTO utente (email,password,nome,cognome,telefono,datanascita,cf,professione,luogo,bio,img_path) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

				$query = $this->prepare($sql);
				$query->bind_param("sssssssssss", $email, $hashed_pass, $nome, $cognome, $telefono, $datanascita, $cf, $professione, $luogo, $bio, $img_path);


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


		$sql = "INSERT INTO utente (email,password,nome,cognome,telefono,datanascita,cf,professione,luogo,bio) VALUES (?,?,?,?,?,?,?,?,?,?)";

				$query = $this->prepare($sql);
				$query->bind_param("ssssssssss", $email, $hashed_pass, $nome, $cognome, $telefono, $datanascita, $cf, $professione, $luogo, $bio);


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



	public function updateProfilo ($id, $email, $password, $conf_password, $nome, $cognome, $telefono, $datanascita, $cf, $professione, $luogo, $bio, $img){
	
	$error = array();
		if (strlen($email) > 50) {$error[] = "Mail tropppo lunga (Max: 50 caratteri)";}
		if (!preg_match($this->mailPattern,$email)) {$error[] = "Mail in formato errato";}
	
		if(!empty($password)){
		if (!preg_match($this->passPattern,$password))
		{
			$error[] = "Password in formato errato, la password deve essere rispettare i seguenti requisiti: deve essere di almeno 8 caratteri con almeno una maiuscola e un numero";
		}
		}
	
		if ($password !== $conf_password) {$error[] = "Le password non coincidono";}
		if (!preg_match($this->namePattern, $nome)) {$error[] = "Nome non valido, non sono concesse lettere accentate (min: 2 caratteri, max: 30 caratteri)";};
		if (!preg_match($this->namePattern, $cognome)) {$error[] = "Cognome non valido, non sono concesse lettere accentate (min: 2 caratteri, max: 30 caratteri)";}
		if($datanascita > date('Y-m-d H:i:s')) {$error[] = "Devi mettere una data passata";}
		if(empty($datanascita)) {$error[] = "Devi specificare una data di nascita";}
		//else if((int)($date_now - $datanascita) < 3) {$error[] = "Sei un prodigio per essere un bebè";}
		//else if((int)($date_now - $datanascita) < 13) {$error[] = "Apprezziamo la buona voltà ma sei troppo giovane per iscriverti a questo sito :(";}
		if (strlen($cf) !== 16) {$error[] = 'Codice fiscale non valido';}
		if (strlen($bio) > 65535) {$error[] = "Biografia troppo lunga (max: 65535 caratteri)";}
		if (strlen($bio) === 0) {$error[] = "Biografia mancante, inserire una biografia";}
		if (!preg_match($this->cellPattern,$telefono)) {$error[] = "Numero di telefono non valido, inserire solo numeri (min: 7 numeri, max: 12 numeri)";}
		if (strlen($luogo) <2) {$error[] = "Luogo non valido, almeno due caratteri";}
		if (strlen($professione) < 2) {$error[] = "Professione non valida, almeno 2 caratteri";}
		if($t = $this->alreadyReg($email,$cf)) 
		{
			foreach($t as $e)
			$error[] = $e;
		}
		
	if(!empty($img))
		{
			$img_format = exif_imagetype($img);
			if(!in_array($img_format , $this->perm_img_format)) {$error[] = 'Formato immagine errato, inserire un immagine in formato PNG o JPEG';} 	 // verifica se è un immagine
			if (filesize($img) > $this->max_img_size) {$error[] = 'Immagine troppo grande (max: 3MB)';}
			$hash = hash_file('sha256', $img);
			if(is_uploaded_file($_FILES['img']['tmp_name']))
			{
			if (!move_uploaded_file($img, $this->imgDir.$hash)) {$error[] = "Impossibile spostare l'immagine";}
			}
			$img_path = $this->imgDir.$hash;
		}
		
		if(count($error)) {return $error;}

	if(!empty($img_path) && !empty($password)) 
	{

		$hashed_pass = hash('sha256', $password);

		$sql = "UPDATE utente

				SET email=?, password=?, nome=?, cognome=?, telefono=?, datanascita=?, cf=?, professione=?, luogo=?, bio=?, img_path=?
				WHERE id=?";

		$query = $this->prepare($sql);
		$query->bind_param("sssssssssssi", $email, $hashed_pass, $nome, $cognome, $telefono, $datanascita, $cf, $professione, $luogo, $bio, $img_path, $id);

	
		if($query->execute())
				{
					$query->close();
					return $id;
				}

	}
	
	else
	{
		$sql = "UPDATE utente
				SET email=?, nome=?, cognome=?, telefono=?, datanascita=?, cf=?, professione=?, luogo=?, bio=?, img_path=?
				WHERE id=?";

		$query = $this->prepare($sql);
		$query->bind_param("ssssssssssi", $email, $nome, $cognome, $telefono, $datanascita, $cf, $professione, $luogo, $bio, $img_path, $id);

	
		if($query->execute())
				{
					$query->close();
					return $id;
				}
	}

	}


public function deleteProfilo($id = NULL){
	
		$sql1 = "DELETE FROM recensione WHERE id_autore = ? ;";
		$sql2 = "DELETE FROM utente WHERE id = ? ;";

		$query1 = $this->prepare($sql3);
		$query2 = $this->prepare($sql4);

		$query1->bind_param("i", $id);
		$query2->bind_param("i", $id);


		if(!$query1->execute())
		{
			return NULL;
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
		$sql = "SELECT recensione.id, descrizione, voto, DATE_FORMAT(data_recensione, '%d/%m/%Y') AS data_recensione, nome, cognome, id_autore, id_utente  FROM recensione JOIN utente ON recensione.id_autore = utente.id WHERE recensione.id=?";
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
