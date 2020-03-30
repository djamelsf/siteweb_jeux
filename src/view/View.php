<?php 
/**
 * 
 */
class View {

	private $title;
	private $content;
	private $router;
	private $feedback;
	
	function __construct($router,$feedback){
		$this->router=$router;
		$this->feedback=$feedback;
	}

	public function render(){
		
			echo "<!DOCTYPE html>
              <html>
              <head>
               <meta charset='UTF-8' />
               <link href='src/skin/s12.css' rel='stylesheet'>
              	<title> Zone Gaming </title>
              </head>
              <body>
              <div id='navbar'> ".$this->navBar()." </div>
              ".$this->feedb()."
              <h1> ".$this->title." </h1>
              <p> ".$this->content." </p>
              </body>
              </html>";
		
		
	}

	public function feedb(){
		if ($this->feedback!=='') {
			return "<p class='feedback'> ".$this->feedback." </p>";
		}
		return "";
	}

	public function navBar(){
		return " <img src='LOGO.png'> <a href='".$this->router->getAccueilPage()."'> Liste des annonces </a> <a href='".$this->router->getInscriptionURL()."'> Inscription </a> <a href='".$this->router->getLoginURL()."'> Connexion </a>  <a href='".$this->router->getListURL()."'> A propos </a>";
	}

	public function makeGamePage(Game $game,$id,$t){
		$this->title=$game->getNom();
		$this->content=$game->getNom()." est un jeu de type ".$game->getType()." <a href='".$this->router->getGameAskDeletionURL($id)."'> Supprimer? </a> <br>".
		" <a href='".$this->router->getGameEditURL($id)."'>Modifier? </a>
		<img src='".$game->getImg()."'>";
	}

	function makeAccueilPage(){
		$this->title='Page d accueil';
		$this->content='<a href='.$this->router->getListURL().'> liste des jeux </a>';
	}

	public function makeUnknownGamePage(){
		$this->title='Page inconnue';
		$this->content='page inconnue';
	}

	public function makeNonPage(){
		$this->title='Accès interdit';
		$this->content="<p id='ai'> Vous n'etes pas autorisé(e) a acceder a cette page, veuillez vous connecter afin de la visualiser </p>";
	}

	public function makeDeniedPage(){
		$this->title='Accès interdit';
		$this->content="<p id='ai'> Vous n'etes pas autorisé(e) a effectuer cette tâche. </p>";
	}

	// public function makeListPage($tabGames){
	// 	$this->title='liste page';
	// 	$string="<table id='listeGame'>";
	// 	foreach ($tabGames as $key => $value) {
	// 		$string.='<tr class="ligne"> <td> <p class="ligneNom">'.$value->getNom().' </p>  </td> <td> <img src="'.$value->getImg().'"></td> <td> <a href="'.$this->router->getGameURL($key).'"> <button> detail </button> </a> </td>  </tr>';
	// 	}
	// 	$string.=' </table>';
	// 	$this->content=$string;
	// }

	public function makeListPage($tabGames){
		$this->title='Liste des annonces';
		if (empty($tabGames)) {
			$string="<div>";
			$string.="<p id='ai'> Liste vide. </p>";
			$string.='</div>';
		}else{
		    $string="<div id='listeGame'>";
		    foreach ($tabGames as $key => $value) {
		    	$string.='<div class="ligne">  <img src="'.$value->getImg().'">  <p>'.$value->getNom().' </p> <p>'.$value->getPrix().' EUR </p> </div>';
		                          }
		    $string.=' </div>';
		}
		$this->content=$string;
	}

