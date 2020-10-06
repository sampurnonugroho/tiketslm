@extends('layouts.master')

@section('content')
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>depend/jquery-confirm/css/jquery-confirm.css"/>
	<script type="text/javascript" src="<?=base_url()?>depend/jquery-confirm/js/jquery-confirm.js"></script>
	
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
							<br>[DATA <?=strtoupper(str_replace("_", " ", $active_menu))?> PAPER]
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
								<th >
									Jumlah
								</th>
								<?php if($session->userdata['level']=="LEVEL1") { ?>
									<th>
										Action
									</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								if(!empty($data_receipt))
								foreach($data_receipt as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row['seal'];?></td>
								<td><?php echo $row['count'];?></td>
								<?php if($session->userdata['level']=="LEVEL1") { ?>
									<td>
										<center>
											<div id="id" hidden><?php echo $row['date'];?></div>
											<span><a class="button" id="detail_preview" href="#" title="Print">Print</a></span>
											<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row['date'];?>', '<?php echo base_url();?>receipt/delete')" href="#" title="delete">Delete</a></span>
										</center>
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
		$(document).on('click', '#detail_preview', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert("<?=base_url()?>pdf/qrcode_tbag/"+id);
			setTimeout(function() {
				var websel = "<?=base_url()?>qr/print_receipt/"+id;
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
		
		
	
		function openModalTambah() {
			$ = jq341;
			
			var content = ''+
				'<form class="form">'+
					'<fieldset>'+
						'<p>'+
							'<label>Dari</label>'+
							'<input type="text" id="dari" class="full-width">'+
						'</p>'+
						'<p>'+
							'<label>Hingga</label>'+
							'<input type="text" id="hingga" class="full-width">'+
						'</p>'+
					'</fieldset>'+
				'</form>'+
			'';
			
			$.confirm({
				title: 'Create Receipt Barcode',
				content: content,
				contentLoaded: function(data, status, xhr){
					// this.setContentAppend(' <b>' + status);
				},
				buttons: {
					Submit: function () {
						var dari = $("#dari").val();
						var hingga = $("#hingga").val();
						
						var that_data = dari+"-"+hingga;
						
						$.confirm({
							title: 'Success!',
							content: 'url:<?=base_url()?>receipt/save/'+that_data,
							// autoClose: 'Close|10000',
							contentLoaded: function(data, status, xhr){
								console.log(data);
								// this.setContentAppend(' <b>' + status);
								window.location.reload();
							},
							buttons: {
								Close: function () {
									// alert("SUCCESS");
								}
							}
						});
					},
					Close: function () {
						// alert("SUCCESS");
					}
				}
			});
		}
		
		function openModalTambah2() {
			var content = ''+
				'<p>'+
					'aaaa'+
				'</p>'+
			'';
			
			$.modal({
				content: content,
				title: '',
				maxWidth: 400,
				buttons: {
					'Yes': function(win) { 
						var id_branch = jq341(".js-example-basic-single2 option:selected").val();
					
						// alert(data);
						$.ajax({
							url: '<?=base_url()?>cashreplenish/add_master',
							dataType: 'html',
							type: 'POST',
							data: {id:id_branch},
							success: function(data) {
								window.location.href = '<?=base_url()?>cashreplenish/add/'+data;
							}
						});
					}
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
	</script>
@endsection