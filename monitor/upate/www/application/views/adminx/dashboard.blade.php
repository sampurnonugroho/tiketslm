@extends('layouts.master')

@section('content')
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAb7d-G5Ea9j3X_haj37bSPJkSN7PpAp7I&libraries=places"></script>
	<?php 
		// print_r($data_flm);
	?>
		<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery-1.6.min.js"></script>
		
		<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
		<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
		<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

		<script src="<?=base_url()?>depend/highchart/code/highcharts.js"></script>
		<script src="<?=base_url()?>depend/highchart/code/modules/exporting.js"></script>
		<script src="<?=base_url()?>depend/highchart/code/modules/export-data.js"></script>
			<style>
				.stat_block table tr td {
					padding: 8px 10px;
					font-size: 11px;
					border: #ccc 1px solid;
					background: #fff;
				}
			</style>
			<section class="grid_14">
				<div class="widget_wrap tabby">
					<div class="widget_top">
						<span class="h_icon list"></span>
						<h6>Main Dashboard & Summary Information</h6>
						<div id="widget_tab">
							<ul>
								<li><a href="#tab1" class="active_tab">Monitoring Statistics</a></li>
								<li><a href="#tab4">Maps & Tracking</a></li>
								<li><a href="#tab2">Recent Runsheets</a></li>
								<li><a href="#tab3">Trouble Tickets</a></li>
								
							</ul>
						</div>
					</div>
					<div class="widget_content">
						<div id="tab1">
							<div class="stat_block black_rev">
							
								<!--<ul class="switch_bar black_rev">
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
								</ul>-->
							

							<!--<center><img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
							</center>
							<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>PORTAL MONITORING SYSTEM & SLA REPORT</p>-->
								
								<div class="grid_6">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Daily Monitoring (ATM, CDM, CRM)</h6>
										</div>
										
										<div class="widget_content">
										<div id="chart_1" class="chart_block" style="height:360px; width:100%;"></div>
										</div>
									</div>
								</div>
								<div class="grid_6">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Daily Monitoring (CIT)</h6>
										</div>
										
										<div class="widget_content">
										<div id="chart_4" class="chart_block" style="height:360px; width:100%;"></div>
										</div>
									</div>
								</div>
								
								<div class="grid_6">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Job Runsheets</h6>
										</div>
										
										<div class="widget_content">
											<div id="chart_2" class="chart_block" style="height:220px; width:100%;"></div>
										</div>
									</div>
								</div>
								<div class="grid_6">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Trouble Tickets</h6>
										</div>
										
										<div class="widget_content">
											<div id="chart_3" class="chart_block" style="height:220px; width:100%;"></div>
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
											<li><a href="#"><span class="stats_icon contact_sl"></span><span class="label"><?=$jml_ticket?> Tickets</span><span class="btn_intro">Total Tickets Active are Opened on system / application </span></a></li>
											<li><a href="#"><span class="stats_icon finished_work_sl"></span><span class="label"><?=$jml_client?> Clients</span><span class="btn_intro">Total Clients Active are using the system / application </span></a></li>
											<li><a href="#"><span class="stats_icon archives_sl"></span><span class="label"><?=$jml_runsheet?> Runsheet</span><span class="btn_intro">Total Runsheet Active are using the system / application </span></a></li>
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
											<h6>Recent Runsheets</h6>
										</div>
										<div class="widget_content">
											<table class="display data_tbl">
												<thead>
													<tr>
														<th>
															Ticket
														</th>
														<th>
															Bank
														</th>
														<th>
															Act
														</th>
														<th>
															Brand
														</th>
														<th>
															Problem Type
														</th>
														<th>
															Status
														</th>
													</tr>
												</thead>
												<tbody>
													<?php 
														// print_r($statusflm);
													
														$no = 0;
														foreach($statusflm as $row): 
														$no++;
														
														$branch = $db->query("SELECT * FROM master_branch WHERE id='$row->cabang'")->row();
														$pro_type = json_decode($row->problem_type);	
														$ary = array();
														foreach($pro_type as $arr) {
															$ary[] = $db->query('SELECT nama_kategori FROM kategori WHERE id_kategori="'.$arr.'"')->row()->nama_kategori;
														}
													?>
													<tr>
														<td><?php echo $row->id_ticket;?></td>
														<td><?php echo $row->bank;?></td>
														<td><?php echo $row->type;?></td>
														<td><?php echo $row->type_mesin;?></td>
														<td><?php echo implode(", ",$ary);?></td>
														<td>
															<?php 
																if($row->accept_time==null && $row->data_solve=="") {
																	echo '<span class="badge_style b_pending">Waiting PIC</span>';
																} else if($row->accept_time!==null && $row->data_solve=="") {
																	echo '<span class="badge_style b_medium">Job Accepted</span>';
																}  else if($row->accept_time!==null && $row->data_solve!=="") {
																	if($row->status_ticket=="CLOSED") {
																		echo '<span class="badge_style b_done">Job Done</span>';
																		echo '<a href="'.base_url().'dashboard/detail/'.$row->id_ticket.'"><span class="badge_style b_done">Detail</span></a>';
																	} else if($row->status_ticket=="PENDING") {
																		echo '<span class="badge_style b_away">Job PENDING</span>';
																	} else if($row->status_ticket=="SLM") {
																		echo '<span class="badge_style b_suspend">Refer to SLM</span>';
																	}
																}
															?>
															
															<br>
														
															<!--<span class="badge_style b_low">Pending A</span>
															<span class="badge_style b_medium">Pending B</span>
															<span class="badge_style b_high">Pending C</span>
															
															<span class="badge_style b_done">Pending D</span>
															<span class="badge_style b_away">Pending E</span>
															<span class="badge_style b_suspend">Pending F</span>-->
														</td>
													</tr>
													<?php 
														endforeach; 
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								
								
									
							</div>
								
								
						</div>
							
						<div id="tab3">
							<div class="stat_block black_rev">
							<section class="grid_12">
			<div class="widget_wrap">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6>STATUS TROUBLE TICKETS</h6>
				</div>
				<div class="widget_content">
					<table class="display data_tbl">
						<thead>
							<tr>
								<th>
									Ticket
								</th>
								<th>
									Bank
								</th>
								<th>
									Act
								</th>
								<th>
									Brand
								</th>
								<th>
									Problem Type
								</th>
								<th>
									Status
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								// print_r($statusflm);
							
								$no = 0;
								foreach($statusflm as $row): 
								$no++;
								
								$branch = $db->query("SELECT * FROM master_branch WHERE id='$row->cabang'")->row();
								$pro_type = json_decode($row->problem_type);	
								$ary = array();
								foreach($pro_type as $arr) {
									$ary[] = $db->query('SELECT nama_kategori FROM kategori WHERE id_kategori="'.$arr.'"')->row()->nama_kategori;
								}
							?>
							<tr>
								<td><?php echo $row->id_ticket;?></td>
								<td><?php echo $row->bank;?></td>
								<td><?php echo $row->type;?></td>
								<td><?php echo $row->type_mesin;?></td>
								<td><?php echo implode(", ",$ary);?></td>
								<td>
									<?php 
										if($row->accept_time==null && $row->data_solve=="") {
											echo '<span class="badge_style b_pending">Waiting PIC</span>';
										} else if($row->accept_time!==null && $row->data_solve=="") {
											echo '<span class="badge_style b_medium">Job Accepted</span>';
										}  else if($row->accept_time!==null && $row->data_solve!=="") {
											if($row->status_ticket=="CLOSED") {
												echo '<span class="badge_style b_done">Job Done</span>';
											} else if($row->status_ticket=="PENDING") {
												echo '<span class="badge_style b_away">Job PENDING</span>';
											} else if($row->status_ticket=="SLM") {
												echo '<span class="badge_style b_suspend">Refer to SLM</span>';
											}
										}
									?>
									
									<br>
								
									<!--<span class="badge_style b_low">Pending A</span>
									<span class="badge_style b_medium">Pending B</span>
									<span class="badge_style b_high">Pending C</span>
									
									<span class="badge_style b_done">Pending D</span>
									<span class="badge_style b_away">Pending E</span>
									<span class="badge_style b_suspend">Pending F</span>-->
								</td>
							</tr>
							<?php 
								endforeach; 
							?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	
							<span class="clear"></span>
								
									
							</div>
								
								
						</div>
						
						<div id="tab4">
							<div class="stat_block black_rev">
								
								<div class="grid_12">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Maps Global Tracking</h6>
										</div>
										
										<div class="widget_content">
											<div id="map1" class="chart_block" style="height:220px; width:100%;"></div>
										</div>
									</div>
								</div>
								<div class="grid_6">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Maps CIT & CR</h6>
										</div>
										
										<div class="widget_content">
											<div id="map2" class="chart_block" style="height:220px; width:100%;"></div>
										</div>
									</div>
								</div>
								<div class="grid_6">
									<div class="widget_wrap">
										<div class="widget_top">
											<span class="h_icon list"></span>
											<h6>Maps FLM & SLM</h6>
										</div>
										
										<div class="widget_content">
											<div id="map3" class="chart_block" style="height:220px; width:100%;"></div>
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
	<script>
		jq341 = jQuery.noConflict();
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "1nd loaded jQuery version ($): " + $.fn.jquery + "<br>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );


		$.get("<?php echo base_url().'datadashboard/get_data_runsheet'?>", function( data ) {
			var tess = Highcharts;
			tess.setOptions({
				colors: ['#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
			});
			tess.chart('chart_2', {
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie'
				},
				title: {
					text: ''
				},
				tooltip: {
					// pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false
						},
						showInLegend: true
					}
				},
				series: [{
					name: 'Persentase',
					colorByPoint: true,
					data: JSON.parse(data)
				}]
			});
		});

		$.get("<?php echo base_url().'datadashboard/get_data_ticket'?>", function( data ) {
			var tes = Highcharts;
			tes.setOptions({
				colors: ['#24CBE5', '#64E572', '#DDDF00', '#50B432', '#ED561B', '#FF9655', '#FFF263', '#6AF9C4']
			});
			tes.chart('chart_3', {
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie'
				},
				title: {
					text: ''
				},
				tooltip: {
					// pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false
						},
						showInLegend: true
					}
				},
				series: [{
					name: 'Persentase',
					colorByPoint: true,
					data: JSON.parse(data)
				}]
			});
		});

		Highcharts.chart('chart_1', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'ATM, CDM, CRM'
			},
			xAxis: {
				categories: [
					'CIMB',
					'OCBC',
					'BCA'
				],
				crosshair: true
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Interval Jumlah Mesin'
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					'<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series: [{
				name: 'ATM',
				data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

			}, {
				name: 'CDM',
				data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

			}, {
				name: 'CRM',
				data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

			}]
		});
		
		Highcharts.chart('chart_4', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'CIT'
			},
			xAxis: {
				categories: [
					'CIMB',
					'OCBC',
					'BCA'
				],
				crosshair: true
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Interval Jumlah Mesin'
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					'<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series: [{
				name: 'CIT',
				data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

			}]
		});

		var map;
		var map2;
		var map3;
		var marker;
		var markers = [];
		var google;
		function initialize() {
			var prop = {
				center: new google.maps.LatLng(-6.317971399999999, 106.83304389999999),
				zoom: 10,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			return new google.maps.Map(document.getElementById("map1"), prop);
		}
		
		function initialize2() {
			var prop = {
				center: new google.maps.LatLng(-6.317971399999999, 106.83304389999999),
				zoom: 10,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			return new google.maps.Map(document.getElementById("map2"), prop);
		}

		function initialize3() {
			var prop = {
				center: new google.maps.LatLng(-6.317971399999999, 106.83304389999999),
				zoom: 10,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			return new google.maps.Map(document.getElementById("map3"), prop);
		}

		map = initialize();
		map2 = initialize2();
		map3 = initialize3();
		google.maps.event.trigger(map, 'resize');
		google.maps.event.trigger(map2, 'resize');
		google.maps.event.trigger(map3, 'resize');

		// var infoWindow = new google.maps.InfoWindow;	
		// if (navigator.geolocation) {
		// 	navigator.geolocation.getCurrentPosition(function(position) {
		// 		var pos = {
		// 			lat: position.coords.latitude,
		// 			lng: position.coords.longitude
		// 		};

		// 		infoWindow.setPosition(pos);
		// 		console.log(pos);
		// 		// marker = new google.maps.Marker({
		// 		// 	position: pos,
		// 		// 	map: map,
		// 		// });

		// 		map.setCenter(pos);		
		// 		map2.setCenter(pos);		
		// 		map3.setCenter(pos);		
		// 	}, function() {
		// 		handleLocationError(true, infoWindow, map.getCenter());
		// 	});
		// } else {
		// 	// Browser doesn't support Geolocation
		// 	handleLocationError(false, infoWindow, map.getCenter());
		// }

		$.get("<?=base_url()?>datadashboard/get_data_client", function(response){

			$.each(JSON.parse(response), function(key, data) {
				marker = {wsid: data.wsid, lat: Number(data.latlng.lat), lng: Number(data.latlng.lng)};
				// addMarker(marker, data.wsid);
				markers.push(marker);
			});

			console.log(markers.length);
			setMarkers(map, markers);
		});

		function addMarker(location, wsid) {
			var infowindow = new google.maps.InfoWindow({
				content: "KODE PIC : "+wsid
			});
            var marker = new google.maps.Marker({
                position: location,
				// animation: google.maps.Animation.BOUNCE,
                map: map,
            });
			marker.addListener('click', function() {
				infowindow.open(map, marker);
			});
		}
		
		function setMarkers(map, locations) {
			// var image = new google.maps.MarkerImage('images/beachflag.png',
			// new google.maps.Size(20, 32),
			// new google.maps.Point(0,0),
			// new google.maps.Point(0, 32));
			// var shadow = new google.maps.MarkerImage('images/beachflag_shadow.png',
			// new google.maps.Size(37, 32),
			// new google.maps.Point(0,0),
			// new google.maps.Point(0, 32));
			var shape = {
				coord: [1, 1, 1, 20, 18, 20, 18 , 1],
				type: 'poly'
			};
			var bounds = new google.maps.LatLngBounds();
			for (var i = 0; i < locations.length; i++) {
				var loc = locations[i];

				var myLatLng = new google.maps.LatLng(loc.lat, loc.lng);
				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map,
					// shadow: shadow,
					// icon: image,
					shape: shape,
					title: loc.wsid,
					// zIndex: beach[3]
				});
				bounds.extend(myLatLng);
			}
			// map.fitBounds(bounds);
		}

		
	</script>
@endsection