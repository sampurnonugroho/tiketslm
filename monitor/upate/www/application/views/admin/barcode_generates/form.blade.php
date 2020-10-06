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
					
					<input type="hidden" class="form-control" name="id" value="<?php echo $id;?>">
					
					<fieldset class="grey-bg required">
						<legend><?=ucwords(str_replace("_", " ", $active_menu))?> <?=ucfirst($flag)?></legend>
						<p>
							<label for="simple-action">Batch Barcode</label>
							<?php echo form_dropdown('id_batch', $dd_batch, $id_batch, ' id="id_batch" required class="full-width"');?>
						</p>
						<p>
							<label for="simple-action">Quantity</label>
							<input required type="text" name="quantity" id="quantity" value="<?php echo $quantity;?>" class="full-width">
						</p>
						<button type="submit" style="float: right">Simpan</button>
						
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection