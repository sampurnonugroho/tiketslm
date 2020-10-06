<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'ssp.class.php';

class Seal extends CI_Controller {
    var $data = array();
	
	public function __construct() {
        parent::__construct();
		$this->load->model("ticket_model");
        $this->load->library('form_validation');
		$this->load->library('curl');

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));
			$level = trim($this->session->userdata('level'));

			$this->data['level'] = $level;
		} else {
            redirect('');
        }
	}
	
	public function index() {
		$this->data['active_menu'] = "seal";

        $this->data['data_seal'] = json_decode($this->curl->simple_get(rest_api().'/master_seal'));
        return view('admin/seal/index', $this->data);
	}
	
	public function server_processing() {
		$param['table'] = 'master_seal'; //nama tabel dari database
		$param['column_order'] = array('kode', 'jenis', 'status'); //field yang ada di table user
		$param['column_search'] = array('kode','jenis','status'); //field yang diizin untuk pencarian 
		$param['order'] = array('id' => 'asc');
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		echo $this->curl->simple_get(rest_api().'/select/datatables', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
	
	public function save() {
		$value = $this->uri->segment(3);
		$data = array(
			'value'       =>  $value
		);
		$insert = $this->curl->simple_post(rest_api().'/master_seal',$data, array(CURLOPT_BUFFERSIZE => 10)); 
	}
	
	public function restore() {
		
	}
	
	public function reject() {
		
	}
	
	public function action_seal() {
		$this->data;
		
		return view('admin/seal/action_seal', $this->data);
	}
	
	public function action_seal_fix() {
		$this->data;
		
		return view('admin/seal/action_seal_fix', $this->data);
	}
	
	public function check_seal() {
		$kode = $this->input->post('value');
		
		$table = "";
		if (strpos($kode, 'BJK') !== false) {
			// echo "T-BAG";
			$table = "master_tbag";
			$info = "T-BAG";
			$jenis = "tbag";
		} else if (strpos($kode, '.') !== false) {
			// echo "BAG";
			$table = "master_bag";
			$info = "BAG";
			$jenis = "bag";
		} else if (strpos($kode, 'A') !== false) {
			// echo "BIG SEAL";
			$table = "master_seal";
			$info = "SEAL";
			$jenis = "big";
		} else if (strpos($kode, 'a') !== false) {
			// echo "SMALL SEAL";
			$table = "master_seal";
			$info = "SEAL";
			$jenis = "small";
		} else if (strpos($kode, '#') !== false) {
			// echo "CASSETTE";
			$table = "master_ctr";
			$info = "CASSETTE";
			$jenis = "ctr";
		}
		
		// echo $table;
		$res['table'] = $table;
		$res['jenis'] = $jenis;
		$res['info'] = $info;
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM $table WHERE kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)));
		if($count->cnt==0) {
			// echo -1;
			$res['result'] = -1;
		} else {
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM $table WHERE kode='$kode' AND status!='used'"), array(CURLOPT_BUFFERSIZE => 10)));
			
			if($count->cnt==0) {
				$res['result'] = 0;
			} else {
				$res['result'] = 1;
			}
		}
		
		echo json_encode($res);
	}
	
	public function update_seal() {
		$kode = $this->input->post('kode');
		$jenis = $this->input->post('jenis');
		$table = $this->input->post('table');
		$info = $this->input->post('info');
		
		$res['table'] = $table;
		$res['jenis'] = $jenis;
		$res['info'] = $info;
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt, kode, jenis FROM $table WHERE kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		// print_r($count);
		
		if($count->cnt==0) {
			$res['result'] = -1;
			$sql = "UPDATE $table SET status='available' WHERE kode='$kode'";
			json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
			$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM $table WHERE kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)), true);
			// $res['data'] = $row;
			
			$arr = array_merge($res, $row);
		} else {
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt, kode, jenis FROM $table WHERE kode='$kode' AND status!='used'"), array(CURLOPT_BUFFERSIZE => 10)));
			
			if($count->cnt==0) {
				$res['result'] = 0;
				$sql = "UPDATE $table SET status='available' WHERE kode='$kode'";
				json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
				$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM $table WHERE kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)), true);
				// $res['data'] = $row;
				
				$arr = array_merge($res, $row);
			} else {
				$res['result'] = 1;
				$sql = "UPDATE $table SET status='available' WHERE kode='$kode'";
				json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
				$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM $table WHERE kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)), true);
				// $res['data'] = $row;
				
				$arr = array_merge($res, $row);
			}
		}
		
		// print_r($res);
		echo json_encode($arr);
	}
	
	// [item_kode] => a57005
    // [item_info] => SEAL
    // [item_table] => master_seal
    // [item_jenis] => small
    // [item_status] => available
	
	public function update_all_seal() {
		$data = $this->input->post('data');
		$status = $this->input->post('status');
		
		$data = json_decode($data, true);
		$array = array();
		foreach($data as $r) {
			$sql = "UPDATE ".$r['item_table']." SET `status`='$status' WHERE kode='".$r['item_kode']."'";
			json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
			
			$res['table'] = $r['item_table'];
			$res['jenis'] = $r['item_jenis'];
			$res['info'] = $r['item_info'];
			$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM ".$r['item_table']." WHERE kode='".$r['item_kode']."'"), array(CURLOPT_BUFFERSIZE => 10)), true);
			
			$arr[] = array_merge($res, $row);
		}
		
		// print_r($arr);
		// echo json_encode($row);
		
		
		// $query = "SELECT * FROM master_seal WHERE kode IN (".implode(', ', $where2).")";
		// $res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo json_encode($res);
		
		// $query = implode('; ', $sql);
		// json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		echo json_encode($arr);
	}
}