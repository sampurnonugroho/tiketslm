@extends('layouts.master')

@section('content')
	<?php 
		// print_r($data_flm);
	?>
<script src="<?=base_url()?>constellation/assets/equipment/Chart.min.js"></script>
		<section class="grid_14">
			<div class="block-border">
				<form class="block-content form" id="complex_form" method="post" action="#">
				<h1>Main Dashboard & Summary Information 123</h1>
				
				<div class="block-controls">
					
					<ul class="controls-tabs js-tabs">
						<li class="current"><a href="#tab-gi" title="General Summary Information & Properties"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/24/Bar-Chart.png" width="24" height="24"></a></li>
						<li><a href="#tab-op" title="Operational Procedures & Manual Instruction"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/48/Save.png" width="24" height="24"></a></li>
						<li><a href="#tab-faq" title="Frequently Asked Question (FAQ)"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/48/Comment.png" width="24" height="24"></a></li>
						<li><a href="#tab-hds" title="Users"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/48/Pie-Chart.png" width="24" height="24"></a></li>
						<li><a href="#tab-dla" title="Informations"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/48/Line-Chart.png" width="24" height="24"></a></li>
						<li><a href="#tab-sl" title="Informations"><img src="<?=base_url()?>constellation/assets/images/icons/web-app/48/Info.png" width="24" height="24"></a></li>
					</ul>
					
				</div>
				<div class="columns">
					<div class="col200pxL-left">
						
						<h2>ADMINISTRATOR <BR>ALL PRIVILLAGE<BR>USER ACCESS</h2>
						
						<ul class="side-tabs js-tabs same-height">
							<li><a href="#tab-gi" title="General Summary Information & Properties">General Summary Information & Properties</a></li>
							<li><a href="#tab-op" title="Operational Procedures & User Guide">Operational Procedures & Manual Instruction</a></li>
							<li><a href="#tab-faq" title="Frequently Asked Question (FAQ)">Frequently Asked Question (FAQ)</a></li>
							
							<li><a href="#tab-hds" title="Operational Procedures & User Guide">Historycal Data & System Log Activity</a></li>
							<li><a href="#tab-dla" title="Operational Procedures & User Guide">Data Load Analytics</a></li>
							<li><a href="#tab-sl" title="System Licences">System Licences</a></li>
							
						</ul>
					
						<div class="block-border grid_10">
						
						</div>
					
					</div>
					
					<div class="col200pxL-right">
						<div id="tab-gi" class="tabs-content">
							<center>
								<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
							</center>
									<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>PORTAL MONITORING SYSTEM & SLA REPORT  
									</p>
										<div class="block-border grid_14">
											<div class="block-content">
												<h1>Monthly Graphic</h1>
												<canvas id="buyers" width="750" height="250"></canvas>
											</div>
										</div>
									<ul class="switch_bar orange_f grid_12 float-left">
										<li>
										<a href="<?=base_url()?>invoice"><span class="stats_icon current_work_sl"></span><span class="label">Analytics</span></a>
										</li>
										<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="stats_icon user_sl"></span><span class="label"> Users</span></a></li>
										<li><a href="#"><span class="stats_icon administrative_docs_sl"></span><span class="label">Content</span></a></li>
										<li><a href="#"><span class="stats_icon finished_work_sl"></span><span class="label">Task List</span></a></li>
										<li><a href="#"><span class="stats_icon config_sl"></span><span class="label">Settings</span></a></li>
										<li><a href="#"><span class="stats_icon archives_sl"></span><span class="label">Archive</span></a></li>
										<li><a href="#"><span class="stats_icon folder_sl"></span><span class="label">Media</span></a></li>
										<li><a href="#"><span class="stats_icon lightbulb_sl"></span><span class="label">Support</span></a></li>
									</ul>
								
									
								
						</div>
						
						
						<div id="tab-faq" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red">
								<div class="block-content">
								<h1>Frequently Asked Question (FAQ)</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										
									</ul>
								</div>
								<ul class="switch_bar black_rev">
									<li>
									<a href="<?=base_url()?>invoice"><span class="stats_icon current_work_sl"></span><span class="label">Analytics</span></a>
									</li>
									<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="stats_icon user_sl"></span><span class="label"> Users</span></a></li>
									<li><a href="#"><span class="stats_icon administrative_docs_sl"></span><span class="label">Content</span></a></li>
									<li><a href="#"><span class="stats_icon finished_work_sl"></span><span class="label">Task List</span></a></li>
									<li><a href="#"><span class="stats_icon config_sl"></span><span class="label">Settings</span></a></li>
									<li><a href="#"><span class="stats_icon archives_sl"></span><span class="label">Archive</span></a></li>
									<li><a href="#"><span class="stats_icon folder_sl"></span><span class="label">Media</span></a></li>
									<li><a href="#"><span class="stats_icon lightbulb_sl"></span><span class="label">Support</span></a></li>
								</ul>
							
								
								
								
								
								
								
								</div>
							</div>
							</section>

						</div>						
						<div id="tab-op" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red">
								<div class="block-content">
								<h1>Operational Procedure & Manual Instruction</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										
									</ul>
								</div>
								
								
								<div class="grid_14">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Operational Menu System</h6>
										</div>
										<div class="widget_content">
											<div class="accordion-basic" id="list-accordion">
												<a class="title"><span class="alert_notify orange" style="margin-top:15px;">1</span><p style="margin-top:-35px;">&nbsp Menu [Actual Daily Monitoring]</p></a>
												<div>
													<div class="widget_content">
														<table class="wtbl_list">
														<thead>
														<tr>
															<th>
																 Menu Title/Name
															</th>
															<th>
																 Function/Process
															</th>
														</tr>
														</thead>
														<tbody>
														<tr class="tr_even">
															<td>
																 Cash In Transit (CIT)
															</td>
															<td>
																Menu ini akan menampilkan halaman Actual Daily Monitoring CIT Nasional, dan CIT Branch
															</td>
														</tr>
														<tr class="tr_even">
															<td>
																 ATM Cash Replanish
															</td>
															<td>
																Menu ini akan menampilkan halaman Actual Daily Monitoring ATM CR Nasional, dan ATM CR Branch
															</td>
														</tr>
														<tr class="tr_even">
															<td>
																 ATM FLM & SLM
															</td>
															<td>
																Menu ini akan menampilkan halaman Actual Daily Monitoring ATM FLM & SLM Nasional, dan ATM FLM & SLM Branch
															</td>
														</tr>
														</tbody>
														</table>
													</div>
												
			
												</div>
												<a class="title"><span class="alert_notify orange" style="margin-top:15px;">2</span><p style="margin-top:-35px;">&nbsp Menu [Result Data Performance]</p></a>
												<div>
													<div class="widget_content">
														<table class="wtbl_list">
														<thead>
														<tr>
															<th>
																 Menu Title/Name
															</th>
															<th>
																 Function/Process
															</th>
														</tr>
														</thead>
														<tbody>
														<tr class="tr_even">
															<td>
																 Cash In Transit (CIT)
															</td>
															<td>
																Menu ini akan menampilkan halaman Result Data Performance CIT Nasional, dan CIT Branch
															</td>
														</tr>
														<tr class="tr_even">
															<td>
																 ATM Cash Replanish
															</td>
															<td>
																Menu ini akan menampilkan halaman Result Data Performance ATM CR Nasional, dan ATM CR Branch
															</td>
														</tr>
														<tr class="tr_even">
															<td>
																 ATM FLM & SLM
															</td>
															<td>
																Menu ini akan menampilkan halaman Result Data Performance ATM FLM & SLM Nasional, dan ATM FLM & SLM Branch
															</td>
														</tr>
														</tbody>
														</table>
													</div>
												
			
												</div>
												<a class="title"><span class="alert_notify orange" style="margin-top:15px;">3</span><p style="margin-top:-35px;">&nbsp Menu [Planning & Preparation]</p></a>
												<div>
													<div class="widget_content">
														<table class="wtbl_list">
														<thead>
														<tr>
															<th>
																 Menu Title/Name
															</th>
															<th>
																 Function/Process
															</th>
														</tr>
														</thead>
														<tbody>
														<tr class="tr_even">
															<td>
																 Cash In Transit (CIT)
															</td>
															<td>
																Menu ini akan menampilkan halaman Result Data Performance CIT Nasional, dan CIT Branch
															</td>
														</tr>
														<tr class="tr_even">
															<td>
																 ATM Cash Replanish
															</td>
															<td>
																Menu ini akan menampilkan halaman Result Data Performance ATM CR Nasional, dan ATM CR Branch
															</td>
														</tr>
														<tr class="tr_even">
															<td>
																 ATM FLM & SLM
															</td>
															<td>
																Menu ini akan menampilkan halaman Result Data Performance ATM FLM & SLM Nasional, dan ATM FLM & SLM Branch
															</td>
														</tr>
														</tbody>
														</table>
													</div>
												
			
												</div>
												<a class="title"><span class="alert_notify orange" style="margin-top:15px;">4</span><p style="margin-top:-35px;">&nbsp Menu [Logistic & Identity]</p></a>
												<div>
													<div class="widget_content">
														<table class="wtbl_list">
														<thead>
														<tr>
															<th>
																 Menu Title/Name
															</th>
															<th>
																 Function/Process
															</th>
														</tr>
														</thead>
														<tbody>
														<tr class="tr_even">
															<td>
																 Cash In Transit (CIT)
															</td>
															<td>
																Menu ini akan menampilkan halaman Result Data Performance CIT Nasional, dan CIT Branch
															</td>
														</tr>
														<tr class="tr_even">
															<td>
																 ATM Cash Replanish
															</td>
															<td>
																Menu ini akan menampilkan halaman Result Data Performance ATM CR Nasional, dan ATM CR Branch
															</td>
														</tr>
														<tr class="tr_even">
															<td>
																 ATM FLM & SLM
															</td>
															<td>
																Menu ini akan menampilkan halaman Result Data Performance ATM FLM & SLM Nasional, dan ATM FLM & SLM Branch
															</td>
														</tr>
														</tbody>
														</table>
													</div>
												
			
												</div>
												<a class="title"><span class="alert_notify orange" style="margin-top:15px;">5</span><p style="margin-top:-35px;">&nbsp Menu [Data Client & User]</p></a>
												<div>
											</div>
										</div>
									</div>
								</div>
								
								
								
								
								
								
								</div>
							</div>
							
							</section>
							
						</div>
						<div id="tab-sl" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red">
								<div class="block-content">
								<h1>System Licences</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										
									</ul>
								</div>
								<ul class="switch_bar black_rev">
									<li>
									<a href="<?=base_url()?>invoice"><span class="stats_icon current_work_sl"></span><span class="label">Analytics</span></a>
									</li>
									<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="stats_icon user_sl"></span><span class="label"> Users</span></a></li>
									<li><a href="#"><span class="stats_icon administrative_docs_sl"></span><span class="label">Content</span></a></li>
									<li><a href="#"><span class="stats_icon finished_work_sl"></span><span class="label">Task List</span></a></li>
									<li><a href="#"><span class="stats_icon config_sl"></span><span class="label">Settings</span></a></li>
									<li><a href="#"><span class="stats_icon archives_sl"></span><span class="label">Archive</span></a></li>
									<li><a href="#"><span class="stats_icon folder_sl"></span><span class="label">Media</span></a></li>
									<li><a href="#"><span class="stats_icon lightbulb_sl"></span><span class="label">Support</span></a></li>
								</ul>
							
								
								
								
								
								
								
								</div>
							</div>
							</section>

						</div>
						<div id="tab-hds" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red">
								<div class="block-content">
								<h1>Historical Data</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										
									</ul>
								</div>
								<ul class="switch_bar black_rev">
									<li>
									<a href="<?=base_url()?>invoice"><span class="stats_icon current_work_sl"></span><span class="label">Analytics</span></a>
									</li>
									<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="stats_icon user_sl"></span><span class="label"> Users</span></a></li>
									<li><a href="#"><span class="stats_icon administrative_docs_sl"></span><span class="label">Content</span></a></li>
									<li><a href="#"><span class="stats_icon finished_work_sl"></span><span class="label">Task List</span></a></li>
									<li><a href="#"><span class="stats_icon config_sl"></span><span class="label">Settings</span></a></li>
									<li><a href="#"><span class="stats_icon archives_sl"></span><span class="label">Archive</span></a></li>
									<li><a href="#"><span class="stats_icon folder_sl"></span><span class="label">Media</span></a></li>
									<li><a href="#"><span class="stats_icon lightbulb_sl"></span><span class="label">Support</span></a></li>
								</ul>
							
								
								
								
								
								
								
								</div>
							</div>
							</section>

						</div>
						<div id="tab-dla" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red">
								<div class="block-content">
								<h1>Data Load Analitycs</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										
									</ul>
								</div>
								<section class="grid_14">
									<div class="block-border">
										<div class="block-content">
											<h1>Monthly Graphic</h1>
											<canvas id="buyers" width="500" height="145"></canvas>
										</div>
									</div>
								</section>
								<section class="grid_14">
									<div class="block-border">
										<div class="block-content">
											<h1>Monthly Graphic</h1>
											<canvas id="buyers" width="500" height="145"></canvas>
										</div>
									</div>
								</section>	
								
								
								
								
								
								</div>
							</div>
							</section>

						</div>
						
					</div>
								
				<!-- THIS PLACE FOR MINI CALENDAR-->
				
				
				
				</div>
				
				</form>
			</div>
		</section>
		
	
		<div class="clear"></div>
		<script>
            // line chart data
            var buyerData = {
                labels : ["January","February","March","April","May","June"],
                datasets : [
                {
                    fillColor : "rgba(172,194,132,0.4)",
                    strokeColor : "#ACC26D",
                    pointColor : "#fff",
                    pointStrokeColor : "#9DB86D",
                    data : [203,156,99,251,305,247]
                }
            ]
            }
            // get line chart canvas
            var buyers = document.getElementById('buyers').getContext('2d');
            // draw line chart
            new Chart(buyers).Line(buyerData);
            // pie chart data
            var pieData = [
                {
                    value: 20,
                    color:"#878BB6"
                },
                {
                    value : 40,
                    color : "#4ACAB4"
                },
                {
                    value : 10,
                    color : "#FF8153"
                },
                {
                    value : 30,
                    color : "#FFEA88"
                }
            ];
            // pie chart options
            var pieOptions = {
                 segmentShowStroke : false,
                 animateScale : true
            }
            // get pie chart canvas
            var countries= document.getElementById("countries").getContext("2d");
            // draw pie chart
            new Chart(countries).Pie(pieData, pieOptions);
            // bar chart data
            var barData = {
                labels : ["January","February","March","April","May","June"],
                datasets : [
                    {
                        fillColor : "#48A497",
                        strokeColor : "#48A4D1",
                        data : [456,479,324,569,702,600]
                    },
                    {
                        fillColor : "rgba(73,188,170,0.4)",
                        strokeColor : "rgba(72,174,209,0.4)",
                        data : [364,504,605,400,345,320]
                    }
                ]
            }
            // get bar chart canvas
            var income = document.getElementById("income").getContext("2d");
            // draw bar chart
            new Chart(income).Bar(barData);
        </script>
    
	</article>
@endsection