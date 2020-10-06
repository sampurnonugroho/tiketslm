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
					<table class="display data_tbl">
						<thead>
							<tr>
								<th>
									Kode
								</th>
								<th >
									Aksi
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
								<td><?php echo $row->kode;?></td>
								<td >
									<div id="id" hidden><?php echo $row->kode;?></div>
									<span><a class="button" id="detail_preview" href="#" title="Print">Print</a></span>
									<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id;?>', '<?php echo base_url();?>bag/delete')" href="#" title="delete">Delete</a></span>
								</td>
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
				var websel = "<?=base_url()?>pdf/qrcode_bag/"+id;
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
							'<label>Tahun Produksi</label>'+
							'<input type="text" id="tahun_bag" placeholder="YY" class="full-width">'+
						'</p>'+
						'<p>'+
							'<label>Bulan Produksi</label>'+
							'<select id="bulan_bag">'+
								'<option value="1">Januari</option>'+
								'<option value="2">Februari</option>'+
								'<option value="3">Maret</option>'+
								'<option value="4">April</option>'+
								'<option value="5">Mei</option>'+
								'<option value="6">Juni</option>'+
								'<option value="7">Juli</option>'+
								'<option value="8">Agustus</option>'+
								'<option value="9">September</option>'+
								'<option value="10">Oktober</option>'+
								'<option value="11">November</option>'+
								'<option value="12">Desember</option>'+
							'</select>'+
						'</p>'+
						'<p>'+
							'<label>Jumlah Catridge</label>'+
							'<input type="text" id="catridge" placeholder="" class="full-width">'+
						'</p>'+
						'<p>'+
							'<label>Dari</label>'+
							'<input type="text" id="dari_bag" class="full-width">'+
						'</p>'+
						'<p>'+
							'<label>Hingga</label>'+
							'<input type="text" id="hingga_bag" class="full-width">'+
						'</p>'+
					'</fieldset>'+
				'</form>'+
			'';
			
			$.confirm({
				title: 'Create Bag Number!',
				content: content,
				contentLoaded: function(data, status, xhr){
					// this.setContentAppend(' <b>' + status);
				},
				buttons: {
					Submit: function () {
						var tahun = $("#tahun_bag").val();
						var bulan = $("#bulan_bag").val();
						var catridge = $("#catridge").val();
						var dari = $("#dari_bag").val();
						var hingga = $("#hingga_bag").val();
						
						var that_data = tahun+"-"+bulan+"-"+catridge+"-"+dari+"-"+hingga;
						
						$.confirm({
							title: 'Success!',
							content: 'url:<?=base_url()?>bag/save/'+that_data,
							// autoClose: 'Close|10000',
							contentLoaded: function(data, status, xhr){
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