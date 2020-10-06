<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Master_subdepartemen extends REST_Controller {
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
			$q = "SELECT * FROM bagian_departemen A LEFT JOIN departemen B ON B.id_dept = A.id_dept";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "SELECT * FROM bagian_departemen A LEFT JOIN departemen B ON B.id_dept = A.id_dept WHERE id_bagian_dept='$id'";
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
        $data = array(
                    'nama_bagian_dept'          => $this->post('nama_bagian_dept'),
                    'id_dept'          			=> $this->post('id_dept')
				);
        $insert = $this->db->insert('bagian_departemen', $data);
        if ($insert) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_put() {
		$id = $this->put('id_bagian_dept');
		
        $data = array(
					'id_bagian_dept'          	=> $this->put('id_bagian_dept'),
					'nama_bagian_dept'          => $this->put('nama_bagian_dept'),
                    'id_dept'          			=> $this->put('id_dept')
				);
        $this->db->where('id_bagian_dept', $id);
        $update = $this->db->update('bagian_departemen', $data);
        if ($update) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function index_delete() {
		$id = $this->delete('id');
		
        $this->db->where('id_bagian_dept', $id);
        $delete = $this->db->delete('bagian_departemen');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
}