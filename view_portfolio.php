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
    <script src="js/upload-file-widget.js"></script>
    <script src="js/functions.js"></script>
</head>

<script>
$(document).ready(function(){
	
	var display_single_portfolio = function(name, summary, stylenames, attachmentDiv, splashImage, portfolioImages) {
		// clear the old container
		$("#portfolio").empty();
	
		// set the title
		$("#portfolio-title").text(name);
		if (is_owner) {
			var upload_splash_button = $("<button>Upload New Splash Image...</button>").on("click", function () { $("#upload-file-widget").uploadfile({title:"Upload Splash Picture:", additional_fields:"<input type='hidden' name='portfolio_id' value='"+requested_portfolio+"'><input type='hidden' name='redirect_url' value='view_portfolio.php?portfolio="+requested_portfolio+"'>"});});
			upload_splash_button.insertAfter('#portfolio-title');
			var edit_title_button = $("<button id ='edit-title-button'>Edit</button>").on("click", function () { swap_title(true); });
			edit_title_button.insertAfter('#portfolio-title');
		}
		
		// the content
		var div = $("<div class='portfolio " + stylenames + "'></div>");
		var content = $("<div id='portfolio-content' class='content full'></div>");
		
		// the splash
		if (splashImage !== null) {
			var splash_element = $("<img src="+splashImage+" id='portfolio-splash-full'/>");
			content.append(splash_element);
		}
		
		// the summary
		var summary_element = $("<span id='portfolio-description'>" + summary + "</span>");
		content.append(summary_element);
		div.append(content);
		if (is_owner) {
			var edit_summary_button = $("<button id='edit-summary-button'>Edit</button>").on("click", function () { swap_description(true); });
			content.append(edit_summary_button);
		}
		
		// append the images
		var image_div = $("<div class='images'></div>");
		var image_title = $("<h2>Images:</h2>");
		image_div.append(image_title);
		content.append(image_div);
		if (portfolioImages == null || portfolioImages.length <= 0) {
			var no_images = $("<span>There are no images attached to this portfolio</span>");
			image_div.append(no_images);
		}
		else {
			for (var i = 0; i < portfolioImages.length; i++) {
				image_div.append("<img src='"+portfolioImages[i]+"' class='thumb'/>");
			}
		}
		
		if (is_owner) {
			var upload_image_button = $("<button>Upload New Image...</button>").on("click", function () { $("#upload-file-widget").uploadfile({title:"Upload Picture:", additional_fields:"<h2>Title:</h2><input type='text' name='picture_title' style='width:310px'/><h2>Description:</h2><input type='text' name='picture_description' style='width:310px'/><input type='hidden' name='portfolio_id' value='"+requested_portfolio+"'/><input type='hidden' name='redirect_url' value='view_portfolio.php?portfolio="+requested_portfolio+"'/>", input_name: "portfolio_picture", additional_class: "large"});});
			image_div.append(upload_image_button);
		}
		
		// append the contributors
		if (is_owner) {
			var contributor_div = $("<div class='contributor'></div>");
			var contributor_break = $("<br/>");
			var contributors_title = $("<h2>All Contributors:</h2>");
			var contributor_span = $("<span>There are no contributors</span>");
			var contributor_title = $("<h2>Add Contributor:</h2>");
			var contributor_input = $("<input type='text' id='contributor-input'></input>");
			var contributor_button = $("<button>Submit</button>");
			contributor_div.append(contributor_break);
			contributor_div.append(contributors_title);
			contributor_div.append(contributor_span);
			contributor_div.append(contributor_title);
			contributor_div.append(contributor_input);
			contributor_div.append(contributor_button);
			content.append(contributor_div);
		}
		
		// attach the new portfolio
		attachmentDiv.append(div);
	};
	
	// attempt to find a requested portfolio
	var requested_portfolio = window.get_parameter_by_name("portfolio");
	var is_owner = false;
	
	// if there is a requested portfolio, attempt to find the portfolio id
	if (requested_portfolio != "") {		
		$.get("server/portfolio_view.php", {get_portfolio: true, portfolio_id: requested_portfolio}, function(data) {
			var info = $.parseJSON(data);
			info['SplashPicture'] = null;
			if (info !== false) {
				// find a splash picture
				$.get("server/picture_view.php", {get_splash_picture_portfolio: true, portfolio_id: requested_portfolio}, function(data) {
					var picture = $.parseJSON(data);
					if (picture !== false) {
						$.each(picture, function( index, value ) {
						  info['SplashPicture'] = value['link'];
						});
					}
					// find the portfolio pictures
					$.get("server/picture_view.php", {get_pictures_for_portfolio: true, portfolio_id: requested_portfolio}, function(data) {
						var picture = $.parseJSON(data);
						if (picture !== false) {
							info['PortfolioPicture'] = new Array();
							$.each(picture, function( index, value ) {
							  info['PortfolioPicture'].push(value['link']);
							});
						}
						else {
							info['PortfolioPicture'] = null;
						}
						// determine if the current user is the owner
						$.get("server/session.php", {is_loggedin: true}, function(data) {
							var success = $.parseJSON(data);
							if (success === true) 
								$.get("server/user_view.php", {get_user_id: true}, function(data) {
									var user_id = $.parseJSON(data);
									if (user_id !== false) 
										$.get("server/portfolio_view.php", {get_user_owns_portfolio: true, user_id: user_id, portfolio_id: requested_portfolio }, function(data) {
											var success = $.parseJSON(data);
											if (success !== false)
												is_owner = true;	
											finalize_load(info);
										});
									else 
										finalize_load(info);
								});
							else 
								finalize_load(info);
						});
					});
					
				});
			}
		});
	}
	
	// finalize the load
	var finalize_load = function(info) {
		display_single_portfolio(info['PortfolioName'], info['Summary'], "", $("#portfolio"), info['SplashPicture'],info['PortfolioPicture']);
	}
	
	// swap the description for an input box
	var swap_description = function(to_input) {
		if (to_input) {
			// replace the title
			var portfolio_description = $('#portfolio-description');
			var text = portfolio_description.text();
			var description_input = $("<textarea id='portfolio-description' type='text'></textarea>");
			portfolio_description.remove();
			
			// create a new save and cancel button
			var save_description_button = $("<button id='done-summary-button'>Done</button>").on("click", function () { swap_description(false);});
			$('#portfolio-content').prepend(save_description_button);
			
			// prepend the input
			$('#portfolio-content').prepend(description_input).on("keyup", function (event) {
				if(event.keyCode == 13)
				{
					swap_description(false);
				}
			});
			description_input.focus();
			description_input.val(text);
			
			// replace the buttons
			var edit_description_button = $("#edit-summary-button");
			edit_description_button.remove();
			
			// move the image to the right place if needed
			var image = $('#portfolio-splash-full'); 
			if (image !== null)
				$('#portfolio-content').prepend(image);
		}
		else {
			// the content
			var summary_input = $("#portfolio-description");
			var text = summary_input.val();
			var summary_element = $("<span id='portfolio-description'>" + text + "</span>");
			summary_input.remove();
			
			// update the description
			$.post("server/portfolio_post.php", {change_description: true, portfolio_id: requested_portfolio, description: summary_input.val()}, function(data) {
				var success = $.parseJSON(data);
			});
			
			// create the endit button
			var edit_summary_button = $("<button id='edit-summary-button'>Edit</button>").on("click", function () { swap_description(true); });
			$('#portfolio-content').prepend(edit_summary_button);
			
			// prepend the input
			$('#portfolio-content').prepend(summary_element);
			
			// replace the buttons
			var edit_description_button = $("#done-summary-button");
			edit_description_button.remove();
			
			// move the image to the right place if needed
			var image = $('#portfolio-splash-full'); 
			if (image !== null)
				$('#portfolio-content').prepend(image);
		}
	}
	
	// swap the title for an input box
	var swap_title = function(to_input) {
		if (to_input) {
			// replace the title
			var portfolio_title = $('#portfolio-title');
			var text = portfolio_title.text();
			var title_input = $("<input id='portfolio-title' type='text' value='"+text+"'></input>");
			portfolio_title.remove();
			$('#second').prepend(title_input);
			title_input.focus();
			
			// replace the buttons
			var edit_portfolio_button = $("#edit-title-button");
			edit_portfolio_button.remove();
			
			// create a new save and cancel button
			var save_portfolio_button = $("<button id='done-title-button'>Done</button>");
			save_portfolio_button.insertAfter(title_input);
			
			// add the listeners
			save_portfolio_button.on("click", function () {
				swap_title(false);
			});
			title_input.on("keyup", function (event) {
				if(event.keyCode == 13)
				{
					swap_title(false);
				}
			});
		}
		else {
			// replace the title
			var portfolio_title = $('#portfolio-title');
			var text = portfolio_title.val();
			var title_h = $("<h1 id='portfolio-title'>"+text+"</h1>");
			portfolio_title.remove();
			$('#second').prepend(title_h);
			
			// update the description
			$.post("server/portfolio_post.php", {change_title: true, portfolio_id: requested_portfolio, title: portfolio_title.val()}, function(data) {
				var success = $.parseJSON(data);
			});
			
			// remove the button
			var done_button = $("#done-title-button");
			done_button.remove();
			
			// add the button
			var edit_title_button = $("<button id ='edit-title-button'>Edit</button>").on("click", function () { swap_title(true); });
			edit_title_button.insertAfter('#portfolio-title');
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
            <h1 id="portfolio-title"> Error locating portfolio: </h1>
            <div id = "portfolio"> 
                <div class="portfolio">
                	<div class="content full">
                   		The requested portfolio was not found or no longer exists.
                    </div>
                </div>
            </div>
        </div><!-- Closes second -->

   
    </div> <!-- closes content-->
    
</div><!-- closes container-->

</div> <!-- closes wrapper-->

</div> <!-- closes repeattop-->

<div id="login-widget"> </div>
<div id="upload-file-widget"> </div>

            
</body>
</html>
