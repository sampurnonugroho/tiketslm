<?php $__env->startSection('content'); ?>
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
		.dataTables_filter {
			width: 40%;
			float: left !important;
			text-align: left !important;
		}
		.dataTables_length {
			float: right !important;
		}
		
		.select2-container {
			z-index: 99999999 !important;
		}
		
		.dataTables_paginate #datatable_previous {
			width: 60px !important;
		}
		
		.dataTables_paginate #datatable_next {
			width: 60px !important;
		}
		
		#ui-datepicker-div {
			z-index: 99999999 !important;
		}
		.ui-datepicker {
			z-index: 99999999 !important;
		}
		.jconfirm {
			z-index: 99999998 !important;
		}
		
		input[readOnly] {
			background: #e6e6e6 !important;
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
						<div class="float-left">
							<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16" hidden> Back</button>
						</div>
						<div class="float-right" hidden>
							<button type="button" onClick="openModalTambah();">Tambah</button>
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
	
			<div class="preview_pdf" hidden>
				<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview' type="button">Close</button>
				<iframe id="preview" name="preview" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
			</div>
			<div class="widget_wrap preview_table">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6><?=ucwords(str_replace("_", " ", $active_menu))?> Data</h6>
				</div>
				<div class="widget_content">
				
					<table id="example" class="display" style="width:100%">
						<thead>
							<tr>
								<th>WSID</th>
								<th>NO. TICKET (BIJAK)</th>
								<th>ACT</th>
								<th>MODEL</th>
								<th>LOCATION</th>
								<th>PROBLEM TYPE</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>WSID</th>
								<th>NO. TICKET (BIJAK)</th>
								<th>ACT</th>
								<th>MODEL</th>
								<th>LOCATION</th>
								<th>PROBLEM TYPE</th>
								<th>ACTION</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</section>
		
		<div id="html_content" hidden>
			<form class="form mysets-area">
				<fieldset>
					<p>
						<label>Vendor</label>
						<input type="text" id="vendor" placeholder="" value="" class="full-width vendor" required>
					</p>
					<p>
						<label>Assign To</label>
						<select name="teknisi_1" class="easyui-validatebox flm" style="width: 100%" required>
							<option value="">-- select flm --</option>
						</select>
					</p>
					<p>
						<label>Security & Guard</label>
						<select name="guard" class="easyui-validatebox guard" style="width: 100%" required>
							<option value="">-- select guard --</option>
						</select>
					</p>
				</fieldset>
			</form>
		</div>
		
		<div id="html_content2" hidden>
			<form class="form mysets-area">
				<fieldset>
					<p>
						<label>WSID</label>
						<select name="id_bank" class="easyui-validatebox client" style="width:100%" required>
							<option value="">-- select client --</option>
						</select>
					</p>
					<p>
						<label>Ticket Client</label>
						<input type="text" id="ticket_client" placeholder="" value="" class="full-width ticket" required>
					</p>
					<p>
						<label>Email Date</label>
						<input data-inputmask="'alias': 'datetime', 'inputFormat': 'dd-mm-yyyy HH:MM'" type="text" id="email_time" class="value full-width datetime" required>
					</p>
					<p>
						<label>Problem Type</label>
						<select multiple name="problem_type[]" class="easyui-validatebox problem" style="width: 100%" required>
							<option value="">-- select problem --</option>
						</select>
					</p>
					<p>
						<label>Assign To</label>
						<select name="teknisi_1" class="easyui-validatebox flm" style="width: 100%" required>
							<option value="">-- select flm --</option>
						</select>
					</p>
					<p>
						<label>Security & Guard</label>
						<select name="guard" class="easyui-validatebox guard" style="width: 100%" required>
							<option value="">-- select guard --</option>
						</select>
					</p>
				</fieldset>
			</form>
		</div>
		
		<div class="clear"></div>
	</article>
	<script>
		jq341 = jQuery.noConflict(true);
		
		var tables = jq341('#example').DataTable({
			serverSide: true,
			ordering: false,
			searching: false,
			lengthChange: false,
			ajax: {
				url: '<?=base_url()?>ticket_slm/json',
				data: function(data) {
					// // Read values
					// var values = jq341('.datepicker_search').val();
					// // alert(values);

					// // Append to data
					// if(values=="") {
						// data.search.value = "<?=date('Y-m-d')?>";
					// } else {
						// data.search.value = values;
					// }
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
					// "render": function ( data, type, row ) {
						// return  "(H-"+data+") RUN NUMBER "+row['run_number'];
					// },
					"className": "text-center",
					"targets": 0,
					"searchable": false
				},
				{
					// "render": function ( data, type, row ) {
						// return  formatDate(data);
					// },
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
					// "render": function ( data, type, row ) {
						// if(data=="0") {
							// return "<span style='font-weight: normal'>"+data+"</span>";
						// } else {
							// return "<span style='font-weight: bold'>"+data+"</span>";
						// }
					// },
					"className": "text-center",
					"targets": 3,
					"searchable": false
				},
				{
					"className": "text-center",
					"targets": 4,
					"searchable": false
				},
				{
					"className": "text-center",
					"targets": 5,
					"searchable": false
				},
				{
					"render": function ( data, type, row ) {
						// var button = ''+
							// '<button type="button" class="button yellow" style="font-size: 12px" onClick="window.location.href=\'<?=base_url()?>runsheet/detail_runsheet/'+row['id']+'\'" title=""><span class="">Edit</span></button> | '+
							// '<button type="button" class="button red" style="font-size: 12px" onClick="button_delete(\'<?=base_url()?>ticket/delete\', \''+row['id']+'\')" title=""><span class="">Delete</span></button>';
						if(row['teknisi_slm']=="" || row['teknisi_slm']==null) {
							var button = ''+
								'<button type="button" class="button yellow" style="font-size: 12px" onClick="openModalEdit(\''+row['id_ticket']+'\')" title=""><span class="">Assign SLM PIC</span></button>'; 
						} else {
							var button = 'Assigned '+row['teknisi_slm']+''; 

						}
						return button;
					},
					"className": "text-center",
					"targets": 6,
					"searchable": false
				}
			],
			"columns": [
				{"data": "wsid", width:100},
				{"data": "id_ticket", width:100},
				{"data": "act", width:100},
				{"data": "model", width:100},
				{"data": "lokasi", width:100},
				{"data": "problem", width:100},
				{"data": "id", width:100}
			]
		});
		
		function button_delete(url, id) {
			$ = jq341;
			$.confirm({
				title: 'Confirm!',
				content: 'Yakin ingin menghapus data ini?!',
				buttons: {
					confirm: function () {
						$.ajax({
							url: url,
							dataType: 'html',
							type: 'POST',
							data: {id: id},
							success: function(data) {
								// console.log(data);
								if(data=="success") {
									tables.draw();
									self.close();
								}
							}
						});
					},
					cancel: function () {
						
					}
				}
			});
			
			
		}
		
		function formatDate (input) {
			var datePart = input.match(/\d+/g),
			year = datePart[0], // get only two digits
			month = datePart[1], day = datePart[2];

			return day+'-'+month+'-'+year;
		}
		
		function openModalEdit(id) {
			$ = jq341;
			var orig = $("#html_content").find(".mysets-area");
			var content = $(orig).clone().show();
			
			$.confirm({
				title: '',
				content: content,
				contentLoaded: function(data, status, xhr){
					// this.setContentAppend(' <b>' + status);
				},
				buttons: {
					Submit: function () {
						var self = this;
						vendor = this.$content.find('.vendor').val();
						flm = this.$content.find('.flm').val();
						guard = this.$content.find('.guard').val();
						
						if(vendor=="") { alert("Mohon isi vendor!"); return false; }
						if(flm=="") { alert("Mohon isi flm!"); return false; }
						if(guard=="") { alert("Mohon isi guard!"); return false; }
						
						var data = {
							id_ticket: id,
							vendor: vendor,
							teknisi_1: flm,
							guard: guard
						};
						
						console.log(data);
						
							$.ajax({
								url: '<?=base_url()?>ticket_slm/save_data2',
								dataType: 'html',
								type: 'POST',
								data: data,
								success: function(data) {
									console.log(data);
									if(data=="success") {
										tables.draw();
										self.close();
									}
								}
							});
						
						return false;
					},
					Close: function () {
						// alert("SUCCESS");
					}
				},
				onContentReady: function () {
					// bind to events
					var jc = this;
					
					jc.$content.find('.flm').select2({
						tags: false, tokenSeparators: [','], width: '100%',
						ajax: {
							url: '<?php echo base_url().'ticket/select_flm'?>', dataType: 'json', delay: 250, type: "POST",
							data: function(params) { return { search: params.term } },
							processResults: function (data, page) { return { results: data }; }
						}, maximumSelectionLength: 3,
						createTag: function (params) { var term = $.trim(params.term);
							if (term === '') { return null; }
							return { id: term, text: term + ' (add new)' };
						}
					});
					
					jc.$content.find('.guard').select2({
						tags: false, tokenSeparators: [','], width: '100%',
						ajax: {
							url: '<?php echo base_url().'security/suggest_security'?>', dataType: 'json', delay: 250, type: "POST",
							data: function(params) { return { search: params.term } },
							processResults: function (data, page) { return { results: data }; }
						}, maximumSelectionLength: 3,
						createTag: function (params) { var term = $.trim(params.term);
							if (term === '') { return null; }
							return { id: term, text: term + ' (add new)' };
						}
					});
				}
			});
		}
		
		function openModalTambah() {
			$ = jq341;
			var orig = $("#html_content").find(".mysets-area");
			var content = $(orig).clone().show();
			
			$.confirm({
				title: '',
				content: content,
				contentLoaded: function(data, status, xhr){
					// this.setContentAppend(' <b>' + status);
				},
				buttons: {
					Submit: function () {
						var self = this;
						client = this.$content.find('.client').val();
						ticket = this.$content.find('.ticket').val();
						datetime = this.$content.find('.datetime').val();
						problem = this.$content.find('.problem').val();
						flm = this.$content.find('.flm').val();
						guard = this.$content.find('.guard').val();
						
						if(client=="") { alert("Mohon isi client!"); return false; }
						if(ticket=="") { alert("Mohon isi ticket!"); return false; }
						if(datetime=="") { alert("Mohon isi tanggal!"); return false; }
						if(problem=="") { alert("Mohon isi problem!"); return false; }
						if(flm=="") { alert("Mohon isi flm!"); return false; }
						if(guard=="") { alert("Mohon isi guard!"); return false; }
						
						var data = {
							id_bank: client,
							ticket_client: ticket,
							email_date: datetime,
							problem_type: problem,
							teknisi_1: flm,
							guard: guard
						};
						
						console.log(data);
						
							$.ajax({
								url: '<?=base_url()?>ticket/save_data2',
								dataType: 'html',
								type: 'POST',
								data: data,
								success: function(data) {
									console.log(data);
									if(data=="success") {
										tables.draw();
										self.close();
									}
								}
							});
						
						return false;
					},
					Close: function () {
						// alert("SUCCESS");
					}
				},
				onContentReady: function () {
					// bind to events
					var jc = this;
					
					var im = new Inputmask();
					im.mask(jc.$content.find('#email_time'));
					
					jc.$content.find('.client').select2({
						tags: false, tokenSeparators: [','], width: '100%',
						ajax: {
							url: '<?php echo base_url().'select/select_client'?>', dataType: 'json', delay: 250, type: "POST",
							data: function(params) { return { search: params.term } },
							processResults: function (data, page) { return { results: data }; }
						}, maximumSelectionLength: 3,
						createTag: function (params) { var term = $.trim(params.term);
							if (term === '') { return null; }
							return { id: term, text: term + ' (add new)' };
						}
					});
					
					jc.$content.find('.problem').select2({
						tags: false, tokenSeparators: [','], width: '100%',
						ajax: {
							url: '<?php echo base_url().'ticket/select_problem_flm'?>', dataType: 'json', delay: 250, type: "POST",
							data: function(params) { return { search: params.term } },
							processResults: function (data, page) { return { results: data }; }
						}, maximumSelectionLength: 3,
						createTag: function (params) { var term = $.trim(params.term);
							if (term === '') { return null; }
							return { id: term, text: term + ' (add new)' };
						}
					});
					
					jc.$content.find('.flm').select2({
						tags: false, tokenSeparators: [','], width: '100%',
						ajax: {
							url: '<?php echo base_url().'ticket/select_flm'?>', dataType: 'json', delay: 250, type: "POST",
							data: function(params) { return { search: params.term } },
							processResults: function (data, page) { return { results: data }; }
						}, maximumSelectionLength: 3,
						createTag: function (params) { var term = $.trim(params.term);
							if (term === '') { return null; }
							return { id: term, text: term + ' (add new)' };
						}
					});
					
					jc.$content.find('.guard').select2({
						tags: false, tokenSeparators: [','], width: '100%',
						ajax: {
							url: '<?php echo base_url().'security/suggest_security'?>', dataType: 'json', delay: 250, type: "POST",
							data: function(params) { return { search: params.term } },
							processResults: function (data, page) { return { results: data }; }
						}, maximumSelectionLength: 3,
						createTag: function (params) { var term = $.trim(params.term);
							if (term === '') { return null; }
							return { id: term, text: term + ' (add new)' };
						}
					});
					
					
						
					// jq341(document).scannerDetection({
						// timeBeforeScanTest: 1000, // wait for the next character for upto 200ms
						// avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
						// preventDefault: true,
						// endChar: [13],
						// onComplete: function(barcode, qty) {
							// // jc.$content.find('.pilih_mesin').show();
							// // jc.$content.find('.seal').val(barcode);
							// $.post('<?php echo base_url().'cpc_prepared/check_seal'?>', { 
								// value: barcode
							// }).done(function( data ) {
									// data = JSON.parse(data);
									// console.log(data.debug);
									// if(data.result=="0") {
										// jc.$content.find('.pilih_mesin').show();
										// jc.$content.find('.seal').val(barcode);
									// } else {
										// jq341.notify("SEAL ALREADY USED!", "error");
									// }
								// }
							// );
						// },
						// onError: function(string, qty) {
							
						// }
					// });
					
					// jc.$content.find('.bank').select2({
						// tags: false, tokenSeparators: [','], width: '100%',
						// ajax: {
							// url: '<?php echo base_url().'cpc_prepared/select_bank'?>', dataType: 'json', delay: 250, type: "POST",
							// data: function(params) { return { search: params.term } },
							// processResults: function (data, page) { return { results: data }; }
						// }, maximumSelectionLength: 3,
						// createTag: function (params) { var term = $.trim(params.term);
							// if (term === '') { return null; }
							// return { id: term, text: term + ' (add new)' };
						// }
					// });
					
					// jc.$content.find('.cashier').select2({
						// tags: false, tokenSeparators: [','], width: '100%',
						// ajax: {
							// url: '<?php echo base_url().'cpc_prepared/get_data_kasir'?>', dataType: 'json', delay: 250, type: "POST",
							// data: function(params) { return { 
								// search: params.term
							// } },
							// processResults: function (data, page) { return { results: data }; }
						// }, maximumSelectionLength: 3,
						// createTag: function (params) { var term = $.trim(params.term);
							// if (term === '') { return null; }
							// return { id: term, text: term + ' (add new)' };
						// }
					// });
					
					// this.$content.find('.table').on("focus", function(){
						// jq341(document).scannerDetection(false);
					// });
					
					// this.$content.find('.value').on("focus", function(){
						// jq341(document).scannerDetection(false);
						// var im = new Inputmask();
						// im.mask(jc.$content.find('#value'));
					// });
					
					// this.$content.find('form').on('submit', function (e) {
						// // if the user submits the form by pressing enter in the field.
						// e.preventDefault();
						// jc.$$formSubmit.trigger('click'); // reference the button and click it
					// });
				}
			});
		}
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>