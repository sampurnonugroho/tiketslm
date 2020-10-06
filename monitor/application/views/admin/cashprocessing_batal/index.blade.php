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
	</style>
	
	<article class="container_12">
	
		<section class="grid_12">
			<div class="block-border">
				<div class="block-content no-title dark-bg">
					<div id="control-bar">
						<div class="container_16">
							<div class="float-left" style="position: absolute">
								<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
							</div>
							<center>
								<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
							</center>
							<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION   
								<br>[DATA <?=strtoupper(str_replace("_", " ", $active_menu))?>]
							</p>
						</div>
					</div>	
				</div>
			</div>
			<div class="block-border">
				<form class="block-content form" id="complex_form" method="post" action="#">
					<h1>[DATA <?=strtoupper(str_replace("_", " ", $active_menu))?>]</h1>
					<br>
					<div style="float: right;z-index: 90000;">
						<table>
							<tr>
								<td><label for="search">Search by Date</label></td>
								<td width="10px"> : </td>
								<td><input type="text" name="simple-calendar" id="search" class="datepicker_search"></td>
							</tr>
						</table>
					</div>
					
					<table id="example" class="display" style="width:100%">
						<thead>
							<tr>
								<th>Run Number</th>
								<th>Date Action</th>
								<th>Kelolaan</th>
								<th>Need to assign</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Run Number</th>
								<th>Date Action</th>
								<th>Kelolaan</th>
								<th>Need to assign</th>
								<th>Action</th>
							</tr>
						</tfoot>
					</table>
					
					<table class="table sortableXxx " cellspacing="80" width="100%" style="display: none">
					
						<thead>
							<tr>
								<th class="black-cell"><span class="loading"></span></th>
								<th scope="col">
									Run Number
								</th>
								<th scope="col">
									Date
								</th>
								<th scope="col">
									Kelolaan
								</th>
								<th scope="col">
									Need to assign
								</th>
								<?php if($session->userdata['level']=="LEVEL1") { ?>
									<th scope="col">
										Action
									</th>
								<?php } ?>
							</tr>
						</thead>
						
						<tbody>
							<?php 
								$no = 0;
								foreach($data_cashprocessing as $row): 
								$no++;
							?>
							<tr>
								<td class="th table-check-cell"><?=$no?></td>
								<td><?php echo "(H-".$row->h_min.") (RUN NUMBER ".$row->run_number.")";?></td>
								<td><?php echo $row->date;?></td>
								<td><?php echo $row->name;?></td>
								<td><?php echo $row->count;?> Data</td>
								<?php if($session->userdata['level']=="LEVEL1") { ?>
									<td style="text-align: center">
										<button type="button" class='button green' onClick="window.location.href='<?php echo base_url();?>cashprocessing/edit/<?php echo $row->id_ct;?>#tab-hcp'" title='Edit'><span class='smaller'>View Detail Data</span></button>
									</td>
								<?php } ?>
							</tr>
							<?php 
								endforeach; 
							?>
						</tbody>
					</table>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
	
	
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
				url: '<?=base_url()?>cashprocessing_batal/json',
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
						return  '(H-'+row['h_min']+') RUN NUMBER '+data+'';
					},
					"className": "text-center",
					"targets": 0,
					"searchable": false
				},
				{
					"render": function ( data, type, row ) {
						return  formatDate(data);
					},
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
					"render": function ( data, type, row ) {
						if(data=="0") {
							return "<span style='font-weight: normal'>"+data+"</span>";
						} else {
							return "<span style='font-weight: bold'>"+data+"</span>";
						}
					},
					"className": "text-center",
					"targets": 3,
					"searchable": false
				},
				{
					"render": function ( data, type, row ) {
						return '<button type="button" class="button green" style="font-size: 12px" onClick="window.location.href=\'<?=base_url()?>cashprocessing_batal/edit/'+data+'\'" title=""><span class="">View Detail Data</span></button>';
					},
					"className": "text-center",
					"targets": 4,
					"searchable": false
				}
			],
			"columns": [
				{"data": "run_number", width:100},
				{"data": "action_date", width:100},
				{"data": "branch", width:100},
				{"data": "count", width:100},
				{"data": "id_ct", width:100}
			],
		});
		
		function formatDate (input) {
			var datePart = input.match(/\d+/g),
			year = datePart[0], // get only two digits
			month = datePart[1], day = datePart[2];

			return day+'-'+month+'-'+year;
		}
		
		
		// jq341('#example_filter').find('input').val('Default Product');
		// jq341('#example_filter label input[type=text]').val('Default Product');
		
		
		jq341('.datepicker_search').datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			onClose: function(dateText, inst) { 
				tables.draw();
			}
		});  
		
		
		function jsUcfirst(string) { return string.charAt(0).toUpperCase() + string.slice(1); }
		
		function openModalBranch()
		{
			// $.modal({
				// content: '<p><label>Branch</label><select name="cabang" class="js-example-basic-single2 full-width" required><option value="">- select cabang -</option></select></p>'+
						 // '<p><label>Zone</label><select name="cabang" class="js-example-basic-single2 full-width" required><option value="">- select cabang -</option></select></p>',
				// title: 'Input',
				// maxWidth: 400,
				// buttons: {
					// 'Yes': function(win) { 
						// $.ajax({
							// url: url,
							// dataType: 'html',
							// type: 'POST',
							// data: {id:id},
							// success: function(data) {
								// if(data=="success") {
									// window.location.reload();
								// } else {
									// win.closeModal();
								// }
							// }
						// });
					// },
					// 'Close': function(win) { win.closeModal(); }
				// }
			// });
			
			$.modal({
				content: '<p><label>Branch</label><select name="cabang" class="js-example-basic-single2 full-width" required><option value="">- select cabang -</option></select></p>',
				title: 'Input',
				maxWidth: 400,
				buttons: {
					'Yes': function(win) { 
						var id_branch = jq341(".js-example-basic-single2 option:selected").val();
					
						// alert(data);
						$.ajax({
							url: '<?=base_url()?>cashtransit/add_master',
							dataType: 'html',
							type: 'POST',
							data: {id:id_branch},
							success: function(data) {
								window.location.href = '<?=base_url()?>cashtransit/add/'+data;
							}
						});
					},
					'Close': function(win) { win.closeModal(); }
				}
			});
			
			jq341('.js-example-basic-single2').select2({
				tags: false,
				tokenSeparators: [','],
				width: '100%',
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'select/select_branch'?>',
					delay: 250,
					type: "POST",
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function (data, page) {
						console.log(data);
						return {
							results: data
						};
					}
				},
				maximumSelectionLength: 3,

				// add "(new tag)" for new tags
				createTag: function (params) {
				  var term = jq341.trim(params.term);

				  if (term === '') {
					return null;
				  }

				  return {
					id: term,
					text: term + ' (add new)'
				  };
				},
			});
			
		}
	
		jq341(document).ready(function() {
			
			jq341('.sort_by_').select2().on('select2:select', function (evt) {
				// var data = jq341(".sort_by_ option:selected").text();
				var value = jq341(".sort_by_ option:selected").val();
				
				if(value!=="") {
					jq341('.sorted_by_1').show();
					jq341('#sort_title').html(jsUcfirst(value));
					
					proses_cari(value, function() {});
				} else {
					jq341('.sorted_by_1').hide();
				}
			});
			
			
			function proses_cari(value, calback) {
				console.log(value);
				var url;
				if(value=="bank") {
					url = '<?php echo base_url().'select/select_bank'?>';
				} else if(value=="branch") {
					url = '<?php echo base_url().'select/select_branch'?>';
				} else {
					url = '<?php echo base_url().'select/select_area'?>';
				}
				
				jq341('.sorted_by_').val([]);
				jq341('.sorted_by_').select2({
					val: "",
					tags: false,
					tokenSeparators: [','],
					ajax: {
						dataType: 'json',
						url: url,
						// delay: 250,
						type: "POST",
						data: function(params) {
							return {
								search: params.term
							}
						},
						processResults: function (data, page) {
							console.log(data);
							return {
								results: data
							};
						}
					},
					maximumSelectionLength: 3
				}).on('select2:select', function (evt) {
					var data = jq341(".sorted_by_ option:selected").text();
					$.ajax({
						type: "POST",
						url : "<?php echo base_url().'select/getdataclient'?>",				
						data: {datas:data},
						success: function(msg){
							console.log(msg);
							var jsdata = JSON.parse(msg);	
							$('.sortableXxx').dataTable().fnClearTable();
							$('.sortableXxx').dataTable().fnAddData(jsdata).fnDraw();
						}
					});
				});
			}
		});
	</script>
@endsection