<?php $__env->startSection('content'); ?>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAb7d-G5Ea9j3X_haj37bSPJkSN7PpAp7I&libraries=places"></script>
	<script type="text/javascript" src="<?=base_url()?>constellation/assets/js/ContextMenu.js"></script>

	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

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
				<form class="block-content form" enctype="multipart/form-data" id="karyawanForm" method="post" action="<?php echo base_url();?>combination_lock/update">
					<fieldset class="grey-bg required">
							<input type="hidden" class="full-width" name="id" value="<?=$id?>">
							<p>
								<label>Combination Lock</label>
								<input type="password" class="full-width" name="combination" value="<?=$combination?>" required>
							</p>
						<button type="submit" style="float: right">Simpan</button>
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
	</article>
	
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>