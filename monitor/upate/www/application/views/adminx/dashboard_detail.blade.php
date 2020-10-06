@extends('layouts.master')

@section('content')
	<?php 
		// print_r($data_flm);
	?>

			<style>
				.stat_block table tr td {
					padding: 8px 10px;
					font-size: 11px;
					border: #ccc 1px solid;
					background: #fff;
				}
			</style>
			<section class="grid_12">
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
				<div class="widget_wrap preview_table">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Data Ticket <?=$id_ticket?></h6>
					</div>
					<div class="widget_content">
						<div style="padding: 20px">
							<?php 
								foreach($data_ticket as $r) {
									if($r->images1!="") {
										echo '
											<img width="200px" height="280" src="'.$r->images1.'"/>
										';
									}
									if($r->images2!="") {
										echo '
											<img width="200px" height="280" src="'.$r->images2.'"/>
										';
									}
									if($r->images3!="") {
										echo '
											<img width="200px" height="280" src="'.$r->images3.'"/>
										';
									}
									if($r->images4!="") {
										echo '
											<img width="200px" height="280" src="'.$r->images4.'"/>
										';
									}
									if($r->images5!="") {
										echo '
											<img width="200px" height="280" src="'.$r->images5.'"/>
										';
									}
								}
							?>
						</div>
					</div>
				</div>
			</section>
		
	
		<div class="clear"></div>
	
	
	</article>
@endsection