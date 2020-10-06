<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class Karyawan extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("karyawan_model");
        $this->load->model("ticket_model");
        $this->load->library('form_validation');
		$this->load->library('curl');

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));
			$level = trim($this->session->userdata('level'));

			$this->data['level'] = $level;

			$this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			$this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			$this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			$this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        $this->data['active_menu'] = "karyawan";
		
		$data_karyawan = json_decode($this->curl->simple_get(rest_api().'/master_karyawan'));
		
		// print_r($data_karyawan);
		foreach($data_karyawan as $row) {
			if($row->id_karyawan!=="") {
				$qrCode = new QrCode($row->id_karyawan);
				$qrCode->setSize(300);
				
				$qrCode->writeFile(realpath(__DIR__ . '/../../upload/qrcode_karyawan').'/'.$row->id_karyawan.'.png');
			}
		}

        $this->data['data_karyawan'] = json_decode($this->curl->simple_get(rest_api().'/master_karyawan'));
        return view('admin/karyawan/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "karyawan";
		
		$this->data['url'] = "karyawan/save";
		
        $this->data['nik'] = "";		
        $this->data['id_karyawan'] = "";		
		$this->data['nama'] = "";
		$this->data['alamat'] = "";
		
		$this->data['dd_jk'] = $this->model_app->dropdown_jk();
		$this->data['id_jk'] = "";

        $this->data['dd_jabatan'] = $this->model_app->dropdown_jabatan();
		$this->data['id_jabatan'] = "";

		$this->data['dd_departemen'] = $this->model_app->dropdown_departemen();
		$this->data['id_departemen'] = "";
		
		$this->data['flag'] = "add";
		
        return view('admin/karyawan/form', $this->data);
    }
	
	function save() {
		$id_karyawan = strtoupper(trim($this->input->post('id_karyawan')));
		$nama = strtoupper(trim($this->input->post('nama')));
		$jk = strtoupper(trim($this->input->post('id_jk')));
		$alamat = strtoupper(trim($this->input->post('alamat')));
		$id_bagian_dept = strtoupper(trim($this->input->post('id_bagian_departemen')));
		$id_jabatan = strtoupper(trim($this->input->post('id_jabatan')));

		$data['id_karyawan'] = $id_karyawan;
		$data['nama'] = $nama;
		$data['jk'] = $jk;
		$data['alamat'] = $alamat;
		$data['id_bagian_dept'] = $id_bagian_dept;
		$data['id_jabatan'] = $id_jabatan;
		
		// print_r($data);

		$insert = $this->curl->simple_post(rest_api().'/master_karyawan',$data,array(CURLOPT_BUFFERSIZE => 10));

		// echo rest_api().'/master_karyawan<br>';
		// echo $insert;
		if (!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('karyawan');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('karyawan');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "karyawan";
	
		$row = json_decode($this->curl->simple_get(rest_api().'/master_karyawan?nik='.$id))[0];


		$this->data['url'] = "karyawan/update";

		$this->data['nik'] = $id;		
		$this->data['id_karyawan'] = $row->id_karyawan;		
		$this->data['nama'] = $row->nama;
		$this->data['alamat'] = $row->alamat;

		$this->data['dd_jk'] = $this->model_app->dropdown_jk();
		$this->data['id_jk'] = $row->jk;

		$this->data['dd_jabatan'] = $this->model_app->dropdown_jabatan();
		$this->data['id_jabatan'] = $row->id_jabatan;

		$this->data['dd_bagian_departemen'] = $this->model_app->dropdown_bagian_departemen($row->id_dept);
		$this->data['id_bagian_departemen'] = $row->id_bagian_dept;

		$this->data['dd_departemen'] = $this->model_app->dropdown_departemen();
		$this->data['id_departemen'] = $row->id_dept;
		
		$this->data['flag'] = "edit";
		
		return view('admin/karyawan/form', $this->data);
	}
	
	function update() {
		$nik = strtoupper(trim($this->input->post('nik')));
		$id_karyawan = strtoupper(trim($this->input->post('id_karyawan')));

		$nama = strtoupper(trim($this->input->post('nama')));
		$jk = strtoupper(trim($this->input->post('id_jk')));
		$alamat = strtoupper(trim($this->input->post('alamat')));
		$id_bagian_dept = strtoupper(trim($this->input->post('id_bagian_departemen')));
		$id_jabatan = strtoupper(trim($this->input->post('id_jabatan')));
		
		
		$data['nik'] = $nik;
		$data['id_karyawan'] = $id_karyawan;
		$data['nama'] = $nama;
		$data['jk'] = $jk;
		$data['alamat'] = $alamat;
		$data['id_bagian_dept'] = $id_bagian_dept;
		$data['id_jabatan'] = $id_jabatan;

		$update = $this->curl->simple_put(rest_api().'/master_karyawan',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('karyawan');	
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('karyawan');	
		}
	}
	
	function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_karyawan', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));

		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
		
		
	}
}