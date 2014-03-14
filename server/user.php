<?php
include ("../db/user_db.php");

class User {
	
	// the database
	private $db = NULL;
	
	// the current user
	private $user = -1;
	
	function __construct() {
		$this->db = new User_db();
	}
	
	function set_user_id($user_id) {
		$this->user = $user_id;	
	}
	
	function get_user_id_current_user() {
		return $this->user;	
	}
	
	function get_user_id($user_name) {
		$users = $this->db->get_user_by_name($user_name);
		if ($users !== FALSE && count($users) > 0)  {
			$keys = array_keys($users);
			return $keys[0];
		}
		return FALSE;
	}
	
	function get_all_users() {
		$usernames = $this->db->get_all_users();
		return $usernames;
	}
	
	function get_user_name_current_user() {
		if ($this->user == -1)
			return FALSE;
		return $this->get_user_name($this->user);	
	}
	
	function get_user_name($user_id) {
		$users = $this->db->get_user($user_id);
		if ($users !== FALSE && count($users) > 0)  {
			$keys = array_keys($users);
			$key = $keys[0];
			$user = $users[$key];
			return $user['username'];
		}
		return FALSE;
	}
	
	function get_user_status_current_user() {
		if ($this->user == -1)
			return FALSE;
		return $this->get_user_status($this->user);	
	}
	
	function get_user_status($user_id) {
		$users = $this->db->get_user($user_id);
		if ($users !== FALSE && count($users) > 0)  {
			$keys = array_keys($users);
			$key = $keys[0];
			$user = $users[$key];
			return $user['status'];
		}
		return FALSE;
	}
	
	function create_user($username, $password, $status) {
		return $this->db->add_user($username, $password, $status);
	}
	
	function delete_current_user() {
		if ($this->user != -1) {
			$id = $this->db->remove_user($this->user);	
			$this->user = -1;	
			return $id;
		}
		return FALSE;
	}
	
	function delete_user($user_id) {
		if ($user_id == $this->user)
			$this->user = -1;
		return $this->db->remove_user($user_id);	
		
	}
	
	function confirm_password_current_user($password) {
		if ($this->user == -1)
			return FALSE;
		return $this->db->check_password_matches($this->user, $password);
	}
	
	function confirm_password($user_id, $password) {
		return $this->db->check_password_matches($user_id, $password);
	}
	
	function change_password_current_user($password) {
		if ($this->user == -1)
			return FALSE;
		return $this->db->change_password($this->user, $password);
	}
	
	function change_password($user_id, $password) {
		return $this->db->change_password($user_id, $password);	
	}
}

?>
