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
	</style>
	
	<article class="container_12">
		
		<section class="grid_12">
		<div class="block-border">
			<div class="block-content no-title dark-bg">
				<div id="control-bar">
					<div class="container_16">
						<div class="float-left">
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
					<table hidden id="exampleX" class="display" style="width:100%">
						<thead>
							<tr>
								<th>NO</th>
								<th>BANK</th>
								<th>DENOM</th>
								<th>VALUE</th>
								<th>SEAL</th>
								<th>TANGGAL</th>
								<th>TIME</th>
								<th>TYPE</th>
								<th>TABLE</th>
								<th>NAMA</th>
								<th>STATUS</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								foreach($data_prepared as $r) {
									$no++;
									if($r->status=="ready" && $r->status=="used") {
							?>
										<tr>
											<td align="center"><?=$no?></td>
											<td align="center"><?=$r->bank?></td>
											<td align="center"><?=number_format($r->denom, 0, ",", ",")?></td>
											<td align="center"><?=number_format($r->value, 0, ",", ",")?></td>
											<td align="center"><?=$r->seal?></td>
											<td align="center"><?=date("d-m-Y", strtotime($r->date_time))?></td>
											<td align="center"><?=date("H:i", strtotime($r->date_time))?></td>
											<td align="center"><?=$r->type_cassette?></td>
											<td align="center"><?=$r->no_table?></td>
											<td align="center"><?=$r->nama?></td>
											<td align="center"><?=strtoupper($r->status)?></td>
										</tr>
							<?php 
									} else {
							?>
										<tr>
											<td align="center"><?=$no?></td>
											<td align="center"><?=$r->seal?></td>
											<td align="center"><?=$r->status?></td>
											<td align="center"><?=$r->remark?></td>
										</tr>
							<?php 
									}
								}
							?>
						</tbody>
					</table>
					
					<table class="display" style="float: right; width:65%; font-weight: bold; font-size: 16px">
						<tr>
							<td>JUMLAH READY</td>
							<td>:</td>
							<td style="color: blue"><?=$jumlah_ready?></td>
							<td>JUMLAH USED</td>
							<td>:</td>
							<td style="color: red"><?=$jumlah_used?></td>
							<td>JUMLAH LAIN-LAIN</td>
							<td>:</td>
							<td style="color: red"><?=$jumlah_lain?></td>
						</tr>
					</table>
					<table id="example" class="display" style="width:100%">
						<thead>
							<tr>
								<th>NO</th>
								<th>TANGGAL</th>
								<th>BANK</th>
								<th>DENOM</th>
								<th>VALUE</th>
								<th>SEAL</th>
								<th>TIME</th>
								<th>TYPE</th>
								<th>TABLE</th>
								<th>NAMA</th>
								<th>STATUS</th>
							</tr>
						</thead>
						<tfoot>
						</tfoot>
					</table>
				</div>
			</div>
		</section>
		
		<div id="html_content" hidden>
			<form class="form mysets-area">
				<fieldset>
					<p>
						<label>SEAL</label>
						<input type="text" id="seal" class="seal full-width" readonly>
					</p>
					<p class="pilih_mesin" hidden>
						<label>BANK</label>
						<select name="bank" class="bank full-width" required>
							<option value=""> - SELECT BANK - </option>
						</select>
					</p>
					<p class="pilih_mesin" hidden>
						<label>DENOM</label>
						<select name="mesin" class="denom full-width" required>
							<option value=""> - SELECT DENOM - </option>
							<option value="50000">50.000</option>
							<option value="100000">100.000</option>
						</select>
					</p>
					<p class="pilih_mesin" hidden>
						<label>TYPE</label>
						<select name="type" class="type full-width">
							<option value=""> - SELECT TYPE - </option>
							<option VALUE="WINCORD"> WINCORD </option>
							<option VALUE="YOSUNG"> YOSUNG </option>
						</select>
					</p>
					<p class="pilih_mesin" hidden>
						<label>NAMA CASHIER</label>
						<select name="bank" class="cashier full-width" required>
							<option value=""> - SELECT CASHIER - </option>
						</select>
					</p>
					<p class="pilih_mesin" hidden>
						<label>NO TABLE</label>
						<input type="text" id="table" class="table full-width">
					</p>
					<p class="pilih_mesin" hidden>
						<label>VALUE</label>
						<input data-inputmask="'alias': 'decimal', 'groupSeparator': ','" type="text" id="value" class="value full-width">
					</p>
				</fieldset>
			</form>
		</div>
	
		<div class="clear"></div>
	</article>
	<script>
		jq341 = jQuery.noConflict(true);
		
		console.log('<?=base_url()?>cpc_prepared/server_processing');
	
		jq341('#example').DataTable({
			serverSide: true,
			ajax: {
				url: '<?=base_url()?>cpc_prepared/server_processing',
				dataFilter: function(data){
					console.log(data);
					var json = jQuery.parseJSON( data );
					json.recordsTotal = json.recordsTotal;
					json.recordsFiltered = json.recordsFiltered;
					json.data = json.data;

					return JSON.stringify( json ); // return JSON string
				}
			}
		});
		
		$('.dataTables_filter').addClass('pull-left');
		
		function openModalTambah() {
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
						seal = this.$content.find('.seal').val();
						bank = this.$content.find('.bank').val();
						denom = this.$content.find('.denom').val();
						type = this.$content.find('.type').val();
						cashier = this.$content.find('.cashier').val();
						table = this.$content.find('.table').val();
						value = this.$content.find('.value').val();
						
						var data = {
							seal: seal,
							bank: bank,
							denom: denom,
							type: type,
							cashier: cashier,
							table: table,
							value: value
						};
						
						$.ajax({
							url: '<?=base_url()?>cpc_prepared/save_data',
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
						
					jq341(document).scannerDetection({
						timeBeforeScanTest: 1000, // wait for the next character for upto 200ms
						avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
						preventDefault: true,
						endChar: [13],
						onComplete: function(barcode, qty) {
							// jc.$content.find('.pilih_mesin').show();
							// jc.$content.find('.seal').val(barcode);
							$.post('<?php echo base_url().'cpc_prepared/check_seal'?>', { 
								value: barcode
							}).done(function( data ) {
									console.log(data);
									if(data=="0") {
										jc.$content.find('.pilih_mesin').show();
										jc.$content.find('.seal').val(barcode);
									} else {
										jq341.notify("SEAL ALREADY USED!", "error");
									}
								}
							);
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
					
					this.$content.find('.table').on("focus", function(){
						jq341(document).scannerDetection(false);
					});
					
					this.$content.find('.value').on("focus", function(){
						jq341(document).scannerDetection(false);
						var im = new Inputmask();
						im.mask(jc.$content.find('#value'));
					});
					
					this.$content.find('form').on('submit', function (e) {
						// if the user submits the form by pressing enter in the field.
						e.preventDefault();
						jc.$$formSubmit.trigger('click'); // reference the button and click it
					});
				}
			});
		}
		
		function openModalEdit(id, seal, bank, denom, type, nama, table, value) {
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
						seal = this.$content.find('.seal').val();
						bank = this.$content.find('.bank').val();
						denom = this.$content.find('.denom').val();
						type = this.$content.find('.type').val();
						cashier = this.$content.find('.cashier').val();
						table = this.$content.find('.table').val();
						value = this.$content.find('.value').val();
						
						var data = {
							seal: seal,
							bank: bank,
							denom: denom,
							type: type,
							cashier: cashier,
							table: table,
							value: value
						};
						
						$.ajax({
							url: '<?=base_url()?>cpc_prepared/save_data',
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
					
					this.$content.find('.table').on("focus", function(){
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
		}
		
		
		
		function library_select(element, url) {
			$ = jq341;
			
			
		}
	</script>
@endsection