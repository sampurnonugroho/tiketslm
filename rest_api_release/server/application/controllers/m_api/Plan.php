<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Plan extends REST_Controller {
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
		$id_user = $this->input->get('id_user');
					
		$sql = "
			SELECT
				cashtransit_detail.*,
				cashtransit.*,
				master_branch.*,
				runsheet_operational.*,
				runsheet_security.*,
				runsheet_cashprocessing.petty_cash,
				cashtransit.run_number AS run_number,
				(SELECT SUM(total) AS petty_cash FROM runsheet_cashprocessing WHERE id_cashtransit=cashtransit.id_cashtransit) as petty_cash
					FROM
						(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, data_solve, cpc_process, unloading FROM cashtransit_detail) AS cashtransit_detail
							LEFT JOIN
								(SELECT id as id_cashtransit, date, branch, run_number, action_date FROM cashtransit) AS cashtransit 
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
						cashtransit.run_number!=''
						AND runsheet_operational.custody = '$id_user' 
						AND cashtransit_detail.data_solve = '' 
						AND cashtransit_detail.unloading = '0' 
						AND cashtransit.action_date <= '".date('Y-m-d')."'
						AND runsheet_cashprocessing.id_runsheet_cashprocessing IS NOT NULL
						
					GROUP BY cashtransit.id_cashtransit
					ORDER BY cashtransit_detail.id DESC
		";

		$query = $this->db->query($sql)->result_array();
		
		$list = array();
		
		$i = 0;
		foreach($query as $r) {
			$list[$i]['id'] = $r['id_cashtransit'];
			$list[$i]['date'] = date("d-m-Y", strtotime($r['date']));
			$list[$i]['branch'] = $r['name_branch'];
			$list[$i]['run_number'] = $r['run_number'];
			$list[$i]['police_number'] = $r['police_number'];
			// $list[$i]['petty_cash'] = number_format($r['petty_cash'], 0, ",", ",");
			$list[$i]['petty_cash'] = $r['petty_cash'];
			
			$i++;
		}
		
		$data['data'] = $list;
		$data['count'] = count($query);
		
		echo json_encode($data);
	}
	
	function get_data_plan_get() {
		$id_user = $this->input->get('id_user');
	
			
		$sql = "SELECT 
					cashtransit_detail.*,
					(SELECT nama_client FROM client_cit WHERE id=cashtransit_detail.id_pengirim) AS pengirim,
					(SELECT alamat FROM client_cit WHERE id=cashtransit_detail.id_pengirim) AS lokasi_pengirim,
					(SELECT nama_client FROM client_cit WHERE id=cashtransit_detail.id_penerima) AS penerima
				FROM cashtransit_detail
					LEFT JOIN
						(SELECT id as id_cashtransit, date, branch, run_number, action_date FROM cashtransit) AS cashtransit 
							ON (cashtransit_detail.id_cashtransit=cashtransit.id_cashtransit)
					LEFT JOIN 
						(SELECT id_cashtransit, custodian_1 AS custody FROM runsheet_operational) AS runsheet_operational
							ON (
								(runsheet_operational.id_cashtransit = cashtransit_detail.id_cashtransit) 
							)
					LEFT JOIN 
						(SELECT id, detail_uang FROM runsheet_cashprocessing) AS runsheet_cashprocessing
							ON (
								(cashtransit_detail.id = runsheet_cashprocessing.id) 
							)
				WHERE 
					cashtransit.run_number!=''
					AND runsheet_operational.custody = '$id_user' 
					AND cashtransit_detail.data_solve = '' 
					AND cashtransit_detail.unloading = '0' 
					AND cashtransit.action_date <= '".date('Y-m-d')."'
		";
		
		$query = $this->db->query($sql)->result_array();
		
		echo json_encode($query);
		
	}
	
	function getreplenish_get() {
		$id_user = $this->input->get('id_user');
		$id_ticket = $this->input->get('id_ticket');
		
		$param = "WHERE 
					cashtransit.run_number!=''
					AND runsheet_operational.custodian_1 = '$id_user' 
					AND cashtransit_detail.data_solve = '' 
					AND cashtransit.action_date <= '".date('Y-m-d')."'
		";
	
		
		$sql = "SELECT 
						cashtransit.id, 
						cashtransit_detail.id AS id_ticket, 
						cashtransit_detail.id_bank, 
						cashtransit_detail.state, 
						cashtransit_detail.no_boc, 
						cashtransit_detail.jenis, 
						cashtransit_detail.ctr, 
						cashtransit_detail.total, 
						cashtransit_detail.fraud_indicated, 
						client.wsid, 
						client.sektor AS ga, 
						cashtransit.run_number AS run_number, 
						master_branch.name, 
						client.denom, 
						client.bank, 
						client.type, 
						client.lokasi, 
						client.vendor AS brand, 
						client.type_mesin AS model, 
						client.data_location, 
						runsheet_cashprocessing.cart_1_seal, 
						runsheet_cashprocessing.cart_2_seal, 
						runsheet_cashprocessing.cart_3_seal, 
						runsheet_cashprocessing.cart_4_seal, 
						runsheet_cashprocessing.cart_5_seal, 
						runsheet_cashprocessing.total AS nominal, 
						runsheet_cashprocessing.pcs_100000, 
						runsheet_cashprocessing.pcs_50000, 
						runsheet_cashprocessing.pcs_20000, 
						runsheet_cashprocessing.pcs_10000, 
						runsheet_cashprocessing.pcs_5000, 
						runsheet_cashprocessing.pcs_2000, 
						runsheet_cashprocessing.pcs_1000, 
						runsheet_cashprocessing.pcs_coin, 
						runsheet_cashprocessing.bag_seal, 
						runsheet_cashprocessing.bag_seal_return, 
						runsheet_cashprocessing.bag_no, 
						runsheet_cashprocessing.detail_uang, 
						runsheet_security.police_number, 
						vehicle.km_status, 
						runsheet_operational.custodian_1, 
						runsheet_operational.custodian_2, 
						runsheet_security.security_1, 
						runsheet_security.security_2, 
						runsheet_logistic.data 
					FROM cashtransit_detail
						LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
						LEFT JOIN master_branch ON (cashtransit.branch=master_branch.id)
						LEFT JOIN client ON (cashtransit_detail.id_bank=client.id)
						LEFT JOIN runsheet_operational ON (cashtransit_detail.id_cashtransit=runsheet_operational.id_cashtransit)
						LEFT JOIN runsheet_logistic ON (cashtransit_detail.id_cashtransit=runsheet_logistic.id_cashtransit)
						LEFT JOIN runsheet_security ON (cashtransit_detail.id_cashtransit=runsheet_security.id_cashtransit)
						LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
						LEFT JOIN vehicle ON (vehicle.police_number=runsheet_security.police_number)
						$param
					";
					
		$query = $this->db->query($sql)->result_array();
		
		
		$list = array();
		$key=0;
		foreach($query as $r) {
			$id_ticket = $r['id_ticket'];
			$seal = [];
			// echo $r['type']."<br>";
			// echo $r['ctr']."<br>";
			if($r['type']=="CRM") {
				// echo "<pre>";
				list($seal_1, $denom_1, $value_1) = explode(";", $r['cart_1_seal']);
				list($seal_2, $denom_2, $value_2) = explode(";", $r['cart_2_seal']);
				list($seal_3, $denom_3, $value_3) = explode(";", $r['cart_3_seal']);
				list($seal_4, $denom_4, $value_4) = explode(";", $r['cart_4_seal']);
				// list($seal_5, $denom_5, $value_5) = explode(";", $r['cart_5_seal']);
				
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
			
				$y = 0;
				for($x=1;$x<=$r['ctr'];$x++) {
					if (strpos($r["cart_".$x."_seal"], ';') !== false) {
						list($sealz, $denom, $value) = explode(";", $r["cart_".$x."_seal"]);
					} else {
						$sealz = $r['cart_'.$x.'_seal'];
					}
					
					// echo $r['cart_1_seal']."<br>";
					// echo $x."<br>";
					
					$seal[$y]['seal'] = $r['cart_'.$x.'_seal'];
					$seal[$y]['status'] = $this->db->query("SELECT * FROM run_status_cancel WHERE id_detail='$id_ticket' AND cart_seal='".$sealz."'")->num_rows();
					$y++;
				}
			} else {	
				$r['pcs_100000'] = $r['pcs_100000'];
				$r['pcs_50000'] = $r['pcs_50000'];
				$r['pcs_20000'] = $r['pcs_20000'];
				$r['pcs_10000'] = $r['pcs_10000'];
				$r['pcs_5000'] = $r['pcs_5000'];
				$r['pcs_1000'] = $r['pcs_1000'];
				
				$y = 0;
				for($x=1;$x<=$r['ctr'];$x++) {
					$seal[$y]['seal'] = $r['cart_'.$x.'_seal'];
					$seal[$y]['status'] = $this->db->query("SELECT * FROM run_status_cancel WHERE id_detail='$id_ticket' AND cart_seal='".$r['cart_'.$x.'_seal']."'")->num_rows();
					$y++;
				}
			}
			
			$list[$key]['id1'] = $r['id_ticket'];
			$list[$key]['id_cashtransit'] = $r['id'];
			$list[$key]['id'] = "T".date("Ymd").sprintf("%04d", $r['id_ticket']);
			$list[$key]['id_bank'] = $r['id_bank'];
			$list[$key]['data_location'] = $r['data_location'];
			$list[$key]['wsid'] = $r['wsid'];
			$list[$key]['no_boc'] = $r['no_boc'];
			$list[$key]['state'] = $r['state'];
			$list[$key]['ga'] = $r['ga'];
			$list[$key]['run_number'] = $r['run_number'];
			$list[$key]['branch'] = $r['name'];
			$list[$key]['bank'] = $r['bank'];
			$list[$key]['act'] = $r['type'];
			$list[$key]['brand'] = $r['brand'];
			$list[$key]['model'] = $r['model'];
			$list[$key]['lokasi'] = $r['lokasi'];
			$list[$key]['fraud_indicated'] = $r['fraud_indicated'];
			
			$list[$key]['denom'] = $r['denom'];
			$list[$key]['ctr'] = $r['ctr'];
			$list[$key]['total'] = $r['nominal'];
			$list[$key]['detail_uang'] = $r['detail_uang'];
			
			$list[$key]['police_number'] = $r['police_number'];
			$list[$key]['km_status'] = $r['km_status'];
			$list[$key]['petty_cash'] = number_format($r['nominal'], 0, ",", ",");
			
			$list[$key]['custodian_1'] = !empty($r['custodian_1']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_1'])->limit(1)->get()->row()->nama : "N/A";
			$list[$key]['custodian_2'] = !empty($r['custodian_2']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_2'])->limit(1)->get()->row()->nama : "N/A";
			$list[$key]['security_1'] = $r['security_1'];
			$list[$key]['security_2'] = $r['security_2'];
			$list[$key]['s100k'] = $r['pcs_100000'];
			$list[$key]['s50k'] = $r['pcs_50000'];
			$list[$key]['s20k'] = $r['pcs_20000'];
			$list[$key]['s10k'] = $r['pcs_10000'];
			$list[$key]['s5k'] = $r['pcs_5000'];
			$list[$key]['s2k'] = $r['pcs_2000'];
			$list[$key]['s1k'] = $r['pcs_1000'];
			$list[$key]['coin'] = $r['pcs_coin'];	
			$list[$key]['bag_seal'] = $r['bag_seal'];
			$list[$key]['bag_seal_return'] = $r['bag_seal_return'];
			$list[$key]['bag_no'] = $r['bag_no'];
			
			
			$i = 0;
			foreach(json_decode($r['data']) as $k => $r) {
				$log[$i]['name'] = $this->db->select('name')->from('inventory')->where('id', $k)->limit(1)->get()->row()->name;
				$log[$i]['qty'] = $r;
				$i++;
			}
			
			$list[$key]['logistic'] = $log;
			$list[$key]['cart_seal'] = $seal;
			
			
			$key++;
		}
		
		
		$result['data'] = $list;
		
		// echo "<pre>";
		// print_r($list);
		echo json_encode($list);
	}
	
	function get_prev_cr_get() {
		$id_user = $this->input->get('id_user');
		$sql = "SELECT 
					cashtransit_detail.id,
					cashtransit_detail.id_bank,
					cashtransit_detail.data_solve
				FROM cashtransit_detail
					LEFT JOIN  (SELECT id_cashtransit, custodian_1 AS custody FROM runsheet_operational) AS runsheet_operational
						ON (runsheet_operational.id_cashtransit = cashtransit_detail.id_cashtransit)
				WHERE 
					runsheet_operational.custody = '$id_user' AND data_solve = '' AND unloading = '0'";
		
		$query = $this->db->query($sql)->result_array();
		
		$list = array();
		$i = 0;
		foreach($query as $r) {
			$id_ticket = $r['id'];
			$id_bank = $r['id_bank'];
			$sql = "SELECT * FROM (SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, cpc_process, updated_date FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id_ticket AND id_bank='$id_bank' AND cashtransit_detail.data_solve!='batal')";
			
			$query = $this->db->query($sql)->row_array();
			$type = $this->db->query("SELECT type FROM client WHERE id='".$query['id_bank']."'")->row()->type;
			if($type=="CRM") {
				$result['id'] 						= $query['id'];
				$result['id_cashtransit'] 			= $query['id_cashtransit'];
				$result['id_bank'] 					= $query['id_bank'];
				$result['id_pengirim'] 				= $query['id_pengirim'];
				$result['id_penerima'] 				= $query['id_penerima'];
				$result['no_boc'] 					= $query['no_boc'];
				$result['state']					= $query['state'];
				$result['metode'] 					= $query['metode'];
				$result['jenis']					= $query['jenis'];
				$result['denom'] 					= $query['denom'];
				$result['pcs_100000'] 				= $query['pcs_100000'];
				$result['pcs_50000'] 				= $query['pcs_50000'];
				$result['pcs_20000'] 				= $query['pcs_20000'];
				$result['pcs_10000'] 				= $query['pcs_10000'];
				$result['pcs_5000'] 				= $query['pcs_5000'];
				$result['pcs_2000'] 				= $query['pcs_2000'];
				$result['pcs_1000'] 				= $query['pcs_1000'];
				$result['pcs_coin'] 				= $query['pcs_coin'];
				$result['detail_uang'] 				= $query['detail_uang'];
				$result['ctr'] 						= $query['ctr'];
				$result['divert'] 					= $query['divert'];
				$result['total'] 					= $query['total'];
				$result['date'] 					= $query['date'];
				$result['data_solve'] 				= $query['data_solve'];
				$result['cpc_process'] 				= $query['cpc_process'];
				$result['updated_date'] 			= $query['updated_date'];
				$result['id_cashtransit_detail'] 	= $query['id_cashtransit_detail'];
				$result['run_number'] 				= $query['run_number'];
				$result['ctr_1_no'] 				= $query['ctr_1_no'];
				$result['ctr_2_no'] 				= $query['ctr_2_no'];
				$result['ctr_3_no'] 				= $query['ctr_3_no'];
				$result['ctr_4_no'] 				= $query['ctr_4_no'];
				$result['ctr_5_no'] 				= $query['ctr_5_no'];
				$result['cart_1_seal'] 				= explode(";", $query['cart_1_seal'])[0];
				$result['cart_2_seal'] 				= explode(";", $query['cart_2_seal'])[0];
				$result['cart_3_seal'] 				= explode(";", $query['cart_3_seal'])[0];
				$result['cart_4_seal'] 				= explode(";", $query['cart_4_seal'])[0];
				$result['cart_5_seal'] 				= explode(";", $query['cart_5_seal'])[0];
				$result['bag_seal'] 				= $query['bag_seal'];
				$result['bag_no'] 					= $query['bag_no'];
				$result['t_bag'] 					= $query['t_bag'];
				$result['data_seal'] 				= $query['data_seal'];
				$result['updated_date_cpc'] 		= $query['updated_date_cpc'];
			} else { 
				$result = $query;
			}
			
			$list[$i]['id'] = $id_ticket;
			$list[$i]['id_bank'] = $id_bank;
			$list[$i]['prev'] = ($result=="" ? null : $result);
			
			$i++;
		}
		
		// echo "<pre>";
		// print_r($list);
		
		echo json_encode($list);
	}
	
	function get_prev_cr_get2() {
	    $id_bank = $this->input->get('id_bank');
		$id_ticket = $this->input->get('id_ticket');
	    
	    // $sql = "SELECT * FROM (SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, cpc_process, updated_date FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id_ticket)";
		
	    $sql = "SELECT * FROM (SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, cpc_process, updated_date FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id_ticket AND id_bank='$id_bank' AND cashtransit_detail.data_solve!='batal')";
		
	    
	    $query = $this->db->query($sql)->row_array();
		
		$type = $this->db->query("SELECT type FROM client WHERE id='".$query['id_bank']."'")->row()->type;
	    
		   // echo "<pre>";
		   // print_r($query);
	    
		if($type=="CRM") {
			$data['id'] 						= $query['id'];
			$data['id_cashtransit'] 			= $query['id_cashtransit'];
			$data['id_bank'] 					= $query['id_bank'];
			$data['id_pengirim'] 				= $query['id_pengirim'];
			$data['id_penerima'] 				= $query['id_penerima'];
			$data['no_boc'] 					= $query['no_boc'];
			$data['state'] 						= $query['state'];
			$data['metode'] 					= $query['metode'];
			$data['jenis'] 					= $query['jenis'];
			$data['denom'] 					= $query['denom'];
			$data['pcs_100000'] 				= $query['pcs_100000'];
			$data['pcs_50000'] 				= $query['pcs_50000'];
			$data['pcs_20000'] 				= $query['pcs_20000'];
			$data['pcs_10000'] 				= $query['pcs_10000'];
			$data['pcs_5000'] 				= $query['pcs_5000'];
			$data['pcs_2000'] 				= $query['pcs_2000'];
			$data['pcs_1000'] 				= $query['pcs_1000'];
			$data['pcs_coin'] 				= $query['pcs_coin'];
			$data['detail_uang'] 				= $query['detail_uang'];
			$data['ctr'] 						= $query['ctr'];
			$data['divert'] 					= $query['divert'];
			$data['total'] 					= $query['total'];
			$data['date'] 					= $query['date'];
			$data['data_solve'] 				= $query['data_solve'];
			$data['cpc_process'] 				= $query['cpc_process'];
			$data['updated_date'] 			= $query['updated_date'];
			$data['id_cashtransit_detail'] 	= $query['id_cashtransit_detail'];
			$data['run_number'] 				= $query['run_number'];
			$data['ctr_1_no'] 				= $query['ctr_1_no'];
			$data['ctr_2_no'] 				= $query['ctr_2_no'];
			$data['ctr_3_no'] 				= $query['ctr_3_no'];
			$data['ctr_4_no'] 				= $query['ctr_4_no'];
			$data['ctr_5_no'] 				= $query['ctr_5_no'];
			$data['cart_1_seal'] 				= explode(";", $query['cart_1_seal'])[0];
			$data['cart_2_seal'] 				= explode(";", $query['cart_2_seal'])[0];
			$data['cart_3_seal'] 				= explode(";", $query['cart_3_seal'])[0];
			$data['cart_4_seal'] 				= explode(";", $query['cart_4_seal'])[0];
			$data['cart_5_seal'] 				= explode(";", $query['cart_5_seal'])[0];
			$data['bag_seal'] 				= $query['bag_seal'];
			$data['bag_no'] 					= $query['bag_no'];
			$data['t_bag'] 					= $query['t_bag'];
			$data['data_seal'] 				= $query['data_seal'];
			$data['updated_date_cpc'] 		= $query['updated_date_cpc'];
			
			echo json_encode($data);
		} else {
			echo json_encode($query);
		}
	}
	
	function loading_get() {
		$id_user = $this->input->get('id_user');
		
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
						#AND IFNULL(c.sektor, c2.sektor)  = run_number 
						AND cashtransit_detail.data_solve != ''
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
						cashtransit.run_number!=''
						AND runsheet_operational.custody = '$id_user' 
						AND data_solve = '' AND unloading = '0' 
						AND cashtransit.action_date <= '".date('Y-m-d')."'
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
						cashtransit.run_number!=''
						AND runsheet_operational.custody = '$id_user' 
						AND cashtransit_detail.data_solve = '' 
						AND cashtransit_detail.loading = '0' 
						AND cashtransit.action_date <= '".date('Y-m-d')."'
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
			// if($r['state']=="ro_cit") {
				// $this->simpan_cit($r);
			// }  else if($r['state']=="ro_atm") {
				// $this->simpan_cr($r);
				// $this->update_seal($r);
			// }
		// }
	}
	
	function syncronize_post() {
		$data_plan = json_decode($_REQUEST['data_plan'], true);
		// echo "<pre>";
		// print_r($data_plan);
		
		foreach($data_plan as $r) {
			if($r['state']=="ro_cit") {
				$this->simpan_cit($r);
			} else if($r['state']=="ro_atm") {
				$this->simpan_cr($r);
				$this->update_seal($r);
			}
		}
	}
	
	function update_seal($r) {
		$data2 = array();
		$return_seal = json_decode($r['data_solve'], true)['bag_seal'];
		$t_bag = json_decode($r['data_solve'], true)['t_bag'];
		
		if($return_seal!="")	{ $data2['bag_seal_return']	= $return_seal; }
		if($t_bag!="")			{ $data2['t_bag']	= $t_bag; }
		
		if(!empty($data2)) {
			$this->db->query("UPDATE master_seal SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$return_seal'");
			$this->db->where('id', $r['id']);
			$update = $this->db->update('runsheet_cashprocessing', $data2);
		}
	}
	
	function simpan_cr($r) {
		$data = array();
		if($r['id']!="")			{ $data['id']			= $r['id']; }
		if($r['jam_cash_in']!="")	{ $data['jam_cash_in']	= date("Y-m-d H:i:s", strtotime($r['jam_cash_in'])); }
		if($r['foto_selfie']!="")	{ $data['foto_selfie']	= $r['foto_selfie']; }
		if($r['receipt_1']!="") 	{ $data['receipt_1']	= $r['receipt_1']; }
		if($r['receipt_2']!="") 	{ $data['receipt_2']	= $r['receipt_2']; }
		if($r['receipt_3']!="") 	{ $data['receipt_3']	= $r['receipt_3']; }
		if($r['receipt_4']!="") 	{ $data['receipt_4']	= $r['receipt_4']; }
		if($r['document_1']!="") 	{ $data['document_1']	= $r['document_1']; }
		if($r['document_2']!="") 	{ $data['document_2']	= $r['document_2']; }
		if($r['receipt_roll']!="") 	{ $data['receipt_roll']	= $r['receipt_roll']; }
		if($r['picture_mesin']!="") { $data['picture_mesin']	= $r['picture_mesin']; }
		if($r['picture_booth']!="") { $data['picture_booth']	= $r['picture_booth']; }
		if($r['data_solve']!="") 	{ $data['data_solve']	= $r['data_solve']; }
		if($r['loading']!="") 		{ $data['loading']	= $r['loading']; }
		
		$id_bank = $this->db->query("SELECT id_bank FROM cashtransit_detail WHERE id='".$r['id']."'")->row_array()['id_bank'];
		$prev = $this->db->query("SELECT * FROM (SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `data_solve`, `cpc_process`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < ".$r['id']." AND id_bank='$id_bank')")->result_array();
		
		echo $this->db->last_query()."\n";
		if(count($prev)==0) {
			$data['cpc_process'] = "pengisian";
		}
		
		if(!empty($data)) {
		    $this->jurnal_cancel($r);
			
			$this->db->where('id', $data['id']);
			$update = $this->db->update('cashtransit_detail', $data);
			echo $this->db->last_query()."\n";
		}
		
	}
	
	function simpan_cit($r) {
		$data = array();
		if($r['foto_selfie']!="")	{ $data['foto_selfie']	= $r['foto_selfie']; }
		if($r['receipt_1']!="") 	{ $data['receipt_1']	= $r['receipt_1']; }
		if($r['receipt_2']!="") 	{ $data['receipt_2']	= $r['receipt_2']; }
		if($r['receipt_3']!="") 	{ $data['receipt_3']	= $r['receipt_3']; }
		if($r['receipt_4']!="") 	{ $data['receipt_4']	= $r['receipt_4']; }
		if($r['document_1']!="") 	{ $data['document_1']	= $r['document_1']; }
		if($r['document_2']!="") 	{ $data['document_2']	= $r['document_2']; }
		if($r['receipt_roll']!="") 	{ $data['receipt_roll']	= $r['receipt_roll']; }
		if($r['picture_mesin']!="") { $data['picture_mesin']	= $r['picture_mesin']; }
		if($r['picture_booth']!="") { $data['picture_booth']	= $r['picture_booth']; }
		if($r['data_solve']!="") 	{ $data['data_solve']	= $r['data_solve']; }
		if($r['loading']!="") 		{ $data['loading']	= $r['loading']; }
		
		
		
		if(!empty($data)) {
			$this->jurnal_cit($r);
		
			$this->db->where('id', $r['id']);
			$update = $this->db->update('cashtransit_detail', $data);
			echo $this->db->last_query()."\n";
		}
	}
	
	function jurnal_cancel($r) {
        $keterangan = "cancel cassette";
        $sql_jurnal =  "SELECT id, count(*) as cnt FROM jurnal WHERE id_detail='".$r['id']."' AND keterangan='".$keterangan."'";
        $check_jurnal = $this->db->query($sql_jurnal)->row();
        
        $sql_cancel = "SELECT * FROM run_status_cancel WHERE id_detail='".$r['id']."' GROUP BY id_detail";
        $num = $this->db->query($sql_cancel)->num_rows();
        
        if($num>0) {
            $denom = $this->db->query("SELECT client.denom as denom FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.id='".$r['id']."'")->row()->denom;
            $query = $this->db->query("SELECT * FROM run_status_cancel WHERE id_detail='".$r['id']."'")->result_array();
            $hasil = 0;
            foreach($query as $x) {
                list($seal, $value) = explode(";", $x['cart_seal']);
                $hasil = $hasil + ($denom*$value);
            }
            
            if($check_jurnal->cnt==0) {
		        $data_jurnal['id_detail'] = $r['id'];
        		$data_jurnal['tanggal'] = explode(" ", $r['jam_cash_in'])[0];
        		$data_jurnal['keterangan'] = $keterangan;
        		$data_jurnal['posisi'] = "debit";
        		$data_jurnal['debit_100'] = ($denom=="100000" ? $hasil : 0);
        		$data_jurnal['debit_50'] = ($denom=="50000" ? $hasil : 0);
        		$data_jurnal['debit_20'] = 0;
        		
        		$this->db->insert('jurnal', $data_jurnal);
		    } else {
		        $data_jurnal['id_detail'] = $r['id'];
        		$data_jurnal['tanggal'] = explode(" ", $r['jam_cash_in'])[0];
        		$data_jurnal['keterangan'] = "$keterangan";
        		$data_jurnal['posisi'] = "debit";
        		$data_jurnal['debit_100'] = ($denom=="100000" ? $hasil : 0);
        		$data_jurnal['debit_50'] = ($denom=="50000" ? $hasil : 0);
        		$data_jurnal['debit_20'] = 0;
    			
    			$this->db->where("id", $check_jurnal->id);
    			$this->db->update("jurnal", $data_jurnal);
		    }
	    }
	}
	
	function jurnal_cit($r) {
		$num = $this->db->query("SELECT * FROM jurnal")->row();
		if(count($num)==0) {
			$keterangan = "saldo awal";
		} else {
			$keterangan = "cash supply";
		}
		
		$check = $this->db->query("SELECT id, count(*) as cnt FROM jurnal WHERE id_detail='".$r['id']."' AND keterangan='".$keterangan."'")->row();
		// print_r($check);
		if($check->cnt==0) {
			$data_jurnal['id_detail'] = $r['id'];
    		$data_jurnal['tanggal'] = date("Y-m-d");
    		$data_jurnal['keterangan'] = $keterangan;
    		$data_jurnal['posisi'] = "debit";
    		$data_jurnal['debit_100'] = trim(json_decode($r['data_solve'])->kertas_100k)*100000;
    		$data_jurnal['debit_50'] = trim(json_decode($r['data_solve'])->kertas_50k)*50000;
    		$data_jurnal['debit_20'] = trim(json_decode($r['data_solve'])->kertas_20k)*20000;
			
    		$this->db->insert('jurnal', $data_jurnal);
		} else {
			$data_jurnal['id_detail'] = $r['id'];
    		$data_jurnal['tanggal'] = date("Y-m-d");
    		$data_jurnal['keterangan'] = $keterangan;
    		$data_jurnal['posisi'] = "debit";
    		$data_jurnal['debit_100'] = trim(json_decode($r['data_solve'])->kertas_100k)*100000;
    		$data_jurnal['debit_50'] = trim(json_decode($r['data_solve'])->kertas_50k)*50000;
    		$data_jurnal['debit_20'] = trim(json_decode($r['data_solve'])->kertas_20k)*20000;
			
			$this->db->where("id", $check->id);
			$this->db->update("jurnal", $data_jurnal);
		}
	}
	
	function dashboard_ho_get() {
		$id_user = $this->input->get('id_user');
	
		$sql = "SELECT detail_ho.* FROM detail_ho 
			LEFT JOIN client_ho ON(client_ho.id_detail=detail_ho.id)
			WHERE detail_ho.custodian='$id_user' AND client_ho.status='onprogress' GROUP BY detail_ho.id
		";
		
		$query = $this->db->query($sql)->result_array();
		
		echo json_encode($query);
	}
	
	function data_handover_get() {
		$id_user = $this->input->get('id_user');
	
		$sql = "SELECT * FROM detail_ho 
			LEFT JOIN client_ho ON(client_ho.id_detail=detail_ho.id)
			WHERE detail_ho.custodian='$id_user' AND client_ho.status='onprogress'
		";
		
		$query = $this->db->query($sql)->result_array();
		
		echo json_encode($query);
	}
	
	function getmerkmesin_get() {
		$sql = "
			SELECT * FROM merk_mesin ORDER BY merk ASC
		";
		
		$query = $this->db->query($sql)->result_array();
		
		// echo "<pre>";
		// print_r($query);
		
		$list = array();
		$key=0;
		foreach($query as $r) {
			$list[$key]['id'] = $r['merk'];
			$list[$key]['merk'] = $r['merk'];
			
			$key++;
		}
		
		$result['data'] = $list;
		
		echo json_encode($result);
	}
}

