@extends('layouts.master')

@section('content')
<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
<style>
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
<div id="content">
		<div class="grid_container">
			<div class="grid_14">
				<div class="preview_pdf" hidden>
					<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview' type="button">Close</button>
					<iframe id="preview" name="preview" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
				</div>
			
				<div class="widget_wrap preview_table">
					<div class="widget_top">
						<span class="h_icon list"></span>
					</div>
					<div class="widget_content">
						<div class=" page_content">
							<div class="invoice_container">
								
								<div class="grid_12 invoice_title">
									<center><img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
								</center>
								<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>[ REPORT ]</p>
								</div>
								<span class="clear"></span>
								<div class="grid_12 invoice_details">
									<div class="invoice_tbl">
										<table>
										<thead>
										<tr class=" gray_sai">
											<th>
												No.
											</th>
											<th>
												ID
											</th>
											<th>
												BANK
											</th>
											<th>
												LOCATION
											</th>
											<th>
												TYPE
											</th>
											<th>
												ACTION
											</th>
										</tr>
										</thead>
										<tbody>
										<?php 
											$no = 0;
											foreach($data_cashtransit as $row) {
											$no++;
										?>
										<tr>
											<td>
												<?=$no?>
											</td>
											<td class="left_align">
												<?=$row->no_boc?> <?=$row->id_detail?>
											</td>
											<td>
												<?=$row->bank?>
											</td>
											<td class="left_align">
												<?=$row->lokasi?>
											</td>
											<td>
												<?=$row->type_mesin?>
											</td>
											<td>
												<div id="id" hidden><?=$row->id_detail?></div>
												<!--<a href="<?=base_url()?>pdf/generate/<?=$row->id_detail?>" target="_blank">Detail</a>-->
												<a href="#" class="detail_previews">Detail</a>
											</td>
										</tr>
										<?php 
											}
										?>
										</tbody>
										</table>
									</div>
								</div>
								<span class="clear"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<span class="clear"></span>
	</div>
		
	<div class="clear"></div>
	<script>
		$(document).on('click', '.detail_previews', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert(id);
			setTimeout(function() {
			var websel = "<?=base_url()?>pdf_fix/boc2/"+id;
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		$(document).on('click', '#close_preview', function(){ 
			$(".preview_pdf").hide();
			$(".preview_table").show();
		});
	</script>
@endsection