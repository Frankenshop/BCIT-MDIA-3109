<?php
include("connect.php");

class Picture_db {
	// get all known picutres
	function get_all_pictures(){
		global $con;
	
		$query = "SELECT * FROM picture";
		$result = mysqli_query($con, $query);
		$pictures = array();
		
		while ($row = mysqli_fetch_array($result)) {
			$picture = array();
			$picture['link'] = $row['link'];
			$picture['title'] = $row['title'];
			$picture['description'] = $row['description'];
			$pictures[$row['id']] = $picture;
		}
		
		return $pictures;
	}
	
	// get a single picture
	function get_picture($picture_id){
		global $con;
	
		$query = "SELECT * FROM picture WHERE id = $picture_id";
		$result = mysqli_query($con, $query);
		
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$pictures = array();
			$picture = array();
			$picture['link'] = $row['link'];
			$picture['title'] = $row['title'];
			$picture['description'] = $row['description'];
			$pictures[$row['id']] = $picture;
			return $pictures;
		}
		return FALSE;
	}
	
	// add a picture
	function add_picture($link, $title, $description) {
		global $con;
		
		$query = "INSERT INTO picture VALUES(0,'$link','$title','$description')";
		$result = mysqli_query($con, $query);
		if ($result === TRUE)	
			return mysqli_insert_id($con);
		return FALSE;
	}
	
	// add a picture to the splash pictures
	function add_splash_picture($portfolio_id, $picture_id) {
		global $con;
		
		$query = "INSERT INTO portfolio_splash VALUES($portfolio_id, $picture_id)";
		$result = mysqli_query($con, $query);
		if ($result === TRUE)	
			return TRUE;
		return FALSE;
	}
	
	// remove a picture from the splash pictures
	function remove_splash_picture($portfolio_id) {
		global $con;
		
		$query = "DELETE FROM portfolio_splash WHERE portfolio_id = $portfolio_id";
		$result = mysqli_query($con, $query);
		return $result;
	}
	
	// add a picture to the portfolio pictures
	function add_portfolio_picture($portfolio_id, $picture_id) {
		global $con;
		
		$query = "INSERT INTO portfolio_picture VALUES($portfolio_id, $picture_id)";
		$result = mysqli_query($con, $query);
		if ($result === TRUE)	
			return TRUE;
		else {
			$query = "UPDATE portfolio_picture SET picture_id = $picture_id WHERE portfolio_id = $portfolio_id";
			$result = mysqli_query($con, $query);
			if ($result === TRUE)	
				return TRUE;
		}
		return FALSE;
	}
	
	// remove a picture from the splash pictures
	function remove_portfolio_picture($portfolio_id, $picture_id) {
		global $con;
		
		$query = "DELETE FROM portfolio_picture WHERE portfolio_id = $portfolio_id AND picture_id = $picture_id";
		$result = mysqli_query($con, $query);
		return $result;
	}
	
	// remove a picture
	function remove_picture($picture_id) {
		global $con;
		
		$query = "DELETE FROM picture WHERE id = $picture_id";
		$result = mysqli_query($con, $query);	
		return $result;
	}
	
	// change the title
	function change_title($picture_id,$title) {
		global $con;
		
		$query = "UPDATE picture SET title = '$title' WHERE id = $picture_id";
		$result = mysqli_query($con, $query);	
		return $result;
	}
	
	// change the link
	function change_link($picture_id,$link) {
		global $con;
		
		$query = "UPDATE picture SET link = '$link' WHERE id = $picture_id";
		$result = mysqli_query($con, $query);	
		return $result;
	}
	
	// change the description
	function change_description($picture_id,$description) {
		global $con;
		
		$query = "UPDATE picture SET description = '$description' WHERE id = $picture_id";
		$result = mysqli_query($con, $query);	
		return $result;
	}
	
	// get the splash image for a portfolio
	function get_splash_picture($portfolio_id) {
		global $con;
	
		$query = "SELECT * FROM portfolio_splash WHERE portfolio_id = $portfolio_id";
		$result = mysqli_query($con, $query);
		
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			return $this->get_picture($row['picture_id']);
		}
		return FALSE;
	}
	
	// get all known picutres for a portfolio
	function get_portfolio_pictures($portfolio_id){
		global $con;
	
		$query = "SELECT * FROM portfolio_picture WHERE portfolio_id = $portfolio_id";
		$result = mysqli_query($con, $query);
		$pictures = array();
		
		while ($row = mysqli_fetch_array($result)) {
			$query = "SELECT * FROM picture WHERE id = $portfolio_id";
			$picture_result = mysqli_query($con, $query);
			if (mysqli_num_rows($picture_result) > 0) {
				$picture_row = mysqli_fetch_array($picture_result);
				$picture = array();
				$picture['link'] = $picture_row['link'];
				$picture['title'] = $picture_row['title'];
				$picture['description'] = $picture_row['description'];
				$pictures[$picture_row['id']] = $picture;
			}
		}
		return $pictures;
	}
	
}

?>
