<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zone extends CI_Controller {
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
		$this->data['active_menu'] = "zone";
		
        $this->data['data_branch'] = json_decode($this->curl->simple_get(rest_api().'/master_branch'));
		
        return view('admin/zone/index', $this->data);
	}
	
	public function add() {
        $this->data['active_menu'] = "zone";
		$this->data['url'] = "zone/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$row = json_decode($this->curl->simple_get(rest_api().'/master_branch?id='.$id))[0];
		
		$this->data['id'] = $id;
		$this->data['branch'] = $row->name;
		
        return view('admin/zone/form', $this->data);
	}
	
	public function get_data() {
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		
		$result = $this->curl->simple_post(rest_api().'/master_zone/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $result;
	}
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['id'] = $this->input->get('id');
		
		return view('admin/zone/show_form', $this->data);
	}
	
	function save_data() {
		// print_r($this->input->post());
		
		$id_branch			= strtoupper(trim($this->input->post('id_branch')));
		$kode				= strtoupper(trim($this->input->post('kode')));
		$name				= strtoupper(trim($this->input->post('name')));
		
		$data['id_branch'] = $id_branch;
		$data['kode'] = $kode;
		$data['name'] = $name;
		
		$insert = $this->curl->simple_post(rest_api().'/master_zone',$data,array(CURLOPT_BUFFERSIZE => 10));
		
		echo json_encode(array(
			'id_branch' => $id_branch,
			'kode' => $kode,
			'name' => $name
		));
	}
	
	function update_data() {
		$id = $this->input->get("id");
		
		$id_branch			= strtoupper(trim($this->input->post('id_branch')));
		$kode				= strtoupper(trim($this->input->post('kode')));
		$name				= strtoupper(trim($this->input->post('name')));
		
		$data['id'] = $id;
		$data['id_branch'] = $id_branch;
		$data['kode'] = $kode;
		$data['name'] = $name;
		
		$update = $this->curl->simple_put(rest_api().'/master_zone',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo json_encode(array(
			'id_branch' => $id_branch,
			'kode' => $kode,
			'name' => $name
		));
	}
	
	function delete_data() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_zone', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}