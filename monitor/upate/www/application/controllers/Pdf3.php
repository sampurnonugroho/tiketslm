<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf3 extends CI_Controller {
	
	function index(){
		$this->load->library('Pdf');
		
		$pdf = new Pdf('P', 'mm', 'A4', false, 'UTF-8', false);
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetRightMargin(5);
		$pdf->setFooterMargin(20);
		$pdf->SetAutoPageBreak(true);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->AddPage();
		$i=0;
$html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
    table.first {
        font-family: helvetica;
        font-size: 8pt;
		border: 1px solid black;
    }
	
	table.first td {
		line-height: 80%;
	}	
	
	table.second td {
		line-height: 10px;
	}
</style>
<table class="first">
	<tr>
		<td width="50%">
			<h3>PT. BINTANG JASA ARTHA KELOLA</h3>
			<p>REPORT REPLENISH - RETURN ATM</p>
			<hr style="height:1px;border:none;color:#333;background-color:#333;width:65%; text-align: left;" />
			<br />
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
		<td width="20%">
			<table style="border: 1px solid black">
				<tr>
					<td style="border: 1px solid black; width:40px; heght"> <span style="padding: 10px">RUN</span> </td>
				</tr>
				<tr>
					<td style="border: 1px solid black; text-align: center; font-size: 24px; font-weight: bold">12</td>
				</tr>
			</table>
		</td>
		<td width="30%">
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

EOF;
		$pdf->writeHTML($html, true, false, false, false, '');
		$pdf->Output('daftar_produk.pdf', 'I');
    }
}
?>