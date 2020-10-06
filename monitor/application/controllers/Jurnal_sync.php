<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_sync extends CI_Controller {
	var $data = array();

	public function __construct() {
        parent::__construct();
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
	
	public function index()
	{	
		return view('admin/jurnal_sync', $this->data);
	}
	
}
