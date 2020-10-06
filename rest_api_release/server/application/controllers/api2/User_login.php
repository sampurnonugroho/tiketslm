<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class User_login extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("login_model");
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	public function proses_post() {
		$login = $this->login_model;
		$post = $this->input->post();
		$username = trim($post['username']);
        $password = trim($post['password']);
		
		// print_r($post);
		
		$akses = $login->proses_login($username, $password);
		// print_r($akses);
		
		if($akses->num_rows() == 1) { 
            foreach($akses->result_array() as $data) {
                $session['id_user'] = $data['username'];
                $session['nama'] = $data['nama'];
                $session['level'] = $data['level'];
                $session['id_jabatan'] = $data['id_jabatan'];
                $session['id_dept'] = $data['id_dept'];
            }
			
			echo json_encode(array("valid"=>true, "session"=>$session, "redirect"=>"dashboard"));
        } else {
            echo json_encode(array("valid"=>false));
        }
	}
}