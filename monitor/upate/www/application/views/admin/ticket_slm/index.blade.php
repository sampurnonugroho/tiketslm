@extends('layouts.master')

@section('content')
	<!-- Always visible control bar -->
	<!--<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
			<button type="button" onClick="openModal()">Open modal</button>
		</div>
		
		<div class="float-right">
			<button type="button" disabled="disabled">Disabled</button>
			<button type="button" class="red">Cancel</button>
			<button type="button" class="grey">Reset</button>
			<button type="button"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/tick-circle.png" width="16" height="16"> Save</button>
		</div>
			
	</div></div>-->
	<!-- End control bar -->

	<!-- Content -->

	<section class="grid_14">
			<div class="block-border" align="center"><form class="block-content form" id="table_form" method="post" action="#">
				<h1>All Ticket List</h1>
				
					
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
					<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION  
					<br>[ALL TICKET LIST]
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
								Departement
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
						</tr>
					</thead>
					
					<tbody>
						<?php 
							$no = 0;
							foreach($datalist_ticket as $row): 
							$no++;
						?>
						<tr>
							<td class="th table-check-cell"><?=$no?></td>
							<td><?php echo $row->id_ticket;?></td>
							<td><?php echo $row->nama;?></td>
							<td><?php echo $row->nama_dept;?></td>
							<td><?php echo $row->tanggal;?></td>
							<td><?php echo $row->nama_kategori;?></td>
							<td><?php echo $row->nama_sub_kategori;?></td>
							<td>
								<?php 
									if($row->status==2) { echo "APPROVAL INTERNAL";}
									else if($row->status==3) { echo "MENUNGGU APPROVAL TEKNISI";}
									else if($row->status==4) { echo "PROSES TEKNISI";}
									else if($row->status==5) { echo "PENDING TEKNISI";}
									else if($row->status==6) { echo "SOLVED";}
								?>
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