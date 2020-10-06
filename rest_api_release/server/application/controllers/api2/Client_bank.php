<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Client_bank extends REST_Controller {
	
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
        $id_branch = $this->get('branch'); 
        if ($id == '') {
			if($id_branch == '') {
				$q = "SELECT * FROM client WHERE 1";
				$user = $this->db->query($q)->result_array();
			} else {
				$q = "SELECT * FROM client WHERE cabang='$id_branch'";
				$user = $this->db->query($q)->result_array();
			}
        } else {
			$q = "SELECT * FROM client WHERE id='$id'";
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
	
	function index2_get() {
		$id_ct = $this->input->get('id_ct');
		$id_bank = $this->input->get('id_bank');
	
		$q = "SELECT *, cashtransit_detail.ctr as ttl_ctr FROM cashtransit_detail LEFT JOIN client ON (cashtransit_detail.id_bank=client.id) WHERE id_cashtransit='$id_ct' AND id_bank='$id_bank'";
		// $q = "SELECT * FROM cashtransit_detail LEFT JOIN (SELECT id, cabang, sektor, bank, type, vendor, type_mesin, lokasi, denom FROM client) as client ON (cashtransit_detail.id_bank=client.id) WHERE id_cashtransit='$id_ct' AND id_bank='$id_bank' ";
		$user = $this->db->query($q)->row();
		
		// echo ($q);   
				
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
                    'wsid'          		=> $this->post('wsid'),
                    'bank'          		=> $this->post('bank'),
                    'lokasi'    			=> $this->post('lokasi'),
                    'sektor'    			=> $this->post('sektor'),
                    'cabang'    			=> $this->post('cabang'),
                    'type'    				=> $this->post('type'),
                    'type_mesin'    		=> $this->post('type_mesin'),
                    'jam_operasional'    	=> $this->post('jam_operasional'),
                    'vendor'    			=> $this->post('vendor'),
                    'status'    			=> $this->post('status'),
                    'tgl_ho'    			=> $this->post('tgl_ho'),
                    'denom'    				=> $this->post('denom'),
                    'ctr'    				=> $this->post('ctr'),
                    'reject'    			=> $this->post('reject'),
                    'limit'    				=> $this->post('limit'),
                    'serial_number'    		=> $this->post('serial_number'),
                    'keterangan'    		=> $this->post('keterangan'),
                    'keterangan2'    		=> $this->post('keterangan2')
				);
		if(!empty($this->post('latlng'))) {
			$data['data_location'] = $this->post('latlng');
		}
        $insert = $this->db->insert('client', $data);
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
					'wsid'          		=> $this->put('wsid'),
                    'bank'          		=> $this->put('bank'),
                    'lokasi'    			=> $this->put('lokasi'),
                    'sektor'    			=> $this->put('sektor'),
                    'cabang'    			=> $this->put('cabang'),
                    'type'    				=> $this->put('type'),
                    'type_mesin'    		=> $this->put('type_mesin'),
                    'jam_operasional'    	=> $this->put('jam_operasional'),
                    'vendor'    			=> $this->put('vendor'),
                    'status'    			=> $this->put('status'),
                    'tgl_ho'    			=> $this->put('tgl_ho'),
                    'denom'    				=> $this->put('denom'),
                    'ctr'    				=> $this->put('ctr'),
                    'reject'    			=> $this->put('reject'),
                    'limit'    				=> $this->put('limit'),
                    'serial_number'    		=> $this->put('serial_number'),
                    'keterangan'    		=> $this->put('keterangan'),
                    'keterangan2'    		=> $this->put('keterangan2')
				);
		if(!empty($this->put('latlng'))) {
			$data['data_location'] = $this->put('latlng');
		}
        $this->db->where('id', $id);
        $update = $this->db->update('client', $data);
        if ($update) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_delete() {
        $id = $this->delete('id');
		
        $this->db->where('id', $id);
        $delete = $this->db->delete('client');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
}