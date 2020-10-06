<?php $__env->startSection('content'); ?>
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
	
	.ui-datepicker {
		width: 17.8em !important;
	}
	#my_tables td {
		border: 0px solid white !important;
	}
</style>

<script>
	function openFilter() {
		$("#html_content").toggle('slow');	
	}
</script>
	<!-- Content -->
<div id="content">
		<div class="grid_container">
			<div class="grid_14">
				
				<div id="html_content" hidden>
					<form class="form mysets-area">
						<table id="my_tables">
							<tr>
								<td>
									<p>
										<label for="simple-calendar">Branch</label>
									</p>
								</td>
								<td>
									<p>
										<select>
											<option> -Pilih Branch- </option>
											<option> CIMB JAKARTA </option>
										</select>
									</p>
								</td>
							</tr>
							<tr>
								<td>
									<p>
										<label for="simple-calendar">Bank</label>
									</p>
								</td>
								<td>
									<p>
										<select>
											<option> -Pilih Bank- </option>
											<option> CIMB NIAGA </option>
										</select>
									</p>
								</td>
							</tr>
						</table>
					</form>
				</div>
			
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
								<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>[ JOURNAL REPORT ]</p>
								</div>
								<div class="invoice_action_bar">
									<div class="btn_30_light">
										<a href="#" onclick="openFilter()" title="Filter Data"><span class="icon search_co"></span><span class="btn_link">Filter</span></a>
									</div>
									<div class="btn_30_light">
										<a href="<?=base_url()?>jurnal/add" title="Add Data"><span class="icon add_co"></span><span class="btn_link">Tambah</span></a>
									</div>
									<div class="btn_30_light">
										<a href="<?=base_url()?>jurnal/export" title="Export Data"><span class="icon page_excel_co"></span><span class="btn_link">Export</span></a>
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
												height: 220px;
												background-color: #333;
												overflow: auto;
												white-space: nowrap;
											}
											
											
											
											.table-scroll {
												position: relative;
												width: 100%;
												z-index: 1;
												margin: auto;
												overflow: auto;
												height: 400px;
											}

											.table-scroll table {
												width: 100%;
												min-width: 1680px;
												margin: auto;
												border-collapse: separate;
												border-spacing: 0;
											}

											.table-wrap {
												position: relative;
											}

											.table-scroll th,
											.table-scroll td {
												padding: 5px 10px;
												border: 1px solid #000;
												background: #fff;
												vertical-align: middle;
											}
											
											/* safari and ios need the tfoot itself to be position:sticky also */
											.table-scroll tfoot,
											.table-scroll tfoot th,
											.table-scroll tfoot td {
												position: -webkit-sticky;
												position: sticky;
												bottom: 0;
												background: #666;
												color: #fff;
												z-index: 0;
											}
											
											
											.table-scroll tfoot .xxx,
											.table-scroll tfoot .xxx2,
											.table-scroll tfoot .xxx3 {
												z-index: 15;
											}
											
											.zui-sticky-col {
												width: 200px;
												position: -webkit-sticky;
												position: sticky;
												top: 0;
											}
											
											.zui-sticky-col2 {
												width: 200px;
												position: sticky;
												top: 36px;
											}

											a:focus {
												background: red;
											}

											/* testing links*/

											.xxx {
												position: -webkit-sticky;
												position: sticky;
												left: 0;
												z-index: 2;
												background: #ccc;
											}

											.xxx2 {
												position: -webkit-sticky;
												position: sticky;
												left: 81px;
												z-index: 4;
												background: #ccc;
											}

											.xxx3 {
												position: -webkit-sticky;
												position: sticky;
												left: 186px;
												z-index: 6;
												background: #ccc;
											}

											thead .xxx,
											thead .xxx2,
											thead .xxx3,
											tfoot th:first-child {
												z-index: 8;
											}
										</style>
										
										<div id="table-scroll" class="table-scroll">
											<table id="main-table" class="main-table">
												<thead>
													<tr>
														<th rowspan=2 class="zui-sticky-col xxx">
															No.
														</th>
														<th rowspan=2 class="zui-sticky-col xxx2">
															Tanggal
														</th>
														<th rowspan=2 class="zui-sticky-col xxx3">
															ID ATM
														</th>
														<th rowspan=2 class="zui-sticky-col">
															Keterangan
														</th>
														<th colspan=2 class="zui-sticky-col">
															DENOM 100 	
														</th>
														<th rowspan=2 style="background-color: #b3c984" class="zui-sticky-col">
															SALDO 100
														</th>
														<th colspan=2 class="zui-sticky-col">
															DENOM 50 	
														</th>
														<th rowspan=2 style="background-color: #b3c984" class="zui-sticky-col">
															SALDO 50
														</th>
														<th colspan=2 class="zui-sticky-col">
															DENOM 20 	
														</th>
														<th rowspan=2 style="background-color: #b3c984" class="zui-sticky-col">
															SALDO 20
														</th>
														<th rowspan=2 class="zui-sticky-col">
															SALDO
														</th>
													</tr>
													<tr>
														<th class="zui-sticky-col2">
															DEBET 100 
														</th>
														<th class="zui-sticky-col2">
															KREDIT 100 
														</th>
														<th class="zui-sticky-col2">
															DEBET 50 
														</th>
														<th class="zui-sticky-col2">
															KREDIT 50 
														</th>
														<th class="zui-sticky-col2">
															DEBET 20 
														</th>
														<th class="zui-sticky-col2">
															KREDIT 20 
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
														$prev_ket = "";
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
																<th class="xxx"><?=$no?></th>
																<td style="text-align: left" class="xxx2"><?=($r->tanggal=="0000-00-00" ? "" : date("d-m-Y", strtotime($r->tanggal)))?></td>
																<td class="xxx3"><?=$r->wsid?></td>
																<td style="text-align: left"><?=strtoupper(str_replace("_", " ", $r->keterangan_jurnal))?></td>
																<td style="text-align: right"><?=($r->debit_100==0 ? 0 : number_format($r->debit_100, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->kredit_100==0 ? 0 : number_format($r->kredit_100, 0, ",", ","))?></td>
																<td style="background-color: #b3c984; text-align: right"><?=($saldo_100==0 ? 0 : number_format($saldo_100, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->debit_50==0 ? 0 : number_format($r->debit_50, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->kredit_50==0 ? 0 : number_format($r->kredit_50, 0, ",", ","))?></td>
																<td style="background-color: #b3c984; text-align: right"><?=($saldo_50==0 ? 0 : number_format($saldo_50, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->debit_20==0 ? 0 : number_format($r->debit_20, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($r->kredit_20==0 ? 0 : number_format($r->kredit_20, 0, ",", ","))?></td>
																<td style="background-color: #b3c984; text-align: right"><?=($saldo_20==0 ? 0 : number_format($saldo_20, 0, ",", ","))?></td>
																<td style="text-align: right"><?=($saldo==0 ? 0 : number_format($saldo, 0, ",", ","))?></td>
															</tr>
													<?php 
															
															$prev_debit_100 = $r->debit_100;
															$prev_kredit_100 = $r->kredit_100;
															$prev_debit_50= $r->debit_50;
															$prev_kredit_50 = $r->kredit_50;
															
															$prev_saldo_100 = $saldo_100;
															$prev_saldo_50 = $saldo_50;
															$prev_ket = $r->keterangan;
														}
													?>
												</tbody>
												<tfoot>
													<tr>
														<th colspan="" class="xxx"></th>
														<th colspan="" class="xxx2"></th>
														<th colspan="" class="xxx3"></th>
														<th colspan=""></th>
														<th colspan=""></th>
														<th colspan=""></th>
														<th style="text-align: right"><?=($saldo_100==0 ? 0 : number_format($saldo_100, 0, ",", ","))?></th>
														<th colspan="2"></th>
														<th style="text-align: right"><?=($saldo_50==0 ? 0 : number_format($saldo_50, 0, ",", ","))?></th>
														<th colspan="2"></th>
														<th style="text-align: right"><?=($saldo_50==0 ? 0 : number_format($saldo_20, 0, ",", ","))?></th>
														<th style="text-align: right"><?=($saldo==0 ? 0 : number_format($saldo, 0, ",", ","))?></th>
													</tr>
												</tfoot>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>