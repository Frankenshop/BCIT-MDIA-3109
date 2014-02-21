<?php
include ("../db/image_db.php");

class Image {
	
	private $var;
	
	function image_check(){
		$var = new Image_db();
		return $var->get_image();
	}
	
	function get_images() {
		$var = new Image_db();
		$image = $var->get_all_images();
		return $image;
	}
}

?>
