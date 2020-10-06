@extends('layouts.master')

@section('content')
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>
	<!-- Always visible control bar -->
	
	<!-- End control bar -->
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
	</style>

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
						<?php if($session->userdata['level']=="LEVEL1" OR $session->userdata['nama_dept']=="GA & LOGISTIC") { ?>
							<div class="float-right">
								<button type="button" onClick="openModalTambah();">Tambah</button>
							</div>
						<?php } ?>
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
					<table class="display data_tbl">
						<thead>
							<tr>
								<th>
									Kode
								</th>
								<th>
									Bank
								</th>
								<th>
									Lokasi
								</th>
								<th>
									CASSETTE
								</th>
								<th>
									DIVERT
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								if(!empty($data_bag))
								foreach($data_bag as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row->wsid;?></td>
								<td><?php echo $row->bank;?></td>
								<td><?php echo $row->lokasi;?></td>
								<td><?php echo $row->cassette;?></td>
								<td><?php echo $row->divert;?></td>
								<!--<td>
									<div id="id" hidden><?php echo $row->kode;?></div>
									<span><a class="button" id="detail_preview1" href="#" title="Print">Print QR</a></span>
									<!--<span><a class="button" id="detail_preview2" href="#" title="Print">Print BAR</a></span>
									<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id;?>', '<?php echo base_url();?>bag/delete')" href="#" title="delete">Delete</a></span>
								</td>-->
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
		$(document).on('click', '#detail_preview1', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert("<?=base_url()?>pdf/qrcode_tbag/"+id);
			setTimeout(function() {
				var websel = "<?=base_url()?>pdf/qrcode_gen/"+id;
				document.getElementById("preview").src = websel;
			}, 100);
		});
		$(document).on('click', '#detail_preview2', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert("<?=base_url()?>pdf/qrcode_tbag/"+id);
			setTimeout(function() {
				var websel = "<?=base_url()?>pdf/barcode_seal/"+id;
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		$(document).on('click', '#close_preview', function(){ 
			$(".preview_pdf").hide();
			$(".preview_table").show();
		});
	
		jq341 = jQuery.noConflict(true);
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
		
		function jsUcfirst(string) { return string.charAt(0).toUpperCase() + string.slice(1); }
	
		function openModalTambah() {
			var content = ''+
				'<form class="form">'+
					'<fieldset>'+
						'<p>'+
							'<label>ID ATM</label>'+
							'<select name="cabang" class="js-example-basic-single2 full-width" required><option value="">- select cabang -</option></select>'+
						'</p>'+
						'<p>'+
							'<label>JUMLAH CASSETTE</label>'+
							'<input type="text" id="cassette" class="full-width">'+
						'</p>'+
						'<p>'+
							'<label>JUMLAH DIVERT</label>'+
							'<input type="text" id="divert" class="full-width">'+
						'</p>'+
					'</fieldset>'+
				'</form>'+
			'';
			
			$.modal({
				content: content,
				maxWidth: 600,
				buttons: {
					'Yes': function(win) { 
						var wsid = jq341(".js-example-basic-single2 option:selected").val();
						var cassette = $("#cassette").val();
						var divert = $("#divert").val();
					
						// alert(wsid+" "+cassette+" "+divert);
						
						var that_data = wsid+"-"+cassette+"-"+divert;
						$.ajax({
							url: '<?=base_url()?>cassette/save/'+that_data,
							dataType: 'html',
							type: 'POST',
							data: {},
							success: function(data) {
								console.log(data);
								window.location.reload();
								// window.location.href = '<?=base_url()?>cassette/add/'+data;
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
					url: '<?php echo base_url().'select/select_atm'?>',
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
	</script>
@endsection