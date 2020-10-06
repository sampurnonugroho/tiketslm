<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Master_user extends REST_Controller {
	
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function index_get() {
        $id = $this->get('id'); 
        if ($id == '') {
			$q = "SELECT A.username, A.level, A.id_user, B.nik, B.id_karyawan, B.nama, A.password, C.id_dept, D.nama_dept 
            FROM user A LEFT JOIN karyawan B ON B.nik = A.username 
            LEFT JOIN bagian_departemen C ON C.id_bagian_dept = B.id_bagian_dept 
            LEFT JOIN departemen D ON D.id_dept = C.id_dept";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "SELECT A.username, A.level, A.id_user, B.nik, B.nama, A.password, C.id_dept, D.nama_dept 
            FROM user A LEFT JOIN karyawan B ON B.nik = A.username 
            LEFT JOIN bagian_departemen C ON C.id_bagian_dept = B.id_bagian_dept 
            LEFT JOIN departemen D ON D.id_dept = C.id_dept WHERE A.id_user='$id'";
            $user = $this->db->query($q)->result_array();
        }
		
		if (!empty($user)) {
			 $this->response($user, REST_Controller::HTTP_OK);
		} else {
			$this->set_response([
				'status' => FALSE,
				'message' => 'User could not be found'
			], REST_Controller::HTTP_NOT_FOUND);
		}
    }
	
	function index_post() {
		$id_user = $this->model_app->getkodeuser();
		
        $data = array(
                    'id_user'           => $id_user,
                    'username'          => $this->post('username'),
                    'password'          => $this->post('password'),
                    'level'    			=> $this->post('level')
				);
        $insert = $this->db->insert('user', $data);
        if ($insert) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_put() {
        $id_user = $this->put('id_user');
		
        $data = array(
					'id_user'           => $this->put('id_user'),
					'level'    			=> $this->put('level')
				);
        $this->db->where('id_user', $id_user);
        $update = $this->db->update('user', $data);
        if ($update) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_delete() {
        $id_user = $this->delete('id');
		
        $this->db->where('id_user', $id_user);
        $delete = $this->db->delete('user');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
}