<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Security extends CI_Controller {
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

			// $this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			// $this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			// $this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			// $this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
	}
	
	public function index() {
		$this->data['active_menu'] = "security";
		
		$query = "
			SELECT
				*, 
				cashtransit.id as id_ct,
				IFNULL((
					SELECT 
						COUNT(DISTINCT c.id)
							FROM cashtransit as c
								WHERE 
									c.date = cashtransit.action_date AND 
									c.id NOT IN (SELECT id_cashtransit FROM runsheet_security)
				), 0) AS count
				FROM
					cashtransit
					LEFT JOIN
						master_branch
							ON (cashtransit.branch=master_branch.id) GROUP BY cashtransit.action_date ORDER BY cashtransit.id DESC 
		";
		$this->data['data_security'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
        return view('admin/security/index', $this->data);
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
									c.id NOT IN (SELECT id_cashtransit FROM runsheet_security)
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
	
	public function add() {
        $this->data['active_menu'] = "security";
		$this->data['url'] = "security/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$branch = $this->db->query("SELECT branch FROM cashtransit WHERE id='$id'")->row()->branch;
		$branch = $this->db->query("SELECT name FROM master_branch WHERE id='$branch'")->row()->name;
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/security/form', $this->data);
	}
	
	public function edit() {
		$this->data['active_menu'] = "security";
		$this->data['url'] = "security/save";
		$this->data['flag'] = "edit";
		
		$date = $this->uri->segment(3);
		
		$this->data['date'] = $date;
		// $this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/security/form', $this->data);
	}
	
	function delete() {
		
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
					runsheet_security.id as ids,
					IF(runsheet_security.security_1='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_security.security_1)
					) AS security_1,
					IF(runsheet_security.security_2='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_security.security_2)
					) AS security_2
				FROM runsheet_security 
				LEFT JOIN vehicle ON (vehicle.police_number=runsheet_security.police_number)
				LEFT JOIN cashtransit ON(cashtransit.id=runsheet_security.id_cashtransit) 
				WHERE cashtransit.action_date='".$date."' LIMIT $offset,$rows";
		
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$result["total"] = count($res);

		$items = array();
		$i = 0;
		foreach($res as $row){
			$items[$i]['id'] = $row->ids;
			$items[$i]['run_number'] = "(H-".$row->h_min.") (RUN NUMBER ".$row->run_number.")"; 
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
	
	public function get_data2() {
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		// echo "<pre>";
		// print_r($id);
		// echo "</pre>";
		
		$query = $this->db->query("select count(*) as cnt FROM runsheet_security WHERE id_cashtransit='".$id."'");
        $row = $query->row_array();
		$result["total"] = $row['cnt'];
		
		$query = $this->db->query("select * from runsheet_security WHERE id_cashtransit='".$id."' limit $offset,$rows");
		
		$items = array();
		$i = 0;
		foreach($query->result() as $row){
			// array_push($items, $row);
			$items[$i]['id'] = $row->id;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['run_number'] = $row->run_number.' <a href="'.base_url().'runsheet/detail_runsheet/'.$row->id_cashtransit.'/'.$row->run_number.'#&tab-drs" class="button blue" iconCls="icon-search">View Detail</a>';
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
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['date'] = $this->input->get('date');
		
		// $query = "select * FROM vehicle";
        // $this->data['datavehicle'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// $query = "SELECT * FROM `karyawan` LEFT JOIN jabatan ON(karyawan.id_jabatan=jabatan.id_jabatan) WHERE jabatan.nama_jabatan='SECURITY'";
        // $this->data['datasecurity'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		if(isset($this->data['row']->isNewRecord)) {
			$this->data['flag'] = "ADD";
		} else {
			$this->data['flag'] = "EDIT";
		}
		
		return view('admin/security/show_form', $this->data);
	}
	
	public function suggest_data_client() {
		$search = $this->input->post('search');
		$date = $this->input->post('date');
		
		$query = "SELECT * FROM cashtransit WHERE action_date='$date' AND h_min!='' AND cashtransit.id NOT IN (SELECT id_cashtransit FROM runsheet_security)";
		
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
	
	public function police_number() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		$sql = "select * FROM vehicle WHERE police_number NOT IN (SELECT police_number FROM runsheet_security WHERE id_cashtransit='$id_cashtransit')";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// print_r($result->result());
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->police_number;
				$list[$key]['text'] = $row->police_number; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function suggest_security() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		$sql = "SELECT * FROM `karyawan` LEFT JOIN jabatan ON(karyawan.id_jabatan=jabatan.id_jabatan) WHERE karyawan.nama LIKE '%$search%' AND (jabatan.nama_jabatan='SECURITY DAN GUARD' OR jabatan.nama_jabatan='GUARD' OR jabatan.nama_jabatan='SECURITY') AND karyawan.nik NOT IN (SELECT security_1 FROM runsheet_security WHERE id_cashtransit='$id_cashtransit') 
		AND karyawan.nik NOT IN (SELECT security_2 FROM runsheet_security WHERE id_cashtransit='$id_cashtransit')";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// print_r($result->result());
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->nik;
				$list[$key]['text'] = $row->nama; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function suggest_security2() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		$prev_id = $this->input->post('prev_id');
		
		$sql = "SELECT * FROM `karyawan` LEFT JOIN jabatan ON(karyawan.id_jabatan=jabatan.id_jabatan) WHERE jabatan.nama_jabatan='SECURITY' AND karyawan.nik!='$prev_id' 
		AND karyawan.nik NOT IN (SELECT security_1 FROM runsheet_security WHERE id_cashtransit='$id_cashtransit') 
		AND karyawan.nik NOT IN (SELECT security_2 FROM runsheet_security WHERE id_cashtransit='$id_cashtransit')";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// print_r($result->result());
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->nik;
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
		$type				= strtoupper(trim($this->input->post('type')));
		$police_number		= strtoupper(trim($this->input->post('police_number')));
		$km_status			= strtoupper(trim($this->input->post('km_status')));
		$security_1			= strtoupper(trim($this->input->post('security_1')));
		$security_2			= strtoupper(trim($this->input->post('security_2')));
		
		$data['id_cashtransit'] = $id_cashtransit;
		$data['run_number'] = $run_number;
		// $data['type'] = $type;
		$data['police_number'] = $police_number;
		// $data['km_status'] = $km_status;
		$data['security_1'] = $security_1;
		$data['security_2'] = $security_2;
		
		$table = "runsheet_security";
		$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));

		$query = "
				SELECT 
					*,
					runsheet_security.id as ids,
					IF(runsheet_security.security_1='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_security.security_1)
					) AS security_1,
					IF(runsheet_security.security_2='', '-none-',
						(SELECT nama FROM karyawan WHERE nik=runsheet_security.security_2)
					) AS security_2
				FROM runsheet_security 
				LEFT JOIN vehicle ON (vehicle.police_number=runsheet_security.police_number)
				LEFT JOIN cashtransit ON(cashtransit.id=runsheet_security.id_cashtransit) 
				WHERE cashtransit.action_date='".$date."' AND cashtransit.id='".$id_cashtransit."'";
				
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));

		echo json_encode(array(
			'id' => $row->ids,
			'run_number' => "(H-".$row->h_min.") (RUN NUMBER ".$row->run_number.")",
			'type' => $row->type,
			'police_number' => $row->police_number,
			'km_status' => $row->km_status,
			'security_1' => $row->security_1,
			'security_2' => $row->security_2
		));
	}
	
	function update_data() {
		$id = $this->input->get("id");
		
		$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
		$run_number			= strtoupper(trim($this->input->post('run_number')));
		$type				= strtoupper(trim($this->input->post('type')));
		$police_number		= strtoupper(trim($this->input->post('police_number')));
		$km_status			= strtoupper(trim($this->input->post('km_status')));
		$security_1			= strtoupper(trim($this->input->post('security_1')));
		$security_2			= strtoupper(trim($this->input->post('security_2')));
		
		$data['id_cashtransit'] = $id_cashtransit;
		$data['run_number'] = $run_number;
		$data['type'] = $type;
		$data['police_number'] = $police_number;
		$data['km_status'] = $km_status;
		$data['security_1'] = $security_1;
		$data['security_2'] = $security_2;
		
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->update('runsheet_security', $data);

		$this->db->trans_complete();

		echo json_encode(array(
			'id_cashtransit' => $id_cashtransit,
			'run_number' => $run_number,
			'type' => $type,
			'police_number' => $police_number,
			'km_status' => $km_status,
			'security_1' => $security_1,
			'security_2' => $security_2
		));
	}
	
	function delete_data() {
		$data['id'] = $_POST['id'];

		$table = "runsheet_security";
		$res = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
}