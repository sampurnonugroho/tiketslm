<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Master_karyawan extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function index_get() {
		$nik = $this->get('nik'); 
        if ($nik == '') {
			$q = "SELECT A.nama, A.nik, A.id_karyawan, A.alamat, A.jk, C.nama_bagian_dept, B.nama_jabatan, D.nama_dept
                               FROM karyawan A LEFT JOIN jabatan B ON B.id_jabatan = A.id_jabatan
                                               LEFT JOIN bagian_departemen C ON C.id_bagian_dept = A.id_bagian_dept
                                               LEFT JOIN departemen D ON D.id_dept = C.id_dept
								ORDER BY A.id_karyawan ASC";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "SELECT *
                               FROM karyawan A LEFT JOIN jabatan B ON B.id_jabatan = A.id_jabatan
                                               LEFT JOIN bagian_departemen C ON C.id_bagian_dept = A.id_bagian_dept
                                               LEFT JOIN departemen D ON D.id_dept = C.id_dept 
								WHERE A.nik='$nik'";
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
		$nik = $this->model_app->getkodekaryawan();
        $data = array(
                    'nik'          		=> $nik,
                    'id_karyawan'       => $this->post('id_karyawan'),
                    'nama'          	=> $this->post('nama'),
                    'jk'          		=> $this->post('jk'),
                    'alamat'          	=> $this->post('alamat'),
                    'id_bagian_dept'    => $this->post('id_bagian_dept'),
                    'id_jabatan'        => $this->post('id_jabatan')
				);
				// print_r($data);
        $insert = $this->db->insert('karyawan', $data);
        if ($insert) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_put() {
		$nik = $this->put('nik');
		
        $data = array(
					'nik'          		=> $this->put('nik'),
					'id_karyawan'       => $this->put('id_karyawan'),
                    'nama'          	=> $this->put('nama'),
                    'jk'          		=> $this->put('jk'),
                    'alamat'          	=> $this->put('alamat'),
                    'id_bagian_dept'    => $this->put('id_bagian_dept'),
                    'id_jabatan'        => $this->put('id_jabatan')
				);
				
		// print_r($data);
        $this->db->where('nik', $nik);
        $update = $this->db->update('karyawan', $data);
        if ($update) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function index_delete() {
		$nik = $this->delete('id');
		
        $this->db->where('nik', $nik);
        $delete = $this->db->delete('karyawan');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
}