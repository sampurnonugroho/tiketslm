<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client_cit extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("ticket_model");
        $this->load->library('form_validation');
		$this->load->library('curl');

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));
			$level = trim($this->session->userdata('level'));

			$this->data['level'] = $level;

			// $this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			// $this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			// $this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			// $this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        $this->data['active_menu'] = "client_cit";

        $this->data['data_client'] = json_decode($this->curl->simple_get(rest_api().'/client_cit'));
        return view('admin/client_cit/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "client_cit";
		$this->data['url'] = "client_cit/save";
		$this->data['flag'] = "add";
		
		$this->data['id'] = '';
		$this->data['nama_client'] = '';
		$this->data['alamat'] = '';
		$this->data['kode_pos'] = '';
		$this->data['wilayah'] = '';
		$this->data['pic'] = '';
		$this->data['telp'] = '';
		$this->data['ktp'] = '';

		
        return view('admin/client_cit/form', $this->data);
    }
	
	function save() {
		$nama_client		= strtoupper(trim($this->input->post('nama_client')));
		$alamat				= strtoupper(trim($this->input->post('alamat')));
		$cabang				= strtoupper(trim($this->input->post('cabang')));
		$sektor				= strtoupper(trim($this->input->post('sektor')));
		$kode_pos			= strtoupper(trim($this->input->post('kode_pos')));
		$wilayah			= strtoupper(trim($this->input->post('wilayah')));
		$pic				= strtoupper(trim($this->input->post('pic')));
		$telp				= strtoupper(trim($this->input->post('telp')));
		$ktp				= strtoupper(trim($this->input->post('ktp')));

		$data['nama_client'] = $nama_client;
		$data['alamat'] = $alamat;
		$data['cabang'] = $cabang;
		$data['sektor'] = $sektor;
		$data['kode_pos'] = $kode_pos;
		$data['wilayah'] = $wilayah;
		$data['pic'] = $pic;
		$data['telp'] = $telp;
		$data['ktp'] = $ktp;
		
		$table = "client_cit";
		$result = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));

		if ($result) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('client_cit');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('client_cit');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "client_cit";
		$this->data['url'] = "client_cit/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM client_cit WHERE id = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
			
		$this->data['id'] = $row->id;
		$this->data['nama_client'] = $row->nama_client;
		$this->data['alamat'] = $row->alamat;
		$this->data['kode_pos'] = $row->kode_pos;
		$this->data['wilayah'] = $row->wilayah;
		$this->data['pic'] = $row->pic;
		$this->data['telp'] = $row->telp;
		$this->data['ktp'] = $row->ktp;
		
		return view('admin/client_cit/form', $this->data);
	}
	
	function update() {
		$id 				= strtoupper(trim($this->input->post('id')));
		
		$nama_client		= strtoupper(trim($this->input->post('nama_client')));
		$alamat				= strtoupper(trim($this->input->post('alamat')));
		$cabang				= strtoupper(trim($this->input->post('cabang')));
		$sektor				= strtoupper(trim($this->input->post('sektor')));
		$kode_pos			= strtoupper(trim($this->input->post('kode_pos')));
		$wilayah			= strtoupper(trim($this->input->post('wilayah')));
		$pic				= strtoupper(trim($this->input->post('pic')));
		$telp				= strtoupper(trim($this->input->post('telp')));
		$ktp				= strtoupper(trim($this->input->post('ktp')));

		$data['id'] = $id;
		$data['nama_client'] = $nama_client;
		$data['alamat'] = $alamat;
		$data['cabang'] = $cabang;
		$data['sektor'] = $sektor;
		$data['kode_pos'] = $kode_pos;
		$data['wilayah'] = $wilayah;
		$data['pic'] = $pic;
		$data['telp'] = $telp;
		$data['ktp'] = $ktp;
		
		// echo "<pre>";
		// print_r($data);
		
		$table = "client_cit";
		$result = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));

		if ($result) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('client_cit');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('client_cit');	
		}
	}
	
	function delete() {	
		// $id = $_POST['id'];

		// $this->db->trans_start();

		// $this->db->where('id', $id);
		// $this->db->delete('client_cit');

		// $this->db->trans_complete();
		
		// if ($this->db->trans_status() === FALSE) {
			// $this->session->set_flashdata('error', 'Data gagal dihapus.');
			// echo "failed";
		// } else  {
			// $this->session->set_flashdata('success', 'Data dihapus.');
			// echo "success";
		// }
		
		$data['id'] = $_POST['id'];
		
		$table = "client_cit";
		$delete = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
	
	private function _uploadImage() {
		$config['upload_path']          = './upload/client/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['file_name']            = $this->input->post('wsid');
		$config['overwrite']			= true;
		$config['max_size']             = 1024; // 1MB
		// $config['max_width']            = 1024;
		// $config['max_height']           = 768;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('image')) {
			return $this->upload->data("file_name");
		}
		
		return "default.jpg";
	}
}