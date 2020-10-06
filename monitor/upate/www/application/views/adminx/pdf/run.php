<html>
    <head>
        <style>
            @page { margin: 0px; size: 21cm 29.5cm portrait; }
        
            @font-face {
                font-family: "aaaaa";
                src: URL("'.base_url().'depend/font/serif_dot_digital-7.ttf") format("truetype");
            }
        
            body {
                margin: 27px 30px 0px 30px; size: 14cm 22cm landscape;
                font-family: Calibri;            
            }
        
            table.first {
                font-family: Calibri;            
                font-size: 8pt;
                width: 100%;
            }
            
            #h3 {
                font-family: Calibri; 
                font-size: 12pt;
            }
            
            table.first td {
                line-height: 1px;
            }	
            
            table.second {
                width: 100%;
            }
            
            table.second td {
                line-height: 12px;
            }
            
            .third {
                font-family: Calibri;       
                font-size: 8pt;
                border: 1px solid black;
                border-collapse: collapse;
                position: absolute;
                top: 30;
                right: 260;
                border-style: solid;
            }
            
            .fourth {
                font-family: Calibri;       
                font-size: 8pt;
                border-collapse: collapse;
                border: 1px solid black;
                border-style: solid;
            }
            
            .fifth {
                font-family: Calibri;       
                font-size: 8pt;
                border-collapse: collapse;
                border: 1px solid black;
                border-style: solid;
            }
            
            .sixth {
                font-family: Calibri;       
                border: none;
                border-collapse: collapse;
            }
            
            .sixth td {
                padding: 4px;
                border: 1px solid black;
                border-style: solid;
            }
            
            table.fourth td {
                padding: 4px;
                border: 1px solid black;
                border-style: solid;
            }
            
            #noborder {
                border: none;
            }
            #noborderbottom {
                border-bottom: 0px solid white;
            }
            #nobordertop {
                border-top: 0px solid white;
            }
            .noborder {
                border: 0px solid white;
            }
            .noborderbottom {
                border-bottom: 0px solid white;
            }
            .nobordertop {
                border-top: 0px solid white;
            }
            .noborderright {
                border-right: 0px solid white;
            }
            .noborderleft {
                border-left: 0px solid white;
            }
            
            .alignleft {
                float: left;
            }
            .alignright {
                float: right;
            }
			
			div.page_break + div.page_break{
				page-break-before: always;
			}
        </style>
		<title>PDF RUNSHEET CASHREPLENISH</title>
    </head>

    <body>
		<?php 
			foreach($data as $row) {
				extract($row);
			
		?>
				
				<div class="page_break"></div>
				<table class="first" >
					<tr>
						<td width="50%">
							<p id="h3">PT. BINTANG JASA ARTHA KELOLA</p>
							<p>REPORT REPLENISH - RETURN ATM</p>
							<hr style="border-style: solid;height:1px;border:none;color:#333;background-color:#333;margin-top: -10px" />
							
							<table class="second">
								<tr>
									<td style="width: 60px">LOCATION</td>
									<td style="width: 10px">:</td>
									<td><?=(isset($lokasi) ? $lokasi : "No Data")?></td>
								</tr>
								<tr>
									<td style="width: 60px">ID</td>
									<td style="width: 10px">:</td>
									<td><?=(isset($wsid) ? $wsid : "No Data")?></td>
								</tr>
							</table>
							<table class="second">
								<tr>
									<td style="width: 60px">BANK</td>
									<td style="width: 10px">:</td>
									<td><?=(isset($bank) ? $bank : "No Data")?></td>
									
									<td style="width: 60px">DENOM</td>
									<td style="width: 10px">:</td>
									<td><?=(isset($denom) ? $denom : "No Data")?></td>
								</tr>
								<tr>
									<td style="width: 60px">TYPE</td>
									<td style="width: 10px">:</td>
									<td><?=(isset($type_mesin) ? $type_mesin : "No Data")?></td>
									
									<td style="width: 60px">VALUE</td>
									<td style="width: 10px">:</td>
									<td><?=(isset($value) ? $value : "No Data")?></td>
								</tr>
							</table>
						</td>
						<td width="15%">
							<center>
								
							</center>
						</td>
						<td width="35%">
							<table class="second">
								<tr>
									<td style="width: 150px">TANGGAL</td>
									<td style="width: 10px">:</td>
									<td><?=date("d-M-Y", strtotime(explode(" ", $date)[0]))?></td>
								</tr>
								<tr>
									<td>TIME REPLENISH(CSO)</td>
									<td>:</td>
									<td>...........................</td>
								</tr>
								<tr>
									<td>TIME PREPARE BAG(CPC)</td>
									<td>:</td>
									<td><?=date("H:i", strtotime(explode(" ", $updated_date_cpc)[1]))?></td>
								</tr>
							</table>
							<hr style="height:1px;border:none;color:#333;background-color:#333;width:100%; text-align: left; margin: 2px" />
							<table class="second">
								<tr>
									<td style="width: 150px">CASHIER</td>
									<td style="width: 10px">:</td>
									<td>...........................</td>
								</tr>
								<tr>
									<td>NO. MEJA</td>
									<td>:</td>
									<td>...........................</td>
								</tr>
								<tr>
									<td>JAM PROSES</td>
									<td>:</td>
									<td>...........................</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table class="third">
					<tr>
						<td style="width: 45px; text-align: center; border: 1px solid black; border-style: solid;">RUN</td>
					</tr>
					<tr>
						<td style="width: 45px; text-align: center; font-size: 24px;"><?=(isset($run) ? $run : "No Data")?></td>
					</tr>
				</table>
				
				<!-- INNER CONTENT DISINI -->
					<?php 
						if($type=="ATM") { 
					?>
							<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
								<thead>
									<tr>
										<td rowspan="2" align="center">PREPARATION</td>
										<td rowspan="2" align="center">SEAL PREPARE</td>
										<td colspan="2" align="center">STATUS</td>
										<td rowspan="2" align="center">SEAL RETURN</td>
										<td rowspan="2" align="center">VALUE</td>
										<td rowspan="2" align="center">TOTAL RETURN</td>
									</tr>
									<tr>
										<td width="90" align="center">PENGALIHAN</td>
										<td width="90" align="center">CANCEL</td>
									</tr>
								</thead>
								<tbody>
								<?php 
									foreach($data as $k => $r) {
										echo '
											<tr>
												<td>'.$r['csst'].'</td>
												<td align="center">'.$r['seal'].'</td>
												<td></td>
												<td></td>
												<td align="center"></td>
												<td align="center"></td>
												<td align="left">Rp.</td>
											</tr>';
									}
								?>
									<tr>
										<td colspan="5" id="noborder"></td>
										<td align="center"></td>
										<td align="left">Rp.</td>
									</tr>
								</tbody>
							</table>
					<?php 
						} else if($type=="CRM") { 
					?>
							<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
								<thead>
									<tr>
										<td rowspan="2" align="center" width="20px">CSST</td>
										<td rowspan="2" align="center">DENOM</td>
										<td rowspan="2" align="center">TOTAL</td>
										<td rowspan="2" align="center">SEAL PREPARE</td>
										<td colspan="2" align="center">STATUS</td>
										<td rowspan="2" align="center">SEAL RETURN</td>
										<td rowspan="2" align="center">VALUE</td>
										<td rowspan="2" align="center">TOTAL RETURN</td>
									</tr>
									<tr>
										<td width="20" align="center">PENGALIHAN</td>
										<td width="20" align="center">CANCEL</td>
									</tr>
								</thead>
								<tbody>
								<?php 
									$ttl = 0;
									foreach($data as $k => $r) {
										list($seal_1, $denom_1, $value_1) = explode(";", $r['seal']);
										
										$ttl = ($denom_1*1000) * $value_1;
										
										echo '
											<tr>
												<td align="center">'.$r['csst'].'</td>
												<td align="center">
													<span class="alignleft">'.(is_numeric($denom_1) ? number_format(($denom_1*1000), 0, ",",".") : $denom_1).'</span>
													<span class="alignright">'.$value_1.'</span>
												</td>
												<td align="center">'.($ttl!=0 ? number_format($ttl, 0, ",",".") : "").'</td>
												<td align="center">'.$seal_1.'</td>
												<td></td>
												<td></td>
												<td align="center"></td>
												<td align="center"></td>
												<td align="left">Rp.</td>
											</tr>';
									}
								?>
									<tr>
										<td colspan="7" id="noborder"></td>
										<td align="center"></td>
										<td align="left">Rp.</td>
									</tr>
								</tbody>
							</table>
					<?php 
						} else if($type=="CDM") { 
					?>
							<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
								<thead>
									<tr>
										<td rowspan="2" align="center">PREPARATION</td>
										<td rowspan="2" align="center">SEAL PREPARE</td>
										<td colspan="2" align="center">STATUS</td>
										<td rowspan="2" align="center">SEAL RETURN</td>
										<td rowspan="2" align="center">VALUE</td>
										<td rowspan="2" align="center">TOTAL RETURN</td>
									</tr>
									<tr>
										<td width="90" align="center">PENGALIHAN</td>
										<td width="90" align="center">CANCEL</td>
									</tr>
								</thead>
								<tbody>
								<?php 
									foreach($data as $k => $r) {
										echo '
											<tr>
												<td align="center">'.$r['csst'].'</td>
												<td align="center">'.$r['seal'].'</td>
												<td></td>
												<td></td>
												<td align="center"></td>
												<td align="center"></td>
												<td align="left">Rp.</td>
											</tr>';
									}
								?>
									<tr>
										<td colspan="5" id="noborder"></td>
										<td align="center"></td>
										<td align="left">Rp.</td>
									</tr>
								</tbody>
							</table>
					<?php 
						}
					?>
				<!-- /INNER CONTENT DISINI -->
				
				<table style="width: 80%; font-size: 10px; margin-top: -14px">
					<tr>
						<td style="width: 120px">TOTAL CATRIDGE</td>
						<td style="width: 10px">:</td>
						<td><?=(isset($ctr) ? $ctr : "No Data")?></td>
						
						<td style="width: 120px">NO. BAG</td>
						<td style="width: 10px">:</td>
						<td><?=(isset($bag_no) ? $bag_no : "No Data")?></td>
					</tr>
					<tr>
						<td style="width: 60px">TOTAL</td>
						<td style="width: 10px">:</td>
						<td><?=(isset($ttl_all) ? $ttl_all : "No Data")?></td>
						
						<td style="width: 60px">SEAL BAG(CPC)</td>
						<td style="width: 10px">:</td>
						<td><?=(isset($bag_seal) ? $bag_seal : "No Data")?></td>
					</tr>
					<tr>
						<td style="width: 60px">TERBILANG</td>
						<td style="width: 10px">:</td>
						<td style="font-weight: bold"><?=(!empty($terbilang) ? "# $terbilang #" : "")?></td>
						
						<td style="width: 60px">SEAL BAG(CSO)</td>
						<td style="width: 10px">:</td>
						<td></td>
					</tr>
				</table>
				
				<table style="width: 100%; font-size: 10px" class="fifth">
					<tr>
						<td align="center" class="noborderbottom noborderright">Prepared By</td>
						<td align="center" class="noborderbottom noborderleft" colspan=2>Received By</td>
						<td align="center" class="noborderbottom" colspan=2>Ops,</td>
						<td align="center" class="noborderbottom">Approval By Return</td>
					</tr>
					<tr>
						<td class="nobordertop noborderbottom" align="center" style="height: 60px" colspan=3></td>
						<td class="nobordertop noborderbottom" colspan=2></td>
						<td class="nobordertop noborderbottom"></td>
					</tr>
					<tr>
						<td style="width: 16.6%" class="nobordertop noborderright" align="center">DUTY CPC</td>
						<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">CSO</td>
						<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">SCC</td>
						<td style="width: 16.6%" class="nobordertop noborderright" align="center">CSO/SCC</td>
						<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">CPC</td>
						<td style="width: 16.6%" class="nobordertop " align="center">DUTY CPC</td>
					</tr>
				</table>
		<?php 
			}
		?>
		
    </body>
</html>