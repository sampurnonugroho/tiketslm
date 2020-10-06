@extends('layouts.master3')

@section('content')
	<?php 
		// print_r($data_flm);
	?>


			<section class="grid_12">
				<div class="widget_wrap tabby">
					<div class="widget_top">
						<span class="h_icon list"></span>
						<h6>DISPLAY Main Dashboard & Summary Information</h6>
					</div>
					<div class="widget_content">
						
							<div class="stat_block black_rev">
							
								
								
								<div class="grid_2">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>WORK UNIT</h6>
										</div>
										
										<div class="widget_content">
										<div class="top_bar blue_lin">
										<ul>
											<li><a href="#"><span class="stats_icon current_work_sl"></span><span class="label"><?=$jml_karyawan?> Employee</span><span class="btn_intro" align="justify">Total Employee Active are using the system / application </span></a>
											</li>
											<li><a href="#"><span class="stats_icon user_sl"></span><span class="label"><?=$jml_user?> User</span><span class="btn_intro">Total User Who have Privillage / Credential Access</span></a></li>
											<li><a href="#"><span class="stats_icon config_sl"></span><span class="label"><?=$jml_teknisi?> Technician</span><span class="btn_intro">Total Technician Are Incharge for support maintenance </span></a></li>
											<li><a href="#"><span class="stats_icon contact_sl"></span><span class="label"><?=$jml_ticket?> Tickets</span><span class="btn_intro">Total Tickets Active are Opened on system / application </span></a></li>
											<li><a href="#"><span class="stats_icon finished_work_sl"></span><span class="label"><?=$jml_client?> Clients</span><span class="btn_intro">Total Clients Active are using the system / application </span></a></li>
											<li><a href="#"><span class="stats_icon archives_sl"></span><span class="label"><?=$jml_runsheet?> Runsheet</span><span class="btn_intro">Total Open Runsheet are prepared on system / application </span></a></li>
											<li><a href="#"><span class="stats_icon archives_sl"></span><span class="label"><?=$jml_runsheet?> Runsheet</span><span class="btn_intro">Total Runsheet are executed using the system / application </span></a></li>
											
										</ul>
									</div>
								
										</div>
									</div>
								</div>
								
								
								<div class="grid_10">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>MAPS GLOBAL TRACKING</h6>
										</div>
										
										<div class="widget_content">
										<div id="chart2" class="chart_block" style="height:175px; width:100%;"></div>
										</div>
									</div>
								</div>
								
								<div class="grid_5">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Maps CIT & CR</h6>
										</div>
										
										<div class="widget_content">
										<div id="chart2" class="chart_block" style="height:180px; width:100%;"></div>
										</div>
									</div>
								</div>
								<div class="grid_5">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Maps FLM & SLM</h6>
										</div>
										
										<div class="widget_content">
										<div id="chart2" class="chart_block" style="height:180px; width:100%;"></div>
										</div>
									</div>
								</div>
								
								<div class="grid_10">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>actual daily monitoring</h6>
										</div>
										
										<div class="widget_content">
										<div id="chart2" class="chart_block" style="height:230px; width:100%;"></div>
										</div>
									</div>
								</div>
								
								
								<span class="clear"></span>
									
							</div>
						
						
						
						

						
					</div>
						
				</div>
				<br>
	
			</section>
		
	
		<div class="clear"></div>
	
	
	</article>
@endsection