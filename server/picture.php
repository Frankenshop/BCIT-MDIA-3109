<?php
include ("../db/picture_db.php");

class Picture {
	// the database
	private $db = NULL;
	
	function __construct() {
		$this->db = new Picture_db();
	}
	
	// get all known picutres
	function get_all_pictures(){
		return $this->db->get_all_pictures();
	}
	
	// get a single picture
	function get_picture($picture_id){
		return $this->db->get_picture($picture_id);
	}
	
	// add a picture
	function add_picture($link, $title, $description) {
		return $this->db->add_picture($link, $title, $description);
	}
	
	// link a pictrue to the splash
	function add_splash_picture($portfolio_id,$picture_id) {
		return $this->db->add_splash_picture($portfolio_id, $picture_id);
	}
	
	// link a pictrue to the splash
	function remove_splash_picture($portfolio_id) {
		return $this->db->remove_splash_picture($portfolio_id);
	}
	
	// link a pictrue to the portfolio
	function add_portfolio_picture($portfolio_id,$picture_id) {
		return $this->db->add_portfolio_picture($portfolio_id, $picture_id);	
	}
	
	// link a pictrue to the portfolio
	function remove_portfolio_picture($portfolio_id,$picture_id) {
		return $this->db->remove_portfolio_picture($portfolio_id, $picture_id);	
	}
	
	// remove a user
	function remove_picture($picture_id) {
		return $this->db->remove_picture($picture_id);
	}
	
	// change the title
	function change_title($picture_id,$title) {
		return $this->db->change_title($picture_id, $title);
	}
	
	// change the link
	function change_link($picture_id,$link) {
		return $this->db->change_link($picture_id, $link);
	}
	
	// change the description
	function change_description($picture_id,$description) {
		return $this->db->change_description($picture_id, $description);
	}
	
	// get the splash image for a portfolio
	function get_splash_picture($portfolio_id) {
		return $this->db->get_splash_picture($portfolio_id);
	}
	
	// get all known picutres for a portfolio
	function get_portfolio_pictures($portfolio_id){
		return $this->db->get_portfolio_pictures($portfolio_id);
	}
}

?>
