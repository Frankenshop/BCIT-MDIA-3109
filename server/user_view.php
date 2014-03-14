<?php

include ("user.php");
include ("session.php");

// create the user
$user = new User();
if (isset($_SESSION['current_user']))
	$user->set_user_id(intval($_SESSION['current_user']));

if (isset($_GET['get_user'])){
	if (isset($_GET['user_id']))
		echo json_encode($user->get_user_name($_GET['user_id']));	
	else
		echo json_encode($user->get_user_name_current_user());	
}

if (isset($_GET['get_user_id'])) {
	if (isset($_GET['user_name']))
		echo json_encode($user->get_user_id($_GET['user_name']));
	else
		echo json_encode($user->get_user_id_current_user());
}

if (isset($_GET['get_all_users'])) {
	echo json_encode($user->get_all_users());	
}

if (isset($_GET['confirm_password'])) {
	if (isset($_GET['password'])) {
		if (isset($_GET['user_id']))
			echo json_encode($user->confirm_password($_GET['user_id'], $_GET['password']));	
		else
			echo json_encode($user->confirm_password_current_user($_GET['password']));	
	}
}

if (isset($_GET['get_status'])) {
	if (isset($_GET['user_id'])) {
		echo json_encode($user->get_user_status($_GET['user_id']));	
	}
	else
		echo json_encode($user->get_user_status_current_user());
}


?>
