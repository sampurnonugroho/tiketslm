<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width" />
    <title>B.I.M.A V.1.1</title>
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
	<link href="<?=base_url()?>assets/constellation/assets/css/mini3537.css?files=reset,common,form,standard,960.gs.fluid,simple-lists,block-lists,planning,table,calendars,wizard,gallery" rel="stylesheet" type="text/css">
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
		
		.easyui-validatebox {
			margin: 0px;
			padding-top: 0px;
			padding-bottom: 0px;
			height: 28px;
			line-height: 28px;
			width: 152px;
			ime-mode: disabled;
		}
		
		#theme-default .acitem li a.active {
			background: #f9f9f9 !important;
			color: #333 !important;
		}
		
		.no-js #loader { display: none;  }
		.js #loader { display: block; position: abso`lute; left: 100px; top: 0; }
		.se-pre-con {
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background: url(<?=base_url()?>depend/images/Preloader_8.gif) center no-repeat #fff;
		}
		
		.message.success li, div.message.success, p.message.success {
			color: white;
		}
		
		.jconfirm.jconfirm-white .jconfirm-bg, .jconfirm.jconfirm-light .jconfirm-bg {
			background-color: #444;
			opacity: 0.2;
			z-index: -1;
		}

		.action-tabs {
			display: none;
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
				<li><a href="<?=base_url()?>all_runsheet" title="All Run Sheet"><span class="icon_block m_projects">All Run Sheet</span></a></li>
				<li><a href="#" title="Calendar"><span class="icon_block m_events">Calendar</span></a></li>
				<li><a href="#" title="All Reports"><span class="icon_block p_book">All Reports</span></a></li>
				<li><a href="#" title="Directory System"><span class="icon_block m_media">Directory System</span></a></li>
				<li><a href="#" title="Settings"><span class="icon_block m_settings">Settings</span></a></li>
				
			</ul>
		</div>
		<div id="sidebar">
			<div id="secondary_nav">
				<ul id="sidenav" class="accordion_mnu collapsible orange_f">
					<div class="btn_30_dark">
						<span class="icon user_business_boss_co"></span>
						<span class="btn_link"><b style="font-size: 16px; margin-top:40px;">MENU SYSTEM<br>CASH IN TRANSIT</b></span>
					</div>
					
					<li <?php if($active_menu=="rekon_atm"
							  || $active_menu=="admcit_nas"
							  || $active_menu=="admcit_bra"
							  || $active_menu=="sla"
							  || $active_menu=="atmcr_nas"
							  || $active_menu=="atmcr_bra"
							  || $active_menu=="jurnal") { echo 'class="expand"'; } ?>>
						<a href="#"><span class="nav_icon computer_imac"></span> Actual Daily Monitoring <span class="down_arrow">&nbsp;</span></a>
						<ul class="acitem">
							<li>
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> Cash In Transit (CIT)</b>
							</li><br>
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
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> ATM Cash Replenishment</b>
							</li><br>
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
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> SLA FLM & SLM </b>
							</li><br>
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
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> REPORT </b>
							</li><br>
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
						<a href="#"><span class="nav_icon computer_imac"></span> Result Data Performance </a>
						<ul class="acitem">
							<li>
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> Cash In Transit (CIT)</b>
							</li><br>
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
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> ATM Cash Replenishment</b>
							</li><br>
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
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> ATM FLM & SLM </b>
							</li><br>
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
					
					<li <?php if($active_menu=="cashtransit" 
							|| $active_menu=="cashreplenish"
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
						<a href="#"><span class="nav_icon computer_imac"></span> Planning & Preparation </a>
						<ul class="acitem">
							<li>
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> Data Planning H-1</b>
							</li><br>
							<li>
								<a class="<?=($active_menu == "cashtransit" ? 'active' : '')?>" href="<?=base_url()?>cashtransit">
									<span class="list-icon">&nbsp;</span>Cash In Transit (CIT)
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "cashreplenish" ? 'active' : '')?>" href="<?=base_url()?>cashreplenish">
									<span class="list-icon">&nbsp;</span>Cash Replanish (CR)
								</a>
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
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> Data Planning H-0</b>
							</li><br>
							<li>
								<a class="<?=($active_menu == "cashtransith0" ? 'active' : '')?>" href="<?=base_url()?>cashtransit">
									<span class="list-icon">&nbsp;</span>Cash In Transit (CIT)
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "cashreplenishh0" ? 'active' : '')?>" href="<?=base_url()?>cashreplenish">
									<span class="list-icon">&nbsp;</span>Cash Replanish (CR)
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "handover3" ? 'active' : '')?>" href="<?=base_url()?>handover3">
									<span class="list-icon">&nbsp;</span>Hand-Over (HO-Incoming)
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "handover4" ? 'active' : '')?>" href="<?=base_url()?>handover4">
									<span class="list-icon">&nbsp;</span>Hand-Over (HO-Outgoing)
								</a>
							</li>
							<li>
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> Data Input For Runsheet </b>
							</li><br>
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
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> Data Planning CPC & Stock</b></li><br>
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
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> Problem Seal</b>
							</li><br>
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
						<a href="#"><span class="nav_icon computer_imac"></span> Cash Processing </a>
						<ul class="acitem">
							<li>
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> ATM Cash Replenishment</b>
							</li><br>
							<li>
								<a class="<?=($active_menu == "cashprocessing_return" ? 'active' : '')?>" href="<?=base_url()?>cashprocessing_return">
									<span class="list-icon">&nbsp;</span>ATM CR - CASH RETURN
								</a>
							</li>
							<li>
								<a class="<?=($active_menu == "cashprocessing_batal" ? 'active' : '')?>" href="<?=base_url()?>cashprocessing_batal">
									<span class="list-icon">&nbsp;</span>ATM CR - BATAL REPLENISH
								</a>
							</li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="barcode_batch" 
								|| $active_menu=="barcode_generates"
								|| $active_menu=="inventory"
								|| $active_menu=="seal"
								|| $active_menu=="bag"
								|| $active_menu=="tbag"
								|| $active_menu=="cassette"
								|| $active_menu=="receipt"
								|| $active_menu=="combination_lock"
								|| $active_menu=="vehicle"
								|| $active_menu=="handphone") { echo 'class="expand"'; } ?>>
						<a href="#"><span class="nav_icon computer_imac"></span> Logistics & Identity</a>
						<ul class="acitem">
							<li>
								<a class="<?=($active_menu == "inventory" ? 'active' : '')?>" href="<?=base_url()?>inventory">
									<span class="list-icon">&nbsp;</span>Supplies & Sparepart
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
						<a href="#"><span class="nav_icon computer_imac"></span> Data Client & User </a>
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
						<a href="#"><span class="nav_icon computer_imac"></span> Billing & Invoices </a>
						<ul class="acitem">
							<li><img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> Cash In Transit (CIT)</b></li><br>
							<li><a href="<?=base_url()?>invoice"><span class="list-icon">&nbsp;</span>CIT - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>CIT - Branch</a></li>
							<li><img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM Cash Replenishment</b></li><br>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>ATM CR - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>ATM CR - Branch</a></li>
							<li><img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;"><b style="font-size: 12px; color:#ffffff;"> ATM FLM & SLM </b></li><br>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Nasional</a></li>
							<li><a href="javascript:void(0)"><span class="list-icon">&nbsp;</span>FLM & SLM - Branch</a></li>
						</ul>
					</li>
					
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
									|| $active_menu=="logistic") { echo 'class="expand"'; } ?>>
						<a href="#"><span class="nav_icon computer_imac"></span> Tickets Management </a>
						<ul class="acitem">
							<li>
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> Issue Ticket</b>
							</li><br>
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
								<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/status.png" width="20" height="20" style="margin-top:10px;">
								<b style="font-size: 12px; color:#ffffff;"> Status Ticket </b>
							</li><br>
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
						<a href="#"><span class="nav_icon computer_imac"></span> Troubleshooting Data </a>
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
						<a href="#"><span class="nav_icon computer_imac"></span> Structure Management </a>
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
						<a href="#"><span class="nav_icon computer_imac"></span> Technical Staff </a>
						<ul class="acitem">
							<li>
								<a class="<?=($active_menu == "teknisi" ? 'active' : '')?>" href="<?=base_url()?>teknisi">
									<span class="list-icon">&nbsp;</span>Technician
								</a>
							</li>
						</ul>
					</li>
					
					<li <?php if($active_menu=="user") { echo 'class="expand"'; } ?>>
						<a href="#"><span class="nav_icon computer_imac"></span> User Management </a>
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
					<img src="<?=base_url()?>assets/constellation/assets/images/bijak.png" width="50" height="50" alt="">
					<span style="width: 500px; float: left; margin-top: -40px; margin-left: 60px">
						<span style="color: white; font-weight: bold">Nama : (<?=$session->userdata['nama']?>)</span><br>
						<span style="color: white; font-weight: bold">Posisi : (<?=$session->userdata['nama_dept']?>)</span>
					</span>
				</div>
				
				<!--<div id="responsive_mnu">
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
				</div>-->
			</div>
			<div class="header_right">
				
				<div id="user_nav">
					<ul>
						<li class="user_thumb"><a href="#"><span class="icon"><img src="<?=base_url()?>depend/images/user_thumb.png" width="30" height="30" alt="User"></span></a></li>
						<li class="user_info"><span class="user_name">BIJAK INTEGRATED MONITORING APPLICATION </span></li>
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
								<ul class="alert message success" style="z-index: 99999; position: fixed; top: 1%; right: 1%; width: 200px; color: white" hidden>
									<li><?=$session->flashdata('success')?></li>
									<li class="close-bt"></li>
								</ul>
							</section>
						<?php elseif ($session->flashdata('error')): ?>
							<section class="grid_12">
								<ul class="alert message error" style="z-index: 99999; position: fixed; top: 1%; right: 1%; width: 200px; color: white" hidden>
									<li><?=$session->flashdata('error')?></li>
									<li class="close-bt"></li>
								</ul>
							</section>
						<?php endif; ?>
						
						<?php echo $__env->yieldContent('content'); ?>
					</div>
                </div>
            </div>
			<footer>
				<div class="float-right">
				<a href="#top" class="button"><img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/navigation-090.png" width="16" height="16"> Page top</a>
				</div>
			</footer>
            <span class="clear"></span>
        </div>
    </div>
