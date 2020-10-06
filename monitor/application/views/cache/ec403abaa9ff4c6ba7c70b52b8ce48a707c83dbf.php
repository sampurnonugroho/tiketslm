<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width" />
    <title>B.I.M.A V2.0</title>
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
	
	<?php 
		if($active_menu!=="handover_in" && $active_menu!=="handover") {
	?>
			<link href="<?=base_url()?>assets/constellation/assets/css/mini3537.css?files=reset,common,form,standard,960.gs.fluid,simple-lists,block-lists,planning,table,calendars,wizard,gallery" rel="stylesheet" type="text/css">
	<?php 
		} else {
	?>
			<link href="<?=base_url()?>assets/constellation/assets/css/mini3537_2.css?files=reset,common,form,standard,960.gs.fluid,simple-lists,block-lists,planning,table,calendars,wizard,gallery" rel="stylesheet" type="text/css">
	<?php
		}
	?>
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
    <!--<script src="<?=base_url()?>depend/js/easing.jquery.js"></script>-->
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
		<style>
			.zoom {
			  transition: transform .1s; /* Animation */
			}
			.zoom:hover {
			  transform: scale(1.2); 
			}
		</style>
		<div id="primary_nav" class="black_pro">
			<ul>
				<li class="zoom"><a href="<?=base_url()?>dashboard" title="Dashboard"><img style="float: left; margin: 10px 0px 0px 10px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/dash.png" width="45" height="45"></a></li>
				<li class="zoom"><a href="<?=base_url()?>all_runsheet" title="All Run Sheet"><img style="float: left; margin: 10px 0px 0px 10px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/report.png" width="45" height="45"></a></li>
				<li class="zoom"><a href="<?=base_url()?>all_problem" title="All Problem Ticket"><img style="float: left; margin: 10px 0px 0px 10px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/newjob.png" width="45" height="45"></a></li>
				<li class="zoom"><a href="<?=base_url()?>all_interval" title="All Interval"><img style="float: left; margin: 10px 0px 0px 10px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/history.png" width="45" height="45"></a></li>
				<li class="zoom"><a href="#" title="Directory System"><img style="float: left; margin: 10px 0px 0px 10px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/dir.png" width="45" height="45"></a></li>
				<li class="zoom"><a href="#" title="Userguide"><img style="float: left; margin: 10px 0px 0px 10px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/userguide.png" width="45" height="45"></a></li>
				<li class="zoom"><a href="#" title="Settings"><img style="float: left; margin: 10px 0px 0px 10px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/setting.png" width="45" height="45"></a></li>
				<li class="zoom"><a href="#" title="Complain & Troubleshoot"><img style="float: left; margin: 10px 0px 0px 10px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/helpdesk.png" width="45" height="45"></a>
				</li>
			</ul>
		</div>
		
		<div id="sidebar">
			<div id="secondary_nav">
				<ul id="sidenav" class="accordion_mnu collapsible orange_f">
					<div class="btn_30_dark">
						<img style="float: left; margin: 3px 0px 0px 0px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation.png" width="25" height="25" style="margin-top:10px;"><span class="btn_link"><b style="font-size: 14px; margin-top:40px;">MENU SYSTEM<br>CASH IN TRANSIT</b></span>
					</div>
					
					<li <?php if($active_menu=="rekon_atm"
							  || $active_menu=="admcit_nas"
							  || $active_menu=="admcit_bra"
							  || $active_menu=="sla"
							  || $active_menu=="atmcr_nas"
							  || $active_menu=="atmcr_bra"
							  || $active_menu=="jurnal") { echo 'class="expand"'; } ?>>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/mn/calendar.png" width="25" height="25" style="margin-top:10px;"> <b>Actual Daily Monitoring </b><span class="down_arrow">&nbsp;</span></a>
						<ul class="acitem">
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Cash In Transit (CIT)</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "admcit_nas" ? 'active' : '')?>" href="<?=base_url()?>admcit_nas">
									<span class="list-icon">&nbsp;</span>CIT - Nasional
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "admcit_bra" ? 'active' : '')?>" href="<?=base_url()?>admcit_bra">
									<span class="list-icon">&nbsp;</span>CIT - Branch
								</a>
							</li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> ATM Cash Replenishment</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "atmcr_nas" ? 'active' : '')?>" href="<?=base_url()?>atmcr_nas">
									<span class="list-icon">&nbsp;</span>ATM CR - Nasional
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "atmcr_bra" ? 'active' : '')?>" href="<?=base_url()?>atmcr_bra">
									<span class="list-icon">&nbsp;</span>ATM CR - Branch
								</a>
							</li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> SLA FLM & SLM</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "sla" ? 'active' : '')?>" href="<?=base_url()?>sla">
									<span class="list-icon">&nbsp;</span>FLM & SLM - Nasional
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "sla_bra" ? 'active' : '')?>" href="<?=base_url()?>sla_bra">
									<span class="list-icon">&nbsp;</span>FLM & SLM - Branch
								</a>
							</li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> REPORT</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "rekon_atm" ? 'active' : '')?>" href="<?=base_url()?>rekon_atm/get_data4">
									<span class="list-icon">&nbsp;</span>Rekon ATM
								</a>
							</li>
							<li hidden>
								<a class="<?=($active_menu == "sla" ? 'active' : '')?>" href="<?=base_url()?>sla">
									<span class="list-icon">&nbsp;</span>SLA FLM
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "jurnal" ? 'active' : '')?>" href="<?=base_url()?>jurnal">
									<span class="list-icon">&nbsp;</span>Journal
								</a>
							</li>
						</ul>
					</li>
					
					<li>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/mn/rdp.png" width="25" height="25" style="margin-top:10px;"> <b> Result Data Performance </b> </a>
						<ul class="acitem">
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Cash In Transit (CIT)</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "rdpcit_nas" ? 'active' : '')?>" href="<?=base_url()?>rdpcit_nas">
									<span class="list-icon">&nbsp;</span>CIT - Nasional
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "rdpcit_bra" ? 'active' : '')?>" href="<?=base_url()?>rdpcit_bra">
									<span class="list-icon">&nbsp;</span>CIT - Branch
								</a>
							</li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> ATM Cash Replenishment</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "rdpcr_nas" ? 'active' : '')?>" href="<?=base_url()?>rdpcr_nas">
									<span class="list-icon">&nbsp;</span>ATM CR - Nasional
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "rdpcr_bra" ? 'active' : '')?>" href="<?=base_url()?>rdpcr_bra">
									<span class="list-icon">&nbsp;</span>ATM CR - Branch
								</a>
							</li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> SLA FLM & SLM</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "rdpsla_nas" ? 'active' : '')?>" href="<?=base_url()?>rdpsla_nas">
									<span class="list-icon">&nbsp;</span>FLM & SLM - Nasional
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "rdpsla_bra" ? 'active' : '')?>" href="<?=base_url()?>rdpsla_bra">
									<span class="list-icon">&nbsp;</span>FLM & SLM - Branch
								</a>
							</li>
						</ul>
					</li>
					
					<li <?php if(
							$active_menu=="cashtransit_1" 
							|| $active_menu=="cashtransit_0"
							|| $active_menu=="cashreplenish_1"
							|| $active_menu=="cashreplenish_0"
							|| $active_menu=="handover"
							|| $active_menu=="handover_in"
							|| $active_menu=="handover_out"
							|| $active_menu=="handover3"
							|| $active_menu=="handover4"
							|| $active_menu=="operational"
							|| $active_menu=="security"
							|| $active_menu=="cashprocessing"
							|| $active_menu=="logistic"
							|| $active_menu=="runsheet"
							|| $active_menu=="invalid_seal"
							|| $active_menu=="cpc_prepared"
							|| $active_menu=="cpc_prepared_stock") { echo 'class="expand"'; } ?>>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/mn/plan.png" width="25" height="25" style="margin-top:10px;"> <b>  Planning & Preparation </b></a>
						<ul class="acitem">
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Data Planning H-1</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "cashtransit_1" ? 'active' : '')?>" href="<?=base_url()?>cashtransit/index_1">
									<span class="list-icon">&nbsp;</span>Cash In Transit (CIT)
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "cashreplenish_1" ? 'active' : '')?>" href="<?=base_url()?>cashreplenish/index_1">
									<span class="list-icon">&nbsp;</span>Cash Replanish (CR)
								</a>
							</li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Data Planning H-0</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "cashtransit_0" ? 'active' : '')?>" href="<?=base_url()?>cashtransit/index_0">
									<span class="list-icon">&nbsp;</span>Cash In Transit (CIT)
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "cashreplenish_0" ? 'active' : '')?>" href="<?=base_url()?>cashreplenish/index_0">
									<span class="list-icon">&nbsp;</span>Cash Replanish (CR)
								</a>
							</li>
							
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Data Handover </b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "handover" ? 'active' : '')?>" href="<?=base_url()?>handover_in">
									<span class="list-icon">&nbsp;</span>Hand-Over (HO-Incoming)
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "handover_out" ? 'active' : '')?>" href="<?=base_url()?>handover_out">
									<span class="list-icon">&nbsp;	</span>Hand-Over (HO-Outgoing)
								</a>
							</li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Data Input For Runsheet </b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "operational" ? 'active' : '')?>" href="<?=base_url()?>operational">
									<span class="list-icon">&nbsp;</span>Operational
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "security" ? 'active' : '')?>" href="<?=base_url()?>security">
									<span class="list-icon">&nbsp;</span>Security Control
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "logistic" ? 'active' : '')?>" href="<?=base_url()?>logistic">
									<span class="list-icon">&nbsp;</span>Logistic Use
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "cashprocessing" ? 'active' : '')?>" href="<?=base_url()?>cashprocessing">
									<span class="list-icon">&nbsp;</span>Cash Processing
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "runsheet" ? 'active' : '')?>" href="<?=base_url()?>runsheet#tab-drs">
									<span class="list-icon">&nbsp;</span>Runsheet
								</a>
							</li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Planning CPC & Stock </b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "cpc_prepared" ? 'active' : '')?>" href="<?=base_url()?>cpc_prepared">
									<span class="list-icon">&nbsp;</span>CPC Prepared
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "cpc_prepared_stock" ? 'active' : '')?>" href="<?=base_url()?>cpc_prepared/stock">
									<span class="list-icon">&nbsp;</span>CPC Stock
								</a>
							</li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Problem Seal </b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "invalid_seal" ? 'active' : '')?>" href="<?=base_url()?>invalid_seal">
									<span class="list-icon">&nbsp;</span>Invalid Seal Process
								</a>
							</li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="cashprocessing" 
								|| $active_menu=="cashprocessing_return"
								|| $active_menu=="cashprocessing_batal") { echo 'class="expand"'; } ?>>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/mn/cpc.png" width="25" height="25" style="margin-top:10px;"> <b> Cash Processing </b></a>
						<ul class="acitem">
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> ATM Cash Replenishment </b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "cashprocessing_return" ? 'active' : '')?>" href="<?=base_url()?>cashprocessing_return">
									<span class="list-icon">&nbsp;</span>ATM CR - Cash Return
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "cashprocessing_batal" ? 'active' : '')?>" href="<?=base_url()?>cashprocessing_batal">
									<span class="list-icon">&nbsp;</span>ATM CR - Batal Replanish
								</a>
							</li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="barcode_batch" 
								|| $active_menu=="barcode_generates"
								|| $active_menu=="inventory"
								|| $active_menu=="logistic_in_use"
								|| $active_menu=="merk_mesin"
								|| $active_menu=="seal"
								|| $active_menu=="bag"
								|| $active_menu=="tbag"
								|| $active_menu=="cassette"
								|| $active_menu=="receipt"
								|| $active_menu=="combination_lock"
								|| $active_menu=="vehicle"
								|| $active_menu=="handphone") { echo 'class="expand"'; } ?>>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/mn/atm.png" width="25" height="25" style="margin-top:10px;"> <b> Logistics & Identity</b></a>
						<ul class="acitem">
							<li>
								<a class="<?=($active_menu == "inventory" ? 'active' : '')?>" href="<?=base_url()?>inventory">
									<span class="list-icon">&nbsp;</span>Supplies & Sparepart
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "logistic_in_use" ? 'active' : '')?>" href="<?=base_url()?>logistic_in_use/show">
									<span class="list-icon">&nbsp;</span>Seal In Use
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "merk_mesin" ? 'active' : '')?>" href="<?=base_url()?>merk_mesin">
									<span class="list-icon">&nbsp;</span>Merk Mesin
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "seal" ? 'active' : '')?>" href="<?=base_url()?>seal">
									<span class="list-icon">&nbsp;</span>Data Seal
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "bag" ? 'active' : '')?>" href="<?=base_url()?>bag">
									<span class="list-icon">&nbsp;</span>Data Bag
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "tbag" ? 'active' : '')?>" href="<?=base_url()?>tbag">
									<span class="list-icon">&nbsp;</span>Data T-Bag
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "cassette" ? 'active' : '')?>" href="<?=base_url()?>cassette">
									<span class="list-icon">&nbsp;</span>Data Cassette
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "receipt" ? 'active' : '')?>" href="<?=base_url()?>receipt">
									<span class="list-icon">&nbsp;</span>Data Receipt Paper
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "combination_lock" ? 'active' : '')?>" href="<?=base_url()?>combination_lock">
									<span class="list-icon">&nbsp;</span>Combination lock
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "vehicle" ? 'active' : '')?>" href="<?=base_url()?>vehicle">
									<span class="list-icon">&nbsp;</span>Vehicle
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "handphone" ? 'active' : '')?>" href="<?=base_url()?>handphone">
									<span class="list-icon">&nbsp;</span>Handphone
								</a>
							</li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="client" 
									|| $active_menu=="client_cit"
									|| $active_menu=="user_client"
									|| $active_menu=="branch"
									|| $active_menu=="zone") { echo 'class="expand"'; } ?>>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/mn/client.png" width="25" height="25" style="margin-top:10px;"> <b> Data Client & User </b></a>
						<ul class="acitem">
							<li>
								<a class="<?=($active_menu == "branch" ? 'active' : '')?>" href="<?=base_url()?>branch">
									<span class="list-icon">&nbsp;</span>Branch
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "zone" ? 'active' : '')?>" href="<?=base_url()?>zone">
									<span class="list-icon">&nbsp;</span>Zone
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "client" ? 'active' : '')?>" href="<?=base_url()?>client">
									<span class="list-icon">&nbsp;</span>Client
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "client_cit" ? 'active' : '')?>" href="<?=base_url()?>client_cit">
									<span class="list-icon">&nbsp;</span>Client CIT
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "user_client" ? 'active' : '')?>" href="<?=base_url()?>user_client">
									<span class="list-icon">&nbsp;</span>User Client
								</a>
							</li>
						</ul>
					</li>
					
					<li>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/document-powerpoint.png" width="25" height="25" style="margin-top:10px;"> <b> Billing & Invoices </b></a>
						<ul class="acitem">
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Cash In Transit (CIT)</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li><a href="<?=base_url()?>invoice"><span class="list-icon">&nbsp;</span>CIT - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>CIT - Branch</a></li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> ATM Cash Replenishment</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>ATM CR - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>ATM CR - Branch</a></li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> ATM FLM & SLM </b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Branch</a></li>
						</ul>
					</li>
					
					<div class="btn_30_dark">
						<img style="float: left; margin: 3px 0px 0px 0px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation.png" width="25" height="25" style="margin-top:10px;"><span class="btn_link"><b style="font-size: 14px; margin-top:40px;">MENU SYSTEM<br>CONTROL TOWER</b></span>
					</div>
				
					<li <?php if($active_menu=="add_ticket" 
									|| $active_menu=="add_ticket_slm"
									|| $active_menu=="status_flm"
									|| $active_menu=="status_slm") { echo 'class="expand"'; } ?>>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/document-bookmark.png" width="25" height="25" style="margin-top:10px;"> <b> Tickets Management </b></a>
						<ul class="acitem">
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Issue Ticket</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "add_ticket" ? 'active' : '')?>" href="<?=base_url()?>ticket/add_ticket">
									<span class="list-icon">&nbsp;</span>FLM
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "add_ticket_slm" ? 'active' : '')?>" href="<?=base_url()?>ticket_slm/add_ticket_slm">
									<span class="list-icon">&nbsp;</span>SLM
								</a>
							</li>
							<li>
								<img style="float: left; margin: 8px 3px 0px 8px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-000-white.png" width="20" height="20">
								<br>
								<b style="font-size: 12px; color:#ffffff; margin: 0px 0px 0px 0px;"> Status Ticket</b><span class="down_arrow">&nbsp;</span>
								<br>
								<br>
							</li>
							<li>
								<a class="<?=($active_menu == "status_flm" ? 'active' : '')?>" href="<?=base_url()?>ticket/status_flm">
									<span class="list-icon">&nbsp;</span>FLM
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "status_slm" ? 'active' : '')?>" href="<?=base_url()?>ticket_slm/status_slm">
									<span class="list-icon">&nbsp;</span>SLM
								</a>
							</li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="kategori" 
									|| $active_menu=="sub_kategori") { echo 'class="expand"'; } ?>>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/folder-bookmark.png" width="25" height="25" style="margin-top:10px;"> <b> Troubleshooting Data </b></a>
						<ul class="acitem">
							<li>
								<a class="<?=($active_menu == "kategori" ? 'active' : '')?>" href="<?=base_url()?>kategori">
									<span class="list-icon">&nbsp;</span>Troubleshoot Category 
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "sub_kategori" ? 'active' : '')?>" href="<?=base_url()?>sub_kategori">
									<span class="list-icon">&nbsp;</span>Sub Category
								</a>
							</li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="karyawan" 
									|| $active_menu=="jabatan"
									|| $active_menu=="departemen"
									|| $active_menu=="bagian_departemen") { echo 'class="expand"'; } ?>>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/mn/structure.png" width="25" height="25" style="margin-top:10px;"> <b> Structure Management </b></a>
						<ul class="acitem">
							<li>
								<a class="<?=($active_menu == "karyawan" ? 'active' : '')?>" href="<?=base_url()?>karyawan">
									<span class="list-icon">&nbsp;</span>Employee
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "jabatan" ? 'active' : '')?>" href="<?=base_url()?>jabatan">
									<span class="list-icon">&nbsp;</span>Employment
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "departemen" ? 'active' : '')?>" href="<?=base_url()?>departemen">
									<span class="list-icon">&nbsp;</span>Departemens
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "bagian_departemen" ? 'active' : '')?>" href="<?=base_url()?>bagian_departemen">
									<span class="list-icon">&nbsp;</span>Sub Departemen
								</a>
							</li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="teknisi") { echo 'class="expand"'; } ?>>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/mn/technician.png" width="25" height="25" style="margin-top:10px;"> <b> Technical Staff </b></a>
						<ul class="acitem">
							<li>
								<a class="<?=($active_menu == "teknisi" ? 'active' : '')?>" href="<?=base_url()?>teknisi">
									<span class="list-icon">&nbsp;</span>Technician
								</a>
							</li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="user") { echo 'class="expand"'; } ?>>
						<a href="#" class="zoom"><img style="float: left; margin: 5px 0px 0px -30px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/mn/userpro.png" width="25" height="25" style="margin-top:10px;"> <b> User Management </b></a>
						<ul class="acitem">
							<li>
								<a class="<?=($active_menu == "user" ? 'active' : '')?>" href="<?=base_url()?>user">
									<span class="list-icon">&nbsp;</span>User Access
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>

	</div>
    <div id="container">
        <div id="header" class="dark_d">
			<div class="header_left">
				<div class="logo">
					<img style="float: left; margin: 5px 0px 0px 0px;" src="<?=base_url()?>assets/constellation/assets/images/bijak.png" width="50" height="50">
					<span style="width: 900px; float: left; margin-top: -50px; margin-left: 60px">
						<span style="font-size:16px; color: white; font-weight: bold; margin: -25px 0px 0px 0px;">BIJAK INTEGRATED MONITORING APPLICATION</span>
						<br>
						<span style="font-size:10px; color: white; font-weight: bold">PT. BINTANG JASA ARTHA KELOLA</span><br>
						<br>
						<span style="margin-top: 50px; font-size:10px; color: white;"> <b>User Access : </b><?=$session->userdata['nama']?> ||</span>
						<span style="font-size:10px; color: white;"><b>Credentials : </b><?=$session->userdata['nama_dept']?></span>
					</span>
				</div>
			</div>
			<div class="header_right">
				
				<div id="user_nav">
					<ul>
						<li class="logout"><a href="<?=base_url() ?>" title="Logout System"><img style="float: left; margin: 0px 0px 0px 0px;" src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/exit.png" width="40" height="40">Logout</a>
						</li>
						<li href="#" class="button" style="float: left; margin: -5px 0px 0px 50px; "><img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/calendar.png" width="16" height="15">
						<small style="color:black;font-size:10px; ">Tanggal :</small>
						<small id="Date" style="color:black;font-size:10px; ">01 January 2020</small>
						</li>
						<li style="float: left; margin: 0px 0px 0px 50px; ">
							<p class="button no-margin" style="background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(0,107,141,1) 0%, rgba(0,69,91,1) 90% );">
							<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/clock.png" width="20" height="20">
							<b id="hours" style="color:white;font-size:15px;">00</b>
							<b id="point" style="color:white;font-size:15px;">:</b>
							<b id="min" style="color:white;font-size:15px;">00</b>
							<b id="point" style="color:white;font-size:15px;">:</b>
							<b id="sec" style="color:white;font-size:15px;">00</b>
							</p>
						</li>
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
				
						<?php echo $__env->yieldContent('content'); ?>
					</div>
                </div>
            </div>
            <span class="clear"></span>
        </div>
    </div>
</body>

</html>

<script src="<?=base_url()?>assets/constellation/assets/js/modal.js"></script>

<script>

	$(document).ready(function() {
		// Create two variable with the names of the months and days in an array
		var monthNames = ["Januari", "Febriari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]

		// Create a newDate() object
		var newDate = new Date();
		// Extract the current date from Date object
		newDate.setDate(newDate.getDate());
		// Output the day, date, month and year    
		$('#Date').html(newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

		setInterval(function() {
			// Create a newDate() object and extract the seconds of the current time on the visitor's
			var seconds = new Date().getSeconds();
			// Add a leading zero to seconds value
			$("#sec").html((seconds < 10 ? "0" : "") + seconds);
		}, 1000);

		setInterval(function() {
			// Create a newDate() object and extract the minutes of the current time on the visitor's
			var minutes = new Date().getMinutes();
			// Add a leading zero to the minutes value
			$("#min").html((minutes < 10 ? "0" : "") + minutes);
		}, 1000);

		setInterval(function() {
			// Create a newDate() object and extract the hours of the current time on the visitor's
			var hours = new Date().getHours();
			// Add a leading zero to the hours value
			$("#hours").html((hours < 10 ? "0" : "") + hours);
		}, 1000);

	});
	
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