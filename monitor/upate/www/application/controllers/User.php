<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
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
        $this->data['active_menu'] = "user";

        $this->data['data_user'] = json_decode($this->curl->simple_get(rest_api().'/master_user'));
        return view('admin/user/index2', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "user";
		$this->data['url'] = "user/save";
		$this->data['flag'] = "add";
		
		$this->data['dd_karyawan'] = $this->model_app->dropdown_karyawan_user();
		$this->data['id_karyawan'] = "";

		$this->data['dd_level'] = $this->model_app->credential();
		$this->data['id_level'] = "";

		$this->data['password'] = "";
		$this->data['id_user'] = "";
		
		
        return view('admin/user/form', $this->data);
    }
	
	function save() {
		$id_karyawan = strtoupper(trim($this->input->post('id_karyawan')));
		$password = strtoupper(trim($this->input->post('password')));
		$id_level = strtoupper(trim($this->input->post('id_level')));
		
		$data['username'] = $id_karyawan;
		$data['password'] = md5($password);
		$data['level'] = $id_level;
		

		$insert = $this->curl->simple_post(rest_api().'/master_user',$data,array(CURLOPT_BUFFERSIZE => 10));
		if (!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('user');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('user');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "user";
		$this->data['url'] = "user/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM user WHERE id_user = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
			
		$this->data['dd_karyawan'] = $this->model_app->dropdown_karyawan();
		$this->data['id_karyawan'] = "";

		$this->data['dd_level'] = $this->model_app->credential();
		$this->data['id_level'] = $row->level;

		$this->data['password'] = "";
		$this->data['id_user'] = $row->id_user;
		
		
		return view('admin/user/form', $this->data);
	}
	
	function update() {
		$id_user = strtoupper(trim($this->input->post('id_user')));


		$id_level = strtoupper(trim($this->input->post('id_level')));
		$data['id_user'] = $id_user;
		$data['level'] = $id_level;
	 

		$update = $this->curl->simple_put(rest_api().'/master_user',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('user');	
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('user');	
		}
	}
	
	function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_user', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));

		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}