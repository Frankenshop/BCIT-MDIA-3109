<?php

include ("portfolio.php");

$portfolio = new Portfolio();

if (isset($_POST['create_portfolio']) && isset($_POST['portfolio_name']) && isset($_POST['portfolio_summary'])){
	if (isset($_POST['user_id']))
		echo json_encode($portfolio->create_portfolio_for_user($_POST['portfolio_name'],$_POST['portfolio_summary'],$_POST['user_id']));
	else
		echo json_encode($portfolio->create_portfolio($_POST['portfolio_name'],$_POST['portfolio_summary']));	
}

if (isset($_POST['change_description']) && isset($_POST['portfolio_id']) && isset($_POST['description'])){
	echo json_encode($portfolio->change_summary($_POST['portfolio_id'],$_POST['description']));		
}

if (isset($_POST['change_title']) && isset($_POST['portfolio_id']) && isset($_POST['title'])){
	echo json_encode($portfolio->change_name($_POST['portfolio_id'],$_POST['title']));		
}
?>

