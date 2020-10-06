<?php $__env->startSection('content'); ?>
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>depend/jquery-confirm/css/jquery-confirm.css"/>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/datatables/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.0/css/fixedColumns.dataTables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/2.0.1/css/scroller.dataTables.min.css"/>
 
	<script type="text/javascript" src="<?=base_url()?>/assets/datatables/datatables.min.js"></script>
	
	<script type="text/javascript" src="<?=base_url()?>assets/jquery.scannerdetection.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/notify.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/jquery.inputmask.js"></script>
	
	<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.1/js/dataTables.scroller.min.js"></script>
	
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
						</style>
						<!--<table id="examplex" class="display nowrap" style="width:100%">
							<thead>
								<tr>
									<th rowspan=2 class="zui-sticky-col ">
										No.
									</th>
									<th rowspan=2 class="zui-sticky-col ">
										Tanggal
									</th>
									<th rowspan=2 class="zui-sticky-col ">
										ID ATM
									</th>
									<th rowspan=2 class="zui-sticky-col">
										Keterangan
									</th>
									<th colspan=2 class="zui-sticky-col">
										DENOM 100 	
									</th>
									<th rowspan=2 style="background-color: #b3c984" class="zui-sticky-col">
										SALDO 100
									</th>
									<th colspan=2 class="zui-sticky-col">
										DENOM 50 	
									</th>
									<th rowspan=2 style="background-color: #b3c984" class="zui-sticky-col">
										SALDO 50
									</th>
									<th colspan=2 class="zui-sticky-col">
										DENOM 20 	
									</th>
									<th rowspan=2 style="background-color: #b3c984" class="zui-sticky-col">
										SALDO 20
									</th>
									<th rowspan=2 class="zui-sticky-col">
										SALDO
									</th>
								</tr>
								<tr>
									<th class="zui-sticky-col2">
										DEBET 100 
									</th>
									<th class="zui-sticky-col2">
										KREDIT 100 
									</th>
									<th class="zui-sticky-col2">
										DEBET 50 
									</th>
									<th class="zui-sticky-col2">
										KREDIT 50 
									</th>
									<th class="zui-sticky-col2">
										DEBET 20 
									</th>
									<th class="zui-sticky-col2">
										KREDIT 20 
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Tiger Nixon</td>
									<td>System Architect</td>
									<td>$320,800</td>
									<td>Edinburgh</td>
									<td>5421</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td>Tiger Nixon</td>
									<td>System Architect</td>
									<td>$320,800</td>
									<td>Edinburgh</td>
									<td>5421</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
									<td>t.nixon@datatables.net</td>
								</tr>
							</tfoot>
						</table>-->
						
						
						<div style="float: left;z-index: 90000;">
							<table>
								<tr>
									<td><button class="small button green" onclick="execute()">Start Sync</button> </td>
									<td width="10px"></td>
									<td><span id="result"></span></td>
								</tr>
							</table>
						</div>
						<table id="example" class="display nowrap" style="width:100%">
							<thead>
								<tr>
									<th rowspan=2 class="zui-sticky-col ">
										No.
									</th>
									<th rowspan=2 class="zui-sticky-col ">
										Tanggal
									</th>
									<th rowspan=2 class="zui-sticky-col ">
										ID ATM
									</th>
									<th rowspan=2 class="zui-sticky-col">
										Keterangan
									</th>
									<th colspan=2 class="zui-sticky-col">
										DENOM 100 	
									</th>
									<th rowspan=2 style="background-color: #b3c984" class="zui-sticky-col">
										SALDO 100
									</th>
									<th colspan=2 class="zui-sticky-col">
										DENOM 50 	
									</th>
									<th rowspan=2 style="background-color: #b3c984" class="zui-sticky-col">
										SALDO 50
									</th>
									<th colspan=2 class="zui-sticky-col">
										DENOM 20 	
									</th>
									<th rowspan=2 style="background-color: #b3c984" class="zui-sticky-col">
										SALDO 20
									</th>
									<th rowspan=2 class="zui-sticky-col">
										SALDO
									</th>
								</tr>
								<tr>
									<th class="zui-sticky-col2">
										DEBET 100 
									</th>
									<th class="zui-sticky-col2">
										KREDIT 100 
									</th>
									<th class="zui-sticky-col2">
										DEBET 50 
									</th>
									<th class="zui-sticky-col2">
										KREDIT 50 
									</th>
									<th class="zui-sticky-col2">
										DEBET 20 
									</th>
									<th class="zui-sticky-col2">
										KREDIT 20 
									</th>
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
				// ajax: function (data, callback, settings) {
					// var out = [];
					// for (var i=data.start, ien=data.start+data.length ; i<ien ; i++ ) {
						// out.push([ i+'-1', i+'-2', i+'-3', i+'-4', i+'-5', i+'-5', i+'-5', i+'-5', i+'-5', i+'-5', i+'-5', i+'-5', i+'-5', i+'-5' ]);
					// }
					// setTimeout(function () {
						// callback( {
							// draw: data.draw,
							// data: out,
							// recordsTotal: 5000000,
							// recordsFiltered: 5000000
						// });
					// }, 50);
				// },
				ajax: {
					url: '<?=base_url()?>Table/json',
					dataFilter: function(data){
						console.log(data);
						var json = jQuery.parseJSON(data);
						json.recordsTotal = json.recordsTotal;
						json.recordsFiltered = json.recordsFiltered;
						json.data = json.data;

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
					leftColumns: 1,
					leftColumns: 2,
					leftColumns: 3
				},
				"columnDefs": [
					{
						"render": function ( data, type, row ) {
							return  data;
						},
						"className": "text-center",
						"targets": 0,
						"searchable": false
					},
					{
						"render": function ( data, type, row ) {
							return  formatDate(data);
						},
						"targets": 1,
						"searchable": false
					},
					{
						"targets": 2,
						"searchable": true
					},
					{
						"render": function ( data, type, row ) {
							return  data.replace("_", " ").toUpperCase();
						},
						"targets": 3,
						"searchable": false
					},
					{
						"targets": 4,
						"searchable": false
					},
					{
						"targets": 5,
						"searchable": false
					},
					{
						"targets": 6,
						"searchable": false
					},
					{
						"targets": 7,
						"searchable": false
					},
					{
						"targets": 8,
						"searchable": false
					},
					{
						"targets": 9,
						"searchable": false
					},
					{
						"targets": 10,
						"searchable": false
					},
					{
						"targets": 11,
						"searchable": false
					},
					{
						"targets": 12,
						"searchable": false
					},
					{
						"targets": 13,
						"searchable": false
					}
				],
				"columns": [
					{"data": "no"},
					{"data": "tanggal"},
					{"data": "wsid"},
					{"data": "keterangan"},
					{"data": "debit_100"},
					{"data": "kredit_100"},
					{"data": "saldo_100"},
					{"data": "debit_50"},
					{"data": "kredit_50"},
					{"data": "saldo_50"},
					{"data": "debit_20"},
					{"data": "kredit_20"},
					{"data": "saldo_20"},
					{"data": "sum_saldo"},
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
						tabless.draw();
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>