<?php $__env->startSection('content'); ?>
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
						<form class="block-content form" id="karyawanForm" method="post" action="<?php echo base_url();?><?php echo $url;?>">
						<?php if($flag=="edit")
						{ ?>
							<p>
								<label for="simple-action">Username</label>
								<input required type="text" name="username" id="username" value="<?php echo $username;?>" class="full-width">
							</p>
							<p>
								<label for="simple-action">Password</label>
								<input  type="password" name="password" id="password" value="<?php echo $password;?>" class="full-width">
							</p>
							
						<?php }else{ ?>
						<p>
							<label for="simple-action">Nama Karyawan</label>
							<?php echo form_dropdown('id_user_client',$dd_karyawan, $id_user_client, ' id="id_karyawan" required class="full-width"');?>
						</p>
						<p>
							<label for="simple-action">Username</label>
							<input required type="text" name="username" id="username" value="<?php echo $username;?>" class="full-width">
						</p>
						<p>
							<label for="simple-action">Password</label>
							<input required type="password" name="password" id="password" value="<?php echo $password;?>" class="full-width">
						</p>
						<?php }?>
						<button type="submit" style="float: right">Save</button>
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
		
	</article>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>