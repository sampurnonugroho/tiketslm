@extends('layouts.master')

@section('content')

	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>


	<link rel="stylesheet" type="text/css" href="<?=base_url()?>depend/jquery-confirm/css/jquery-confirm.css"/>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>

	<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/datatables/datatables.min.css"/>

	<script type="text/javascript" src="<?=base_url()?>/assets/datatables/datatables.min.js"></script>

	<script type="text/javascript" src="<?=base_url()?>assets/jquery.scannerdetection.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/notify.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/jquery.inputmask.js"></script>

	<style>
		.jconfirm .jconfirm-holder {
			max-height: 100%;
			padding: 54px 450px;
				padding-top: 54px;
				padding-bottom: 54px;
		}
		#preview {
			float: right; 
			height: 803px; 
			width: 100%; 
			border: 1px solid #666; 
			-moz-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			-webkit-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
		}
		.dataTables_filter {
			width: 40%;
			float: left !important;
			text-align: left !important;
		}
		.dataTables_wrapper {
			padding: 5px
		}
		.dataTables_length {
			float: right !important;
		}
		.text-center{
			text-align: center
		}
		#preview {
			float: right; 
			height: 803px; 
			width: 100%; 
			border: 1px solid #666; 
			-moz-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			-webkit-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
			box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
		}
	</style>
	<!-- Content -->
	<div id="content">
		<div class="grid_container">
			<div class="grid_14">
				<div class="preview_pdf" hidden>
					<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview' type="button">Close</button>
					<iframe id="preview" name="preview" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
				</div>
			
				<div class="widget_wrap preview_table">
					<div class="widget_top">
						<span class="h_icon list"></span>
					</div>
					<div class="widget_content">
						<div class=" page_content">
							<div class="invoice_container">
								
								<div class="grid_12 invoice_title">
									<center><img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
								</center>
								<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b>
								<br>ACTUAL DAILY MONITORING
								<br>[ ATM CR - NASIONAL ]</p>
								</div>
								<span class="clear"></span>
								<div class="grid_12 invoice_details">
									<div class="invoice_tbl">
										<form class="form" id="complex_form" method="post" action="#">
											<div style="float: right;z-index: 90000;">
												<label for="search">Search by Date</label>
												<input type="text" name="simple-calendar" id="search" class="datepicker_search">
											</div>
											
											<table id="example" class="display" style="width:100%">
												<thead>
													<tr>
														<th>TANGGAL</th>
														<th>WSID</th>
														<th>BANK</th>
														<th>LOCATION</th>
														<th>ACTION</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th>TANGGAL</th>
														<th>WSID</th>
														<th>BANK</th>
														<th>LOCATION</th>
														<th>ACTION</th>
													</tr>
												</tfoot>
											</table>
										</form>
										<table style="display: none">
											<thead>
												<tr class=" gray_sai">
													<th>
														No.
													</th>
													<th>
														Tanggal
													</th>
													<th>
														ID
													</th>
													<th>
														BANK
													</th>
													<th>
														LOCATION
													</th>
													<th>
														TYPE
													</th>
													<th>
														ACTION
													</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													$no = 0;
													foreach($data_cashreplenish as $row) {
													$no++;
												?>
												<tr>
													<td>
														<?=$no?>
													</td>
													<td>
														<?=date("d-m-Y", strtotime($row->action_date))?>
													</td>
													<td class="left_align">
														<?=$row->wsid?> <div hidden><?=$row->id_detail?></div>
													</td>
													<td>
														<?=$row->bank?>
													</td>
													<td class="left_align">
														<?=$row->lokasi?>
													</td>
													<td>
														<?=$row->type_mesin?>
													</td>
													<td>
														<div id="id" hidden><?=$row->id_detail?></div>
														<!--<a href="<?=base_url()?>pdf/generate/<?=$row->id_detail?>" target="_blank">Detail</a>-->
														<a href="#" class="detail_previews">Detail</a>
													</td>
												</tr>
												<?php 
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
								<span class="clear"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<span class="clear"></span>
	</div>
		
	<div class="clear"></div>
	
	<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
	<script src="<?=base_url()?>depend/js/jquery-ui-1.8.18.custom.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	<script src="<?=base_url()?>depend/js/full-calendar.jquery.js"></script>
	
	<script>
		jq341 = jQuery.noConflict(true);
		jq3412 = jQuery.noConflict(true);
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq3412.fn.jquery + "<br>" );
		
		var tables = jq3412('#example').DataTable({
			serverSide: true,
			ordering: false,
			searching: false,
			lengthChange: false,
			ajax: {
				url: '<?=base_url()?>atmcr_nas/json',
				data: function(data) {
					// Read values
					var values = jq341('.datepicker_search').val();
					// alert(values);

					// Append to data
					if(values=="") {
						// data.search.value = "<?=date('Y-m-d')?>";
					} else {
						data.search.value = values;
					}
				},
				dataFilter: function(data){
					console.log(data);
					var json = jQuery.parseJSON( data );
					json.recordsTotal = json.recordsTotal;
					json.recordsFiltered = json.recordsFiltered;
					json.data = json.data;

					return JSON.stringify( json ); // return JSON string
				}
			},
			"columnDefs": [
				{
					"render": function ( data, type, row ) {
						if(data===null) {
							if(row['updated_date']===null) {
								return  "(Data Lama)";								
							} else {
								return  "(Data Lama) "+formatDate(row['updated_date']);
							}
						} else {
							return  formatDate(data);
						}
					},
					"className": "text-center",
					"targets": 0,
					"searchable": false
				},
				{
					"className": "text-center",
					"targets": 1,
					"searchable": false
				},
				{
					"className": "text-center",
					"targets": 2,
					"searchable": false
				},
				{
					"className": "text-center",
					"targets": 3,
					"searchable": false
				},
				{
					"render": function ( data, type, row ) {
						return '<div id="id" hidden>'+data+'</div><button type="button" class="button" style="font-size: 12px; color: black"><a href="#" class="detail_previews" style="color: black">Detail</a></button>';
					},
					"className": "text-center",
					"targets": 4,
					"searchable": false
				}
			],
			"columns": [
				{"data": "action_date", width:100},
				{"data": "wsid", width:100},
				{"data": "bank", width:100},
				{"data": "lokasi", width:100},
				{"data": "id_detail", width:100}
			]
		});
		
		function formatDate (input) {
			var datePart = input.match(/\d+/g),
			year = datePart[0], // get only two digits
			month = datePart[1], day = datePart[2];

			return day+'-'+month+'-'+year;
		}
		
		jq341('.datepicker_search').datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			onClose: function(dateText, inst) { 
				tables.draw();
			}
		}); 
	
		jq341(document).on('click', '.detail_previews', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = jq341(this).closest('td').find('#id').text();
			// alert(id);
			setTimeout(function() {
			var websel = "<?=base_url()?>atmcr_nas/pdf/"+id;
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		jq341(document).on('click', '#close_preview', function(){ 
			jq341(".preview_pdf").hide();
			jq341(".preview_table").show();
		});
	</script>
@endsection