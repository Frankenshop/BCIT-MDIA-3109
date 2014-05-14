<?php
//Where the database/functions connector will go
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="CSS/styles.css" rel="stylesheet" type="text/css" media="screen"/>
    <title>Folio2Folio</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="js/login-widget.js"></script>
    <script src="js/signup-widget.js"></script>
    
</head>

<script>
$(document).ready(function(){
	$.get("server/session.php", {is_loggedin: true}, function(data) {
		var success = $.parseJSON(data);
		if (success === true) {
			var redirect = $("<form action='manage_portfolio.php' method='post'>").submit();
		}
		else {
			$("#signup-widget").signup();
		}
	});
});

</script>


<body>


<?php
include('Includes/inc_nav.php');
?>
	<div id="content">

    
    <div id="second">
    
    	<div id="signup-widget"></div>
    
    </div><!-- Closes second -->
    
    
    <div id="third">
    	
    
    </div> <!-- Closes third -->
    
    

   
    </div> <!-- closes content-->
    
</div><!-- closes container-->

</div> <!-- closes wrapper-->

</div> <!-- closes repeattop-->

<div id="login-widget"> </div>

            
</body>
</html>
