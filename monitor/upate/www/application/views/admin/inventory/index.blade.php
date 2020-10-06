@extends('layouts.master')

@section('content')
	<!-- Content -->
	<article class="container_12">
		<style>
			table {
				width:100%;
			}
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
			}
			th, td {
				padding: 15px;
				text-align: left;
			}
			table.t01 tr:nth-child(even) {
				background-color: #fff;
			}
			table.t01 tr:nth-child(odd) {
				background-color: #D3D6FF;
			}
			table.t01 th {
				background-color: #eee;
				color: black;
			}
		</style>
		
		<section class="grid_12">
			<div class="block-border">
			<div class="block-content no-title dark-bg">
				<div id="control-bar">
					<div class="container_16">
						<div class="float-left">
							<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
						</div>
						<div class="float-right" hidden>
							<button type="button" onClick="window.location.href='<?=base_url()?>inventory/add'">Add Data</button>
						</div>
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
	
			<div class="widget_wrap">
				<div class="widget_top">
					<span class="h_icon blocks_images"></span>
					<h6>Supplies & Spareparts Data</h6>
				</div>
				<div class="widget_content">
					<table class="display t01">
						<thead>
							<tr>
								<th width="80">
									Supplies Name
								</th>
								<th width="45">
									Total
								</th>
								<th width="20">
									Quantity
								</th>
								<th width="30">
									Used
								</th>
								<th width="30">
									Type
								</th>
								<th hidden>
									Operation
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								foreach($datainventory1 as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row->name;?></td>
								<td><?php echo (($row->qty)+($row->used));?></td>
								<td><?php echo ($row->qty);?></td>
								<td><?php echo ($row->used);?></td>
								<td>
								    <?php 
								        if($row->name=="MESIN ATM") {
								            echo "ASSETS";
								        } else {
								            echo ($row->type=="supplies"?"Supplies":"Spare Part");
								        }
								    ?>
								</td>
								<td hidden>
									<span><a class="action-icons c-edit" onClick="window.location.href='<?php echo base_url();?>inventory/edit/<?php echo $row->id;?>'" href="#" title="Edit">Edit</a></span>
									<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id;?>', '<?php echo base_url();?>inventory/delete')" href="#" title="delete">Delete</a></span>
								</td>
							</tr>
							<?php 
								endforeach; 
							?>
						</tbody>
					</table>
					<br>
					<br>
					<table class="display t01">
						<thead>
							<tr>
								<th width="80">
									Supplies Name
								</th>
								<th width="45">
									Total
								</th>
								<th width="20">
									Quantity
								</th>
								<th width="30">
									Used
								</th>
								<th width="30">
									Type
								</th>
								<th hidden>
									Operation
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$no = 0;
								foreach($datainventory2 as $row): 
								$no++;
							?>
							<tr>
								<td><?php echo $row->name;?></td>
								<td><?php echo (($row->qty)+($row->used));?></td>
								<td><?php echo ($row->qty);?></td>
								<td><?php echo ($row->used);?></td>
								<td><?php echo ($row->type=="supplies"?"Supplies":"Spare Part")?></td>
								<td hidden>
									<span><a class="action-icons c-edit" onClick="window.location.href='<?php echo base_url();?>inventory/edit/<?php echo $row->id;?>'" href="#" title="Edit">Edit</a></span>
									<span><a class="action-icons c-delete" onClick="openDelete('<?php echo $row->id;?>', '<?php echo base_url();?>inventory/delete')" href="#" title="delete">Delete</a></span>
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