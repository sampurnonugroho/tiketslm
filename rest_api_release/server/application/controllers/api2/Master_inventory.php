<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Master_inventory extends REST_Controller {
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
        // if ($id == '') {
			// $q = "SELECT * FROM inventory WHERE 1";
            // $user = $this->db->query($q)->result_array();
        // } else {
			// $q = "SELECT * FROM inventory WHERE id='$id'";
            // $user = $this->db->query($q)->result_array();
        // }
		
		$q = "SELECT name, qty, used, unit, type FROM view_inv_seal";
		$a1 = $this->db->query($q)->result_array();
		
		$q = "SELECT name, qty, used, unit, type FROM view_inv_bag";
		$a2 = $this->db->query($q)->result_array();
		
		$q = "SELECT name, qty, used, unit, type FROM view_inv_tbag";
		$a3 = $this->db->query($q)->result_array();
		
		$data = array_merge($a1,$a2,$a3);
		
		if (!empty($data)) {
			 $this->response($data, REST_Controller::HTTP_OK);
		} else {
			$this->set_response([
				'status' => FALSE,
				'message' => 'Data could not be found'
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	
	function index_post() {
        $data = array(
                    'name'          		=> $this->post('name'),
                    'qty'          			=> $this->post('qty'),
                    'unit'          		=> $this->post('unit'),
                    'type'          		=> $this->post('type')
				);
        $insert = $this->db->insert('inventory', $data);
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
					'name'          		=> $this->put('name'),
                    'qty'          			=> $this->put('qty'),
                    'unit'          		=> $this->put('unit'),
                    'type'          		=> $this->put('type')
				);
        $this->db->where('id', $id);
        $update = $this->db->update('inventory', $data);
        if ($update) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function index_delete() {
		$id = $this->delete('id');
		
        $this->db->where('id', $id);
        $delete = $this->db->delete('inventory');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
}