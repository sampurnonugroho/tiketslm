<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Auth extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
        
        // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']); 
    }
	
	function login_post() {
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));
		$token = $this->input->post('token');
		
		$param = "
			WHERE (user.username='".$username."' OR karyawan.id_karyawan = '$username') AND user.password='".$password."'
		";
		
		$sql  = "
			SELECT SQL_CALC_FOUND_ROWS * FROM user
				LEFT JOIN karyawan 
					ON(user.username=karyawan.nik)
				LEFT JOIN jabatan 
					ON(karyawan.id_jabatan=jabatan.id_jabatan)
				LEFT JOIN bagian_departemen 
					ON(karyawan.id_bagian_dept=bagian_departemen.id_bagian_dept)
				LEFT JOIN departemen 
					ON(bagian_departemen.id_dept=departemen.id_dept)
				".$param."
		";
		
		$query = $this->db->query($sql)->row_array();
		$num_rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array()['found_rows'];
		
		if ($num_rows > 0) {
			$this->db->where('username', $username);
			$this->db->where('password', $password);
			$this->db->update('user', array('token' => $token));
			
			$user = [
				"id"=>$query['id_user'], 
				"username"=>$query['username'], 
				"name"=>$query['nama'], 
				"level"=>$query['level']
			];
			
			$result['data'] = $user;
		}
		
		echo json_encode($result);
	}
	
	function login_get() {
		$username = $this->input->get('username');
		$password = md5($this->input->get('password'));
		$token = $this->input->get('token');
		
		$param = "
			WHERE (user.username='".$username."' OR karyawan.id_karyawan = '$username') AND user.password='".$password."'
		";
		
		$sql  = "
			SELECT SQL_CALC_FOUND_ROWS * FROM user
				LEFT JOIN karyawan 
					ON(user.username=karyawan.nik)
				LEFT JOIN jabatan 
					ON(karyawan.id_jabatan=jabatan.id_jabatan)
				LEFT JOIN bagian_departemen 
					ON(karyawan.id_bagian_dept=bagian_departemen.id_bagian_dept)
				LEFT JOIN departemen 
					ON(bagian_departemen.id_dept=departemen.id_dept)
				".$param."
		";
		
		$query = $this->db->query($sql)->row_array();
		$num_rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array()['found_rows'];
		
		$result = array();
		if ($num_rows > 0) {
			// $this->db->where('username', $username);
			// $this->db->where('password', $password);
			// $this->db->update('user', array('token' => $token));
			
			$username = $query['username'];
			
			$sql = "UPDATE user SET token='' WHERE token='$token' AND last_updated < NOW()";
			$this->db->query($sql);
			$sql = "UPDATE user SET token='$token' WHERE username='$username' AND password='$password'";
			$this->db->query($sql);
			
			$user = array(
				"id"=>$query['id_user'], 
				"id_staff"=>$query['id_karyawan'], 
				"username"=>$query['username'], 
				"name"=>$query['nama'], 
				"level"=>$query['level']
			);
			
			$result['data'] = $user;
		}
		
		echo json_encode($result);
	}
	
	function logout_get() {
		$username = $this->input->get('username');
		
		$sql = "UPDATE user SET token='' WHERE username='$username'";
		$this->db->query($sql);
	}
	
	function update_location_get() {
		$username = $this->input->get('username');
		$latlng = $this->input->get('latlng');
		
		$sql = "UPDATE user SET latlng='' WHERE token=''";
		$this->db->query($sql);
		
		$sql = "UPDATE user SET latlng='$latlng' WHERE username='$username'";
		$this->db->query($sql);
	}
}