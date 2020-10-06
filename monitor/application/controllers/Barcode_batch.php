<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class Barcode_batch extends CI_Controller {
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
		$this->data['active_menu'] = "barcode_batch";
		// $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
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
		
		$this->data['data_batch'] = $this->db->query("SELECT * FROM barcode_batch")->result();
        
        return view('admin/barcode_batch/index', $this->data);
    }
	
	public function add() {
		$this->data['active_menu'] = "barcode_batch";
		$this->data['url'] = "barcode_batch/save";
		$this->data['flag'] = "add";
		
		$this->data['id'] = '';
		$this->data['batch_barcode'] = $this->model_app->get_batch_code();
		
        return view('admin/barcode_batch/form', $this->data);
	}
	
	public function save() {
		// echo "<pre>";
		// print_r($_REQUEST);
		// echo "</pre>";
		
		$this->db->trans_start();

		$this->db->insert('barcode_batch', $_REQUEST);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('barcode_batch');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('barcode_batch');	
		}
	}
	
	public function edit($id) {
		
	}
}