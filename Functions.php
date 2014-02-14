<?php

// the current mysql connection
$mysql = NULL;

// must be called at the top of the index page before any headers are sent
function Initialize() {
	session_start();
}

function Connect() {
	global $mysql;
	
	// Kill an existing connection
	if ($mysql != NULL)
		mysql_close($mysql);
	
	// Connect to mysql
	$mysql = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_USER_PASSWORD);
	if (!$mysql) 
    	die('Could not connect: ' . mysql_error());
	
	// Connect to database
	$db = mysql_select_db(DATABASE_TABLE_NAME, $mysql);
	if (!$db) 
		die ('Could not connect to database "'.DATABASE_TABLE_NAME.'": ' . mysql_error());
}


// returns true if the user was created or false if the username was taken
function CreateUser($username, $password) {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, or call Connect() prior to calling CreateUser()');
	
	// check for existing username
	$query = "SELECT * FROM Users WHERE Username = '$username'";
	$rows = mysql_query( $query, $mysql );
	if (mysql_num_rows($rows) > 0)
		return false;
	
	// make sure we dont use reserved characters
	if (strpos($username,"fbuser_"))
		return false;
	
	// insert the user	
	$query = "INSERT INTO Users VALUES('$username','$password')";
	mysql_query( $query, $mysql );
	
	// check for errors
	if (mysql_error() != '') {
		echo mysql_error();
		die ('The current mysql connection does not have the permissions to create a user');
	}
	
	// insert the portfolio	
	$query = "INSERT INTO Portfolios VALUES('$username')";
	mysql_query( $query, $mysql );
	


function UpdateUser($user_name) {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, call Connect() or ConnectAdmin() prior to calling UpdateUser()');
	
	// attempt to update the file
	$query = "UPDATE Users SET FirstName = '$first_name', LastName = '$last_name', Email = '$email', Address = '$address', Province = '$province', City = '$city' WHERE Username = '$user_name'";
	$rows = mysql_query( $query, $mysql );
	
	// check for errors
	if (mysql_error() != '') {
		die ('The current mysql connection does not have the permissions to update a user');
	}
}

// returns false if the username or password is wrong
function LoginUser($username, $password) {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, call Connect() or ConnectAdmin() prior to calling LoginUser()');
	
	// activate a user
	$query = "SELECT * FROM Users WHERE Username = '$username' AND Password = '$password'";
	$rows = mysql_query( $query, $mysql );
	// check to see if we logged in
	if (mysql_num_rows($rows) == 1) {
		$row = mysql_fetch_assoc($rows);
		// make sure we are verified
		if ($row["IsActive"] == "0")
			return false;
		$_SESSION["logged_in_user"] = $username;
		$_SESSION["auth"] = intval($row["IsAdmin"]);	
		return true;
	}
	else 
		return false;
}


function LogoutUser() {
	$_SESSION["logged_in_user"] = NULL;
	$_SESSION["auth"] = NULL;	
}

function DeleteAsset($file_name) {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, call Connect() or ConnectAdmin() prior to calling DeleteAsset()');
	
	// attempt to update the file
	$query = "DELETE FROM Assets WHERE FileName = '{$_SESSION['logged_in_user']}_$file_name'";
	$rows = mysql_query( $query, $mysql );
	
	// check for errors
	if (mysql_error() != '') {
		die ('The current mysql connection does not have the permissions to update an asset');
	}
}

function GetUserForAsset($file_name) {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, call Connect() or ConnectAdmin() prior to calling UpdateAsset()');
	
	// attempt to find the file
	$query = "SELECT * FROM Assets WHERE FileName = '$file_name'";
	$rows = mysql_query( $query, $mysql );
	
	// if there is a row
	if (mysql_num_rows($rows) > 0) {
		$row = mysql_fetch_assoc($rows);
		return $row['Username'];
	}
	return false;
}

function UpdateAsset($file_name, $file_title, $file_description) {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, call Connect() or ConnectAdmin() prior to calling UpdateAsset()');
	
	// attempt to update the file
	$query = "UPDATE Assets SET Title = '$file_title', Description = '$file_description' WHERE FileName = '{$_SESSION['logged_in_user']}_$file_name'";
	$rows = mysql_query( $query, $mysql );
	
	// check for errors
	if (mysql_error() != '') {
		die ('The current mysql connection does not have the permissions to update an asset');
	}
}

// gets the display picture for the user
function GetDisplayPicture() {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, call Connect() or ConnectAdmin() prior to calling GetDisplayPicture()');
	
	// make sure a user is logged in
	if (!IsUserLoggedIn())
		return false;
		
	// attempt to find the file
	$query = "SELECT Username, PictureLink FROM Portfolios WHERE Username = '{$_SESSION['logged_in_user']}'";
	$rows = mysql_query( $query, $mysql );
	echo mysql_error();
	if (mysql_num_rows($rows) > 0) {
		$row = mysql_fetch_assoc($rows);
		if (!empty($row['PictureLink']) && $row['PictureLink'] != "NULL")
			return $row['PictureLink'];
	}
	return false;
}



// this will return the name of the file if the file was uploaded or false if there was an error
function UploadDisplayPicture($data) {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, call Connect() or ConnectAdmin() prior to calling UploadFile()');
	
	// make sure a user is logged in
	if (!IsUserLoggedIn())
		return false;
	
	// make sure the file doesn't exist
	if (($oldPicture = GetDisplayPicture()) !== false)
		@unlink(DISPLAY_LOCAL . $oldPicture);
		
	// create a unique file name
	$filename = $_SESSION['logged_in_user']."_".$data['name'];
	
	// attempt to upload the file
	if (move_uploaded_file($data['tmp_name'], DISPLAY_LOCAL . $filename) == TRUE) {
		$query = "UPDATE Portfolios SET PictureLink = '$filename' WHERE Username = '{$_SESSION['logged_in_user']}'";
		mysql_query( $query, $mysql );
		return $filename;	
	}
    
	// if we got here, something bad happened
	return false;
}

// this will return the name of the file if the file was uploaded or false if there was an error
function UploadFile($data) {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, call Connect() or ConnectAdmin() prior to calling UploadFile()');
	
	// make sure a user is logged in
	if (!IsUserLoggedIn())
		return false;
	
	// make sure the file doesn't exist
	if (HasFile($data['name']))
		return false;
		
	// create a unique file name
	$filename = $_SESSION['logged_in_user']."_".$data['name'];
	
	// attempt to upload the file
	if (move_uploaded_file($data['tmp_name'], UPLOADS_LOCAL . $filename) == TRUE) {
		
		// attempt to create an asset for this user
		$query = "INSERT INTO Assets VALUES(0,'{$_SESSION['logged_in_user']}',CURDATE(), CURTIME(),'$filename',NULL,NULL)";
		$rows = mysql_query( $query, $mysql );
		
		// if there was an error, delete the file
		if (mysql_error() != "") {
			unlink(UPLOADS_LOCAL . $filename);
			echo mysql_error();
			$query = "DELETE FROM Assets WHERE Username = '{$_SESSION['logged_in_user']}' AND FileName = '$filename'";
			mysql_query( $query, $mysql );
			die('Mysql connection does not have the correct permissions to upload a file, call ConnectAdmin()');
		}
		return $filename;	
	}
    
	// if we got here, something bad happened
	return false;
}

// portfolio function
function GetPortfolio($username) {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, call Connect() or ConnectAdmin() prior to calling GetPortfolio()');
		
	// get the portfolio items
	$query = "SELECT * FROM Portfolios WHERE Username = '$username'";
	$rows = mysql_query( $query, $mysql );
	
	// do some error checking 
	if (mysql_num_rows($rows) == 0)
		return NULL;
		
	// get the row
	$row = mysql_fetch_assoc($rows);
	
	// get the users info
	$query = "SELECT * FROM Users WHERE Username = '$username'";
	$rows = mysql_query( $query, $mysql );
	
	// do some error checking 
	if (mysql_num_rows($rows) == 0)
		die('Something really strange has happened');
		
	// get the row
	$user_row = mysql_fetch_assoc($rows);
	
	// get the assets
	$query = "SELECT * FROM Assets WHERE Username = '$username' ORDER BY ADate, ATime DESC";
	$rows = mysql_query( $query, $mysql );
	
	// convert the assets to useable data
	$assets = array();
	while ($asset_row = mysql_fetch_assoc($rows)) {
		array_push($assets, array( 	'file_name' => $asset_row['FileName'] == NULL ? "" : str_replace("{$username}_", "", $asset_row['FileName']), 
									'title' => $asset_row['Title'] == NULL ? "" : $asset_row['Title'],
									'description' => $asset_row['Description'] == NULL ? "" : $asset_row['Description']));
	}
	
	// return an array
	return array(	'username' => $row['Username'],
					'first_name' => $user_row['FirstName'],
					'last_name' => $user_row['LastName'],
					'email' => $user_row['Email'],
					'address' => $user_row['Address'],
					'city' => $user_row['City'],
					'province' => $user_row['Province'],
					'picture_url' => $row['PictureLink'],
					'portfolio_name' => $row['PortfolioName'] == NULL ? "" : $row['PortfolioName'],
					'summary' => $row['Summary'] == NULL ? "" : $row['Summary'],
					'education' => $row['Education'] == NULL ? "" : $row['Education'],
 					'assets' => $assets );
}

function UpdatePortfolio($user_name, $portfolio_name, $portfolio_education, $portfolio_summary) {
	global $mysql;
	
	// do some error checking
	if ($mysql == NULL)
		die ('No mysql connection, call Connect() or ConnectAdmin() prior to calling UpdatePortfolio()');
	
	// attempt to update the file
	$query = "UPDATE Portfolios SET PortfolioName = '$portfolio_name', Education = '$portfolio_education', Summary = '$portfolio_summary' WHERE Username = '$user_name'";
	$rows = mysql_query( $query, $mysql );
	
	// check for errors
	if (mysql_error() != '') {
		die ('The current mysql connection does not have the permissions to update a portfolio');
	}
}

}
?>