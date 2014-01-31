<?php
include ("user.php");

/*if(isset($_GET['user'])){
	if(isset($_GET['user_id'])){
		$userClass = new User();
		$userClass->get_user_info($id);
		echo json_encode($user_info);
	}*/
	
	if(isset($_GET['user'])){
	if(isset($_GET['username']) && isset($_GET['password'])){
		$userClass = new User();
		$userClass->get_user_info($id);
		echo json_encode($user_info);
	}

	
}


?>