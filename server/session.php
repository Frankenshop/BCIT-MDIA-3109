<?php

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}	

if (isset($_POST['login']) && isset($_POST['user_id'])) {
	$_SESSION['current_user'] = $_POST['user_id'];
	echo json_encode(TRUE);
}

if (isset($_POST['logout'])) {
	unset($_SESSION['current_user']);
	echo json_encode(TRUE);
}

if (isset($_GET['is_loggedin'])) {
	echo json_encode(isset($_SESSION['current_user']));	
}

?>
