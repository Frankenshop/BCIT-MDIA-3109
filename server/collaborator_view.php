<?php

include ("collaborator.php");

$collaborator = new Collaborator();

if (isset($_GET['collaborator'])){
	echo $collaborator->collaborator_check();	
}


if (isset($_GET['collaboratorname'])) {
	echo json_encode($collaborator->get_collaborators());	
}
?>
