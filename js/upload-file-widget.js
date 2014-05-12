$(function() {
	
	// the login widget
	$.widget( "sean.uploadfile", {
		// default options
		options: {
			title: "",
			input_name: "",
			additional_fields: "",
			additional_class: ""
		},
		
		// the constructor
		_create: function() {
			// add the class and id
			this.element.addClass( "upload-file-widget" );
			if (this.options.additional_class != "")
				this.element.addClass(this.options.additional_class);
			
			// att the title
			if (this.options.title == "")
				this.title = $( "<h2>", {
					text: "Upload File:"
				}).appendTo( this.element );
			else
				this.title = $( "<h2>", {
					text: this.options.title
				}).appendTo( this.element );
			
			// the form element
			this.form_element = $( "<form action='server/upload.php' method='post' enctype='multipart/form-data'>" ).appendTo( this.element );
			this.additional_elements = this.element;
			
			// add the inputs
			if (this.options.input_name == "")
				this.file_input = $( "<input type='file' class='upload' name='splash_picture' />").appendTo( this.form_element );
			else
				this.file_input = $( "<input type='file' class='upload' name='"+this.options.input_name+"' />").appendTo( this.form_element );
				
			if (this.options.additional_fields != "")
				this.additional_elements = $(this.options.additional_fields).appendTo(this.form_element);
			
			// add the buttons
			this.cancel_button = $( "<button type='submit' class='upload-file-button-cancel' id='upload-file-widget-cancel-button'>Cancel</button>").appendTo( this.form_element ).button();
			this.upload_button = $( "<button type='submit' class='upload-file-button-login' id='upload-file-widget-upload-button'>Upload</button>").appendTo( this.form_element ).button();
			
			// bind click events on the buttons
			this._on( this.cancel_button, {
			  click: "cancel"
			});
		},

		// cancel the login
		cancel: function( event ) {
			this.element.uploadfile("destroy");
		},
		
		// events bound via _on are removed automatically
		// revert other modifications here
		_destroy: function() {
			// remove generated elements
			// add the class and id
			this.title.remove();
			this.form_element.remove();
			this.file_input.remove();
			this.cancel_button.remove();
			this.upload_button.remove();
			if (this.additional_elements != this.element)
				this.additional_elements.remove();
			this.element.removeClass( "upload-file-widget" );
		}
	});
});
