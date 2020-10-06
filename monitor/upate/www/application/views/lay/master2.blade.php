<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width" />
    <title>B.I.M.A</title>
    <link href="<?=base_url()?>depend/css/reset.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/layout.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/themes.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/typography.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/styles.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/shCore.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/jquery.jqplot.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/data-table.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/form.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/ui-elements.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/wizard.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/sprite.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/gradient.css" rel="stylesheet" type="text/css">
	
	<link href="<?=base_url()?>constellation/assets/css/mini3537.css?files=reset,common,form,standard,960.gs.fluid,simple-lists,block-lists,planning,table,calendars,wizard,gallery" rel="stylesheet" type="text/css">
    <!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
    <!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
    <!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->
    <!-- Jquery -->
    <script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
    <script src="<?=base_url()?>depend/js/jquery-ui-1.8.18.custom.min.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.ui.touch-punch.js"></script>
    <script src="<?=base_url()?>depend/js/chosen.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/uniform.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/bootstrap-dropdown.js"></script>
    <script src="<?=base_url()?>depend/js/bootstrap-colorpicker.js"></script>
    <script src="<?=base_url()?>depend/js/sticky.full.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.noty.js"></script>
    <script src="<?=base_url()?>depend/js/selectToUISlider.jQuery.js"></script>
    <script src="<?=base_url()?>depend/js/fg.menu.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.tagsinput.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.cleditor.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.tipsy.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.peity.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.simplemodal.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.jBreadCrumb.1.1.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.colorbox-min.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.idTabs.min.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.multiFieldExtender.min.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.confirm.js"></script>
    <script src="<?=base_url()?>depend/js/elfinder.min.js"></script>
    <script src="<?=base_url()?>depend/js/accordion.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/autogrow.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/check-all.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/data-table.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/ZeroClipboard.js"></script>
    <script src="<?=base_url()?>depend/js/TableTools.min.js"></script>
    <script src="<?=base_url()?>depend/js/jeditable.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/ColVis.min.js"></script>
    <script src="<?=base_url()?>depend/js/duallist.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/easing.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/full-calendar.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/input-limiter.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/inputmask.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/iphone-style-checkbox.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/meta-data.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/quicksand.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/raty.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/smart-wizard.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/stepy.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/treeview.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/ui-accordion.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/vaidation.jquery.js"></script>
    <script src="<?=base_url()?>depend/js/mosaic.1.0.1.min.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.collapse.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.cookie.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.autocomplete.min.js"></script>
    <script src="<?=base_url()?>depend/js/localdata.js"></script>
    <script src="<?=base_url()?>depend/js/excanvas.min.js"></script>
    <script src="<?=base_url()?>depend/js/jquery.jqplot.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.dateAxisRenderer.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.cursor.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.logAxisRenderer.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.canvasTextRenderer.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.highlighter.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.pieRenderer.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.barRenderer.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.categoryAxisRenderer.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.pointLabels.min.js"></script>
    <script src="<?=base_url()?>depend/js/chart-plugins/jqplot.meterGaugeRenderer.min.js"></script>
    <script src="<?=base_url()?>depend/js/custom-scripts2.js"></script>
	
	<style>
		#theme-default .acitem li a.active {
			background: #f9f9f9 !important;
			color: #333 !important;
		}
	
		.no-js #loader { display: none;  }
		.js #loader { display: block; position: absolute; left: 100px; top: 0; }
		.se-pre-con {
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background: url(<?=base_url()?>depend/images/Preloader_8.gif) center no-repeat #fff;
		}
	</style>
</head>

