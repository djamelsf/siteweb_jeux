<?php 
/**
 * 
 */
class Controller {

	private $view;
	private $data;
	private $GameStorage;
	public $currentGameBuilder;
	public $modifiedGameBuilders;
    public $AuthenticationManager;
    public $currentAccountBuilder;
    public $AccountStorage;
	
	function __construct($view,GameStorage $GameStorage,$AuthenticationManager,$AccountStorage)
	{
		$this->view=$view;
		//readAll() / hydrate()
		//$this->data=$GameStorage->hydrate();
		$this->data=$GameStorage->readAll();
		$this->GameStorage=$GameStorage;
        $this->AuthenticationManager=$AuthenticationManager;
        $this->AccountStorage=$AccountStorage;
        $this->currentAccountBuilder= key_exists('currentAccountBuilder', $_SESSION) ? $_SESSION['currentAccountBuilder'] : null;
		$this->currentGameBuilder = key_exists('currentGameBuilder', $_SESSION) ? $_SESSION['currentGameBuilder'] : null;
		$this->modifiedGameBuilders = key_exists('modifiedGameBuilders', $_SESSION) ? $_SESSION['modifiedGameBuilders'] : array();
	}

	public function __destruct() {
		$_SESSION['currentGameBuilder'] = $this->currentGameBuilder;
		$_SESSION['modifiedGameBuilders'] = $this->modifiedGameBuilders;
        $_SESSION['currentAccountBuilder']= $this->currentAccountBuilder;
	}

	public function showInformation($id) {
		$bool=0;
		foreach ($this->data as $key => $value) {
			if ($key==$id) {
                $t=$this->GameStorage->getUser($id);

				$this->view->makeGamePage($value,$id,$t);
				//$this->view->makeDebugPage($t);
				$bool=1;
			}
		}
		if ($bool===0) {
			$this->view->makeUnknownGamePage();
		}
        
    }

    public function showList(){
    	//$this->view->makeDebugPage($this->data);
    	//$this->currentGameBuilder = null;
    	$this->view->makeListPage($this->data);
    }

    public function showMylist(){
        $data2=$this->GameStorage->readByUser($_SESSION['user']->getLogin());
        $this->view->makeMyListPage($data2);
    }

    public function newGame() {
		/* Affichage du formulaire de création
		* avec les données par défaut. */
		if ($this->currentGameBuilder === null) {
			$this->currentGameBuilder = new GameBuilder(null);
		}
		$this->view->makeGameCreationPage($this->currentGameBuilder);
        
	}

    public function saveNewGame(array $data){
            
     
    $this->currentGameBuilder=new GameBuilder($data);
        if ($this->currentGameBuilder->isValid()) {
    	$game=$this->currentGameBuilder->createGame();
    	$id=$this->GameStorage->create($game,$_SESSION['user']->getLogin());
    	//$this->view->makeGamePage($game);
    	$this->currentGameBuilder = null;
        
    	$this->view->makeGameCreatedPage($id);
    }else{
    	//$this->view->makeGameCreationPage($GameBuilder);
    	$this->view->makeGameNotCreatedPage();
    }
    }

    public function EditNewGame(array $data,$id){
       
    if($this->GameStorage->existsGame($id,$_SESSION['user']->getLogin())){
            ////////////
            if ($this->GameStorage->read($id)===null) {
            $this->view->makeUnknownGamePage();
        }else{
            $Builder=new GameBuilder($data);
            if ($Builder->isValid()) {
                $game=$Builder->createGame();
                $this->GameStorage->update($id,$game);
                 unset($this->modifiedGameBuilders[$id]);
                 $this->view->makeGameEditedPage($id);
            }else{
                $this->modifiedGameBuilders[$id] = $Builder;
                $this->view->makeGameNotEditedPage($id);
            }

        }

     }else{
        $this->view->makeDeniedPage();
     }
    }

    public function askGameDeletion($id){
        if($this->GameStorage->existsGame($id,$_SESSION['user']->getLogin())){
            $game=$this->GameStorage->read($id);
        if ($game) {
            $this->view->makeGameDeletionPage($id);
        }else{
            $this->view->makeUnknownGamePage();
        }

        }else{
            $this->view->makeDeniedPage();
        }
    }


    public function deleteGame($id){
        if($this->GameStorage->existsGame($id,$_SESSION['user']->getLogin())){
            if ($this->GameStorage->delete($id)) {
            $this->view->makeGameDeletedPage();
            }
        }else{
            $this->view->makeDeniedPage();
        }
    }

    public function modifierGame($id){
  if($this->GameStorage->existsGame($id,$_SESSION['user']->getLogin())){
        /////////////
                if (key_exists($id, $this->modifiedGameBuilders)) {
            $this->view->makeGameModificationPage($this->modifiedGameBuilders[$id],$id);
        }else{
            $data=$this->GameStorage->read($id);
             if ($data) {
             $tab=$this->transformData($data,$id);
             $currentGameBuilder= new GameBuilder($tab);
             $this->view->makeGameModificationPage($currentGameBuilder,$id);
             }

        } 

    }else{
        $this->view->makeDeniedPage();
    }
    }

    public function transformData($data,$id){
    	$tab=[];
    	$game=$data[$id];
        $tab['nom']=$game->getNom();
        $tab['type']=$game->getType();
        $tab['disc']=$game->getDisc();
        $tab['prix']=$game->getPrix();
        $tab['img']=$game->getImg();
        return $tab;
    }

    public function MakeLoginPage(){
        $this->view->makeLoginFormPage();
    }

    public function checkConnection(array $data){
        $b=$this->AuthenticationManager->connectUser($data['login'], $data['mdp']);
        if ($b) {
            $this->view->makeUserConnectedPage();
        }else{
            $this->view->makeUserNotConnectedPage();
        }
    }

    public function newAccount(){

        if ($this->currentAccountBuilder === null) {
            $this->currentAccountBuilder = new AccountBuilder(null);
        }
        $this->view->makeInscriptionPageUser($this->currentAccountBuilder);
    }

    public function saveNewAccount(array $data){
        $this->currentAccountBuilder=new AccountBuilder($data);
        if($this->currentAccountBuilder->isValid()){
            if ($this->AccountStorage->exists($data['login'])) {
                $this->view->alreadyExistAccount();
            }else{
                //
                  $account=$this->currentAccountBuilder->createAccount();
                  $id=$this->AccountStorage->create($account);

                  $this->currentAccountBuilder = null;
        
                  $this->view->makeAccountCreatedPage();
                //
            }
        }else{
            $this->view->makeNotCreatedPage();
        }
        
    
    }

    public function deconnexion(){

            $this->AuthenticationManager->disconnectUser();
        $this->view->makeDeconnexionPage();
    }











}
 ?>