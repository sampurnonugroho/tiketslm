<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

use Dompdf\Dompdf;

class Barcode_generates extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("ticket_model");
        $this->load->library('form_validation');

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));
			$level = trim($this->session->userdata('level'));

			$this->data['level'] = $level;
		} else {
            redirect('');
        }
    }
	
    public function index() {       
		$this->data['active_menu'] = "barcode_generates";
		$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
		// echo '<img style="width: 220px; height: 60px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($this->model_app->get_batch_code(), $generator::TYPE_CODE_128)) . '">';
		// foreach($this->model_app->get_child_code($this->model_app->get_batch_code(), 100) as $r) {
			// echo "<br>";
			// echo '<img style="width: 220px; height: 60px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($r, $generator::TYPE_CODE_128)) . '">';
			// echo "<br>";
		// }
		
		// print_r($this->model_app->get_batch_code());
		// echo "<br>";
		// echo "<pre>";
		// print_r($this->model_app->get_child_code($this->model_app->get_batch_code(), 10));
		// echo "</pre>";
		
		$datas = $this->db->query("SELECT * FROM barcode_generates LEFT JOIN barcode_batch ON(barcode_generates.batch=barcode_batch.barcode) GROUP BY barcode_generates.batch")->result();
        // $this->data['data_batch'] = 
		$items = array();
		$i = 0;
		foreach($datas as $row){
			$items[$i]['id'] = $row->id;
			$items[$i]['batch'] = $row->batch;
			$items[$i]['barcode'] = $row->barcode;
			$items[$i]['purposes'] = $row->purposes;
			$items[$i]['branch'] = $row->branch;
			$items[$i]['area'] = $row->area;
			$items[$i]['bag_seal'] = $row->bag_seal;
			$items[$i]['preview'] = '<img style="width: 160px; height: 60px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($row->barcode, $generator::TYPE_CODE_128)) . '">';
			$i++;
		}
		$this->data['data_batch'] = $items;
		
        return view('admin/barcode_generates/index', $this->data);
    }
	
	public function add() {
		$this->data['active_menu'] = "barcode_generates";
		$this->data['url'] = "barcode_generates/save";
		$this->data['flag'] = "add";
		
		$this->data['id'] = '';
		
		$this->data['dd_batch'] = $this->model_app->dropdown_batch_barcode();
		$this->data['id_batch'] = "";
		
		$this->data['quantity'] = "";
		
        return view('admin/barcode_generates/form', $this->data);
	}
	
	public function save() {
		echo "<pre>";
		print_r($_REQUEST);
		echo "</pre>";
		
		$parent = trim($this->input->post('id_batch'));
		$quantity = trim($this->input->post('quantity'));
		
		$this->db->trans_start();
		
		$p = substr($parent, 8, 5);
		$datax = array();
		for($i=1; $i<=$quantity; $i++) {
			$max_nik = $i;
			$nik = $p.sprintf("%08s", $max_nik);
			$datax['batch'] = $parent;
			$datax['barcode'] = $nik;
			$datax['qty'] = $quantity;
			
			$this->db->insert('barcode_generates', $datax);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('barcode_generates');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('barcode_generates');	
		}
	}
	
	public function print_barcode($id) {
		
		$data = $this->db->query("SELECT * FROM barcode_generates LEFT JOIN barcode_batch ON(barcode_generates.batch=barcode_batch.barcode) WHERE barcode_generates.batch='$id' GROUP BY batch")->row();
		
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
						
						foreach($this->model_app->get_child_code($data->batch, $data->qty) as $r) {
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
	
	public function print_barcode2() {
		$id = $this->uri->segment(3);
		
		
		$data = $this->db->query("SELECT * FROM barcode_generates LEFT JOIN barcode_batch ON(barcode_generates.batch=barcode_batch.barcode) WHERE barcode_generates.batch='$id' GROUP BY batch")->row();
		
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
							@page { margin: 0px; size: 22cm 14cm portrait; }
							table tr td {
								padding-left: 60px;
								padding-right: 60px;
							}
						</style>
					</head>
					<body>';
						
						$html .= '
							<table border=1>';
								$html .= '
									<tr>
								';
								$columns = 3;
								$i = 0;
								foreach($this->model_app->get_child_code($data->batch, $data->qty) as $r) {
									
									// if($i % 3 == 0) {
										// $html .= '
											// <td>
												// <img style="width: 250px; height: 60px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($r, $generator::TYPE_CODE_128)) . '">
											// </td>
										// ';
									// } else {
										// $html .= '
											// </tr><tr>
										// ';
									// }
									// $i++;
									
									$i++;
									//if this is first value in row, create new row
									if ($i % $columns == 1) {
										$html .= '<tr>';
									}
									$html .= '<td><img style="width: 120px; height: 40px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($r, $generator::TYPE_CODE_128)) . '"><br><center>'.$r.'</center></td>';
									//if this is last value in row, end row
									if ($i % $columns == 0) {
										$html .= '</tr>';
									}
									
									if($i==15) {
										break;
									}
								}
								
								$spacercells = $columns - ($i % $columns);
								if ($spacercells < $columns) {
									for ($j=1; $j<=$spacercells; $j++) {
										$html .= '<td></td>';
									}
									$html .= '</tr>';
								}
								
						$html .= '
							</table>
						';
		
		$html .= '</body>
				</html>';

	
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
}