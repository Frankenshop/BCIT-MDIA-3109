<?php

include("connect.php");

class User_db {
	// get all known users
	function get_all_users(){
		global $con;
	
		$query = "SELECT * FROM user";
		$result = mysqli_query($con, $query);
		$username = array();
		
		while ($row = mysqli_fetch_array($result)) {
			$user = array();
			$user['username'] = $row['username'];
			$username[$row['id']] = $user;
		}
		
		return $username;
	}
	
	// get a single user
	function get_user($user_id) {
		global $con;
	
		$query = "SELECT * FROM user WHERE id = $user_id";
		$result = mysqli_query($con, $query);
		
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$username = array();
			$user = array();
			$user['username'] = $row['username'];
			$username[$row['id']] = $user;
			return $username;
		}
		return FALSE;
	}
	
	// add a new user
	function add_user($username, $password, $isAdmin) {
		global $con;
		
		$query = "INSERT INTO user VALUES(0,'$username','$password','$isAdmin')";
		$result = mysqli_query($con, $query);
		if ($result === TRUE)	
			return mysqli_insert_id($con);
		return FALSE;
	}
	
	// remove a user
	function remove_user($user_id) {
		global $con;
		
		$query = "DELETE FROM user WHERE id = $user_id";
		$result = mysqli_query($con, $query);	
		return $result;
	}
	
	// change a password
	function change_password($user_id, $password) {
		global $con;
		
		$query = "UPDATE user SET password = '$password' WHERE id = $user_id";
		$result = mysqli_query($con, $query);	
		return $result;
	}
}

?>
