( function() {
	
	 var patd_cats=JSON.parse(pstd_cat_obj.category);
	   var categories=[];

	   for( var cat in patd_cats){
		   categories.push({"text":patd_cats[cat],"value":cat});
	   }
     tinymce.PluginManager.add( 'cool_process', function( editor, url ) {
	
		function disabled_button_on_click() {
				this.disabled( !this.disabled() );
				editor.insertContent( '[cool-process]' );
			}
	
		function re_enable_button() {	
				var state = this.enabled();
			}
		
		editor.on( 'keyup' , function() {
				if ( editor.getContent().indexOf( '[cool-process]' ) > -1 ) {
					editor.controlManager.setDisabled('cool_process_shortcode_button', true);
				} else {
					editor.controlManager.setDisabled('cool_process_shortcode_button', false);
				}
			});

	   var pp_categories=[];
		var ani_options=[];
	   ani_options.push({"text":"NO","value":"no"});
	   ani_options.push({"text":"YES","value":"yes"});
 		var row_steps={
		   "number":[
			   {"text":"2","value":"2"},
			   {"text":"3","value":"3"},
			   {"text":"4","value":"4"},
			   {"text":"5","value":"5"},
			   {"text":"6","value":"6"},
			   {"text":"7","value":"7"},
			   {"text":"8","value":"8"},
			   {"text":"9","value":"9"},
			   {"text":"10","value":"10"},
			 ]};
     var show_step={
		   "number":[
			   {"text":"2","value":"2"},
			   {"text":"3","value":"3"},
			   {"text":"4","value":"4"},
			   {"text":"5","value":"5"},
			   {"text":"6","value":"6"},
			   {"text":"7","value":"7"},
			   {"text":"8","value":"8"},
			   {"text":"9","value":"9"},
			   {"text":"10","value":"10"},
			 ]};
			 
			 
			 if(typeof pstd_cat_obj != 'undefined' && typeof pstd_cat_obj.category != 'undefined') 
{
        editor.addButton( 'cool_process_shortcode_button', {
			//	title: 'Cool process Shortcode',
				text: false,
				type: 'menubutton',
				image: url + '/coolprocess.png',
			
			
				menu: [
                {
                    text: 'Add Process with icons',
                    onclick: function() {

                        editor.windowManager.open( {
                            title: 'Add Cool Process with icons',
                            body: [
                            		{
	                                type: 'listbox', 
	                                name: 'category', 
	                                label: 'Process categories',
	                                'values':categories
                          		  	},
									{
										type: 'listbox',
										name: 'number_of_posts',
										label: 'Show Steps',
										'values':row_steps.number
									},
									
									
			
									{
									type: 'textbox',
									name: 'icon_size',
									label: 'Icon Size (in px)eg:72px',
									value:''
									},
									
									{
									type: 'listbox',
									name: 'animation',
									label: 'Animation effects',
									'values':ani_options
									},
							],
                            onsubmit: function( e ) {
                                editor.insertContent( '[cool-process category="' + e.data.category + '" type="default"  show-posts="' + e.data.number_of_posts + '" icon-size="' + e.data.icon_size + '" animation="' + e.data.animation + '"]');
                            }
                        });
                    }
                },
					{
						text: 'Add Process with images',
						onclick: function() {

							editor.windowManager.open( {
								title: 'Add Cool Process with images',
								body: [
								{
	                                type: 'listbox', 
	                                name: 'category', 
	                                label: 'Process categories',
	                                'values':categories
                          		  	},
									{
										type: 'listbox',
										name: 'number_of_posts',
										label: 'Show Steps',
										'values':row_steps.number
									},
								],
								onsubmit: function( e ) {
									editor.insertContent( '[cool-process category="' + e.data.category + '" type="with-image" show-posts="' + e.data.number_of_posts + '"]');
								}
							});
						}
					},
					{
						text: 'Add Process with numbers',
						onclick: function() {

							editor.windowManager.open( {
								title: 'Add Cool Process with Text',
								body: [
								
								{
	                                type: 'listbox', 
	                                name: 'category', 
	                                label: 'Process categories',
	                                'values':categories
                          		  	},
								{
										type: 'listbox',
										name: 'number_of_posts',
										label: 'Show Steps',
										'values':row_steps.number
									},
									
								],
								onsubmit: function( e ) {
									editor.insertContent( '[cool-process category="' + e.data.category + '" type="with-number" show-posts="' + e.data.number_of_posts + '"]');
								}
							});
						}
					},
					
					
					{
						text: 'Add Vertical Process',
						onclick: function() {

							editor.windowManager.open( {
								title: 'Add Cool Vertical Process with icons',
								body: [
								{
	                                type: 'listbox', 
	                                name: 'category', 
	                                label: 'Process categories',
	                                'values':categories
                          		  	},
									{
										type: 'listbox',
										name: 'number_of_posts',
										label: 'Show Steps',
										'values':row_steps.number
									},
									
									{
									type: 'textbox',
									name: 'icon_size',
									label: 'Icon Size (in px)eg:10px',
									value:''
									},
									
									{
									type: 'textbox',
									name: 'choose_color',
									label: 'Choose Color (hex value)eg: #ffhh00',
									value:''
									},
								],
								onsubmit: function( e ) {
									editor.insertContent( '[cool-process category="' + e.data.category + '" type="vertical-process" show-posts="' + e.data.number_of_posts + '" icon-size="' + e.data.icon_size + '"  choose-color="' + e.data. choose_color + '"]');
								}
							});
						}
					},

					

           ]
			});

		
		editor.onSetContent.add(function(editor, o) {
			  if ( editor.getContent().indexOf( '[cool-process]' ) > -1) {
					editor.controlManager.setDisabled('cool_process_shortcode_button', true);
				}
		  });
		  }
   });

})();