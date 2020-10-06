<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bagian_departemen extends CI_Controller {
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

			$this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			$this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			$this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			$this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        $this->data['active_menu'] = "bagian_departemen";

        $this->data['data_bagian_departemen'] = json_decode($this->curl->simple_get(rest_api().'/master_subdepartemen'));
        return view('admin/bagian_departemen/index', $this->data);
    }
	
	function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_subdepartemen', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));

		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}

    public function add() {
        $this->data['active_menu'] = "bagian_departemen";
		$this->data['url'] = "bagian_departemen/save";
		$this->data['flag'] = "add";
		
		$this->data['dd_departemen'] = $this->model_app->dropdown_departemen();
		$this->data['id_departemen'] = "";

		$this->data['id_bagian_dept'] ="";
		$this->data['nama_bagian_dept'] ="";

		
        return view('admin/bagian_departemen/form', $this->data);
    }
	
	function save() {
		$nama_bagian_dept = strtoupper(trim($this->input->post('nama_bagian_dept')));
		$id_dept = strtoupper(trim($this->input->post('id_departemen')));

		$data['nama_bagian_dept'] = $nama_bagian_dept;
		$data['id_dept'] = $id_dept;

		$insert = $this->curl->simple_post(rest_api().'/master_subdepartemen',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('bagian_departemen');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('bagian_departemen');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "bagian_departemen";
		$this->data['url'] = "bagian_departemen/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM bagian_departemen WHERE id_bagian_dept = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));

		$this->data['id_bagian_dept'] = $id;		
		$this->data['nama_bagian_dept'] = $row->nama_bagian_dept;

		$this->data['dd_departemen'] = $this->model_app->dropdown_departemen();
		$this->data['id_departemen'] = $row->id_dept;
		
		return view('admin/bagian_departemen/form', $this->data);
	}
	
	function update() {
		$id_bagian_dept = strtoupper(trim($this->input->post('id_bagian_dept')));
		$id_dept = strtoupper(trim($this->input->post('id_departemen')));
		$nama_bagian_dept = strtoupper(trim($this->input->post('nama_bagian_dept')));

		$data['id_bagian_dept'] = $id_bagian_dept;
		$data['nama_bagian_dept'] = $nama_bagian_dept;
		$data['id_dept'] = $id_dept;

		$update = $this->curl->simple_put(rest_api().'/master_subdepartemen',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('bagian_departemen');	
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('bagian_departemen');	
		}
	}
}