<?php

include ("picture.php");

$picture = new Picture();

if (isset($_GET['get_splash_picture_portfolio']) && isset($_GET['portfolio_id'])) {
	echo json_encode($picture->get_splash_picture($_GET['portfolio_id']));
}

if (isset($_GET['get_pictures_for_portfolio']) && isset($_GET['portfolio_id'])) {
	echo json_encode($picture->get_portfolio_pictures($_GET['portfolio_id']));
}

?>