	public function makeGameCreationPage(GameBuilder $GameBuilder){
	     $data=$GameBuilder->getData();
		$erreur=$GameBuilder->getError();
		$nomE=''; $typeE=''; $discE=''; $prixE=''; $imgE='';
		if ($data===null) {
			$nom=''; $type=''; $disc=''; $prix=''; $img='';
		}else{
			$nom=key_exists('nom', $data) ? $data['nom'] : '';  $type=key_exists('type', $data) ? $data['type'] :''; $disc=key_exists('disc', $data) ? $data['disc'] : ''; $prix=key_exists('prix', $data) ? $data['prix'] :''; $img=key_exists('img', $data) ? $data['img'] :'';
			$nomE=key_exists('nom', $erreur) ? $erreur['nom'] : ''; 
			$typeE=key_exists('type', $erreur) ? $erreur['type'] :'';
			$discE=key_exists('disc', $erreur) ? $erreur['disc'] : '';
			$prixE=key_exists('prix', $erreur) ? $erreur['prix'] : ''; 
			$imgE=key_exists('img', $erreur) ? $erreur['img'] : '';
		}
		$this->title='Creation Jeu';
		$string="";
		$string.="<form method='post' enctype='multipart/form-data'  action='".$this->router->getGameSaveURL()."'>";
		$string.="Nom : <input type='text' name='nom' value=".$nom."> ".$nomE." <br>";
		$string.="Type : <input type='text' name='type' value=".$type."> ".$typeE." <br>";
		$string.="Discription : <input type='text' name='disc' value=".$disc."> ".$discE." <br>";
		$string.="Prix : <input type='number' name='prix' value=".$prix."> ".$prixE." <br>";
		$string.="Image : <input type='file' name='img' value=".$img."> ".$imgE." <br>";
		$string.="<input type='submit' value='Créer'> <br>";
		$string.="</form>";
		$this->content=$string;
	}

	public function makeGameModificationPage(GameBuilder $GameBuilder,$id){
	    $data=$GameBuilder->getData();
		$erreur=$GameBuilder->getError();
		$nomE=''; $typeE=''; $discE=''; $prixE=''; $imgE='';
		if ($data===null) {
			$nom=''; $type=''; $disc=''; $prix=''; $img='';
		}else{
			$nom=key_exists('nom', $data) ? $data['nom'] : '';  $type=key_exists('type', $data) ? $data['type'] :''; $disc=key_exists('disc', $data) ? $data['disc'] : ''; $prix=key_exists('prix', $data) ? $data['prix'] :''; $img=key_exists('img', $data) ? $data['img'] :'';
			$nomE=key_exists('nom', $erreur) ? $erreur['nom'] : ''; 
			$typeE=key_exists('type', $erreur) ? $erreur['type'] :'';
			$discE=key_exists('disc', $erreur) ? $erreur['disc'] : '';
			$prixE=key_exists('prix', $erreur) ? $erreur['prix'] : ''; 
			$imgE=key_exists('img', $erreur) ? $erreur['img'] : '';
		}
		$this->title='Modification Jeu';
		$string="";
		$string.="<form method='post' enctype='multipart/form-data' action='".$this->router->getGameSaveEditURL($id)."'>";
		$string.="Nom : <input type='text' name='nom' value=".$nom."> ".$nomE." <br>";
		$string.="Type : <input type='text' name='type' value=".$type."> ".$typeE." <br>";
		$string.="Discription : <input type='text' name='disc' value=".$disc."> ".$discE." <br>";
		$string.="Prix : <input type='number' name='prix' value=".$prix."> ".$prixE." <br>";
		$string.="Image : <input type='file' name='img' value=".$img."> ".$imgE." <br>";
		$string.="<input type='submit' value='Modifier'> <br>";
		$string.="</form>";
		$this->content=$string;
	}

	public function makeDebugPage($variable) {
	$this->title = 'Debug';
	$this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
    }

    public function makeAccountCreatedPage(){
    	$this->router->POSTredirect($this->router->getLoginURL(), "Le compte a bien été crée !");
    }

    public function makeGameCreatedPage($id) {
		$this->router->POSTredirect($this->router->getGameURL($id), "Le jeu a bien été créée !");
	}

