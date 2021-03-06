@extends('layouts.master')

@section('content')
	<!-- Always visible control bar -->
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
		</div>
			
	</div></div>
	<!-- End control bar -->

	<!-- Content -->
	<article class="container_12">
		
	
		<section class="grid_2"></section>
		<section class="grid_8">
			<div class="block-border">
			
				<form class="block-content form" id="karyawanForm" method="post" action="<?php echo base_url();?><?php echo $url;?>">
					<input required type="hidden" name="id_teknisi" id="id_teknisi" value="<?php echo $id_teknisi;?>" class="full-width">
					
					<fieldset class="grey-bg required">
						<legend>Add Technical Staff</legend>
						<?php if($flag=="edit")
						{}else{?>
						<p>
							<label for="simple-action">Nama Teknisi</label>
							<?php echo form_dropdown('id_karyawan',$dd_karyawan, $id_karyawan, ' id="id_karyawan" required class="full-width"');?>
						</p>
						<?php }?>
						<p>
							<label>Klasifikasi Teknisi</label>
						<?php echo form_dropdown('id_kategori',$dd_kategori, $id_kategori, ' id="id_kategori" required class="full-width"');?>
						</p>
						<button type="submit" style="float: right">Save</button>
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection