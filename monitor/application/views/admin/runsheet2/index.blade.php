@extends('layouts.master')

@section('content')


	<!-- Content -->
	<article class="container_12">
	<section class="grid_12">
			<div class="block-border" align="center"><form class="block-content form" id="table_form" method="post" action="#">
				<h1>Data Pegawai / Karyawan</h1>
				
					
					<div class="block-border"><div class="block-content no-title dark-bg">
					<div id="control-bar"><div class="container_16">
					<div class="float-left">
							<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
					</div>
					<div class="float-right">
							<button type="button" onClick="window.location.href='<?=base_url()?>karyawan/add'">Add Data</button>
					</div>
					<center>
					<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
					</center>
					<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>PORTAL MONITORING SYSTEM & SLA REPORT  
					<br>[DATA PEGAWAI/KARYAWAN]
					</p>
						
					</div>
					</div>	
					</div>
					</div>
							
					
					
				
				<table class="table sortable2 no-margin" cellspacing="0" width="100%">
				
					<thead>
						<tr>
							<th class="black-cell"><span class="loading"></span></th>
							<th scope="col">
								<span class="column-sort">
									<a href="javascript:void(0)" title="Sort up" class="sort-up"></a>
									<a href="javascript:void(0)" title="Sort down" class="sort-down"></a>
								</span>
								NIK
							</th>
							<th scope="col">
								Nama
							</th>
							<th scope="col">
								Alamat
							</th>
							<th scope="col">
								Jenis Kelamin
							</th>
							<th scope="col">
								Departemen
							</th>
							<th scope="col">
								Bagian
							</th>
							<th scope="col">
								Jabatan
							</th>
							<th scope="col">
								Aksi
							</th>
						</tr>
					</thead>
					
					<tbody>
						<?php 
							$no = 0;
							foreach($data_karyawan as $row): 
							$no++;
						?>
						<tr>
							<td class="th table-check-cell"><?=$no?></td>
							<td><?php echo $row->nik;?></td>
							<td><?php echo $row->nama;?></td>
							<td><?php echo $row->alamat;?></td>
							<td><?php echo $row->jk;?></td>
							<td><?php echo $row->nama_dept;?></td>
							<td><?php echo $row->nama_bagian_dept;?></td>
							<td><?php echo $row->nama_jabatan;?></td>
							<td>
								<button type="button" class='button green' onClick="window.location.href='<?php echo base_url();?>karyawan/edit/<?php echo $row->nik;?>'" title='Edit'><span class='smaller'>Edit</span></button>
								<button type="button" class='button red' onClick="openDelete('<?php echo $row->nik;?>', '<?php echo base_url();?>karyawan/delete')" title='Delete'><span class='smaller'>Del</span></button>
							</td>
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
@endsection