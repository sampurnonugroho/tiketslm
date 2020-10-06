<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Mpdf\Mpdf;

class Pdf_fix extends CI_Controller {
    public function __construct() {
        parent::__construct();
		$this->load->library('curl');
    }
	
	public function runsheet() {
		$id_ct = $this->uri->segment(3);
		$id_ga = $this->uri->segment(4);
		
		$sql = "select *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail, cashtransit_detail.ctr as jum_ctr FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) WHERE cashtransit_detail.state='ro_atm' AND cashtransit_detail.data_solve='' AND cashtransit.id='$id_ct' AND client.sektor IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit='$id_ct' AND run_number='$id_ga') GROUP BY cashtransit_detail.id ORDER BY cashtransit.id DESC ";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($result);
		
		$content_html = '';
		$inner_content_html = '';
		
		foreach($result as $row) {
			$type = $row->type;
			$ctr = $row->jum_ctr;
			$denom = "-";
			$value = "-";
			$ttl_ctr = 0;
			$ttl_all = 0;
			$terbilang = '';
			
			if($type=="ATM") {
				$ttl_ctr = '('.$ctr.') '.($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)).'';
				$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
				$value = (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000);
				$ttl_all = 'Rp. '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom), 0, ",", ".").'';
				$terbilang = ucwords($this->terbilang(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom)));
				
				$inner_content_html = '
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
							<tr>
								<td>Catridge 1</td>
								<td align="center">'.$row->cart_1_seal.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 2</td>
								<td align="center">'.$row->cart_2_seal.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 3</td>
								<td align="center">'.$row->cart_3_seal.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 4</td>
								<td align="center">'.$row->cart_4_seal.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Divert</td>
								<td align="center">'.$row->divert.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td colspan=5 id="noborder"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
						</tbody>
					</table>
				';
			} else {
				list($seal_1, $denom_1, $value_1) = explode(";", $row->cart_1_seal);
				list($seal_2, $denom_2, $value_2) = explode(";", $row->cart_2_seal);
				list($seal_3, $denom_3, $value_3) = explode(";", $row->cart_3_seal);
				list($seal_4, $denom_4, $value_4) = explode(";", $row->cart_4_seal);
				$seal_5 = $row->cart_5_seal;
				
				$ttl_1 = 'Rp. '.number_format(($denom_1*$value_1)*1000, 0, ",", ".");
				$ttl_2 = 'Rp. '.number_format(($denom_2*$value_2)*1000, 0, ",", ".");
				$ttl_3 = 'Rp. '.number_format(($denom_3*$value_3)*1000, 0, ",", ".");
				$ttl_4 = 'Rp. '.number_format(($denom_4*$value_4)*1000, 0, ",", ".");
				
				$ttl_all1 = ($denom_1*$value_1) +
						   ($denom_2*$value_2) +
						   ($denom_3*$value_3) +
						   ($denom_4*$value_4);
				
				$ttl_all = 'Rp. '.number_format(($ttl_all1)*1000, 0, ",", ".");
				
				$ttl_ctr = ''.$ctr.'';
				
				$terbilang = ucwords($this->terbilang(($ttl_all1)*1000));
				

				$inner_content_html = '
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
							<tr>
								<td align="center">1</td>
								<td align="center">
									<span class="alignleft">'.number_format(($denom_1*1000), 0, ",",".").'</span>
									<span class="alignright">'.$value_1.'</span>
								</td>
								<td align="center">'.$ttl_1.'</td>
								<td align="center">'.$seal_1.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">2</td>
								<td align="center">
									<span class="alignleft">'.number_format(($denom_2*1000), 0, ",",".").'</span>
									<span class="alignright">'.$value_2.'</span>
								</td>
								<td align="center">'.$ttl_2.'</td>
								<td align="center">'.$seal_2.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">3</td>
								<td align="center">
									<span class="alignleft">'.number_format(($denom_3*1000), 0, ",",".").'</span>
									<span class="alignright">'.$value_3.'</span>
								</td>
								<td align="center">'.$ttl_3.'</td>
								<td align="center">'.$seal_3.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">4</td>
								<td align="center">
									<span class="alignleft">'.number_format(($denom_4*1000), 0, ",",".").'</span>
									<span class="alignright">'.$value_4.'</span>
								</td>
								<td align="center">'.$ttl_4.'</td>
								<td align="center">'.$seal_4.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">5</td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center">'.$seal_5.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">6</td>
								<td>DIVERT</td>
								<td align="center"></td>
								<td align="center">'.$row->divert.'</td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td colspan="7" id="noborder"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
						</tbody>
					</table>
				';
			}
			
			$content_html .= '
				<table class="first">
					<tr>
						<td width="50%">
							<p id="h3">PT. BINTANG JASA ARTHA KELOLA</p>
							<p>REPORT REPLENISH - RETURN ATM</p>
							<hr style="border-style: solid;height:1px;border:none;color:#333;background-color:#333;margin-top: -10px" />
							
							<table class="second">
								<tr>
									<td style="width: 60px">LOCATION</td>
									<td style="width: 10px">:</td>
									<td>'.$row->lokasi.'</td>
								</tr>
								<tr>
									<td style="width: 60px">ID</td>
									<td style="width: 10px">:</td>
									<td>'.$row->wsid.'</td>
								</tr>
							</table>
							<table class="second">
								<tr>
									<td style="width: 60px">BANK</td>
									<td style="width: 10px">:</td>
									<td>'.$row->bank.'</td>
									
									<td style="width: 60px">DENOM</td>
									<td style="width: 10px">:</td>
									<td>'.$denom.'</td>
								</tr>
								<tr>
									<td style="width: 60px">TYPE</td>
									<td style="width: 10px">:</td>
									<td>'.$row->type_mesin.'</td>
									
									<td style="width: 60px">VALUE</td>
									<td style="width: 10px">:</td>
									<td>'.$value.'</td>
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
									<td>'.date("d-M-Y", strtotime(explode(" ", $row->date)[0])).'</td>
								</tr>
								<tr>
									<td>TIME REPLENISH(CSO)</td>
									<td>:</td>
									<td>...........................</td>
								</tr>
								<tr>
									<td>TIME PREPARE BAG(CPC)</td>
									<td>:</td>
									<td>'.date("H:i", strtotime(explode(" ", $row->updated_date_cpc)[1])).'</td>
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
						<td style="width: 45px; text-align: center; font-size: 24px;">'.$row->sektor.'</td>
					</tr>
				</table>
				
				'.$inner_content_html.'
				
				<table style="width: 80%; font-size: 10px; margin-top: -14px">
					<tr>
						<td style="width: 120px">TOTAL CATRIDGE</td>
						<td style="width: 10px">:</td>
						<td>'.$ttl_ctr.'</td>
						
						<td style="width: 120px">NO. BAG</td>
						<td style="width: 10px">:</td>
						<td>'.$row->bag_no.'</td>
					</tr>
					<tr>
						<td style="width: 60px">TOTAL</td>
						<td style="width: 10px">:</td>
						<td>'.$ttl_all.'</td>
						
						<td style="width: 60px">SEAL BAG(CPC)</td>
						<td style="width: 10px">:</td>
						<td>'.$row->bag_seal.'</td>
					</tr>
					<tr>
						<td style="width: 60px">TERBILANG</td>
						<td style="width: 10px">:</td>
						<td style="font-weight: bold"># '.$terbilang.' #</td>
						
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
			';
		}
		
		$template_html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 22cm 14cm portrait; }
					
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
					'.$content_html.'
				</body>
			</html>
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($template_html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function runsheet2() {
		$html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 22cm 14cm portrait; }
					
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
					<table class="first">
						<tr>
							<td width="50%">
								<p id="h3">PT. BINTANG JASA ARTHA KELOLA</p>
								<p>REPORT REPLENISH - RETURN ATM</p>
								<hr style="border-style: solid;height:1px;border:none;color:#333;background-color:#333;margin-top: -10px" />
								
								<table class="second">
									<tr>
										<td style="width: 60px">LOCATION</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 60px">ID</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
								</table>
								<table class="second">
									<tr>
										<td style="width: 60px">BANK</td>
										<td style="width: 10px">:</td>
										<td></td>
										
										<td style="width: 60px">DENOM</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 60px">TYPE</td>
										<td style="width: 10px">:</td>
										<td></td>
										
										<td style="width: 60px">VALUE</td>
										<td style="width: 10px">:</td>
										<td></td>
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
										<td></td>
									</tr>
									<tr>
										<td>TIME REPLENISH(CSO)</td>
										<td>:</td>
										<td></td>
									</tr>
									<tr>
										<td>TIME PREPARE BAG(CPC)</td>
										<td>:</td>
										<td></td>
									</tr>
								</table>
								<hr style="height:1px;border:none;color:#333;background-color:#333;width:100%; text-align: left; margin: 2px" />
								<table class="second">
									<tr>
										<td style="width: 150px">CASHIER</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td>NO. MEJA</td>
										<td>:</td>
										<td></td>
									</tr>
									<tr>
										<td>JAM PROSES</td>
										<td>:</td>
										<td></td>
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
							<td style="width: 45px; text-align: center; font-size: 24px;">12</td>
						</tr>
					</table>
					
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
							<tr>
								<td>Catridge 1</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 2</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 3</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 4</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Divert</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td colspan=5 id="noborder"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
						</tbody>
					</table>
					
					
					<table style="width: 80%; font-size: 10px; margin-top: -14px">
						<tr>
							<td style="width: 120px">TOTAL CATRIDGE</td>
							<td style="width: 10px">:</td>
							<td>() </td>
							
							<td style="width: 120px">NO. BAG</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
						<tr>
							<td style="width: 60px">TOTAL</td>
							<td style="width: 10px">:</td>
							<td>Rp. </td>
							
							<td style="width: 60px">SEAL BAG(CPC)</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
						<tr>
							<td style="width: 60px">TERBILANG</td>
							<td style="width: 10px">:</td>
							<td style="font-weight: bold">#  #</td>
							
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
				</body>
			</html>
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function runsheet_atm() {
		$html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 22cm 14cm portrait; }
					
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
							line-height: 5px;
						}	
						
						table.second {
							width: 100%;
						}
						
						table.second td {
							line-height: 10px;
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
					<table class="first">
						<tr>
							<td width="50%">
								<p id="h3">PT. BINTANG JASA ARTHA KELOLA</p>
								<p>REPORT REPLENISH - RETURN ATM</p>
								<hr style="border-style: solid;height:1px;border:none;color:#333;background-color:#333;" />
								
								<table class="second">
									<tr>
										<td style="width: 60px">LOCATION</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 60px">ID</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
								</table>
								<table class="second">
									<tr>
										<td style="width: 60px">BANK</td>
										<td style="width: 10px">:</td>
										<td></td>
										
										<td style="width: 60px">DENOM</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 60px">TYPE</td>
										<td style="width: 10px">:</td>
										<td></td>
										
										<td style="width: 60px">VALUE</td>
										<td style="width: 10px">:</td>
										<td></td>
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
										<td></td>
									</tr>
									<tr>
										<td>TIME REPLENISH(CSO)</td>
										<td>:</td>
										<td></td>
									</tr>
									<tr>
										<td>TIME PREPARE BAG(CPC)</td>
										<td>:</td>
										<td></td>
									</tr>
								</table>
								<hr style="height:1px;border:none;color:#333;background-color:#333;width:100%; text-align: left; margin: 2px" />
								<table class="second">
									<tr>
										<td style="width: 150px">CASHIER</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td>NO. MEJA</td>
										<td>:</td>
										<td></td>
									</tr>
									<tr>
										<td>JAM PROSES</td>
										<td>:</td>
										<td></td>
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
							<td style="width: 45px; text-align: center; font-size: 24px;">12</td>
						</tr>
					</table>
					
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
							<tr>
								<td>Catridge 1</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 2</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 3</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 4</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Divert</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td colspan=5 id="noborder"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
						</tbody>
					</table>
					
					
					<table style="width: 80%; font-size: 10px; margin-top: -14px">
						<tr>
							<td style="width: 120px">TOTAL CATRIDGE</td>
							<td style="width: 10px">:</td>
							<td>() </td>
							
							<td style="width: 120px">NO. BAG</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
						<tr>
							<td style="width: 60px">TOTAL</td>
							<td style="width: 10px">:</td>
							<td>Rp. </td>
							
							<td style="width: 60px">SEAL BAG(CPC)</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
						<tr>
							<td style="width: 60px">TERBILANG</td>
							<td style="width: 10px">:</td>
							<td style="font-weight: bold">#  #</td>
							
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
				</body>
			</html>
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function runsheet_crm() {
		$html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 22cm 14cm portrait; }
					
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
							line-height: 5px;
						}	
						
						table.second {
							width: 100%;
						}
						
						table.second td {
							line-height: 10px;
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
					<table class="first">
						<tr>
							<td width="50%">
								<p id="h3">PT. BINTANG JASA ARTHA KELOLA</p>
								<p>REPORT REPLENISH - RETURN ATM</p>
								<hr style="border-style: solid;height:1px;border:none;color:#333;background-color:#333;" />
								
								<table class="second">
									<tr>
										<td style="width: 60px">LOCATION</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 60px">ID</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
								</table>
								<table class="second">
									<tr>
										<td style="width: 60px">BANK</td>
										<td style="width: 10px">:</td>
										<td></td>
										
										<td style="width: 60px">DENOM</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 60px">TYPE</td>
										<td style="width: 10px">:</td>
										<td></td>
										
										<td style="width: 60px">VALUE</td>
										<td style="width: 10px">:</td>
										<td></td>
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
										<td></td>
									</tr>
									<tr>
										<td>TIME REPLENISH(CSO)</td>
										<td>:</td>
										<td></td>
									</tr>
									<tr>
										<td>TIME PREPARE BAG(CPC)</td>
										<td>:</td>
										<td></td>
									</tr>
								</table>
								<hr style="height:1px;border:none;color:#333;background-color:#333;width:100%; text-align: left; margin: 2px" />
								<table class="second">
									<tr>
										<td style="width: 150px">CASHIER</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td>NO. MEJA</td>
										<td>:</td>
										<td></td>
									</tr>
									<tr>
										<td>JAM PROSES</td>
										<td>:</td>
										<td></td>
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
							<td style="width: 45px; text-align: center; font-size: 24px;">12</td>
						</tr>
					</table>
					
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
							<tr>
								<td align="center">1</td>
								<td align="center">
									<span class="alignleft">100.000</span>
									<span class="alignright">400</span>
								</td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">2</td>
								<td align="center">
									<span class="alignleft">100.000</span>
									<span class="alignright">400</span>
								</td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">3</td>
								<td align="center">
									<span class="alignleft">50.000</span>
									<span class="alignright">400</span>
								</td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">4</td>
								<td align="center">
									<span class="alignleft">50.000</span>
									<span class="alignright">400</span>
								</td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">5</td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">6</td>
								<td>DIVERT</td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td colspan="7" id="noborder"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
						</tbody>
					</table>
					
					
					<table style="width: 80%; font-size: 10px; margin-top: -14px">
						<tr>
							<td style="width: 120px">TOTAL CATRIDGE</td>
							<td style="width: 10px">:</td>
							<td>() </td>
							
							<td style="width: 120px">NO. BAG</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
						<tr>
							<td style="width: 60px">TOTAL</td>
							<td style="width: 10px">:</td>
							<td>Rp. </td>
							
							<td style="width: 60px">SEAL BAG(CPC)</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
						<tr>
							<td style="width: 60px">TERBILANG</td>
							<td style="width: 10px">:</td>
							<td style="font-weight: bold">#  #</td>
							
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
				</body>
			</html>
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function runsheet_report() {
		
	}
	
	public function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = $this->penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . $this->penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}

	public function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim($this->penyebut($nilai));
		} else {
			$hasil = trim($this->penyebut($nilai));
		}     		
		return $hasil." Rupiah";
	}
}