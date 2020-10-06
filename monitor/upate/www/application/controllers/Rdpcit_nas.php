<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rdpcit_nas extends CI_Controller {
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
        $this->data['active_menu'] = "rdpcit_nas";

        $query = "SELECT *, A.id as id_ct, B.id as id_detail FROM cashtransit_detail B LEFT JOIN cashtransit A ON(A.id=B.id_cashtransit) LEFT JOIN master_branch C ON(A.branch=C.id) LEFT JOIN client_cit D ON (IF(B.id_pengirim=0, B.id_penerima, B.id_pengirim)=D.id) WHERE B.state='ro_cit' AND B.data_solve!='' AND B.id IN ( SELECT MAX(id) FROM cashtransit_detail WHERE state='ro_cit' AND data_solve!='' GROUP BY no_boc )";
        $this->data['data_cashtransit'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// print_r($this->data['data_cashreplenish']);
		
        return view('admin/rdpcit_nas/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "rdpcit_nas";
		$this->data['url'] = "rdpcit_nas/save";
		$this->data['flag'] = "add";
		
		$this->data['dd_karyawan'] = $this->model_app->dropdown_karyawan_user();
		$this->data['id_karyawan'] = "";

		$this->data['dd_level'] = $this->model_app->credential();
		$this->data['id_level'] = "";

		$this->data['password'] = "";
		$this->data['id_user'] = "";
		
		
        return view('admin/rdpcit_nas/form', $this->data);
    }
	
	function save() {
		$getkodeuser = $this->model_app->getkodeuser();
	
		$id_user = $getkodeuser;

		$id_karyawan = strtoupper(trim($this->input->post('id_karyawan')));
		$password = strtoupper(trim($this->input->post('password')));
		$id_level = strtoupper(trim($this->input->post('id_level')));


		$data['id_user'] = $id_user;
		$data['username'] = $id_karyawan;
		$data['password'] = md5($password);
		$data['level'] = $id_level;
		

		$this->db->trans_start();

		$this->db->insert('rdpcit_nas', $data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('rdpcit_nas');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('rdpcit_nas');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "rdpcit_nas";
		$this->data['url'] = "rdpcit_nas/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM rdpcit_nas WHERE id_user = '$id'";
		$row = $this->db->query($sql)->row();
		
			
		$this->data['dd_karyawan'] = $this->model_app->dropdown_karyawan();
		$this->data['id_karyawan'] = "";

		$this->data['dd_level'] = $this->model_app->credential();
		$this->data['id_level'] = $row->level;

		$this->data['password'] = "";
		$this->data['id_user'] = $row->id_user;
		
		
		return view('admin/rdpcit_nas/form', $this->data);
	}
	
	function update() {
		$id_user = strtoupper(trim($this->input->post('id_user')));


		$id_level = strtoupper(trim($this->input->post('id_level')));
		$data['level'] = $id_level;
	 

		$this->db->trans_start();

		$this->db->where('id_user', $id_user);
		$this->db->update('user', $data);

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('user');	
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('user');	
		}
	}
	
	function delete() {
		$id = $_POST['id'];

		$this->db->trans_start();

		$this->db->where('username', $id);
		$this->db->delete('user');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
	
	function detail_foto() {
		$id = $this->uri->segment(3);
		
		$sql = "SELECT * FROM cashtransit_detail WHERE id='$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['data_foto'] = $row;
		
		return view('admin/rdpcit_nas/detail_foto', $this->data);
	}
}