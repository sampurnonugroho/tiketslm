@extends('layouts.master')

@section('content')
	
	<!-- Content -->
	<article class="container_12">
	<section class="grid_12">
			<div class="block-border" align="center"><form class="block-content form" id="table_form" method="post" action="#">
				<h1>Troubleshoot Ticket</h1>
				
					
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
					<br>[TROUBLESHOOT TICKET]
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
								Status
							</th>
							<th scope="col">
								Feedback
							</th>
						</tr>
					</thead>
					
					<tbody>
						<?php 
							$no = 0;
							foreach($datalist_myticket as $row): 
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
								<?php if($row->status==1) { echo "MENUNGGU DISETUJUI";}
						        else if($row->status==2) { echo "DISETUJUI";}
						        else if($row->status==0) { echo "TICKET DITOLAK";}
						        else if($row->status==3) { echo "MENUNGGU APRROVAL TEKNISI";}
						        else if($row->status==4) { echo "PROSES TEKNISI";}
						        else if($row->status==5) { echo "PENDING TEKNISI";}
						        else if($row->status==6) { echo "SOLVED";}
						        ?>
							</td>
							<td>
								<?php if($row->status==6 AND $row->feedback == "") {?>
									<a class="ubah btn btn-success btn-xs" href="<?php echo base_url();?>myticket/feedback_yes/<?php echo $row->id_ticket;?>/<?php echo $row->id_teknisi;?>"><span class="glyphicon glyphicon-thumbs-up" ></span></a>
									<a title="Hapus Kontak" class="hapus btn btn-danger btn-xs" href="<?php echo base_url();?>myticket/feedback_no/<?php echo $row->id_ticket;?>/<?php echo $row->id_teknisi;?>"><span class="glyphicon glyphicon-thumbs-down"></span></a>
								<?php } else if($row->status==6 AND  $row->feedback == 1) { echo "ANDA MEMBERIKAN FEEDBACK POSITIF";}
									  else if($row->status==6 AND $row->feedback == 0) { echo "ANDA MEMBERIKAN FEEDBACK NEGATIF";}
									  else
									  {
										echo "MENUNGGU STATUS SOLVED DARI TEKNISI";
									  }
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
		
	</article>
@endsection