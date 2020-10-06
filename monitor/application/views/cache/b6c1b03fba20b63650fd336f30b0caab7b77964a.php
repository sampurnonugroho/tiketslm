<?php $__env->startSection('content'); ?>

	<!-- Content -->
	<article class="container_12">
	
	<section class="grid_12">
		<div class="block-border">
			<div class="block-content no-title dark-bg">
				<div id="control-bar">
					<div class="container_16">
						<center>
							<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
						</center>
						<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION 
							
						</p>
					</div>
				</div>	
			</div>
		</div>
			<div class="block-border"><form class="block-content form" id="table_form" method="post" action="#">
				<h1>Data Merk Mesin</h1>
				<br>
				<div class="float-left">
					<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
				</div>
				<?php if($session->userdata['level']=="LEVEL1") { ?>
					<div class="float-right">
						<button type="button" onClick="window.location.href='<?=base_url()?>merk_mesin/add'">Tambah</button>
					</div>
				<?php } ?>
				<table class="table sortable2 " cellspacing="0" width="100%">
				
					<thead>
						<tr>
							<th class="black-cell"><span class="loading"></span></th>
							<th scope="col">
								<span class="column-sort">
									<a href="javascript:void(0)" title="Sort up" class="sort-up"></a>
									<a href="javascript:void(0)" title="Sort down" class="sort-down"></a>
								</span>
								Merk
							</th>
							<?php if($session->userdata['level']=="LEVEL1") { ?>
								<th scope="col" width="80px">
									Aksi
								</th>
							<?php } ?>
						</tr>
					</thead>
					
					<tbody>
						<?php 
							$no = 0;
							if(!empty($datamerk))
							foreach($datamerk as $row): 
							$no++;
						?>
						<tr>
							<td class="th table-check-cell"><?=$no?></td>
							<td><?php echo $row->merk;?></td>
							<?php if($session->userdata['level']=="LEVEL1") { ?>
								<td>
									<button type="button" class='button green' onClick="window.location.href='<?php echo base_url();?>merk_mesin/edit/<?php echo $row->id;?>'" title='Edit'><span class='smaller'>Edit</span></button>
									<button type="button" class='button red' onClick="openDelete('<?php echo $row->id;?>', '<?php echo base_url();?>merk_mesin/delete')" title='Delete'><span class='smaller'>Del</span></button>
								</td>
							<?php } ?>
						</tr>
						<?php 
							endforeach; 
						?>
					</tbody>
				
				</table>
					
			</form></div>
		</section>
		
		<div class="clear"></div>
		
	</article>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>