<?php 
/**
 * 
 */
class PrivateView extends View {

	private $title;
	private $content;
	private $router;
	private $feedback;
	private $Account;
	
	function __construct(Account $Account,$router,$feedback)
	{
		parent::__construct($router,$feedback);
        $this->router=$router;
        $this->feedback=$feedback;
		$this->Account=$Account;
	}

	public function render(){
		
			echo "<!DOCTYPE html>
              <html>
              <head>
               <meta charset='UTF-8' />
               <link href='src/skin/s12.css' rel='stylesheet'>
              	<title> Zone Gaming</title>
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
		return " <img src='LOGO.png'> <a href='".$this->router->getAccueilPage()."'> Liste des annonces </a> <a href='".$this->router->getGameCreationURL()."'> Ajouter une annonce </a>  <a href='".$this->router->getMyList()."'> Mes annonces </a> <a href='".$this->router->DeconnexionURL()."'> Deconnexion (".$_SESSION['user']->getLogin().") </a>";
	}

	public function makeAccueilPage(){
		$this->title='Page d accueil';
		$this->content='Bonjour '.$this->Account->getNom();
	}

	public function makeGamePage(Game $game,$id,$t){
		$this->title=$game->getNom();
		$string="";
		$string.="<div class='cJeu'> <img src='".$game->getImg()."'>";
		$string.="<div class='cC'> <p> Jeu : ".$game->getNom()." </p> <p> Type : ".$game->getType()." </p> <p> Num : ".$game->getDisc()." </p> <p> Prix : ".$game->getPrix()." EUR </p>  <p> par ".$t." </p>";
		$string.="</div>";
		$string.="</div>";
		$this->content=$string;
	}

	public function makeUnknownGamePage(){
		$this->title='Jeu inconnu';
		$this->content='Ce jeu n est pas dispo';
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
		$string="<div id='listeGame'>";
		foreach ($tabGames as $key => $value) {
			$string.='<div class="ligne"> <img src="'.$value->getImg().'"> <p>'.$value->getNom().' </p>   <a href="'.$this->router->getGameURL($key).'"> <button> detail </button> </a>  </div>';
		}
		$string.=' </div>';
		$this->content=$string;
	}

	public function makeMyListPage($tabGames){
		$this->title='Mes annonces';
		if (empty($tabGames)) {
			$string="<div>";
			$string.="<p id='ai'> Vous n'avez pas encore ajouté(e) des annonces. </p>";
			$string.='</div>';
		}else{
		$string="<div id='listeGame'>";
		foreach ($tabGames as $key => $value) {
			$string.='<div class="ligne2"> <img src="'.$value->getImg().'"> <p>'.$value->getNom().' </p>   <a href="'.$this->router->getGameURL($key).'"> <button> detail </button> </a> <a href="'.$this->router->getGameEditURL($key).'"> <button> Modifier </button> </a> <a href="'.$this->router->getGameAskDeletionURL($key).'"> <button> Supprimer </button> </a>  </div>';
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
		$this->title='Ajouter une annonce';
		$string="";
		$string.="<form class='addAnn' method='post' enctype='multipart/form-data'  action='".$this->router->getGameSaveURL()."'>";
		$string.="Nom : <input type='text' name='nom' value=".$nom."> ".$nomE." <br>";
		$string.="Type : <input type='text' name='type' value=".$type."> ".$typeE." <br>";
		$string.="Num : <input type='number' name='disc' value=".$disc."> ".$discE." <br>";
		$string.="Prix : <input type='number' name='prix' value=".$prix."> ".$prixE." <br>";
		$string.="Image : <input type='file' name='img' value=".$img."> ".$imgE." <br>";
		$string.="<input class='addd' type='submit' value='Créer'> <br>";
		$string.="</form>";
		///////////
		// $string.="<form class='addAnn' method='post' enctype='multipart/form-data'  action='".$this->router->getGameSaveURL()."'>";
		// $string.="<div class='contenu'> <div> <p> Nom : </p> <p> Type : </p> <p> Num : </p> <p> Prix : </p> <p> Image : </p> </div>";
		// $string.="<div> <input type='text' name='nom' value=".$nom."> <input type='text' name='type' value=".$type."> <input type='text' name='disc' value=".$disc.">  <input type='number' name='prix' value=".$prix."> <input type='file' name='img' value=".$img."> </div>";
		// $string.="<div> <p> ".$nomE."  </p> <p> ".$typeE."  </p> <p> ".$discE."  </p> <p> ".$prixE."  </p> <p> ".$imgE."  </p> </div> </div>";
		// $string.="<input class='addd' type='submit' value='Créer'> <br>";
		// $string.="</form>";
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
		$string.="<form method='post' class='addAnn' enctype='multipart/form-data' action='".$this->router->getGameSaveEditURL($id)."'>";
		$string.="Nom : <input type='text' name='nom' value=".$nom."> ".$nomE." <br>";
		$string.="Type : <input type='text' name='type' value=".$type."> ".$typeE." <br>";
		$string.="Num : <input type='number' name='disc' value=".$disc."> ".$discE." <br>";
		$string.="Prix : <input type='number' name='prix' value=".$prix."> ".$prixE." <br>";
		$string.="Image : <input type='file' name='img' value=".$img."> ".$imgE." <br>";
		$string.="<input class='addd' type='submit' value='Modifier'> <br>";
		$string.="</form>";
		$this->content=$string;
	}


	 public function makeGameCreatedPage($id) {
		$this->router->POSTredirect($this->router->getGameURL($id), "Le jeu a bien été créée !");
	}

	public function makeGameNotCreatedPage() {
		$this->router->POSTredirect($this->router->getGameCreationURL(), "Erreur dans le formulaire !");
	}

	public function makeGameEditedPage($id) {
		$this->router->POSTredirect($this->router->getGameURL($id), "Le jeu a bien été modifié !");
	}

	public function makeGameNotEditedPage($id) {
		$this->router->POSTredirect($this->router->getGameEditURL($id), "Erreur dans le formulaire !");
	}

	public function makeGameDeletedPage() {
		$this->router->POSTredirect($this->router->getMyList(), "Le jeu a bien été supprimé");
	}

	public function makeUserConnectedPage() {
		$this->router->POSTredirect($this->router->getListURL(), "Vous etes connecté !");
	}

	public function makeUserNotConnectedPage() {
		$this->router->POSTredirect($this->router->getLoginURL(), "Connexion echoué !");
	}

	public function makeDeconnexionPage() {
		$this->router->POSTredirect($this->router->getListURL(), "Au revoir !");
	}

	public function makeGameDeletionPage($id){
		$string="<form method='post' class='DLT' action='".$this->router->getGameDeletionURL($id)."''>";
		$string.=" <p> Voulez vous vraiment supprimer ce jeu ?</p>";
		$string.=" Oui ? <input type='radio' name='ouiNon' value='oui'> <br>";
		$string.=" Non ? <input type='radio' name='ouiNon' value='non'> <br>";
		$string.="<input type='submit' value='confirmer'> <br>";
		$string.="</form>";
		$this->title="Supprimer?";
		$this->content=$string;
	}
}

 ?>