<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Master_subkategori extends REST_Controller {
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
			$q = "SELECT * FROM sub_kategori A LEFT JOIN kategori B ON B.id_kategori = A.id_kategori";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "SELECT * FROM sub_kategori A LEFT JOIN kategori B ON B.id_kategori = A.id_kategori WHERE A.id_sub_kategori='$id'";
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
                    'nama_sub_kategori'          => $this->post('nama_sub_kategori'),
                    'id_kategori'          		 => $this->post('id_kategori')
				);
        $insert = $this->db->insert('sub_kategori', $data);
        if ($insert) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_put() {
		$id = $this->put('id_sub_kategori');
		
        $data = array(
					'id_sub_kategori'          	 => $this->put('id_sub_kategori'),
					'nama_sub_kategori'          => $this->put('nama_sub_kategori'),
                    'id_kategori'          		 => $this->put('id_kategori')
				);
        $this->db->where('id_sub_kategori', $id);
        $update = $this->db->update('sub_kategori', $data);
        if ($update) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function index_delete() {
		$id = $this->delete('id');
		
        $this->db->where('id_sub_kategori', $id);
        $delete = $this->db->delete('sub_kategori');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
}