<body id="theme-default" class="full_block">
    <div class="se-pre-con"></div>
    <div id="actionsBox" class="actionsBox">
		<div id="actionsBoxMenu" class="menu">
			<span id="cntBoxMenu">0</span>
			<a class="button box_action">Archive</a>
			<a class="button box_action">Delete</a>
			<a id="toggleBoxMenu" class="open"></a>
			<a id="closeBoxMenu" class="button t_close">X</a>
		</div>
		<div class="submenu">
			<a class="first box_action">Move...</a>
			<a class="box_action">Mark as read</a>
			<a class="box_action">Mark as unread</a>
			<a class="last box_action">Spam</a>
		</div>
	</div>
	<div id="left_bar">
		<div id="primary_nav" class="black_pro">
			<ul>
				<li><a href="<?=base_url()?>dashboard" title="Dashboard"><span class="icon_block m_dashboard">Dashboard</span></a></li>
				<li><a href="#" title="All Run Sheet"><span class="icon_block m_projects">All Run Sheet</span></a></li>
				<li><a href="#" title="Calendar"><span class="icon_block m_events">Calendar</span></a></li>
				<li><a href="#" title="All Reports"><span class="icon_block p_book">All Reports</span></a></li>
				<li><a href="#" title="Directory System"><span class="icon_block m_media">Directory System</span></a></li>
				<li><a href="#" title="Settings"><span class="icon_block m_settings">Settings</span></a></li>
				
			</ul>
		</div>
		<!--<div id="start_menu">
			<ul>
				<li class="jtop_menu ">
					<div class="icon_block black_gel">
						<span class="start_icon">Quick Menu</span>
					</div>
					<ul class="black_gel">
						<li><a href="<?=base_url()?>invoice"><span class="list-icon graph_b">&nbsp;</span>Analytics<span class="mnu_tline">Tagline</span></a></li>
						<li><a href="#"><span class="list-icon cog_4_b">&nbsp;</span>Settings<span class="mnu_tline">Tagline</span></a></li>
						<li><a href="#"><span class="list-icon vault_b">&nbsp;</span>The Archive<span class="mnu_tline">Tagline</span></a></li>
						<li><a href="#"><span class="list-icon list_images_b">&nbsp;</span>Task List<span class="mnu_tline">Tagline</span></a></li>
						<li><a href="#"><span class="list-icon documents_b">&nbsp;</span>Content List<span class="mnu_tline">Tagline</span></a>
						</li>
						<li><a href="#"><span class="list-icon folder_b">&nbsp;</span>Media<span class="mnu_tline">Tagline</span></a>
						</li>
						<li><a href="#"><span class="list-icon phone_3_b">&nbsp;</span>Contact<span class="mnu_tline">Tagline</span></a>
						</li>
						<li><a href="#"><span class="list-icon users_b">&nbsp;</span>User<span class="mnu_tline">Tagline</span></a>
							<ul>
								<li><a href="#"><span class="list-icon user_2_b">&nbsp;</span>Add New User<span class="mnu_tline">Tagline</span></a></li>
								<li><a href="#"><span class="list-icon money_b">&nbsp;</span>Paid Users<span class="mnu_tline">Tagline</span></a></li>
								<li><a href="#"><span class="list-icon users_2_b">&nbsp;</span>All Users<span class="mnu_tline">Tagline</span></a></li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
		</div>-->
		<div id="sidebar">
			<div id="secondary_nav">
				<ul id="sidenav" class="accordion_mnu collapsible orange_f">
					<div class="btn_30_dark">
						<span class="icon user_business_boss_co"></span><span class="btn_link"><b style="font-size: 16px; margin-top:40px;">MENU SYSTEM<br>CASH IN TRANSIT</b></span>
					</div>
					<li <?php if($active_menu=="rekon_atm") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac">
					<!--<img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="30" height="30" style="margin-top:-10px;">-->
					</span> Actual Daily Monitoring </a>
						<ul class="acitem">
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Cash In Transit (CIT)</b></li><br>
							<li><a href="<?=base_url()?>admcit_nas"><span class="list-icon">&nbsp;</span>CIT - Nasional</a></li>
							<li><a href="<?=base_url()?>admcit_bra"><span class="list-icon">&nbsp;</span>CIT - Branch</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM Cash Replenishment</b></li><br>
							<li><a href="<?=base_url()?>atmcr_nas"><span class="list-icon">&nbsp;</span>ATM CR - Nasional</a></li>
							<li><a href="<?=base_url()?>atmcr_bra"><span class="list-icon">&nbsp;</span>ATM CR - Branch</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM FLM & SLM </b></li><br>
							<li><a href="<?=base_url()?>atmfslm_nas"><span class="list-icon">&nbsp;</span>FLM & SLM - Nasional</a></li>
							<li><a href="<?=base_url()?>atmfslm_bra"><span class="list-icon">&nbsp;</span>FLM & SLM - Branch</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> REPORT </b></li><br>
							<li><a class="<?=($active_menu == "rekon_atm" ? 'active' : '')?>" href="<?=base_url()?>rekon_atm"><span class="list-icon">&nbsp;</span>Rekon ATM</a></li>
						</ul>
					</li>
					<li><a href="#"><span class="nav_icon computer_imac"></span> Result Data Performance </a>
						<ul class="acitem">
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Cash In Transit (CIT)</b></li><br>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>CIT - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>CIT - Branch</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM Cash Replenishment</b></li><br>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>ATM CR - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>ATM CR - Branch</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM FLM & SLM </b></li><br>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Branch</a></li>
						</ul>
					</li>
					
					
					<li <?php if($active_menu=="cashtransit" 
									|| $active_menu=="cashreplenish"
									|| $active_menu=="operational"
									|| $active_menu=="security"
									|| $active_menu=="cashprocessing"
									|| $active_menu=="logistic"
									|| $active_menu=="runsheet") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> Planning & Preparation </a>
						<ul class="acitem">
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Request Order H-1</b></li><br>
							<li><a class="<?=($active_menu == "cashtransit" ? 'active' : '')?>" href="<?=base_url()?>cashtransit"><span class="list-icon">&nbsp;</span>CIT</a></li>
							<li><a class="<?=($active_menu == "cashreplenish" ? 'active' : '')?>" href="<?=base_url()?>cashreplenish"><span class="list-icon">&nbsp;</span>CR</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Request Order H-0</b></li><br>
							<li><a class="<?=($active_menu == "cashtransith0" ? 'active' : '')?>" href="<?=base_url()?>cashtransit"><span class="list-icon">&nbsp;</span>CIT</a></li>
							<li><a class="<?=($active_menu == "cashreplenishh0" ? 'active' : '')?>" href="<?=base_url()?>cashreplenish"><span class="list-icon">&nbsp;</span>CR</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Data Input For Runsheet </b></li><br>
							<li><a class="<?=($active_menu == "operational" ? 'active' : '')?>" href="<?=base_url()?>operational"><span class="list-icon">&nbsp;</span>Operational</a></li>
							<li><a class="<?=($active_menu == "security" ? 'active' : '')?>" href="<?=base_url()?>security"><span class="list-icon">&nbsp;</span>Security Control</a></li>
							<li><a class="<?=($active_menu == "logistic" ? 'active' : '')?>" href="<?=base_url()?>logistic"><span class="list-icon">&nbsp;</span>Logistic Use</a></li>
							<li><a class="<?=($active_menu == "cashprocessing" ? 'active' : '')?>" href="<?=base_url()?>cashprocessing"><span class="list-icon">&nbsp;</span>Cash Processing</a></li>
							<li><a class="<?=($active_menu == "runsheet" ? 'active' : '')?>" href="<?=base_url()?>runsheet#tab-drs"><span class="list-icon">&nbsp;</span>Runsheet</a></li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="barcode_batch" 
									|| $active_menu=="barcode_generates"
									|| $active_menu=="inventory"
									|| $active_menu=="seal"
									|| $active_menu=="bag") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> Logistics & Identity</a>
						<ul class="acitem">
							<li><a class="<?=($active_menu == "inventory" ? 'active' : '')?>" href="<?=base_url()?>inventory"><span class="list-icon">&nbsp;</span>Supplies & Sparepart</a></li>
							<li><a class="<?=($active_menu == "barcode_batch" ? 'active' : '')?>" href="<?=base_url()?>barcode_batch"><span class="list-icon">&nbsp;</span>Batch Barcode</a></li>
							<li><a class="<?=($active_menu == "barcode_generates" ? 'active' : '')?>" href="<?=base_url()?>barcode_generates"><span class="list-icon">&nbsp;</span>Barcode Generates</a></li>
							<li><a class="<?=($active_menu == "seal" ? 'active' : '')?>" href="<?=base_url()?>seal"><span class="list-icon">&nbsp;</span>Data Seal</a></li>
							<li><a class="<?=($active_menu == "bag" ? 'active' : '')?>" href="<?=base_url()?>bag"><span class="list-icon">&nbsp;</span>Data Bag</a></li>
						</ul>
					</li>
					<li <?php if($active_menu=="client" 
									|| $active_menu=="client_cit"
									|| $active_menu=="user_client"
									|| $active_menu=="branch"
									|| $active_menu=="zone") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> Data Client & User </a>
						<ul class="acitem">
							<li><a class="<?=($active_menu == "branch" ? 'active' : '')?>" href="<?=base_url()?>branch"><span class="list-icon">&nbsp;</span>Branch</a></li>
							<li><a class="<?=($active_menu == "zone" ? 'active' : '')?>" href="<?=base_url()?>zone"><span class="list-icon">&nbsp;</span>Zone</a></li>
							<li><a class="<?=($active_menu == "client" ? 'active' : '')?>" href="<?=base_url()?>client"><span class="list-icon">&nbsp;</span>Client</a></li>
							<li><a class="<?=($active_menu == "client_cit" ? 'active' : '')?>" href="<?=base_url()?>client"><span class="list-icon">&nbsp;</span>Client CIT</a></li>
							<li><a class="<?=($active_menu == "user_client" ? 'active' : '')?>" href="<?=base_url()?>user_client"><span class="list-icon">&nbsp;</span>User Client</a></li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="vehicle" 
									|| $active_menu=="handphone"
									|| $active_menu=="maps") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> Gadget & GPS </a>
						<ul class="acitem">
							<li><a class="<?=($active_menu == "vehicle" ? 'active' : '')?>" href="<?=base_url()?>vehicle"><span class="list-icon">&nbsp;</span>Vehicle</a></li>
							<li><a class="<?=($active_menu == "handphone" ? 'active' : '')?>" href="<?=base_url()?>handphone"><span class="list-icon">&nbsp;</span>Handphone</a></li>
							<!--<li><a class="<?=($active_menu == "maps" ? 'active' : '')?>" href="<?=base_url()?>maps"><span class="list-icon">&nbsp;</span>Maps</a></li>-->
						</ul>
					</li>
					<li><a href="#"><span class="nav_icon computer_imac"></span> Billing & Invoices </a>
						<ul class="acitem">
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Cash In Transit (CIT)</b></li><br>
							<li><a href="<?=base_url()?>invoice"><span class="list-icon">&nbsp;</span>CIT - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>CIT - Branch</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM Cash Replenishment</b></li><br>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>ATM CR - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>ATM CR - Branch</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM FLM & SLM </b></li><br>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Branch</a></li>
						</ul>
					</li>
					
					<!--<li <?php if($active_menu=="cashtransit" 
									|| $active_menu=="cashreplenish") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> Planning & Preparation </a>
						<ul class="acitem">
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Cash In Transit (CIT)</b></li><br>
							<li><a class="<?=($active_menu == "cashtransit" ? 'active' : '')?>" href="<?=base_url()?>cashtransit"><span class="list-icon">&nbsp;</span>CIT - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>CIT - Branch</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM Cash Replenishment</b></li><br>
							<li><a class="<?=($active_menu == "cashreplenish" ? 'active' : '')?>" href="<?=base_url()?>cashreplenish"><span class="list-icon">&nbsp;</span>ATM CR - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>ATM CR - Branch</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM FLM & SLM </b></li><br>
							<li><a class="<?=($active_menu == "add_ticket" ? 'active' : '')?>" href="<?=base_url()?>ticket/add_ticket"><span class="list-icon">&nbsp;</span>FLM & SLM - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Branch</a></li>
						</ul>
					</li>-->
					<li <?php if($active_menu=="cashprocessing" 
									|| $active_menu=="cashprocessing_return") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> Cash Processing </a>
						<ul class="acitem">
							<!--<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Cash In Transit (CIT)</b></li><br>
							<li><a class="<?=($active_menu == "cashprocessing" ? 'active' : '')?>" href="<?=base_url()?>cashprocessing"><span class="list-icon">&nbsp;</span>CIT - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>CIT - Branch</a></li>-->
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM Cash Replenishment</b></li><br>
							<li><a class="<?=($active_menu == "cashprocessing_return" ? 'active' : '')?>" href="<?=base_url()?>cashprocessing_return"><span class="list-icon">&nbsp;</span>ATM CR - CASH RETURN</a></li>
							<!--<li><a class="<?=($active_menu == "cashprocessing_cr" ? 'active' : '')?>" href="<?=base_url()?>cashprocessing"><span class="list-icon">&nbsp;</span>ATM CR - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>ATM CR - Branch</a></li>-->
							<!--<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM FLM & SLM </b></li><br>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Branch</a></li>-->
						</ul>
					</li>
					
					<!--<li><a href="#"><span class="nav_icon computer_imac"></span> Actual Daily Monitoring </a>
						<ul class="acitem">
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM FLM & SLM </b></li><br>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Branch</a></li>
						</ul>
					</li>-->
					<!--<li <?php if($active_menu=="ticket_list" 
									|| $active_menu=="approval_list"
									|| $active_menu=="myassignment_list") { echo 'class="expand"'; } ?>><a class="active" href="#"><span class="nav_icon computer_imac"></span> Tickets Management </a>
						<ul class="acitem">
							<li><a class="<?=($active_menu == "" ? 'active' : '')?>" href="<?=base_url()?>ticket/add_ticket"><span class="list-icon">&nbsp;</span>New Issue Ticket</a></li>
							<li><a class="<?=($active_menu == "ticket_list" ? 'active' : '')?>" href="<?=base_url()?>ticket/ticket_list"><span class="list-icon">&nbsp;</span>List Tickets</a></li>
							<li><a class="<?=($active_menu == "approval_list" ? 'active' : '')?>" href="<?=base_url()?>approval/approval_list"><span class="list-icon">&nbsp;</span>Approval Ticket</a></li>
							<li><a class="<?=($active_menu == "myassignment_list" ? 'active' : '')?>" href="<?=base_url()?>myassignment/myassignment_list"><span class="list-icon">&nbsp;</span>Assignment Tickets</a></li>
						</ul>
					</li>-->
				
				
					<div class="btn_30_dark">
						<span class="icon user_business_boss_co"></span><span class="btn_link"><b style="font-size: 16px; margin-top:40px;">MENU SYSTEM<br>CONTROL TOWER</b></span>
					</div>
					<li <?php if($active_menu=="add_ticket" 
									|| $active_menu=="add_ticket_slm"
									|| $active_menu=="status_flm"
									|| $active_menu=="status_slm"
									|| $active_menu=="operational"
									|| $active_menu=="security"
									|| $active_menu=="cashprocessing"
									|| $active_menu=="logistic") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> Tickets Management </a>
						<ul class="acitem">
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Issue Ticket</b></li><br>
							<li><a class="<?=($active_menu == "add_ticket" ? 'active' : '')?>" href="<?=base_url()?>ticket/add_ticket"><span class="list-icon">&nbsp;</span>FLM</a></li>
							<li><a class="<?=($active_menu == "add_ticket_slm" ? 'active' : '')?>" href="<?=base_url()?>ticket_slm/add_ticket_slm"><span class="list-icon">&nbsp;</span>SLM</a></li>
							<li><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" widt	h="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Status Ticket </b></li><br>
							<li><a class="<?=($active_menu == "status_flm" ? 'active' : '')?>" href="<?=base_url()?>ticket/status_flm"><span class="list-icon">&nbsp;</span>FLM</a></li>
							<li><a class="<?=($active_menu == "status_slm" ? 'active' : '')?>" href="<?=base_url()?>ticket_slm/status_slm"><span class="list-icon">&nbsp;</span>SLM</a></li>
						</ul>
					</li>
					<li <?php if($active_menu=="kategori" 
									|| $active_menu=="sub_kategori") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> Troubleshooting Data </a>
						<ul class="acitem">
							<li><a class="<?=($active_menu == "kategori" ? 'active' : '')?>" href="<?=base_url()?>kategori"><span class="list-icon">&nbsp;</span>Troubleshoot Category </a></li>
							<li><a class="<?=($active_menu == "sub_kategori" ? 'active' : '')?>" href="<?=base_url()?>sub_kategori"><span class="list-icon">&nbsp;</span>Sub Category</a></li>
						</ul>
					</li>
					<li <?php if($active_menu=="karyawan" 
									|| $active_menu=="jabatan"
									|| $active_menu=="departemen"
									|| $active_menu=="bagian_departemen") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> Structure Management </a>
						<ul class="acitem">
							<li><a class="<?=($active_menu == "karyawan" ? 'active' : '')?>" href="<?=base_url()?>karyawan"><span class="list-icon">&nbsp;</span>Employee</a></li>
							<li><a class="<?=($active_menu == "jabatan" ? 'active' : '')?>" href="<?=base_url()?>jabatan"><span class="list-icon">&nbsp;</span>Employment</a></li>
							<li><a class="<?=($active_menu == "departemen" ? 'active' : '')?>" href="<?=base_url()?>departemen"><span class="list-icon">&nbsp;</span>Departemens</a></li>
							<li><a class="<?=($active_menu == "bagian_departemen" ? 'active' : '')?>" href="<?=base_url()?>bagian_departemen"><span class="list-icon">&nbsp;</span>Sub Departemen</a></li>
						</ul>
					</li>
					<li <?php if($active_menu=="teknisi") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> Technical Staff </a>
						<ul class="acitem">
							<li><a class="<?=($active_menu == "teknisi" ? 'active' : '')?>" href="<?=base_url()?>teknisi"><span class="list-icon">&nbsp;</span>Technician</a></li>
						</ul>
					</li>
					<li <?php if($active_menu=="user") { echo 'class="expand"'; } ?>><a href="#"><span class="nav_icon computer_imac"></span> User Management </a>
						<ul class="acitem">
							<li><a class="<?=($active_menu == "user" ? 'active' : '')?>" href="<?=base_url()?>user"><span class="list-icon">&nbsp;</span>User Access</a></li>
						</ul>
					</li>
				</ul>

			</div>
		</div>
	</div>
    <div id="container">
        <div id="header" class="black_gel">
			<div class="header_left">
				<div class="logo">
					<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50" alt="">
				</div>
				<div id="responsive_mnu">
					<a href="#responsive_menu" class="fg-button" id="hierarchybreadcrumb"><span class="responsive_icon"></span>Menu System</a>
					<div id="responsive_menu" class="hidden">
						<ul>
							<li><a href="#"> Actual Daily Monitoring</a>
							<ul>
								<li><a href="dashboard.html">Dashboard Main</a></li>
								<li><a href="dashboard-01.html">Dashboard 01</a></li>
								<li><a href="dashboard-02.html">Dashboard 02</a></li>
								<li><a href="dashboard-03.html">Dashboard 03</a></li>
								<li><a href="dashboard-04.html">Dashboard 04</a></li>
							</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="header_right">
				
				<div id="user_nav">
					<ul>
						<li class="user_thumb"><a href="#"><span class="icon"><img src="<?=base_url()?>depend/images/user_thumb.png" width="30" height="30" alt="User"></span></a></li>
						<li class="user_info"><span class="user_name">GLOBAL TERMINAL ONE</span><span><a href="#">Administrator System</a></span></li>
						<li class="logout"><a href="<?=base_url()?>"><span class="icon"></span>Logout</a></li>
					</ul>
				</div>
			</div>
		</div>
        <div id="content">
            <div class="grid_container">
                <div class="grid_12">
                    <div class="widget_wrap">
						<?php if ($session->flashdata('success')): ?>
							<section class="grid_12">
								<ul class="alert message success" style="z-index: 99999; position: fixed; top: 1%; right: 1%; width: 200px" hidden>
									<li><?=$session->flashdata('success')?></li>
									<li class="close-bt"></li>
								</ul>
							</section>
						<?php elseif ($session->flashdata('error')): ?>
							<section class="grid_12">
								<ul class="alert message error" style="z-index: 99999; position: fixed; top: 1%; right: 1%; width: 200px" hidden>
									<li><?=$session->flashdata('error')?></li>
									<li class="close-bt"></li>
								</ul>
							</section>
						<?php endif; ?>
				
						@yield('content')
					</div>
                </div>
            </div>
            <span class="clear"></span>
        </div>
    </div>
</body>

</html>

<script src="<?=base_url()?>constellation/assets/js/modal.js"></script>

<script>
	$(window).load(function() {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");;
	});
	
	$(document).ready(function() {
		$(".alert").show(1000);
		window.setTimeout(function()  {
			$(".alert").fadeTo(500, 0).slideUp(500, function() {
				$(this).remove();
			});
		}, 5000);
	});

	function openDelete(id, url)
	{
		$.modal({
			content: '<br>Anda yakin akan menghapus data dengan ID : ('+id+')?',
			title: 'Delete',
			maxWidth: 400,
			buttons: {
				'Yes': function(win) { 
					$.ajax({
						url: url,
						dataType: 'html',
						type: 'POST',
						data: {id:id},
						success: function(data) {
							if(data=="success") {
								window.location.reload();
							} else {
								win.closeModal();
							}
						}
					});
				},
				'Close': function(win) { win.closeModal(); }
			}
		});
	}
</script>