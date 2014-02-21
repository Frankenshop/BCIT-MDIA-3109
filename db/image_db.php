<?php

include("connect.php");

class Image_db {
	private $portfolio;
	
	
	
	function get_all_imagess(){
		global $con;
	
		$query = "SELECT * FROM images";
		$result = mysqli_query($con, $query);
		$images = array();
		
		while ($row = mysqli_fetch_array($result)) {
			$imagename[$row['id']] = $row['ImageName'];
		}
		
		return $imagename;
		
	}
}

?>
