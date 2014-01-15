<?php

include ("user.php");

$user = new User();

if (isset($_GET['user'])){
	echo $user->user_check();	
}


if (isset($_GET['username'])){
	echo json_encode($user->get_usernames());	
}

?>
