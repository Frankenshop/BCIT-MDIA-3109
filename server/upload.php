<?php

include("picture.php");
if (isset($_FILES['splash_picture']) && isset($_POST['portfolio_id'])) {
	upload_splash_picture($_FILES['splash_picture'], $_POST['portfolio_id']);
}

if (isset($_FILES['portfolio_picture']) && isset($_POST['portfolio_id']) && isset($_POST['picture_title']) && isset($_POST['picture_description'])) {
	upload_portfolio_picture($_FILES['portfolio_picture'], $_POST['portfolio_id'],$_POST['picture_title'],$_POST['picture_description']);
}

if (isset($_POST['redirect_url'])) {
	header( 'Location: ../'.$_POST['redirect_url'] ) ;
}
else {
	header( 'Location: ../index.php' );	
}

// attempt to upload a splash picture
function upload_splash_picture($data,$portfolio_id) {
	$portfolio = new Picture();
	
	// create a unique file name
	$filename = $portfolio_id."__".str_replace(" ","",$data['name']);
	
	
	// the link to check
	$link = "PictureSplash/" . $filename;
	
	// make sure the file doesn't exist
	if (($picture = $portfolio->get_splash_picture($portfolio_id)) !== FALSE) {
		$keys = array_keys($picture);
		$actual_picture = $picture[$keys[0]];
		@unlink("../".$actual_picture['link']);
		$portfolio->remove_splash_picture($portfolio_id);
		$portfolio->remove_picture($keys[0]);	
	}
		
	// attempt to upload the file
	if (move_uploaded_file($data['tmp_name'], "../".$link) == TRUE) {
		$picture_id = $portfolio->add_picture($link, "Splash Picture", "No Description");
		$portfolio->add_splash_picture($portfolio_id, $picture_id);
	}
}

// attempt to upload a portfolio picture
function upload_portfolio_picture($data,$portfolio_id,$title,$description) {
	// create a unique file name
	$filename = $portfolio_id."__".str_replace(" ","",$data['name']);
	
	// the link to check
	$link = "PicturePortfolio/" . $filename;
	
	// make sure the file doesn't exist
	if (file_exists ("../".$link))
		@unlink("../".$link);
	
	// attempt to upload the file
	if (move_uploaded_file($data['tmp_name'], "../".$link) == TRUE) {
		$portfolio = new Picture();
		$picture_id = $portfolio->add_picture($link, $title, $description);
		$portfolio->add_portfolio_picture($portfolio_id, $picture_id);
	}
}

?>