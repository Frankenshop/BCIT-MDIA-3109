<?php
include ("../db/portfolio_db.php");

class Portfolio {
	
	private $var;
	
	function portfolio_check(){
		$var = new Portfolio_db();
		return $var->get_portfolio();
	}
	
	function get_portfolios() {
		$var = new Portfolio_db();
		$portfolio = $var->get_all_portfolios();
		return $portfolio;
	}
}

?>
