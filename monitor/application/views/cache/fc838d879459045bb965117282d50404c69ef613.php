<?php $__env->startSection('content'); ?>
	<style type="text/css">
		form{
			margin:0;
			padding:0;
		}
		.dv-table td{
			border:0;
		}
		.dv-table input{
			border:1px solid #ccc;
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
	
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/easyui/jquery.easyui.min.js"></script>
	
	
	<script src="<?=base_url()?>assets/select2/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>assets/select2/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>assets/select2/select2.min.js"></script>
	
	<!-- Content -->
	<article class="container_12">
		
		<section class="grid_12">
		<div class="block-border">
			<div class="block-content no-title dark-bg">
				<div id="control-bar">
					<div class="container_16">
						<div class="float-left">
							<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
						</div>
						<?php if($session->userdata['level']=="LEVEL1") { ?>
							<div class="float-right">
								<!--<button type="button" onClick="window.location.href='<?=base_url()?>handover/add'">Add Data</button>-->
								<button type="button" onClick="openModalHandover();">Add Data</button>
							</div>
						<?php } ?>
						<center>
							<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
						</center>
						<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION  
							<br>[DATA <?=strtoupper(str_replace("_", " ", $active_menu))?> - INCOMING]
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
					<h6>Incoming <?=ucwords(explode("_", $active_menu)[0])?> Data</h6>
				</div>
				<div id="control-bar" class="clearfix">
					<div class="container_12">
						<div style="float: right">
							<label for="search">Search by Date : </label>
							<input type="text" name="simple-calendar" id="search" class="datepicker_search">
						</div>
					</div>
				</div>
				<div class="widget_content">
					<table class="display data_tbl2">
						<thead>
							<tr>
								<th>
									Tanggal
								</th>
								<th>
									Run Number
								</th>
								<th>
									Bank
								</th>
								<th>
									Custody
								</th>
								<th>
									Guard
								</th>
								<th>
									Police Number
								</th>
								<?php if($session->userdata['level']=="LEVEL1") { ?>
									<th>
										Act
									</th>
								<?php } ?>

							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								foreach($handover as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row->date;?></td>
								<td><?php echo $row->run;?></td>
								<td><?php echo $row->bank;?></td>
								<td><?php echo $row->custodian;?></td>
								<td><?php echo $row->guard;?></td>
								<td><?php echo $row->police_number;?></td>
								<?php if($session->userdata['level']=="LEVEL1") { ?>
									<td>
										<!--<span><a class="action-icons c-edit" onClick="window.location.href='<?php echo base_url();?>handover/add_ho/<?php echo $row->id;?>'" href="#" title="Detail">Detail</a></span>-->
										<span><a class="button" onClick="window.location.href='<?php echo base_url();?>handover/add_ho/<?php echo $row->id;?>'" href="#" id="print_bast_done" title="Detail">Detail</a></span>
										<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id;?>', '<?php echo base_url();?>handover/delete')" href="#" title="delete">Delete</a></span>
									</td>
								<?php } ?>
							</tr>
							<?php 
								endforeach; 
							?>
						</tbody>
						</table>
				</div>
			</div>
		</section>
	
		<div class="clear"></div>
		
	</article>
	<script>
		jq341 = jQuery.noConflict(true);
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
		
		function openModalHandover() {
			var content = ''+
				'<form class="form">'+
					'<fieldset>'+
						'<p>'+
							'<label>Run Number</label>'+
							'<input type="text" id="run_number" placeholder="" value="<?=$run_number?>" readonly="readonly" class="full-width">'+
						'</p>'+
						'<p>'+
							'<label>Jumlah Lokasi</label>'+
							'<input type="text" id="jumlah_lokasi" placeholder="" value="" class="full-width" required>'+
						'</p>'+
						'<p>'+
							'<label>Bank <span style="font-size: 8px">*jika tidak ada, input manual</span></label>'+
							'<select name="bank" class="bank full-width" required>'+
								'<option value="">- pilih bank -</option>'+
							'</select>'+
						'</p>'+
						'<p>'+
							'<label>Kendaraan</label>'+
							'<select name="cabang" class="vehicle full-width" required>'+
								'<option value="">- pilih kendaraan -</option>'+
							'</select>'+
						'</p>'+
						'<p>'+
							'<label>Security & Guard</label>'+
							'<select name="cabang" class="guard full-width" required>'+
								'<option value="">- pilih security & guard -</option>'+
							'</select>'+
						'</p>'+
						'<p>'+
							'<label>Custody</label>'+
							'<select name="cabang" class="custodian full-width" required>'+
								'<option value="">- select custody -</option>'+
							'</select>'+
						'</p>'+
					'</fieldset>'+
				'</form>'+
			'';
			
			$.modal({
				content: content,
				title: '',
				maxWidth: 400,
				buttons: {
					'Yes': function(win) { 
						var run_number = jq341("#run_number").val();
						var jumlah_lokasi = jq341("#jumlah_lokasi").val();
						var bank = jq341(".bank option:selected").val();
						var vehicle = jq341(".vehicle option:selected").val();
						var guard = jq341(".guard option:selected").val();
						var custodian = jq341(".custodian option:selected").val();
						
						if(run_number=="" || jumlah_lokasi=="" || vehicle=="" || guard=="" || custodian=="") {
							alert("ISI FORM UNTUK MELANJUTKAN");
						}
						
						$.ajax({
							url: '<?=base_url()?>handover/save_data',
							dataType: 'html',
							type: 'POST',
							data: {
								run_number: run_number,
								jumlah_lokasi: jumlah_lokasi,
								bank: bank,
								vehicle: vehicle,
								guard: guard,
								custodian: custodian
							},
							success: function(data) {
								// console.table(data);
								window.location.href = '<?=base_url()?>handover/add_ho/'+data;
							}
						});
					},
					'Close': function(win) { win.closeModal(); }
				}
			});
			
			jq341('.bank').select2({
				no_results_text: "Oops, nothing found!",
				tags: true,
				tokenSeparators: [','],
				width: '100%',
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'select/select_bank'?>',
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
			
			jq341('.vehicle').select2({
				tags: false,
				tokenSeparators: [','],
				width: '100%',
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'security/police_number'?>',
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
			
			jq341('.guard').select2({
				tags: false,
				tokenSeparators: [','],
				width: '100%',
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'security/suggest_security'?>',
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
			
			jq341('.custodian').select2({
				tags: false,
				tokenSeparators: [','],
				width: '100%',
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'operational/suggest_custodi'?>',
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
	
	
		$(document).on('click', '#print_qrcode', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert(id);
			setTimeout(function() {
				var websel = "<?=base_url()?>qr/index/"+id;
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		$(document).on('click', '#print_bast', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert(id);
			setTimeout(function() {
				var websel = "<?=base_url()?>pdf_fix/bast";
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		$(document).on('click', '#print_bast_done', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
// 			alert(id);
			setTimeout(function() {
				var websel = "<?=base_url()?>pdf_fix/bast_done/"+id;
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		$(document).on('click', '#close_preview', function(){ 
			$(".preview_pdf").hide();
			$(".preview_table").show();
		});
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>