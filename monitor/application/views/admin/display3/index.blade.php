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
			            <h6>Trouble Information</h6>
			            <div id="widget_tab">
			                <ul>
							<li><a href="#tab1">Recent Issue Troubleshoot</a></li>  

			                </ul>
			            </div>
			        </div>
			        <div class="widget_content">
			            
			            <div id="tab1">
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