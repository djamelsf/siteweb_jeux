<?php 
/**
 * 
 */
class GameStorageStub implements GameStorage {
	
	protected $db;
	
	function __construct()
	{
		$this->db=array(
	'medor' => new Game('Medor','chien','rottweiller','50 EUR'),
	'felix' => new Game('felix','chat','grand taille','20 EUR'),
	'denver' => new Game('denver','dinosore','lalala','10000 EUR'),
);
    
	}

	public function read($id) {
		if (key_exists($id, $this->db)) {
			return $this->db[$id];
		}
		return null;
	}


	public function readAll() {
		return $this->db;
	}
	
}
 ?>