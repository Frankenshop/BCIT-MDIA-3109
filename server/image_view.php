<?php

include ("image.php");

$image = new Image();

if (isset($_GET['image'])){
	echo $image->image_check();	
}


if (isset($_GET['imagename'])) {
	echo json_encode($image->get_images());	
}
?>
