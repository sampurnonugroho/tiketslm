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
							<button type="button" onClick="window.location.href='<?=base_url()?>karyawan/add'">Add Data</button>
						</div>
						<center>
							<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
						</center>
						<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION  
							<br>[DATA EMPLOYEE]
						</p>
					</div>
				</div>	
			</div>
		</div>
	
		<section class="grid_12">
			<div class="widget_wrap">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6>EMPLOYEE LIST</h6>
				</div>
				<div class="widget_content">
					<table class="display data_tbl">
						<thead>
							<tr>
								<th>
									ID / NIK
								</th>
								<th>
									Nama
								</th>
								<th>
									Alamat
								</th>
								<th>
									Departemen
								</th>
								<th>
									Bagian
								</th>
								<th>
									Jabatan
								</th>
								<th>
									Operation
								</th>

							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								if(!empty($data_karyawan))
								foreach($data_karyawan as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row->nik;?></td>
								<td><?php echo $row->nama;?></td>
								<td><?php echo $row->alamat;?></td>
								<td><?php echo $row->nama_dept;?></td>
								<td><?php echo $row->nama_bagian_dept;?></td>
								<td><?php echo $row->nama_jabatan;?></td>
								<td>
									<span><a class="action-icons c-edit" onClick="window.location.href='<?php echo base_url();?>karyawan/edit/<?php echo $row->nik;?>'" href="#" title="Edit">Edit</a></span>
									<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->nik;?>', '<?php echo base_url();?>karyawan/delete')" href="#" title="delete">Delete</a></span>
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