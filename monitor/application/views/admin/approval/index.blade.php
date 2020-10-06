@extends('layouts.master')

@section('content')

	<!-- Content -->
	<section class="grid_14">
			<div class="block-border" align="center"><form class="block-content form" id="table_form" method="post" action="#">
				<h1>Approval Ticket List</h1>
				
					
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
					<br>[APPROVAL TICKET LIST]
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
								Reported
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
								Status
							</th>
							<th scope="col">
								ACTION
							</th>
						</tr>
					</thead>
					
					<tbody>
						<?php 
							$no = 0;
							foreach($datalist_approval as $row): 
							$no++;
						?>
						<tr>
							<td class="th table-check-cell"><?=$no?></td>
							<td><?php echo $row->id_ticket;?></td>
							<td><?php echo $row->nama;?></td>
							<td><?php echo $row->tanggal;?></td>
							<td><?php echo $row->nama_kategori;?></td>
							<td><?php echo $row->nama_sub_kategori;?></td>
							<td>
								<?php 
									if($row->status==1) { echo "MENUNGGU APPROVAL";}
						        	else if($row->status==0) { echo "TIDAK APPROVAL";}
								?>
							</td>
							<?php if($row->status == 1)
						         	{?>
						         	<td>
<a class="ubah btn btn-success btn-xs" href="<?php echo base_url();?>approval/approval_yes/<?php echo $row->id_ticket;?>"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/tick-circle.png" width="16" height="16"></a>
<a title="Hapus Kontak" class="hapus btn btn-danger btn-xs" href="<?php echo base_url();?>approval/approval_no/<?php echo $row->id_ticket;?>"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/cross-circle.png" width="16" height="16"></a>
</td>
<?php } else if($row->status == 2) {?>
<td>
<a title="Hapus Kontak" class="hapus btn btn-danger btn-xs" href="<?php echo base_url();?>approval/approval_no/<?php echo $row->id_ticket;?>"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/cross-circle.png" width="16" height="16"></a>
</td>
<?php } else if($row->status == 0) {?>
<td>
<a class="ubah btn btn-primary btn-xs" href="<?php echo base_url();?>approval/approval_reaction/<?php echo $row->id_ticket;?>"><span class="glyphicon glyphicon-retweet" ></span></a>
</td>
<?php }?>
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