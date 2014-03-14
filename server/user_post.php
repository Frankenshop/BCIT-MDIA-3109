<?php

include ("user.php");
include ("session.php");

// create the user
$user = new User();
if (isset($_SESSION['current_user']))
	$user->set_user_id(intval($_SESSION['current_user']));

if (isset($_POST['create_user']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['status'])) {
	echo json_encode($user->create_user($_POST['username'],$_POST['password'],$_POST['status']));
}
if (isset($_POST['delete_user'])) {
	echo json_encode($user->delete_current_user());	
}
if (isset($_POST['change_password']) && isset($_POST['password'])) {
	echo json_encode($user->change_password_current_user($_POST['password']));
}	
?>
