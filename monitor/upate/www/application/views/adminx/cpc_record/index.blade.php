@extends('layouts.master')

@section('content')
<style>
	#preview {
		float: right; 
		height: 803px; 
		width: 100%; 
		border: 1px solid #666; 
		-moz-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
		-webkit-box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
		box-shadow: 0px 0px 6px rgba(0,0,0,0.5);
	}
</style>
	<!-- Content -->
<div id="content">
		<div class="grid_container">
			<div class="grid_14">
				<div class="preview_pdf" hidden>
					<button style="margin-top: 5px; float: right" class="btn btn-primary pull-right" id='close_preview' type="button">Close</button>
					<iframe id="preview" name="preview" src="about:blank" frameborder="0" marginheight="0" marginwidth="0"></iframe>
				</div>
			
				<div class="widget_wrap preview_table">
					<div class="widget_top">
						<span class="h_icon list"></span>
					</div>
					<div class="widget_content">
						<div class=" page_content">
							<div class="invoice_container">
								
								<div class="grid_12 invoice_title">
									<center><img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
								</center>
								<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>[ REPORT ]</p>
								</div>
								<div class="invoice_action_bar">
									<div class="btn_30_light">
										<a href="<?=base_url()?>cpc_record/add" title="Add Data"><span class="icon add_co"></span><span class="btn_link">Tambah</span></a>
									</div>
									<div class="btn_30_light">
										<a href="<?=base_url()?>excel/export_cpc_xls" title="Export Data"><span class="icon page_excel_co"></span><span class="btn_link">Export</span></a>
									</div>
								</div>
								<span class="clear"></span>
								<div class="grid_12 invoice_details">
									<div class="invoice_tbl">
										<style>
											#view_data tr td {
												border: 1px solid white;
											}
											
											#view_data th, td {
												border-bottom: 1px solid #ddd;
												text-align: left;
											}
											
											#view_data th {
												vertical-align: middle;
												text-align: center;
											}
											
											#view_data th {
												background: #e7e7e7;
												background-color: #ebebeb;
											}
											div.scrollmenu {
												background-color: #333;
												overflow: auto;
												white-space: nowrap;
											}
										</style>
										<div class="scrollmenu">
											<table id="view_data">
												<thead>
													<tr>
														<th rowspan=2>
															No.
														</th>
														<th rowspan=2>
															Tanggal
														</th rowspan=2>
														<th rowspan=2>
															Catatan
														</th>
														<th rowspan=2>
															Keterangan
														</th>
														<th colspan=2>
															DENOM 100 	
														</th>
														<th rowspan=2 style="background-color: #b3c984">
															SALDO 100
														</th>
														<th colspan=2>
															DENOM 50 	
														</th>
														<th rowspan=2 style="background-color: #b3c984">
															SALDO 50
														</th>
														<th rowspan=2>
															SALDO
														</th>
														<th rowspan=2>
															AKSI
														</th>
													</tr>
													<tr>
														<th>
															DEBET 100 
														</th>
														<th>
															KREDIT 100 
														</th>
														<th>
															DEBET 50 
														</th>
														<th>
															KREDIT 50 
														</th>
													</tr>
												</thead>
												<tbody>
													<?php 
														$no = 0;
														$prev_debit_100 = 0;
														$prev_kredit_100 = 0;
														$prev_debit_50 = 0;
														$prev_kredit_50 = 0;
														$prev_saldo_100 = 0;
														$prev_saldo_50 = 0;
														$prev_saldo = 0;
														$saldo_100 = 0;
														$saldo_50 = 0;
														$saldo = 0;
														foreach($data_record as $r) {
															$no++;
															if($r->kredit_100==0) {
																$saldo_100 = $prev_saldo_100 + $r->debit_100;
															} else {
																$saldo_100 = $prev_saldo_100 - $r->kredit_100;
															}
															
															if($r->kredit_50==0) {
																$saldo_50 = $prev_saldo_50 + $r->debit_50;
															} else {
																$saldo_50 = $prev_saldo_50 - $r->kredit_50;
															}
															
															$saldo = $saldo_100 + $saldo_50;
													?>
															<tr>
																<td><?=$no?></td>
																<td style="text-align: left"><?=($r->tanggal=="0000-00-00" ? "" : date("d-m-Y", strtotime($r->tanggal)))?></td>
																<td style="text-align: left"><?=$r->catatan?></td>
																<td style="text-align: left"><?=strtoupper(str_replace("_", " ", $r->keterangan))?></td>
																<!--<td style="text-align: right"><?=($r->debit_100==0 ? "" : number_format($r->debit_100, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->kredit_100==0 ? "" : number_format($r->kredit_100, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->saldo_100==0 ? "" : number_format($r->saldo_100, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->debit_50==0 ? "" : number_format($r->debit_50, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->kredit_50==0 ? "" : number_format($r->kredit_50, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->saldo_50==0 ? "" : number_format($r->saldo_50, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->saldo==0 ? "" : number_format($r->saldo, 0, ",", ","))?></td>-->
																<td style="text-align: right"><?=($r->debit_100==0 ? "" : number_format($r->debit_100, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->kredit_100==0 ? "" : number_format($r->kredit_100, 0, ",", ","))?></td>
																<td style="background-color: #b3c984; text-align: right"><?=($saldo_100==0 ? "" : number_format($saldo_100, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->debit_50==0 ? "" : number_format($r->debit_50, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->kredit_50==0 ? "" : number_format($r->kredit_50, 0, ",", ","))?></td>
																<td style="background-color: #b3c984; text-align: right"><?=($saldo_50==0 ? "" : number_format($saldo_50, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($saldo==0 ? "" : number_format($saldo, 0, ",", ","))?></td>
																<td>
																	<div class="btn_30_light">
																		<a onClick="openDelete('<?php echo $r->id;?>', '<?php echo base_url();?>cpc_record/delete')" href="#" title="Pay Now"><span class="icon delete_co"></span></a>
																	</div>
																	<div class="btn_30_light">
																		<a onClick="window.location.href='<?php echo base_url();?>cpc_record/edit/<?php echo $r->id;?>'" href="#" title="Print"><span class="icon application_edit_co"></span></a>
																	</div>
																</td>
															</tr>
													<?php 
															$prev_debit_100 = $r->debit_100;
															$prev_kredit_100 = $r->kredit_100;
															$prev_debit_50= $r->debit_50;
															$prev_kredit_50 = $r->kredit_50;
															
															$prev_saldo_100 = $saldo_100;
															$prev_saldo_50 = $saldo_50;
														}
													?>
												</tbody>
											</table>
										</div>
										<!--<table id="">
										<thead>
										<tr class=" gray_sai">
											<th>
												No.
											</th>
											<th>
												No Bukti
											</th>
											<th>
												Date
											</th>
											<th colspan="2">
												Details
											</th>
											<th>
												Debet
											</th>
											<th>
												Kredit
											</th>
										</tr>
										</thead>
										<tbody>
										<tr>
											<td>
												1
											</td>
											<td class="left_align">
												BKM01
											</td>
											<td class="left_align">
												2019-06-01
											</td>
											<td colspan="2" class="left_align" style="font-weight: bold">
												Setoran Modal Tuan Kaler
											</td>
											<td align="center">&nbsp;</td>
											<td align="center">&nbsp;</td>
										</tr>
										
										<tr>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td class="left_align">11001-Kas</td>
										  <td style="text-align: right">0</td>
										  <td style="text-align: right">0</td>
										</tr>
										<tr>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td class="left_align">31001-Modal Tn. Kaler</td>
										  <td style="text-align: right">0</td>
										  <td style="text-align: right">0</td>
										</tr>
										<tr>
											<td>
												2
											</td>
											<td class="left_align">
												BKM02
											</td>
											<td class="left_align">
												2019-06-02
											</td>
											<td colspan="2" class="left_align" style="font-weight: bold">
												Pembelian Peralatan Perusahaan
											</td>
											<td align="center">&nbsp;</td>
											<td align="center">&nbsp;</td>
										</tr>
										
										<tr>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td class="left_align">12001-Peralatan Usaha</td>
										  <td style="text-align: right">0</td>
										  <td style="text-align: right">0</td>
										</tr>
										<tr>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td>&nbsp;</td>
										  <td class="left_align">11001-Kas</td>
										  <td style="text-align: right">0</td>
										  <td style="text-align: right">0</td>
										</tr>
										</tbody>
										</table>-->
									</div>
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
	<script>
		$(document).on('click', '.detail_preview', function(){ 
			$(".preview_pdf").show();
			$(".preview_table").hide();
			
			document.getElementById("preview").src = "";
			var id = $(this).closest('td').find('#id').text();
			// alert(id);
			setTimeout(function() {
			var websel = "<?=base_url()?>pdf/generate/"+id;
				document.getElementById("preview").src = websel;
			}, 100);
		});
		
		$(document).on('click', '#close_preview', function(){ 
			$(".preview_pdf").hide();
			$(".preview_table").show();
		});
	</script>
@endsection