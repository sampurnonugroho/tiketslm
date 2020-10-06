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
							<input required type="text" name="type" id="" value="<?php echo $type;?>" class="full-width">
						</p>
						<p>
							<label for="simple-action">Number</label>
							<input required type="text" name="no" id="" value="<?php echo $no;?>" class="full-width">
						</p>
						<p>
							<label for="simple-action">Imei</label>
							<input required type="text" name="imei" id="" value="<?php echo $imei;?>" class="full-width">
						</p>
						<p>
							<label for="simple-action">Number SIM</label>
							<input required type="text" name="number" id="" value="<?php echo $number;?>" class="full-width">
						</p>
						<button type="submit" style="float: right">Simpan</button>
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection