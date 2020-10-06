@extends('layouts.master')

@section('content')
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
		</div>
			
	</div></div>

	<!-- Content -->
	<article class="container_12">
		
	
		<section class="grid_2"></section>
		<section class="grid_8">
			<div class="block-border">
			
				<form class="block-content form" id="karyawanForm" method="post" action="<?php echo base_url();?><?php echo $url;?>">					
					<input type="hidden" class="form-control" name="nik" value="<?php echo $nik;?>">
					
					<fieldset class="grey-bg required">
						<legend>Add Employee</legend>
						<p>
							<label for="simple-action">ID Pegawai / Karyawan</label>
							<input type="text" class="full-width">
						</p>
						<p>
							<label for="simple-action">Nama</label>
							<input required type="text" name="nama" id="nama" value="<?php echo $nama;?>" class="full-width">
						</p>
						<!--<p>
							<label for="simple-action">Email</label>
							<input type="text" class="full-width">
						</p>
						<p>
							<label for="simple-action">No Telp/HP</label>
							<input type="text" class="full-width">
						</p>-->
						<p>
							<label for="simple-action">Jenis Kelamin</label>
							<?php echo form_dropdown('id_jk',$dd_jk, $id_jk, ' id="id_jk" required class="full-width"');?>
						</p>
						<p>
							<label for="simple-action">Alamat</label>
							<textarea required type="text" name="alamat" id="alamat" value="" class="full-width"><?php echo $alamat;?></textarea>
						</p>
						<p>
							<label for="simple-action">Departemen</label>
							<?php echo form_dropdown('id_departemen',$dd_departemen, $id_departemen, ' id="id_departemen" required class="full-width"');?>
						</p>
						<p>
							<div id="div-order">
							<?php 
								if($flag=="edit" && $id_departemen!="") {
									echo '<label for="simple-action">Departemen</label>';
									echo form_dropdown('id_bagian_departemen',$dd_bagian_departemen, $id_bagian_departemen, 'required class="full-width"');
								}else{}
							?>
							</div>
						</p>
						<div class="form-group">
							<label>Jabatan</label>
							<?php echo form_dropdown('id_jabatan',$dd_jabatan, $id_jabatan, 'required class="full-width"');?>
						</div>
						<button type="submit" style="float: right">Save</button>
						
						
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
	<script language="javascript" type="text/javascript">
		// $(document).on("change", "#id_departemen", function() {
			// alert("AAA");
		// });
	
		$(document).ready(function() {

			$("#id_departemen").change(function(){
				// Put an animated GIF image insight of content
				var id_departemen = $("#id_departemen").val();
				// alert(id_departemen);
				$.ajax({
					type: "POST",
					url: '<?php echo base_url().'select/select_bagian_departemen'?>',					
					data: {id_departemen: id_departemen},
					success: function(msg){
						// alert(msg);
						console.log(msg);
						if(msg=="") {
							$('#div-order').hide();
						} else {
							$('#div-order').show();
						}
						$('#div-order').html(msg);
					}, error: function(e) {
						alert(JSON.stringify(e));
					}
				});
			});   
		});
		
		// $(document).on("change", "#id_departemen", function() {
			// alert("AA");
		// });
	</script>
@endsection