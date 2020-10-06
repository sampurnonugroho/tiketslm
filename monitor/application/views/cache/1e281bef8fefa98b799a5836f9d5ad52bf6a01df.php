<?php $__env->startSection('content'); ?>
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>depend/jquery-confirm/css/jquery-confirm.css"/>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/datatables/datatables.min.css"/>
 
	<script type="text/javascript" src="<?=base_url()?>/assets/datatables/datatables.min.js"></script>
	
	<script type="text/javascript" src="<?=base_url()?>assets/jquery.scannerdetection.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/notify.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/jquery.inputmask.js"></script>
	
	<style>
		.jconfirm .jconfirm-holder {
			max-height: 100%;
			padding: 54px 450px;
				padding-top: 54px;
				padding-bottom: 54px;
		}
		#preview {
			float: right; 
			height: 803px; 
			width: 100%; 
			border: 1px solid #666; 
			-moz-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			-webkit-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
		}
		.dataTables_filter {
			width: 40%;
			float: left !important;
			text-align: left !important;
		}
		.dataTables_length {
			float: right !important;
		}
		
		.select2-container {
			z-index: 99999999 !important;
		}
		
		.dataTables_paginate #datatable_previous {
			width: 60px !important;
		}
		
		.dataTables_paginate #datatable_next {
			width: 60px !important;
		}
		
		
		.view {
			margin: auto;
			width: 100%;
		}

		.wrapper {
			position: relative;
			overflow: auto;
			border: 1px solid black;
			white-space: nowrap;
		}

		.sticky-col {
			position: sticky;
			position: -webkit-sticky;
			background-color: white;
		}

		.first-col {
			width: 50px;
			min-width: 50px;
			max-width: 50px;
			left: 0px;
		}

		.second-col {
			width: 150px;
			min-width: 150px;
			max-width: 150px;
			left: 50px;
		}
		
		button.red, .red button, .big-button.red, .red .big-button {
			color: white;
			border-color: #bf3636 #5d0000 #0a0000;
			background: #790000 url(../images/old-browsers-bg/button-element-red-bg.png) repeat-x top;
			background: -moz-linear-gradient(top,white,#ca3535 4%,#790000);
			background: -webkit-gradient(linear,left top, left bottom,from(white),to(#790000),color-stop(0.03, #ca3535));
		}
		
		button.yellow, .yellow button, .big-button.yellow, .yellow .big-button {
			color: black;
			border-color: #ffcc00 #ffcc00 #ffcc00;
			background: #790000 url(../images/old-browsers-bg/button-element-yellow-bg.png) repeat-x top;
			background: -moz-linear-gradient(top,white,#ffff00 4%,#ffcc00);
			background: -webkit-gradient(linear,left top, left bottom,from(white),to(#ffcc00),color-stop(0.03, #ffff00));
		}
		
		button.green, .green button, .big-button.green, .green .big-button {
			color: black;
			border-color: #99ff33 #99ff33 #99ff33;
			background: #790000 url(../images/old-browsers-bg/button-element-green-bg.png) repeat-x top;
			background: -moz-linear-gradient(top,white,#99ff66 4%,#33cc33);
			background: -webkit-gradient(linear,left top, left bottom,from(white),to(#33cc33),color-stop(0.03, #99ff66));
		}
	</style>
	
	<article class="container_12">
		
		<section class="grid_12">
			<div class="preview_pdf" hidden>
				<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview' type="button">Close</button>
				<iframe id="preview" name="preview" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
			</div>
			<div style="float: right">
				<label for="search">Search by date : </label>
				<input type="text" name="simple-calendar" id="search" class="datepicker_search">
			</div>
			<div class="widget_wrap preview_table">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6><?=ucwords(str_replace("_", " ", $active_menu))?> Data</h6>
				</div>
				<div class="widget_content" id="content_table">
					
				</div>
			</div>
		</section>
		
		<div id="html_content" hidden>
			<form class="form mysets-area">
				<fieldset>
					<p>
						<label>ID BANK</label>
						<select id="seal" class="js-example-basic-single2 full-width">- select cabang -</option></select>
					</p>
				</fieldset>
			</form>
		</div>
		
		<div id="html_content_verify" hidden>
			<form class="form mysets-area">
				<fieldset>
					<p>
						<label>ID BANK</label>
						<select id="sealxx" class="js-example-basic-single2xxx full-width">- select cabang -</option></select>
					</p>
				</fieldset>
			</form>
		</div>
	
		<div class="clear"></div>
	</article>
	
	
	<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
	<script src="<?=base_url()?>depend/js/jquery-ui-1.8.18.custom.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	<script src="<?=base_url()?>depend/js/full-calendar.jquery.js"></script>
	
	
	<script>
		jq341 = jQuery.noConflict(true);
		jq3412 = jQuery.noConflict(true);
		
		console.log(jq341().jquery);
		console.log(jq3412().jquery);
		
		setInterval(get_data_table, 1000);
			
		jq341.get("<?=base_url()?>all_problem/show_table", function(response){
			jq341('#content_table div').remove();
			jq341('#content_table').html(response);
		});
				
		function get_data_table() {
			jq341.get("<?=base_url()?>all_problem/check_data", function(response){
				if(response>0) {
					console.log("UPDATED");
					jq341.get("<?=base_url()?>all_problem/update_status", function(response){
						console.log(response)
						if(response=="success") {
							jq341.get("<?=base_url()?>all_problem/show_table", function(response){
								jq341('#content_table div').remove();
								jq341('#content_table').html(response);
							});
						}
					});
				}
			});		
		}
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>