@extends('layouts.master')

@section('content')
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>depend/jquery-confirm/css/jquery-confirm.css"/>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/datatables/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/datatables/fixedColumns.dataTables.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/datatables/scroller.dataTables.min.css"/>
 
	<script type="text/javascript" src="<?=base_url()?>/assets/datatables/datatables.min.js"></script>
	
	<script type="text/javascript" src="<?=base_url()?>assets/jquery.scannerdetection.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/notify.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/jquery.inputmask.js"></script>
	
	<script type="text/javascript" src="<?=base_url()?>/assets/datatables/dataTables.fixedColumns.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>/assets/datatables/dataTables.scroller.min.js"></script>
	
	<article class="container_12">
		
		<section class="grid_12">
			<div class="preview_pdf" hidden>
				<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview' type="button">Close</button>
				<iframe id="preview" name="preview" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
			</div>
			<div class="widget_wrap preview_table">
				<div class="widget_top">
					<span class="h_icon list_images"></span>
					<h6>REPORT SERVICE LEVEL AGREEMENT (SLA - FLM NASIONAL)</h6>
				</div>
				<div class="widget_content" id="content_table">
					<div>
						<style>
							div.dataTables_wrapper {
								width: 100%;
								margin: 0 auto;
							}
							th, td { white-space: nowrap; background: #fff; }
							th { white-space: nowrap; background: #fff; vertical-align: middle }
							div.dataTables_wrapper {
								width: 100%;
								margin: 0 auto;
								display: block;
							}
							
							// .dataTables_scrollHead thead {
								// visibility: collapse;   
							// }
							// .dataTables_scrollBody thead {
								// visibility: collapse;   
							// }
							
							.text-total {
								background-color: rgb(179, 201, 132);
								text-align: right;
							}
							th.text-total2 {
								background: #666;
								text-align: center;
								vertical-align: middle;
								color: white;
								font-weight: bold;
							}
							td.text-total2 {
								background: #666;
								text-align: right;
								vertical-align: middle;
								color: white;
								font-weight: bold;
							}
							.text-number {
								text-align: right;
							}
						</style>
						<table id="example" class="display nowrap cell-border" style="width:100%">
							<thead>
								<tr>
                                    <th>No</th>
                                    <th>WSID / Lokasi</th>
                                    <th>Custody</th>
                                    <th>Guard</th>
                                    <th>No. Ticket (BIJAK)</th>
                                    <th>No. Ticket (CLIENT)</th>
									<th>Problem</th>
                                    <th>Action Taken</th>
                                    <th>Email Date / Time</th>
                                    <th>Entry Date / Time</th>
                                    <th>Accept Time</th>
                                    <th>Arrival Date / Time</th>
                                    <th>Start Date / Time</th>
                                    <th>Close Date / Time</th>
									<th style="background-color: #ebebeb">Response Time Duty</th>
                                    <th style="background-color: #ebebeb">Response Time Flm</th>
                                    <th style="background-color: #ebebeb">Repair Time</th>
                                    <th style="background-color: #ebebeb">Resolution Time</th>
                                    <th style="background-color: #ebebeb">DT (%) / Up Time</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</section>
	
		<div class="clear"></div>
	</article>
	
	
	<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
	<script src="<?=base_url()?>depend/js/jquery-ui-1.8.18.custom.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	<script src="<?=base_url()?>depend/js/full-calendar.jquery.js"></script>
	
	
	<script>
		var tabless;
		jq341 = jQuery.noConflict(true);
		jq3412 = jQuery.noConflict(true);
		
		console.log(jq341().jquery);
		console.log(jq3412().jquery);
		
		jq3412(document).ready(function() {
			tabless = jq3412('#example').DataTable({
				serverSide: true,
				ordering: false,
				searching: false,
				lengthChange: false,
				ajax: {
					url: '<?=base_url()?>sla/json',
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
						// console.log(data);
						var json = jQuery.parseJSON( data );
						json.recordsTotal = json.recordsTotal;
						json.recordsFiltered = json.recordsFiltered;
						json.data = json.data;

						return JSON.stringify( json ); // return JSON string
					}
				},
				scrollX:        true,
				"columns": [
					{"data": "no"},
					{"data": "lokasi"},
					{"data": "nama_teknisi"},
					{"data": "nama_guard"},
					{"data": "ticket"},
					{"data": "ticket_client"},
					{"data": "problem_type"},
					{"data": "keterangan"},
					{"data": "email_date"},
					{"data": "entry_date"},
					{"data": "accept_time"},
					{"data": "arrival_date"},
					{"data": "start_date"},
					{"data": "close_date"},
					{"data": "response_duty"},
					{"data": "response_flm"},
					{"data": "repair_time"},
					{"data": "resolution_time"},
					{"data": "down_time"},
				],
			});
		});
		
		function formatDate (input) {
			var datePart = input.match(/\d+/g),
			year = datePart[0], // get only two digits
			month = datePart[1], day = datePart[2];

			return day+'-'+month+'-'+year;
		}
	</script>
@endsection