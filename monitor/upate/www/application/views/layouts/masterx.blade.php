<!doctype html>
<!--[if lt IE 8 ]><html lang="en" class="no-js ie ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie"><![endif]-->
<!--[if (gt IE 8)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>PORTAL MONITORING SYSTEM</title>
	<meta name="description" content="">
	<meta name="author" content="">
	
	<!-- Combined stylesheets load -->
	<!-- Load either 960.gs.fluid or 960.gs to toggle between fixed and fluid layout -->
	<link href="<?=base_url()?>assets/constellation/assets/css/mini3537.css?files=reset,common,form,standard,960.gs.fluid,simple-lists,block-lists,planning,table,calendars,wizard,gallery" rel="stylesheet" type="text/css">
	
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link rel="icon" type="image/png" href="favicon-large.png">
	<link href="<?=base_url()?>assets/constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<!-- Modernizr for support detection, all javascript libs are moved right above </body> for better performance -->
	<script src="<?=base_url()?>assets/constellation/assets/js/libs/modernizr.custom.min.js"></script>
	
	
	<script src="<?=base_url()?>assets/constellation/assets/equipment/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<link href="<?=base_url()?>assets/constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>assets/constellation/assets/equipment/select2.min.js"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAb7d-G5Ea9j3X_haj37bSPJkSN7PpAp7I&libraries=places"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/constellation/assets/js/ContextMenu.js"></script>
	<style>
		/*	start styles for the ContextMenu	*/
        .context_menu {
            background-color: white;
            border: 1px solid gray;
        }

        .context_menu_item {
            padding: 3px 6px;
        }

        .context_menu_item:hover {
            background-color: #CCCCCC;
        }

        .context_menu_separator {
            background-color: gray;
            height: 1px;
            margin: 0;
            padding: 0;
        }
		
		.controls {
			margin-top: 10px;
			border: 1px solid transparent;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			height: 40px;
			color: rgb(86, 86, 86);
			font-family: Roboto, Arial, sans-serif;
			-moz-user-select: none;
			font-size: 18px;
			background-color: rgb(255, 255, 255);
			padding: 0px 17px;
			border-bottom-right-radius: 2px;
			border-top-right-radius: 2px;
			background-clip: padding-box;
			box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px;
			min-width: 64px;
			border-left: 0px none;
			outline: currentcolor none medium;
		}
		
		#searchInput {
			background-color: #fff;
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
			margin-left: 12px;
			padding: 0 11px 0 13px;
			text-overflow: ellipsis;
			width: 50%;
		}

		#searchInput:focus {
			border-color: #4d90fe;
		}

		ul#geoData {
			text-align: left;
			font-weight: bold;
			margin-top: 10px;
		}

		ul#geoData span {
			font-weight: normal;
		}
		
		.pac-container {
			z-index: 999990 !important;
		}
		
		.select2-container {
			z-index: 999999 !important;
		}
		
		.block-content .no-margin.last-child, .block-content .message.no-margin.last-child {
			margin-top: 25px !important;
		}
		
		.no-margin .block-controls:first-child {
			height: 34px !important;
		}
	</style>
</head>

<body>
	
	@yield('content')
	
	<!-- Combined JS load -->
	<script src="<?=base_url()?>assets/constellation/assets/js/minia4f1.php?files=libs/jquery-1.6.3.min,old-browsers,libs/jquery.hashchange,jquery.accessibleList,searchField,common,standard,jquery.tip,jquery.contextMenu,jquery.modal,list"></script>
	<!--[if lte IE 8]><script src="<?=base_url()?>assets/constellation/assets/js/standard.ie.js"></script><![endif]-->
	
	<!-- Plugins -->
	<script src="<?=base_url()?>assets/constellation/assets/js/libs/jquery.dataTables.min.js"></script>
	<script src="<?=base_url()?>assets/constellation/assets/js/libs/jquery.datepick/jquery.datepick.min.js"></script>
</body>
</html>