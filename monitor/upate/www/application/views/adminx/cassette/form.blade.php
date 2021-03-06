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
					<input required type="hidden" name="id_bagian_dept" id="id_bagian_dept" value="<?php echo $id_bagian_dept;?>" class="full-width">
					
					<fieldset class="grey-bg required">
						<legend><?=ucwords(str_replace("_", " ", $active_menu))?> <?=ucfirst($flag)?></legend>
						
						<p>
							<label>Nama Bagian Departemen</label>
						<input type="text" class="full-width" name="nama_bagian_dept" value="<?php echo $nama_bagian_dept;?>" required>
						</p>
						<p>
							<label>Nama/Posisi Departemen</label>
							<?php echo form_dropdown('id_departemen',$dd_departemen, $id_departemen, 'class="full-width"');?>
						</p>
						<button type="submit" style="float: right">Save</button>
			
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection