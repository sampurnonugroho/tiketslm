@extends('layouts.master')

@section('content')
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
					<input required type="hidden" name="id_sub_kategori" id="id_sub_kategori" value="<?php echo $id_sub_kategori;?>" class="full-width">
					
					<fieldset class="grey-bg required">
						<legend><?=ucwords(str_replace("_", " ", $active_menu))?> <?=ucfirst($flag)?></legend>
						
						<p>
							<label for="simple-action">Klasifikasi Troubleshoot / Kerusakan</label>
							<input required type="text" name="nama_sub_kategori" id="nama_sub_kategori" value="<?php echo $nama_sub_kategori;?>" class="full-width">
						</p>
						<p>
							<label>Klasifikasi Teknisi</label>
							<?php echo form_dropdown('id_kategori',$dd_kategori, $id_kategori, 'class="full-width"');?>
						</p>
						<button type="submit" style="float: right">Save</button>
						
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection