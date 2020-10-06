<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class atmcr_bra extends CI_Controller {
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
        $this->data['active_menu'] = "atmcr_bra";

        $this->data['data_user'] = $this->model_app->datauser();
        return view('admin/atmcr_bra/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "atmcr_bra";
		$this->data['url'] = "atmcr_bra/save";
		$this->data['flag'] = "add";
		
		$this->data['dd_karyawan'] = $this->model_app->dropdown_karyawan_user();
		$this->data['id_karyawan'] = "";

		$this->data['dd_level'] = $this->model_app->credential();
		$this->data['id_level'] = "";

		$this->data['password'] = "";
		$this->data['id_user'] = "";
		
		
        return view('admin/atmcr_bra/form', $this->data);
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

		$this->db->insert('atmcr_bra', $data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('atmcr_bra');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('atmcr_bra');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "atmcr_bra";
		$this->data['url'] = "atmcr_bra/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM atmcr_bra WHERE id_user = '$id'";
		$row = $this->db->query($sql)->row();
		
			
		$this->data['dd_karyawan'] = $this->model_app->dropdown_karyawan();
		$this->data['id_karyawan'] = "";

		$this->data['dd_level'] = $this->model_app->credential();
		$this->data['id_level'] = $row->level;

		$this->data['password'] = "";
		$this->data['id_user'] = $row->id_user;
		
		
		return view('admin/atmcr_bra/form', $this->data);
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
}