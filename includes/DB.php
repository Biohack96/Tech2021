<?php
class DB extends mysqli{


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

    public function getOpereByCategoria($categoria)
	{
		$sql = "SELECT titolo, img_path, o.id, username, descrizione_short FROM opera o JOIN autore a ON o.id_categoria=a.id WHERE o.id_categoria=?";
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

}