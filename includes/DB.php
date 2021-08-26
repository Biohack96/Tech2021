<?php
class DB extends mysqli{

	private $imgDir = 'img/upload/';
	private $max_img_size = 3000000; // 3MB
	private $perm_img_format = array(IMAGETYPE_GIF , IMAGETYPE_JPEG , IMAGETYPE_PNG);


	public function __construct($host="localhost", $user="root", $pass="", $db="sharearts")
	{
        parent::__construct($host, $user, $pass, $db);

		if (mysqli_connect_error()) {
           	 die();
        	}
	}

    public function getListaCategorie()
	{
		$sql = "SELECT nome_categoria, id FROM categoria";
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


	public function setOpera($titolo, $sht_dsc, $descrizione, $data, $id_autore, $id_categoria, $img){

		if(!empty($img))
		{
			$img_format = exif_imagetype($img);
			if(!in_array($img_format , $this->perm_img_format)) {$error[] = 'Formato immagine errato, inserire un immagine in formato PNG o JPEG';} 	 // verifica se è un immagine
			if (filesize($img) > $this->max_img_size) {$error[] = 'Immagine troppo grande (max: 3MB)';}
			$hash = hash_file('sha256', $img);
			if (!move_uploaded_file($img, $this->imgDir.$hash)) {$error[] = "Impossibile spostare l'immagine";}
			$img_path = $this->imgDir.$hash;
			
		}

        if(!empty($img)){
			$sql = "INSERT INTO opera VALUES (NULL,?,?,?,?,?,?,?);";
			
			$query = $this->prepare($sql);
			$query->bind_param("ssssiis",$titolo, $sht_dsc, $descrizione, $data, $id_autore, $id_categoria, $img_path);
		
			if($query->execute())
			{
				$res = $this->affected_rows;
				$query->close();
				return (bool)$res;
			}
		}
	else return false;
		
}


}