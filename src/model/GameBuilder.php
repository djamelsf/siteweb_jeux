<?php 
/**
 * 
 */
class GameBuilder {

	private $data;
	private $error=null;
	
	function __construct($data)
	{
		
		$this->data=$data;
		$this->error= array();
	}

	public function createGame(){
		$datetime = date("Y_m_d_H_i_s");
		if (move_uploaded_file($_FILES['img']['tmp_name'], "upload/".$datetime.".jpg")) {
         //  echo "Copie de fichier réussie";
         // } else {
         //  echo "Problème de copie de fichier";
         // }
		}
		return new Game(htmlspecialchars($this->data['nom']),htmlspecialchars($this->data['type']),htmlspecialchars($this->data['disc']),htmlspecialchars($this->data['prix']),"upload/".$datetime.'.jpg');
	}

	public function isValid(){
		$bool=1;
		if (empty($this->data)) {
			$this->error['nom']='Vous devez entrer un nom';
			$this->error['type']='Vous devez entrer son type';
			$this->error['disc']='Vous devez entrer son num';
			$this->error['prix']='Vous devez entrer son prix valide';
			$this->error['img']='Vous devez entrer une image';
			$bool=0;
		}
		if ($this->data['nom']==="") {
			$this->error['nom']='Vous devez entrer un nom';
			$bool=0;
    	}if ($this->data['type']==="") {
    		$this->error['type']='Vous devez entrer son type';
    		$bool=0;
    	}if ($this->data['disc']==="") {
    		$this->error['disc']='Vous devez entrer son num';
    		$bool=0;
    	}if ($this->data['prix']<0 || $this->data['prix']==="") {
    		$this->error['prix']='Vous devez entrer son prix valide';
    		$bool=0;
    	}if($_FILES['img']['name']===""){
            $this->error['img']='Vous devez entrer une image';
    		$bool=0;
    	}else {
    		$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
		    $detectedType = exif_imagetype($_FILES['img']['tmp_name']);
    		if (!in_array($detectedType, $allowedTypes)) {
    		$this->error['img']='Vous devez entrer une image au format JPEG ou PNG !';
    		$bool=0;
    	    }
        }

    	return $bool;
	}

	public function getData(){
		return $this->data;
	}

	public function getError(){
		return $this->error;
	}
}
 ?>