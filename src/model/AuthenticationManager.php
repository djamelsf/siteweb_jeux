<?php 
/**
 * 
 */
class AuthenticationManager {

	private $AccountStorage;

	function __construct(AccountStorage $AccountStorage)
	{
		$this->AccountStorage=$AccountStorage;
	}

	public function connectUser($login, $password){
		$login=$this->AccountStorage->checkAuth($login, $password);
		if ($login!=null) {
			$_SESSION['user']=$login;
			return true;
		}else{
			return false;
		}
	}

	public function isUserConnected(){
		if (isset($_SESSION['user'])) {
			return true;
		}else{
			return false;
		}
	}

	public function isAdminConnected(){
		if (isset($_SESSION['user'])) {
			if ($_SESSION['user']->getStatut()==='admin') {
				return true;
			}
		}
		return false;
	}

	public function getUserName(){
		return $_SESSION['user']->getNom();
	}

	public function disconnectUser(){
		unset($_SESSION['user']);
	}

	public function getAccountStorage(){
		return $this->AccountStorage;
	}
}
 ?>