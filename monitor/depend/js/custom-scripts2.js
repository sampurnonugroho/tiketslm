var jq = $.noConflict();

jq(function() {

	/*==JQUERY STEPY==*/
	jq('#stepy_form').stepy({
		backLabel: 'Back',
		nextLabel: 'Next',
		errorImage:true,
		block: true,
		description: true,
		legend: false,
		titleClick: true,
		titleTarget: '#top_tabby',
		validate: true
	});
	
		
	/*===================
	LIST-ACCORDION
	===================*/	  

	jq('#list-accordion').accordion({
		header: ".title",
		autoheight: false
	});
	
	jq('#stepy_form').validate({
					errorPlacement: function(error, element) {
						jq('#stepy_form div.stepy-error').append(error);
					}, rules: {
						'name':			'required',
						'email':		'required',
					}, messages: {
						'name':		{ required:  'Name field is required!' },
						'email':			{ required:  'Email field is requerid!' },
					}
				});
				
		jq('#left_stepy').stepy({
		backLabel: 'Back',
		nextLabel: 'Next',
		errorImage:     true,
		block: true,
		description: true,
		legend: false,
		titleClick: true,
		titleTarget: '#left_title',
		validate: true
	});		
				
			jq('#left_stepy').validate({
					errorPlacement: function(error, element) {
						jq('#left_stepy div.stepy-error').append(error);
					}, rules: {
						'name':			'required',
						'password':		'required',
					}, messages: {
						'name':		{ required:  'name field is required!' },
						'password':			{ required:  'pass field is requerid!' },
					}
				});	
				
	jq('#stepy_no_validation').stepy({
		backLabel: 'Back',
		nextLabel: 'Next',
		errorImage:     true,
		block: true,
		description: true,
		legend: false,
		titleClick: true,
		titleTarget: '#tabby_no_validation',
		validate: true
	});		
	
  jq('#valid').stepy({
	  
		
		backLabel:      'Backward',
  block:          true,
  errorImage:     true,
  nextLabel:      'Forward',
  titleClick:     true,
  validate:       true
	});
	
	jq('#valid').validate({
					errorPlacement: function(error, element) {
						jq('#valid div.stepy-error').append(error);
					}, rules: {
						'user':			{ maxlength: 1 },
						'email':		'email',
						'checked':		'required',
						'newsletter':	'required',
						'password':		'required',
						'bio':			'required',
						'day':			'required'
					}, messages: {
						'user':			{ maxlength: 'User field should be less than 1!' },
						'email':		{ email: 	 'Invalid e-mail!' },
						'checked':		{ required:  'Checked field is required!' },
						'newsletter':	{ required:  'Newsletter field is required!' },
						'password':		{ required:  'Password field is requerid!' },
						'bio':			{ required:  'Bio field is required!' },
						'day':			{ required:  'Day field is requerid!' },
					}
				});
				
				
				
				

	/*==AUTO GROW TEXTBOX==*/	
	 jq(".input_grow").autoGrow();
	
	/*==INPUT MASK==*/
	jq("#date").mask("99/99/9999");
	jq("#phone").mask("(999) 999-9999");
	jq("#mobile").mask("(999) 999-9999");
	jq("#tin").mask("99-9999999");
	jq("#ssn").mask("999-99-9999");	

	/*======================
	ACCORDION MENU
	========================*/
	jq('.accordion_mnu').initMenu();
	
	/*======================
	DUAL LIST
	========================*/	
    jq.configureBoxes();
	 
	/*======================
	PROGRESS BAR
	========================*/		 
	jq( "#probar_blue" ).progressbar({
			value: 37
		});
	jq( "#probar_black" ).progressbar({
			value: 25
		});
	jq( "#probar_orange" ).progressbar({
			value: 75
		});
	jq( "#probar_green" ).progressbar({
			value: 80
		});
	jq( "#probar_stripe" ).progressbar({
			value: 70
		});
		jq( "#probar_stripe_blue" ).progressbar({
			value: 20
		});
		jq( "#probar_stripe_green" ).progressbar({
			value: 50
		});
		jq( "#probar_stripe_orange" ).progressbar({
			value: 60
		});
	/*======================
	RATY
	========================*/	
	  jq('#star').raty({
		half:  true,
		start: 3.3
	  });
	  
	/*======================
	DATEPICKER
	========================*/	
	jq( ".datepicker" ).datepicker();  


	/*END*/

	/*======================
	IPHONE STYLE BUTTON
	========================*/
	jq('.on_off :checkbox').iphoneStyle();
		jq('.disabled :checkbox').iphoneStyle();
		jq('.long_tiny :checkbox').iphoneStyle({ checkedLabel: 'Very Long Text', uncheckedLabel: 'Tiny' });
		
		var onchange_checkbox = (jq('.onchange :checkbox')).iphoneStyle({
		  onChange: function(elem, value) { 
			jq('span#status').html(value.toString());
		  }
		});

	
	
		
	/*======================
	iBUTTON Radio/Check Box
	========================*/
	
	jq(".cb-enable").click(function(){
		  var parent = jq(this).parents('.switch');
		  jq('.cb-disable',parent).removeClass('selected');
		  jq(this).addClass('selected');
		  jq('.checkbox',parent).attr('checked', true);
	});
	
	jq(".cb-disable").click(function(){
		var parent = jq(this).parents('.switch');
		jq('.cb-enable',parent).removeClass('selected');
		jq(this).addClass('selected');
		jq('.checkbox',parent).attr('checked', false);
	});
	
		
	

	/*======================
	INPUT LIMITER
	========================*/
	
	jq('.limiter').inputlimiter({limit: 100});
	
   
	/*======================
	Tags Input
	========================*/ 
			jq('#tags_1').tagsInput({
				width:'99%',
				'defaultText':'add a test tag'
				});

	
	
	/*======================
	DROPDOWN
	========================*/
	jq('.dropdown-toggle').dropdown()
	

	
	/*======================
	BREADCRUMB
	========================*/
	jq("#breadCrumb0").jBreadCrumb();
	jq("#breadCrumb1").jBreadCrumb();
	jq("#breadCrumb2").jBreadCrumb();
	jq("#breadCrumb3").jBreadCrumb();
	
	
	
	/*======================
	Sticky
	========================*/
	jq('.s_noty').click(function(){
		
	jq('.s_noty').sticky('This is Simple Note');
	});
	jq('.i_noty').click(function(){
		
	jq('.i_noty').sticky();
	});
	
	jq('.click_noty_btn').click(function(){
		
	jq('.sticky_html').sticky();
	});
	
	jq('.custom_n').click(function(){
		
	jq('.sticky_custom').sticky();
	});
	
	
	/*======================
	TREEVIEW
	========================*/

	jq(function() {
		jq("#tree").treeview({
			collapsed: true,
			animated: "fast",
			control:"#sidetreecontrol",
			prerendered: true,
			persist: "location"
		});
	})
	
	/*==============================
	  NOTY TOP
	================================*/
	
	jq('.alert_t').click(function() {
		
		var noty_id = noty({
			layout : 'top',
			text: 'noty - a jquery notification library!',
			modal : true,
			type:'alert',
			
			 });
		  });

	jq('.error_t').click(function() {
		
		var noty_id = noty({
			layout : 'top',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'error', 
			 });
		  });
		  
	jq('.success_t').click(function() {
		
		var noty_id = noty({
			layout : 'top',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'success', 
			 });
		  });
		  
	jq('.info_t').click(function() {
		
		var noty_id = noty({
			layout : 'top',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'information', 
			 });
		  });
	
	jq('.confirm_t').click(function() {
		
		var noty_id = noty({
			layout : 'top',
			text: 'noty - a jquery notification library!',
			modal : true,
			buttons: [
				{type: 'button green', text: 'Ok', click: function($noty) {
		  
					// this = button element
					// $noty = $noty element
		  
					$noty.close();
					noty({force: true, text: 'You clicked "Ok" button', type: 'success'});
				  }
				},
				{type: 'button pink', text: 'Cancel', click: function($noty) {
					$noty.close();
					noty({force: true, text: 'You clicked "Cancel" button', type: 'error'});
				  }
				}
				],
			 type : 'success', 
			 });
		  });


	/*==============================
	  NOTY CENTER
	================================*/
	
	jq('.alert_c').click(function() {
		
		var noty_id = noty({
			layout : 'center',
			text: 'noty - a jquery notification library!',
			modal : true,
			type:'alert',
			
			 });
		  });

	jq('.error_c').click(function() {
		
		var noty_id = noty({
			layout : 'center',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'error', 
			 });
		  });
		  
	jq('.success_c').click(function() {
		
		var noty_id = noty({
			layout : 'center',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'success', 
			 });
		  });
		  
	jq('.info_c').click(function() {
		
		var noty_id = noty({
			layout : 'center',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'information', 
			 });
		  });
	
	jq('.confirm_c').click(function() {
		
		var noty_id = noty({
			layout : 'center',
			text: 'noty - a jquery notification library!',
			modal : true,
			buttons: [
				{type: 'button green', text: 'Ok', click: function($noty) {
		  
					// this = button element
					// $noty = $noty element
		  
					$noty.close();
					noty({force: true, text: 'You clicked "Ok" button', type: 'success',layout : 'center',modal : true,});
				  }
				},
				{type: 'button pink', text: 'Cancel', click: function($noty) {
					$noty.close();
					noty({force: true, text: 'You clicked "Cancel" button', type: 'error',layout : 'center',modal : true,});
				  }
				}
				],
			 type : 'success', 
			 });
		  });
	
	/*==============================
	  NOTY BOTTOM
	================================*/
	
	jq('.alert_b').click(function() {
		
		var noty_id = noty({
			layout : 'bottom',
			text: 'noty - a jquery notification library!',
			modal : true,
			type:'alert',
			closeButton : true,
			
			 });
		  });

	jq('.error_b').click(function() {
		
		var noty_id = noty({
			layout : 'bottom',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'error', 
			 });
		  });
		  
	jq('.success_b').click(function() {
		
		var noty_id = noty({
			layout : 'bottom',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'success', 
			 });
		  });
		  
	jq('.info_b').click(function() {
		
		var noty_id = noty({
			layout : 'bottom',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'information', 
			 });
		  });
	
	jq('.confirm_b').click(function() {
		
		var noty_id = noty({
			layout : 'bottom',
			text: 'noty - a jquery notification library!',
			modal : true,
			buttons: [
				{type: 'button green', text: 'Ok', click: function($noty) {
		  
					// this = button element
					// $noty = $noty element
		  
					$noty.close();
					noty({force: true, text: 'You clicked "Ok" button', type: 'success',layout : 'bottom',modal : true,});
				  }
				},
				{type: 'button pink', text: 'Cancel', click: function($noty) {
					$noty.close();
					noty({force: true, text: 'You clicked "Cancel" button', type: 'error',layout : 'bottom',modal : true,});
				  }
				}
				],
			 type : 'success', 
			 });
		  });
	
	
	/*==============================
	  NOTY TOP LEFT
	================================*/
	
	jq('.alert_tl').click(function() {
		
		var noty_id = noty({
			layout : 'topLeft',
			text: 'noty - a jquery notification library!',
			modal : true,
			type:'alert',
			
			 });
		  });

	jq('.error_tl').click(function() {
		
		var noty_id = noty({
			layout : 'topLeft',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'error', 
			 });
		  });
		  
	jq('.success_tl').click(function() {
		
		var noty_id = noty({
			layout : 'topLeft',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'success', 
			 });
		  });
		  
	jq('.info_tl').click(function() {
		
		var noty_id = noty({
			layout : 'topLeft',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'information', 
			 });
		  });
	
	jq('.confirm_tl').click(function() {
		
		var noty_id = noty({
			layout : 'topLeft',
			text: 'noty - a jquery notification library!',
			modal : true,
			buttons: [
				{type: 'button green', text: 'Ok', click: function($noty) {
		  
					// this = button element
					// $noty = $noty element
		  
					$noty.close();
					noty({force: true, text: 'You clicked "Ok" button', type: 'success',layout : 'topLeft',modal : true,});
				  }
				},
				{type: 'button pink', text: 'Cancel', click: function($noty) {
					$noty.close();
					noty({force: true, text: 'You clicked "Cancel" button', type: 'error',layout : 'topLeft',modal : true,});
				  }
				}
				],
			 type : 'success', 
			 });
		  });
	
	/*==============================
	  NOTY TOP CENTER
	================================*/
	
	jq('.alert_tc').click(function() {
		
		var noty_id = noty({
			layout : 'topCenter',
			text: 'noty - a jquery notification library!',
			modal : true,
			type:'alert',
			
			 });
		  });

	jq('.error_tc').click(function() {
		
		var noty_id = noty({
			layout : 'topCenter',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'error', 
			 });
		  });
		  
	jq('.success_tc').click(function() {
		
		var noty_id = noty({
			layout : 'topCenter',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'success', 
			 });
		  });
		  
	jq('.info_tc').click(function() {
		
		var noty_id = noty({
			layout : 'topCenter',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'information', 
			 });
		  });
	
	jq('.confirm_tc').click(function() {
		
		var noty_id = noty({
			layout : 'topCenter',
			text: 'noty - a jquery notification library!',
			modal : true,
			buttons: [
				{type: 'button green', text: 'Ok', click: function($noty) {
		  
					// this = button element
					// $noty = $noty element
		  
					$noty.close();
					noty({force: true, text: 'You clicked "Ok" button', type: 'success',layout : 'topCenter',modal : true,});
				  }
				},
				{type: 'button pink', text: 'Cancel', click: function($noty) {
					$noty.close();
					noty({force: true, text: 'You clicked "Cancel" button', type: 'error',layout : 'topCenter',modal : true,});
				  }
				}
				],
			 type : 'success', 
			 });
		  });
	
	/*==============================
	  NOTY TOP RIGHT
	================================*/
	
	jq('.alert_tr').click(function() {
		
		var noty_id = noty({
			layout : 'topRight',
			text: 'noty - a jquery notification library!',
			modal : true,
			type:'alert',
			
			 });
		  });

	jq('.error_tr').click(function() {
		
		var noty_id = noty({
			layout : 'topRight',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'error', 
			 });
		  });
		  
	jq('.success_tr').click(function() {
		
		var noty_id = noty({
			layout : 'topRight',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'success', 
			 });
		  });
		  
	jq('.info_tr').click(function() {
		
		var noty_id = noty({
			layout : 'topRight',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'information', 
			 });
		  });
	
	jq('.confirm_tr').click(function() {
		
		var noty_id = noty({
			layout : 'topRight',
			text: 'noty - a jquery notification library!',
			modal : true,
			buttons: [
				{type: 'button green', text: 'Ok', click: function($noty) {
		  
					// this = button element
					// $noty = $noty element
		  
					$noty.close();
					noty({force: true, text: 'You clicked "Ok" button', type: 'success',layout : 'topRight',modal : true,});
				  }
				},
				{type: 'button pink', text: 'Cancel', click: function($noty) {
					$noty.close();
					noty({force: true, text: 'You clicked "Cancel" button', type: 'error',layout : 'topRight',modal : true,});
				  }
				}
				],
			 type : 'success', 
			 });
		  });
		  
		  
	/*==============================
	  NOTY BOTTOM RIGHT
	================================*/
	
	jq('.alert_br').click(function() {
		
		var noty_id = noty({
			layout : 'bottomRight',
			text: 'noty - a jquery notification library!',
			modal : true,
			type:'alert',
			
			 });
		  });

	jq('.error_br').click(function() {
		
		var noty_id = noty({
			layout : 'bottomRight',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'error', 
			 });
		  });
		  
	jq('.success_br').click(function() {
		
		var noty_id = noty({
			layout : 'bottomRight',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'success', 
			 });
		  });
		  
	jq('.info_br').click(function() {
		
		var noty_id = noty({
			layout : 'bottomRight',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'information', 
			 });
		  });
	
	jq('.confirm_br').click(function() {
		
		var noty_id = noty({
			layout : 'bottomRight',
			text: 'noty - a jquery notification library!',
			modal : true,
			buttons: [
				{type: 'button green', text: 'Ok', click: function($noty) {
		  
					// this = button element
					// $noty = $noty element
		  
					$noty.close();
					noty({force: true, text: 'You clicked "Ok" button', type: 'success',layout : 'bottomRight',modal : true,});
				  }
				},
				{type: 'button pink', text: 'Cancel', click: function($noty) {
					$noty.close();
					noty({force: true, text: 'You clicked "Cancel" button', type: 'error',layout : 'bottomRight',modal : true,});
				  }
				}
				],
			 type : 'success', 
			 });
		  });
		  
		  
	/*==============================
	  NOTY BOTTOM LEFT
	================================*/
	
	jq('.alert_bl').click(function() {
		
		var noty_id = noty({
			layout : 'bottomLeft',
			text: 'noty - a jquery notification library!',
			modal : true,
			type:'alert',
			
			 });
		  });

	jq('.error_bl').click(function() {
		
		var noty_id = noty({
			layout : 'bottomLeft',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'error', 
			 });
		  });
		  
	jq('.success_bl').click(function() {
		
		var noty_id = noty({
			layout : 'bottomLeft',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'success', 
			 });
		  });
		  
	jq('.info_bl').click(function() {
		
		var noty_id = noty({
			layout : 'bottomLeft',
			text: 'noty - a jquery notification library!',
			modal : true,
			type : 'information', 
			 });
		  });
	
	jq('.confirm_bl').click(function() {
		
		var noty_id = noty({
			layout : 'bottomLeft',
			text: 'noty - a jquery notification library!',
			modal : true,
			buttons: [
				{type: 'button green', text: 'Ok', click: function($noty) {
		  
					// this = button element
					// $noty = $noty element
		  
					$noty.close();
					noty({force: true, text: 'You clicked "Ok" button', type: 'success',layout : 'bottomLeft',modal : true,});
				  }
				},
				{type: 'button pink', text: 'Cancel', click: function($noty) {
					$noty.close();
					noty({force: true, text: 'You clicked "Cancel" button', type: 'error',layout : 'bottomLeft',modal : true,});
				  }
				}
				],
			 type : 'success', 
			 });
		  });
	
	/*======================
	DRILL MINEU
	========================*/	

    	// BUTTONS
    	jq('.fg-button').hover(
    		function(){ jq(this).removeClass('ui-state-default').addClass('ui-state-focus'); },
    		function(){ jq(this).removeClass('ui-state-focus').addClass('ui-state-default'); }
    	);
    	
    	// MENUS    	
		jq('#flat').menu({ 
			content: jq('#flat').next().html(), // grab content from this page
			showSpeed: 400 
		});
		
		jq('#hierarchy').menu({
			content: jq('#hierarchy').next().html(),
			crumbDefaultText: ' '
		});
		
		jq('#hierarchybreadcrumb').menu({
			content: jq('#hierarchybreadcrumb').next().html(),
			backLink: false
		});
		
		// or from an external source
		$.get('menuContent.html', function(data){ // grab content from another page
			jq('#flyout').menu({ content: data, flyOut: true });
		});

/*Colapsible Widget*/
jq(".collapsible_widget").collapse({ head : ".widget_top",
                group : ".widget_content", show: function(){
				
                    this.animate({
                        opacity: 'toggle', 
                        height: 'toggle'
                    }, 300);
                },
                hide : function() {
                    
                    this.animate({
                        opacity: 'toggle', 
                        height: 'toggle'
                    }, 300);
                }
            });

// Slider
		jq( "#slider-range" ).slider({
			range: true,
			min: 0,
			max: 500,
			values: [ 75, 300 ],
			slide: function( event, ui ) {
				jq( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
			}
		});
		jq( "#amount" ).val( "$" + jq( "#slider-range" ).slider( "values", 0 ) +
			" - $" + jq( "#slider-range" ).slider( "values", 1 ) );
	
	jq( "#master" ).slider({
			value: 60,
			orientation: "horizontal",
			range: "min",
			animate: true
		});
		// setup graphic EQ
		jq( "#eq > span" ).each(function() {
			// read initial values from markup and remove that
			var value = parseInt( jq( this ).text(), 10 );
			jq( this ).empty().slider({
				value: value,
				range: "min",
				animate: true,
				orientation: "vertical"
			});
		});
	
	
	
			
//CLE EDITOR
		
		jq("#txt_editor").cleditor({
		width:"100%", 
		height:"100%",
		bodyStyle: "margin: 10px; font: 12px Arial,Verdana; cursor:text"
	});
	
//TIPSY
jq('.tip_top').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 's', });// nw | n | ne | w | e | sw | s | se
	
	jq('.go_to a').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 'e', });// nw | n | ne | w | e | sw | s | se

jq('#primary_nav li a').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 'w', });// nw | n | ne | w | e | sw | s | se


jq('.tipTop').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 's', });// nw | n | ne | w | e | sw | s | se

jq('.tipLeft').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 'e', });// nw | n | ne | w | e | sw | s | se

jq('.tipRight').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 'w', });// nw | n | ne | w | e | sw | s | se
	
jq('.tipBot').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 'n', });// nw | n | ne | w | e | sw | s | se

jq('.tipTopL').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 'sw', });// nw | n | ne | w | e | sw | s | se
	
jq('.tipTopR').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 'se', });// nw | n | ne | w | e | sw | s | se

jq('.tipBotL').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 'nw', });// nw | n | ne | w | e | sw | s | se

jq('.tipBotR').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 'ne', });// nw | n | ne | w | e | sw | s | se

jq('.action-icons').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 's', });// nw | n | ne | w | e | sw | s | se

jq('.post_action_bar a, .invoice_action_bar a').tipsy({
	title: 'title',
	fade: 'out',     // fade tooltips in/out?
    gravity: 's', });// nw | n | ne | w | e | sw | s | se


//MIN Chart 

  jq("span.pie").peity("pie",{
	  diameter: 150,
	  colours: ["#abd037", "#1889cb"]
	  })
  jq(".line").peity("line",{
	  width: 100
	  })
  jq(".bar").peity("bar",{
   width: 100
  })
   jq(".activities_chart").peity("bar",{
   width: 100,
   height:30,
   colour: "#9ec22d"
  })
   jq(".visit_chart").peity("bar",{
   width: 100,
   height:30,
   colour: "#1889cb"
  })

  // Set a custom colour and/or diameter.
  jq(".diameter span").peity("pie", {
    colours: function() {
      return ["#dddddd", this.getAttribute("data-colour")]
    },
    diameter: function() {
      return this.getAttribute("data-diameter")
    }
  })

  // Simple evented example.
  jq("select").change(function(){
    jq(this)
      .siblings("span.graph")
      .text(jq(this).val() + "/" + 5).change()
  }).change()

  var chartUpdate = function(event, value) {
    jq("#notice").text(
      "Chart updated: " + value
    )
  }

  jq("span.graph").peity("pie").bind("chart:changed", chartUpdate)




