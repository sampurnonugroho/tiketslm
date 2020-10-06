<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends CI_Controller {
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
       $this->data['active_menu'] = "branch";

        $this->data['databranch'] = json_decode($this->curl->simple_get(rest_api().'/master_branch'));
        return view('admin/branch/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "branch";
		
		$this->data['url'] = "branch/save";
		
        $this->data['id'] = "";		
		$this->data['name'] = "";
		$this->data['kode'] = "";
		
		$this->data['flag'] = "add";
		
        return view('admin/branch/form', $this->data);
    }
	
	function save() {
		$kode = strtoupper(trim($this->input->post('kode')));
		$name = strtoupper(trim($this->input->post('name')));

		$data['kode'] = $kode;
		$data['name'] = $name;

		$insert = $this->curl->simple_post(rest_api().'/master_branch',$data, array(CURLOPT_BUFFERSIZE => 10)); 

		if(!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('branch');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('branch');	
		}
	}

    public function edit($id = null)
    {
        $this->data['active_menu'] = "branch";
		$this->data['url'] = "branch/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM master_branch WHERE id = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['id'] = $row->id;		
		$this->data['kode'] = $row->kode;
		$this->data['name'] = $row->name;
		
		return view('admin/branch/form', $this->data);
    }
	
	function update() {
		$id = strtoupper(trim($this->input->post('id')));
		$kode = strtoupper(trim($this->input->post('kode')));
		$name = strtoupper(trim($this->input->post('name')));

		$data['id'] = $id;
		$data['kode'] = $kode;
		$data['name'] = $name;

		$update = $this->curl->simple_put(rest_api().'/master_branch',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('branch');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('branch');	
		}
	}

    function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_branch', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));
		
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}