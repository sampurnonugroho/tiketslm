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
					<input type="hidden" class="form-control" name="id_user" value="<?php echo $id_user;?>">
					
					<fieldset class="grey-bg required">
						<legend>Add User Access</legend>
						<?php if($flag=="edit")
						{}else{?>
						<p>
							<label for="simple-action">Nama Pegawai / Staff</label>
							<?php echo form_dropdown('id_karyawan',$dd_karyawan, $id_karyawan, ' id="id_karyawan" required class="full-width"');?>
						</p>
						<p>
							<label for="simple-action">Password</label>
							<input required type="password" name="password" id="password" value="<?php echo $password;?>" class="full-width">
						</p>
						<?php }?>
						<p>
							<label for="simple-action">User Level</label>
							<?php echo form_dropdown('id_level',$dd_level, $id_level, ' id="id_level" required class="full-width"');?>
						</p>
						<button type="submit" style="float: right">Save</button>
						<div class="float-left">
							<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
						</div>
						
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
@endsection