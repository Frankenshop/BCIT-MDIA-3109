<?php

include("connect.php");

class Portfolio_db {
	private $portfolio;
	
	function get_all_portfolios(){
		global $con;
	
		$query = "SELECT * FROM portfolio";
		$result = mysqli_query($con, $query);
		$portfolios = array();
		
		while ($row = mysqli_fetch_array($result)) {
			$portfolioname[$row['id']] = $row['PortfolioName'];
		}
		
		return $portfolioname;
	}
	
	function get_portfolio($portfolio_id) {
		global $con;
	
		$query = "SELECT * FROM portfolio WHERE id = $portfolio_id";
		$result = mysqli_query($con, $query);
		
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$portfolios = array();
			$portfolio = array();
			$portfolio['PortfolioName'] = $row['PortfolioName'];
			$portfolio['Summary'] = $row['Summary'];
			return $portfolio;
		}
		return FALSE;	
	}
	
	function add_portfolio($name,$summary) {
		global $con;
		
		$query = "INSERT INTO portfolio VALUES(0,'$name','$summary')";
		$result = mysqli_query($con, $query);
		if ($result === TRUE)	
			return mysqli_insert_id($con);
		return FALSE;
	}
	
	function add_portfolio_for_user($name,$summary,$user_id) {
		global $con;
		
		$query = "INSERT INTO portfolio VALUES(0,'$name','$summary')";
		$result = mysqli_query($con, $query);
		if ($result === TRUE) {
			$id = mysqli_insert_id($con);
			$query = "INSERT INTO user_portfolio VALUES('$user_id','$id')";
			$result = mysqli_query($con, $query);
			if ($result === TRUE) {
				return $id;	
			}
		}
		return FALSE;
	}
	
	function remove_portfolio($portfolio_id) {
		global $con;
		
		$query = "DELETE FROM portfolio WHERE id = $portfolio_id";
		$result = mysqli_query($con, $query);	
		return $result;	
	}
	
	// change the title
	function change_name($portfolio_id,$name) {
		global $con;
		
		$query = "UPDATE portfolio SET PortfolioName = '$name' WHERE id = $portfolio_id";
		$result = mysqli_query($con, $query);	
		return $result;
	}
	
	function change_summary($portfolio_id,$summary) {
		global $con;
		
		$query = "UPDATE portfolio SET Summary = '$summary' WHERE id = $portfolio_id";
		$result = mysqli_query($con, $query);	
		return $result;
	}
	
	function get_user_portfolios($user_id) {
		global $con;
	
		$query = "SELECT * FROM user_portfolio WHERE user_id = $user_id";
		$result = mysqli_query($con, $query);
		$portfolios = array();
		
		while ($row = mysqli_fetch_array($result)) {
			
			$query = "SELECT * FROM portfolio WHERE id = " . $row['portfolio_id'];
			$portfolio_result = mysqli_query($con, $query);
			
			if (mysqli_num_rows($portfolio_result) > 0) {
				$portfolio_row = mysqli_fetch_array($portfolio_result);
				$portfolio = array();
				$portfolio['PortfolioName'] = $portfolio_row['PortfolioName'];
				$portfolio['Summary'] = $portfolio_row['Summary'];
				$portfolios[$portfolio_row['id']] = $portfolio;
			}
		}
		return $portfolios;
	}
	
	function get_user_collaborating_portfolios($user_id) {
		global $con;
	
		$query = "SELECT * FROM portfolio_collaborator WHERE user_id = $user_id";
		$result = mysqli_query($con, $query);
		$portfolios = array();
		
		while ($row = mysqli_fetch_array($result)) {
			
			$query = "SELECT * FROM portfolio WHERE id = " . $row['portfolio_id'];
			$portfolio_result = mysqli_query($con, $query);
			
			if (mysqli_num_rows($portfolio_result) > 0) {
				$portfolio_row = mysqli_fetch_array($portfolio_result);
				$portfolio = array();
				$portfolio['PortfolioName'] = $portfolio_row['PortfolioName'];
				$portfolio['Summary'] = $portfolio_row['Summary'];
				$portfolios[$portfolio_row['id']] = $portfolio;
			}
		}
		return $portfolios;
	}
}

?>
