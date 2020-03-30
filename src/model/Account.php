<?php 
/**
 * 
 */
class Account {

	private $nom;
	private $login;
	private $mdp;
	private $statut;
	
	function __construct($nom,$login,$mdp,$statut)
	{
		$this->nom=$nom;
		$this->login=$login;
		$this->mdp=$mdp;
		$this->statut=$statut;
	}

	public function getNom(){
		return $this->nom;
	}

	public function getLogin(){
		return $this->login;
	}

	public function getMdp(){
		return $this->mdp;
	}

	public function getStatut(){
		return $this->statut;
	}
}

 ?>