<?php 

/**
 * 
 */
class AccountBuilder {

	private $data;
	private $error=null;
	
	
	function __construct($data)
	{
		$this->data=$data;
		$this->error= array();
	}

	public function createAccount(){
		$hash = password_hash($this->data['mdp'], PASSWORD_BCRYPT);
		return new Account($this->data['nom'],$this->data['login'],$hash,'user');
	}

	public function isValid(){
		$bool=1;
		if (empty($this->data)) {
			$this->error['nom']='Vous devez entrer un nom';
			$this->error['login']='Vous devez entrer un pseudo ';
			$this->error['mdp']='Vous devez entrer un mot de passe';
			$bool=0;
		}
		if ($this->data['nom']==="") {
			$this->error['nom']='Vous devez entrer un nom';
			$bool=0;
    	}if ($this->data['login']==="") {
    		$this->error['login']='Vous devez entrer un pseudo';
    		$bool=0;
    	}else{
    		// if ($this->AccountStorage->exists($this->data['login'])) {
    		// 	$this->error['login']='Vous devez entrer un autre pseudo';
    		//     $bool=0;
    		// }
    		
    	}
    	if ($this->data['mdp']==="") {
    		$this->error['mdp']='Vous devez entrer un mot de passe';
    		$bool=0;
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