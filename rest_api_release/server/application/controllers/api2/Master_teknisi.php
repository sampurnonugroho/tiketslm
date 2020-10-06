<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Master_teknisi extends REST_Controller {
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
			$q = "SELECT A.point, A.id_teknisi, B.nama, B.jk, C.nama_kategori, A.status, A.point FROM teknisi A 
                                LEFT JOIN karyawan B ON B.nik = A.nik
                                LEFT JOIN kategori C ON C.id_kategori = A.id_kategori";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "SELECT A.point, A.id_teknisi, B.nama, B.jk, C.nama_kategori, A.status, A.point FROM teknisi A 
                                LEFT JOIN karyawan B ON B.nik = A.nik
                                LEFT JOIN kategori C ON C.id_kategori = A.id_kategori WHERE A.id_teknisi='$id'";
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
		$id_teknisi = $this->model_app->getkodeteknisi();
        $data = array(
                    'id_teknisi'          => $id_teknisi,
                    'nik'          		  => $this->post('nik'),
                    'id_kategori'         => $this->post('id_kategori')
				);
        $insert = $this->db->insert('teknisi', $data);
        if ($insert) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_put() {
		$id = $this->put('id_teknisi');
		
        $data = array(
					'id_teknisi'          	     => $this->put('id_teknisi'),
                    'id_kategori'          		 => $this->put('id_kategori')
				);
        $this->db->where('id_teknisi', $id);
        $update = $this->db->update('teknisi', $data);
        if ($update) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function index_delete() {
		$id = $this->delete('id');
		
        $this->db->where('id_teknisi', $id);
        $delete = $this->db->delete('teknisi');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
}