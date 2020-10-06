<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_interval extends CI_Controller {
    public function __construct() {
        parent::__construct();
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
		return view('admin/all_interval/index', $this->data);
    }
	
	public function json() {
		$query = "
			SELECT * FROM client
		";
		
		$param['query'] = $query; //nama tabel dari database
		$param['column_order'] = array('id'); //field yang ada di table user
		$param['column_search'] = array('wsid'); //field yang diizin untuk pencarian 
		$param['order'] = array(array('id' => 'ASC'));
		// $param['group'] = array('action_date');
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		// echo ;
		$data = $this->curl->simple_get(rest_api().'/datatables', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		echo $data;
	}
}