<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Plan_runsheet extends REST_Controller {
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
			$q = "select *, cashtransit.id as id_ct, IFNULL((SELECT COUNT(DISTINCT client.sektor) FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.id_cashtransit=cashtransit.id AND client.sektor NOT IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit=cashtransit.id) GROUP BY cashtransit_detail.id_cashtransit), 0) as count FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) ORDER BY cashtransit.id DESC";
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
	
	function get_data_post() {
		$id = $this->post('id'); 
		$base_url = $this->post('base_url'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$qry = "select SQL_CALC_FOUND_ROWS *, cashtransit.id as id_ct, IFNULL((SELECT COUNT(DISTINCT client.sektor) FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.id_cashtransit=cashtransit.id AND client.sektor IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit=cashtransit.id) GROUP BY cashtransit_detail.id_cashtransit), 0) as count FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) ORDER BY cashtransit.id DESC limit $offset,$rows";
		
		$query = $this->db->query($qry)->result();
		
		$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
		$result["total"] = $rows['found_rows'];
		
		// echo "<pre>";
		// print_r($query);
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			// // array_push($items, $row);
			$items[$i]['id_ct'] = $row->id_ct;
			$items[$i]['date'] = $row->date;
			$items[$i]['branch'] = $row->branch;
			$items[$i]['count'] = $row->count;
			$items[$i]['export'] = "<a href='".$base_url."excel/print_xls/".$row->id_ct."'>Print</a>";
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	function get_data2_post() {
		$id = $this->post('id'); 
		$base_url = $this->post('base_url'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		
		
		$qry = "SELECT  runsheet_security.id, 
		runsheet_security.id_cashtransit, 
        runsheet_security.run_number,
        vehicle.type,
        vehicle.police_number,
        vehicle.km_status,
        runsheet_security.security_1,
        runsheet_security.security_2
		FROM runsheet_security LEFT JOIN vehicle ON(runsheet_security.police_number=vehicle.police_number) WHERE runsheet_security.id_cashtransit='".$id."' limit $offset,$rows";
		
		$query = $this->db->query($qry)->result();
		
		$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
		$result["total"] = $rows['found_rows'];
		
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			$items[$i]['id'] = $row->id;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['run_number'] = '<a href="'.$base_url.'runsheet/detail_runsheet/'.$row->id_cashtransit.'/'.$row->run_number.'#&tab-drs" class="button blue" iconCls="icon-search">View Detail</a> '.$row->run_number;
			$items[$i]['type'] = $row->type;
			$items[$i]['police_number'] = $row->police_number;
			$items[$i]['km_status'] = $row->km_status;
			$items[$i]['security_1'] = $row->security_1;
			$items[$i]['security_2'] = $row->security_2;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	function get_data_runsheet_post() {
		$id = $this->post('id'); 
		$base_url = $this->post('base_url'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		
		
		$qry = "SELECT  runsheet_security.id, 
		runsheet_security.id_cashtransit, 
        runsheet_security.run_number,
        vehicle.type,
        vehicle.police_number,
        vehicle.km_status,
        runsheet_security.security_1,
        runsheet_security.security_2
		FROM runsheet_security LEFT JOIN vehicle ON(runsheet_security.police_number=vehicle.police_number) WHERE runsheet_security.id_cashtransit='".$id."' limit $offset,$rows";
		
		$query = $this->db->query($qry)->result();
		
		$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
		$result["total"] = $rows['found_rows'];
		
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			$items[$i]['id'] = $row->id;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['run_number'] = '<a href="'.$base_url.'runsheet/detail_runsheet/'.$row->id_cashtransit.'/'.$row->run_number.'#&tab-drs" class="button blue" iconCls="icon-search">View Detail</a> '.$row->run_number;
			$items[$i]['type'] = $row->type;
			$items[$i]['police_number'] = $row->police_number;
			$items[$i]['km_status'] = $row->km_status;
			$items[$i]['security_1'] = $row->security_1;
			$items[$i]['security_2'] = $row->security_2;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	function index_post() {
		
	}
	
	function index_put() {
		
	}
	
	function index_delete() {
		
	}
}