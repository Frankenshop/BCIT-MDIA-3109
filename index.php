<?php
//Where the database/functions connector will go
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="CSS/styles.css" rel="stylesheet" type="text/css" media="screen"/>
    <title>Folio2Folio</title>
    <script src=
    "http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js">
    </script>
</head>

<script>

$(document).ready(function(){
	$.get("server/user_view.php", {user:true}, function(data){
		//do something with the data
		//console.log(data);
	});

	$.get("server/user_view.php", {username:true}, function(data){
		var names = $.parseJSON(data);
		console.log(names);
	});
	
	$.get("server/portfolio_view.php", {portfolioname:true}, function(data){

		var portfolios = $.parseJSON(data);	
		console.log(portfolios);
	});
});

</script>


<body>


<?php
include('Includes/inc_nav.php');
?>
	<div id="content">

    
    <div id="second">
    
    
    <p> Boop </p>
    
    <br />
    
    <p> Boop </p>
    
    <br />
    
    <p> Boop </p>
    
    <br />
    
    </div><!-- Closes second -->
    
    
    <div id="third">
    
    <br />
    
    <p> Boop </p>
    
    <br />
    
    <p> Boop </p>
    
    <br />
    
    <p> Boop </p>
    
    <br />
    
    
    </div> <!-- Closes third -->
    
    

   
    </div> <!-- closes content-->
    
</div><!-- closes container-->

</div> <!-- closes wrapper-->

</div> <!-- closes repeattop-->


            
            
</body>
</html>
