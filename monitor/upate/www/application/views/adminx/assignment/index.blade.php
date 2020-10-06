@extends('layouts.master')

@section('content')

	<!-- Content -->
	<section class="grid_14">
			<div class="block-border" align="center"><form class="block-content form" id="table_form" method="post" action="#">
				<h1>Assignment Ticket List</h1>
				
					
					<div class="block-border"><div class="block-content no-title dark-bg">
					<div id="control-bar"><div class="container_16">
					<!--<div class="float-left">
							<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
					</div>
					<div class="float-right">
							<button type="button" onClick="window.location.href='<?=base_url()?>karyawan/add'">Add Data</button>
					</div>-->
					<center>
					<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
					</center>
					<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>PORTAL MONITORING SYSTEM & SLA REPORT  
					<br>[ASSIGNMENT TICKET LIST]
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
								ID Ticket
							</th>
							<th scope="col">
								Tanggal
							</th>
							<th scope="col">
								Nama Kategori
							</th>
							<th scope="col">
								Nama Sub Kategori
							</th>
							<th scope="col">
								Progress (%)
							</th>
							<th scope="col">
								Aksi
							</th>
						</tr>
					</thead>
					
					<tbody>
						<?php 
							$no = 0;
							foreach($datalist_assignment as $row): 
							$no++;
						?>
						<tr>
							<td class="th table-check-cell"><?=$no?></td>
							<td><?php echo $row->id_ticket;?></td>
							<td><?php echo $row->tanggal;?></td>
							<td><?php echo $row->nama_kategori;?></td>
							<td><?php echo $row->nama_sub_kategori;?></td>
							<td><?php echo $row->progress;?></td>
							<td>
								<?php if($row->status==4) {?>
								<?php } else if($row->status==3) { ?>
									<a class="ubah btn btn-success btn-xs" href="<?php echo base_url();?>myassignment/terima/<?php echo $row->id_ticket;?>"><span class="glyphicon glyphicon-thumbs-up" ></span></a>
									<a class="ubah btn btn-danger btn-xs" href="<?php echo base_url();?>myassignment/pending/<?php echo $row->id_ticket;?>"><span class="glyphicon glyphicon-minus-sign" ></span></a>
								<?php } else if($row->status==5) { ?>
									<a class="ubah btn btn-success btn-xs" href="<?php echo base_url();?>myassignment/terima/<?php echo $row->id_ticket;?>"><span class="glyphicon glyphicon-thumbs-up" ></span></a>
								<?php }?>
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

@endsection