<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Master_seal extends REST_Controller {
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
			$q = "SELECT * FROM master_seal WHERE status='available'";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "SELECT * FROM master_seal WHERE id='$id' AND status='available'";
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
		$kode = $this->get('kode'); 
		$q = "SELECT * FROM master_seal WHERE kode='$kode'";
		$user = $this->db->query($q)->result_array();
		
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
		list($jenis, $dari, $hingga) = explode("-", $value);
		
		if($jenis=="big") {
			for($i = $dari; $i<=$hingga; $i++) {
				$kode = "A".sprintf('%05d', $i);
				$result = $this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM master_seal WHERE UPPER(kode) = BINARY(kode) AND kode='$kode'")->row();
				$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
				$rows = $this->db->query($sql)->row_array();
				$total_rows = $rows['found_rows'];
				if ( $total_rows == 0 ) { 
					$data = array(
						"kode" 	 => $kode,
						"jenis"  => $jenis,
						"status" => "available"
					);
					$insert = $this->db->insert('master_seal', $data);
					array_push($array, $data);
				} else {
					$insert = false;
				}
			}
		} else if($jenis=="small") {
			for($i = $dari; $i<=$hingga; $i++) {
				
				$kode = "a".sprintf('%05d', $i);
				$result = $this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM master_seal WHERE LOWER(kode) = BINARY(kode) AND kode='$kode'")->row();
				$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
				$rows = $this->db->query($sql)->row_array();
				$total_rows = $rows['found_rows'];
				if ( $total_rows == 0 ) { 
					$data = array(
						"kode" 	 => $kode,
						"jenis"  => $jenis,
						"status" => "available"
					);
					$insert = $this->db->insert('master_seal', $data);
					array_push($array, $data);
				} else {
					$insert = false;
				}
			}
		} else if($jenis=="sample") { 
			for($i = $dari; $i<=$hingga; $i++) {
				
				$kode = "P0002000000".sprintf('%02d', $i);
				$this->db->where('kode',$kode);
				$q = $this->db->get('master_seal');
				if ( $q->num_rows() == 0 ) { 
					$data = array(
						"kode" 	 => $kode,
						"jenis"  => $jenis,
						"status" => "available"
					);
					$insert = $this->db->insert('master_seal', $data);
					array_push($array, $data);
				} else {
					$insert = false;
				}
			}
		}
		
		// print_r($this->db->last_query());   
		
        if ($insert) { 
            $this->response($array, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_put() {
		// $id = $this->put('id_kondisi');
		
        // $data = array(
					// 'id_kondisi'          			=> $this->put('id_kondisi'),
					// 'nama_kondisi'          		=> $this->put('nama_kondisi'),
                    // 'waktu_respon'          			=> $this->put('waktu_respon')
				// );
        // $this->db->where('id_kondisi', $id);
        // $update = $this->db->update('kondisi', $data);
        // if ($update) {
            // $this->response($data, REST_Controller::HTTP_OK);
        // } else {
            // $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        // }
	}
	
	function index_delete() {
		$id = $this->delete('id');
		
        $this->db->where('id', $id);
        $delete = $this->db->delete('master_seal');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
}