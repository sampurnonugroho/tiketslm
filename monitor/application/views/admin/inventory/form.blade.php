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
					<input required type="hidden" name="id" id="id" value="<?php echo $id;?>" class="full-width">
					
					<fieldset class="grey-bg required">
						<legend><?=ucwords(str_replace("_", " ", $active_menu))?> <?=ucfirst($flag)?></legend>
						
						<p>
							<label for="simple-action">Nama Kategori</label>
							<input required type="text" name="name" id="name" value="<?php echo $name;?>" class="full-width">
						</p>
						<p>
							<label for="simple-action">Jumlah Tersedia	</label>
							<input required type="text" name="qty" id="qty" value="<?php echo $qty;?>" class="full-width">
						</p>
						<p>
							<label for="simple-action">Satuan</label>
							<input required type="text" name="unit" id="unit" value="<?php echo $unit;?>" class="full-width">
						</p>
						<p>
							<label for="simple-action">Type</label>
							<select name="type" class="full-width" required>
								<option value="">- select type -</option>
								<option value="supplies" <?=($type=="supplies"?"selected":"")?>>Supplies</option>
								<option value="sparepart" <?=($type=="sparepart"?"selected":"")?>>Spare Part</option>
							</select>
						</p>
						<button type="submit" style="float: right">Simpan</button>
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection