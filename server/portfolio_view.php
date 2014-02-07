<?php

include ("portfolio.php");

$portfolio = new Portfolio();

if (isset($_GET['portfolio'])){
	echo $portfolio->portfolio_check();	
}


if (isset($_GET['portfolioname'])) {
	echo json_encode($portfolio->get_portfolios());	
}
?>
