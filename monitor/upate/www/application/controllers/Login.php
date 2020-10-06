<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("login_model");
        $this->load->library('form_validation');
		$this->load->library('curl');
    }

    public function index()
	{	
		return view('admin/login');
	}

    public function proses() {
		$post = $this->input->post();
		$data['username'] = trim($post['username']);
		$data['password'] = md5(trim($post['password']));
		
		$proses = $this->curl->simple_post(rest_api().'/user_login/proses',$data,array(CURLOPT_BUFFERSIZE => 10));

		if($proses) {
			$sess = json_decode($proses)->session;
			$session['id_user'] = $sess->id_user;
			$session['nama'] = $sess->nama;
			$session['level'] = $sess->level;
			$session['id_jabatan'] = $sess->id_jabatan;
			$session['id_dept'] = $sess->id_dept;
			$session['nama_dept'] = $sess->nama_dept;
			
			$this->session->set_userdata($session);
			echo json_encode(array("valid"=>json_decode($proses)->valid, "redirect"=>base_url().json_decode($proses)->redirect));
		} else {
			echo json_encode(array("valid"=>false));
		}
		
    }
}