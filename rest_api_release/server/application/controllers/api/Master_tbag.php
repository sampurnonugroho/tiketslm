<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Master_tbag extends REST_Controller {
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
			$q = "SELECT * FROM master_tbag WHERE status='available'";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "SELECT * FROM master_tbag WHERE id='$id' AND status='available'";
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
		$value = $this->post('value'); 
		$array = array();
		list($dari, $hingga) = explode("-", $value);
        for($i = $dari; $i<=$hingga; $i++) {
			$kode = "BJK".sprintf('%06d', $i);;
			$this->db->where('kode',$kode);
			$q = $this->db->get('master_tbag');
			if ( $q->num_rows() == 0 ) { 
				$data = array(
					"kode" 	 => $kode,
					"status" => "available"
				);
				$insert = $this->db->insert('master_tbag', $data);
				array_push($array, $data);
			} else {
				$insert = false;
			}
		}
		
        if ($insert) {
            $this->response($array, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function index_put() {
		
	}
	
	function index_delete() {
		$id = $this->delete('id');
		
        $this->db->where('id', $id);
        $delete = $this->db->delete('master_tbag');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
}