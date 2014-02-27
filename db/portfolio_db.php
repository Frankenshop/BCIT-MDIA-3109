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
}

?>
