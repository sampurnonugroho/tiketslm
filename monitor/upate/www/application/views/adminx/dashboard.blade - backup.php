@extends('layouts.master')

@section('content')
	<?php 
		// print_r($data_flm);
	?>


			<section class="grid_14">
				<div class="widget_wrap tabby">
					<div class="widget_top">
						<span class="h_icon list"></span>
						<h6>Main Dashboard & Summary Information</h6>
						<div id="widget_tab">
							<ul>
								<li><a href="#tab1" class="active_tab">Monitoring Statistics</a></li>
								<li><a href="#tab2">Recent Run Sheet</a></li>
								<li><a href="#tab3">Recent Tickets</a></li>
							</ul>
						</div>
					</div>
					<div class="widget_content">
						<div id="tab1">
							<div class="stat_block black_rev">
							
								<ul class="switch_bar black_rev">
									<li>
									<a href="<?=base_url()?>invoice"><span class="stats_icon current_work_sl"></span><span class="label">Analytics</span></a>
									</li>
									<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="stats_icon user_sl"></span><span class="label"> Users</span></a></li>
									<li><a href="#"><span class="stats_icon administrative_docs_sl"></span><span class="label">Content</span></a></li>
									<li><a href="#"><span class="stats_icon finished_work_sl"></span><span class="label">Task List</span></a></li>
									<li><a href="#"><span class="stats_icon config_sl"></span><span class="label">Settings</span></a></li>
									<li><a href="#"><span class="stats_icon archives_sl"></span><span class="label">Archive</span></a></li>
									<li><a href="#"><span class="stats_icon address_sl"></span><span class="label">Contact</span></a></li>
									<li><a href="#"><span class="stats_icon folder_sl"></span><span class="label">Media</span></a></li>
									<li><a href="#"><span class="stats_icon category_sl"></span><span class="label">Explorer</span></a></li>
									<li><a href="#"><span class="stats_icon calendar_sl"></span><span class="label">Events</span></a></li>
									<li><a href="#"><span class="stats_icon lightbulb_sl"></span><span class="label">Support</span></a></li>
									<li><a href="#"><span class="stats_icon bank_sl"></span><span class="label">Order List</span></a></li>
								</ul>
							

							<!--<center><img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
							</center>
							<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>PORTAL MONITORING SYSTEM & SLA REPORT</p>-->
								<div class="grid_3">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Monitoring</h6>
										</div>
										
										<div class="widget_content">
										<div id="chart5" class="chart_block" style="height:180px; width:100%;"></div>
										</div>
									</div>
								</div>
								<div class="grid_9">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Actual Daily Monitoring</h6>
										</div>
										
										<div class="widget_content">
										<div id="chart2" class="chart_block" style="height:180px; width:100%;"></div>
										</div>
									</div>
								</div>
								
								<div class="grid_12">
									<div class="top_bar orange_lin">
										<ul>
											<li><a href="#"><span class="stats_icon current_work_sl"></span><span class="label"><?=$jml_karyawan?> Employee</span><span class="btn_intro" align="justify">Total Employee Active are using the system / application </span></a>
											</li>
											<li><a href="#"><span class="stats_icon user_sl"></span><span class="label"><?=$jml_user?> User</span><span class="btn_intro">Total User Who have Privillage / Credential Access</span></a></li>
											<li><a href="#"><span class="stats_icon config_sl"></span><span class="label"><?=$jml_teknisi?> Technician</span><span class="btn_intro">Total Technician Are Incharge for support maintenance </span></a></li>
											<li><a href="#"><span class="stats_icon contact_sl"></span><span class="label">Tickets</span><span class="btn_intro">Total Employee Active are using the system / application </span></a></li>
											<li><a href="#"><span class="stats_icon finished_work_sl"></span><span class="label">Clients</span><span class="btn_intro">Total Employee Active are using the system / application </span></a></li>
											<li><a href="#"><span class="stats_icon archives_sl"></span><span class="label">Runsheet</span><span class="btn_intro">Total Employee Active are using the system / application </span></a></li>
										</ul>
									</div>
								</div>
								
								<span class="clear"></span>
									
							</div>
						</div>

						<div id="tab2">
							<div class="stat_block black_rev">
							
							<div class="grid_14">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon documents"></span>
											<h6>Content</h6>
										</div>
										<div class="widget_content">
											<table class="display data_tbl">
											<thead>
											<tr>
												<th>
													 Id
												</th>
												<th>
													 Details
												</th>
												<th>
													 Submit Date
												</th>
												<th>
													 Submited By
												</th>
												<th>
													 Status
												</th>
												<th>
													 Publish Date
												</th>
												<th>
													 Action
												</th>
											</tr>
											</thead>
											<tbody>
											<tr>
												<td>
													<a href="#">01</a>
												</td>
												<td>
													<a href="#">Pellentesque ut massa ut ligula ... </a>
												</td>
												<td class="sdate center">
													 1st FEB 2012
												</td>
												<td class="center">
													 Jaman
												</td>
												<td class="center">
													<span class="badge_style b_done">Publish</span>
												</td>
												<td class="center sdate">
													 3rd FEB 2012
												</td>
												<td class="center">
													<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Publish</a></span>
												</td>
											</tr>
											<tr>
												<td>
													<a href="#">02</a>
												</td>
												<td>
													<a href="#">Nulla non ante dui, sit amet ... </a>
												</td>
												<td class="sdate center">
													 1st FEB 2012
												</td>
												<td class="center">
													 Jhon
												</td>
												<td class="center">
													<span class="badge_style b_done">Publish</span>
												</td>
												<td class="center sdate">
													 3rd FEB 2012
												</td>
												<td class="center">
													<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Publish</a></span>
												</td>
											</tr>
											<tr>
												<td>
													<a href="#">03</a>
												</td>
												<td>
													<a href="#">Aliquam eu pellentesque... </a>
												</td>
												<td class="sdate center">
													 1st FEB 2012
												</td>
												<td class="center">
													 Mike
												</td>
												<td class="center">
													<span class="badge_style b_done">Publish</span>
												</td>
												<td class="center sdate">
													 3rd FEB 2012
												</td>
												<td class="center">
													<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Publish</a></span>
												</td>
											</tr>
											<tr>
												<td>
													<a href="#">04</a>
												</td>
												<td>
													<a href="#">Maecenas egestas alique... </a>
												</td>
												<td class="sdate center">
													 1st FEB 2012
												</td>
												<td class="center">
													 Sam
												</td>
												<td class="center">
													<span class="badge_style b_pending">Pending</span>
												</td>
												<td class="center sdate">
													 -
												</td>
												<td class="center">
													<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Publish</a></span>
												</td>
											</tr>
											</tbody>
											</table>
										</div>
									</div>
								</div>
								
								
									
							</div>
								
								
						</div>
							
						<div id="tab3">
							<div class="stat_block black_rev">
							<div class="grid_6">
							<div class="widget_wrap">
								<div class="widget_top">
									<span class="h_icon help"></span>
									<h6>FLM List Ticket</h6>
								</div>
								<div class="widget_content">
									<div class="ticket_list">
										<div class="ticket_block">
											<div class="ticket_info">
												<div class="widget_thumb">
													<img src="images/user-thumb1.png" width="40" height="40" alt="User">
												</div>
												<span class="user-info"> User: kjaman on IP: 192.118.1.1 <b>ID #12467RS</b></span>
												<p>
													<a href="#">Suspendisse convallis laoreet lectus in aliquam. Vivamus quis elit nisl, ut posuere leo.</a>
												</p>
											</div>
											<ul class="action_list">
												<li><a class="p_reply" href="#">Reply</a></li>
												<li><a class="p_forward" href="#">Forward</a></li>
												<li class="right"><a class="p_approve" href="#">Resolved</a></li>
											</ul>
										</div>
										<div class="ticket_block">
											<div class="ticket_info">
												<div class="widget_thumb">
													<img src="images/user-thumb1.png" width="40" height="40" alt="User">
												</div>
												<span class="user-info"> User: kjaman on IP: 192.118.1.1 <b>ID #12467RS</b></span>
												<p>
													<a href="#">Suspendisse convallis laoreet lectus in aliquam. Vivamus quis elit nisl, ut posuere leo.</a>
												</p>
											</div>
											<ul class="action_list">
												<li><a class="p_reply" href="#">Reply</a></li>
												<li><a class="p_forward" href="#">Forward</a></li>
												<li class="right"><a class="p_approve" href="#">Resolved</a></li>
											</ul>
										</div>
										<div class="ticket_block">
											<div class="ticket_info">
												<div class="widget_thumb">
													<img src="images/user-thumb1.png" width="40" height="40" alt="User">
												</div>
												<span class="user-info"> User: kjaman on IP: 192.118.1.1 <b>ID #12467RS</b></span>
												<p>
													<a href="#">Suspendisse convallis laoreet lectus in aliquam. Vivamus quis elit nisl, ut posuere leo.</a>
												</p>
											</div>
											<ul class="action_list">
												<li><a class="p_reply" href="#">Reply</a></li>
												<li><a class="p_forward" href="#">Forward</a></li>
												<li class="right"><a class="p_approve" href="#">Resolved</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							</div>
							<div class="grid_6">
							<div class="widget_wrap">
								<div class="widget_top">
									<span class="h_icon help"></span>
									<h6>SLM List  Ticket</h6>
								</div>
								<div class="widget_content">
									<div class="ticket_list">
										<div class="ticket_block">
											<div class="ticket_info">
												<div class="widget_thumb">
													<img src="images/user-thumb1.png" width="40" height="40" alt="User">
												</div>
												<span class="user-info"> User: kjaman on IP: 192.118.1.1 <b>ID #12467RS</b></span>
												<p>
													<a href="#">Suspendisse convallis laoreet lectus in aliquam. Vivamus quis elit nisl, ut posuere leo.</a>
												</p>
											</div>
											<ul class="action_list">
												<li><a class="p_reply" href="#">Reply</a></li>
												<li><a class="p_forward" href="#">Forward</a></li>
												<li class="right"><a class="p_approve" href="#">Resolved</a></li>
											</ul>
										</div>
										<div class="ticket_block">
											<div class="ticket_info">
												<div class="widget_thumb">
													<img src="images/user-thumb1.png" width="40" height="40" alt="User">
												</div>
												<span class="user-info"> User: kjaman on IP: 192.118.1.1 <b>ID #12467RS</b></span>
												<p>
													<a href="#">Suspendisse convallis laoreet lectus in aliquam. Vivamus quis elit nisl, ut posuere leo.</a>
												</p>
											</div>
											<ul class="action_list">
												<li><a class="p_reply" href="#">Reply</a></li>
												<li><a class="p_forward" href="#">Forward</a></li>
												<li class="right"><a class="p_approve" href="#">Resolved</a></li>
											</ul>
										</div>
										<div class="ticket_block">
											<div class="ticket_info">
												<div class="widget_thumb">
													<img src="images/user-thumb1.png" width="40" height="40" alt="User">
												</div>
												<span class="user-info"> User: kjaman on IP: 192.118.1.1 <b>ID #12467RS</b></span>
												<p>
													<a href="#">Suspendisse convallis laoreet lectus in aliquam. Vivamus quis elit nisl, ut posuere leo.</a>
												</p>
											</div>
											<ul class="action_list">
												<li><a class="p_reply" href="#">Reply</a></li>
												<li><a class="p_forward" href="#">Forward</a></li>
												<li class="right"><a class="p_approve" href="#">Resolved</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							</div>
						
							<span class="clear"></span>
								
									
							</div>
								
								
						</div>
						
						
					</div>
						
				</div>
			<br>
	
</section>
		
	
		<div class="clear"></div>
	
	
	</article>
@endsection