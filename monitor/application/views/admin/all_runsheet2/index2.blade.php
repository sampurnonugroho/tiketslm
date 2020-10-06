@extends('layouts.master')

@section('content')
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>depend/jquery-confirm/css/jquery-confirm.css"/>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/datatables/datatables.min.css"/>
 
	<script type="text/javascript" src="<?=base_url()?>/assets/datatables/datatables.min.js"></script>
	
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
						<div class="float-right">
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
								<th>NO</th>
								<th>TANGGAL</th>
								<th>BANK</th>
								<th>ID ATM</th>
								<th>DENOM</th>
								<th>NO TABLE</th>
								<th>CASHIER</th>
								<th>AKSI</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								foreach($data_prepared as $r) {
									$no++;
							?>
									<tr>
										<td align="center"><?=$no?></td>
										<td align="center"><?=date("d-m-Y", strtotime($r->tanggal))?></td>
										<td align="center"><?=$r->bank?></td>
										<td align="center"><?=$r->wsid?></td>
										<td align="center"><?=$r->denom?></td>
										<td align="center"><?=$r->no_table?></td>
										<td align="center"><?=$r->nama?></td>
										<td align="center"><button onclick="window.location.href='<?=base_url()?>cpc_prepared/detail/<?=$r->id?>'" style="font-size: 10px">DETAIL</button></td>
									</tr>
							<?php 
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
		
		<div id="html_content" hidden>
			<form class="form mysets-area">
				<fieldset>
					<p>
						<label>BANK</label>
						<select name="bank" class="bank full-width" required>
						</select>
					</p>
					<p class="pilih_mesin" hidden>
						<label>MESIN</label>
						<select name="mesin" class="mesin full-width" required>
						</select>
					</p>
					<p class="pilih_mesin" hidden>
						<label>NO TABLE</label>
						<input type="text" id="table" class="table full-width">
					</p>
					<p class="pilih_mesin" hidden>
						<label>NAMA CASHIER</label>
						<select name="bank" class="cashier full-width" required>
						</select>
					</p>
				</fieldset>
			</form>
		</div>
	
		<div class="clear"></div>
	</article>
	<script>
		jq341 = jQuery.noConflict(true);
	
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
						bank = this.$content.find('.bank').val();
						mesin = this.$content.find('.mesin').val();
						table = this.$content.find('.table').val();
						cashier = this.$content.find('.cashier').val();
						var data = {
							bank: bank,
							mesin: mesin,
							table: table,
							cashier: cashier
						};
						
						$.ajax({
							url: '<?=base_url()?>cpc_prepared/save_data',
							dataType: 'html',
							type: 'POST',
							data: data,
							success: function(data) {
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
					}).on('select2:select', function (evt) {
						jc.$content.find('.pilih_mesin').show();
						var bank = jq341(".bank option:selected").text();
						
						jc.$content.find('.mesin').select2({
							tags: false, tokenSeparators: [','], width: '100%',
							ajax: {
								url: '<?php echo base_url().'cpc_prepared/select_mesin'?>', dataType: 'json', delay: 250, type: "POST",
								data: function(params) { return { 
									search: params.term,
									bank: bank
								} },
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
									search: params.term,
									bank: bank
								} },
								processResults: function (data, page) { return { results: data }; }
							}, maximumSelectionLength: 3,
							createTag: function (params) { var term = $.trim(params.term);
								if (term === '') { return null; }
								return { id: term, text: term + ' (add new)' };
							}
						});
					});
					
					
					this.$content.find('.name').focus();
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