<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Mpdf\Mpdf;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class Qr extends CI_Controller {
	var $data = array();
	
    public function __construct() {
        parent::__construct();
		$this->load->library('curl');
    }

	public function index() {
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
		
		$data['wsid'] = $wsid;
		$data['cassette'] = $cassette;
		$data['divert'] = $divert;
		$this->load->library('pdf');
	
		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "qrcode-handover-".$wsid.".pdf";
		$this->pdf->load_view('tes', $data);
	}
	
	public function print_receipt() {
		$date = str_replace("%20", " ", $this->uri->segment(3));
		
		// echo $date;
		
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
		
		// echo "<pre>";
		// print_r($result);
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
	
	public function gen() {
		$template_content = '';
		$from = $this->uri->segment(3);
		$to = $this->uri->segment(4);
		$html = $this->uri->segment(5);
		
		
		
		$fr = intval(str_replace("RC", "", $from));
		$to = intval(str_replace("RC", "", $to));
		
		for($i=$fr; $i<=$to; $i++) {
			$qrCode = new QrCode("RC".sprintf('%05d', $i));
			$qrCode->setSize(300);
			$qrCode->writeFile(realpath(__DIR__ . '/../../upload/tes').'/RC'.sprintf('%05d', $i).'.png');
		}
		
		
		if(!isset($html)) {
			for($i=$fr; $i<=$to; $i++) {
				$template_content .= '
					<div class="">
						<div class="">
							
							<center>
								<img style="margin-top: 18px" src="'.realpath(__DIR__ . '/../../upload/tes').'/RC'.sprintf('%05d', $i).'.png" width="90" height="90"></img>
							</center>
							<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
								<span>RC'.sprintf('%05d', $i).'</span>
							</center>
						</div>
					</div>
				';
			}
		} else {
			for($i=$fr; $i<=$to; $i++) {
				$template_content .= '
					<div class="">
						<div class="">
							
							<center>
								<img style="margin-top: 18px" src="'.base_url().'upload/tes/a'.$i.'.png" width="80" height="80"></img>
							</center>
							<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
								<span>a'.$i.'</span>
							</center>
						</div>
					</div>
				';
			}
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
				</body>
			</html>
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
	
	public function gen2() {
		$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
		$template_content = '';
		$from = $this->uri->segment(3);
		$to = $this->uri->segment(4);
		$html = $this->uri->segment(5);
		
		
		
		$fr = intval(str_replace("195.", "", $from));
		$to = intval(str_replace("195.", "", $to));
		
		// for($i=$fr; $i<=$to; $i++) {
			// $qrCode = new QrCode("a".$i);
			// $qrCode->setSize(300);
			// $qrCode->writeFile(realpath(__DIR__ . '/../../upload/tes').'/a'.$i.'.png');
		// }
		
		
		if(!isset($html)) {
			for($i=$fr; $i<=$to; $i++) {
				$template_content .= '
					<div class="">
						<div class="">
							
							<center>
								<img style="width: 150px; height: 60px" src="data:image/png;base64,' . base64_encode($generator->getBarcode('195.'.$i, $generator::TYPE_CODE_128)) . '">
							</center>
							<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
								<span>195.'.$i.'</span>
							</center>
						</div>
					</div>
				';
				
				if($i!=$to) {
					$template_content .= '<div style="page-break-after: always;"></div>';
				}
			}
		} else {
			for($i=$fr; $i<=$to; $i++) {
				$template_content .= '
					<div class="">
						<div class="">
							
							<center>
								<img style="width: 120px; height: 40px" src="data:image/png;base64,' . base64_encode($generator->getBarcode('a'.$i, $generator::TYPE_CODE_128)) . '">
							</center>
							<center style="margin-top: 1px; font-size: 12px; font-weight: bold">
								<span>a'.$i.'</span>
							</center>
						</div>
					</div>
					
					<div style="page-break-after: always;"></div>
				';
			}
		}
		
		$template_html = '
			<html>
				<head>
					<style>
						
						@page { margin-top: 20px; margin-bottom: 0px;  size: 6cm 3.5cm portrait; }
					
					
						body {
							margin: 1px 1px 1px 1px; 
							font-family: Calibri;            
						}
					</style>
				</head>

				<body>
					'.$template_content.'
				</body>
			</html>
		';
		
		if(!isset($html)) {
			$dompdf = new DOMPDF();
			$dompdf->loadHtml($template_html);
			$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
			$dompdf->render();

			$dompdf->stream('tbag.pdf', array("Attachment" => false));
		} else {
			echo $template_html;
		}
	}
}