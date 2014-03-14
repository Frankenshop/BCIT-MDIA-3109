$(function() {
	
	// change the login / logout button
	$.get("server/session.php", {is_loggedin: true}, function(data) {
		var success = $.parseJSON(data);
		change_button(!success);
	});
	
	var change_button = function( login ) { 
		var log_button = $( "#log-button");
		var first_child = log_button.children("a:first");
		if (first_child !== null)
			first_child.remove();
		
		if (login === false) {
			var logout_button = $( "<a href = '#'>Logout</a>").appendTo( log_button );
			var logout_form = $( "<form action='index.php'></form>").appendTo( log_button );
			logout_button.on( "click", function () {
			  do_logout(logout_form);
			});
		}
		else {
			var login_button = $( "<a href = '#'>Login</a>").appendTo( log_button );
			login_button.on( "click", function () {
			  do_login();
			});
		}
	};
	
	var do_login = function () {
		$( "#login-widget" ).login({login_page: "index.php"});
	};
	
	var do_logout = function (logout_form) {
		$.post("server/session.php", {logout:true}, function(data) {
			var success = $.parseJSON(data);
			if (success === true)
				logout_form.submit();
		});
	};
	
	// the login widget
	$.widget( "sean.login", {
		// default options
		options: {
			login_name: "",
			cancel_page: "",
			login_page: ""
		},
		
		// the constructor
		_create: function() {
			// add the class and id
			this.element.addClass( "login-widget" );
			
			// att the title
			this.title = $( "<h2>", {
				text: "Login:"
			}).appendTo( this.element );
			
			// add the inputs
			this.username_label = $( "<label for = 'login-widget-username-input'> Username: </label>").appendTo( this.element );
			this.username_input = $( "<input type = 'text'>", {
				text: this.options.login_name,
				"class": "login-input-username",
				"id": "login-widget-username-input"
			}).appendTo( this.element );
			this.username_break = $( "<br/>" ).appendTo( this.element );
			this.password_label = $( "<label for = 'login-widget-password-input'> Password: </label>").appendTo( this.element );
			this.password_input = $( "<input type = 'password'>", {
				text: "",
				"class": "login-input-password",
				"id": "login-widget-password-input"
			}).appendTo( this.element );
			this.password_break = $( "<br/>" ).appendTo( this.element );
			
			// see if we are appending the buttons to a form
			if (this.options.cancel_page === "") 
				this.cancel_element = this.element;
			else
				this.cancel_element = $( "<form action='" + this.options.cancel_page + "' method='get'>" ).appendTo( this.element );
			if (this.options.login_page === "")
				this.login_element = this.element;
			else
				this.login_element = $( "<form action='" + this.options.login_page + "' method='get'>" ).appendTo( this.element );
				
			// add the buttons
			this.cancel_button = $( "<button type='submit' class='login-button-cancel' id='login-widget-cancel-button'>Cancel</button>").appendTo( this.cancel_element ).button();
			this.login_button = $( "<button type='submit' class='login-button-login' id='login-widget-login-button'>Login</button>").appendTo( this.login_element ).button();
			this.button_break = $( "<br/> ").appendTo( this.element);
			
			// add the error text
			this.error_span = $( "<span class='error-text' style='display:none'></span>" ).appendTo( this.element );
			
			// bind click events on the buttons
			this._on( this.login_button, {
			  click: "login"
			});
			// bind click events on the buttons
			this._on( this.cancel_button, {
			  click: "cancel"
			});
		},
		
		// login using the supplied username
		login: function( event ) {
			event.preventDefault();
			var self = this;
			var username = this.username_input.val();
			var password = this.password_input.val();
			if (username !== "") {
				if (password !== "") {
					$.get("server/user_view.php", {get_user_id:true, user_name:username}, function(data){
						var id = $.parseJSON(data);
						if (id === false)
							self.error("Invalid username or password");
						else {
							$.get("server/user_view.php", {confirm_password:true, user_id: id, password: password}, function(data){
								var result = $.parseJSON(data);
								if (result === true) {
									$.get("server/user_view.php", {get_status:true, user_id: id}, function (data) {
										var status = $.parseJSON(data);
										if (status !== false) {
											if (status == 2)
												self.error("This account has been banned");
											else if (status == 3)
												self.error("This account has not been activated");
											else {
												$.post("server/session.php", {login:true, user_id: id}, function(data) {
													var success = $.parseJSON(data);
													if (success === true) {
														
														if (self.login_element != self.element) {
															self.login_element.submit();	
														}
														else
															change_button( false );
														self.element.login("destroy");
														self._trigger( "login");
													}
													else
														self.error("Problem encountered on server");
												});
											}
										}
										else
											self.error("Problem encountered on server");
									});
									
								}
								else 
									self.error("Invalid username or password");
							});
						}
					});
				}
				else 
					self.error("Enter a password");
			}
			else 
				self.error("Enter a username");
		},
		
		// set the error text
		error: function( text ) {
			if (!this.element.hasClass("error"))
				this.element.addClass("error");	
			this.error_span.text(text);
			this.error_span.attr( "style", "display:block");
		},
		
		// cancel the login
		cancel: function( event ) {
			if (this.options.cancel_page === "") {
				this.element.login("destroy");
			}
		},
		
		// events bound via _on are removed automatically
		// revert other modifications here
		_destroy: function() {
			// remove generated elements
			this.title.remove();
			this.username_label.remove();
			this.username_input.remove();
			this.username_break.remove();
			this.password_label.remove();
			this.password_input.remove();
			this.password_break.remove();
			this.login_button.remove();
			this.cancel_button.remove();
			this.button_break.remove();
			this.error_span.remove();
			if (this.cancel_element !== this.element)
				this.cancel_element.remove();
			if (this.login_element !== this.element)
				this.login_element.remove();
			this.element.removeClass( "login-widget" );
			if (this.element.hasClass("error"))
				this.element.removeClass( "error" );
		}
	});
});
