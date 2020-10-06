<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Client_cit extends REST_Controller {
	
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
			$q = "SELECT * FROM client_cit WHERE 1";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "SELECT * FROM client_cit WHERE id='$id'";
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
                    'nama_client'          	=> $this->post('nama_client'),
                    'alamat'          		=> $this->post('alamat'),
                    'kode_pos'    			=> $this->post('kode_pos'),
                    'wilayah'    			=> $this->post('wilayah'),
                    'pic'    				=> $this->post('pic'),
                    'telp'    				=> $this->post('telp'),
                    'ktp'    				=> $this->post('ktp')
				);
        $insert = $this->db->insert('client_cit', $data);
        if ($insert) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_put() {
        $id = $this->put('id');
		
        $data = array(
					'id'          			=> $this->put('id'),
					'nama_client'          	=> $this->put('nama_client'),
                    'alamat'          		=> $this->put('alamat'),
                    'kode_pos'    			=> $this->put('kode_pos'),
                    'wilayah'    			=> $this->put('wilayah'),
                    'pic'    				=> $this->put('pic'),
                    'telp'    				=> $this->put('telp'),
                    'ktp'    				=> $this->put('ktp'),
				);
        $this->db->where('id', $id);
        $update = $this->db->update('client_cit', $data);
        if ($update) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_delete() {
        $id = $this->delete('id');
		
        $this->db->where('id', $id);
        $delete = $this->db->delete('client_cit');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
}