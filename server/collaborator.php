<?php
include ("../db/collaborator_db.php");

class Collaborator {
	
	private $var;
	
	function collaborator_check(){
		$var = new Collaborator_db();
		return $var->get_collaborator();
	}
	
	function get_collaborators() {
		$var = new Collaborator_db();
		$collaborator = $var->get_all_collaborators();
		return $collaborator;
	}
}

?>
