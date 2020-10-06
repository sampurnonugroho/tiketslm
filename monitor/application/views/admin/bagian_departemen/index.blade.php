@extends('layouts.master')

@section('content')
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
								<button type="button" onClick="window.location.href='<?=base_url()?>bagian_departemen/add'">Add Data</button>
							</div>
						<?php } ?>
						<center>
							<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
						</center>
						<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION
							<br>[SUB DEPARTEMENT]
						</p>
					</div>
				</div>	
			</div>
		</div>
	
		<section class="grid_12">
			<div class="widget_wrap">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6>Data SUB DEPARTEMENT</h6>
				</div>
				<div class="widget_content">
					<table class="display data_tbl">
						<thead>
							<tr>
								<th>
									Departemen
								</th>
								<th>
									Sub Departemen
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
								if(!empty($data_bagian_departemen))
								foreach($data_bagian_departemen as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row->nama_dept;?></td>
								<td><?php echo $row->nama_bagian_dept;?></td>
								<?php if($session->userdata['level']=="LEVEL1" OR $session->userdata['nama_dept']=="HUMAN RESOURCES") { ?>
									<td>
										<span><a class="action-icons c-edit" onClick="window.location.href='<?php echo base_url();?>bagian_departemen/edit/<?php echo $row->id_bagian_dept;?>'" href="#" title="Edit">Edit</a></span>
										<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id_bagian_dept;?>', '<?php echo base_url();?>bagian_departemen/delete')" href="#" title="delete">Delete</a></span>
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

@endsection