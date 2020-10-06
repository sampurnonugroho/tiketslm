@extends('layouts.master')

@section('content')

	<!-- Content -->
	<article class="container_12">
		
	
		<section class="grid_2"></section>
		<section class="grid_8">
			<div class="block-border">
			
				<form class="block-content form" id="karyawanForm" method="post" action="<?php echo base_url();?><?php echo $url;?>">
					<h1>Data Pegawai / Karyawan</h1>
					
					<input type="hidden" class="form-control" name="nik" value="<?php echo $nik;?>">
					
					<fieldset class="grey-bg required">
						<legend>Form Input Data Pegawai / Karyawan</legend>
						<p>
							<label for="simple-action">ID Pegawai / Karyawan</label>
							<input type="text" class="full-width">
						</p>
						<p>
							<label for="simple-action">Nama</label>
							<input required type="text" name="nama" id="nama" value="<?php echo $nama;?>" class="full-width">
						</p>
						<p>
							<label for="simple-action">Email</label>
							<input type="text" class="full-width">
						</p>
						<p>
							<label for="simple-action">No Telp/HP</label>
							<input type="text" class="full-width">
						</p>
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
						<p>
							<label for="simple-action">Jabatan</label>
							<?php echo form_dropdown('id_jabatan',$dd_jabatan, $id_jabatan, 'required class="full-width"');?>
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