$(function() {
	
	// the add portfolio widget
	$.widget( "sean.addportfolio", {
		// default options
		options: {
			edit:false,
			title:"Add new portfolio",
			portfolio_name:"",
			summary:"",
			portfolio_id:""
		},
		
		// the constructor
		_create: function() {
			// add the class and id
			this.element.addClass( "add-portfolio-widget" );
			
			// the children
			this.children = this.element.children();
			this.show_children(false);
			
			// add the container
			this.container = $( "<div class = 'add-portfolio-container'></div>").appendTo( this.element );
			this.left = $( "<div class = 'add-portfolio-left'></div>").appendTo( this.container );
			this.right = $( "<div class = 'add-portfolio-right'></div>").appendTo( this.container );
			
			// add the title
			this.title = $( "<h2>", {
				text: this.options.title
			}).appendTo( this.left )
			
			// add the inputs
			this.portfolioname_div = $("<div class='add-portfolio-portfolioname-div'></div>").appendTo(this.left);
			this.portfolioname_label = $( "<label for = 'add-portfolio-widget-portfolioname-input'> Portfolio Name: </label>").appendTo( this.portfolioname_div );
			this.portfolioname_input = $( "<input type = 'text' class='add-portfolio-input-portfolioname' id='add-portfolio-widget-portfolioname-input' value='"+this.options.portfolio_name+"'>").appendTo( this.portfolioname_div )
			this.portfolioname_input.focus();
			
			this.summary_div = $("<div class='add-portfolio-summary-div'></div>").appendTo(this.left);
			this.summary_label = $( "<label for = 'add-portfolio-widget-summary-input'> Summary: </label>").appendTo( this.summary_div );
			this.summary_input = $( "<textarea>", {
				text: this.options.summary,
				"class": "add-portfolio-input-summary",
				"id": "add-portfolio-widget-summary-input"
			}).appendTo( this.summary_div )
			
			// add the buttons
			var button_string = "Add";
			if (this.options.edit)
				button_string = "Update";
			this.add_button = $( "<button type='button' class='add-portfolio-button-add' id='add-portfolio-widget-add-button'>"+button_string+"</button>").appendTo( this.right ).button();
			this.cancel_button = $( "<button type='button' class='add-portfolio-button-cancel' id='add-portfolio-widget-cancel-button'>Cancel</button>").appendTo( this.right ).button();
			
			// bind click events on the buttons
			this._on( this.add_button, {
			  click: "add"
			});
			// bind click events on the buttons
			this._on( this.cancel_button, {
			  click: "cancel"
			});
		},
		
		// add the new portfolio
		add: function( event ) {
			event.preventDefault();
			var self = this;
			var portfolio_name = this.portfolioname_input.val();
			var portfolio_summary = this.summary_input.val();
			// create or edit the portfolio
			if (this.options.edit) {
				
				$.post("server/portfolio_post.php", {change_title: true, portfolio_id: self.options.portfolio_id, title: portfolio_name }, function(data) {
					var success = $.parseJSON(data);
					if (success) {
						$.post("server/portfolio_post.php", {change_description: true, portfolio_id: self.options.portfolio_id, description: portfolio_summary }, function(data) {
							var success = $.parseJSON(data);
							if (success) {
								self.element.trigger( "update", [portfolio_name, portfolio_summary] );
								self.element.addportfolio("destroy");
							}
						});
					}
				});
			}
			else {
				$.get("server/user_view.php", {get_user_id:true}, function(data){
					var id = $.parseJSON(data);
					if (id !== false) {
						$.post("server/portfolio_post.php", {create_portfolio:true, portfolio_name: portfolio_name, portfolio_summary: portfolio_summary, user_id: id}, function(data){
							var success = $.parseJSON(data);
							if (success !== false) {
								self.element.trigger( "login", [portfolio_name, portfolio_summary]);
								self.element.addportfolio("destroy");
							}
						});
					}
				});
			}
		},
		
		// remove the new portfolio
		cancel: function( event ) {
			event.preventDefault();
			this.element.addportfolio("destroy");
		},
		
		show_children: function( should_show ) {
			if (!should_show) {
				$.each( this.children, function( index, value ){
					value.style.display="none";
				});
			}
			else {
				$.each( this.children, function( index, value ){
					value.style.display="";
				});
			}
		},
		
		// events bound via _on are removed automatically
		// revert other modifications here
		_destroy: function() {
			// remove generated elements
			this.container.remove();
			this.left.remove();
			this.right.remove();
			
			this.title.remove();
			this.portfolioname_div.remove();
			this.portfolioname_label.remove();
			this.portfolioname_input.remove();
			this.summary_div.remove();
			this.summary_label.remove();
			this.summary_input.remove();
		
			// remove the buttons
			this.add_button.remove();
			this.cancel_button.remove();
			this.element.removeClass( "add-portfolio-widget" );
			
			// show the children
			this.show_children(true);
		}
	});
});
