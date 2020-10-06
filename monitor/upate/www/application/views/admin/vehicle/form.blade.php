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
					<input required type="hidden" name="id" id="id" value="<?php echo $id;?>" class="full-width">
					
					<fieldset class="grey-bg required">
						<legend><?=ucwords(str_replace("_", " ", $active_menu))?> <?=ucfirst($flag)?></legend>
						
						<p>
							<label for="simple-action">Type</label>
							<input required type="text" name="type" id="type" value="<?php echo $type;?>" class="full-width">
						</p>
						<p>
							<label for="simple-action">Police Number</label>
							<input required type="text" name="police_number" id="police_number" value="<?php echo $police_number;?>" class="full-width">
						</p>
						<p>
							<label for="simple-action">KM Status</label>
							<input required type="text" name="km_status" id="km_status" value="<?php echo $km_status;?>" class="full-width">
						</p>
						
						<button type="submit" style="float: right">Simpan</button>
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection