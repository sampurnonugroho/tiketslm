<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Operational extends CI_Controller {
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
		$this->data['active_menu'] = "operational";
		
		$query = "
			SELECT
				*, 
				cashtransit.id as id_ct,
				IFNULL((
					SELECT 
						COUNT(DISTINCT c.id)
							FROM cashtransit as c
								WHERE 
									c.date = cashtransit.date AND 
									c.id NOT IN (SELECT id_cashtransit FROM runsheet_operational)
				), 0) AS count
				FROM
					cashtransit
					LEFT JOIN
						master_branch
							ON (cashtransit.branch=master_branch.id) GROUP BY cashtransit.date ORDER BY cashtransit.id DESC 
		";
		$this->data['data_operational'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
        return view('admin/operational/index', $this->data);
	}
	
	function json() {
		$query = "
			SELECT
				*, 
				cashtransit.id as id_ct,
				master_branch.name as branch,
				IFNULL((
					SELECT 
						COUNT(DISTINCT c.id)
							FROM cashtransit as c
								WHERE 
									c.action_date = cashtransit.action_date AND 
									c.id NOT IN (SELECT id_cashtransit FROM runsheet_operational)
				), 0) AS count
				FROM
					cashtransit
					LEFT JOIN
						master_branch
							ON (cashtransit.branch=master_branch.id)
		";
		
		$param['query'] = $query; //nama tabel dari database
		$param['column_order'] = array('id_ct'); //field yang ada di table user
		$param['column_search'] = array('action_date'); //field yang diizin untuk pencarian 
		$param['order'] = array(array('id_ct' => 'DESC'));
		$param['group'] = array('action_date');
		$param['where'] = array(array('cashtransit.action_date[!]' => ''));
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		echo $this->curl->simple_get(rest_api().'/datatables', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
	
	public function edit() {
		$this->data['active_menu'] = "operational";
		$this->data['url'] = "operational/save";
		$this->data['flag'] = "edit";
		
		$date = $this->uri->segment(3);
		
		// $query = "SELECT name FROM master_branch WHERE id IN (SELECT branch FROM cashtransit WHERE id='$id')";
		// $branch = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->name;
		
		
		$this->data['date'] = $date;
		// $this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/operational/form', $this->data);
	}
	
	public function get_data() {
		$date = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		
		$query = "
				SELECT 
					*,
					runsheet_operational.id as ids,
					IF(runsheet_operational.custodian_1='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_operational.custodian_1)
					) AS custody_1,
					IF(runsheet_operational.custodian_2='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_operational.custodian_2)
					) AS custody_2
				FROM runsheet_operational 
				LEFT JOIN cashtransit ON(cashtransit.id=runsheet_operational.id_cashtransit) 
				WHERE cashtransit.action_date='".$date."' LIMIT $offset,$rows";
		
		// $result = $this->curl->simple_post(rest_api().'/run_operational/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$result["total"] = count($res);

		$items = array();
		$i = 0;
		foreach($res as $row){
			$items[$i]['id'] = $row->ids;
			$items[$i]['run_number'] = "(H-".$row->h_min.") (RUN NUMBER ".$row->run_number.")"; 
			$items[$i]['custodian_1'] = $row->custody_1;
			$items[$i]['custodian_2'] = $row->custody_2;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['date'] = $this->input->get('date');
		$this->data['index'] = $this->input->get('index');
		$this->data['row'] = json_decode($this->input->get('row'));
		
		if(isset($this->data['row']->isNewRecord)) {
			$this->data['flag'] = "ADD";
		} else {
			$this->data['flag'] = "EDIT";
		}
		
		$sql = "SELECT * FROM user LEFT JOIN karyawan ON(user.username=karyawan.nik) WHERE user.level='CUSTODI'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		$this->data['custodi'] = $row;
		
		return view('admin/operational/show_form', $this->data);
	}
	
	function suggest_data_client() {
		$search = $this->input->post('search');
		$date = $this->input->post('date');
		
		$query = "SELECT * FROM cashtransit WHERE action_date='$date' AND h_min!='' AND cashtransit.id NOT IN (SELECT id_cashtransit FROM runsheet_operational)";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));

		$list = array();
		$key = 0;
		$prev_id = "";
		foreach($result as $row) {
			$list[$key]['id'] = $row->id;
			$list[$key]['text'] = "(H-".$row->h_min.") (RUN NUMBER ".$row->run_number.")"; 
			
			$key++;
		}
		
		echo json_encode($list);
	}
	
	public function suggest_custodi() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		$sql = "SELECT * FROM user LEFT JOIN karyawan ON(user.username=karyawan.nik) WHERE user.level='CUSTODI' AND user.username NOT IN (SELECT custodian_1 FROM runsheet_operational WHERE id_cashtransit='$id_cashtransit') AND user.username NOT IN (SELECT custodian_2 FROM runsheet_operational WHERE id_cashtransit='$id_cashtransit')";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// print_r($result->result());
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->username;
				$list[$key]['text'] = $row->nama; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function suggest_custodi2() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		$prev_id = $this->input->post('prev_id');
		
		$sql = "SELECT * FROM user LEFT JOIN karyawan ON(user.username=karyawan.nik) WHERE user.level='CUSTODI' AND user.username!='$prev_id' AND user.username NOT IN (SELECT custodian_1 FROM runsheet_operational WHERE id_cashtransit='$id_cashtransit') AND user.username NOT IN (SELECT custodian_2 FROM runsheet_operational WHERE id_cashtransit='$id_cashtransit')";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// print_r($result->result());
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->username;
				$list[$key]['text'] = $row->nama; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function save_data() {
		$date				= strtoupper(trim($this->input->post('date')));
		$id_cashtransit		= strtoupper(trim($this->input->post('run_number')));
		$run_number			= "0";
		$custodian_1		= strtoupper(trim($this->input->post('custodian_1')));
		$custodian_2		= strtoupper(trim($this->input->post('custodian_2')));
		
		// echo "<pre>";
		// echo $run_number;
		// echo "\n";
		// echo $custodian_1;
		// print_r($_REQUEST);
		
		// $sql = "SELECT *, client_cit.sektor as sektor_1, client.sektor as sektor_2 
					// FROM cashtransit_detail 
						// LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
						// LEFT JOIN client_cit ON(cashtransit_detail.id_pengirim=client_cit.id)
					// WHERE 
						// cashtransit_detail.id_cashtransit='$id_cashtransit' AND 
						// (client.sektor NOT IN (SELECT run_number FROM runsheet_operational WHERE id_cashtransit='$id_cashtransit') OR
						// client_cit.sektor NOT IN (SELECT run_number FROM runsheet_operational WHERE id_cashtransit='$id_cashtransit'))";
						
		// echo $sql;
		// $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		// print_r($result);
		
		$data['id_cashtransit'] = $id_cashtransit;
		$data['run_number'] = $run_number;
		$data['custodian_1'] = $custodian_1;
		$data['custodian_2'] = $custodian_2;
		
		// print_r($data);
		
		$table = "runsheet_operational";
		$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		
		// $query = "
				// SELECT 
					// *,
					// master_zone.name AS zone_name,
					// runsheet_operational.id as ids,
					// IF(runsheet_operational.custodian_1='', '-none-',
						// (SELECT nama FROM karyawan WHERE nik=runsheet_operational.custodian_1)
					// ) AS custody_1,
					// IF(runsheet_operational.custodian_2='', '-none-',
						// (SELECT nama FROM karyawan WHERE nik=runsheet_operational.custodian_2)
					// ) AS custody_2
				// FROM runsheet_operational 
				// LEFT JOIN master_zone ON(master_zone.id=runsheet_operational.run_number) 
				// LEFt JOIN master_branch ON(master_branch.id=master_zone.id_branch)
				// WHERE id_cashtransit='".$id_cashtransit."'";
				
		$query = "
				SELECT 
					*,
					runsheet_operational.id as ids,
					IF(runsheet_operational.custodian_1='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_operational.custodian_1)
					) AS custody_1,
					IF(runsheet_operational.custodian_2='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_operational.custodian_2)
					) AS custody_2
				FROM runsheet_operational 
				LEFT JOIN cashtransit ON(cashtransit.id=runsheet_operational.id_cashtransit) 
				WHERE cashtransit.action_date='".$date."' AND cashtransit.id='".$id_cashtransit."'";
		
		// $result = $this->curl->simple_post(rest_api().'/run_operational/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));

		echo json_encode(array(
			'id' => $row->ids,
			'run_number' => "(H-".$row->h_min.") (RUN NUMBER ".$row->run_number.")",
			'custodian_1' => $row->custody_1,
			'custodian_2' => $row->custody_2
		));
	}
	
	function delete_data() {
		$data['id'] = $_POST['id'];

		$table = "runsheet_operational";
		$res = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
}