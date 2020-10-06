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
								<th>SEAL</th>
								<th>VALUE</th>
								<th>TYPE CASSETE</th>
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
										<td align="center"><?=$r->seal?></td>
										<td align="center">Rp. <?=number_format($r->value, 0, ",", ",")?></td>
										<td align="center"><?=strtoupper($r->type_cassette)?></td>
										<td align="center">
											<button type="button" onclick="openModalEdit('<?=$r->id?>', '<?=$r->seal?>', '<?=$r->value?>', '<?=$r->type_cassette?>')" style="font-size: 10px">EDIT</button>
											<button type="button" class="red" onClick="openDelete('<?php echo $r->id;?>', '<?php echo base_url();?>cpc_prepared/delete_detail')" style="font-size: 10px">DELETE</button>
										</td>
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
						<label>SEAL</label>
						<input type="text" id="seal" class="seal full-width" readonly>
					</p>
					<p class="pilih_mesin" hidden>
						<label>VALUE</label>
						<input data-inputmask="'alias': 'decimal', 'groupSeparator': ','" type="text" id="value" class="value full-width">
					</p>
					<p class="pilih_mesin" hidden>
						<label>TYPE</label>
						<select name="type" class="type full-width">
							<option value=""> - SELECT TYPE - </option>
							<option VALUE="cassette"> CASSETTE </option>
							<option VALUE="reject"> REJECT </option>
						</select>
					</p>
				</fieldset>
			</form>
		</div>
	
		<div class="clear"></div>
	</article>
	<script>
		jq341 = jQuery.noConflict(true);
		var selector = document.getElementById("value");
	
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
		
		function openModalEdit(id, seal, value, type_cassette) {
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
						value = this.$content.find('.value').val();
						type = this.$content.find('.type').val();
						var data = {
							id_detail: "<?=$id_detail?>",
							seal: seal,
							value: value,
							type: type
						};
						
						// console.log(data);
						
						$.ajax({
							url: '<?=base_url()?>cpc_prepared/save_data_detail',
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
						
					}
				},
				onContentReady: function () {
					var jc = this;
					var im = new Inputmask();
					im.mask(jc.$content.find('#value'));
					jc.$content.find('.pilih_mesin').show();
					
					jc.$content.find('.seal').val(seal);
					jc.$content.find('.value').val(value);
					jc.$content.find('.type option[value='+type_cassette+']').attr('selected','selected');
				}
			});
		}
		
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
						value = this.$content.find('.value').val();
						type = this.$content.find('.type').val();
						var data = {
							id_detail: "<?=$id_detail?>",
							seal: seal,
							value: value,
							type: type
						};
						
						// console.log(data);
						
						$.ajax({
							url: '<?=base_url()?>cpc_prepared/save_data_detail',
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
					
					jq341(document).scannerDetection({
						timeBeforeScanTest: 1000, // wait for the next character for upto 200ms
						avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
						preventDefault: true,
						endChar: [13],
						onComplete: function(barcode, qty) {
							jc.$content.find('.pilih_mesin').show();
							jc.$content.find('.seal').val(barcode);
							jc.$content.find('.value').focus();
						},
						onError: function(string, qty) {
							
						}
					});
					
					this.$content.find('.value').on("focus", function(){
						jq341(document).scannerDetection(false);
						var im = new Inputmask();
						im.mask(jc.$content.find('#value'));
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