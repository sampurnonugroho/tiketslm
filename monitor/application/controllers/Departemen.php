<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Departemen extends CI_Controller {
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
        $this->data['active_menu'] = "departemen";

        $this->data['data_departemen'] = json_decode($this->curl->simple_get(rest_api().'/master_departemen'));
        return view('admin/departemen/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "departemen";
		$this->data['url'] = "departemen/save";
		$this->data['flag'] = "add";
		
		$this->data['id_departemen'] ="";
        $this->data['nama_departemen'] ="";
		
        return view('admin/departemen/form', $this->data);
    }
	
	function save() {
		$nama_dept = strtoupper(trim($this->input->post('nama_dept')));

		$data['nama_dept'] = $nama_dept;

		$insert = $this->curl->simple_post(rest_api().'/master_departemen',$data, array(CURLOPT_BUFFERSIZE => 10));

		if (!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('departemen');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('departemen');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "departemen";
		$this->data['url'] = "departemen/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM departemen WHERE id_dept = '$id'";
		$row = $this->db->query($sql)->row();
			
		$this->data['id_departemen'] = $id;		
		$this->data['nama_departemen'] = $row->nama_dept;
		
		return view('admin/departemen/form', $this->data);
	}
	
	function update() {
		$id_dept = strtoupper(trim($this->input->post('id_dept')));
		$nama_dept = strtoupper(trim($this->input->post('nama_dept')));

		$data['id_dept'] = $id_dept;
		$data['nama_dept'] = $nama_dept;

		$update = $this->curl->simple_put(rest_api().'/master_departemen',$data,array(CURLOPT_BUFFERSIZE => 10));
		
		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('departemen');	
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('departemen');	
		}
	}
	
	function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_departemen', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));
		
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}