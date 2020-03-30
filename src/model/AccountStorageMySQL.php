<?php 

/**
 * 
 */
class AccountStorageMySQL implements AccountStorage {

	public $connexion;
	
	function __construct($connexion)
	{
		$this->connexion=$connexion;
	}

	public function checkAuth($login, $password){
		$rq = "SELECT * FROM Account WHERE login= :login";
		$stmt = $this->connexion->prepare($rq);
		$data = array(
         ':login' => $login,
        );
		$stmt->execute($data);
		$account=$this->hydrate($stmt);
		if ($account!=null) {
			if (password_verify($password, $account->getMdp())) {
			    return $account;
		    }else{
			    return null;
		    }
		}else{
			return null;
		}
		
	}

	public function create(Account $a){
		$rq = "INSERT INTO Account (nom,login,mdp,statut) VALUES (:nom,:login,:mdp,:statut)";
		$stmt = $this->connexion->prepare($rq);
		$data = array(
         ':nom' => $a->getNom(),
         ':login' => $a->getLogin(),
         ':mdp' => $a->getMdp(),
         ':statut' => $a->getStatut(),
        );
        $t=$stmt->execute($data);
        if ($t) {
        	return $this->connexion->lastInsertId();
        }
	}

	public function exists($login){
		$rq = "SELECT * FROM Account WHERE login= :login";
		$stmt = $this->connexion->prepare($rq);
		$data = array(
         ':login' => $login,
        );
		$stmt->execute($data);
		$rs=$this->hydrate($stmt);
		if ($rs===[]) {
			return 0;
		}else{
			return 1;
		}
	}

	public function hydrate($stmt){
		$account=[];
		if ($setup = $stmt->fetch(PDO::FETCH_ASSOC)) {
		     $account=new Account($setup['nom'],$setup['login'],$setup['mdp'],$setup['statut']);
		}
    	return $account;
	}
}

 ?>