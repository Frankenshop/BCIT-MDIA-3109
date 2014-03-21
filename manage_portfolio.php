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
    <script src="js/add-portfolio-widget.js"></script>
    <script src="js/functions.js"></script>
</head>

<script>
$(document).ready(function(){
	var display_single_portfolio = function(id, name, summary, stylenames, attachmentDiv, splashImage) {
		var div = $("<div class='portfolio " + stylenames + "'></div>");
		
		// the content
		var content = $("<div class='content'></div>");
		var name_element = $("<h2 class='portfolio-title'>" + name + "</h2>");
		var summary_element = $("<span class='portfolio-description'>" + summary + "</span>");
		if (splashImage === null) {
			var image = $("<img src='Images/defaultsplashthumb.png'/>");
			content.append(image);
		}
		content.append(name_element);
		content.append(summary_element);
		div.append(content);
		
		// the edit
		if(requested_user == "") {
			var edit = $("<div class='edit'></div>");
			var quick_edit_button = $("<button>Quick Edit...</button>");
			var edit_button = $("<button>Edit...</button>").on("click",function() {$("<form action='view_portfolio.php?portfolio="+id+"' method='post'>").submit()});	
			div.append(edit);
			edit.append(quick_edit_button);
			edit.append(edit_button);
			quick_edit_button.on("click", function() { 
				div.addportfolio({portfolio_id: id,edit: true, title:"Edit portfolio:", portfolio_name: name, summary: summary}).on("update", function (event,name,description) { 
					name_element.text(name); 
					summary_element.text(description); 
				});
			});
		}
		
		// attach the new portfolio
		attachmentDiv.append(div);
	};
	
	var display_all_portfolios = function(id) {
		// clear all old children
		$("#portfolio").empty();
		$("#collaborators-portfolio").empty();
		
		// get the users portfolios
		$.get("server/portfolio_view.php", {get_user_portfolios:true, user_id: id}, function(data){
			var portfolios = $.parseJSON(data);
			var keys = Object.keys(portfolios);
			if (keys.length > 0) {
				$.each( keys, function( index, value ){
					var styleString = index % 2 == 1 ? "owner odd" : "owner even";
					display_single_portfolio(keys[index], portfolios[value]['PortfolioName'], portfolios[value]['Summary'], styleString, $("#portfolio"), null);
				});
			}
			else {
				var text;
				if (requested_user == "")
					text = "You do not own any portfolios, click below to create one";
				else 
					text = requested_user + " does not own any portfolios";
				$("#portfolio").append($("<div class='portfolio'><div class='content full'><h2 id='portfolio-title'>"+text+"</h2></div></div>"));
			}
		});
		
		// get the collaborators portfolios
		$.get("server/portfolio_view.php", {get_user_collaborating_portfolios:true, user_id: id}, function(data){
			var portfolios = $.parseJSON(data);
			var keys = Object.keys(portfolios);
			if (keys.length > 0) {
				$.each( keys, function( index, value ){
					var styleString = index % 2 == 1 ? "collaborator odd" : "collaborator even";
					display_single_portfolio(keys[index], portfolios[value]['PortfolioName'], portfolios[value]['Summary'], styleString, $("#collaborators-portfolio"), null);
				});
			}
			else {
				var text;
				if (requested_user == "")
					text = "You have not been invited to collaborate on any portfolios";
				else 
					text = requested_user + " does not collaborate on any portfolios";
				$("#collaborators-portfolio").append($("<div class='portfolio'><div class='content full'><h2 id='portfolio-title'>"+text+"</h2></div></div>"));	
			}
		});
		
		// take the final steps in the loading process
		finalize_load();
	}
	
	// display all the portfolios for the current user
	var display_all_portfolios_current_user = function() {
		// get the id of the user
		$.get("server/user_view.php", {get_user_id:true}, function(data){
			var id = $.parseJSON(data);
			if (id == -1)
				console.log("No user is currently logged in.");
			else {
				display_all_portfolios(id);
			}
		});
	};

	// do a load for the current user
	var load_for_current_user = function() {
		$.get("server/session.php", {is_loggedin: true}, function(data) {
			var success = $.parseJSON(data);
			if (success === true) {
				display_all_portfolios_current_user();
			}
			else {
				$("#login-widget").login({cancel_page:"index.php"}).on( "login", function( event ) { display_all_portfolios_current_user(); });
			}
		});
	}
	
	// attempt to find a requested user
	var requested_user = window.get_parameter_by_name("user");
	
	// if there is a requested user, attempt to find the user's id.
	if (requested_user != "") {
		$.get("server/user_view.php", {get_user_id: true, user_name: requested_user}, function(data) {
			var id = $.parseJSON(data);
			if (id === false) {
				requested_user = "";
				load_for_current_user();	
			}
			else 
				display_all_portfolios(id);
		});
	}
	else {
		load_for_current_user();
	}
	
	// take the final steps in loading
	var finalize_load = function() {
		// add the "add-portfolio" div if needed
		if (requested_user == "") {
			// create the tree
			var add_portfolio = $("<div id = 'add-portfolio'></div>");
			var portfolio = $("<div class = 'portfolio'></div>");
			var content = $("<div class = 'content full'></div>");
			var title = $("<h2>Add new portfolio</h2>");
			var button = $("<button id = 'add-new-portfolio'>+</button>");
			$("#second").append(add_portfolio);
			add_portfolio.append(portfolio);
			portfolio.append(content);
			content.append(title);
			content.append(button);
			
			// add the listeners
			$("#add-new-portfolio").on( "click", function () {
				$("#add-portfolio").addportfolio();
			});
			
			$("#add-portfolio").on( "login", function( event, name, summary) { 
				var count = $("#portfolio").children().length;
				var styleString = count % 2 == 0 ? "owner odd" : "owner even";
				display_single_portfolio(name, summary, styleString, $("#portfolio"), null);
			});	
		}
		// otherwise, change the title
		else {
			$("#my_portfolio_title").text(requested_user+"'s Portfolios:");
		}
	}
});

</script>


<body>


<?php
include('Includes/inc_nav.php');
?>
	<div id="content">

    
    <div id="second">
    	<h1 id="my_portfolio_title"> My Portfolios: </h1>
    	<div id = "portfolio"> </div>
    </div><!-- Closes second -->
    
    
    <div id="third">
    	<h1> Collaborator's Portfolios: </h1>
    	<div id = "collaborators-portfolio"> </div>
    </div> <!-- Closes third -->
    
    

   
    </div> <!-- closes content-->
    
</div><!-- closes container-->

</div> <!-- closes wrapper-->

</div> <!-- closes repeattop-->

<div id="login-widget"> </div>

            
</body>
</html>
