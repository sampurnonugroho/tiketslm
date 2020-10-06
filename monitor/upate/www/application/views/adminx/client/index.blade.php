@extends('layouts.master')

@section('content')
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
						<div class="float-right">
							<button type="button" onClick="window.location.href='<?=base_url()?>client/add'">Add Data</button>
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
					<div class="block-border"><form class="block-content form" id="table_form" method="post" action="#">
						
						<fieldset>
							<legend>Data Client / Bank</legend>
							<button style="margin-top: -35px; float: right" class="btn btn-primary pull-right" id='detail_preview' type="button">Print QrCode</button>
							<div class="columns">
								<p class="colx2-left">
									<label for="complex-en-url">Sort By</label>
									<span class="relative">
										<select name="bank" class="sort_by_ full-width" required>
											<option value="">- Selected Sort -</option>
											<option value="bank">BANK</option>
											<option value="branch">CABANG</option>
											<option value="zone">SEKTOR</option>
										</select>
									</span>
								</p>
								<p class="sorted_by_1 colx2-right" hidden>
									<label for="complex-en-url">Sort By <span id="sort_title"></span></label>
									<span class="relative">
										<select name="bank" class="sorted_by_ full-width">
											<option value="">- Selected Sort -</option>
										</select>
									</span>
								</p>
							</div>
						</fieldset>
					</div>
					<table class="display data_tbl">
						<thead>
							<tr>
								<th>
									ID
								</th>
								<th>
									Bank
								</th>
								<th>
									Picture
								</th>
								<th>
									QR Code
								</th>
								<th>
									Sektor
								</th>
								<th>
									Tanggal H/O
								</th>
								<th>
									Tanggal Isi
								</th>
								<th>
									Actual
								</th>
								<th>
									Act
								</th>

							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								foreach($data_client as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row->wsid;?></td>
								<td><?php echo $row->bank;?></td>
								<td>
									<?php 
										if($row->picture!=="") {
									?>
											<img src="<?=base_url()?>upload/client/<?php echo $row->picture;?>" width="100" height="100"></img>
									<?php 
										} else {
									?>
											<img src="<?=base_url()?>upload/client/default.jpg" width="100" height="100"></img>
									<?php 
										} 
									?>
								</td>
								<td><img src="<?=base_url()?>upload/qrcode/<?php echo $row->wsid;?>.png" width="100" height="100"></img></td>
								<td><?php echo $row->sektor;?></td>
								<td><?php echo $row->tgl_ho;?></td>
								<td><?php echo $row->tgl_isi;?></td>
								<td><?php echo $row->type;?></td>
								<td>
									<span><a class="action-icons c-edit" onClick="window.location.href='<?php echo base_url();?>client/edit/<?php echo $row->id;?>'" href="#" title="Edit">Edit</a></span>
									<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id;?>', '<?php echo base_url();?>client/delete')" href="#" title="delete">Delete</a></span>
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
			// alert(id);
			setTimeout(function() {
				var websel = "<?=base_url()?>pdf/qrcode";
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		$(document).on('click', '#close_preview', function(){ 
			$(".preview_pdf").hide();
			$(".preview_table").show();
		});
	</script>
@endsection