/*====================
	ANIMATED PROGRESS BAR
	======================*/
    jQuery.fn.anim_progressbar = function (aOptions) {
        // def values
        var iCms = 1000;
        var iMms = 60 * iCms;
        var iHms = 3600 * iCms;
        var iDms = 24 * 3600 * iCms;

        // def options
        var aDefOpts = {
            start: new Date(), // now
            finish: new Date().setTime(new Date().getTime() + 60 * iCms), // now + 60 sec
            interval: 100
        }
        var aOpts = jQuery.extend(aDefOpts, aOptions);
        var vPb = this;

        // each progress bar
        return this.each(
            function() {
                var iDuration = aOpts.finish - aOpts.start;

                // calling original progressbar
                jq(vPb).children('.pbar').progressbar();

                // looping process
                var vInterval = setInterval(
                    function(){
                        var iLeftMs = aOpts.finish - new Date(); // left time in MS
                        var iElapsedMs = new Date() - aOpts.start, // elapsed time in MS
                            iDays = parseInt(iLeftMs / iDms), // elapsed days
                            iHours = parseInt((iLeftMs - (iDays * iDms)) / iHms), // elapsed hours
                            iMin = parseInt((iLeftMs - (iDays * iDms) - (iHours * iHms)) / iMms), // elapsed minutes
                            iSec = parseInt((iLeftMs - (iDays * iDms) - (iMin * iMms) - (iHours * iHms)) / iCms), // elapsed seconds
                            iPerc = (iElapsedMs > 0) ? iElapsedMs / iDuration * 100 : 0; // percentages

                        // display current positions and progress
                        jq(vPb).children('.percent').html('<b>'+iPerc.toFixed(1)+'%</b>');
                        jq(vPb).children('.elapsed').html(iDays+' days '+iHours+'h:'+iMin+'m:'+iSec+'s</b>');
                        jq(vPb).children('.pbar').children('.ui-progressbar-value').css('width', iPerc+'%');

                        // in case of Finish
                        if (iPerc >= 100) {
                            clearInterval(vInterval);
                            jq(vPb).children('.percent').html('<b>100%</b>');
                            jq(vPb).children('.elapsed').html('Finished');
                        }
                    } ,aOpts.interval
                );
            }
        );
    }

    // default mode
    // jq('#progress1,#progress_s').anim_progressbar();

    // from second #5 till 15
    // var iNow = new Date().setTime(new Date().getTime() + 5 * 1000); // now plus 5 secs
    // var iEnd = new Date().setTime(new Date().getTime() + 15 * 1000); // now plus 15 secs
    // jq('#progress2,#progress_p').anim_progressbar({start: iNow, finish: iEnd, interval: 100});

    // we will just set interval of updating to 1 sec
    // jq('#progress3,#progress_n').anim_progressbar({interval: 1000});
	
	
	/*colorbox*/
				jq(".group1").colorbox({rel:'group1'});
				jq(".portfolio a").colorbox();
				jq(".group4").colorbox({rel:'group4', slideshow:true});
				jq(".inline").colorbox({inline:true, width:"50%"});
				jq(".ajax").colorbox();
				jq(".youtube").colorbox({iframe:true, innerWidth:425, innerHeight:344});
				jq(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
					
					
					/*
 * SimpleModal Confirm Modal Dialog
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: confirm.js 254 2010-07-23 05:14:44Z emartin24 $
 */

jQuery(function ($) {
	jq('#confirm-dialog input.confirm, #confirm-dialog a.confirm').click(function (e) {
		e.preventDefault();

		// example of calling the confirm function
		// you must use a callback function to perform the "yes" action
		confirm("Continue to the SimpleModal Project page?", function () {
			window.location.href = 'http://www.ericmmartin.com/projects/simplemodal/';
		});
	});
});

function confirm(message, callback) {
	jq('#confirm').modal({
		closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
		position: ["20%",],
		overlayId: 'confirm-overlay',
		containerId: 'confirm-container', 
		onShow: function (dialog) {
			var modal = this;

			jq('.message', dialog.data[0]).append(message);

			// if the user clicks "yes"
			jq('.yes', dialog.data[0]).click(function () {
				// call the callback
				if ($.isFunction(callback)) {
					callback.apply();
				}
				// close the dialog
				modal.close(); // or $.modal.close();
			});
		}
	});
}


/*
 * SimpleModal OSX Style Modal Dialog
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: osx.js 238 2010-03-11 05:56:57Z emartin24 $
 */

jQuery(function ($) {
	var OSX = {
		container: null,
		init: function () {
			jq("input.osx, a.osx").click(function (e) {
				e.preventDefault();	

				jq("#osx-modal-content").modal({
					overlayId: 'osx-overlay',
					containerId: 'osx-container',
					closeHTML: null,
					minHeight: 80,
					opacity: 65, 
					position: ['0',],
					overlayClose: true,
					onOpen: OSX.open,
					onClose: OSX.close
				});
			});
		},
		open: function (d) {
			var self = this;
			self.container = d.container[0];
			d.overlay.fadeIn('slow', function () {
				jq("#osx-modal-content", self.container).show();
				var title = jq("#osx-modal-title", self.container);
				title.show();
				d.container.slideDown('slow', function () {
					setTimeout(function () {
						var h = jq("#osx-modal-data", self.container).height()
							+ title.height()
							+ 20; // padding
						d.container.animate(
							{height: h}, 
							200,
							function () {
								jq("div.close", self.container).show();
								jq("#osx-modal-data", self.container).show();
							}
						);
					}, 300);
				});
			})
		},
		close: function (d) {
			var self = this; // this = SimpleModal object
			d.container.animate(
				{top:"-" + (d.container.height() + 20)},
				500,
				function () {
					self.close(); // or $.modal.close();
				}
			);
		}
	};

	OSX.init();

});
/*
 * SimpleModal Basic Modal Dialog
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: basic.js 254 2010-07-23 05:14:44Z emartin24 $
 */

jQuery(function ($) {
	// Load dialog on page load
	//jq('#basic-modal-content').modal();

	// Load dialog on click
	jq('.basic-modal').click(function (e) {
		jq('#basic-modal-content').modal();

		return false;
	});
});

// color picker

jq('.colorpicker').colorpicker();
jq('#widget_tab ul').idTabs(); 
jq('#widget_leftTab ul').idTabs(function(id,list,set){ 
    jq("a",set).removeClass("p_selected") 
    .filter("[href='"+id+"']",set).addClass("p_selected"); 
    for(i in list) 
      jq(list[i]).hide(); 
    jq(id).fadeIn(); 
    return false; 
  });
  
jq("#adv2").idTabs(function(id,list,set){ 
    jq("a",set).removeClass("selected") 
    .filter("[href='"+id+"']",set).addClass("selected"); 
    for(i in list) 
      jq(list[i]).hide(); 
    jq(id).fadeIn(); 
    return false; 
  }); 
  
  jq("#commentForm").validate();
  jq("#regitstraion_form").validate({
	  rules: {
			name: "required",
			username: {
				required: true,
				minlength: 2
			},
			password: {
				required: true,
				minlength: 5
			},
			confirm_password: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			},
			agree: "required"
		},
		messages: {
			name: "Please enter your firstname",
			username: {
				required: "Please enter a username",
				minlength: "Your username must consist of at least 2 characters"
			},
			password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			confirm_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			},
			email: "Please enter a valid email address",
			agree: "Please accept our policy"
		}
		});
  
  jq(function() {
	
	var container = jq('div.container');
	// validate the form when it is submitted
	var validator = jq("#form2").validate({
		errorContainer: container,
		errorLabelContainer: jq("ol", container),
		wrapper: 'li',
		meta: "validate"
	});
	
	
});
	
  
  jq("#form103").validate();

  
jq(".elem_extend").EnableMultiField({
	linkText: 'Add More',
    linkClass: 'addMoreFields',
	removeLinkText: 'Remove',
    removeLinkClass: 'removeFields',
	
	});


jq('.item .delete').click(function(){
		
		var elem = jq(this).closest('.item');
		
		$.confirm({
			'title'		: 'Delete Confirmation',
			'message'	: 'You are about to delete this item. <br />It cannot be restored at a later time! Continue?',
			'buttons'	: {
				'Yes'	: {
					'class'	: 'btn_30_blue',
					'action': function(){
						elem.slideUp();
					}
				},
				'No'	: {
					'class'	: 'btn_30_blue',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
		
	});



jq('.confirm_dialog').click(function(){
		
		
		
		$.confirm({
			'title'		: 'Delete Confirmation',
			'message'	: 'You are about to delete this item. <br />It cannot be restored at a later time! Continue?',
			'buttons'	: {
				'Yes'	: {
					'class'	: 'yes',
				},
				'No'	: {
					'class'	: 'no',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
		
	});


//mosaic
jq('.circle').mosaic({
					opacity		:	0.8			//Opacity for overlay (0-1)
				});
				
				jq('.fade').mosaic();
				
				jq('.bar').mosaic({
					animation	:	'slide'		//fade or slide
				});
				
				jq('.bar2').mosaic({
					animation	:	'slide'		//fade or slide
				});
				
				jq('.bar3').mosaic({
					animation	:	'slide',	//fade or slide
					anchor_y	:	'top'		//Vertical anchor position
				});
				
				jq('.cover').mosaic({
					animation	:	'slide',	//fade or slide
					hover_x		:	'400px'		//Horizontal position on hover
				});
				
				jq('.cover2').mosaic({
					animation	:	'slide',	//fade or slide
					anchor_y	:	'top',		//Vertical anchor position
					hover_y		:	'80px'		//Vertical position on hover
				});
				
				jq('.cover3').mosaic({
					animation	:	'slide',	//fade or slide
					hover_x		:	'400px',	//Horizontal position on hover
					hover_y		:	'300px'		//Vertical position on hover
				});
		 

	
	
	jq("#suggest1").focus().autocomplete(cities);
	jq("#month").autocomplete(months, {
		minChars: 0,
		max: 12,
		autoFill: true,
		mustMatch: true,
		matchContains: false,
		scrollHeight: 220,
		formatItem: function(data, i, total) {
			// don't show the current month in the list of values (for whatever reason)
			if ( data[0] == months[new Date().getMonth()] ) 
				return false;
			return data[0];
		}
	});
	jq("#suggest13").autocomplete(emails, {
		minChars: 0,
		width: 310,
		matchContains: "word",
		autoFill: false,
		formatItem: function(row, i, max) {
			return i + "/" + max + ": \"" + row.name + "\" [" + row.to + "]";
		},
		formatMatch: function(row, i, max) {
			return row.name + " " + row.to;
		},
		formatResult: function(row) {
			return row.to;
		}
	});
	jq('.checkall').checkAll('.tr_select input:checkbox');	
	
	});
	
jq(function(){

	// Blur images on mouse over
	jq(".portfolio a").hover( function(){ 
		jq(this).children("img").animate({ opacity: 0.75 }, "fast"); 
	}, function(){ 
		jq(this).children("img").animate({ opacity: 1.0 }, "slow"); 
	}); 
	

	// Clone portfolio items to get a second collection for Quicksand plugin
	var $portfolioClone = jq(".portfolio").clone();
	
	// Attempt to call Quicksand on every click event handler
	jq(".filter a").click(function(e){
		
		jq(".filter li").removeClass("current");	
		
		// Get the class attribute value of the clicked link
		var $filterClass = jq(this).parent().attr("class");

		if ( $filterClass == "all" ) {
			var $filteredPortfolio = $portfolioClone.find("li");
		} else {
			var $filteredPortfolio = $portfolioClone.find("li[data-type~=" + $filterClass + "]");
		}
		
		// Call quicksand
		jq(".portfolio").quicksand( $filteredPortfolio, { 
			duration: 800, 
			easing: 'easeInOutQuad' 
		}, function(){
			
			// Blur newly cloned portfolio items on mouse over and apply prettyPhoto
			jq(".portfolio a").hover( function(){ 
				jq(this).children("img").animate({ opacity: 0.75 }, "fast"); 
			}, function(){ 
				jq(this).children("img").animate({ opacity: 1.0 }, "slow"); 
			}); 
			
		});


		jq(this).parent().addClass("current");

		// Prevent the browser jump to the link anchor
		e.preventDefault();
	});
});


jq(function() {
	// validate signup form on keyup and submit
	// var validator = jq("#signupform").validate({
		// rules: {
			// firstname: "required",
			// lastname: "required",
			// username: {
				// required: true,
				// minlength: 2,
				// remote: "users.php"
			// },
			// password: {
				// required: true,
				// minlength: 5
			// },
			// password_confirm: {
				// required: true,
				// minlength: 5,
				// equalTo: "#password"
			// },
			// email: {
				// required: true,
				// email: true,
				// remote: "emails.php"
			// },
			// dateformat: "required",
			// terms: "required"
		// },
		// messages: {
			// firstname: "Enter your firstname",
			// lastname: "Enter your lastname",
			// username: {
				// required: "Enter a username",
				// minlength: jQuery.format("Enter at least {0} characters"),
				// remote: jQuery.format("{0} is already in use")
			// },
			// password: {
				// required: "Provide a password",
				// rangelength: jQuery.format("Enter at least {0} characters")
			// },
			// password_confirm: {
				// required: "Repeat your password",
				// minlength: jQuery.format("Enter at least {0} characters"),
				// equalTo: "Enter the same password as above"
			// },
			// email: {
				// required: "Please enter a valid email address",
				// minlength: "Please enter a valid email address",
				// remote: jQuery.format("{0} is already in use")
			// }
		// },
		
		// // set this class to error-labels to indicate valid fields
		// success: function(label) {
			// // set &nbsp; as text for IE
			// label.html("&nbsp;").addClass("checked");
		// }
	// });
	
	

});
	
	

			
	/*======================
	DATA TABLE
	========================*/
	jq(function() {
	 jq('.data_tbl').dataTable({   
	"sPaginationType": "full_numbers",
	"iDisplayLength": 10,
	"oLanguage": {
        "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",	
    },
	 "sDom": '<"table_top"fl<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>'
	 
	});
	jq("div.table_top select").addClass('tbl_length');
	jq(".tbl_length").chosen({
		disable_search_threshold: 4	
	});
		
	});
	
	
	jq(function() {
	
	jq('#data_tbl_tools').dataTable({   
"sPaginationType": "full_numbers",
					"iDisplayLength": 10,
					"oLanguage": {
					"sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",	
					},
					"sDom":  '<"table_top"fl<"clear">>,<"tbl_tools"CT<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>',
				
					"oTableTools": {
						"sSwfPath": "swf/copy_cvs_xls_pdf.swf"
					}
				} );
					jq("div.table_top select").addClass('tbl_length');
					jq(".tbl_length").chosen({
						disable_search_threshold: 4	
					});		
	});
	

	
	jq(function() {
	jq('#action_tbl').dataTable({   
	 "aoColumnDefs": [
						{ "bSortable": false, "aTargets": [ 0 , 7 ] }
					],
					"aaSorting": [[1, 'asc']],
	"sPaginationType": "full_numbers",
	"iDisplayLength": 10,
	"oLanguage": {
        "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",	
    },
	 "sDom": '<"table_top"fl<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>'
	 
	});
	jq("div.table_top select").addClass('tbl_length');
	jq(".tbl_length").chosen({
		disable_search_threshold: 4	
	});
		
	});
	
	jq(function() {
	
	jq('.data_editable').dataTable({   
	"sPaginationType": "full_numbers",
	"iDisplayLength": 10,
	"oLanguage": {
        "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",	
    },
	 "sDom": '<"table_top"fl<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>'
	  /*
        "fnDrawCallback": function () {
            jq('.data_editable tbody td').editable();
        },*/
	 
	});
	jq("div.table_top select").addClass('tbl_length');
	jq(".tbl_length").chosen({
		disable_search_threshold: 4	
	});
	    /* Apply the jEditable handlers to the table */
    jq('.data_editable td').editable( '../examples_support/editable_ajax.php', {
        "callback": function( sValue, y ) {
            var aPos = oTable.fnGetPosition( this );
            oTable.fnUpdate( sValue, aPos[0], aPos[1] );
        },
        "submitdata": function ( value, settings ) {
            return {
                "row_id": this.parentNode.getAttribute('id'),
                "column": oTable.fnGetPosition( this )[2]
            };
        }
    } );		
			
	});
	
	
	jq(function() {
	
	jq('.data_tbl_search').dataTable({   
	  "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": false,
        "bInfo": false,
        "bAutoWidth": false,
	"oLanguage": {
        "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",	
    },
	 "sDom": '<"table_top"fl<"clear">>,<"table_content"t>'
	 
	});		
		
	
	
	});
	
	
	/* Formating function for row details */
function fnFormatDetails ( oTable, nTr )
{
    var aData = oTable.fnGetData( nTr );
    var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
    sOut += '<tr><td>Rendering engine:</td><td>'+aData[1]+' '+aData[4]+'</td></tr>';
    sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
    sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
    sOut += '</table>';
     
    return sOut;
}
 
jq(function() {
    /*
     * Insert a 'details' column to the table
     */
    var nCloneTh = document.createElement( 'th' );
    var nCloneTd = document.createElement( 'td' );
    nCloneTd.innerHTML = '<img src="images/details_open.png">';
    nCloneTd.className = "center";
     
    jq('.tbl_details thead tr').each( function () {
        this.insertBefore( nCloneTh, this.childNodes[0] );
    } );
     
    jq('.tbl_details tbody tr').each( function () {
        this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
    } );
     
    /*
     * Initialse DataTables, with no sorting on the 'details' column
     */
    var oTable = jq('.tbl_details').dataTable( {
		
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0 ] }
        ],
        "aaSorting": [[1, 'asc']],
		"sPaginationType": "full_numbers",
	"iDisplayLength": 10,
	"oLanguage": {
        "sLengthMenu": "<span class='lenghtMenu'> _MENU_</span><span class='lengthLabel'>Entries per page:</span>",	
    },
	 "sDom": '<"table_top"fl<"clear">>,<"table_content"t>,<"table_bottom"p<"clear">>'

		
		
    });
	jq("div.table_top select").addClass('tbl_length');
	jq(".tbl_length").chosen({
		disable_search_threshold: 4	
	});
     
    /* Add event listener for opening and closing details
     * Note that the indicator for showing which row is open is not controlled by DataTables,
     * rather it is done here
     */
    jq('.tbl_details tbody td img').live('click', function () {
        var nTr = jq(this).parents('tr')[0];
        if ( oTable.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
            this.src = "images/details_open.png";
            oTable.fnClose( nTr );
        }
        else
        {
            /* Open this row */
            this.src = "images/details_close.png";
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
        }
    } );
} );
	



 jq(function() {
                /* tells us if we dragged the box */
                var dragged = false;
				
                /* timeout for moving the mox when scrolling the window */
                var moveBoxTimeout;
				
                /* make the actionsBox draggable */
                jq('#actionsBox').draggable({
                    start: function(event, ui) {
                        dragged = true;
                    },
                    stop: function(event, ui) {
                        var $actionsBox = jq('#actionsBox');
                        /*
                        calculate the current distance from the window's top until the element
                        this value is going to be used further, to move the box after we scroll
                         */
                        $actionsBox.data('distanceTop',parseFloat($actionsBox.css('top'),10) - jq(document).scrollTop());
                    }
                });
				
                /*
                when clicking on an input (checkbox),
                change the class of the table row,
                and show the actions box (if any checked)
                 */
                jq('#action_tbl input[type="checkbox"]').bind('click',function(e) {
                    var $this = jq(this);
                    if($this.is(':checked'))
                        $this.parents('tr:first').addClass('selected');
                    else
                        $this.parents('tr:first').removeClass('selected');
                    showActionsBox();
                });
				
                function showActionsBox(){
                    /* number of checked inputs */
                    var BoxesChecked = jq('#action_tbl input:checked').length;
                    /* update the number of checked inputs */
                    jq('#cntBoxMenu').html(BoxesChecked);
                    /*
                    if there is at least one selected, show the BoxActions Menu
                    otherwise hide it
                     */
                    var $actionsBox = jq('#actionsBox');
                    if(BoxesChecked > 0){
                        /*
                        if we didn't drag, then the box stays where it is
                        we know that that position is the document current top
                        plus the previous distance that the box had relative to the window top (distanceTop)
                         */
                        if(!dragged)
                            $actionsBox.stop(true).animate({'top': parseInt(15 + jq(document).scrollTop()) + 'px','opacity':'1'},500);
                        else
                            $actionsBox.stop(true).animate({'top': parseInt(jq(document).scrollTop() + $actionsBox.data('distanceTop')) + 'px','opacity':'1'},500);
                    }
                    else{
                        $actionsBox.stop(true).animate({'top': parseInt(jq(document).scrollTop() - 50) + 'px','opacity':'0'},500,function(){
                            jq(this).css('left','50%');
                            dragged = false;
                            /* if the submenu was open we hide it again */
                            var $toggleBoxMenu = jq('#toggleBoxMenu');
                            if($toggleBoxMenu.hasClass('closed')){
                                $toggleBoxMenu.click();
                            }
                        });
                    }
                }
				
                /*
                when scrolling, move the box to the right place
                 */
                jq(window).scroll(function(){
                    clearTimeout(moveBoxTimeout);
                    moveBoxTimeout = setTimeout(showActionsBox,500);
                });
				
                /* open sub box menu for other actions */
                jq('#toggleBoxMenu').toggle(
                function(e){
                    jq(this).addClass('closed').removeClass('open');
                    jq('#actionsBox .submenu').stop(true,true).slideDown();
                },
                function(e){
                    jq(this).addClass('open').removeClass('closed');
                    jq('#actionsBox .submenu').stop(true,true).slideUp();
                }
            );
				
                /*
                close the actions box menu:
                hides it, and then removes the element from the DOM,
                meaning that it will no longer appear
                 */
                jq('#closeBoxMenu').bind('click',function(e){
                    jq('#actionsBox').animate({'top':'-50px','opacity':'0'},1000,function(){
                        jq(this).remove();
                    });
                });
				
                /*
                as an example, for all the actions (className:box_action)
                alert the values of the checked inputs
                 */
                jq('#actionsBox .box_action').bind('click',function(e){
                    var ids = '';
                    jq('#action_tbl input:checked').each(function(e,i){
                        var $this = jq(this);
                        ids += 'id : ' + $this.attr('id') + ' , value : ' + $this.val() + '\n';
                    });
                    alert('checked inputs:\n'+ids);
                });
            });
			