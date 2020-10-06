<?php $__env->startSection('content'); ?>
	<article class="container_12">
		<div class="block-border">
			<div class="block-content no-title dark-bg">
				<div id="control-bar">
					<div class="container_16">
						<div class="float-left">
							<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
						</div>
						<?php if($session->userdata['level']=="LEVEL1" OR $session->userdata['nama_dept']=="HUMAN RESOURCES") { ?>
							<div class="float-right">
								<button type="button" onClick="window.location.href='<?=base_url()?>jabatan/add'">Add Data</button>
							</div>
						<?php } ?>
						<center>
							<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
						</center>
						<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION  
							<br>[DATA EMPLOYMENT/<?=strtoupper(str_replace("_", " ", $active_menu))?>]
						</p>
					</div>
				</div>	
			</div>
		</div>
	
		<section class="grid_12">
			<div class="widget_wrap">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6><?=ucwords(str_replace("_", " ", $active_menu))?> Data</h6>
				</div>
				<div class="widget_content">
					<table class="display data_tbl">
						<thead>
							<tr>
								<th>
									Employment / Jabatan
								</th>
								<?php if($session->userdata['level']=="LEVEL1" OR $session->userdata['nama_dept']=="HUMAN RESOURCES") { ?>
									<th>
										Operation
									</th>
								<?php } ?>

							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								if(!empty($data_jabatan))
								foreach($data_jabatan as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row->nama_jabatan;?></td>
								<?php if($session->userdata['level']=="LEVEL1" OR $session->userdata['nama_dept']=="HUMAN RESOURCES") { ?>
									<td>
										<span><a class="action-icons c-edit" onClick="window.location.href='<?php echo base_url();?>jabatan/edit/<?php echo $row->id_jabatan;?>'" href="#" title="Edit">Edit</a></span>
										<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id_jabatan;?>', '<?php echo base_url();?>jabatan/delete')" href="#" title="delete">Delete</a></span>
									</td>
								<?php } ?>
							</tr>
							<?php 
								endforeach; 
							?>
						</tbody>
						
					</table>
				</div>
			</div>
		</section>
	
		<div class="clear"></div>
		
	</article>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>