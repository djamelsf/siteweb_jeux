<?php 
/**
 * 
 */
class Game {

	private $nom;
	private $type;
	private $disc;// numero de telephone
	private $prix;
	private $img;
	private $user;
	
	function __construct($nom,$type,$disc,$prix,$img)
	{
		$this->nom=$nom;
		$this->type=$type;
		$this->disc=$disc;
		$this->prix=$prix;
		$this->img=$img;
	}

	public function getNom(){
		return $this->nom;
	}
	public function getType(){
		return $this->type;
	}
	public function getDisc(){
		return $this->disc;
	}
	public function getPrix(){
		return $this->prix;
	}
	public function getImg(){
		return $this->img;
	}
	



}
 ?>