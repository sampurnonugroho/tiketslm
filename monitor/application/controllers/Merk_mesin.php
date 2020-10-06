<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Merk_mesin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("branch_model");
        $this->load->model("ticket_model");
        $this->load->library('form_validation');
		$this->load->library('curl');

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
       $this->data['active_menu'] = "merk_mesin";

		$sql = "SELECT * FROM merk_mesin ORDER BY merk ASC";
		$this->data['datamerk'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
        return view('admin/merk_mesin/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "merk_mesin";
		
		$this->data['url'] = "merk_mesin/save";
		
        $this->data['id'] = "";		
		$this->data['merk'] = "";
		
		$this->data['flag'] = "add";
		
        return view('admin/merk_mesin/form', $this->data);
    }
	
	function save() {
		$merk = strtoupper(trim($this->input->post('merk')));
		
		$sql = "INSERT INTO `merk_mesin`(`merk`) VALUES ('$merk')";
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));

		if(!$res) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('merk_mesin');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('merk_mesin');	
		}
	}

    public function edit($id = null)
    {
        $this->data['active_menu'] = "merk_mesin";
		$this->data['url'] = "merk_mesin/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM merk_mesin WHERE id = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['id'] = $row->id;		
		$this->data['merk'] = $row->merk;
		
		return view('admin/merk_mesin/form', $this->data);
    }
	
	function update() {
		$id = strtoupper(trim($this->input->post('id')));
		$merk = strtoupper(trim($this->input->post('merk')));
		
		$sql = "UPDATE `merk_mesin` SET `merk`='$merk' WHERE `id`='$id'";
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));

		if(!$res) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('merk_mesin');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('merk_mesin');	
		}
	}

    function delete() {
		$id = $_POST['id'];

		$sql = "DELETE FROM `merk_mesin` WHERE `id`='$id'";
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));

		if (!$res) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}