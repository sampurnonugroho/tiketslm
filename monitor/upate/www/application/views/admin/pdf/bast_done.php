<html>
    <head>
        <style>
			* {
                font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 10px; font-style: normal; 
				font-size: 12px
			}
			
			ol {
				margin-left: 0px;
				padding-left: 15px;
				left: 10px;
			}
		
            @page { margin: 0px; size: 21cm 31.7cm portrait; }
        
			@font-face {
			  font-family: "aaaaa";
			  src: URL("<?=base_url()?>depend/font/serif_dot_digital-7.ttf") format("truetype");
			}
        
            body {
                margin: 27px 30px 0px 30px; size: 14cm 22cm landscape;    
				font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 10px; font-style: normal; 
				font-size: 10px;
            }
        
            table.first {
                font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 10px; font-style: normal;          
                font-size: 10px;
                width: 100%;
            }
            
            #h3 {
                font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 10px; font-style: normal; 
                font-size: 14px;
				padding-bottom: 0px;	
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
                font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 10px; font-style: normal;       
                font-size: 10px;
                border: 1px solid black;
                border-collapse: collapse;
                position: absolute;
                top: 30;
                right: 260;
                border-style: solid;
				border-width: thin;
            }
            
            .fourth {
                font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 10px; font-style: normal;        
                font-size: 10px;
                border-collapse: collapse;
                border: 1px solid black;
                border-style: solid;
				border-width: thin;
            }
            
            .fifth {
                font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 10px; font-style: normal;    
                font-size: 10px;
                border-collapse: collapse;
                border: 1px solid black;
                border-style: solid;
				border-width: thin;
            }
            
            .sixth {
                font-family: Tahoma, Verdana, Segoe, sans-serif; font-size: 10px; font-style: normal;      
                border: none;
                border-collapse: collapse;
            }
            
            .sixth td {
                padding: 4px;
                border: 1px solid black;
                border-style: solid;
				border-width: thin;
            }
            
            table.fourth td {
                padding: 4px;
                border: 1px solid black;
                border-style: solid;
				border-width: thin;
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
			
			h1 {
				text-align: center; 
				line-height: 20px;
				font-size: 14px;
			}
			
			#table1, #table2 {
				width: 100%;
				font-size: 10px;
			}
			
			#table1, th, td {
				border: 1px solid black;
				border-collapse: collapse;
			}
			
			#table2, #table2 th, #table2 td {
				border: 0px solid black;
				border-collapse: collapse;
			}
			
			th, td {
				padding: 3px 10px 3px 10px;
			}
			
			th {
				text-align: center
			}
        </style>
		<title>PDF RUNSHEET CASHREPLENISH</title>
    </head>

    <body>
        
        <?php 
            $data_ho = json_decode($data_bast->data_handover, TRUE);
			list($tanggal, $jam) = explode(" ", $data_bast->updated);
			list($y, $m, $d) = explode("-", $tanggal);
            // echo "<pre>";
            // print_r($data_ho);
			
			$hari = date("D", strtotime($data_bast->updated));
 
			switch($hari){
				case 'Sun':
					$hari_ini = "Minggu";
				break;
		 
				case 'Mon':			
					$hari_ini = "Senin";
				break;
		 
				case 'Tue':
					$hari_ini = "Selasa";
				break;
		 
				case 'Wed':
					$hari_ini = "Rabu";
				break;
		 
				case 'Thu':
					$hari_ini = "Kamis";
				break;
		 
				case 'Fri':
					$hari_ini = "Jumat";
				break;
		 
				case 'Sat':
					$hari_ini = "Sabtu";
				break;
				
				default:
					$hari_ini = "Tidak di ketahui";		
				break;
			}
			
			// echo "<pre>";
			// print_r($data_ho);
        ?>
		<h1>BERITA ACARA SERAH TERIMA MESIN ATM <br>BESERTA PERLENGKAPANNYA</h1>
		
		<p>
			Pada hari ini <b><?=$hari_ini?></b>, jam <b><?=$jam?></b> tanggal <b><?=$d?></b> bulan <b><?=$m?></b> tahun <b><?=$y?></b>, sesuai surat addendum Perjanjian Jasa Cash Logistic (CL) dan First Level Maintenance (FLM) antara PT. <b>Bank <?=$data_bast->bank?></b> (Tbk) dengan PT. Bintang Jasa Artha Kelola.
		</p>
		
		<ol>
			<li>
				PT. Bintang Jasa Artha Kelola, selanjutnya disebut PIHAK PERTAMA; dalam hal ini diwakili oleh pejabat tersebut dibawah ini, selaku yang menyerahkan kepada pihak kedua;
			</li>
			<li>
				PT. <b>Bank <?=$data_bast->bank?></b>, selanjutnya disebut PIHAK KEDUA; dalam hal ini diwakili oleh pejabat tersebut dibawah ini, selaku pihak penerima dari pihak pertama;
			</li>
		</ol>
		
		<p>
			Dengan ini menyatakan bahwa para pihak sepakat melakukan serah terima sebuah mesin ATM Merek : <b><?=$data_ho['arr_merk']['jumlah']?></b> dengan serial Number <b><?=$data_ho['arr_serial']['jumlah']?></b> yang berlokasi di <b><?=$data_bast->alamat?></b> dengan No. ID ATM : <b><?=$data_bast->wsid?></b> Dalam kondisi baik beserta perlengkapannya ( isi dan lengkapi, bila tidak ada / kurang lengkap beri penjelasan ) sebagai berikut :
		</p>
		
		<table id="table1">
			<tr>
				<th width="1%">No.</th>
				<th width="35%">Jenis</th>
				<th width="10%">Jumlah</th>
				<th width="30%">Keterangan</th>
			</tr>
			<tr>
				<td></td>
				<td>Merek ATM</td>
				<td><?=$data_ho['0']['jumlah']?></td>
				<td><?=$data_ho['0']['keterangan']?></td>
			</tr>
			<tr>
				<td></td>
				<td>Serial Number ATM</td>
				<td><?=$data_ho['1']['jumlah']?></td>
				<td><?=$data_ho['1']['keterangan']?></td>
			</tr>
			<tr>
				<td>1.</td>
				<td>Currency Cassette (Kotak Uang)</td>
				<td><?=$data_ho['2']['jumlah']?></td>
				<td><?=$data_ho['2']['keterangan']?></td>
			</tr>
			<tr>
				<td>2.</td>
				<td>Reject bin (kotak reject)</td>
				<td><?=$data_ho['3']['jumlah']?></td>
				<td><?=$data_ho['3']['keterangan']?></td>
			</tr>
			<tr>
				<td>3.</td>
				<td>Anak kunci L (untuk mengubah kombinasi)</td>
				<td><?=$data_ho['4']['jumlah']?></td>
				<td><?=$data_ho['4']['keterangan']?></td>
			</tr>
			<tr>
				<td>4.</td>
				<td>Anak kunci dual kombinasi</td>
				<td><?=$data_ho['5']['jumlah']?></td>
				<td><?=$data_ho['5']['keterangan']?></td>
			</tr>
			<tr>
				<td>5.</td>
				<td>Anak kunci fascia (cover atas)</td>
				<td><?=$data_ho['6']['jumlah']?></td>
				<td><?=$data_ho['6']['keterangan']?></td>
			</tr>
			<tr>
				<td>6.</td>
				<td>Anak kunci fascia (cover bawah)</td>
				<td><?=$data_ho['7']['jumlah']?></td>
				<td><?=$data_ho['7']['keterangan']?></td>
			</tr>
			<tr>
				<td>7.</td>
				<td>Modem dan anak kunci gembok (merk / no seri)</td>
				<td><?=$data_ho['8']['jumlah']?></td>
				<td><?=$data_ho['8']['keterangan']?></td>
			</tr>
			<tr>
				<td>8.</td>
				<td>UPS (merk / no seri)</td>
				<td><?=$data_ho['9']['jumlah']?></td>
				<td><?=$data_ho['9']['keterangan']?></td>
			</tr>
			<tr>
				<td>9.</td>
				<td>Anak kunci gembok AC</td>
				<td><?=$data_ho['10']['jumlah']?></td>
				<td><?=$data_ho['10']['keterangan']?></td>
			</tr>
			<tr>
				<td>10.</td>
				<td>Air Conditioning (AC) / (merk)</td>
				<td><?=$data_ho['11']['jumlah']?></td>
				<td><?=$data_ho['11']['keterangan']?></td>
			</tr>
			<tr>
				<td>11.</td>
				<td>Anak kunci brangkas ATM</td>
				<td><?=$data_ho['12']['jumlah']?></td>
				<td><?=$data_ho['12']['keterangan']?></td>
			</tr>
			<tr>
				<td>12.</td>
				<td>Kunci ruang ATM</td>
				<td><?=$data_ho['13']['jumlah']?></td>
				<td><?=$data_ho['13']['keterangan']?></td>
			</tr>
			<tr>
				<td>13.</td>
				<td>Nomor ID ATM</td>
				<td><?=$data_ho['14']['jumlah']?></td>
				<td><?=$data_ho['14']['keterangan']?></td>
			</tr>
			<tr>
				<td>14.</td>
				<td>Denom / pecahan uang yang digunakan</td>
				<td><?=$data_ho['15']['jumlah']?></td>
				<td><?=$data_ho['15']['keterangan']?></td>
			</tr>
			<tr>
				<td>15.</td>
				<td>Limit pengisian uang</td>
				<td><?=$data_ho['16']['jumlah']?></td>
				<td><?=$data_ho['16']['keterangan']?></td>
			</tr>
			<tr>
				<td>16.</td>
				<td>Bill count akhir</td>
				<td><?=$data_ho['17']['jumlah']?></td>
				<td><?=$data_ho['17']['keterangan']?></td>
			</tr>
			<tr>
				<td>17.</td>
				<td>Dynabold</td>
				<td><?=$data_ho['18']['jumlah']?></td>
				<td><?=$data_ho['18']['keterangan']?></td>
			</tr>
			<tr>
				<td>18.</td>
				<td>Neon sign logo lama / baru</td>
				<td><?=$data_ho['19']['jumlah']?></td>
				<td><?=$data_ho['19']['keterangan']?></td>
			</tr>
			<tr>
				<td>19.</td>
				<td>Kondisi lampu ruangan</td>
				<td><?=$data_ho['20']['jumlah']?></td>
				<td><?=$data_ho['20']['keterangan']?></td>
			</tr>
			<tr>
				<td>20.</td>
				<td>Sticker logo lama / baru</td>
				<td><?=$data_ho['21']['jumlah']?></td>
				<td><?=$data_ho['21']['keterangan']?></td>
			</tr>
			<tr>
				<td>21.</td>
				<td>Integrated Transformer</td>
				<td><?=$data_ho['22']['jumlah']?></td>
				<td><?=$data_ho['22']['keterangan']?></td>
			</tr>
			<tr>
				<td>22.</td>
				<td>Kunci card reader</td>
				<td><?=$data_ho['23']['jumlah']?></td>
				<td><?=$data_ho['23']['keterangan']?></td>
			</tr>
			<tr>
				<td>23.</td>
				<td>Combination lock (vendor sebelumnya)</td>
				<td><?=$data_ho['24']['jumlah']?></td>
				<td><?=$data_ho['24']['keterangan']?></td>
			</tr>
			<tr>
				<td>24.</td>
				<td colspan="3">
					<table width="100%">
						<tr>
							<td>
								<?php 
									if($data_ho['2']['jumlah']==2) {
										if($data_ho['26']['key']=="no_ctr_1") {
											echo $data_ho['26']['jenis']." : ".$data_ho['26']['jumlah']." (".$data_ho['26']['keterangan'].")<br>";
										}
										if($data_ho['27']['key']=="no_ctr_2") {
											echo $data_ho['27']['jenis']." : ".$data_ho['27']['jumlah']." (".$data_ho['27']['keterangan'].")<br>";
										}
										if($data_ho['28']['key']=="no_rjt_1") {
											echo $data_ho['28']['jenis']." : ".$data_ho['28']['jumlah']." (".$data_ho['28']['keterangan'].")<br>";
										}
									} else {
										if($data_ho['26']['key']=="no_ctr_1") {
											echo $data_ho['26']['jenis']." : ".$data_ho['26']['jumlah']." (".$data_ho['26']['keterangan'].")<br>";
										}
										if($data_ho['27']['key']=="no_ctr_2") {
											echo $data_ho['27']['jenis']." : ".$data_ho['27']['jumlah']." (".$data_ho['27']['keterangan'].")<br>";
										}
										if($data_ho['28']['key']=="no_ctr_3") {
											echo $data_ho['28']['jenis']." : ".$data_ho['28']['jumlah']." (".$data_ho['28']['keterangan'].")<br>";
										}
										if($data_ho['29']['key']=="no_ctr_4") {
											echo $data_ho['29']['jenis']." : ".$data_ho['29']['jumlah']." (".$data_ho['29']['keterangan'].")<br>";
										}
										if($data_ho['30']['key']=="no_ctr_5") {
											echo $data_ho['30']['jenis']." : ".$data_ho['30']['jumlah']." (".$data_ho['30']['keterangan'].")<br>";
										}
									}
								?>
							</td>
							<td>
								<?php 
									if($data_ho['2']['jumlah']==8) {
										if($data_ho['31']['key']=="no_ctr_6") {
											echo $data_ho['31']['jenis']." : ".$data_ho['31']['jumlah']." (".$data_ho['31']['keterangan'].")<br>";
										}
										if($data_ho['32']['key']=="no_ctr_7") {
											echo $data_ho['32']['jenis']." : ".$data_ho['32']['jumlah']." (".$data_ho['32']['keterangan'].")<br>";
										}
										if($data_ho['33']['key']=="no_ctr_8") {
											echo $data_ho['33']['jenis']." : ".$data_ho['33']['jumlah']." (".$data_ho['33']['keterangan'].")<br>";
										}
										if($data_ho['34']['key']=="no_rjt_1") {
											echo $data_ho['34']['jenis']." : ".$data_ho['34']['jumlah']." (".$data_ho['34']['keterangan'].")<br>";
										}
										if($data_ho['35']['key']=="no_rjt_2") {
											echo $data_ho['35']['jenis']." : ".$data_ho['35']['jumlah']." (".$data_ho['35']['keterangan'].")<br>";
										}
									} else {
										if($data_ho['31']['key']=="no_ctr_6") {
											echo $data_ho['31']['jenis']." : ".$data_ho['31']['jumlah']." (".$data_ho['31']['keterangan'].")<br>";
										}
										if($data_ho['32']['key']=="no_ctr_7") {
											echo $data_ho['32']['jenis']." : ".$data_ho['32']['jumlah']." (".$data_ho['32']['keterangan'].")<br>";
										}
										if($data_ho['33']['key']=="no_ctr_8") {
											echo $data_ho['33']['jenis']." : ".$data_ho['33']['jumlah']." (".$data_ho['33']['keterangan'].")<br>";
										}
										if($data_ho['34']['key']=="no_ctr_9") {
											echo $data_ho['34']['jenis']." : ".$data_ho['34']['jumlah']." (".$data_ho['34']['keterangan'].")<br>";
										}
										if($data_ho['35']['key']=="no_ctr_10") {
											echo $data_ho['35']['jenis']." : ".$data_ho['35']['jumlah']." (".$data_ho['35']['keterangan'].")<br>";
										}
										if($data_ho['36']['key']=="no_rjt_1") {
											echo $data_ho['36']['jenis']." : ".$data_ho['36']['jumlah']." (".$data_ho['36']['keterangan'].")<br>";
										}
										if($data_ho['37']['key']=="no_rjt_2") {
											echo $data_ho['37']['jenis']." : ".$data_ho['37']['jumlah']." (".$data_ho['37']['keterangan'].")<br>";
										}
									}
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<p>
			Sejak ditandatanganinya Berita Acara Serah Terima ini, maka PIHAK PERTAMA bertanggung jawab atas performance / Out Of Cash & Up time ATM yang dikelola, namun tidak terbatas terhadap hal-hal sebagai berikut :
			<ol>
				<li>Pengisian uang dan maintenance mesin ATM berikut system komunikasinya</li>
				<li>Kebersihan & kenyamanan ruang ATM</li>
			</ol>
		</p>
		
		<table id="table2">
			<tr>
				<td align="center"> 
					PIHAK PERTAMA<br>
					Yang Menyerahkan<br>
					<br><br><br><br>
					
				
				
					(_______________________________)<br>
				</td>
				<td width="30%"></td>
				<td align="center">
					PIHAK KEDUA<br>
					Yang Menerima<br>
					<br><br><br><br>
					
				
				
					(_______________________________)
				</td>
			</tr>
		</table>
		
		*) coret yang tidak perlu.
    </body>
</html>