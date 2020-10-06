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
							<button type="button" onClick="window.location.href='<?=base_url()?>handover/add'">Add Data</button>
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
									WSID
								</th>
								<th>
									Bank
								</th>
								<th>
									Alamat
								</th>
								<th>
									Custody
								</th>
								<th>
									Status
								</th>
								<th>
									Act
								</th>

							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								foreach($handover as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row->wsid;?></td>
								<td><?php echo $row->bank;?></td>
								<td><?php echo $row->lokasi;?></td>
								<td><?php echo $row->nama;?></td>
								<td><?php echo strtoupper($row->status);?></td>
								<td>
								    <div id="id" hidden><?php echo $row->wsid;?></div>
								    <span><a class="button" href="#" id="print_bast" title="Edit">Print BAST</a></span>
									<span><a class="button" href="#" id="print_qrcode" title="Edit">Print QR-CODE</a></span>
									<span><a class="action-icons c-edit" onClick="window.location.href='<?php echo base_url();?>handover/edit/<?php echo $row->id;?>'" href="#" title="Edit">Edit</a></span>
									<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id;?>', '<?php echo base_url();?>handover/delete')" href="#" title="delete">Delete</a></span>
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
		$(document).on('click', '#print_qrcode', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert(id);
			setTimeout(function() {
				var websel = "<?=base_url()?>pdf/qrcode_bast/"+id;
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
		
		$(document).on('click', '#close_preview', function(){ 
			$(".preview_pdf").hide();
			$(".preview_table").show();
		});
	</script>
@endsection