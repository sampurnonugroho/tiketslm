<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Unload extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
        
        // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']); 
    }
	
	function dashboard_get() {
		$sql = "
			SELECT
				count(*) as cnt,
				cashtransit_detail.*,
				cashtransit.*,
				master_branch.*,
				runsheet_operational.*,
				runsheet_security.*,
				runsheet_cashprocessing.petty_cash,
				(SELECT SUM(total) AS petty_cash FROM runsheet_cashprocessing WHERE id_cashtransit=cashtransit.id_cashtransit) as petty_cash
					FROM
						(SELECT id as id_cashtransit, h_min, date, branch, run_number FROM cashtransit) AS cashtransit
							LEFT JOIN
								(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, state, metode, data_solve, cpc_process, unloading FROM cashtransit_detail) AS cashtransit_detail
									ON (cashtransit_detail.id_cashtransit=cashtransit.id_cashtransit)
							LEFT JOIN 
								(SELECT id as id_client, sektor FROM client) AS client 
									ON (cashtransit_detail.id_bank=client.id_client) 
							LEFT JOIN 
								(SELECT id as id_client_cit, sektor FROM client_cit) AS client_cit 
									ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id_client_cit)
							LEFT JOIN 
								(SELECT id as id_branch, name AS name_branch FROM master_branch) AS master_branch
									ON (cashtransit.branch=master_branch.id_branch)
							LEFT JOIN 
								(SELECT id_cashtransit, custodian_1 AS custody FROM runsheet_operational) AS runsheet_operational
									ON (
										(runsheet_operational.id_cashtransit = cashtransit_detail.id_cashtransit) 
									)
							LEFT JOIN 
								(SELECT id_cashtransit, police_number FROM runsheet_security) AS runsheet_security
									ON (
										(runsheet_security.id_cashtransit = cashtransit_detail.id_cashtransit) 
									)
							LEFT JOIN 
								(SELECT id as id_runsheet_cashprocessing, id_cashtransit, total as petty_cash FROM runsheet_cashprocessing) AS runsheet_cashprocessing
									ON (
										(runsheet_cashprocessing.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
										(runsheet_cashprocessing.id_runsheet_cashprocessing = cashtransit_detail.id)
									)
					WHERE
						(data_solve != '' AND (cpc_process = '' OR cpc_process = 'pengisian')) AND unloading = '0'
					GROUP BY cashtransit.id_cashtransit
					ORDER BY cashtransit_detail.id DESC
		";

		$query = $this->db->query($sql)->result_array();
		
		// echo "<pre>";
		// print_r($query);
		
		$list = array();
		$data = array();
		
		$i = 0;
		$petty = 0;
		foreach($query as $r) {
			$data_solve = json_decode($r['data_solve']);
			
			$list[$i]['id'] = $r['id'];
			$list[$i]['id_cashtransit'] = $r['id_cashtransit'];
			$list[$i]['date'] = date("d-m-Y", strtotime($r['date']));
			$list[$i]['branch'] = $r['name_branch'];
			$list[$i]['run_number'] = "(H-".$r['h_min'].") (RUN NUMBER ".$r['run_number'].")";
			$list[$i]['police_number'] = $r['police_number'];
			
			if($r['state']=="ro_cit") {
				if($r['metode']=="CP") {
					$s100k = ($data_solve->kertas_100k='' ? 0 : $data_solve->kertas_100k*100000);
					$s50k = ($data_solve->kertas_50k='' ? 0 : $data_solve->kertas_50k*50000);
					$total = $s100k+$s50k;
					$petty = $petty + $total;
				}
			} else if($r['state']=="ro_atm") {
				$petty = $petty + $data_solve->return_withdraw;
			}
			
			$list[$i]['petty_cash'] = $petty;
			$list[$i]['count'] = $r['cnt'];
			
			$i++;
		}
		
		$data['data'] = $list;
		$data['count'] = count($query);
		
		// print_r($data);
		
		echo json_encode($data);
	}
	
	function get_data_plan_get() {
		$sql = "SELECT 
					cashtransit_detail.id as id1,
					cashtransit_detail.id_cashtransit,
					cashtransit_detail.id_pengirim,
					cashtransit_detail.id_penerima,
					cashtransit_detail.state,
					cashtransit_detail.no_boc,
					cashtransit_detail.metode,
					cashtransit_detail.jenis,
					#cashtransit_detail.*,
					runsheet_cashprocessing.*,
					client.*,
					master_branch.name_branch,
					(SELECT nama_client FROM client_cit WHERE id=cashtransit_detail.id_pengirim) AS pengirim,
					(SELECT alamat FROM client_cit WHERE id=cashtransit_detail.id_pengirim) AS lokasi_pengirim,
					(SELECT nama_client FROM client_cit WHERE id=cashtransit_detail.id_penerima) AS penerima
				FROM cashtransit_detail
					LEFT JOIN
							(SELECT id as id_cashtransit, date, branch FROM cashtransit) AS cashtransit 
								ON (cashtransit_detail.id_cashtransit=cashtransit.id_cashtransit)
					LEFT JOIN 
						(SELECT id_cashtransit, custodian_1 AS custody FROM runsheet_operational) AS runsheet_operational
							ON (
								(runsheet_operational.id_cashtransit = cashtransit_detail.id_cashtransit) 
							)
					LEFT JOIN 
						(SELECT * FROM runsheet_cashprocessing) AS runsheet_cashprocessing
							ON (
								(cashtransit_detail.id = runsheet_cashprocessing.id) 
							)
					LEFT JOIN 
						(SELECT *, id as id_client FROM client) AS client 
							ON (cashtransit_detail.id_bank=client.id_client) 
					LEFT JOIN 
						(SELECT id as id_branch, name AS name_branch FROM master_branch) AS master_branch
							ON (cashtransit.branch=master_branch.id_branch)
				WHERE 
					(data_solve != '' AND (cpc_process = '' OR cpc_process = 'pengisian')) AND unloading = '0'
		";
		
		$query = $this->db->query($sql)->result_array();
		
		
		// echo "<pre>";
		// print_r($query);
		
		echo json_encode($query);

		
	}
	
	function loading_get() {
		$sql = "SELECT
					D.id,
					D.state,
					cashtransit.id,
					cashtransit.run_number,
					D.id_bank,
					IFNULL((
						SELECT COUNT(DISTINCT cashtransit_detail.id) AS done_action FROM cashtransit_detail
							LEFT JOIN client c ON (cashtransit_detail.id_bank = c.id)
							LEFT JOIN client_cit c2 ON(IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=c2.id)
						WHERE cashtransit_detail.id_cashtransit = cashtransit.id 
						AND cashtransit_detail.unloading = '1'
					),0) AS Xdone_action,
					IFNULL((
						SELECT COUNT(DISTINCT cashtransit_detail.id) AS done_action FROM cashtransit_detail
							LEFT JOIN client c ON (cashtransit_detail.id_bank = c.id)
							LEFT JOIN client_cit c2 ON(IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=c2.id)
						WHERE cashtransit_detail.id_cashtransit = cashtransit.id 
						#AND IFNULL(c.sektor, c2.sektor)  = run_number 
						AND cashtransit_detail.data_solve <> ''
					),0) AS done_action,
					IFNULL((
						SELECT COUNT(DISTINCT cashtransit_detail.id) AS done_loading FROM cashtransit_detail
							LEFT JOIN client c ON (cashtransit_detail.id_bank = c.id)
							LEFT JOIN client_cit c2 ON(IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=c2.id)
						WHERE cashtransit_detail.id_cashtransit = cashtransit.id
						AND cashtransit_detail.loading = '1'
					),0) AS done_loading,
					IFNULL((
						SELECT COUNT(DISTINCT cashtransit_detail.id) AS done_unloading FROM cashtransit_detail
							LEFT JOIN client c ON (cashtransit_detail.id_bank = c.id)
							LEFT JOIN client_cit c2 ON(IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=c2.id)
						WHERE cashtransit_detail.id_cashtransit = cashtransit.id
						AND cashtransit_detail.unloading = '1'
					),0) AS done_unloading,
					IFNULL((
						SELECT COUNT(DISTINCT cashtransit_detail.id) AS count_loading FROM cashtransit_detail
							LEFT JOIN client c ON (cashtransit_detail.id_bank = c.id)
							LEFT JOIN client_cit c2 ON(IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=c2.id)
						WHERE cashtransit_detail.id_cashtransit = cashtransit.id
						#AND IFNULL(c.sektor, c2.sektor)  = run_number
					),0) AS count_loading
						FROM cashtransit
						LEFT JOIN cashtransit_detail AS D
							ON (cashtransit.id=D.id_cashtransit)
						LEFT JOIN master_branch
							ON (master_branch.id=cashtransit.branch)
						LEFT JOIN client 
							ON(D.id_bank = client.id) 
						LEFT JOIN client_cit 
							ON(IF(D.id_pengirim=0, D.id_penerima, D.id_pengirim)=client_cit.id)
						LEFT JOIN 
							(SELECT id_cashtransit, custodian_1 AS custody FROM runsheet_operational) AS runsheet_operational
								ON (
									(runsheet_operational.id_cashtransit = D.id_cashtransit) 
								)
						WHERE 
						data_solve != '' AND unloading = '0'
						GROUP BY run_number, cashtransit.id
						ORDER BY D.id DESC";
						
		$query = $this->db->query($sql)->result_array();
		
		$i = 0;
		$list = array();
		foreach($query as $r) {
			$list[$i]['id'] = $r['id'];
			$list[$i]['state'] = $r['state'];
			$list[$i]['run_number'] = $r['run_number'];
			$list[$i]['id_bank'] = $r['id_bank'];
			$list[$i]['done_action'] = strval($r['done_action']-$r['Xdone_action']);
			$list[$i]['done_loading'] = strval($r['done_loading']-$r['Xdone_action']);
			$list[$i]['done_unloading'] = strval($r['done_unloading']);
			$list[$i]['count_loading'] = strval($r['count_loading']-$r['Xdone_action']);
			
			$i++;
		}
		echo json_encode($list);
	}
	
	function loading_runsheet_get() {
		$id_user = $this->input->get('id_user');
		
		$sql = "
			SELECT 
				client.*,
				client_cit.*,
				cashtransit_detail.*,
				master_branch.*,
				runsheet_operational.*,
				runsheet_security.*,
				runsheet_cashprocessing.*
				FROM
					(SELECT * FROM cashtransit_detail) AS cashtransit_detail
						LEFT JOIN
							(SELECT * FROM cashtransit) AS cashtransit 
								ON (cashtransit_detail.id_cashtransit=cashtransit.id)
						LEFT JOIN 
							(SELECT *, type as c_type, vendor as c_brand, type_mesin as c_model, lokasi as c_lokasi FROM client) AS client 
								ON (cashtransit_detail.id_bank=client.id) 
						LEFT JOIN 
							(SELECT * FROM client_cit) AS client_cit 
								ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
						LEFT JOIN 
							(SELECT *, master_branch.name as branch_name FROM master_branch) AS master_branch
								ON (cashtransit.branch=master_branch.id)
						LEFT JOIN 
							(SELECT *, custodian_1 AS custody FROM runsheet_operational) AS runsheet_operational
								ON (
									(runsheet_operational.id_cashtransit = cashtransit_detail.id_cashtransit) 
								)
						LEFT JOIN 
							(SELECT * FROM runsheet_security) AS runsheet_security
								ON (
									(runsheet_security.id_cashtransit = cashtransit_detail.id_cashtransit) 
								)
						LEFT JOIN 
							(SELECT *, id as id_runsheet_cashprocessing, total as petty_cash FROM runsheet_cashprocessing) AS runsheet_cashprocessing
								ON (
									(runsheet_cashprocessing.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
									(runsheet_cashprocessing.id_runsheet_cashprocessing = cashtransit_detail.id)
								)
								
					WHERE
						(data_solve != '' AND (cpc_process = '' OR cpc_process = 'pengisian')) AND unloading = '0'
		";
		$query = $this->db->query($sql)->result_array();
		
		$list = array();
		$key=0;
		foreach($query as $r) {
			if($r['state']=="ro_atm") {
				if($r['type']=="CRM") {
					list($seal_1, $denom_1, $value_1) = explode(";", $r['cart_1_seal']);
					list($seal_2, $denom_2, $value_2) = explode(";", $r['cart_2_seal']);
					list($seal_3, $denom_3, $value_3) = explode(";", $r['cart_3_seal']);
					list($seal_4, $denom_4, $value_4) = explode(";", $r['cart_4_seal']);
					
					$r['pcs_100000'] =  ($denom_1=="100" ? $value_1 : 0) +
										($denom_2=="100" ? $value_2 : 0) +
										($denom_3=="100" ? $value_3 : 0) +
										($denom_4=="100" ? $value_4 : 0);
					
					$r['pcs_50000'] =   ($denom_1=="50" ? $value_1 : 0) +
										($denom_2=="50" ? $value_2 : 0) +
										($denom_3=="50" ? $value_3 : 0) +
										($denom_4=="50" ? $value_4 : 0);
					
					$r['nominal'] = $r['total'] =  ((intval($denom_1) * intval($value_1)) +
													(intval($denom_2) * intval($value_2)) +
													(intval($denom_3) * intval($value_3)) +
													(intval($denom_4) * intval($value_4))) * 1000;
				}  else {
					$r['pcs_100000'] = $r['pcs_100000']*$r['ctr'];
					$r['pcs_50000'] = $r['pcs_50000']*$r['ctr'];
					$r['pcs_20000'] = $r['pcs_20000']*$r['ctr'];
					$r['pcs_10000'] = $r['pcs_10000']*$r['ctr'];
					$r['pcs_5000'] = $r['pcs_5000']*$r['ctr'];
					$r['pcs_1000'] = $r['pcs_1000']*$r['ctr'];
				}
				
				$list[$key]['id1'] = $r['id'];
				$list[$key]['id'] = "T".date("Ymd").sprintf("%04d", $r['id']);
				$list[$key]['wsid'] = $r['wsid'];
				$list[$key]['state'] = $r['state'];
				$list[$key]['ga'] = $r['run_number'];
				$list[$key]['branch'] = $r['branch_name'];
				$list[$key]['bank'] = (!empty($r['bank']) ? $r['bank'] : $r['nama_client']);
				$list[$key]['act'] = (!empty($r['c_type']) ? $r['c_type'] : '-');
				$list[$key]['brand'] = (!empty($r['c_brand']) ? $r['c_brand'] : '-');
				$list[$key]['model'] = (!empty($r['c_model']) ? $r['c_model'] : '-');
				$list[$key]['lokasi'] = (!empty($r['c_lokasi']) ? $r['c_lokasi'] : '-');;
				$list[$key]['police_number'] = $r['police_number'];
				$list[$key]['km_status'] = 0;
				$list[$key]['petty_cash'] = number_format($r['petty_cash'], 0, ",", ",");
				
				$list[$key]['custodian_1'] = !empty($r['custodian_1']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_1'])->limit(1)->get()->row()->nama : "N/A";
				$list[$key]['custodian_2'] = !empty($r['custodian_2']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_2'])->limit(1)->get()->row()->nama : "N/A";
				$list[$key]['security_1'] = $r['security_1'];
				$list[$key]['security_2'] = $r['security_2'];
				$list[$key]['bag_seal'] = $r['bag_seal'];
				$list[$key]['bag_no'] = $r['bag_no'];
			} else if($r['state']=="ro_cit") {
				$list[$key]['id1'] = $r['id'];
				$list[$key]['id'] = "T".date("Ymd").sprintf("%04d", $r['id']);
				$list[$key]['wsid'] = $r['wsid'];
				$list[$key]['state'] = $r['state'];
				$list[$key]['ga'] = $r['run_number'];
				$list[$key]['branch'] = $r['branch_name'];
				$list[$key]['metode'] = ($r['metode']=="CP" ? "CASH PICKUP" : ($r['metode']=="CD" ? "CASH DELIVERY" : ""));
				$list[$key]['jenis'] = $r['jenis'];
				$list[$key]['lokasi'] = $r['lokasi'];
				
				$list[$key]['pengirim'] = ($r['id_pengirim']==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$r['id_pengirim']."'")->row()->nama_client);
				$list[$key]['penerima'] = ($r['id_penerima']==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$r['id_penerima']."'")->row()->nama_client);
				
				$list[$key]['police_number'] = $r['police_number'];
				$list[$key]['petty_cash'] = number_format($r['petty_cash'], 0, ",", ",");
				
				$list[$key]['custodian_1'] = !empty($r['custodian_1']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_1'])->limit(1)->get()->row()->nama : "N/A";
				$list[$key]['custodian_2'] = !empty($r['custodian_2']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_2'])->limit(1)->get()->row()->nama : "N/A";
				$list[$key]['security_1'] = $r['security_1'];
				$list[$key]['security_2'] = $r['security_2'];
				$list[$key]['bag_seal'] = $r['bag_seal'];
				$list[$key]['bag_no'] = $r['bag_no'];
			}
			
			$key++;
		}
		
		echo json_encode($list);
	}
	
	function syncronize_get() {
		$data_plan = json_decode($_REQUEST['data_plan'], true);
		echo "<pre>";
		print_r($data_plan);
		
		// foreach($data_plan as $r) {
			// $data = array();
			// if($r['unloading']!="") 		{ $data['unloading'] = $r['unloading']; }
		
			// if(!empty($data)) {
				// $this->db->where('id', $r['id']);
				// $update = $this->db->update('cashtransit_detail', $data);
				// echo $this->db->last_query()."\n";
			// }
		// }
	}
	
	function syncronize_post() {
		$data_plan = json_decode($_REQUEST['data_plan'], true);
		// echo "<pre>";
		// print_r($data_plan);
		
		foreach($data_plan as $r) {
			$data = array();
			if($r['unloading']!="") 		{ $data['unloading'] = $r['unloading']; }
		
			if(!empty($data)) {
				$this->db->where('id', $r['id']);
				$update = $this->db->update('cashtransit_detail', $data);
				echo $this->db->last_query()."\n";
			}
		}
	}
}

