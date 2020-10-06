<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Runsheet extends CI_Controller {
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
		$this->data['active_menu'] = "runsheet";
		
        $this->data['data_runsheet'] = json_decode($this->curl->simple_get(rest_api().'/Plan_runsheet'));
		
		// echo "<pre>";
		// print_r($this->data['data_security']);
		// echo "</pre>";
		
        return view('admin/runsheet/index', $this->data);
	}
	
	public function add() {
        $this->data['active_menu'] = "runsheet";
		$this->data['url'] = "runsheet/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$branch = $this->db->query("SELECT branch FROM cashtransit WHERE id='$id'")->row()->branch;
		$branch = $this->db->query("SELECT name FROM master_branch WHERE id='$branch'")->row()->name;
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/runsheet/form', $this->data);
	}
	
	public function edit() {
		$this->data['active_menu'] = "runsheet";
		$this->data['url'] = "runsheet/save";
		$this->data['flag'] = "edit";
		
		$id = $this->uri->segment(3);
		
		$branch = $this->db->query("SELECT branch FROM cashtransit WHERE id='$id'")->row()->branch;
		$branch = $this->db->query("SELECT name FROM master_branch WHERE id='$branch'")->row()->name;
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/runsheet/form', $this->data);
	}
	
	function delete() {
		
	}
	
	public function get_data_runsheet_atm() {
		// header('Content-Type: application/json');	
		$search = $this->input->post('search');
		$id_ct = $this->uri->segment(3);
		$id_ga = $this->uri->segment(4);
		
		
		$sql = "SELECT *, IFNULL(client.sektor, client_cit.sektor) as sektor, cashtransit_detail.id as id, cashtransit_detail.ctr as ctr2, master_branch.name as branch_name, master_zone.name as zone_name, cashtransit_detail.data_solve as solve 
			FROM cashtransit_detail 
				LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
				LEFT JOIN client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
				LEFT JOIN master_zone ON(client.sektor=master_zone.id) 
				LEFT JOIN master_branch ON (master_zone.id_branch=master_branch.id) 
				LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
			WHERE 
				cashtransit_detail.id_cashtransit='$id_ct' AND 
				IFNULL(client.sektor, client_cit.sektor)='$id_ga' AND
				cashtransit_detail.state='ro_atm' AND
				runsheet_cashprocessing.id IS NOT NULL";
		
		// echo $sql;
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// print_r($result);
		
		// $list = $result;
		// if (count($result) > 0) {
			// $key=0;
			// echo json_encode($list);
		// } else {
			// echo json_encode($list);
		// }
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				if($row->state=="ro_atm") {
					if($row->type=="CRM") {
						list($seal_1, $denom_1, $value_1) = explode(";", $row->cart_1_seal);
						list($seal_2, $denom_2, $value_2) = explode(";", $row->cart_2_seal);
						list($seal_3, $denom_3, $value_3) = explode(";", $row->cart_3_seal);
						list($seal_4, $denom_4, $value_4) = explode(";", $row->cart_4_seal);
						
						$row->pcs_100000 =  ($denom_1=="100" ? $value_1 : 0) +
											($denom_2=="100" ? $value_2 : 0) +
											($denom_3=="100" ? $value_3 : 0) +
											($denom_4=="100" ? $value_4 : 0);
						
						$row->pcs_50000 =   ($denom_1=="50" ? $value_1 : 0) +
											($denom_2=="50" ? $value_2 : 0) +
											($denom_3=="50" ? $value_3 : 0) +
											($denom_4=="50" ? $value_4 : 0);
						
						$row->total 	=  ((intval($denom_1) * intval($value_1)) +
											(intval($denom_2) * intval($value_2)) +
											(intval($denom_3) * intval($value_3)) +
											(intval($denom_4) * intval($value_4))) * 1000;
					} else {
						$row->pcs_100000 = $row->pcs_100000;
						$row->pcs_50000 = $row->pcs_50000;
						$row->pcs_20000 = $row->pcs_20000;
						$row->pcs_10000 = $row->pcs_10000;
						$row->pcs_5000 = $row->pcs_5000;
						$row->pcs_1000 = $row->pcs_1000;
					}
					
					$list[$key]['id'] = $row->id;
					$list[$key]['wsid'] = $row->wsid;
					$list[$key]['branch_name'] = $row->branch_name;
					$list[$key]['sektor'] = $row->kode_zone;
					$list[$key]['bank'] = $row->bank;
					$list[$key]['type'] = $row->type;
					$list[$key]['vendor'] = $row->vendor;
					$list[$key]['type_mesin'] = $row->type_mesin;
					$list[$key]['lokasi'] = $row->lokasi;
					$list[$key]['pcs_100000'] = $row->pcs_100000;
					$list[$key]['pcs_50000'] = $row->pcs_50000;
					$list[$key]['pcs_20000'] = $row->pcs_20000;
					$list[$key]['pcs_10000'] = $row->pcs_10000;
					$list[$key]['pcs_5000'] = $row->pcs_5000;
					$list[$key]['pcs_2000'] = $row->pcs_2000;
					$list[$key]['pcs_1000'] = $row->pcs_1000;
					$list[$key]['pcs_coin'] = $row->pcs_coin;
					$list[$key]['ctr2'] = $row->ctr2;
					$list[$key]['total'] = number_format($row->total, 0, ',', ',');
					$list[$key]['solve'] = $row->solve;
				} else {
					$list[$key]['id'] = $row->id;
					$list[$key]['branch_name'] = $row->branch_name;
					$list[$key]['sektor'] = $row->sektor;
					$list[$key]['bank'] = $row->bank;
					$list[$key]['type'] = $row->type;
					$list[$key]['vendor'] = $row->vendor;
					$list[$key]['type_mesin'] = $row->type_mesin;
					$list[$key]['lokasi'] = $row->lokasi;
					$list[$key]['pcs_100000'] = $row->pcs_100000;
					$list[$key]['pcs_50000'] = $row->pcs_50000;
					$list[$key]['pcs_20000'] = $row->pcs_20000;
					$list[$key]['pcs_10000'] = $row->pcs_10000;
					$list[$key]['pcs_5000'] = $row->pcs_5000;
					$list[$key]['pcs_2000'] = $row->pcs_2000;
					$list[$key]['pcs_1000'] = $row->pcs_1000;
					$list[$key]['pcs_coin'] = $row->pcs_coin;
					$list[$key]['ctr2'] = $row->ctr2;
					$list[$key]['total'] = number_format($row->total, 0, ',', ',');
					$list[$key]['solve'] = $row->solve;
				}
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}

	public function get_data_runsheet_cit() {
		// header('Content-Type: application/json');	
		$search = $this->input->post('search');
		$id_ct = $this->uri->segment(3);
		$id_ga = $this->uri->segment(4);
		
		
		$sql = "SELECT 
					*, 
					runsheet_cashprocessing.*,
					cashtransit_detail.id as id, 
					cashtransit_detail.ctr as ctr2, 
					master_branch.name as branch_name, 
					master_zone.name as zone_name, 
					cashtransit_detail.data_solve as solve 
				FROM cashtransit_detail 
					LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
					LEFT JOIN client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
					LEFT JOIN master_zone ON(client_cit.sektor=master_zone.id) 
					LEFT JOIN master_branch ON (master_zone.id_branch=master_branch.id) 
					LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
				WHERE 
					cashtransit_detail.id_cashtransit='$id_ct' AND 
					cashtransit_detail.state='ro_cit' AND
					IFNULL(client.sektor, client_cit.sektor)='$id_ga' AND
					runsheet_cashprocessing.id IS NOT NULL	
					";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		// $list = $result;
		// if (count($result) > 0) {
			// $key=0;
			// echo json_encode($list);
		// } else {
			// echo json_encode($list);
		// }
		
		$items = array();
		if (count($result) > 0) {
			$i=0;
			foreach ($result as $row) {
				$detailuang = json_decode($row->detail_uang, true);
				
				$items[$i]['id'] = $row->id;
				$items[$i]['id_cashtransit'] = $row->id_cashtransit;
				$items[$i]['state'] = $row->state;
				$items[$i]['metode'] = ($row->metode=="CP" ? "CASH PICKUP" : ($row->metode=="CD" ? "CASH DELIVERY" : ""));
				$items[$i]['jenis'] = $row->jenis;
				if($row->id_pengirim!=0){
					$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_pengirim."'")->row()->sektor;
				} else {
					$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_penerima."'")->row()->sektor;
				}
				$items[$i]['pengirim'] = ($row->id_pengirim==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_pengirim."'")->row()->nama_client);
				$items[$i]['penerima'] = ($row->id_penerima==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_penerima."'")->row()->nama_client);
				$items[$i]['kertas_100k'] = $detailuang['kertas_100k'];
				$items[$i]['kertas_50k'] = $detailuang['kertas_50k'];
				$items[$i]['kertas_20k'] = $detailuang['kertas_20k'];
				$items[$i]['kertas_10k'] = $detailuang['kertas_10k'];
				$items[$i]['kertas_5k'] = $detailuang['kertas_5k'];
				$items[$i]['kertas_2k'] = $detailuang['kertas_2k'];
				$items[$i]['kertas_1k'] = $detailuang['kertas_1k'];
				$items[$i]['logam_1k'] = $detailuang['logam_1k'];
				$items[$i]['logam_500'] = $detailuang['logam_500'];
				$items[$i]['logam_200'] = $detailuang['logam_200'];
				$items[$i]['logam_100'] = $detailuang['logam_100'];
				$items[$i]['logam_50'] = $detailuang['logam_50'];
				$items[$i]['total'] = $row->total;
				$i++;
			}
			echo json_encode($items);
		} else {
			echo json_encode($items);
		}
	}
	
	public function get_data() {
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		$data['base_url'] = base_url();
		
		$result = $this->curl->simple_post(rest_api().'/plan_runsheet/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $result;
	}
	
	public function get_data2() {
		// $id = $this->uri->segment(3);
		// $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		// $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		
		// $data['id'] = $id;
		// $data['page'] = $page;
		// $data['rows'] = $rows;
		// $data['base_url'] = base_url();
		
		// $result = $this->curl->simple_post(rest_api().'/plan_runsheet/get_data2',$data,array(CURLOPT_BUFFERSIZE => 10));

		// echo $result;
		
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
					runsheet_security.id, 
					runsheet_security.id_cashtransit, 
					runsheet_security.run_number,
					vehicle.type,
					vehicle.police_number,
					vehicle.km_status,
					runsheet_security.security_1,
					runsheet_security.security_2,
					master_zone.name AS zone_name
				FROM runsheet_security 
				LEFT JOIN master_zone ON(master_zone.id=runsheet_security.run_number) 
				LEFt JOIN master_branch ON(master_branch.id=master_zone.id_branch)
				LEFT JOIN vehicle ON(runsheet_security.police_number=vehicle.police_number) 
				WHERE runsheet_security.id_cashtransit='".$id."' limit $offset,$rows";
		
		// $result = $this->curl->simple_post(rest_api().'/run_operational/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$result["total"] = count($res);
		

		$items = array();
		$i = 0;
		foreach($res as $row){
			// $items[$i]['id'] = $row->id;
			// $items[$i]['id_cashtransit'] = $row->id_cashtransit;
			// $items[$i]['run_number'] = substr($row->zone_name, 0, 5)." ".$row->kode_zone;
			// $items[$i]['type'] = $row->type;
			// $items[$i]['police_number'] = $row->police_number;
			// $items[$i]['km_status'] = $row->km_status;
			// $items[$i]['security_1'] = $row->security_1;
			// $items[$i]['security_2'] = $row->security_2;
			$items[$i]['id'] = $row->id;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['action'] = '<a href="'.$base_url.'runsheet/detail_runsheet/'.$row->id_cashtransit.'/'.$row->run_number.'#&tab-drs" class="button blue" iconCls="icon-search">View Detail</a>';
			$items[$i]['run_number'] = substr($row->zone_name, 0, 5)." ".$row->kode_zone;
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
		$this->data['id_cashtransit'] = $this->input->get('id_cashtransit');
		$this->data['index'] = $this->input->get('index');
		
		return view('admin/runsheet/show_form', $this->data);
	}
	
	public function detail_runsheet() {
		$this->data['active_menu'] = "runsheet";
		$this->data['flag'] = "show_form";
		$this->data['id_cashtransit'] = $this->input->get('id_cashtransit');
		$this->data['index'] = $this->input->get('index');
		
		$id_ct = $this->uri->segment(3);
		$id_zona = $this->uri->segment(4);
		
		
		$branch = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) WHERE cashtransit.id='".$id_ct."'"), array(CURLOPT_BUFFERSIZE => 10)))->name;
		
		
		$security = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM cashtransit LEFT JOIN runsheet_security ON(cashtransit.id=runsheet_security.id_cashtransit) WHERE cashtransit.id='".$id_ct."' AND runsheet_security.run_number='".$id_zona."'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		$operational = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM cashtransit LEFT JOIN runsheet_operational ON(cashtransit.id=runsheet_operational.id_cashtransit) WHERE cashtransit.id='".$id_ct."' AND runsheet_operational.run_number='".$id_zona."'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		$cash = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM cashtransit LEFT JOIN runsheet_cashprocessing ON(cashtransit.id=runsheet_cashprocessing.id_cashtransit) WHERE cashtransit.id='".$id_ct."' AND runsheet_cashprocessing.run_number='".$id_zona."'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		$logistic = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM cashtransit LEFT JOIN runsheet_logistic ON(cashtransit.id=runsheet_logistic.id_cashtransit) WHERE cashtransit.id='".$id_ct."' AND runsheet_logistic.run_number='".$id_zona."'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$km = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT km_status FROM vehicle WHERE police_number='".$security->police_number."'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$zone = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT kode_zone, name FROM master_zone WHERE id='".$id_zona."'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$i = 0;
		foreach(json_decode($logistic->data) as $k => $r) {
			$log[$i]['name'] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT name FROM inventory WHERE id='".$k."'"), array(CURLOPT_BUFFERSIZE => 10)))->name;
			
			$log[$i]['qty'] = $r;
			$i++;
		}
		
		$this->data['id_ct'] = $id_ct;
		$this->data['id_zona'] = $id_zona;
		$this->data['branch'] = $branch;
		$this->data['ga'] = $zone->name;
		$this->data['police_number'] = $security->police_number;
		$this->data['km'] = number_format($km->km_status, 0, ",", ",");
		$this->data['security_1'] = !empty($security->security_1) ? json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan WHERE nik='".$security->security_1."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama : "";
		$this->data['security_2'] = !empty($security->security_2) ? json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan WHERE nik='".$security->security_2."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama : "";
		$this->data['custodian_1'] = !empty($operational->custodian_1) ? json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan WHERE nik='".$operational->custodian_1."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama : "";
		$this->data['custodian_2'] = !empty($operational->custodian_2) ? json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan WHERE nik='".$operational->custodian_2."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama : "";
		$this->data['logistic'] = $log;
		$this->data['petty_cash'] = (!empty($cash->total) ? number_format($cash->total, 0, ",", ",") : 0);
		$this->data['statusx'] = !empty($operational->data_solve) ? "SOLVED" : "ASD";
		
		
		return view('admin/runsheet/detail_runsheet', $this->data);
	}
	
	public function suggest() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		$sql = "SELECT * FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN master_zone ON(client.sektor=master_zone.id) WHERE cashtransit_detail.id_cashtransit='$id_cashtransit' AND client.sektor NOT IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit='$id_cashtransit') GROUP BY client.sektor";
		// echo $sql;
		$result = $this->db->query($sql);
		// print_r($result->result());
		
		$list = array();
		if ($result->num_rows() > 0) {
			$key=0;
			foreach ($result->result() as $row) {
				$list[$key]['id'] = $row->sektor;
				$list[$key]['text'] = "(".$row->sektor.") ".$row->name; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function save_data() {
		// print_r($this->input->post());
		
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

		$this->db->insert('runsheet_security', $data);

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
		$id = $_POST['id'];

		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('runsheet_security');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}