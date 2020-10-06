<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Mpdf\Mpdf;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class Pdf extends CI_Controller {
    public function __construct() {
        parent::__construct();
		$this->load->library('curl');
    }
	
    public function index() {    
		$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
		
		// $html = '
				// <html>
					// <head>
						// <style>
							// @page { margin: 0px; }
							// img {
							// }
						// </style>
					// </head>
					// <body>
						// <img style="width: 100%; height: 100px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($this->model_app->get_batch_code(), $generator::TYPE_CODE_128)) . '"><br>
						// <center style="font-size: 30px">'.$this->model_app->get_batch_code().'</center>';
						
						// foreach($this->model_app->get_child_code($this->model_app->get_batch_code(), 10) as $r) {
								// $html .= '<img style="width: 100%; height: 100px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($r, $generator::TYPE_CODE_128)) . '"><br>
								// <center style="font-size: 30px">'.$r.'</center>';
						// }
		
		// $html .= '</body>
				// </html>';
		
		$html = '
				<html>
					<head>
						<style>
							@page { margin: 0px; }
							img {
							}
						</style>
					</head>
					<body>';
						
						foreach($this->model_app->get_child_code($this->model_app->get_batch_code(), 10) as $r) {
								$html .= '<img style="width: 100%; height: 100px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($r, $generator::TYPE_CODE_128)) . '"><br>
								<center style="font-size: 30px">'.$r.'</center>';
						}
		
		$html .= '</body>
				</html>';

	
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream();
    }
	
	public function print_receipt() {
		$date = str_replace("%20", " ", $this->uri->segment(3));
		
		echo $date;
		
		$query = "
			SELECT 
				*
			FROM 
				master_receipt
			WHERE
				date='$date'
		";
		
		$result = json_decode(
			$this->curl->simple_get(rest_api().'/select/query_all', 
				array('query'=>$query), 
				array(CURLOPT_BUFFERSIZE => 10)
			)
		);
		
		echo "<pre>";
		print_r($result);
		foreach($result as $r) {
			$qrCode = new QrCode($r->kode);
			$qrCode->setSize(300);
			$qrCode->writeFile(realpath(__DIR__ . '/../../upload/qrcode_receipt').'/'.$r->kode.'.png');
		}
		
		$data['data'] = $result;
		$this->load->library('pdf');
		
		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "qrcode-handover-".$wsid.".pdf";
		$this->pdf->load_view('receipt', $data);
	}
	
	public function print_command() {
		$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
		
		$template_content = '
			<div class="">
				<div class="">
					
					<center>
						<img style="width: 60%; height: 45px; margin-top: 40px" src="data:image/png;base64,' . base64_encode($generator->getBarcode("^&041&^", $generator::TYPE_CODE_128)) . '">
					</center>
					<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
						<span>SLEEP</span>
					</center>
				</div>
			</div>
			<div class="">
				<div class="">
					
					<center>
						<img style="width: 60%; height: 45px; margin-top: 40px" src="data:image/png;base64,' . base64_encode($generator->getBarcode("^&037&^", $generator::TYPE_CODE_128)) . '">
					</center>
					<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
						<span>BATTERY OUTPUT</span>
					</center>
				</div>
			</div>
		';
		
		$template_html = '
			<html>
				<head>
					<title>CMD PRINT</title>
					<style>
						
						@page { margin: 0px; size: 6cm 4cm portrait; }
					
					
						body {
							margin: 1px 1px 1px 1px; 
							font-family: Calibri;            
						}
					</style>
				</head>

				<body>
					'.$template_content.'
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($template_html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function barcode_seal() {
		$id = $this->uri->segment(3);
		$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
		
		$template_content = '
			<div class="">
				<div class="">
					
					<center>
						<img style="width: 60%; height: 45px; margin-top: 40px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($id, $generator::TYPE_CODE_128)) . '">
					</center>
					<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
						<span>'.$id.'</span>
					</center>
				</div>
			</div>
		';
		
		$template_html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 6cm 4cm portrait; }
					
					
						body {
							margin: 1px 1px 1px 1px; 
							font-family: Calibri;            
						}
					</style>
				</head>

				<body>
					'.$template_content.'
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($template_html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function qrcode_gen() { 
		$id = $this->uri->segment(3);
		$html = $this->uri->segment(4);
		
		$qrCode = new QrCode($id);
		$qrCode->setSize(300);
		
		$qrCode->writeFile(realpath(__DIR__ . '/../../upload/qrcode_bag').'/'.$id.'.png');
		
		// echo realpath(__DIR__ . '/../../upload/qrcode_bag').'/'.$id.'.png';
		
		// $template_content = '
			// <div class="">
				// <div class="">
					
					// <center>
						// <img style="margin-top: 18px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bag').'/'.$id.'.png" width="80" height="80"></img>
					// </center>
					// <center style="margin-top: 1px; font-size: 12px; font-weight: bold">
						// <span>'.$id.'</span>
					// </center>
				// </div>
			// </div>
		// ';
		
		if(!isset($html)) {
			$template_content = '
				<div class="">
					<div class="">
						
						<center>
							<img style="margin-top: 18px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bag').'/'.$id.'.png" width="80" height="80"></img>
						</center>
						<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
							<span>'.$id.'</span>
						</center>
					</div>
				</div>
			';
		} else {
			$template_content = '
				<div class="">
					<div class="">
						
						<center>
							<img style="margin-top: 18px" src="'.base_url().'upload/qrcode_bag/'.$id.'.png" width="80" height="80"></img>
						</center>
						<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
							<span>'.$id.'</span>
						</center>
					</div>
				</div>
			';
		}
		
		$template_html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 6cm 4cm portrait; }
					
					
						body {
							margin: 1px 1px 1px 1px; 
							font-family: Calibri;            
						}
					</style>
				</head>

				<body>
					'.$template_content.'
		';
		
		if(!isset($html)) {
			$dompdf = new DOMPDF();
			$dompdf->loadHtml($template_html);
			$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
			$dompdf->render();

			$dompdf->stream('document.pdf', array("Attachment" => false));
		} else {
			echo $template_html;
		}
	}
	
	public function qrcode_gen2() { 
		$id = $this->uri->segment(3);
		
		$qrCode = new QrCode($id);
		$qrCode->setSize(300);
		
		$qrCode->writeFile(realpath(__DIR__ . '/../../upload/qrcode_bag').'/'.$id.'.png');
		
		// echo realpath(__DIR__ . '/../../upload/qrcode_bag').'/'.$id.'.png';
		
		// $template_content = '
			// <div class="">
				// <div class="">
					
					// <center>
						// <img style="margin-top: 18px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bag').'/'.$id.'.png" width="80" height="80"></img>
					// </center>
					// <center style="margin-top: 1px; font-size: 12px; font-weight: bold">
						// <span>'.$id.'</span>
					// </center>
				// </div>
			// </div>
		// ';
		
		if(!isset($html)) {
			$template_content = '
				<div class="">
					<div class="">
						
						<center>
							<img style="margin-top: 18px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bag').'/'.$id.'.png" width="80" height="80"></img>
						</center>
						<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
							<span>'.$id.'</span>
						</center>
					</div>
				</div>
			';
		} else {
			$template_content = '
				<div class="">
					<div class="">
						
						<center>
							<img style="margin-top: 18px" src="'.base_url().'upload/qrcode_bag/'.$id.'.png" width="80" height="80"></img>
						</center>
						<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
							<span>'.$id.'</span>
						</center>
					</div>
				</div>
			';
		}
		
		$template_html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 6cm 4cm portrait; }
					
					
						body {
							margin: 1px 1px 1px 1px; 
							font-family: Calibri;            
						}
					</style>
				</head>

				<body>
					'.$template_content.'
		';
		
		// if(!isset($html)) {
			// $dompdf = new DOMPDF();
			// $dompdf->loadHtml($template_html);
			// $dompdf->set_paper(array(0, 0, 227, 151), "portrait");
			// $dompdf->render();

			// $dompdf->stream('document.pdf', array("Attachment" => false));
		// } else {
			// echo $template_html;
		// }
		
		echo base_url().'upload/qrcode_bag/'.$id.'.png';
	}
	
	public function qrcode_bag() { 
		$id = $this->uri->segment(3);
		
		$qrCode = new QrCode($id);
		$qrCode->setSize(300);
		
		$qrCode->writeFile(realpath(__DIR__ . '/../../upload/qrcode_bag').'/'.$id.'.png');
		
		$template_content = '
			<div class="">
				<div class="">
					
					<center>
						<img style="margin-top: 18px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bag').'/'.$id.'.png" width="80" height="80"></img>
					</center>
					<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
						<span>BAG : '.$id.'</span>
					</center>
				</div>
			</div>
		';
		
		$template_html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 6cm 4cm portrait; }
					
					
						body {
							margin: 1px 1px 1px 1px; 
							font-family: Calibri;            
						}
					</style>
				</head>

				<body>
					'.$template_content.'
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($template_html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function qrcode_tbag() { 
		$id = $this->uri->segment(3);
		
		$qrCode = new QrCode($id);
		$qrCode->setSize(300);
		
		$qrCode->writeFile(realpath(__DIR__ . '/../../upload/qrcode_tbag').'/'.$id.'.png');
		
		$template_content = '
			<div class="">
				<div class="">
					
					<center>
						<img style="margin-top: 18px" src="'.realpath(__DIR__ . '/../../upload/qrcode_tbag').'/'.$id.'.png" width="80" height="80"></img>
					</center>
					<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
						<span>T-BAG : '.$id.'</span>
					</center>
				</div>
			</div>
		';
		
		$template_html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 6cm 4cm portrait; }
					
					
						body {
							margin: 1px 1px 1px 1px; 
							font-family: Calibri;            
						}
					</style>
				</head>

				<body>
					'.$template_content.'
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($template_html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function qrcode_bast() { 
		$wsid = $this->uri->segment(3);
	
		$sql = "SELECT * FROM client_ho WHERE wsid='$wsid'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$qrCode = new QrCode($wsid);
		$qrCode->setSize(300);
		
		$qrCode->writeFile(realpath(__DIR__ . '/../../upload/qrcode_bast').'/'.$wsid.'.png');
		
		$cassette = intval($result->ctr);
		for($i = 1; $i<=$cassette; $i++) {
			$kode = $wsid.".CST.".sprintf('%02d', $i);
			
			$qrCode = new QrCode($kode);
			$qrCode->setSize(300);
			
			$qrCode->writeFile(realpath(__DIR__ . '/../../upload/qrcode_bast').'/'.$kode.'.png');
		}
		
		$divert = intval($result->reject);
		for($i = 1; $i<=$divert; $i++) {
			$kode = $wsid.".DIV.".sprintf('%02d', $i);
			
			$qrCode = new QrCode($kode);
			$qrCode->setSize(300);
			
			$qrCode->writeFile(realpath(__DIR__ . '/../../upload/qrcode_bast').'/'.$kode.'.png');
		}
		
		$template_content = '';
		$template_content .= '<tr>';
		$j=0;
		for($i=1; $i<=$cassette; $i++) {
			$kode = $wsid.".CST.".sprintf('%02d', $i);
			
			$template_content .= '
				<td align="center">
					<div class="">
						<div class="">
							<img style="margin-top: 18px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bast').'/'.$kode.'.png" width="150" height="150"></img><br>
							<span>CASSETTE-'.$i.' :'.$kode.'</span>
						</div>
					</div>
				</td>
			';
			
			if ($j%2==0) {
				// $template_content .= $j;
			} else {
				$template_content .= '</tr><tr>';
			}
			
			$j++;
		}
		
		for($i=1; $i<=$divert; $i++) {
			$kode = $wsid.".DIV.".sprintf('%02d', $i);
			
			$template_content .= '
				<td align="center">
					<div class="">
						<div class="">
							<img style="margin-top: 18px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bast').'/'.$kode.'.png" width="150" height="150"></img><br>
							<span>DIVERT : '.$kode.'</span>
						</div>
					</div>
				</td>
			';
			
			if ($j%2==0) {
				// $template_content .= $j;
			} else {
				$template_content .= '</tr><tr>';
			}
			
			$j++;
		}
		
		for($i=1; $i<=1; $i++) {
			$template_content .= '
				<td align="center">
					<div class="">
						<div class="">
							<img style="margin-top: 18px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bast').'/'.$wsid.'.png" width="150" height="150"></img><br>
							<span>ID ATM : '.$wsid.'</span>
						</div>
					</div>
				</td>
			';
			
			if ($j%2==0) {
				// $template_content .= $j;
			} else {
				$template_content .= '</tr><tr>';
			}
			
			$j++;
		}
		
		
		$template_html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 21cm 29.7cm portrait; }
					
					
						body {
							margin: 1px 1px 1px 1px; 
							font-family: Calibri;            
						}
						
						table td {
							padding: 60px
						}
					</style>
				</head>

				<body>
					<table width="100%">
						'.$template_content.'
					</table>
				</body>
			</html>
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($template_html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function qrcode() { 
	
		$data_client = json_decode($this->curl->simple_get(rest_api().'/client_bank'));
		
		
		
		// foreach($data_client as $r) {
			// $template_content .= '
				// <img src="'.realpath(__DIR__ . '/../../upload/qrcode').'/'.$r->wsid.'.png" width="150" height="150"></img>
			// ';
		// }
		// $template_content = '
			// <div class="row">
				// <div class="column" style="margin-right: 40px; margin-left: 10px">
					// <img src="'.realpath(__DIR__ . '/../../upload/qrcode').'/1001.png" alt="Snow" style="width:100%; height:100%">
				// </div>
				// <div class="column">
					// <img src="'.realpath(__DIR__ . '/../../upload/qrcode').'/1001.png" alt="Forest" style="width:100%; height:100%">
				// </div>
			// </div>
		// ';
		
		$i=0;
		$template_content = '<div class="row">';
		$clas_kiri = '';
		foreach($data_client as $r) {
			
			if($i % 2 == 0) { $template_content .= '</div><div class="row">'; $clas_kiri = 'style="margin-right: 40px; margin-left: 10px"'; } else { $clas_kiri = ''; }
			
			$template_content .= '
				<div class="column" '.$clas_kiri.'>
					<img src="'.realpath(__DIR__ . '/../../upload/qrcode').'/'.$r->wsid.'.png" alt="Snow" style="width:100%; height:100%">
				</div>
			';
			$i++;
		}
		
		$template_html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 5cm 2cm portrait; }
					
					
						body {
							margin: 1px 1px 1px 1px; 
							font-family: Calibri;            
						}
						
						img {
							padding-top: 1px;
							padding-left: -2px;
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
						
						/* Three image containers (use 25% for four, and 50% for two, etc) */
						.column {
							float: left;
							width: 35%;
							padding: 0px;
						}
						
						.row::after {
							content: "";
							clear: both;
							display: table;
						} 
						
						.class_kiri {
							margin-right: 20px
						}
					</style>
				</head>

				<body>
					'.$template_content.'
		';
		
		// echo $template_html;
		
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($template_html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
    }
	
	public function tes() {    
		return view('admin/pdf/index');
    }

	public function generate_runsheet() {  
		$id_ct = $this->uri->segment(3);
		$id_ga = $this->uri->segment(4);
		
		// echo $id_ct." ";
		// echo $id_zona;
		
		$id = $this->uri->segment(3);
		
		$sql = "select *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail, cashtransit_detail.ctr as jum_ctr FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) WHERE cashtransit_detail.state='ro_atm' AND cashtransit_detail.data_solve='' AND cashtransit.id='$id_ct' AND client.sektor IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit='$id_ct' AND run_number='$id_ga') GROUP BY cashtransit_detail.id ORDER BY cashtransit.id DESC ";
		
		$result = $this->db->query($sql)->result();
		
		$mpdf = new \Mpdf\Mpdf();

		$html = '
		<html>
			<head>
				<style>

					@page {
						size: auto;
						odd-header-name: html_myHeader1;
						even-header-name: html_myHeader2;
						odd-footer-name: html_myFooter1;
						even-footer-name: html_myFooter2;
						sheet-size: 280mm 140mm;
						page-break-before: right;
					}
					div.chapter1 {
						page-break-before: right;
					}
				</style>
			</head>

			<body>';
			
			$i = 0;
			foreach($result as $row) {
				$i++;
				$denom = (intval($row->pcs_50000)!==0 ? 50000 : 100000);
				// print_r($row);
				
				
				if($i>1) {
					$html .= '<div class="chapter1">';
				}
				
				$ctr = $row->jum_ctr;
				$html .= '
					<div class="container">
						<div class="row">
							<div class="col-xs-5 col-sm-6">
								<h5 style="margin-bottom: -2px; font-weight: bold">PT. BINTANG JASA ARTHA KELOLA</h5>
								<p style="margin-bottom: -20px;">REPORT REPLENISH - RETURN ATM</p>
								<hr style="height:3px;border:none;color:#333;background-color:#333;width:60%; text-align: left; margin-bottom: -4px" />
								
								<table style="width: 100%; font-size: 10px; font-weight: bold;">
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
								<table style="width: 100%; font-size: 10px">
									<tr>
										<td style="width: 60px">BANK</td>
										<td style="width: 10px">:</td>
										<td>'.$row->bank.'</td>
										
										<td style="width: 60px">DENOM</td>
										<td style="width: 10px">:</td>
										<td>'.(intval($row->pcs_50000)!==0 ? "50000" : "100000").'</td>
									</tr>
									<tr>
										<td style="width: 60px">TYPE</td>
										<td style="width: 10px">:</td>
										<td>'.$row->type_mesin.'</td>
										
										<td style="width: 60px">VALUE</td>
										<td style="width: 10px">:</td>
										<td>'.(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000).'</td>
									</tr>
								</table>
							</div>
							<div class="col-xs-1">
								<table border=1 style="">
									<tr>
										<th style="width:50px; text-align: center"> RUN </th>
									</tr>
									<tr>
										<td style="height: 40px; text-align: center; font-size: 24px; font-weight: bold">'.$row->sektor.'</td>
									</tr>
								</table>
							</div>
							<div class="col-xs-4">
								<table style="width: 100%; font-size: 10px">
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
								<table style="width: 100%; font-size: 10px">
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
							</div>
						</div>
					</div>

					<table style="width: 100%; font-size: 10px; font-weight: bold" border=1>
						<thead>
							<tr>
								<th rowspan="2" align="center">PREPARATION</th>
								<th rowspan="2" align="center">SEAL PREPARE</th>
								<th colspan="2" align="center">STATUS</th>
								<th rowspan="2" align="center">SEAL RETURN</th>
								<th rowspan="2" align="center">VALUE</th>
								<th rowspan="2" align="center">TOTAL RETURN</th>
							</tr>
							<tr>
								<th width="90" align="center">PENGALIHAN</th>
								<th width="90" align="center">CANCEL</th>
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
								<td align="center"></td>
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
					
					<table style="width: 80%; font-size: 10px; margin-top: -14px">
						<tr>
							<td style="width: 120px">TOTAL CATRIDGE</td>
							<td style="width: 10px">:</td>
							<td>('.$ctr.') '.($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)).'</td>
							
							<td style="width: 120px">NO. BAG</td>
							<td style="width: 10px">:</td>
							<td>'.$row->bag_no.'</td>
						</tr>
						<tr>
							<td style="width: 60px">TOTAL</td>
							<td style="width: 10px">:</td>
							<td>Rp. '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom), 0, ",", ".").'</td>
							
							<td style="width: 60px">SEAL BAG(CPC)</td>
							<td style="width: 10px">:</td>
							<td>'.$row->bag_seal.'</td>
						</tr>
						<tr>
							<td style="width: 60px">TERBILANG</td>
							<td style="width: 10px">:</td>
							<td style="font-weight: bold"># '.ucwords($this->terbilang(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom))).' #</td>
							
							<td style="width: 60px">SEAL BAG(CSO)</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
					</table>
					
					<table style="width: 100%; font-size: 10px" border=1>
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
					</table>';
					
					if($i>1) {
						$html .= '</div>';
					}
				}
		$html .= '
			</body>
		</html>';

		$stylesheet = file_get_contents(base_url().'depend/bootstrap.min.css');
		$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html);

		// echo $html;
		$mpdf->Output();
	}  
	
	public function ongenerate() { 
		
		$html = '
			<html>
				<head>
					<style>
						@page { size: 22cm 14cm portrait; }
					
						table.first {
							font-family: helvetica;
							font-size: 8pt;
							width: 100%;
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
							font-family: helvetica;
							font-size: 8pt;
							border: 1px solid black;
							border-collapse: collapse;
							
							position: absolute;
							top: 20;
							right: 240;
						}
						
						.fourth {
							font-family: helvetica;
							font-size: 8pt;
							border-collapse: collapse;
						}
						
						.fifth {
							font-family: helvetica;
							font-size: 8pt;
							border-collapse: collapse;
						}
						
						table.fourth td {
						    padding: 4px;
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
					</style>
				</head>

				<body>
					<table class="first">
						<tr>
							<td width="50%">
								<h3>PT. BINTANG JASA ARTHA KELOLA</h3>
								<p>REPORT REPLENISH - RETURN ATM</p>
								<hr style="height:2px;border:none;color:#333;background-color:#333;width:100%; text-align: left; margin-top: -8px" />
								
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
					<table border="1" class="third">
						<tr>
							<td style="width: 45px; text-align: center">RUN</td>
						</tr>
						<tr>
							<td style="width: 45px; text-align: center; font-size: 24px; font-weight: bold">12</td>
						</tr>
					</table>
					
					<table style="width: 100%; font-size: 10px;" border=1 class="fourth">
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
								<td colspan="5" id="noborder"></td>
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
					
					<table style="width: 100%; font-size: 10px" border=1 class="fifth">
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
		// $dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->set_paper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function laporan_pdf(){

		$data = array(
			"dataku" => array(
				"nama" => "Petani Kode",
				"url" => "http://petanikode.com"
			)
		);

		$this->load->library('pdf');

		// $this->pdfa->setPaper('A4', 'potrait');
		// $this->pdfa->filename = "laporan-petanikode.pdf";
		// $this->pdfa->load_view('laporan_pdf', $data);
		
		return view('laporan_pdf');
	}
	
	public function ongenerate2() {
		
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
							font-family: "aaaaa", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;            
						}
					
						table.first {
							font-family: "aaaaa", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;            
							font-size: 8pt;
							width: 100%;
						}
						
						#h3 {
							font-family: "aaaaa", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
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
							font-family: "aaaaa", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;       
							font-size: 8pt;
							border: 1px solid black;
							border-collapse: collapse;
							position: absolute;
							top: 20;
							right: 240;
							border-style: dotted;
						}
						
						.fourth {
							font-family: "aaaaa", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;       
							font-size: 8pt;
							border-collapse: collapse;
							border: 1px solid black;
							border-style: dotted;
						}
						
						.fifth {
							font-family: "aaaaa", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;       
							font-size: 8pt;
							border-collapse: collapse;
							border: 1px solid black;
							border-style: dotted;
						}
						
						.sixth {
							font-family: "helveticae", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;       
							border: none;
							border-collapse: collapse;
						}
						
						.sixth td {
							padding: 4px;
							border: 1px solid black;
							border-style: dotted;
						}
						
						table.fourth td {
						    padding: 4px;
							border: 1px solid black;
							border-style: dotted;
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
								<hr style="border-style: dotted;height:1px;border:none;color:#333;background-color:#333;" />
								
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
							<td style="width: 45px; text-align: center; border: 1px solid black; border-style: dotted;">RUN</td>
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

		// Output the generated PDF to Browser
		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function ongenerate3() {
		
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

		// Output the generated PDF to Browser
		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function generate() {    
		error_reporting(0);
		$id = $this->uri->segment(3);
		// $sql = "select *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN runsheet_cashprocessing ON(cashtransit.id=runsheet_cashprocessing.id_cashtransit) WHERE cashtransit.state='ro_atm' AND cashtransit_detail.data_solve!='' AND cashtransit_detail.id='$id' ORDER BY cashtransit.id DESC";
		
		$sql = "select *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail, cashtransit_detail.ctr as jum_ctr FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) WHERE cashtransit_detail.state='ro_atm' AND cashtransit_detail.data_solve!='' AND cashtransit_detail.id='$id' GROUP BY cashtransit_detail.id ORDER BY cashtransit.id DESC";
		
		// echo $sql;
		$row = $this->db->query($sql)->row();
		// $data = json_decode($row->data_solve);
		if($row->cpc_process!=="") {
			$data = json_decode($row->cpc_process);
		} else {
			$data = json_decode($row->data_solve);
		}
		// $data = json_decode($row->data_solve);
		// print_r($row);
		// echo "<br>";
		// echo "<br>";
		// echo "<br>";
		// print_r($data);
		
		$denom = (intval($row->pcs_50000)!==0 ? 50000 : 100000);
	
		$mpdf = new Mpdf();
		// Define the Header/Footer before writing anything so they appear on the first page
		$mpdf->SetHTMLHeader('	
			<div class="container">
				
				<div class="row">
					<div class="col-xs-9 col-sm-4">
						<h5 style="margin-bottom: -2px; font-weight: bold">PT. BINTANG JASA ARTHA KELOLA</h5>
						<p style="margin-bottom: -20px;">REPORT REPLENISH - RETURN ATM</p>
						<hr style="height:3px;border:none;color:#333;background-color:#333;width:60%; text-align: left; margin-bottom: -4px" />
						
						<table style="width: 100%; font-size: 10px; font-weight: bold;">
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
						<table style="width: 100%; font-size: 10px; font-weight: bold;">
							<tr>
								<td style="width: 60px">BANK</td>
								<td style="width: 10px">:</td>
								<td>'.$row->bank.'</td>
								
								<td style="width: 60px">DENOM</td>
								<td style="width: 10px">:</td>
								<td>'.(intval($row->pcs_50000)!==0 ? "50000" : "100000").'</td>
							</tr>
							<tr>
								<td style="width: 60px">TYPE</td>
								<td style="width: 10px">:</td>
								<td>'.$row->type_mesin.'</td>
								
								<td style="width: 60px">VALUE</td>
								<td style="width: 10px">:</td>
								<td>'.(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000).'</td>
							</tr>
						</table>
					</div>
					<div class="col-sm-4">
						<table style="width: 100%; font-size: 10px; font-weight: bold">
							<tr>
								<td style="width: 150px">TANGGAL</td>
								<td style="width: 10px">:</td>
								<td>'.date("d-M-Y", strtotime(explode(" ", $row->date)[0])).'</td>
							</tr>
							<tr>
								<td>TIME REPLENISH(CSO)</td>
								<td>:</td>
								<td>'.date("H:i", strtotime(explode(" ", $row->updated_date)[1])).'</td>
							</tr>
							<tr>
								<td>TIME PREPARE BAG(CPC)</td>
								<td>:</td>
								<td>'.date("H:i", strtotime(explode(" ", $row->updated_date_cpc)[1])).'</td>
							</tr>
						</table>
						<hr style="height:1px;border:none;color:#333;background-color:#333;width:100%; text-align: left; margin: 2px" />
						<table style="width: 100%; font-size: 10px; font-weight: bold">
							<tr>
								<td style="width: 150px">CASHIER</td>
								<td style="width: 10px">:</td>
								<td>'.($data->cashier!==null ? $data->cashier : "...........................").'</td>
							</tr>
							<tr>
								<td>NO. MEJA</td>
								<td>:</td>
								<td>'.($data->nomeja!==null ? $data->nomeja : "...........................").'</td>
							</tr>
							<tr>
								<td>JAM PROSES</td>
								<td>:</td>
								<td>'.($data->jamproses!==null ? $data->jamproses : "...........................").'</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		');
		// $mpdf->SetHTMLFooter('
		// <table width="100%">
			// <tr>
				// <td width="33%">{DATE j-m-Y}</td>
				// <td width="33%" align="center">{PAGENO}</td>
				// <td width="33%" style="text-align: right;">My document</td>
			// </tr>
		// </table>');
		
		$total = ($data->cart_1_no!=="" ? (intval($denom)*$data->cart_1_no) : "") +
				 ($data->cart_2_no!=="" ? (intval($denom)*$data->cart_2_no) : "") +
				 ($data->cart_3_no!=="" ? (intval($denom)*$data->cart_3_no) : "") +
				 ($data->cart_4_no!=="" ? (intval($denom)*$data->cart_4_no) : "") +
				 ($data->div_no!=="" ? (intval($denom)*$data->div_no) : "");
				 
		$ctr_1 = ($data->cart_1_seal!=="") ? 1 : 0;
		$ctr_2 = ($data->cart_2_seal!=="") ? 1 : 0;
		$ctr_3 = ($data->cart_3_seal!=="") ? 1 : 0;
		$ctr_4 = ($data->cart_4_seal!=="") ? 1 : 0;

		// $ctr = $ctr_1+$ctr_2+ +$ctr_3+$ctr_4;
		$ctr = $row->jum_ctr;
		
		if(!empty($row->receipt_1)) {
			$receipt_1 = '
				<td style="padding-top: 140px; padding-bottom: 140px;">
					<img style="width: 500px; height: 350px;
						-webkit-transform: rotate(90deg);
						-moz-transform: rotate(90deg);
						-o-transform: rotate(90deg);
						-ms-transform: rotate(90deg);
						transform: rotate(90deg);" src="data:image/jpeg;base64,'.json_decode($row->receipt_1).'" alt="" title="" class="rotate90_2" />
				</td>
			';
		} else {
			$receipt_1 = 'hah kosong!';
		}
		
		if(!empty($row->receipt_2)) {
			$receipt_2 = '
				<td style="padding-top: 140px; padding-bottom: 140px;">
					<img style="width: 500px; height: 350px;
						-webkit-transform: rotate(90deg);
						-moz-transform: rotate(90deg);
						-o-transform: rotate(90deg);
						-ms-transform: rotate(90deg);
						transform: rotate(90deg);" src="data:image/jpeg;base64,'.json_decode($row->receipt_2).'" alt="" title="" class="rotate90_2" />
				</td>
			';
		} else {
			$receipt_2 = 'hah kosong!';
		}
		
		if(!empty($row->receipt_3)) {
			$receipt_3 = '
				<td style="padding-top: 140px; padding-bottom: 140px;">
					<img style="width: 500px; height: 350px;
						-webkit-transform: rotate(90deg);
						-moz-transform: rotate(90deg);
						-o-transform: rotate(90deg);
						-ms-transform: rotate(90deg);
						transform: rotate(90deg);" src="data:image/jpeg;base64,'.json_decode($row->receipt_3).'" alt="" title="" class="rotate90_2" />
				</td>
			';
		} else {
			$receipt_3 = 'hah kosong!';
		}
		
		if(!empty($row->receipt_4)) {
			$receipt_4 = '
				<td style="padding-top: 140px; padding-bottom: 140px;">
					<img style="width: 500px; height: 350px;
						-webkit-transform: rotate(90deg);
						-moz-transform: rotate(90deg);
						-o-transform: rotate(90deg);
						-ms-transform: rotate(90deg);
						transform: rotate(90deg);" src="data:image/jpeg;base64,'.json_decode($row->receipt_4).'" alt="" title="" class="rotate90_2" />
				</td>
			';
		} else {
			$receipt_4 = 'hah kosong!';
		}

		$stylesheet = file_get_contents(base_url().'depend/bootstrap.min.css');
		$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML('
		<br><br><br><br>
		
		<table style="width: 100%; font-size: 10px; font-weight: bold" border=1>
			<thead>
				<tr>
					<th rowspan="2" align="center">PREPARATION</th>
					<th rowspan="2" align="center">SEAL PREPARE</th>
					<th colspan="2" align="center">STATUS</th>
					<th rowspan="2" align="center">SEAL RETURN</th>
					<th rowspan="2" align="center">VALUE</th>
					<th rowspan="2" align="center">TOTAL RETURN</th>
				</tr>
				<tr>
					<th width="90" align="center">PENGALIHAN</th>
					<th width="90" align="center">CANCEL</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Catridge 1</td>
					<td align="center">'.$row->cart_1_seal.'</td>
					<td></td>
					<td></td>
					<td align="center">'.$data->cart_1_seal.'</td>
					<td align="center">'.($data->cart_1_seal!=="" ? intval($data->cart_1_no) : "").'</td>
					<td align="center">'.($data->cart_1_seal!=="" ? 'Rp. '.number_format((intval($denom)*$data->cart_1_no), 0, ",", ".") : "").'</td>
				</tr>
				<tr>
					<td>Catridge 2</td>
					<td align="center">'.$row->cart_2_seal.'</td>
					<td></td>
					<td></td>
					<td align="center">'.$data->cart_2_seal.'</td>
					<td align="center">'.($data->cart_2_seal!=="" ? intval($data->cart_2_no) : "").'</td>
					<td align="center">'.($data->cart_2_seal!=="" ? 'Rp. '.number_format((intval($denom)*$data->cart_2_no), 0, ",", ".") : "").'</td>
				</tr>
				<tr>
					<td>Catridge 3</td>
					<td align="center">'.$row->cart_3_seal.'</td>
					<td></td>
					<td></td>
					<td align="center">'.$data->cart_3_seal.'</td>
					<td align="center">'.($data->cart_3_seal!=="" ? intval($data->cart_3_no) : "").'</td>
					<td align="center">'.($data->cart_3_seal!=="" ? 'Rp. '.number_format((intval($denom)*$data->cart_3_no), 0, ",", ".") : "").'</td>
				</tr>
				<tr>
					<td>Catridge 4</td>
					<td align="center">'.$row->cart_4_seal.'</td>
					<td></td>
					<td></td>
					<td align="center">'.$data->cart_4_seal.'</td>
					<td align="center">'.($data->cart_4_seal!=="" ? intval($data->cart_4_no) : "").'</td>
					<td align="center">'.($data->cart_4_seal!=="" ? 'Rp. '.number_format((intval($denom)*$data->cart_4_no), 0, ",", ".") : "").'</td>
				</tr>
				<tr>
					<td>Divert</td>
					<td align="center">'.$row->divert.'</td>
					<td></td>
					<td></td>
					<td align="center">'.$data->div_seal.'</td>
					<td align="center">'.($data->div_seal!=="" ? intval($data->div_no) : "").'</td>
					<td align="center">'.($data->div_seal!=="" ? 'Rp. '.number_format((intval($denom)*$data->div_no), 0, ",", ".") : "").'</td>
				</tr>
				<tr>
					<td colspan=5 id="noborder"></td>
					<td align="center">'.(intval($data->div_no)+intval($data->cart_4_no)+intval($data->cart_3_no)+intval($data->cart_2_no)+intval($data->cart_1_no)).'</td>
					<td align="center">Rp. '.number_format($total, 0, ",", ".").'</td>
				</tr>
			</tbody>
		</table>
		
		<table style="width: 80%; font-size: 10px; margin-top: -14px">
			<tr>
				<td style="width: 120px">TOTAL CATRIDGE</td>
				<td style="width: 10px">:</td>
				<td>('.$ctr.') '.($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)).'</td>
				
				<td style="width: 120px">NO. BAG</td>
				<td style="width: 10px">:</td>
				<td>'.$data->bag_no.'</td>
			</tr>
			<tr>
				<td style="width: 60px">TOTAL</td>
				<td style="width: 10px">:</td>
				<td>Rp. '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom), 0, ",", ".").'</td>
				
				<td style="width: 60px">SEAL BAG(CPC)</td>
				<td style="width: 10px">:</td>
				<td>'.$row->bag_seal.'</td>
			</tr>
			<tr>
				<td style="width: 60px">TERBILANG</td>
				<td style="width: 10px">:</td>
				<td style="font-weight: bold"># '.ucwords($this->terbilang(300000000)).' #</td>
				
				<td style="width: 60px">SEAL BAG(CSO)</td>
				<td style="width: 10px">:</td>
				<td>'.$data->bag_seal.'</td>
			</tr>
		</table>
		
		<table style="width: 100%; font-size: 10px" border=1>
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
		
		<table id="table" style="margin-top: 120px" border=1>
			<tr>
				'.$receipt_1.'
				'.$receipt_2.'
				'.$receipt_3.'
			</tr>
			<tr>
				'.$receipt_4.'
			</tr>
		</table>
		',\Mpdf\HTMLParserMode::HTML_BODY);
		
		// $mpdf->WriteHTML('<pagebreak pagenumstyle="1" suppress="off" />');
		
		$mpdf->Output($row->wsid.'('.date('Ymd').').pdf', 'I');
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