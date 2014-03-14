$(function() {
	
	// the signup widget
	$.widget( "sean.signup", {
		// default options
		options: {

		},
		
		// the constructor
		_create: function() {
			// private variables
			this._username_valid = false;
			this._email_valid 	 = false;
			this._password_valid = false;
			this._confirmed_password_valid = false;
			
			// add the class and id
			this.element.addClass( "signup-widget" );
			
			// add the title
			this.title = $( "<h2>", {
				text: "Signup:"
			}).appendTo( this.element );
			
			// add the inputs
			this.username_label = $( "<label for = 'signup-widget-username-input'> Username: </label>").appendTo( this.element );
			this.username_input = $( "<input type = 'text'>", {
				text: this.options.signup_name,
				"class": "signup-input-username",
				"id": "signup-widget-username-input"
			}).appendTo( this.element ).button();
			this.username_error = $( "<span class='error-text' style='display:none'></span>" ).appendTo( this.element );
			this.username_break = $( "<br/>" ).appendTo( this.element );
			this.password_label = $( "<label for = 'signup-widget-password-input'> Password: </label>").appendTo( this.element );
			this.password_input = $( "<input type = 'password'>", {
				text: "",
				"class": "signup-input-password",
				"id": "signup-widget-password-input"
			}).appendTo( this.element );
			this.password_break = $( "<br/>" ).appendTo( this.element );
			this.password_confirm_label = $( "<label for = 'signup-widget-password-confirm-input'> Confirm Password: </label>").appendTo( this.element );
			this.password_confirm_input = $( "<input type = 'password'>", {
				text: "",
				"class": "signup-input-password-confirm",
				"id": "signup-widget-password-confirm-input"
			}).appendTo( this.element );
			this.password_error = $( "<span class='error-text' style='display:none'></span>" ).appendTo( this.element );
			this.password_confirm_break = $( "<br/>" ).appendTo( this.element );
			
			// the email
			this.email_label = $( "<label for = 'signup-widget-password-confirm-input'> E-Mail: </label>").appendTo( this.element );
			this.email_input = $( "<input type = 'text'>", {
				text: "",
				"class": "signup-input-email",
				"id": "signup-widget-email-input"
			}).appendTo( this.element );
			this.email_error = $( "<span class='error-text' style='display:none'></span>" ).appendTo( this.element );
			this.email_confirm_break = $( "<br/>" ).appendTo( this.element );
			
			// add the buttons
			this.signup_button = $( "<button type='submit' class='signup-button-signup' id='signup-widget-signup-button'>Signup</button>").appendTo( this.element );
			this.signup_button.button({disabled:true});
			this.button_break = $( "<br/> ").appendTo( this.element);
			
			// the verification text
			this.verify_text = $( "<h3 class='signup-verify-text' style='display:none'>A verification e-mail has been sent, please click on the link in the e-mail to activate your account.</h3>").appendTo( this.element );
			
			// validate the fields
			this._on( this.username_input, {
				focus: "error_username_clear",
				keyup: "validate_username",
				blur: "validate_username"
			});
			this._on( this.password_input, {
				focus: "error_password_clear",
				keyup: "validate_password",
				blur: "validate_password"
			});
			this._on( this.password_confirm_input, {
				focus: "error_password_clear",
				keyup: "validate_password_confirm",
				blur: "validate_password_confirm"
			});
			this._on( this.email_input, {
				focus: "error_email_clear",
				keyup: "validate_email",
				blur: "validate_email"
			});
			// bind click events on the buttons
			this._on( this.signup_button, {
				click: "signup"
			});	
		},
		
		// validate the confirmed password
		validate_email: function( event ) {
			// private variables
			this._email_valid = false;
			if (this.email_input.val().length != 0) {
				var re = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if (this.email_input.val().match(re) != null) {
					this._email_valid = true;
					this.error_email_clear();
				}
				else {
					this.error_email("The e-mail is not valid");
				}
			}
			this.check_complete();
		},
		
		// validate the password
		validate_password: function( event ) {
			// private variables
			this._password_valid = false;
			if (this.password_input.val().length != 0) {
				if (this.password_input.val().length >= 8) {
					if (this.password_input.val().match(/\d+/g) != null) {
						this._password_valid = true;
						this.error_password_clear();
					}
					else {
						this.error_password("Passwords must contain at least 1 number");
					}
				}
				else {
					this.error_password("Passwords must be 8 or more characters");
				}
			}
			this.check_complete();
		},
		
		// validate the confirmed password
		validate_password_confirm: function( event ) {
			// private variables
			this._confirmed_password_valid = false;
			if (this.password_confirm_input.val().length != 0) {
				if (this.password_confirm_input.val() == this.password_input.val()) {
					this._confirmed_password_valid = true;
					this.validate_password(event);
				}
				else {
					this.error_password("The passwords do not match");
				}
			}
			this.check_complete();
		},
		
		// validate the username
		validate_username: function( event ) {
			var self = this;
			// private variables
			this._username_valid = false;
			if (this.username_input.val().length != 0) {
				if (this.username_input.val().length >= 8) {
					$.get("server/user_view.php", {get_user_id:true, user_name:this.username_input.val()}, function(data){
						var id = $.parseJSON(data);
						if (id !== false) {
							self.error_username("Username has been taken");
						}
						else {
							self._username_valid = true;
							self.error_username_clear();
						}
					});
				}
				else {
					self.error_username("Usernames must be 8 or more characters");
				}
			}
			this.check_complete();
		},
		
		// check for a complete form
		check_complete: function ( event ) {
			
			if (this._email_valid === true && this._password_valid === true && this._confirmed_password_valid === true && this._username_valid === true) {
				this.signup_button.button({disabled:false});
			}
			else
				this.signup_button.button({disabled:true});
		},
		
		// signup using the supplied username
		signup: function( event ) {
			event.preventDefault();
			var self = this;
			$.post("server/user_post.php", {create_user:true, username:this.username_input.val(), password:this.password_input.val(), status: 3}, function(data) {
				var success = $.parseJSON(data);
				if (success !== false) {
					self.show_verify();
				}
				else
					self.error_email("Error encountered while attempting signup");
			});
		},

		// set the username error text
		error_username: function( text ) {
			if (!this.element.hasClass("error"))
				this.element.addClass("error");	
			this.username_error.text(text);
			this.username_error.attr( "style", "display:block");
		},
		error_username_clear: function() {
			this.username_error.attr( "style", "display:none");
			this.clear_error_state();
		},
		
		// set the password error text
		error_password: function( text ) {
			if (!this.element.hasClass("error"))
				this.element.addClass("error");	
			this.password_error.text(text);
			this.password_error.attr( "style", "display:block");
		},
		error_password_clear: function() {
			this.password_error.attr( "style", "display:none");
			this.clear_error_state();
		},
		
		// set the password error text
		error_email: function( text ) {
			if (!this.element.hasClass("error"))
				this.element.addClass("error");	
			this.email_error.text(text);
			this.email_error.attr( "style", "display:block");
		},
		error_email_clear: function() {
			this.email_error.attr( "style", "display:none");
			this.clear_error_state();
		},
		
		// get rid of the error state on the element
		clear_error_state: function () {
			if (this.username_error.attr( "style" ) == "display:none" &&
				this.password_error.attr( "style" ) == "display:none" &&
				this.email_error.attr( "style" ) == "display:none" &&
				this.element.hasClass("error"))
				this.element.removeClass( "error" );
		},
		
		// cancel the signup
		cancel: function( event ) {
			if (this.options.cancel_page === "") {
				this.element.signup("destroy");
			}
		},
		
		show_verify: function() {
			this.username_label.attr("style", "display: none");
			this.username_input.attr("style", "display: none");
			this.username_break.attr("style", "display: none");
			this.password_label.attr("style", "display: none");
			this.password_input.attr("style", "display: none");
			this.password_break.attr("style", "display: none");
			this.signup_button.attr("style", "display: none");
			this.button_break.attr("style", "display: none");
			this.username_error.attr("style", "display: none");
			this.password_confirm_label.attr("style", "display: none");
			this.password_confirm_input.attr("style", "display: none");
			this.password_error.attr("style", "display: none");
			this.password_confirm_break.attr("style", "display: none");
			this.email_label.attr("style", "display: none");
			this.email_input.attr("style", "display: none");
			this.email_error.attr("style", "display: none");
			this.email_confirm_break.attr("style", "display: none");
			this.verify_text.attr("style", "display: block");
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
			this.signup_button.remove();
			this.button_break.remove();
			this.username_error.remove();
			this.password_confirm_label.remove();
			this.password_confirm_input.remove();
			this.password_error.remove();
			this.password_confirm_break.remove();
			this.email_label.remove();
			this.email_input.remove();
			this.email_error.remove();
			this.email_confirm_break.remove();
			this.verify_text.remove();
			
			this.element.removeClass( "signup-widget" );
			if (this.element.hasClass("error"))
				this.element.removeClass( "error" );
				
			// private variables
			this._username_valid = false;
			this._email_valid 	 = false;
			this._password_valid = false;
			this._confirmed_password_valid = false;
		}
	});
});
