@extends('layouts.master')

@section('content')
	<!-- Content -->
	<article class="container_12">
	
	<section class="grid_12">
			<div class="block-border"><form class="block-content form" id="complex_form" method="post" action="#">
				<h1>Main Dashboard & Summary Information</h1>
				
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
						
						<h2>Portal Monitoring System & SLA Report</h2>
						
						<ul class="side-tabs js-tabs same-height">
							<li><a href="#tab-gi" title="General Summary Information & Properties">General Summary Information & Properties</a></li>
							<li><a href="#tab-op" title="Operational Procedures & User Guide">Operational Procedures & Manual Instruction</a></li>
							<li><a href="#tab-faq" title="Frequently Asked Question (FAQ)">Frequently Asked Question (FAQ)</a></li>
							
							<li><a href="#tab-hds" title="Operational Procedures & User Guide">Historycal Data & System Log Activity</a></li>
							<li><a href="#tab-dla" title="Operational Procedures & User Guide">Data Load Analytics</a></li>
							<li><a href="#tab-sl" title="System Licences">System Licences</a></li>
							
						</ul>
					
					<div class="block-border grid_10">
					<div class="block-content no-title dark-bg"><p align="center"><b>Calendar</b></p>
					<div class="mini-calendar">
						<div class="calendar-controls">
							<a href="javascript:void(0)" class="calendar-prev" title="Previous month"><img src="<?=base_url()?>constellation/assets/images/cal-arrow-left.png" width="16" height="16"></a>
							<a href="javascript:void(0)" class="calendar-next" title="Next month"><img src="<?=base_url()?>constellation/assets/images/cal-arrow-right.png" width="16" height="16"></a>
							June 2019
						</div>
						
						<table cellspacing="0">
							<thead>
								<tr>
									<th scope="col" class="week-end">S</th>
									<th scope="col">M</th>
									<th scope="col">T</th>
									<th scope="col">W</th>
									<th scope="col">T</th>
									<th scope="col">F</th>
									<th scope="col" class="week-end">S</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="week-end other-month">28</td>
									<td class="other-month">29</td>
									<td class="other-month">30</td>
									<td class="other-month">31</td>
									<td><a href="javascript:void(0)">1</a></td>
									<td><a href="javascript:void(0)">2</a></td>
									<td class="week-end"><a href="javascript:void(0)">3</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">4</a></td>
									<td><a href="javascript:void(0)">5</a></td>
									<td><a href="javascript:void(0)">6</a></td>
									<td><a href="javascript:void(0)">7</a></td>
									<td><a href="javascript:void(0)">8</a></td>
									<td class="today"><a href="javascript:void(0)">9</a></td>
									<td class="week-end"><a href="javascript:void(0)">10</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">11</a></td>
									<td><a href="javascript:void(0)">12</a></td>
									<td><a href="javascript:void(0)">13</a></td>
									<td><a href="javascript:void(0)">14</a></td>
									<td><a href="javascript:void(0)">15</a></td>
									<td><a href="javascript:void(0)">16</a></td>
									<td class="week-end"><a href="javascript:void(0)">17</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">18</a></td>
									<td><a href="javascript:void(0)">19</a></td>
									<td><a href="javascript:void(0)">20</a></td>
									<td><a href="javascript:void(0)">21</a></td>
									<td><a href="javascript:void(0)">22</a></td>
									<td><a href="javascript:void(0)">23</a></td>
									<td class="week-end"><a href="javascript:void(0)">24</a></td>
								</tr>
								<tr>
									<td class="week-end"><a href="javascript:void(0)">25</a></td>
									<td class="unavailable">26</td>
									<td class="unavailable">27</td>
									<td class="unavailable">28</td>
									<td><a href="javascript:void(0)">29</a></td>
									<td><a href="javascript:void(0)">30</a></td>
									<td class="week-end other-month">1</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					</div>
					</div>
					
					
					
				
				
					
					</div>
					<div class="col200pxL-right">
						
						<div id="tab-gi" class="tabs-content">
							
							<ul class="tabs js-tabs same-height">
								<li class="current"><a href="#tab-gip" title="Locales">General Information & Properties</a></li>
								<li class="current"><a href="#tab-hsp" title="Locales">Summary Planning</a></li>
								<li><a href="#tab-hsflm" title="First Line Maintenance (FLM)">First Line Maintenance (FLM)</a></li>
								<li><a href="#tab-hsslm" title="Second Line Maintenance (SLM)">Second Line Maintenance (SLM)</a></li>
								<!--<li><a href="#tab-hslog" title="Logistics">Logistics</a></li>-->
							</ul>
							
							<div class="tabs-content">
								
								<div id="tab-gip" style="height: 470px; display: block;">
								
									<center>
					<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
					</center>
					<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>PORTAL MONITORING SYSTEM & SLA REPORT  
					</p>
								<section class="grid_1"></section>
								<section class="grid_10">
									<div class="block-border ">
										<div class="dark-grey-gradient">

											<ul class="grid dark-grey-gradient">
												<li>
													<div class="grid-picto">
														<br>
														<small>Total Pegawai</small>
														<p class="grid-name" style="margin-top: 2px"><b>28</b> Orang</p>
														<p class="grid-details">
													</div>
												</li>
												<li>
													<div class="grid-picto">
														<br>
														<small>Total User</small>
														<p class="grid-name" style="margin-top: 2px"><b>28</b> Orang</p>
														<p class="grid-details">
													</div>
												</li>
												<li>
													<div class="grid-picto">
														<br>
														<small>Total Teknisi FLM</small>
														<p class="grid-name" style="margin-top: 2px"><b>28</b> Orang</p>
														<p class="grid-details">
													</div>
												</li>
												<li>
													<div class="grid-picto">
														<br>
														<small>Total Teknisi SLM</small>
														<p class="grid-name" style="margin-top: 2px"><b>28</b> Orang</p>
														<p class="grid-details">
													</div>
												</li>
											</ul>

											<!--<ul class="grid dark-grey-gradient">
												<li>
													<div class="grid-picto">
														<br>
														<small>Total Ticket</small>
														<p class="grid-name" style="margin-top: 2px"><b>28</b> Orang</p>
														<p class="grid-details">
													</div>
												</li>
												<li>
													<div class="grid-picto">
														<br>
														<small>Ticked Solved</small>
														<p class="grid-name" style="margin-top: 2px"><b>28</b> Orang</p>
														<p class="grid-details">
													</div>
												</li>
												<li>
													<div class="grid-picto">
														<br>
														<small>Ticked On Process</small>
														<p class="grid-name" style="margin-top: 2px"><b>28</b> Orang</p>
														<p class="grid-details">
													</div>
												</li>
												<li>
													<div class="grid-picto">
														<br>
														<small>Ticket Waiting Approval FLM</small>
														<p class="grid-name" style="margin-top: 2px"><b>28</b> Orang</p>
														<p class="grid-details">
													</div>
												</li>
												<li>
													<div class="grid-picto">
														<br>
														<small>Ticket Waiting Approval SLM</small>
														<p class="grid-name" style="margin-top: 2px"><b>28</b> Orang</p>
														<p class="grid-details">
													</div>
												</li>
											</ul>-->
										</div>
									</div>
								</section>
								
									
									
								</div>
								
								<div id="tab-hsp" style="height: 470px; display: block;">
								
										<section class="grid_4">
											<div class="block-border"><div class="block-content">
												<h1>List calendar</h1>
												
												<ul class="message no-margin">
													<li>12 events found</li>
												</ul>
												
												<div class="no-margin">
													<table cellspacing="0" class="list-calendar">
														<tbody>
															<tr class="empty">
																<th scope="row">01</th>
																<td>
																	<ul class="mini-menu">
																		<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
																	</ul>
																	No events
																</td>
															</tr>
															<tr>
																<th scope="row">02</th>
																<td>
																	<ul class="mini-menu">
																		<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
																	</ul>
																	<ul class="events-dots-list">
																		<li><a href="javascript:void(0)"><span></span> Lena's birthday</a></li>
																		<li><a href="javascript:void(0)"><span></span> Replace server hard drive</a></li>
																		<li><a href="javascript:void(0)"><span></span> Max's birthday</a></li>
																	</ul>
																</td>
															</tr>
															<tr class="empty">
																<th scope="row">03</th>
																<td>
																	<ul class="mini-menu">
																		<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
																	</ul>
																	No events
																</td>
															</tr>
															<tr class="empty">
																<th scope="row">04</th>
																<td>
																	<ul class="mini-menu">
																		<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
																	</ul>
																	No events
																</td>
															</tr>
															<tr>
																<th scope="row">05</th>
																<td>
																	<ul class="mini-menu">
																		<li><a href="javascript:void(0)" title="Add event"><img src="images/icons/add.png" width="16" height="16"> Add event</a></li>
																	</ul>
																	<ul class="events">
																		<li><a href="javascript:void(0)"><b>9:00</b> Meeting</a></li>
																		<li><a href="javascript:void(0)"><b>11:00</b> Meeting with D.H.</a></li>
																		<li><a href="javascript:void(0)"><b>14:00</b> Meeting</a></li>
																	</ul>
																	<div class="more-events">
																		2 more events
																		<ul>
																			<li><a href="javascript:void(0)"><b>17:00</b> Soccer</a></li>
																			<li><a href="javascript:void(0)"><b>21:00</b> Diner with Jane</a></li>
																		</ul>
																	</div>
																</td>
															</tr>
															</tbody>
													</table>
												</div>
											
											</div></div>
										</section>
								<section class="grid_8">
			<div class="block-border"><div class="block-content">
				<h1>Planning list</h1>
				
				<div class="task with-legend">
					<div class="legend"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status.png" width="16" height="16"> Standard task</div>
					
					<div class="task-description">
						<ul class="floating-tags">
							<li class="tag-time">5 days remain.</li>
							<li class="tag-tags">Server, disk</li>
							<li class="tag-user">You, Marc</li>
						</ul>
						
						<h3>Task name</h3>
						Small task description Lorem ipsum
					</div>
					
					<ul class="task-dialog">
						<li class="auto-hide">
							<form name="task-1-comment" method="post" action="#" class="form input-with-button">
								<input type="text" name="comment" id="task-1-comment" value="" title="Enter comment...">
								<button type="submit">Add</button>
							</form>
						</li>
					</ul>
				</div>
				
				<div class="task with-legend">
					<div class="legend"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/status-away.png" width="16" height="16"> Soon overdue task</div>
					
					<div class="task-description">
						<ul class="floating-tags">
							<li class="tag-time">Today</li>
							<li class="tag-tags">Website</li>
							<li class="tag-user">You</li>
						</ul>
						
						<h3>Task name</h3>
						Small task description Lorem ipsum
					</div>
					
					<ul class="task-dialog">
						<li class="auto-hide">
							<form name="task-1-comment" method="post" action="#" class="form input-with-button">
								<input type="text" name="comment" id="task-1-comment" value="" title="Enter comment...">
								<button type="submit">Add</button>
							</form>
						</li>
					</ul>
				</div>
				
				
				
				<ul class="message no-margin">
					<li>2 tasks found</li>
				</ul>
			</div></div>
		</section>
					
									
									
									
								</div>
								
								<div id="tab-hsflm">
									<section class="grid_12">
									<div class="block-border "><div class="dark-grey-gradient">
									
									<ul class="grid dark-grey-gradient">
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									
									
									
									
									</ul>	
									</div></div>
								</section>
								</div>
								<div id="tab-hsslm">
									
									<section class="grid_12">
									<div class="block-border "><div class="dark-grey-gradient">
									
									<ul class="grid dark-grey-gradient">
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									<li>
										<div class="grid-picto user">
											<small>Administrator</small>
											<p class="grid-name">John Doe</p>
											<p class="grid-details">Age: <b>28</b><br>
											Gender: <b>male</b><br>
											Country: <b>USA</b></p>
										</div>
										<ul class="grid-actions">
											<li><a href="javascript:void(0)" title="Edit" class="with-tip"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/pencil.png" width="16" height="16"></a></li>
										</ul>
									</li>	
									
									
									
									
									</ul>	
									</div></div>
								</section>
								</div>
								<!--<div id="tab-hslog">
									Logistics
								</div>-->
							
							</div>
						</div>
						
						
						<div id="tab-faq" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red"><div class="block-content">
								<h1>Portal Monitoring System</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										<li><a href="javascript:void(0)"><strong>Operational Procedure & Manual Instruction</strong></a></li>
										<li class="sep"></li>
									</ul>
								</div>
								
								<div class="infos">
									<small>Operational Procedure And Manual Instructions</small>
									<h2 class="bigger">PORTAL MONITORING SYSTEM</h2>
								</div>
								
								<p class="grey"></p>
								
								<p><strong></strong></p>
								
								<dl class="accordion">
									<dt><span class="number">1</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">2</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">3</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">4</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">5</span>123</dt>
									<dd>
										
									</dd>
								</dl>
							
							</div></div>
						</section>

						</div>
						
						<div id="tab-op" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red"><div class="block-content">
								<h1>Portal Monitoring System</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										<li><a href="javascript:void(0)"><strong>Operational Procedure & Manual Instruction</strong></a></li>
										<li class="sep"></li>
									</ul>
								</div>
								
								<div class="infos">
									<small>Operational Procedure And Manual Instructions</small>
									<h2 class="bigger">PORTAL MONITORING SYSTEM</h2>
								</div>
								
								
								<p><strong></strong></p>
								
								<dl class="accordion">
									<dt><span class="number">1</span>123</dt>
									<dd>
									
									</dd>
									
									<dt><span class="number">2</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">3</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">4</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">5</span>123</dt>
									<dd>
										
									</dd>
								</dl>
							
							</div></div>
						</section>

						</div>
						<div id="tab-sl" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red"><div class="block-content">
								<h1>Portal Monitoring System</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										<li><a href="javascript:void(0)"><strong>Operational Procedure & Manual Instruction</strong></a></li>
										<li class="sep"></li>
									</ul>
								</div>
								
								<div class="infos">
									<small>Operational Procedure And Manual Instructions</small>
									<h2 class="bigger">PORTAL MONITORING SYSTEM</h2>
								</div>
								
								
								
								<p><strong></strong></p>
								
								<dl class="accordion">
									<dt><span class="number">1</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">2</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">3</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">4</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">5</span>123</dt>
									<dd>
										
									</dd>
								</dl>
							
							</div></div>
						</section>

						</div>
						<div id="tab-hds" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red"><div class="block-content">
								<h1>Portal Monitoring System</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										<li><a href="javascript:void(0)"><strong>Operational Procedure & Manual Instruction</strong></a></li>
										<li class="sep"></li>
									</ul>
								</div>
								
								<div class="infos">
									<small>Operational Procedure And Manual Instructions</small>
									<h2 class="bigger">PORTAL MONITORING SYSTEM</h2>
								</div>
								
								
								<p><strong></strong></p>
								
								<dl class="accordion">
									<dt><span class="number">1</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">2</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">3</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">4</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">5</span>123</dt>
									<dd>
										
									</dd>
								</dl>
							
							</div></div>
						</section>

						</div>
						<div id="tab-dla" class="tabs-content" style="height:530px">
							<section class="grid_12 red">
							<div class="block-border-red"><div class="block-content">
								<h1>Portal Monitoring System</h1>
								<div class="block-controls">
									<ul class="controls-buttons">
										<li class="sep"></li>
										<li><a href="javascript:void(0)"><strong>Operational Procedure & Manual Instruction</strong></a></li>
										<li class="sep"></li>
									</ul>
								</div>
								
								<div class="infos">
									<small>Operational Procedure And Manual Instructions</small>
									<h2 class="bigger">PORTAL MONITORING SYSTEM</h2>
								</div>
								
							
								
								<p><strong></strong></p>
								
								<dl class="accordion">
									<dt><span class="number">1</span>123</dt>
									<dd>
									
									</dd>
									
									<dt><span class="number">2</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">3</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">4</span>123</dt>
									<dd>
										
									</dd>
									
									<dt><span class="number">5</span>123</dt>
									<dd>
										
									</dd>
								</dl>
							
							</div></div>
						</section>

						</div>
						
					</div>
					
				<!-- THIS PLACE FOR MINI CALENDAR-->
				
				
				
				</div>
				
			</form></div>
		</section>
		
	
		<div class="clear"></div>
		
	</article>
@endsection