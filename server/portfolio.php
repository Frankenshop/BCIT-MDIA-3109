<?php
include ("../db/portfolio_db.php");

class Portfolio {
	
	// the database
	private $db = NULL;
	
	// the current portfolio
	private $portfolio = -1;
	
	function __construct() {
		$this->db = new Portfolio_db();
	}
	
	function get_all_portfolios(){
		return $this->db->get_all_portfolios();
	}
	
	function get_info($portfolio_id) {
		return $this->db->get_portfolio($portfolio_id);
	}
	
	function get_info_current_portfolio() {
		if ($this->portfolio == -1)
			return FLASE;
		return $this->db->get_portfolio($this->portfolio);
	}
	
	function create_portfolio($name,$summary) {
		return $this->db->add_portfolio($name,$summary);
	}
	
	function create_portfolio_for_user($name,$summary,$user_id) {
		return $this->db->add_portfolio_for_user($name,$summary,$user_id);
	}
	
	function delete_portfolio($portfolio_id) {
		if ($portfolio_id == $this->portfolio)
			$this->portfolio = -1;
		return $this->db->remove_portfolio($this->portfolio);	
	}
	
	function delete_current_portfolio() {
		if ($this->portfolio != -1) {
			$id = $this->db->remove_portfolio($this->portfolio);	
			$this->portfolio = -1;	
			return $id;
		}
		return FALSE;
	}
	
	function add_collaborating_portfolio_for_user($portfolio_id,$user_id) {
		return $this->db->add_collaborating_portfolio_for_user($portfolio_id, $user_id);
	}
	
	function add_collaborator_for_current_portfolio($user_id) {
		return $this->db->add_collaborating_portfolio_for_user($this->portfolio, $user_id);
	}
	
	function remove_collaborating_portfolio_for_user($portfolio_id, $user_id) {
		return $this->db->remove_collaborating_portfolio($portfolio_id, $user_id);
	}
	
	function remove_collaborating_portfolio_for_current_portfolio($user_id) {
		return $this->db->remove_collaborating_portfolio($this->portfolio, $user_id);
	}
	
	function change_name($portfolio_id,$name) {
		return $this->db->change_name($portfolio_id,$name);
	}
	
	function change_name_current_portfolio($name) {
		if ($this->portfolio == -1)
			return FALSE;
		return $this->db->change_name($this->portfolio,$name);
	}
	
	function change_summary($portfolio_id,$summary) {
		return $this->db->change_summary($portfolio_id,$summary);
	}
	
	function change_summary_current_portfolio($summary) {
		if ($this->portfolio == -1)
			return FALSE;
		return $this->db->change_summary($this->portfolio,$summary);
	}
	
	function get_user_portfolios($user_id) {
		return $this->db->get_user_portfolios($user_id);
	}
	
	function get_user_is_portfolio_owner($user_id, $portfolio_id) {
		$portfolios = $this->db->get_user_portfolios($user_id);
		if (count($portfolios) <= 0)
			return FALSE;
		$portfolio_keys = array_keys($portfolios);
		foreach ($portfolio_keys as $key) {
			if ($key == $portfolio_id)
				return TRUE;
		}
		return FALSE;
	}
	
	function get_collaborating_users($portfolio_id) {
		return $this->db->get_collaborating_users($portfolio_id);
	}
	
	function get_user_collaborating_portfolios($user_id) {
		return $this->db->get_user_collaborating_portfolios($user_id);
	}
}

?>
