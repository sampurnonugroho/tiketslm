<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bag extends CI_Controller {
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
	
	public function index() {
		$this->data['active_menu'] = "bag";

		$this->data['data_bag'] = json_decode($this->curl->simple_get(rest_api().'/master_bag'));
        return view('admin/bag/index', $this->data);
	}
	
	public function save() {
		$value = $this->uri->segment(3);
		$data = array(
			'value'       =>  $value
		);
		$this->curl->simple_post(rest_api().'/master_bag',$data, array(CURLOPT_BUFFERSIZE => 10)); 
	}
	
	public function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_bag', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10)); 
		
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}