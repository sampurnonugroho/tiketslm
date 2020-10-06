<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {
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

			$this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			$this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			$this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			$this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        $this->data['active_menu'] = "kategori";

        $this->data['datakategori'] = json_decode($this->curl->simple_get(rest_api().'/master_kategori'));
        return view('admin/kategori/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "kategori";
		$this->data['url'] = "kategori/save";
		$this->data['flag'] = "add";
		
		$this->data['id_kategori'] = "";		
		$this->data['nama_kategori'] = "";
		
        return view('admin/kategori/form', $this->data);
    }
	
	function save() {
		$nama_kategori = strtoupper(trim($this->input->post('nama_kategori')));

		$data['nama_kategori'] = $nama_kategori;

		$insert = $this->curl->simple_post(rest_api().'/master_kategori',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('kategori');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('kategori');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "kategori";
		$this->data['url'] = "kategori/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM kategori WHERE id_kategori = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));

		$this->data['url'] = "kategori/update";
			
		$this->data['id_kategori'] = $id;		
		$this->data['nama_kategori'] = $row->nama_kategori;
		
		return view('admin/kategori/form', $this->data);
	}
	
	function update() {
		$id_kategori = strtoupper(trim($this->input->post('id_kategori')));
		$nama_kategori = strtoupper(trim($this->input->post('nama_kategori')));

		$data['id_kategori'] = $id_kategori;
		$data['nama_kategori'] = $nama_kategori;

		$update = $this->curl->simple_put(rest_api().'/master_kategori',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('kategori');	
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('kategori');	
		}
	}
	
	function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_kategori', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));

		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}