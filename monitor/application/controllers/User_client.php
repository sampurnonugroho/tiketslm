<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_client extends CI_Controller {
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

			$this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			$this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			$this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			$this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        $this->data['active_menu'] = "user_client";

        $this->data['data_user_client'] = $this->model_app->datauser_client();
        return view('admin/user_client/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "user_client";
		$this->data['url'] = "user_client/save";
		$this->data['flag'] = "add";
		
		$this->data['dd_karyawan'] = $this->model_app->dropdown_user_client();
		$this->data['id_user_client'] = "";

		$this->data['password'] = "";
		$this->data['id'] = "";
		
		
        return view('admin/user_client/form', $this->data);
    }
	
	function save() {
		$id_user_client = strtoupper(trim($this->input->post('id_user_client')));
		$username = strtoupper(trim($this->input->post('username')));
		$password = strtoupper(trim($this->input->post('password')));


		$data['id_user_client'] = $id_user_client;
		$data['username'] = $username;
		$data['password'] = md5($password);
		

		$this->db->trans_start();

		$this->db->insert('user_client', $data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('user_client');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('user_client');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "user_client";
		$this->data['url'] = "user_client/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM user_client WHERE id_user_client = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
			
		$this->data['dd_karyawan'] = $this->model_app->dropdown_user_client2();
		$this->data['id_user_client'] = $row->id_user_client;

		$this->data['username'] = $row->username;
		$this->data['password'] = "";
		$this->data['id'] = $row->id;
		
		
		return view('admin/user_client/form', $this->data);
	}
	
	function update() {
		$id = trim($this->input->post('id'));
		// $id_user_client = strtoupper(trim($this->input->post('id_user_client')));
		$username = strtoupper(trim($this->input->post('username')));
		$password = strtoupper(trim($this->input->post('password')));


		// $data['id_user_client'] = $id_user_client;

		$data['username'] = $username;
		if($password!=="") {
			$data['password'] = md5($password);
		}

		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->update('user_client', $data);

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('user_client');	
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('user_client');	
		}
	}
	
	function delete() {
		$id = $_POST['id'];

		$this->db->trans_start();

		$this->db->where('id_user_client', $id);
		$this->db->delete('user_client');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}