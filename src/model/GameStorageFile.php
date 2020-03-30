<?php 
/**
 * 
 */

require_once("lib/ObjectFileDB.php");

class GameStorageFile implements GameStorage {

	private $db;
	private $list;
	
	function __construct($file)
	{
		$this->db = new ObjectFileDB($file);
		$this->list=array(
    'medor' => new Game('Medor','chien','rottweiller','50 EUR'),
    'felix' => new Game('felix','chat','grand taille','20 EUR'),
    'denver' => new Game('denver','dinosore','lalala','10000 EUR'),
);
    }

	public function reinit(){
		$this->deleteAll();
		foreach ($this->list as $key => $value) {
			$this->create($value);
		}
	}

	public function read($id) {
        if ($this->db->exists($id)) {
            return $this->db->fetch($id);
        } else {
            return null;
        }
    }

    public function readAll() {
        return $this->db->fetchAll();
    }

	public function create(Game $c) {
        return $this->db->insert($c);
    }

    public function update($id, Game $c) {
        if ($this->db->exists($id)) {
            $this->db->update($id, $c);
            return true;
        }
        return false;
    }

    public function delete($id) {
        if ($this->db->exists($id)) {
            $this->db->delete($id);
            return true;
        }
        return false;
    }

	public function deleteAll() {
        $this->db->deleteAll();
    }
}





 ?>