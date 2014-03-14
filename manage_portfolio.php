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
	var display_portfolio = function() {
		$.get("server/user_view.php", {get_user_id:true}, function(data){
			var id = $.parseJSON(data);
			if (id == -1)
				console.log("No user is currently logged in.");
			else {
				$.get("server/portfolio_view.php", {get_user_portfolios:true, user_id: id}, function(data){
					var portfolios = $.parseJSON(data);
					console.log(portfolios);
					if (portfolios.length > 0)
						console.log(portfolios);
					else
						console.log("No portfolios.");
				});
			}
		});
	};
	
	$.get("server/session.php", {is_loggedin: true}, function(data) {
		var success = $.parseJSON(data);
		if (success === true) {
			display_portfolio();
		}
		else {
			$("#login-widget").login({cancel_page:"index.php"}).on( "login", function( event) { display_portfolio(); });
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
    
    <br />
    
    <p> Boop </p>
    
    </div> <!-- Closes third -->
    
    

   
    </div> <!-- closes content-->
    
</div><!-- closes container-->

</div> <!-- closes wrapper-->

</div> <!-- closes repeattop-->

<div id="login-widget"> </div>

            
</body>
</html>
