<?php

include("connect.php");

class Collaborator_db {
	private $portfolio;
	
	
	
	function get_all_collaborators(){
		global $con;
	
		$query = "SELECT * FROM collaborator";
		$result = mysqli_query($con, $query);
		$collaborators = array();
		
		while ($row = mysqli_fetch_array($result)) {
			$collaboratorname[$row['id']] = $row['CollaboratorName'];
		}
		
		return $collaboratorname;
		
	}
}

?>
