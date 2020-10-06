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
		
		$this->data['data_operational'] = json_decode($this->curl->simple_get(rest_api().'/Run_operational'));
		
        return view('admin/operational/index', $this->data);
	}
	
	public function edit() {
		$this->data['active_menu'] = "operational";
		$this->data['url'] = "operational/save";
		$this->data['flag'] = "edit";
		
		$id = $this->uri->segment(3);
		
		$query = "SELECT name FROM master_branch WHERE id IN (SELECT branch FROM cashtransit WHERE id='$id')";
		$branch = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->name;
		
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/operational/form', $this->data);
	}
	
	public function get_data() {
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		
		$query = "
				SELECT 
					*,
					master_zone.name AS zone_name,
					runsheet_operational.id as ids,
					IF(runsheet_operational.custodian_1='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_operational.custodian_1)
					) AS custody_1,
					IF(runsheet_operational.custodian_2='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_operational.custodian_2)
					) AS custody_2
				FROM runsheet_operational 
				LEFT JOIN master_zone ON(master_zone.id=runsheet_operational.run_number) 
				LEFt JOIN master_branch ON(master_branch.id=master_zone.id_branch)
				WHERE id_cashtransit='".$id."' LIMIT $offset,$rows";
		
		// $result = $this->curl->simple_post(rest_api().'/run_operational/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$result["total"] = count($res);

		$items = array();
		$i = 0;
		foreach($res as $row){
			$items[$i]['id'] = $row->ids;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['run_number'] = substr($row->zone_name, 0, 5)." ".$row->kode_zone;
			$items[$i]['custodian_1'] = $row->custody_1;
			$items[$i]['custodian_2'] = $row->custody_2;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['id'] = $this->input->get('id');
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
		$data['search'] = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		$query = "SELECT *, client_cit.sektor as sektor_1, client.sektor as sektor_2 FROM cashtransit_detail 
					LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
					LEFT JOIN client_cit ON(cashtransit_detail.id_pengirim=client_cit.id)
					LEFT JOIN master_zone ON(client_cit.sektor=master_zone.id OR client.sektor=master_zone.id) 
				WHERE 
					cashtransit_detail.id_cashtransit='$id_cashtransit' AND 
					(client.sektor NOT IN (SELECT run_number FROM runsheet_operational WHERE id_cashtransit='$id_cashtransit') OR
					client_cit.sektor NOT IN (SELECT run_number FROM runsheet_operational WHERE id_cashtransit='$id_cashtransit'))
					GROUP BY cashtransit_detail.id";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));

		$list = array();
		$key = 0;
		$prev_id = "";
		foreach($result as $row) {
			if(array_search($row->id, array_column($list, 'id')) !== false) {
				// echo "FOUND";
			} else {
				if($row->sektor_1!=null) {
					$list[$key]['id'] = $row->id;
					$list[$key]['text'] = "(".$row->kode_zone.") ".$row->name; 
				} else if($row->sektor_2!=null) {
					$list[$key]['id'] = $row->id;
					$list[$key]['text'] = "(".$row->kode_zone.") ".$row->name; 
				}
				
				$key++;
			}
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
		$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
		$run_number			= strtoupper(trim($this->input->post('run_number')));
		$custodian_1		= strtoupper(trim($this->input->post('custodian_1')));
		$custodian_2		= strtoupper(trim($this->input->post('custodian_2')));
		
		// echo "<pre>";
		// echo $run_number;
		// echo "\n";
		// echo $custodian_1;
		
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
		
		$table = "runsheet_operational";
		$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		$query = "
				SELECT 
					*,
					master_zone.name AS zone_name,
					runsheet_operational.id as ids,
					IF(runsheet_operational.custodian_1='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_operational.custodian_1)
					) AS custody_1,
					IF(runsheet_operational.custodian_2='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_operational.custodian_2)
					) AS custody_2
				FROM runsheet_operational 
				LEFT JOIN master_zone ON(master_zone.id=runsheet_operational.run_number) 
				LEFt JOIN master_branch ON(master_branch.id=master_zone.id_branch)
				WHERE id_cashtransit='".$id_cashtransit."'";
		
		// $result = $this->curl->simple_post(rest_api().'/run_operational/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));

		echo json_encode(array(
			'id' => $row->ids,
			'id_cashtransit' => $row->id_cashtransit,
			'run_number' => substr($row->zone_name, 0, 5)." ".$row->kode_zone,
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