<?php $__env->startSection('content'); ?>

	<!-- Content -->
<div id="content">
		<div class="grid_container">
			<div class="grid_14">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list"></span>
						<h6>Invoice</h6>
					</div>
					<div class="widget_content">
						<div class=" page_content">
							<div class="invoice_container">
								<div class="invoice_action_bar">
									<div class="btn_30_light">
										<a href="#" title="Pay Now"><span class="icon zone_money_co"></span></a>
									</div>
									<div class="btn_30_light">
										<a href="#" title="Print"><span class="icon printer_co"></span></a>
									</div>
									<div class="btn_30_light">
										<a href="#" title="Download .pdf"><span class="icon drive_disk_co"></span></a>
									</div>
								</div>
								<div class="grid_6 invoice_num">
									<span>Invoice# 01010</span>
								</div>
								<div class="grid_6 invoice_date">
									Date: 12/01/2012
								</div>
								<span class="clear"></span>
								<div class="grid_12 invoice_title">
									<center><img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
							</center>
							<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>[ INVOICE ]</p>
								</div>
								<div class="grid_9 invoice_to">
									<ul>
										<li>
										<strong><span>From :</span></strong>
										<span>PT. BINTANG JASA ARTHA KELOLA</span>
										<span>Jl. Dharmawangsa No. 123</span>
										<span>Jakarta - Indonesia</span>
										</li>
									</ul>
								</div>
								<div class="grid_3 invoice_from">
									<ul>
										<li>
										<strong><span>To :</span></strong>
										<span>Client Bank/Retail/Other</span>
										<span>Jl. Panjang Tak Berpenghujung No. 123</span>
										<span>Jakarta - Indonesia</span>
										</li>
									</ul>
								</div>
								<span class="clear"></span>
								<div class="grid_12 invoice_details">
									<div class="invoice_tbl">
										<table>
										<thead>
										<tr class=" gray_sai">
											<th>
												No.
											</th>
											<th>
												Services/Request Order ID
											</th>
											<th>
												Date
											</th>
											<th>
												Details
											</th>
											<th>
												Unit Price
											</th>
											<th>
												Total
											</th>
										</tr>
										</thead>
										<tbody>
										<tr>
											<td>
												1
											</td>
											<td class="left_align">
												#DMC 01245
											</td>
											<td>
												12/5/2012
											</td>
											<td class="left_align">
												5GB Dedicated Hosting
											</td>
											<td>
												$250
											</td>
											<td>
												$250
											</td>
										</tr>
										<tr>
											<td>
												2
											</td>
											<td class="left_align">
												#DMC 01246
											</td>
											<td>
												15/5/2012
											</td>
											<td class="left_align">
												20GB Mail server
											</td>
											<td>
												$450
											</td>
											<td>
												$900
											</td>
										</tr>
										<tr>
											<td>
												3
											</td>
											<td class="left_align">
												#DMC 01248
											</td>
											<td>
												19/5/2012
											</td>
											<td class="left_align">
												Domain Registration
											</td>
											<td>
												$10
											</td>
											<td>
												$50
											</td>
										</tr>
										<tr>
											<td colspan="5" class="grand_total">
												Grand Total:
											</td>
											<td>
												IDR 1200
											</td>
										</tr>
										</tbody>
										</table>
									</div>
									<p class="amount_word">
										Amounts in word: <b><i>Sekian Sekian Sekian Rupiah</i></b>
									</p>
									<blockquote class="quote_blue">
										<p>
											Barang/jasa yang sudah direquest tidak dapat dibatalkan
										</p>
									</blockquote>
									<h5 class="notes">Notes:</h5>
									<p>
										Invoice ini berlaku sejak tanggal dikeluarkan hingga tanggal xx/xx/xxxx
									</p>
								</div>
								<span class="clear"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<span class="clear"></span>
	</div>
		
	<div class="clear"></div>
	
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>