</body>

</html>

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

<script src="<?=base_url()?>depend/js/custom-scripts.js"></script>

<script src="<?=base_url()?>assets/constellation/assets/js/modal.js"></script>

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
		
		$(document).on("click", ".close-bt", function() {
			$(".alert").remove();
		});
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
							// console.log(data);
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
 
<script>
/*=================
CHART 2
===================*/
$(function () {
	/*========================================================
	Data Point Highlighting, Tooltips and Cursor Tracking
	==========================================================*/
	var line1 = [
		['23-May-08', 300.55],
		['20-Jun-08', 566.5],
		['25-Jul-08', 300.88],
		['22-Aug-08', 509.84],
		['26-Sep-08', 300.13],
		['24-Oct-08', 600.75],
		['21-Nov-08', 303],
		['26-Dec-08', 308.56],
		['23-Jan-09', 660.14],
		['20-Feb-09', 346.51],
		['20-Mar-09', 560.99],
		['24-Apr-09', 386.15]
	];
	var plot1 = $.jqplot('chart2', [line1], {
		title: 'Data Monitoring',
		axes: {
			xaxis: {
				renderer: $.jqplot.DateAxisRenderer,
				tickOptions: {
					formatString: '%b&nbsp;%#d'
				}
			},
			yaxis: {
				tickOptions: {
					formatString: '$%.2f'
				}
			}
		},
		highlighter: {
			show: true,
			sizeAdjust: 7.5
		},
		cursor: {
			show: false
		},
		grid: {
			borderColor: '#ccc', // CSS color spec for border around grid.
			borderWidth: 2.0, // pixel width of border around grid.
			shadow: false // draw a shadow for grid.
		},
		seriesDefaults: {
			lineWidth: 2, // Width of the line in pixels.
			shadow: false, // show shadow or not.
			markerOptions: {
				show: true, // wether to show data point markers.
				style: 'filledCircle', // circle, diamond, square, filledCircle.
				// filledDiamond or filledSquare.
				lineWidth: 2, // width of the stroke drawing the marker.
				size: 14, // size (diameter, edge length, etc.) of the marker.
				color: '#ff8a00', // color of marker, set to color of line by default.
				shadow: true, // wether to draw shadow on marker or not.
				shadowAngle: 45, // angle of the shadow.  Clockwise from x axis.
				shadowOffset: 1, // offset from the line of the shadow,
				shadowDepth: 3, // Number of strokes to make when drawing shadow.  Each stroke
				// offset by shadowOffset from the last.
				shadowAlpha: 0.07 // Opacity of the shadow
			}
		}
	});
});

$(function() {
    plot2 = jQuery.jqplot('chart5',
        [
            [
                ['Verwerkende industrie', 9],
                ['Retail', 0],
                ['Primaire producent', 0],
                ['Out of home', 0],
                ['Groothandel', 0],
                ['Grondstof', 0],
                ['Consument', 3],
                ['Bewerkende industrie', 2]
            ]
        ], {
            title: ' ',
            seriesDefaults: {
                shadow: false,
                renderer: jQuery.jqplot.PieRenderer,
                rendererOptions: {
                    startAngle: 180,
                    sliceMargin: 4,
                    showDataLabels: true
                }
            },
            grid: {
                borderColor: '#ccc', // CSS color spec for border around grid.
                borderWidth: 2.0, // pixel width of border around grid.
                shadow: false // draw a shadow for grid.
            },
            legend: {
                show: false,
                location: 'w'
            }
        }
    );
});
</script>
	