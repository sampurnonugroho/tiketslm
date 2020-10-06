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
	<link href="<?=base_url()?>constellation/assets/css/mini3537.css?files=reset,common,form,standard,960.gs.fluid,simple-lists,block-lists,planning,table,calendars,wizard,gallery" rel="stylesheet" type="text/css">
	
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link rel="icon" type="image/png" href="favicon-large.png">
	<link href="<?=base_url()?>constellation/assets/css/select2.min.css" rel="stylesheet" />
	<!-- Modernizr for support detection, all javascript libs are moved right above </body> for better performance -->
	<script src="<?=base_url()?>constellation/assets/js/libs/modernizr.custom.min.js"></script>
	

</head>

<body>
	<div hidden>// vscode-fold=3</div>
	<!-- Header -->
	
	<!-- Server status -->
	<header>
		<div class="container_12" >
			<p id="skin-name"><small>Portal<br> Monitoring System</small> <strong>1.0</strong></p>
			<div class="server-info">Server: <strong>Apache (unknown)</strong></div>
			<div class="server-info">Php: <strong>5.5.31</strong></div>
		</div>
	</header>
	<!-- End server status -->
	
	<!-- Main nav -->
	<nav id="main-nav">
		
		<ul class="container_12" >
			<li class="home <?php if($active_menu=="dashboard" 
									|| $active_menu=="ticket_list" 
									|| $active_menu=="approval_list" 
									|| $active_menu=="myticket_list" 
									|| $active_menu=="myassignment_list") { echo "current"; } ?>"><a href="javascript:void(0)" title="Home">Home</a>
				<ul>
					<li class="<?=($active_menu == "dashboard" ? 'current' : '')?>"><a href="<?=base_url()?>dashboard" title="Dashboard">Dashboard</a></li>
					<li class="<?=($active_menu == "ticket_list" ? 'current' : '')?>"><a href="<?=base_url()?>ticket/ticket_list" title="My profile">List Ticket</a></li>
					<li class="<?=($active_menu == "approval_list" ? 'current' : '')?>"><a href="<?=base_url()?>approval/approval_list" title="My profile">Approval Ticket</a></li>
					<li class="<?=($active_menu == "myticket_list" ? 'current' : '')?>"><a href="<?=base_url()?>myticket/myticket_list" title="My profile"> My Troubleshoot Ticket</a></li>
					<li class="<?=($active_menu == "myassignment_list" ? 'current' : '')?>"><a href="<?=base_url()?>myassignment/myassignment_list" title="My profile">Assignment Ticket</a></li>
					
				</ul>
			</li>
			<li class="write <?php if($active_menu=="karyawan" 
										|| $active_menu=="user"
										|| $active_menu=="jabatan"
										|| $active_menu=="departemen"
										|| $active_menu=="bagian_departemen"
										|| $active_menu=="kategori"
										|| $active_menu=="sub_kategori"
										|| $active_menu=="teknisi"
										|| $active_menu=="kondisi"
										|| $active_menu=="client") { echo "current"; } ?>"><a href="javascript:void(0)" title="Write">Write</a>
				<ul>
					<li class="<?=($active_menu == "karyawan" ? 'current' : '')?>"><a href="<?=base_url()?>karyawan" title="Karyawan">Karyawan</a></li>
					<li class="<?=($active_menu == "user" ? 'current' : '')?>"><a href="<?=base_url()?>user" title="User/Pengguna">User/Pengguna</a></li>
					<li class="<?=($active_menu == "jabatan" ? 'current' : '')?>"><a href="<?=base_url()?>jabatan" title="Jabatan">Jabatan</a></li>
					<li class="<?=($active_menu == "departemen" ? 'current' : '')?>"><a href="<?=base_url()?>departemen" title="Departemen">Departemen</a></li>
					<li class="<?=($active_menu == "bagian_departemen" ? 'current' : '')?>"><a href="<?=base_url()?>bagian_departemen" title="Bagian Departemen">Bagian Departemen</a></li>
					<li class="<?=($active_menu == "kategori" ? 'current' : '')?>"><a href="<?=base_url()?>kategori" title="Kategori">Kategori Trouble</a></li>
					<li class="<?=($active_menu == "sub_kategori" ? 'current' : '')?>"><a href="<?=base_url()?>sub_kategori" title="Sub Kategori">Sub Kategori</a></li>
					<li class="<?=($active_menu == "teknisi" ? 'current' : '')?>"><a href="<?=base_url()?>teknisi" title="Teknisi">Teknisi</a></li>
					<li class="<?=($active_menu == "kondisi" ? 'current' : '')?>"><a href="<?=base_url()?>kondisi" title="Severity Level">Severity Level</a></li>
					<li class="<?=($active_menu == "client" ? 'current' : '')?>"><a href="<?=base_url()?>client" title="Client">Client</a></li>
					
				</ul>
			</li>
			</ul>
	</nav>
	<!-- End main nav -->
	
	<!-- Sub nav -->
	<div id="sub-nav" ><div class="container_12">
		<ul id="status-infos">
			<li class="spaced">Logged as: <strong>Admin</strong></li>
			<li>
				<a href="javascript:void(0)" class="button" title="<?=isset($notif_list_ticket)?$notif_list_ticket:"0"?> ticket">List Ticket <strong><?=isset($notif_list_ticket)?$notif_list_ticket:"0"?></strong></a>
				<div id="messages-list" class="result-block">
					<span class="arrow"><span></span></span>
					
					<ul class="small-files-list icon-mail">
						<li>
							<a href="javascript:void(0)"><strong>10:15</strong> Please update...<br>
							<small>From: System</small></a>
						</li>
					</ul>
					
					<p id="messages-info" class="result-info"><a href="javascript:void(0)">Go to inbox &raquo;</a></p>
				</div>
			</li>
			<li>
				<a href="javascript:void(0)" class="button" title="<?=isset($notif_approval)?$notif_approval:"0"?> ticket">Approval Ticket <strong><?=isset($notif_approval)?$notif_approval:"0"?></strong></a>
				<div id="comments-list" class="result-block">
					<span class="arrow"><span></span></span>
					
					<ul class="small-files-list icon-comment">
						<li>
							<a href="javascript:void(0)"><strong>Jane</strong>: I don't think so<br>
							<small>On <strong>Post title</strong></small></a>
						</li>
					</ul>
					
					<p id="comments-info" class="result-info"><a href="javascript:void(0)">Manage comments &raquo;</a></p>
				</div>
			</li>
			<li>
				<a href="javascript:void(0)" class="button" title="<?=isset($notif_assignment)?$notif_assignment:"0"?> ticket">Assignment Ticket <strong><?=isset($notif_assignment)?$notif_assignment:"0"?></strong></a>
				<div id="comments-list" class="result-block">
					<span class="arrow"><span></span></span>
					
					<ul class="small-files-list icon-comment">
						<li>
							<a href="javascript:void(0)"><strong>Jane</strong>: I don't think so<br>
							<small>On <strong>Post title</strong></small></a>
						</li>
					</ul>
					
					<p id="comments-info" class="result-info"><a href="javascript:void(0)">Manage comments &raquo;</a></p>
				</div>
			</li>
			<li><a href="<?=base_url()?>" class="button red" title="Logout"><span class="smaller">LOGOUT</span></a></li>
		</ul>
		
		<!--<a href="javascript:void(0)" title="Help" class="nav-button"><b>Help</b></a>
	
		<form id="search-form" name="search-form" method="post" action="http://www.display-inline.fr/demo/constellation/template/search.php">
			<input type="text" name="s" id="s" value="" title="Search admin..." autocomplete="off">
		</form>-->
	
	</div></div>
	<!-- End sub nav -->
	
	<!-- Status bar -->
	<div id="status-bar"><div class="container_12">
		<div class="float-left">
			<div class="button menu-opener ">
			<img src="<?=base_url()?>constellation/assets/images/icons/fugue/application-blog.png" width="16" height="16"> Actual Daily Monitoring
				<div class="menu-arrow">
					<img src="<?=base_url()?>constellation/assets/images/menu-open-arrow.png" width="16" height="16"></div>
						<div class="menu">
							<ul>
								<li class="icon_export"><a href=""><p style="padding:-80px">Cash In Transit (CIT)</p></a>
									<ul>
										<li class="icon_network float-left"><a href="javascript:void(0)">CIT - National</a></li>
										<li class="icon_server float-left"><a href="javascript:void(0)">CIT - Branch</a></li>
									</ul>
								</li>
								<li class="sep"></li>
								<li class="icon_export"><a href="">ATM Cash Replenish </a>
									<ul>
										<li class="icon_network float-left"><a href="javascript:void(0)">ATM CR - National</a></li>
										<li class="icon_server float-left"><a href="javascript:void(0)">ATM CR - Branch</a></li>
									</ul>
								</li>
								<li class="sep"></li>
								<li class="icon_export"><a href="">ATM FLM & SLM </a>
									<ul>
										<li class="icon_network float-left"><a href="javascript:void(0)">FLM & SLM - National</a></li>
										<li class="icon_server float-left"><a href="javascript:void(0)">FLM & SLM - Branch</a></li>
									</ul>
								</li>		
							</ul>
						</div>
			</div>
			<div class="button menu-opener ">
			<img src="<?=base_url()?>constellation/assets/images/icons/fugue/application-blog.png" width="16" height="16"> Planning & Preparation
				<div class="menu-arrow">
					<img src="<?=base_url()?>constellation/assets/images/menu-open-arrow.png" width="16" height="16"></div>
						<div class="menu">
							<ul>
								<li class="icon_export"><a href=""><p style="padding:-80px">Request Order H - 1</p></a>
									<ul>
										<li class="icon_network float-left"><a href="javascript:void(0)">Request Order CIT</a></li>
										<li class="icon_server float-left"><a href="javascript:void(0)">Request Order ATM</a></li>
									</ul>
								</li>
								<li class="sep"></li>
								<li class="icon_export"><a href="">Request Order H - 0</a>
									<ul>
										<li class="icon_network float-left"><a href="javascript:void(0)">Request Order CIT</a></li>
										<li class="icon_server float-left"><a href="javascript:void(0)">Request Order ATM</a></li>
									</ul>
								</li>
								<li class="sep"></li>
								<li class="icon_export"><a href="">Data Input Run Sheet</a>
									<ul>
										<li class="icon_network float-left"><a href="javascript:void(0)">Input Operasional</a></li>
										<li class="icon_server float-left"><a href="javascript:void(0)">Security Control</a></li>
										<li class="icon_server float-left"><a href="javascript:void(0)">Cash Processing</a></li>
									</ul>
								</li>		
							</ul>
						</div>
			</div>
			<div class="button menu-opener ">
			<img src="<?=base_url()?>constellation/assets/images/icons/fugue/application-blog.png" width="16" height="16"> Result Data Performance
				<div class="menu-arrow">
					<img src="<?=base_url()?>constellation/assets/images/menu-open-arrow.png" width="16" height="16"></div>
						<div class="menu">
							<ul>
								<li class="icon_export"><a href=""><p>Results ATM Services</p></a>
									<ul>
										<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time All <br> Bank & Branch</a></li>
										<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time Per <br> Bank Nasional</a>
										<ul>
											<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time Per <br> Bank Nasional<br>Untuk ATM</a></li>
											<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time Per <br> Bank Nasional<br>Untuk CDM</a></li>
										</ul>
										</li>
										<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time All <br>Bank Per Branch</a>
										<ul>
											<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time All <br>Bank Per Branch<br>Untuk ATM</a></li>
											<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time All <br>Bank Per Branch<br>Untuk CDM</a></li>
										</ul>
										</li>
										<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time<br> Branch Per Bank</a>
										<ul>
											<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time Branch Per Bank Untuk ATM</a></li>
											<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time Branch Per Bank Untuk CDM</a></li>
										</ul>
										</li>
										<li class="icon_network float-left"><a href="javascript:void(0)">Total Up Time Per ID</a></li>
									</ul>
									
								</li>
								<li class="sep"></li>
								<li class="icon_export"><a href=""><p>Result Cash In Transit</p></a>
									<ul>
										<li class="icon_network float-left"><a href="javascript:void(0)">Result CIT Delivery</a>
										<ul>
											<li class="icon_network float-left"><a href="javascript:void(0)">Nasional</a></li>
											<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
										</ul>
										</li>
										<li class="icon_network float-left"><a href="javascript:void(0)">Result CIT PickUp</a>
										<ul>
											<li class="icon_network float-left"><a href="javascript:void(0)">Nasional</a></li>
											<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
										</ul>
										</li>
									</ul>
								</li>
							</ul>
						</div>
			</div>
			<div class="button menu-opener ">
			<img src="<?=base_url()?>constellation/assets/images/icons/fugue/application-blog.png" width="16" height="16"> Cash Processing
				<div class="menu-arrow">
					<img src="<?=base_url()?>constellation/assets/images/menu-open-arrow.png" width="16" height="16"></div>
						<div class="menu">
						<ul>
							<li class="icon_network float-left"><a href="javascript:void(0)">ATM Services</a>
							<ul>
								<li class="icon_network float-left"><a href="javascript:void(0)">Daily Saldo Report</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Bank</a></li>
							</ul>
							</li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Cash In Transit</a>
							<ul>
								<li class="icon_network float-left"><a href="javascript:void(0)">Daily Saldo Report</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Bank</a></li>
							</ul>
								</li>
						</ul>	
						</div>
			</div>
			<div class="button menu-opener ">
			<img src="<?=base_url()?>constellation/assets/images/icons/fugue/application-blog.png" width="16" height="16"> Invoice
				<div class="menu-arrow">
					<img src="<?=base_url()?>constellation/assets/images/menu-open-arrow.png" width="16" height="16"></div>
						<div class="menu">
						<ul>
							<li class="icon_network float-left"><a href="javascript:void(0)">ATM Services</a>
							<ul>
								<li class="icon_network float-left"><a href="javascript:void(0)">Nasional</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Bank</a></li>
							</ul>
							</li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Cash In Transit</a>
							<ul>
								<li class="icon_network float-left"><a href="javascript:void(0)">Nasional</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Bank</a></li>
							</ul>
								</li>
						</ul>	
						</div>
			</div>
			<div class="button menu-opener ">
			<img src="<?=base_url()?>constellation/assets/images/icons/fugue/application-blog.png" width="16" height="16"> Logistics
				<div class="menu-arrow">
					<img src="<?=base_url()?>constellation/assets/images/menu-open-arrow.png" width="16" height="16"></div>
						<div class="menu">
						<ul>
							<li class="icon_network float-left"><a href="javascript:void(0)">Logistic Stock</a>
							<ul>
								<li class="icon_network float-left"><a href="javascript:void(0)">Nasional</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
							</ul>
							</li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Logistic Used</a>
							<ul>
								<li class="icon_network float-left"><a href="javascript:void(0)">Nasional</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
							</ul>
								</li>
						</ul>	
						</div>
			</div>
			<div class="button menu-opener ">
			<img src="<?=base_url()?>constellation/assets/images/icons/fugue/application-blog.png" width="16" height="16"> Gadget & GPS
				<div class="menu-arrow">
					<img src="<?=base_url()?>constellation/assets/images/menu-open-arrow.png" width="16" height="16"></div>
						<div class="menu">
						<ul>
							<li class="icon_network float-left"><a href="javascript:void(0)">Nasional</a></li>
							<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
						</ul>	
						</div>
			</div>
			<div class="button menu-opener ">
			<img src="<?=base_url()?>constellation/assets/images/icons/fugue/application-blog.png" width="16" height="16"> Data Client
				<div class="menu-arrow">
					<img src="<?=base_url()?>constellation/assets/images/menu-open-arrow.png" width="16" height="16"></div>
						<div class="menu">
						<ul>
							<li class="icon_network float-left"><a href="javascript:void(0)">Logistic Stock</a>
							<ul>
								<li class="icon_network float-left"><a href="javascript:void(0)">Nasional</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
							</ul>
							</li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Logistic Used</a>
							<ul>
								<li class="icon_network float-left"><a href="javascript:void(0)">Nasional</a></li>
								<li class="icon_network float-left"><a href="javascript:void(0)">Branches</a></li>
							</ul>
								</li>
						</ul>	
						</div>
			</div>
									
	
		</div>							
	
		
		<!-- v1.5: you can now add class red to the breadcrumb -->
		<!--<ul id="breadcrumb">
			<li><a href="javascript:void(0)" title="Home">Home</a></li>
			<li><a href="javascript:void(0)" title="Dashboard">Dashboard</a></li>
		</ul>-->
		</div></div>
	<!-- End status bar -->
	
	<div id="header-shadow"></div>
	<!-- End header -->
	
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
	
	<!-- End content -->
	
	<footer>
		
		<div class="float-left">
			<a href="javascript:void(0)" class="button">Help</a>
			<a href="javascript:void(0)" class="button">About</a>
		</div>
		
		<div class="float-right">
			<a href="#top" class="button"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-090.png" width="16" height="16"> Page top</a>
		</div>
		
	</footer>
	
	<!--
	
	Updated as v1.5:
	Libs are moved here to improve performance
	
	-->
	
	<!-- Combined JS load -->
	<script src="<?=base_url()?>constellation/assets/js/minia4f1.php?files=libs/jquery-1.6.3.min,old-browsers,libs/jquery.hashchange,jquery.accessibleList,searchField,common,standard,jquery.tip,jquery.contextMenu,jquery.modal,list"></script>
	<!--[if lte IE 8]><script src="<?=base_url()?>constellation/assets/js/standard.ie.js"></script><![endif]-->
	
	<!-- Plugins -->
	<script src="<?=base_url()?>constellation/assets/js/libs/jquery.dataTables.min.js"></script>
	<script src="<?=base_url()?>constellation/assets/js/libs/jquery.datepick/jquery.datepick.min.js"></script>
	
	<script>
		/*
		 * This script is dedicated to building and refreshing the demo chart
		 * Remove if not needed
		 */
		
		// Add listener for tab
		// $('#tab-stats').onTabShow(function() { drawVisitorsChart(); }, true);
		
		// Handle viewport resizing
		var previousWidth = $(window).width();
		$(window).resize(function()
		{
			if (previousWidth != $(window).width())
			{
				// drawVisitorsChart();
				previousWidth = $(window).width();
			}
		});
		
		// Demo chart
		// function drawVisitorsChart() {

			// // Create our data table.
			// var data = new google.visualization.DataTable();
			// var raw_data = [['Website', 50, 73, 104, 129, 146, 176, 139, 149, 218, 194, 96, 53],
							// ['Shop', 82, 77, 98, 94, 105, 81, 104, 104, 92, 83, 107, 91],
							// ['Forum', 50, 39, 39, 41, 47, 49, 59, 59, 52, 64, 59, 51],
							// ['Others', 45, 35, 35, 39, 53, 76, 56, 59, 48, 40, 48, 21]];
			
			// var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
			
			// data.addColumn('string', 'Month');
			// for (var i = 0; i < raw_data.length; ++i)
			// {
				// data.addColumn('number', raw_data[i][0]);
			// }
			
			// data.addRows(months.length);
			
			// for (var j = 0; j < months.length; ++j)
			// {
				// data.setValue(j, 0, months[j]);
			// }
			// for (var i = 0; i < raw_data.length; ++i)
			// {
				// for (var j = 1; j < raw_data[i].length; ++j)
				// {
					// data.setValue(j-1, i+1, raw_data[i][j]);
				// }
			// }
			
			// // Create and draw the visualization.
			// var div = $('#chart_div');
			// new google.visualization.ColumnChart(div.get(0)).draw(data, {
				// title: 'Monthly unique visitors count',
				// width: div.width(),
				// height: 330,
				// legend: 'right',
				// yAxis: {title: '(thousands)'}
			// });
			
			// // Message
			// notify('Chart updated');
		// };
		
	</script>
	
	<script>
		
		/*
		 * This script shows how to setup the various template plugins and functions
		 */
		
		
		$(document).ready(function()
		{
			/*
			 * Example context menu
			 */
			$(".alert").show(1000);
			window.setTimeout(function()  {
				$(".alert").fadeTo(500, 0).slideUp(500, function() {
					$(this).remove();
				});
			}, 5000);
			
			
			// Context menu for all favorites
			$('.favorites li').bind('contextMenu', function(event, list)
			{
				var li = $(this);
				
				// Add links to the menu
				if (li.prev().length > 0)
				{
					list.push({ text: 'Move up', link:'#', icon:'up' });
				}
				if (li.next().length > 0)
				{
					list.push({ text: 'Move down', link:'#', icon:'down' });
				}
				list.push(false);	// Separator
				list.push({ text: 'Delete', link:'#', icon:'delete' });
				list.push({ text: 'Edit', link:'#', icon:'edit' });
			});
			
			// Extra options for the first one
			$('.favorites li:first').bind('contextMenu', function(event, list)
			{
				list.push(false);	// Separator
				list.push({ text: 'Settings', icon:'terminal', link:'#', subs:[
					{ text: 'General settings', link: '#', icon: 'blog' },
					{ text: 'System settings', link: '#', icon: 'server' },
					{ text: 'Website settings', link: '#', icon: 'network' }
				] });
			});
			
			/*
			 * Dynamic tab content loading
			 */
			
			$('#tab-comments').onTabShow(function()
			{
				$(this).loadWithEffect('ajax-tab.html', function()
				{
					notify('Content loaded via ajax');
				});
			}, true);
			
			/*
			 * Table sorting
			 */
			
			// A small classes setup...
			$.fn.dataTableExt.oStdClasses.sWrapper = 'no-margin last-child';
			$.fn.dataTableExt.oStdClasses.sInfo = 'message no-margin';
			$.fn.dataTableExt.oStdClasses.sLength = 'float-left';
			$.fn.dataTableExt.oStdClasses.sFilter = 'float-right';
			$.fn.dataTableExt.oStdClasses.sPaging = 'sub-hover paging_';
			$.fn.dataTableExt.oStdClasses.sPagePrevEnabled = 'control-prev';
			$.fn.dataTableExt.oStdClasses.sPagePrevDisabled = 'control-prev disabled';
			$.fn.dataTableExt.oStdClasses.sPageNextEnabled = 'control-next';
			$.fn.dataTableExt.oStdClasses.sPageNextDisabled = 'control-next disabled';
			$.fn.dataTableExt.oStdClasses.sPageFirst = 'control-first';
			$.fn.dataTableExt.oStdClasses.sPagePrevious = 'control-prev';
			$.fn.dataTableExt.oStdClasses.sPageNext = 'control-next';
			$.fn.dataTableExt.oStdClasses.sPageLast = 'control-last';
			
			// Apply to table
			$('.sortable').each(function(i)
			{
				// DataTable config
				var table = $(this),
					oTable = table.dataTable({
						/*
						 * We set specific options for each columns here. Some columns contain raw data to enable correct sorting, so we convert it for display
						 * @url http://www.datatables.net/usage/columns
						 */
						aoColumns: [
							{ bSortable: false },	// No sorting for this columns, as it only contains checkboxes
							{ sType: 'string' },
							{ bSortable: false },
							{ sType: 'numeric', bUseRendered: false, fnRender: function(obj) // Append unit and add icon
								{
									return '<small><img src="<?=base_url()?>constellation/assets/images/icons/fugue/image.png" width="16" height="16" class="picto"> '+obj.aData[obj.iDataColumn]+' Ko</small>';
								}
							},
							{ sType: 'date' },
							{ sType: 'numeric', bUseRendered: false, fnRender: function(obj) // Size is given as float for sorting, convert to format 000 x 000
								{
									return obj.aData[obj.iDataColumn].split('.').join(' x ');
								}
							},
							{ bSortable: false }	// No sorting for actions column
						],
						
						/*
						 * Set DOM structure for table controls
						 * @url http://www.datatables.net/examples/basic_init/dom.html
						 */
						sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
						
						/*
						 * Callback to apply template setup
						 */
						fnDrawCallback: function()
						{
							this.parent().applyTemplateSetup();
						},
						fnInitComplete: function()
						{
							this.parent().applyTemplateSetup();
						}
					});
				
				// Sorting arrows behaviour
				table.find('thead .sort-up').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'asc']]);
					
					// Prevent bubbling
					return false;
				});
				table.find('thead .sort-down').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'desc']]);
					
					// Prevent bubbling
					return false;
				});
			});

			$('.sortable2').each(function(i)
			{
				// DataTable config
				var table = $(this),
					oTable = table.dataTable({
						/*
						 * We set specific options for each columns here. Some columns contain raw data to enable correct sorting, so we convert it for display
						 * @url http://www.datatables.net/usage/columns
						 */
						aoColumns: [
							{ bSortable: false },	// No sorting for this columns, as it only contains checkboxes
							{ sType: 'string' },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false }
						],
						
						/*
						 * Set DOM structure for table controls
						 * @url http://www.datatables.net/examples/basic_init/dom.html
						 */
						sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
						
						/*
						 * Callback to apply template setup
						 */
						fnDrawCallback: function()
						{
							this.parent().applyTemplateSetup();
						},
						fnInitComplete: function()
						{
							this.parent().applyTemplateSetup();
						}
					});
				
				// Sorting arrows behaviour
				table.find('thead .sort-up').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'asc']]);
					
					// Prevent bubbling
					return false;
				});
				table.find('thead .sort-down').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'desc']]);
					
					// Prevent bubbling
					return false;
				});
			});
			
			$('.sortableX').each(function(i)
			{
				// DataTable config
				var table = $(this),
					oTable = table.dataTable({
						/*
						 * We set specific options for each columns here. Some columns contain raw data to enable correct sorting, so we convert it for display
						 * @url http://www.datatables.net/usage/columns
						 */
						aoColumns: [
							{ bSortable: false },	// No sorting for this columns, as it only contains checkboxes
							{ sType: 'string' },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false },
							{ sType: 'string', bSortable: false }
						],
						
						/*
						 * Set DOM structure for table controls
						 * @url http://www.datatables.net/examples/basic_init/dom.html
						 */
						sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
						
						/*
						 * Callback to apply template setup
						 */
						fnDrawCallback: function()
						{
							this.parent().applyTemplateSetup();
						},
						fnInitComplete: function()
						{
							this.parent().applyTemplateSetup();
						}
					});
				
				// Sorting arrows behaviour
				table.find('thead .sort-up').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'asc']]);
					
					// Prevent bubbling
					return false;
				});
				table.find('thead .sort-down').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'desc']]);
					
					// Prevent bubbling
					return false;
				});
			});
			
			/*
			 * Datepicker
			 * Thanks to sbkyle! http://themeforest.net/user/sbkyle
			 */
			$('.datepicker').datepick({
				alignment: 'bottom',
				showOtherMonths: true,
				selectOtherMonths: true,
				renderer: {
					picker: '<div class="datepick block-border clearfix form"><div class="mini-calendar clearfix">' +
							'{months}</div></div>',
					monthRow: '{months}',
					month: '<div class="calendar-controls" style="white-space: nowrap">' +
								'{monthHeader:M yyyy}' +
							'</div>' +
							'<table cellspacing="0">' +
								'<thead>{weekHeader}</thead>' +
								'<tbody>{weeks}</tbody></table>',
					weekHeader: '<tr>{days}</tr>',
					dayHeader: '<th>{day}</th>',
					week: '<tr>{days}</tr>',
					day: '<td>{day}</td>',
					monthSelector: '.month',
					daySelector: 'td',
					rtlClass: 'rtl',
					multiClass: 'multi',
					defaultClass: 'default',
					selectedClass: 'selected',
					highlightedClass: 'highlight',
					todayClass: 'today',
					otherMonthClass: 'other-month',
					weekendClass: 'week-end',
					commandClass: 'calendar',
					commandLinkClass: 'button',
					disabledClass: 'unavailable'
				}
			});
		});
		
		// Demo modal
		function openDelete(id, url)
		{
			$.modal({
				content: 'Anda yakin akan menghapus data dengan ID : ('+id+')?',
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
	
		function buttonSubmit(id) {
			$('#'+id).submit();
		}
		
		
		$("#id_departemen").change(function(){
			var data = {id_departemen:$("#id_departemen").val()};
			$.ajax({
				type: "POST",
				url : "<?php echo base_url().'select/select_bagian_departemen'?>",				
				data: data,
				success: function(msg){
					$('#div-order').html(msg);
				}
			});
		});  
	</script>
	
	<script src="<?=base_url()?>constellation/assets/js/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="<?=base_url()?>constellation/assets/js/select2.min.js"></script>
	
	<script>
		jq341 = jQuery.noConflict();
		// console.log( "<h3>After $.noConflict(true)</h3>" );
		// console.log( "1st loaded jQuery version ($): " + $.fn.jquery + "<br>" );
		// console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
	
		jq341(document).ready(function()
		{
			// jq341(".js-example-basic-single").select2({no_results_text: "Oops, nothing found!"}); 
			jq341(".js-example-basic-single2").select2({no_results_text: "Oops, nothing found!"}); 
			jq341('.js-example-basic-single').select2({
				tags: true,
				tokenSeparators: [','],
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'select/select_bank'?>',
					delay: 250,
					type: "POST",
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function (data, page) {
						// console.log(data);
						return {
							results: data
						};
					}
				},
				maximumSelectionLength: 3,

				// add "(new tag)" for new tags
				createTag: function (params) {
				  var term = jq341.trim(params.term);

				  if (term === '') {
					return null;
				  }

				  return {
					id: term,
					text: term + ' (add new)'
				  };
				},
			}).on('select2:select', function (evt) {
				var data = jq341(".js-example-basic-single option:selected").text();
				// alert("Data yang dipilih adalah "+data);
				
				jq341('.js-example-basic-single2').select2({
					tags: true,
					tokenSeparators: [','],
					ajax: {
						dataType: 'json',
						url: '<?php echo base_url().'select/select_branch'?>',
						delay: 250,
						type: "POST",
						data: function(params) {
							return {
								search: params.term,
								bank: data,
							}
						},
						processResults: function (data, page) {
							// console.log(data);
							return {
								results: data
							};
						}
					},
					maximumSelectionLength: 3,

					// add "(new tag)" for new tags
					createTag: function (params) {
					  var term = jq341.trim(params.term);

					  if (term === '') {
						return null;
					  }

					  return {
						id: term,
						text: term + ' (add new)'
					  };
					},
				});
			});
			
			jq341('.js-example-basic-single3').select2({
				tags: true,
				tokenSeparators: [','],
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'select/select_area'?>',
					delay: 250,
					type: "POST",
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function (data, page) {
						// console.log(data);
						return {
							results: data
						};
					}
				},
				maximumSelectionLength: 3,

				// add "(new tag)" for new tags
				createTag: function (params) {
				  var term = jq341.trim(params.term);

				  if (term === '') {
					return null;
				  }

				  return {
					id: term,
					text: term + ' (add new)'
				  };
				},
			});
			
			jq341('.sort_by_bank').select2({
				tags: true,
				tokenSeparators: [','],
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'select/select_bank'?>',
					delay: 250,
					type: "POST",
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function (data, page) {
						// console.log(data);
						return {
							results: data
						};
					}
				},
				maximumSelectionLength: 3,

				// add "(new tag)" for new tags
				createTag: function (params) {
				  var term = jq341.trim(params.term);

				  if (term === '') {
					return null;
				  }

				  return {
					id: term,
					text: term + ' (add new)'
				  };
				},
			}).on('select2:select', function (evt) {
				var data = jq341(".sort_by_bank option:selected").text();
					alert(data);
					
				$.ajax({
					type: "POST",
					url : "<?php echo base_url().'select/getdataclient'?>",				
					data: {bank:data},
					success: function(msg){
						var jsdata = JSON.parse(msg);	
						$('.sortableX').dataTable().fnClearTable();
						$('.sortableX').dataTable().fnAddData(jsdata).fnDraw();
					}
				});
				// $('.sortableX').each(function(i) {
					// var table = $(this)
						// oTable = table.dataTable();
					// console.log(oTable);
					// // oTable.fnClearTable();
					// // oTable.fnAddData();
					// // oTable.fnDraw();
					
					// // var datax = [
						// // ["Trident","Internet Explorer 4.0","Win 95+","4","X","X","X","X","X","X","X"],
						// // ["Trident","Internet Explorer 4.0","Win 95+","4","X","X","X","X","X","X","X"],
						// // ["Trident","Internet Explorer 4.0","Win 95+","4","X","X","X","X","X","X","X"],
						// // ["Trident","Internet Explorer 4.0","Win 95+","4","X","X","X","X","X","X","X"],
						// // ["Trident","Internet Explorer 4.0","Win 95+","4","X","X","X","X","X","X","X"]
					// // ];
					// // console.log(datax);
					// // var jsdata = JSON.parse(datax);
					
					
					// // oTable.fnAddData(datax).fnDraw();
					// $.ajax({
						// type: "POST",
						// url : "<?php echo base_url().'select/getdataclient'?>",				
						// data: {bank:"MANDIRI"},
						// success: function(msg){
							// var jsdata = JSON.parse(msg);	
							// console.log(jsdata);
							// oTable.fnClearTable();
							// oTable.fnAddData(jsdata).fnDraw();
						// }
					// });
				// })
				
				// $.get('myUrl', function(newDataArray) {
					// datatable.clear();
					// datatable.rows.add(newDataArray);
					// datatable.draw();
				// });
				// $('.sortableX tbody tr').remove();
			});
		});
	</script>
</body>
</html>