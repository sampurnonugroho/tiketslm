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
        </style>
    </head>

    <body>
		<?php foreach($rows as $r) { ?>
			<table style="width: 100%; font-size: 9px; page-break-before: after;" border="1" class="sixth">
				<thead>
					<tr>
						<td colspan="6" align="left" width="50%">
							<h4 style="font-size: 12px; margin-top: 0px; margin-bottom: 0px">PT. BINTANG JASA ARTHA KELOLA</h4>
							<h6 style="font-size: 11px; font-weight: normal; margin-top: 0px; margin-bottom: 0px">
								Jl. Dharmawangsa X No. 21<br>
								Kebayoran Baru - Jakarta 12160<br>
								Telp./Fax. : (021) 2751 4201 / 2751 4204
							</h6>
						</td>
						<td rowspan="3" colspan="6" align="center" width="50%">
							<font style="font-size: 24px; font-weight: bold">
								<?=$r['metode']?>
							</font>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="6">
							BILL OF CARRIAGE
						</td>
					</tr>
					<tr>
						<td align="left" colspan="3">
							TANGGAL <font style="font-weight: bold"><?=$r['date']?></font>
						</td>
						<td align="center" colspan="1">
							NO.SERI
						</td>
						<td align="center" colspan="2">
							
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="6" height="30px">
							PENGIRIM <br><br>
							<font style="font-weight: bold"><?=$r['pengirim']?></font>
						</td>
						<td align="left" style="vertical-align: top;" colspan="6">
							PENERIMA <br><br>
							<font style="font-weight: bold"><?=$r['penerima']?></font>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="6">
							ALAMAT
						</td>
						<td align="center" colspan="6">
							ALAMAT
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="6">
							CLIENT
						</td>
						<td align="left" style="vertical-align: top;" colspan="6">
							CLIENT
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="6" height="30px">
							ALAMAT	
						</td>
						<td align="left" style="vertical-align: top;" colspan="6">
							ALAMAT
						</td>
					</tr>
					<tr>
						<td align="left" colspan="3">
							KODE POS 
						</td>
						<td align="left" colspan="3">
							WILAYAH
						</td>
						<td align="left" colspan="3">
							KODE POS 
						</td>
						<td align="left" colspan="3">
							WILAYAH
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="6">
							NAMA YANG DI HUBUNGI 
							<br><br>
						</td>
						<td align="left" style="vertical-align: top;" colspan="4">
							NAMA YANG DI HUBUNGI
						</td>
						<td align="left" style="vertical-align: top;" colspan="2">
							KM TIBA
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="6">
							TELP
						</td>
						<td align="left" style="vertical-align: top;" colspan="6">
							TELP
						</td>
					</tr>
					<tr>
						<td align="left" colspan="3">
							CUSTODIAN 1
						</td>
						<td align="left" colspan="3">
							CUSTODIAN 2
						</td>
						<td align="left" colspan="3">
							GUARD
						</td>
						<td align="left" colspan="3">
							NO MOBIL
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="12" height="30px">
							TERBILANG 
							<center><font style="font-weight: bold;"># <?=$r['terbilang']?> #</font></center>
						</td>
					</tr>
					<tr>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							PECAHAN
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							MATA UANG
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							JUMLAH
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="3">
							NILAI
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							PECAHAN
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							MATA UANG
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							JUMLAH
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="3">
							NILAI
						</td>
					</tr>
					<?php 
						$count = 0;
						foreach($r['uang'] as $key => $row) {
							
							if($row!=0) {
								$count++;
								if($key=="kertas_100k") {
									echo '
										<tr>
											<td>
												100.000
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*100000), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								} else if($key=="kertas_50k") {
									echo '
										<tr>
											<td>
												50.000
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*50000), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								} else if($key=="kertas_20k") {
									echo '
										<tr>
											<td>
												20.000
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*20000), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								} else if($key=="kertas_10k") {
									echo '
										<tr>
											<td>
												10.000
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*10000), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								} else if($key=="kertas_5k") {
									echo '
										<tr>
											<td>
												5.000
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*5000), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								} else if($key=="kertas_2k") {
									echo '
										<tr>
											<td>
												2.000
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*2000), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								} else if($key=="kertas_1k") {
									echo '
										<tr>
											<td>
												1.000
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*1000), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								} else if($key=="logam_1k") {
									echo '
										<tr>
											<td>
												1.000
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*1000), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								} else if($key=="logam_500") {
									echo '
										<tr>
											<td>
												500
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*500), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								} else if($key=="logam_200") {
									echo '
										<tr>
											<td>
												200
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*200), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								} else if($key=="logam_100") {
									echo '
										<tr>
											<td>
												100
											</td>
											<td>IDR</td>
											<td>'.$row.'</td>
											<td colspan="3">Rp. '.number_format(($row*100), 0, ",", ".").',-</td>
											<td></td><td></td><td></td><td colspan="3"></td>
										</tr>
									';
								}
							}
						}

						for($i = 0; $i<(10-$count); $i++) {
							echo '
								<tr>
									<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
								</tr>
							';
						}
					?>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="3">
							NO.KANTONG / TAS
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							NO.SEGEL
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							NO.KANTONG / TAS
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							NO.SEGEL
						</td>
					</tr>
					<tr>
						<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
						<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
					</tr>
					<tr>
						<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
						<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
					</tr>
					<tr>
						<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
						<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
					</tr>
					<tr>
						<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
						<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
					</tr>
					<tr>
						<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
						<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
					</tr>
					
					<tr>
						<td align="center" colspan="6">
							SERAH / TERIMA
						</td>
						<td align="center" colspan="6">
							SERAH / TERIMA
						</td>
					</tr>
					
					<tr>
						<td colspan="3">NAMA</td><td colspan="3">NAMA</td>
						<td colspan="3">NAMA</td><td colspan="3">NAMA</td>
					</tr>
					<tr>
						<td colspan="3">KTP/KPP</td><td colspan="3">KTP/KPP</td>
						<td colspan="3">KTP/KPP</td><td colspan="3">KTP/KPP</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align: top;" align="left">TANGGAL <BR><BR></td>
						<td colspan="1" style="vertical-align: top;" align="left">JAM</td>
						<td colspan="2" style="vertical-align: top;" align="left">TANGGAL</td>
						<td colspan="1" style="vertical-align: top;" align="left">JAM</td>
						<td colspan="2" style="vertical-align: top;" align="left">TANGGAL</td>
						<td colspan="1" style="vertical-align: top;" align="left">JAM</td>
						<td colspan="2" style="vertical-align: top;" align="left">TANGGAL</td>
						<td colspan="1" style="vertical-align: top;" align="left">JAM</td>
					</tr>
					<tr>
						<td colspan="3"><br><br><br><br></td>
						<td colspan="3"></td>
						<td colspan="3"></td>
						<td colspan="3"></td>
					</tr>
					<tr>
						<td colspan="12"><span style="visibility: hidden">a</span></td>
					</tr>
					<tr>
						<td colspan="1" align="center">CUSTODIAN<br><br></td>
						<td colspan="1" align="center">ID</td>
						<td colspan="4" rowspan="5" style="vertical-align: top;" align="center">TANDA TANGAN</td>
						<td colspan="1" align="center">VAULT</td>
						<td colspan="1" align="center">ID</td>
						<td colspan="4" rowspan="5" style="vertical-align: top;" align="center">TANDA TANGAN</td>
					</tr>
					<tr>
						<td colspan="1" style="vertical-align: top;" align="center">TERIMA<br><br></td>
						<td colspan="1" style="vertical-align: top;" align="center">DISERAHKAN</td>
						<td colspan="1" style="vertical-align: top;" align="center">TERIMA</td>
						<td colspan="1" style="vertical-align: top;" align="center">DISERAHKAN</td>
					</tr>
					<tr>
						<td colspan="1" style="vertical-align: top;" align="center">TANGGAL<br><br></td>
						<td colspan="1" style="vertical-align: top;" align="center">WAKTU</td>
						<td colspan="1" style="vertical-align: top;" align="center">TANGGAL</td>
						<td colspan="1" style="vertical-align: top;" align="center">WAKTU</td>
					</tr>
					<tr>
						<td colspan="1" style="vertical-align: top;" align="center">TANPA CATATAN<br><br></td>
						<td colspan="1" style="vertical-align: top;" align="center">ADA CATATAN</td>
						<td colspan="1" style="vertical-align: top;" align="center">TANPA CATATAN</td>
						<td colspan="1" style="vertical-align: top;" align="center">ADA CATATAN</td>
					</tr>
					<tr>
						<td colspan="2" align="center">NO BERITA ACARA (KALAU ADA)</td>
						<td colspan="2" align="center">NO BERITA ACARA (KALAU ADA)</td>
					</tr>
					<tr>
						<td colspan="2" align="center">ORIGINAL 1</td>
						<td colspan="4" align="center">ORIGINAL 2 - PENGIRIM</td>
						<td colspan="2" align="center">ORIGINAL 3 - PENERIMA</td>
						<td colspan="4" align="center">ORIGINAL 4 - ARSIP</td>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		<?php } ?>
    </body>
</html>