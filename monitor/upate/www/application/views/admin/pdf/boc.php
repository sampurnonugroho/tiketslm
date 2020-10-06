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
		<title>PDF BILL OF CARRIAGE</title>
    </head>

    <body>
		<?php foreach($rows as $r) { 
			
		?>
			<table style="width: 100%; font-size: 9px; page-break-before: after; color: blue" border="1" class="sixth">
				<thead>
					<tr>
						<td colspan="6" align="left" width="50%">
							<h4 style="color: black; font-size: 12px; margin-top: 0px; margin-bottom: 0px">PT. BINTANG JASA ARTHA KELOLA</h4>
							<h6 style="color: black; font-size: 11px; font-weight: normal; margin-top: 0px; margin-bottom: 0px">
								Jl. Dharmawangsa X No. 21<br>
								Kebayoran Baru - Jakarta 12160<br>
								Telp./Fax. : (021) 2751 4201 / 2751 4204
							</h6>
						</td>
						<td rowspan="3" colspan="6" align="center" width="50%">
							<font style="color: black; font-size: 24px; font-weight: bold">
								<?=$r['metode']?>
							</font>
						</td>
					</tr>
					<tr>
						<td style="color: black" align="center" colspan="6">
							BILL OF CARRIAGE
						</td>
					</tr>
					<tr>
						<td align="left" colspan="3">
							<span style="color: black">TANGGAL</span> <font style="font-weight: bold"><?=$r['date']?></font>
						</td>
						<td style="color: black" align="center" colspan="1">
							NO.SERI
						</td>
						<td align="center" colspan="2">
							<?=$r['no_boc']?>
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="6" height="30px">
							<span style="color: black">PENGIRIM</span>
							<center><font style="font-weight: bold; font-size: 14px"><?=$r['pengirim']?></font></center>
						</td>
						<td align="left" style="vertical-align: top;" colspan="6">
							<span style="color: black">PENERIMA</span>
							<center><font style="font-weight: bold; font-size: 14px"><?=$r['penerima']?></font></center>
						</td>
					</tr>
					<tr>
						<td style="color: black" align="center" colspan="6">
							ALAMAT
						</td>
						<td style="color: black" align="center" colspan="6">
							ALAMAT
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="6">
							<span style="color: black">CLIENT : </span><?=$r['client_pengirim']?>
						</td>
						<td align="left" style="vertical-align: top;" colspan="6">
							<span style="color: black">CLIENT : </span><?=$r['client_penerima']?>
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="6" height="30px">
							<span style="color: black">ALAMAT</span>
							<center><font style="font-weight: bold; font-size: 14px"><?=$r['alamat_pengirim']?></font></center>
						</td>
						<td align="left" style="vertical-align: top;" colspan="6">
							<span style="color: black">ALAMAT</span>
							<center><font style="font-weight: bold; font-size: 14px"><?=$r['alamat_penerima']?></font></center>
						</td>
					</tr>
					<tr>
						<td align="left" colspan="3">
							<span style="color: black">KODE POS</span> : <?=$r['kodepos_pengirim']?>
						</td>
						<td align="left" colspan="3">
							<span style="color: black">WILAYAH</span> : <?=$r['wilayah_pengirim']?>
						</td>
						<td align="left" colspan="3">
							<span style="color: black">KODE POS</span> : <?=$r['kodepos_penerima']?>
						</td>
						<td align="left" colspan="3">
							<span style="color: black">WILAYAH</span> : <?=$r['wilayah_penerima']?>
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="6">
							<span style="color: black">NAMA YANG DI HUBUNGI : </span> 
							<?=$r['pic_pengirim']?>
						</td>
						<td align="left" style="vertical-align: top;" colspan="4">
							<span style="color: black">NAMA YANG DI HUBUNGI : </span>
							<?=$r['pic_penerima']?>
						</td>
						<td align="left" style="vertical-align: top;" colspan="2">
							<span style="color: black">KM TIBA</span>
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="6">
							<span style="color: black">TELP</span> : <?=$r['telp_pengirim']?>
						</td>
						<td align="left" style="vertical-align: top;" colspan="6">
							<span style="color: black">TELP</span> : <?=$r['telp_penerima']?>
						</td>
					</tr>
					<tr>
						<td align="left" colspan="3">
							<span style="color: black">CUSTODIAN 1 : </span><?=$r['custody_1']?>
						</td>
						<td align="left" colspan="3">
							<span style="color: black">CUSTODIAN 2 : </span><?=$r['custody_2']?>
						</td>
						<td align="left" colspan="3">
							<span style="color: black">GUARD : </span><?=$r['security_1']?>
						</td>
						<td align="left" colspan="3">
							<span style="color: black">NO MOBIL : </span><?=$r['police_number']?>
						</td>
					</tr>
					<tr>
						<td align="left" colspan="2">
							<span style="color: black">JENIS BARANG</span>
						</td>
						<td align="left" colspan="1">
						</td>
						<td align="left" colspan="1">
							<span style="color: black">SATUAN</span>
						</td>
						<td align="left" colspan="1">
							<span style="color: black"></span>
						</td>
						<td align="left" colspan="1">
							<span style="color: black">JUMLAH</span>
						</td>
						<td align="left" colspan="3">
							<?=number_format($r['total'], 0, ",", ".")?>
						</td>
						<td align="left" colspan="2">
							<span style="color: black">JENIS TRANSAKSI</span>
						</td>
						<td align="left" colspan="1">
							<?=$r['jenis']?>
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="12" height="30px">
							<span style="color: black">TERBILANG</span>
							<center><font style="font-weight: bold;"><?=$r['terbilang']?></font></center>
						</td>
					</tr>
					<tr>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							<span style="color: black">PECAHAN</span>
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							<span style="color: black">MATA UANG</span>
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							<span style="color: black">JUMLAH</span>
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="3">
							<span style="color: black">NILAI</span>
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							<span style="color: black">PECAHAN</span>
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							<span style="color: black">MATA UANG</span>
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
							<span style="color: black">JUMLAH</span>
						</td>
						<td style="font-size: 7px; font-weight: bold" align="center" colspan="3">
							<span style="color: black">NILAI</span>
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
							<span style="color: black">NO.KANTONG / TAS</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="color: black">NO.SEGEL</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="color: black">NO.KANTONG / TAS</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="color: black">NO.SEGEL</span>
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span><?=(empty($r['bag_no']) ? '<span style="visibility: hidden">a</span>' : $r['bag_no'])?></span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span><?=(empty($r['bag_seal']) ? '<span style="visibility: hidden">a</span>' : $r['bag_seal'])?></span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="color: black"></span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="color: black"></span>
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
					</tr>
					<tr>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
						<td align="left" style="vertical-align: top;" colspan="3">
							<span style="visibility: hidden">a</span>
						</td>
					</tr>
					
					<tr>
						<td align="center" colspan="6">
							<span style="color: black">SERAH / TERIMA</span>
						</td>
						<td align="center" colspan="6">
							<span style="color: black">SERAH / TERIMA</span>
						</td>
					</tr>
					
					<tr>
						<td colspan="3">
							<span style="color: black">NAMA</span>
						</td>
						<td colspan="3">
							<span style="color: black">NAMA</span>
						</td>
						<td colspan="3">
							<span style="color: black">NAMA</span>
						</td>
						<td colspan="3">
							<span style="color: black">NAMA</span>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<span style="color: black">KTP/KPP</span>
						</td>
						<td colspan="3">
							<span style="color: black">KTP/KPP</span>
						</td>
						<td colspan="3">
							<span style="color: black">KTP/KPP</span>
						</td>
						<td colspan="3">
							<span style="color: black">KTP/KPP</span>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align: top;" align="left">
							<span style="color: black">TANGGAL</span> <BR><BR>
						</td>
						<td colspan="1" style="vertical-align: top;" align="left">
							<span style="color: black">JAM</span>
						</td>
						<td colspan="2" style="vertical-align: top;" align="left">
							<span style="color: black">TANGGAL</span>
						</td>
						<td colspan="1" style="vertical-align: top;" align="left">
							<span style="color: black">JAM</span>
						</td>
						<td colspan="2" style="vertical-align: top;" align="left">
							<span style="color: black">TANGGAL</span>
						</td>
						<td colspan="1" style="vertical-align: top;" align="left">
							<span style="color: black">JAM</span>
						</td>
						<td colspan="2" style="vertical-align: top;" align="left">
							<span style="color: black">TANGGAL</span>
						</td>
						<td colspan="1" style="vertical-align: top;" align="left">
							<span style="color: black">JAM</span>
						</td>
					</tr>
					<tr>
						<td colspan="3"><span style="color: black">TT</span><br><br><br><br></td>
						<td colspan="3"><span style="color: black">TT</span><br><br><br><br></td>
						<td colspan="3"><span style="color: black">TT</span><br><br><br><br></td>
						<td colspan="3"><span style="color: black">TT</span><br><br><br><br></td>
					</tr>
					<tr>
						<td colspan="12"><span style="visibility: hidden">a</span></td>
					</tr>
					<tr>
						<td colspan="1" align="center"><span style="color: black">CUSTODIAN</span><br><br></td>
						<td colspan="1" align="center"><span style="color: black">ID</span></td>
						<td colspan="4" rowspan="5" style="vertical-align: top;" align="center"><span style="color: black">TANDA TANGAN</span></td>
						<td colspan="1" align="center"><span style="color: black">VAULT</span></td>
						<td colspan="1" align="center"><span style="color: black">ID</span></td>
						<td colspan="4" rowspan="5" style="vertical-align: top;" align="center"><span style="color: black">TANDA TANGAN</span></td>
					</tr>
					<tr>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">TERIMA</span><br><br></td>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">DISERAHKAN</span></td>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">TERIMA</span></td>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">DISERAHKAN</span></td>
					</tr>
					<tr>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">TANGGAL</span><br><br></td>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">WAKTU</span></td>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">TANGGAL</span></td>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">WAKTU</span></td>
					</tr>
					<tr>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">TANPA CATATAN</span><br><br></td>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">ADA CATATAN</span></td>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">TANPA CATATAN</span></td>
						<td colspan="1" style="vertical-align: top;" align="center"><span style="color: black">ADA CATATAN</span></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><span style="color: black">NO BERITA ACARA (KALAU ADA)</span></td>
						<td colspan="2" align="center"><span style="color: black">NO BERITA ACARA (KALAU ADA)</span></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><span style="color: black">ORIGINAL 1</span></td>
						<td colspan="4" align="center"><span style="color: black">ORIGINAL 2 - PENGIRIM</span></td>
						<td colspan="2" align="center"><span style="color: black">ORIGINAL 3 - PENERIMA</span></td>
						<td colspan="4" align="center"><span style="color: black">ORIGINAL 4 - ARSIP</span></td>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		<?php } ?>
    </body>
</html>