	public function makeGameNotCreatedPage() {
		$this->router->POSTredirect($this->router->getGameCreationURL(), "Erreur dans le formulaire !");
	}

	public function alreadyExistAccount(){
		$this->router->POSTredirect($this->router->getInscriptionURL(), "le pseudo est dèja utilisé par une autre personne !");
	}

	public function makeNotCreatedPage() {
		$this->router->POSTredirect($this->router->getInscriptionURL(), "Erreur dans le formulaire !");
	}

	public function makeGameEditedPage($id) {
		$this->router->POSTredirect($this->router->getGameURL($id), "Le jeu a bien été modifié !");
	}

	public function makeGameNotEditedPage($id) {
		$this->router->POSTredirect($this->router->getGameEditURL($id), "Erreur dans le formulaire !");
	}

	public function makeGameDeletedPage() {
		$this->router->POSTredirect($this->router->getListURL(), "Le jeu a bien été supprimé");
	}

	public function makeUserConnectedPage() {
		$this->router->POSTredirect($this->router->getListURL(), "Bonjour, ".$_SESSION['user']->getNom());
	}

	public function makeUserNotConnectedPage() {
		$this->router->POSTredirect($this->router->getLoginURL(), "Connexion echoué !");
	}

	public function makeDeconnexionPage() {
		$this->router->POSTredirect($this->router->getListURL(), "Au revoir !");
	}

	public function makeGameDeletionPage($id){
		$string="<form method='post' action='".$this->router->getGameDeletionURL($id)."''>";
		$string.="Etes vous sûr de supprimer ce jeu ?";
		$string.=" Oui ? <input type='radio' name='ouiNon' value='oui'> <br>";
		$string.=" Non ? <input type='radio' name='ouiNon' value='non'> <br>";
		$string.="<input type='submit' value='confirmer'> <br>";
		$string.="</form>";
		$this->title="Supprimer?";
		$this->content=$string;
	}

	public function makeLoginFormPage(){
		$string="<form id='login' method='post' action='".$this->router->getCheckURL()."'>";
		//$string.="Login : <input type='text' name='login'> <br>";
		$string.="<div id='formLogin'>";
		$string.="<div id='user'> <p> Pseudo </p> <input type='text' name='login'> </div>";
		//$string.="Mot de passe : <input type='password' name='mdp'> <br>";
		$string.="<div id='mdpp'> <p> Mot de passe </p> <input type='password' name='mdp'> </div>";
		$string.="</div>";
		$string.="<input id='conx' type='submit' value='Connexion'> <br>";
		$string.="</form>";
		$this->title="Login page";
		$this->content=$string;
	}

	public function makeInscriptionPageUser(AccountBuilder $AccountBuilder){
		$data=$AccountBuilder->getData();
		$erreur=$AccountBuilder->getError();
		$nom="";
		$login="";
		$nomE="";
		$loginE="";
		$mdpE="";
		if($data!==null){
		$nom=key_exists('nom', $data) ? $data['nom'] : '';
		$login=key_exists('login', $data) ? $data['login'] : '';
		$nomE=key_exists('nom', $erreur) ? $erreur['nom'] : ''; 
		$loginE=key_exists('login', $erreur) ? $erreur['login'] : ''; 
		$mdpE=key_exists('mdp', $erreur) ? $erreur['mdp'] : ''; 
		}
		
		
		$string="<form  class='addAnn' method='post' action='".$this->router->getSaveUserURL()."'>";
		$string.="Nom : <input type='text' name='nom' value='".$nom."'> ".$nomE." <br>";
		$string.="Pseudo : <input type='text' name='login' value='".$login."'> ".$loginE." <br>";
		$string.="Mot de passe : <input type='password' name='mdp'> ".$mdpE." <br>";
		$string.="<input type='submit' class='addd' value='confirmer'> <br>";
		$string.="</form>";
		$this->title="Nouvelle inscription";
		$this->content=$string;
	}


}

 ?>