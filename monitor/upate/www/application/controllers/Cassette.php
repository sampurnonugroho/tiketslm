<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cassette extends CI_Controller {
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
		$this->data['active_menu'] = "cassette";

		$query = "SELECT 
					id, wsid, bank, alamat AS lokasi,
					IFNULL((
						SELECT COUNT(DISTINCT master_cassette.id) AS cassette
						FROM master_cassette WHERE wsid=client_ho.wsid AND kode LIKE '%CST%'
					),0) AS cassette,
					IFNULL((
						SELECT COUNT(DISTINCT master_cassette.id) AS divert
						FROM master_cassette WHERE wsid=client_ho.wsid AND kode LIKE '%DIV%'
					),0) AS divert
					FROM client_ho";
		$this->data['data_bag'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
        return view('admin/cassette/index', $this->data);
	}
	
	public function index2() {
		$this->data['active_menu'] = "cassette";

		$query = "SELECT * FROM client";
		$this->data['data_bag'] = json_decode($this->curl->simple_get(rest_api().'/master_cassette'));
        return view('admin/cassette/index', $this->data);
	}
	
	public function save() {
		$value = $this->uri->segment(3);
		$data = array(
			'value'       =>  $value
		);
		
		$result = $this->curl->simple_post(rest_api().'/master_cassette',$data, array(CURLOPT_BUFFERSIZE => 10)); 
		
		echo $result;
	}
	
	public function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_cassette', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10)); 
		
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}