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
			<div style="float: right">
				<label for="search">Search by Date : </label>
				<input type="text" name="simple-calendar" id="search" class="datepicker_search">
			</div>
			<div class="widget_wrap preview_table">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6><?=ucwords(str_replace("_", " ", $active_menu))?> Data</h6>
				</div>
				<div class="widget_content" id="content_table">
					<div>
						<table id="example" class="display nowrap cell-border" style="width:100%">
							<thead>
								<tr>
									<th>NO</th>
									<th>BANK</th>
									<th>WSID</th>
									<th>TYPE</th>
									<th>ACT</th>
									<th>TANGGAL ISI</th>
									<th>INTERVAL</th>
									<th>STATUS INTERVAL</th>
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
			jq3412('#examplex').DataTable({
				scrollY:        "300px",
				scrollX:        true,
				scrollCollapse: true,
				paging:         false,
				fixedColumns:   {
					leftColumns: 1,
					leftColumns: 2,
					leftColumns: 3
				}
			});
			
			tabless = jq3412('#example').DataTable({
				serverSide: true,
				ordering: false,
				searching: true,
				ajax: {
					url: '<?=base_url()?>all_interval/json',
					dataFilter: function(data){
						console.log(data);
						var json = jQuery.parseJSON(data);
						json.recordsTotal = json.recordsTotal;
						json.recordsFiltered = json.recordsFiltered;
						json.data = json.data;
						
						$("#saldo100_value").html(json.saldo_100);
						$("#saldo50_value").html(json.saldo_50);
						$("#saldo20_value").html(json.saldo_20);
						$("#saldo_value").html(json.sum_saldo);

						return JSON.stringify( json ); // return JSON string
					}
				},
				scrollY: 350,
				scroller: {
					loadingIndicator: true
				},
				scrollX:        true,
				scrollCollapse: true,
				fixedColumns:   {
					leftColumns: 3,
					rightColumns: 1
				},
				// "columnDefs": [
					// {
						// "render": function ( data, type, row ) {
							// return  data;
						// },
						// "className": "text-center",
						// "targets": 0,
						// "searchable": false
					// },
					// {
						// "render": function ( data, type, row ) {
							// return  formatDate(data);
						// },
						// "targets": 1,
						// "searchable": false
					// },
					// {
						// "targets": 2,
						// "searchable": true
					// },
					// {
						// "render": function ( data, type, row ) {
							// return  data.replace("_", " ").toUpperCase();
						// },
						// "targets": 3,
						// "searchable": false
					// },
					// {
						// "className": "text-number",
						// "targets": 4,
						// "searchable": false
					// },
					// {
						// "className": "text-number",
						// "targets": 5,
						// "searchable": false
					// },
					// {
						// "className": "text-total",
						// "targets": 6,
						// "searchable": false
					// },
					// {
						// "className": "text-number",
						// "targets": 7,
						// "searchable": false
					// },
					// {
						// "className": "text-number",
						// "targets": 8,
						// "searchable": false
					// },
					// {
						// "className": "text-total",
						// "targets": 9,
						// "searchable": false
					// },
					// {
						// "className": "text-number",
						// "targets": 10,
						// "searchable": false
					// },
					// {
						// "className": "text-number",
						// "targets": 11,
						// "searchable": false
					// },
					// {
						// "className": "text-total",
						// "targets": 12,
						// "searchable": false
					// },
					// {
						// "className": "text-total2",
						// "targets": 13,
						// "searchable": false
					// }
				// ],
				"columns": [
					{"data": "wsid"},
					{"data": "bank"},
					{"data": "wsid"},
					{"data": "type_mesin"},
					{"data": "type"},
					{"data": "tgl_isi"},
					{"data": "interval_isi"},
					{"data": "interval_isi"},
				]
			});
		});
		
		function formatDate (input) {
			var datePart = input.match(/\d+/g),
			year = datePart[0], // get only two digits
			month = datePart[1], day = datePart[2];

			return day+'-'+month+'-'+year;
		}
		
		var total = 0;
		
		function execute() {
			setTimeout("delete_sync()",1500);
			setTimeout("proses()",1500);
		}
	
		function proses(page=1) {
			tabless.draw();
			$.ajax({
				url: 'http://pt-bijak.co.id/rest_api_dev_minggu/server/api/table/index2?page='+page,
				dataType: 'html',
				type: 'GET',
				data: {},
				success: function(data) {
					res = JSON.parse(data);
					console.log(res);
					
					if(total==0) {
						total = res.total;
					}
					console.log(total);
					if(res.total!==res.page && res.page!=="done") {
						setTimeout("proses('"+page+"')",10);
					}
					percen = (((res.total*-1)+total)/total)*100;
					// document.getElementById("result").innerHTML = percen.toFixed(2)+"%";
					percen = isNaN(percen) ? 100 : percen.toFixed(2);
					document.getElementById("result").innerHTML = "Progress : "+percen+"%";
					
					if(percen==100) {
						// tabless.draw();
						window.location.reload();
					}
				}
			});
		}
		
		function delete_sync() {
			$.ajax({
				url: 'http://pt-bijak.co.id/rest_api_dev_minggu/server/api/table/delete_sync',
				dataType: 'html',
				type: 'GET',
				data: {},
				success: function(data) {
					total = 0;
					console.clear();
					console.log("DONE");
				}
			});
		}
	</script>
@endsection