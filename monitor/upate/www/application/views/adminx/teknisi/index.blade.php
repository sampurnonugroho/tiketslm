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
						<div class="float-right">
							<button type="button" onClick="window.location.href='<?=base_url()?>teknisi/add'">Add Data</button>
						</div>
						<center>
							<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
						</center>
						<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION 
							<br>[DATA TECHNICIAN]
						</p>
					</div>
				</div>	
			</div>
		</div>
	
		<section class="grid_12">
			<div class="widget_wrap">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6><?=ucwords(str_replace("_", " ", $active_menu))?> Data </h6>
				</div>
				<div class="widget_content">
					<table class="display data_tbl">
						<thead>
							<tr>
								<th>
									ID Teknisi
								</th>
								<th>
									Nama Teknisi
								</th>
								<th>
									Klasifikasi Teknisi
								</th>
								<th>
									Status
								</th>
								<th>
									Point
								</th>
								<th>
									Operation
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								if(!empty($datateknisi))
								foreach($datateknisi as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row->id_teknisi;?></td>
								<td><?php echo $row->nama;?></td>
								<td><?php echo $row->nama_kategori;?></td>
								<td><?php echo $row->status;?></td>
								<td><?php echo $row->point;?></td>
								<td>
									<span><a class="action-icons c-edit" onClick="window.location.href='<?php echo base_url();?>teknisi/edit/<?php echo $row->id_teknisi;?>'" href="#" title="Edit">Edit</a></span>
									<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id_teknisi;?>', '<?php echo base_url();?>teknisi/delete')" href="#" title="delete">Delete</a></span>
								</td>
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