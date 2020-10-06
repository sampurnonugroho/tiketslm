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
		
		
		.view {
			margin: auto;
			width: 100%;
		}

		.wrapper {
			position: relative;
			overflow: auto;
			border: 1px solid black;
			white-space: nowrap;
		}

		.sticky-col {
			position: sticky;
			position: -webkit-sticky;
			background-color: white;
		}

		.first-col {
			width: 50px;
			min-width: 50px;
			max-width: 50px;
			left: 0px;
		}

		.second-col {
			width: 150px;
			min-width: 150px;
			max-width: 150px;
			left: 50px;
		}
		
		button.red, .red button, .big-button.red, .red .big-button {
			color: white;
			border-color: #bf3636 #5d0000 #0a0000;
			background: #790000 url(../images/old-browsers-bg/button-element-red-bg.png) repeat-x top;
			background: -moz-linear-gradient(top,white,#ca3535 4%,#790000);
			background: -webkit-gradient(linear,left top, left bottom,from(white),to(#790000),color-stop(0.03, #ca3535));
		}
		
		button.yellow, .yellow button, .big-button.yellow, .yellow .big-button {
			color: black;
			border-color: #ffcc00 #ffcc00 #ffcc00;
			background: #790000 url(../images/old-browsers-bg/button-element-yellow-bg.png) repeat-x top;
			background: -moz-linear-gradient(top,white,#ffff00 4%,#ffcc00);
			background: -webkit-gradient(linear,left top, left bottom,from(white),to(#ffcc00),color-stop(0.03, #ffff00));
		}
		
		button.green, .green button, .big-button.green, .green .big-button {
			color: black;
			border-color: #99ff33 #99ff33 #99ff33;
			background: #790000 url(../images/old-browsers-bg/button-element-green-bg.png) repeat-x top;
			background: -moz-linear-gradient(top,white,#99ff66 4%,#33cc33);
			background: -webkit-gradient(linear,left top, left bottom,from(white),to(#33cc33),color-stop(0.03, #99ff66));
		}
	</style>
	
	<article class="container_12">
		
		<section class="grid_12">
			<div class="preview_pdf" hidden>
				<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview' type="button">Close</button>
				<iframe id="preview" name="preview" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
			</div>
			<div style="float: right">
				<label for="search">Search by date : </label>
				<input type="text" name="simple-calendar" id="search" class="datepicker_search">
			</div>
			<div class="widget_wrap preview_table">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6><?=ucwords(str_replace("_", " ", $active_menu))?> Data</h6>
				</div>
				<div class="widget_content" id="content_table">
					<div>
						<?php 
							foreach($data_run as $d) { 
						?>
								<table class="table">
									<tr>
										<th hidden>ID</th>
										<th>NOMOR POLISI</th>
										<th>CUSTODY</th>
										<th>GUARD</th>
									</tr>
									<tr>
										<td hidden><?=$d->id_cashtransit?></td>
										<td><?=$d->police_number?></td>
										<td><?=$d->nama_custody?></td>
										<td><?=$d->nama_security?></td>
									</tr>
								</table>
								
								<div class="view" style="margin-bottom: 20px">
									<div class="wrapper" style="margin-top: -20px">
										<table class="table">
											<thead>
												<tr>
													<th class="sticky-col first-col">No</th>
													<th>Layanan</th>
													<th>ID ATM/NO BOC</th>
													<th>Lokasi</th>
													<th>Bank</th>
													<th>Denom</th>
													<th>Jumlah</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													$no = 0;
													foreach($tess($d->id_cashtransit) as $r) {
														$no++;
												?>
														<tr <?php if($r->data_solve=="batal" && $r->cpc_process=="") { echo "style='color: red'"; }
																  if($r->data_solve!=="batal" && $r->cpc_process!=="") { echo "style='color: green'"; }?>>
															<td class="sticky-col first-col"><?=$no?></td>
															<td><?=($r->state=="ro_cit" ? "CASH PICKUP" : "REPLENISH")?></td>
															<td style="text-align: center"><?=$r->wsid?></td>
															<td><?=$r->lokasi_client?></td>
															<td><?=$r->nama_client?></td>
															<td><?=number_format($r->denom, 0, ',', '.')?></td>
															<td><?=number_format($r->total, 0, ',', '.')?></td>
															<td>
																<?php if(($r->data_solve=="batal" || $r->data_solve!=="batal") && $r->cpc_process=="") { ?>
																	<button type="button" class="red" onclick="openBatal(
																		'<?=$r->ids?>'
																	)" style="font-size: 10px" <?php if($r->data_solve=="batal") { echo "disabled"; } ?>>BATAL</button>
																<?php } ?>
															
																<?php if($r->data_solve!=="batal" && $r->cpc_process=="") { ?>
																	<button type="button" class="yellow" onclick="openPengalihan(
																		'<?=$r->ids?>'
																	)" style="font-size: 10px;" <?php if($r->data_solve=="batal") { echo "disabled"; } ?>>PENGALIHAN</button>
																<?php } ?>
																
																<?php if($r->data_solve!=="batal" && $r->cpc_process!=="") { ?>
																	<button type="button" class="green" onclick="" style="font-size: 10px;" <?php if($r->data_solve=="batal") { echo "hidden"; } ?>>DONE</button>
																<?php } ?>
															</td>
														</tr>
												<?php 
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
						<?php
							}
						?>
					</div>
				</div>
			</div>
		</section>
		
		<div id="html_content" hidden>
			<form class="form mysets-area">
				<fieldset>
					<p>
						<label>ID BANK</label>
						<select id="seal" class="js-example-basic-single2 full-width">- select cabang -</option></select>
					</p>
				</fieldset>
			</form>
		</div>
		
		<div id="html_content_verify" hidden>
			<form class="form mysets-area">
				<fieldset>
					<p>
						<label>ID BANK</label>
						<select id="sealxx" class="js-example-basic-single2xxx full-width">- select cabang -</option></select>
					</p>
				</fieldset>
			</form>
		</div>
	
		<div class="clear"></div>
	</article>
	
	
	<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
	<script src="<?=base_url()?>depend/js/jquery-ui-1.8.18.custom.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	<script src="<?=base_url()?>depend/js/full-calendar.jquery.js"></script>
	
	
	<script>
		jq341 = jQuery.noConflict(true);
		jq3412 = jQuery.noConflict(true);
		
		console.log(jq341().jquery);
		console.log(jq3412().jquery);
	
		// jq341('#example').DataTable({
			// serverSide: true,
			// ajax: {
				// url: '<?=base_url()?>seal/server_processing',
				// dataFilter: function(data){
					// console.log(data);
					// var json = jQuery.parseJSON( data );
					// json.recordsTotal = json.recordsTotal;
					// json.recordsFiltered = json.recordsFiltered;
					// json.data = json.data;

					// return JSON.stringify( json ); // return JSON string
				// }
			// }
		// });
		
		// $('.dataTables_filter').addClass('pull-left');
		
			
		jq341( ".datepicker_search" ).datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			onClose: function(dateText, inst) { 
				// $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
				// alert(new Date(inst.selectedYear, inst.selectedMonth, 1));
				// alert(dateText);
				
				jq341('#content_table div').remove();
				
				jq341.confirm({
					draggable: false,
					title: false,
					theme: 'light',
					content: "Please wait...",
					buttons: {
						yes: {
							isHidden: true, // hide the button
							keys: ['y'],
							action: function () {
								$.alert('Critical action <strong>was performed</strong>.');
							}
						}
					},
					onContentReady: function () {
						self = this;
						self.showLoading();
						
						$.ajax({
							url     : "<?=base_url()?>all_runsheet/get_data",
							type    : "POST",
							data    : {date: dateText},
							dataType: "html",
							timeout : 10000,
							cache   : false,
							success : function(json){
								jq341('#content_table').html(json);
								self.close();
							},
							error   : function(jqXHR, status, error){
								// alert(JSON.stringify(error));
								if(status==="timeout") {
									$.ajax(this);
									return;
								}
							}
						});
						
					}
				});
				
			}
		});  
		
		function openBatal2(id) {
			alert(id);
		}
		
		function openBatal(id) {
			$ = jq341;
			var orig = $("#html_content").find(".mysets-area");
			var content = $(orig).clone().show();
			
			$.confirm({
				title: 'Info Pembatalan!',
				content: "Are you sure?",
				contentLoaded: function(data, status, xhr){
					// this.setContentAppend(' <b>' + status);
				},
				buttons: {
					Submit: function () {
						var jc = this;
						
						var data = {
							id: id,
							action: 'batal'
						};
						
						$.ajax({
							url: '<?=base_url()?>all_runsheet/save_batal',
							dataType: 'html',
							type: 'POST',
							data: data,
							success: function(data) {
								// console.log(data);
								if(data=="success") {
									window.location.reload();
								} else {
									alert("DATA ALREADY EXIST!");
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
					
					this.$content.find('form').on('submit', function (e) {
						// if the user submits the form by pressing enter in the field.
						e.preventDefault();
						jc.$$formSubmit.trigger('click'); // reference the button and click it
					});
				}
			});
		}
		
		
		
		function openPengalihan(id) {
			// alert(jq341(".datepicker_search").val());
			
			$ = jq3412;
			var orig = $("#html_content").find(".mysets-area");
			var content = $(orig).clone().show();
			
			$.confirm({
				title: 'Info Pengalihan!',
				content: content,
				contentLoaded: function(data, status, xhr){
					// this.setContentAppend(' <b>' + status);
				},
				buttons: {
					Submit: function () {
						var self = this;
						
						var data = {
							id: id,
							id_bank: jq341(".js-example-basic-single2 option:selected").val(),
							action: 'pengalihan'
						};
						
						console.log(data);
						
							$.ajax({
								url: '<?=base_url()?>all_runsheet/update_pengalihan',
								dataType: 'html',
								type: 'POST',
								data: data,
								success: function(data) {
									console.log(data);
									if(data=="success") {
										
										jq341('#content_table div').remove();
										$.ajax({
											url     : "<?=base_url()?>all_runsheet/get_data",
											type    : "POST",
											data    : {date: jq341(".datepicker_search").val()},
											dataType: "html",
											timeout : 10000,
											cache   : false,
											success : function(json){
												jq341('#content_table').html(json);
												self.close();
											},
											error   : function(jqXHR, status, error){
												// alert(JSON.stringify(error));
												if(status==="timeout") {
													$.ajax(this);
													return;
												}
											}
										});
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
					
					$('.js-example-basic-single2').select2({
						tokenSeparators: [','],
						ajax: {
							dataType: 'json',
							url: '<?php echo base_url().'all_runsheet/select_client'?>',
							delay: 250,
							type: "POST",
							data: function(params) {
								return {
									id: id,
									search: params.term
								}
							},
							processResults: function (data, page) {
								return {
									results: data
								};
							}
						}
					});
					
					var jc = this;
					this.$content.find('form').on('submit', function (e) {
						// if the user submits the form by pressing enter in the field.
						e.preventDefault();
						jc.$$formSubmit.trigger('click'); // reference the button and click it
					});
				}
			});
		}
		
		function openModalAudit(id, seal, bank, denom, type, nama, table, value) {
			$ = jq341;
			
			$.confirm({
				title: 'Info!',
				content: "Apakah anda yakin akan memverifikasi data ini?",
				contentLoaded: function(data, status, xhr){
					// this.setContentAppend(' <b>' + status);
				},
				buttons: {
					Yes: function () {
						
						var orig = $("#html_content_verify").find(".mysets-area");
						var content = $(orig).clone().show();
						
						$.confirm({
							title: 'Info Pembatalan!',
							content: content,
							contentLoaded: function(data, status, xhr){
								// this.setContentAppend(' <b>' + status);
							},
							buttons: {
								Submit: function () {
									var jc = this;
									seal = this.$content.find('.seal').val();
									status = this.$content.find('.status').val();
									remark = this.$content.find('.remark').val();
									
									var data = {
										seal: seal,
										status: status,
										remark: remark
									};
									
									$.ajax({
										url: '<?=base_url()?>cpc_prepared/save_data_audit',
										dataType: 'html',
										type: 'POST',
										data: data,
										success: function(data) {
											console.log(data);
											if(data=="success") {
												window.location.reload();
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
									
								jq341(document).scannerDetection({
									timeBeforeScanTest: 1000, // wait for the next character for upto 200ms
									avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
									preventDefault: true,
									endChar: [13],
									onComplete: function(barcode, qty) {
										jc.$content.find('.pilih_mesin').show();
										jc.$content.find('.seal').val(barcode);
									},
									onError: function(string, qty) {
										
									}
								});
								
								jc.$content.find('.bank').select2({
									tags: false, tokenSeparators: [','], width: '100%',
									ajax: {
										url: '<?php echo base_url().'cpc_prepared/select_bank'?>', dataType: 'json', delay: 250, type: "POST",
										data: function(params) { return { search: params.term } },
										processResults: function (data, page) { return { results: data }; }
									}, maximumSelectionLength: 3,
									createTag: function (params) { var term = $.trim(params.term);
										if (term === '') { return null; }
										return { id: term, text: term + ' (add new)' };
									}
								});
								
								jc.$content.find('.cashier').select2({
									tags: false, tokenSeparators: [','], width: '100%',
									ajax: {
										url: '<?php echo base_url().'cpc_prepared/get_data_kasir'?>', dataType: 'json', delay: 250, type: "POST",
										data: function(params) { return { 
											search: params.term
										} },
										processResults: function (data, page) { return { results: data }; }
									}, maximumSelectionLength: 3,
									createTag: function (params) { var term = $.trim(params.term);
										if (term === '') { return null; }
										return { id: term, text: term + ' (add new)' };
									}
								});
								
								this.$content.find('.remark').on("focus", function(){
									jq341(document).scannerDetection(false);
								});
								
								this.$content.find('.value').on("focus", function(){
									jq341(document).scannerDetection(false);
									var im = new Inputmask();
									im.mask(jc.$content.find('#value'));
								});
								
								var im = new Inputmask();
								im.mask(jc.$content.find('#value'));
								
								jc.$content.find('.pilih_mesin').show();
								jc.$content.find('.seal').val(seal);
								newoption = $("<option selected='selected'></option>").val(bank).text(bank);
								this.$content.find('.bank').append(newoption).trigger('change');
								jc.$content.find('.denom option[value='+denom+']').attr('selected','selected');
								jc.$content.find('.type option[value='+type+']').attr('selected','selected');
								newoption = $("<option selected='selected'></option>").val(nama).text(nama);
								this.$content.find('.cashier').append(newoption).trigger('change');
								jc.$content.find('.table').val(table);
								jc.$content.find('.value').val(value);
								
								this.$content.find('form').on('submit', function (e) {
									// if the user submits the form by pressing enter in the field.
									e.preventDefault();
									jc.$$formSubmit.trigger('click'); // reference the button and click it
								});
							}
						});
					},
					Close: function () {
					}
				}
			});
		}
		
		function openModalAction(id) {
			$ = jq341;
			var orig = $("#html_content").find(".mysets-area");
			var content = $(orig).clone().show();
			
			$.confirm({
				title: 'Info!',
				content: content,
				contentLoaded: function(data, status, xhr){
					// this.setContentAppend(' <b>' + status);
				},
				buttons: {
					Submit: function () {
						var jc = this;
						// seal = this.$content.find('.seal').val();
						// bank = this.$content.find('.bank').val();
						// denom = this.$content.find('.denom').val();
						// type = this.$content.find('.type').val();
						// cashier = this.$content.find('.cashier').val();
						// table = this.$content.find('.table').val();
						// value = this.$content.find('.value').val();
						
						// var data = {
							// seal: seal,
							// bank: bank,
							// denom: denom,
							// type: type,
							// cashier: cashier,
							// table: table,
							// value: value
						// };
						
						// $.ajax({
							// url: '<?=base_url()?>cpc_prepared/save_data',
							// dataType: 'html',
							// type: 'POST',
							// data: data,
							// success: function(data) {
								// // console.log(data);
								// if(data=="success") {
									// window.location.reload();
								// } else {
									// alert("DATA ALREADY EXIST!");
								// }
							// }
						// });
						
						// return false;
					},
					Close: function () {
						// alert("SUCCESS");
					}
				},
				onContentReady: function () {
					// bind to events
					var jc = this;
					
					this.$content.find('form').on('submit', function (e) {
						// if the user submits the form by pressing enter in the field.
						e.preventDefault();
						jc.$$formSubmit.trigger('click'); // reference the button and click it
					});
				}
			});
		}
	</script>
@endsection