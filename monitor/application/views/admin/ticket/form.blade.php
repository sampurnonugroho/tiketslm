@extends('layouts.master')

@section('content')
	<script language="javascript" type="text/javascript">
		
		$(document).ready(function() {

			$("#id_kategori").change(function(){
				// Put an animated GIF image insight of content
			
				var data = {id_kategori:$("#id_kategori").val()};
				$.ajax({
						type: "POST",
						url : "<?php echo base_url().'select/select_sub_kategori'?>",				
						data: data,
						success: function(msg){
							$('#div-order').html(msg);
						}
				});
			});   

		});

	</script>	
	<!-- Content -->
	<article class="container_12">
		
	
		<section class="grid_2"></section>
		<section class="grid_8">
			<div class="block-border">
			
				<form class="block-content form" id="karyawanForm" method="post" action="<?php echo base_url();?><?php echo $url;?>">
					<h1>Pelapor Masalah</h1>
					<article class="container_12" style="margin-bottom: -30px">
						<section class="grid_6">
							<p>
								<label for="simple-action">ID USER</label>
								<input type="text" class="full-width" value="<?php echo $nama;?>" disabled>
							</p>
						</section>
						<section class="grid_6">
							<p>
								<label for="simple-action">NAMA</label>
								<input type="text" class="full-width" value="<?php echo $departemen;?>" readonly>
							</p>
						</section>
					</article>
					<article class="container_12" style="margin-top: -30px">
						<section class="grid_6">
							<p>
								<label for="simple-action">DEPARTEMEN</label>
								<input type="text" class="full-width" value="<?php echo $nama;?>" readonly>
							</p>
						</section>
						<section class="grid_6">
							<p>
								<label for="simple-action">BAGIAN DEPARTEMEN</label>
								<input type="text" class="full-width" value="<?php echo $bagian_departemen;?>" readonly>
							</p>
						</section>
					</article>
				</form>
			</div>
			<div class="block-border">
			
				<form class="block-content form" id="karyawanForm" method="post" action="<?php echo base_url();?><?php echo $url;?>">
					<h1>Deskripsi Masalah</h1>
					
					<input type="hidden" class="form-control" name="id_ticket" value="<?php echo $id_ticket;?>">
					<input type="hidden" class="form-control" name="id_user" value="<?php echo $id_user;?>">
					
					<fieldset class="grey-bg required">
						<legend>Form Input Data Pegawai / Karyawan</legend>
						<p>
							<label for="simple-action">Kategori Masalah</label>
							<?php echo form_dropdown('id_kategori',$dd_kategori, $id_kategori, ' id="id_kategori" required class="full-width"');?>
						</p>
						<p>
						<div id="div-order">

						<?php if($flag=="edit")
						{

	                     echo form_dropdown('id_sub_kategori',$dd_sub_kategori, $id_sub_kategori, 'required class="full-width"');

						}else{}
					    ?>

					    </div>
						</p>
						<p>
							<label>Severity Level & Urgensi</label>
							<?php echo form_dropdown('id_kondisi',$dd_kondisi, $id_kondisi, ' id="id_kondisi" required class="full-width"');?>
						</p>
						<p>
							<label>Subject Masalah</label>
							<input type="text" class="full-width" name="problem_summary" placeholder="" value="<?php echo $problem_summary;?>" required>
						</p>
						<p>
							<label>Deskripsi Masalah</label>
							<textarea name="problem_detail" class="full-width" rows="10" required><?php echo $problem_detail;?></textarea>
						</p>
						<button type="submit" style="float: right">Simpan</button>
						
						<div class="float-left">
							<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
							<!--<button type="button" onClick="openModal()">Open modal</button>-->
						</div>
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection