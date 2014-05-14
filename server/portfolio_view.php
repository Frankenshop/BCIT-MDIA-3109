<?php

include ("portfolio.php");

$portfolio = new Portfolio();

if (isset($_GET['current_portfolio'])){
	echo json_encode($portfolio->get_info_current_portfolio());	
}

if (isset($_GET['get_portfolio']) && isset($_GET['portfolio_id'])) {
	echo json_encode($portfolio->get_info($_GET['portfolio_id']));
}

if (isset($_GET['get_user_portfolios']) && isset($_GET['user_id'])) {
	echo json_encode($portfolio->get_user_portfolios($_GET['user_id']));
}

if (isset($_GET['get_user_collaborating_portfolios']) && isset($_GET['user_id'])) {
	echo json_encode($portfolio->get_user_collaborating_portfolios($_GET['user_id']));
}

if (isset($_GET['get_collaborating_users']) && isset($_GET['portfolio_id'])) {
	echo json_encode($portfolio->get_collaborating_users($_GET['portfolio_id']));
}

if (isset($_GET['get_user_owns_portfolio']) && isset($_GET['user_id']) && isset($_GET['portfolio_id'])) {
	echo json_encode($portfolio->get_user_is_portfolio_owner($_GET['user_id'], $_GET['portfolio_id']));
}
?>
