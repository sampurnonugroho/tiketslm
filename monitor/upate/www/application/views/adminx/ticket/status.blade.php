@extends('layouts.master')

@section('content')
	<!-- Content -->
	<article class="container_12">
		<div class="block-border">
			<div class="block-content no-title dark-bg">
				<div id="control-bar">
					<div class="container_16">
						<center>
							<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
						</center>
						<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION 
							<br>[DATA <?=strtoupper(str_replace("_", " ", $active_menu))?>]
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
									Ticket
								</th>
								<th>
									Bank
								</th>
								<th>
									Act
								</th>
								<th>
									Brand
								</th>
								<th>
									Problem Type
								</th>
								<th>
									Status
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								// print_r($statusflm);
							
								$no = 0;
								foreach($statusflm as $row): 
								$no++;
								
								$branch = $db->query("SELECT * FROM master_branch WHERE id='$row->cabang'")->row();
								$pro_type = json_decode($row->problem_type);	
								$ary = array();
								foreach($pro_type as $arr) {
									$ary[] = $db->query('SELECT nama_kategori FROM kategori WHERE id_kategori="'.$arr.'"')->row()->nama_kategori;
								}
							?>
							<tr>
								<td><?php echo $row->id_ticket;?></td>
								<td><?php echo $row->bank;?></td>
								<td><?php echo $row->type;?></td>
								<td><?php echo $row->type_mesin;?></td>
								<td><?php echo implode(", ",$ary);?></td>
								<td>
									<?php 
										if($row->accept_time==null && $row->data_solve=="") {
											echo '<span class="badge_style b_pending">Waiting PIC</span>';
										} else if($row->accept_time!==null && $row->data_solve=="") {
											echo '<span class="badge_style b_medium">Job Accepted</span>';
										}  else if($row->accept_time!==null && $row->data_solve!=="") {
											if($row->status_ticket=="CLOSED") {
												echo '<span class="badge_style b_done">Job Done</span>';
											} else if($row->status_ticket=="PENDING") {
												echo '<span class="badge_style b_away">Job PENDING</span>';
											} else if($row->status_ticket=="SLM") {
												echo '<span class="badge_style b_suspend">Refer to SLM</span>';
											}
										}
									?>
									
									<br>
								
									<!--<span class="badge_style b_low">Pending A</span>
									<span class="badge_style b_medium">Pending B</span>
									<span class="badge_style b_high">Pending C</span>
									
									<span class="badge_style b_done">Pending D</span>
									<span class="badge_style b_away">Pending E</span>
									<span class="badge_style b_suspend">Pending F</span>-->
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