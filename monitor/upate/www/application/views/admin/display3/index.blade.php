@extends('layouts.master3')

@section('content')
	<?php 
		// print_r($data_flm);
	?>
		<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery-1.6.min.js"></script>
		
		<script src="<?=base_url()?>assets/constellation/assets/equipment/jquery-3.4.1.min.js"></script>
		<link href="<?=base_url()?>assets/constellation/assets/equipment/select2.min.css" rel="stylesheet" />
		<script src="<?=base_url()?>assets/constellation/assets/equipment/select2.min.js"></script>

		<script src="<?=base_url()?>depend/highchart/code/highcharts.js"></script>
		<script src="<?=base_url()?>depend/highchart/code/modules/exporting.js"></script>
		<script src="<?=base_url()?>depend/highchart/code/modules/export-data.js"></script>
		
			<section class="grid_12" style="margin: -40px 0px 0px 0px; width:100%;">
			    <div class="widget_wrap tabby">
			        <div class="widget_top">
			            <span class="h_icon list"></span>
			            <h6>Main Dashboard & Summary Information</h6>
			            <div id="widget_tab">
			                <ul>
							<li><a onclick="function_map1()" href="#tab1">Maps Global Tracking</a></li>
			                    

			                </ul>
			            </div>
			        </div>
			        <div class="widget_content">
			            
			            <div id="tab1">
			                    <div id="map1" class="chart_block" style="height:600px; width:100%;"></div>
			                    
							<script>							
								function function_map1() {
									var map_1;
									var infowindow_1 = null;
									var markers_1 = [];
									
									var sitess_1 = [];
									
									function initialize_1() {
										var prop = {
											center: new google.maps.LatLng(-6.317971399999999, 106.83304389999999),
											zoom: 12,
											mapTypeId: google.maps.MapTypeId.ROADMAP
										};
										return new google.maps.Map(document.getElementById("map1"), prop);
									}
									
									map_1 = initialize_1();
									google.maps.event.trigger(map_1, 'resize');
									
									$.get("<?=base_url()?>datadashboard/get_data_client", function(response){
										$.each(JSON.parse(response), function(key, data) {
											var latlng = JSON.parse(data.latlng);
											var datas = [data.wsid, Number(latlng.lat), Number(latlng.lng), 1, data.wsid];
											
											sitess_1.push(datas);
										});
										
										// console.log(sitess_1);
										setZoom_1(map_1, sitess_1);
										setMarkers_1(map_1, sitess_1);
									});

									infowindow_1 = new google.maps.InfoWindow({
										content: "Loading..."
									});
									
									setInterval(refreshMap_1, 2000);
				
									function refreshMap_1() {
										// $.get("<?=base_url()?>datadashboard/get_data_client", function(response){
											// $.each(JSON.parse(response), function(key, data) {
												// // var latlng = JSON.parse(data.latlng);
												// // marker = {lat: Number(latlng.lat), lng: Number(latlng.lng)};
												// // addMarker(marker, data.nik);
												
												// if(markers_1[data.nik]!==undefined) {
													// var latlng = JSON.parse(data.latlng);
													// var uluru = {lat: Number(latlng.lat), lng: Number(latlng.lng)};
													
													// moove_1(data.nik, uluru);
												// }
											// });
										// });
									}
									
									/*
									This functions sets the markers (array)
									*/
									function setMarkers_1(map, markers) {
										for (var i = 0; i < markers.length; i++) {
											var site = markers[i];
											var siteLatLng = new google.maps.LatLng(site[1], site[2]);
											
											var icon = { // car icon
												path: 'M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759   c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z    M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z M15.741,21.713   v4.492l-2.73-0.349V14.502L15.741,21.713z M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z M14.568,40.882l2.218-3.336 h13.771l2.219,3.336H14.568z M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805',
												scale: 0.4,
												fillColor: "#427af4", //<-- Car Color, you can change it 
												fillOpacity: 1,
												strokeWeight: 1,
												anchor: new google.maps.Point(0, 5),
												// rotation: data.val().angle //<-- Car angle
												rotation: 0 //<-- Car angle
											};

											var marker = new google.maps.Marker({
												icon: "<?=base_url()?>assets/icon/atm-2.png",
												position: siteLatLng,
												map: map,
												title: site[0],
												zIndex: site[3],
												html: site[4],
												// Markers drop on the map
												animation: google.maps.Animation.DROP
											});
											
											markers_1[site[0]] = marker;

											google.maps.event.addListener(marker, "click", function() {
												infowindow_1.setContent(this.html);
												infowindow_1.open(map, this);
											});
										}
									}

									/*
									Set the zoom to fit comfortably all the markers in the map
									*/
									function setZoom_1(map, markers) {
										var boundbox = new google.maps.LatLngBounds();
										for (var i = 0; i < markers.length; i++) {
											boundbox.extend(new google.maps.LatLng(markers[i][1], markers[i][2]));
										}
										map.setCenter(boundbox.getCenter());
										map.fitBounds(boundbox);
									}
									
									function moove_1(index, latlng) {
										// console.log(latlng);
										marker = markers_1[index];
										marker.setPosition(latlng);
									}
								}
							</script>
			            </div>
						
					</div>
			    </div>

			</section>
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

		$.get("<?php echo base_url().'datadashboard/jumlah_kelola_atm'?>", function( data ) {
			// console.log();
			Highcharts.chart('chart_1', {
				chart: {
					type: 'column'
				},
				title: {
					text: 'ATM, CDM, CRM'
				},
				xAxis: {
					categories:  JSON.parse(data).bank	,
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
						'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
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
				series: JSON.parse(data).data
			});
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
	</script>
@endsection