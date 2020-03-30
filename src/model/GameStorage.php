<?php 
interface GameStorage {
	
	public function read($id);
	public function readAll();
	public function create(Game $a,$user);
	public function delete($id);
	public function update($id, Game $c);
}
 ?>