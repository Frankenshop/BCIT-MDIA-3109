<?php

include ("portfolio.php");

$portfolio = new Portfolio();

if (isset($_GET['current_portfolio'])){
	echo json_encode($portfolio->get_info_current_portfolio());	
}

if (isset($_GET['get_user_portfolios']) && isset($_GET['user_id'])) {
	echo json_encode($portfolio->get_user_portfolios($_GET['user_id']));
}
?>
