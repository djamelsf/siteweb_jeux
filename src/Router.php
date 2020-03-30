<?php 

/**
 * 
 */
require_once("view/View.php");
require_once("view/PrivateView.php");
require_once("control/Controller.php");
require_once("model/Game.php");
require_once("model/GameStorage.php");
//require_once("model/GameStorageStub.php");
//require_once("model/GameStorageFile.php");
require_once("model/GameStorageMySQL.php");
require_once("model/GameBuilder.php");
 require_once("model/AccountBuilder.php");
 require_once("model/Account.php");
 require_once("model/AccountStorage.php");
 require_once("model/AuthenticationManager.php");
 require_once("model/AccountStorageMySQL.php");

class Router {
	
	public function main($GameStorage,$AccountStorage){
		session_start();

		$feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
		$_SESSION['feedback'] = '';

		$login=key_exists('user', $_SESSION) ? $_SESSION['user'] : '';

	       if (isset($_SESSION['user'])) {
	       	$view=new PrivateView($_SESSION['user'],$this,$feedback);
	       }else{
	       	$view=new View($this,$feedback);
	       }
	     	
	         
	     	

		
		//$GameStorage=new GameStorageFile('src/games_db.txt');
		//$GameStorage->reinit();
        $AuthenticationManager= new AuthenticationManager($AccountStorage);
		$Controller=new Controller($view,$GameStorage,$AuthenticationManager,$AccountStorage);



		$gameId = key_exists('game', $_GET) ? $_GET['game'] : null;
		$action = key_exists('action', $_GET) ? $_GET['action'] : null;

		if ($action === null) {
			/* Pas d'action demandée : par défaut on affiche
	 	 	 * la page d'accueil, sauf si un jeu est demandé,
	 	 	 * auquel cas on affiche sa page. */
			$action = ($gameId === null) ? "accueil" : "voir";
		}


		try {
			switch ($action) {
				case 'voir':
					if ($gameId === null) {
					    // $view->makeUnknownActionPage();
				    } else {
				    	if (isset($_SESSION['user'])) {
				    		$Controller->showInformation($gameId);
				    	}else{
				    		$view->makeNonPage();
				    	}     
					     
				    }
					break;

				case 'nouveau':
					if (isset($_SESSION['user'])) {
						$Controller->newGame();
					}else{
						$view->makeNonPage();
					}
					break;

				case 'sauverNouveau':
				    if (isset($_SESSION['user'])) {
				        $Controller->saveNewGame($_POST);
				    }else{
				    	$view->makeNonPage();
				    }
					
					break;

				case 'login':
					$Controller->MakeLoginPage();
					break;

				case 'check':
					$Controller->checkConnection($_POST);
					break;

				case 'inscription':
					$Controller->newAccount();
					break;

				case 'Saveinscription':
					$Controller->saveNewAccount($_POST);
					break;

				case 'deconnexion':
				    if (isset($_SESSION['user'])) {
				    	$Controller->deconnexion();
				    }else{
				    	$view->makeNonPage();
				    }
					break;

				case 'mylist':
				    if (isset($_SESSION['user'])) {
				    	$Controller->showMylist();
				    }else{
				    	$view->makeNonPage();
				    }
					break;

				case 'accueil':
					$Controller->showList();
					break;

				case 'liste':
					$Controller->showList();
					break;

				case 'edit':
					if ($gameId===null) {
						# code...
					}else{
						$Controller->modifierGame($gameId);
					}
					break;

				case 'saveEdit':
					if ($gameId===null) {
						# code...
					}else{
						$Controller->EditNewGame($_POST,$gameId);
					}
					break;

				case 'askDelete':
					if ($gameId===null) {
						//
					}else{
						$Controller->askGameDeletion($gameId);
					}
					break;

				case 'delete':
					if ($gameId===null) {
						# code...
					}else{
						if ($_POST['ouiNon']==='oui') {
							$Controller->deleteGame($gameId);
						}else{
							$Controller->showInformation($gameId);
						}
					}
					break;
				
				default:
					$view->makeUnknownGamePage();
					break;
			}
			
		} catch (Exception $e) {
			$view->makeUnexpectedErrorPage($e);
		}


		//////////////

        //echo $_SERVER['PATH_INFO'];
        


		
		

		$view->render();
		}
    

    public function getAccueilPage(){
    	return '.';
    }

	public function getGameURL($key){
		return '?game='.$key;
	}

	public function getListURL(){
		return '?action=liste';
	}

	public function getGameCreationURL(){
		return '?action=nouveau';
	}

	public function getGameSaveURL(){
		return '?action=sauverNouveau';
	}

	public function POSTredirect($url, $feedback){
		$_SESSION['feedback'] = $feedback;
		header("Location: ".htmlspecialchars_decode($url), true, 303);
		die;
	}

	// public function getGameAskDeletionURL($id){
	// 	return '?askDelete='.$id;
	// }

	public function getGameAskDeletionURL($id){
		return '?action=askDelete&amp;game='.$id;
	}

	// public function getGameDeletionURL($id){
	// 	return '?delete='.$id;
	// }

	public function getGameDeletionURL($id){
		return '?action=delete&amp;game='.$id;
	}

	// public function getGameEditURL($id){
	// 	return '?edit='.$id;
	// }

	public function getGameEditURL($id){
		return '?action=edit&amp;game='.$id;
	}

	// public function getGameSaveEditURL($id){
	// 	return '?saveEdit='.$id;
	// }

	public function getGameSaveEditURL($id){
		return '?action=saveEdit&amp;game='.$id;
	}

	public function getLoginURL(){
		return '?action=login';
	}

	public function getCheckURL(){
		return '?action=check';
	}

	public function getInscriptionURL(){
		return '?action=inscription';
	}

	public function getSaveUserURL(){
		return '?action=Saveinscription';
	}

	public function DeconnexionURL(){
		return '?action=deconnexion';
	}

	public function getMyList(){
		return '?action=mylist';
	}

		
		
	}

 ?>