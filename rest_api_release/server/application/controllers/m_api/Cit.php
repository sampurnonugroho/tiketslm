<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Cit extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
        
        // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']); 
    }
	
	function index_get() {
	    echo "AAA";
	}
	
	function dashboard_ho_get() {
		$id_user = $this->input->get('id_user');
		
		$param = "WHERE (detail_ho.custodian = '$id_user' AND client_ho.status = 'onprogress')";

		$sql = "
			SELECT *, client_ho.alamat as lokasi, client_ho.id as id FROM client_ho 
			LEFT JOIN detail_ho ON (client_ho.id_detail=detail_ho.id)
			LEFT JOIN karyawan ON(client_ho.custodian=karyawan.nik) 
			$param
		";

		$query = $this->db->query($sql)->result_array();
		
		$list = array();
		// $list['count'] = count($query);
		$key = 0;
		foreach($query as $r) {
			$list[$key]['id'] = $r['id'];
			$list[$key]['wsid'] = $r['wsid'];

			$key++;
		}

		$data['data'] = $list;
		$data['count'] = count($query);

		echo json_encode($data);
	}

	function dashboard_get() {
		$id_user = $this->input->get('id_user');
		
		$param = "WHERE (`runsheet_operational`.`custodian_1` = '$id_user' AND data_solve = '' AND unloading = '0')";
	
		
		// $sql = "SELECT 
					// cashtransit.id, 
					// cashtransit_detail.id AS id_ticket, 
					// cashtransit_detail.id_bank, 
					// cashtransit_detail.state, 
					// cashtransit_detail.jenis, 
					// cashtransit_detail.ctr, 
					// cashtransit_detail.pcs_100000 AS s100k, 
					// cashtransit_detail.pcs_50000 AS s50k, 
					// cashtransit_detail.pcs_20000 AS s20k, 
					// cashtransit_detail.pcs_10000 AS s10k, 
					// cashtransit_detail.pcs_5000 AS s5k, 
					// cashtransit_detail.pcs_2000 AS s2k, 
					// cashtransit_detail.pcs_1000 AS s1k, 
					// cashtransit_detail.pcs_coin AS scoink, 
					// cashtransit_detail.total, 
					// IFNULL((client.sektor), client_cit.sektor) AS run_number,
					// IFNULL((client.sektor), client_cit.sektor) AS ga,
					// master_branch.name, 
					// client.bank, 
					// client.type, 
					// client.lokasi, 
					// client.vendor AS brand, 
					// client.type_mesin AS model, 
					// client_cit.nama_client, 
					// client_cit.lokasi AS lokasi_b, 
					// runsheet_cashprocessing.cart_1_seal, 
					// runsheet_cashprocessing.cart_2_seal, 
					// runsheet_cashprocessing.cart_3_seal, 
					// runsheet_cashprocessing.cart_4_seal, 
					// runsheet_cashprocessing.cart_5_seal,
					// runsheet_cashprocessing.total AS nominal,
					// runsheet_cashprocessing.pcs_100000, 
					// runsheet_cashprocessing.pcs_50000, 
					// runsheet_cashprocessing.pcs_20000, 
					// runsheet_cashprocessing.pcs_10000, 
					// runsheet_cashprocessing.pcs_5000, 
					// runsheet_cashprocessing.pcs_2000, 
					// runsheet_cashprocessing.pcs_1000, 
					// runsheet_cashprocessing.pcs_coin, 
					// runsheet_cashprocessing.bag_seal, 
					// runsheet_cashprocessing.bag_no, 
					// runsheet_security.police_number, 
					// runsheet_security.km_status, 
					// runsheet_operational.custodian_1, 
					// runsheet_operational.custodian_2, 
					// runsheet_security.security_1, 
					// runsheet_security.security_2, 
					// runsheet_logistic.data 
				// FROM cashtransit_detail
					// LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
					// LEFT JOIN master_branch ON (cashtransit.branch=master_branch.id)
					// LEFT JOIN client ON (cashtransit_detail.id_bank=client.id)
					// LEFT JOIN client_cit ON(cashtransit_detail.id_pengirim=client_cit.id)
					// LEFT JOIN runsheet_operational ON((cashtransit_detail.id_cashtransit = runsheet_operational.id_cashtransit) AND (runsheet_operational.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor))
					// LEFT JOIN runsheet_logistic ON((cashtransit_detail.id_cashtransit = runsheet_logistic.id_cashtransit) AND (runsheet_logistic.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor))
					// LEFT JOIN runsheet_security ON((cashtransit_detail.id_cashtransit = runsheet_security.id_cashtransit) AND (runsheet_security.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor))
					// LEFT JOIN runsheet_cashprocessing ON((cashtransit_detail.id = runsheet_cashprocessing.id) AND (runsheet_cashprocessing.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor)) 
					// $param
				// ";
					
		$sql = "
			SELECT
				cashtransit_detail.*,
				cashtransit.*,
			#	client.*,
			#	client_cit.*,
				master_branch.*,
				runsheet_operational.*,
				runsheet_security.*,
				runsheet_cashprocessing.petty_cash,
				cashtransit.run_number AS run_number,
				(SELECT SUM(total) AS petty_cash FROM runsheet_cashprocessing WHERE id_cashtransit=cashtransit.id_cashtransit) as petty_cash
					FROM
						(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, data_solve, cpc_process, unloading FROM cashtransit_detail) AS cashtransit_detail
							LEFT JOIN
								(SELECT id as id_cashtransit, date, branch, run_number FROM cashtransit) AS cashtransit 
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
						runsheet_operational.custody = '$id_user' AND (data_solve = '' OR (cpc_process = '' OR cpc_process = 'pengisian')) AND unloading = '0'
						
					GROUP BY cashtransit.id_cashtransit
		";

					
		$query = $this->db->query($sql)->result_array();
		// echo "<pre>";
		// print_r($query);
		
		$list = array();
		
		$i = 0;
		foreach($query as $r) {
			// $petty = $petty + intval($r['petty_cash']);
			
			$list[$i]['id'] = $r['id_cashtransit'];
			$list[$i]['date'] = date("d-m-Y", strtotime($r['date']));
			$list[$i]['branch'] = $r['name_branch'];
			$list[$i]['run_number'] = $r['run_number'];
			$list[$i]['police_number'] = $r['police_number'];
			// $list[$i]['petty_cash'] = number_format($r['petty_cash'], 0, ",", ",");
			$list[$i]['petty_cash'] = $r['petty_cash'];
			
			$i++;
		}
		
		// $list['petty_cash'] = number_format($petty, 0, ",", ",");
		
		$data['data'] = $list;
		$data['count'] = count($query);
		
		echo json_encode($data);
	}
	
	function loading_get() {
		$id_cashtransit = $this->input->get('id_cashtransit');
						
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
							WHERE 
							cashtransit.id='".$id_cashtransit."'
							GROUP BY run_number, cashtransit.id
							ORDER BY D.id DESC";
						
		$query = $this->db->query($sql)->row_array();
		
		echo json_encode($query);
	}
	
	function unloading_runsheet_get() {
		$id_user = $this->input->get('id_user');
		
		$param = "WHERE (`runsheet_operational`.`custodian_1` = '$id_user' AND 
					(`cashtransit_detail`.`cpc_process` = '' OR `cashtransit_detail`.`cpc_process` = 'pengisian') AND 
					`cashtransit_detail`.`unloading` = '0')";
					
		$sql = "SELECT 
					cashtransit.id, 
					cashtransit_detail.id AS id_ticket, 
					cashtransit_detail.id_bank, 
					cashtransit_detail.state, 
					cashtransit_detail.jenis, 
					cashtransit_detail.ctr, 
					cashtransit_detail.pcs_100000 AS s100k, 
					cashtransit_detail.pcs_50000 AS s50k, 
					cashtransit_detail.pcs_20000 AS s20k, 
					cashtransit_detail.pcs_10000 AS s10k, 
					cashtransit_detail.pcs_5000 AS s5k, 
					cashtransit_detail.pcs_2000 AS s2k, 
					cashtransit_detail.pcs_1000 AS s1k, 
					cashtransit_detail.pcs_coin AS scoink, 
					cashtransit_detail.total, 
					cashtransit_detail.cpc_process, 
					IFNULL((client.sektor), client_cit.sektor) AS ga,
					master_branch.name, 
					client.bank, 
					client.type, 
					client.lokasi, 
					client.vendor AS brand, 
					client.type_mesin AS model, 
					client_cit.nama_client, 
					client_cit.lokasi AS lokasi_b, 
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
					runsheet_security.police_number, 
					#runsheet_security.km_status, 
					runsheet_operational.custodian_1, 
					runsheet_operational.custodian_2, 
					runsheet_security.security_1, 
					runsheet_security.security_2, 
					runsheet_logistic.data 
				FROM cashtransit_detail
					LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
					LEFT JOIN master_branch ON (cashtransit.branch=master_branch.id)
					LEFT JOIN client ON (cashtransit_detail.id_bank=client.id)
					LEFT JOIN client_cit ON(cashtransit_detail.id_pengirim=client_cit.id)
					LEFT JOIN runsheet_operational ON((cashtransit_detail.id_cashtransit = runsheet_operational.id_cashtransit) AND (runsheet_operational.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor))
					LEFT JOIN runsheet_logistic ON((cashtransit_detail.id_cashtransit = runsheet_logistic.id_cashtransit) AND (runsheet_logistic.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor))
					LEFT JOIN runsheet_security ON((cashtransit_detail.id_cashtransit = runsheet_security.id_cashtransit) AND (runsheet_security.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor))
					LEFT JOIN runsheet_cashprocessing ON((cashtransit_detail.id = runsheet_cashprocessing.id) AND (runsheet_cashprocessing.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor)) 
					$param
				";
						
		$query = $this->db->query($sql)->result_array();
		
		$list = array();
		$key=0;
		foreach($query as $r) {
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
			}  else {
				$r['pcs_100000'] = $r['pcs_100000']*$r['ctr'];
				$r['pcs_50000'] = $r['pcs_50000']*$r['ctr'];
				$r['pcs_20000'] = $r['pcs_20000']*$r['ctr'];
				$r['pcs_10000'] = $r['pcs_10000']*$r['ctr'];
				$r['pcs_5000'] = $r['pcs_5000']*$r['ctr'];
				$r['pcs_1000'] = $r['pcs_1000']*$r['ctr'];
			}
			
			$list[$key]['id1'] = $r['id_ticket'];
			$list[$key]['id'] = "T".date("Ymd").sprintf("%04d", $r['id_ticket']);
			$list[$key]['id_bank'] = (!empty($r['id_bank']) ? $r['id_bank'] : $r['id']);
			$list[$key]['state'] = $r['state'];
			$list[$key]['ga'] = $r['ga'];
			$list[$key]['branch'] = $r['name'];
			$list[$key]['bank'] = (!empty($r['bank']) ? $r['bank'] : $r['nama_client']);
			$list[$key]['act'] = (!empty($r['type']) ? $r['type'] : '-');
			$list[$key]['brand'] = (!empty($r['brand']) ? $r['brand'] : '-');
			$list[$key]['model'] = (!empty($r['model']) ? $r['model'] : '-');
			$list[$key]['lokasi'] = (!empty($r['lokasi']) ? $r['lokasi'] : $r['lokasi_b']);
			$list[$key]['total'] = number_format($r['nominal'], 0, ",", ",");
			
			$list[$key]['ctr'] = $r['ctr'];
			$list[$key]['nominal'] = (!empty($r['nominal']) ? number_format($r['nominal'], 0, ",", ",") : number_format($r['total'], 0, ",", ","));
			
			$list[$key]['police_number'] = $r['police_number'];
			$list[$key]['km_status'] = $r['km_status'];
			$list[$key]['petty_cash'] = number_format($r['nominal'], 0, ",", ",");
			
			$list[$key]['custodian_1'] = !empty($r['custodian_1']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_1'])->limit(1)->get()->row()->nama : "N/A";
			$list[$key]['custodian_2'] = !empty($r['custodian_2']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_2'])->limit(1)->get()->row()->nama : "N/A";
			$list[$key]['security_1'] = $r['security_1'];
			$list[$key]['security_2'] = $r['security_2'];
			$list[$key]['s100k'] = number_format($r['pcs_100000'], 0, ",", ",");
			$list[$key]['s50k'] = number_format($r['pcs_50000'], 0, ",", ",");
			$list[$key]['s20k'] = number_format($r['pcs_20000'], 0, ",", ",");
			$list[$key]['s10k'] = number_format($r['pcs_10000'], 0, ",", ",");
			$list[$key]['s5k'] = number_format($r['pcs_5000'], 0, ",", ",");
			$list[$key]['s2k'] = number_format($r['pcs_2000'], 0, ",", ",");
			$list[$key]['s1k'] = number_format($r['pcs_1000'], 0, ",", ",");
			$list[$key]['coin'] = number_format($r['pcs_coin'], 0, ",", ",");
			$list[$key]['cpc_process'] = $r['cpc_process'];
			$list[$key]['bag_seal'] = $r['bag_seal'];
			$list[$key]['bag_seal_return'] = $r['bag_seal_return'];
			$list[$key]['bag_no'] = $r['bag_no'];
			
			$key++;
		}
		
		
		$result['data'] = $list;
		
		echo json_encode($list);
	}
	
	function loading_runsheet_get() {
		$id_cashtransit = $this->input->get('id_cashtransit');
// 		$id_user = "K0013";
		
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
									#AND (runsheet_operational.run_number = IFNULL(client.sektor, client_cit.sektor))
								)
						LEFT JOIN 
							(SELECT * FROM runsheet_security) AS runsheet_security
								ON (
									(runsheet_security.id_cashtransit = cashtransit_detail.id_cashtransit) 
									#AND (runsheet_security.run_number = IFNULL(client.sektor, client_cit.sektor))
								)
						LEFT JOIN 
							(SELECT *, id as id_runsheet_cashprocessing, total as petty_cash FROM runsheet_cashprocessing) AS runsheet_cashprocessing
								ON (
									(runsheet_cashprocessing.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
									(runsheet_cashprocessing.id_runsheet_cashprocessing = cashtransit_detail.id)
								)
								
					WHERE
						cashtransit.id = '$id_cashtransit' AND 
						cashtransit_detail.data_solve = '' AND
						cashtransit_detail.loading = '0'
		";
// 		echo $sql;
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
				// $list[$key]['id_bank'] = (!empty($r['id_bank']) ? $r['id_bank'] : $r['id']);
				$list[$key]['state'] = $r['state'];
				$list[$key]['ga'] = $r['run_number'];
				$list[$key]['branch'] = $r['branch_name'];
				$list[$key]['bank'] = (!empty($r['bank']) ? $r['bank'] : $r['nama_client']);
				$list[$key]['act'] = (!empty($r['c_type']) ? $r['c_type'] : '-');
				$list[$key]['brand'] = (!empty($r['c_brand']) ? $r['c_brand'] : '-');
				$list[$key]['model'] = (!empty($r['c_model']) ? $r['c_model'] : '-');
				$list[$key]['lokasi'] = (!empty($r['c_lokasi']) ? $r['c_lokasi'] : '-');;
				// $list[$key]['total'] = number_format($r['total'], 0, ",", ",");
				
				// $list[$key]['ctr'] = $r['ctr'];
				// $list[$key]['nominal'] = (!empty($r['petty_cash']) ? number_format($r['petty_cash'], 0, ",", ",") : number_format($r['total'], 0, ",", ","));
				
				$list[$key]['police_number'] = $r['police_number'];
				$list[$key]['km_status'] = 0;
				$list[$key]['petty_cash'] = number_format($r['petty_cash'], 0, ",", ",");
				
				$list[$key]['custodian_1'] = !empty($r['custodian_1']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_1'])->limit(1)->get()->row()->nama : "N/A";
				$list[$key]['custodian_2'] = !empty($r['custodian_2']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_2'])->limit(1)->get()->row()->nama : "N/A";
				$list[$key]['security_1'] = $r['security_1'];
				$list[$key]['security_2'] = $r['security_2'];
				// $list[$key]['s100k'] = number_format($r['pcs_100000'], 0, ",", ",");
				// $list[$key]['s50k'] = number_format($r['pcs_50000'], 0, ",", ",");
				// $list[$key]['s20k'] = number_format($r['pcs_20000'], 0, ",", ",");
				// $list[$key]['s10k'] = number_format($r['pcs_10000'], 0, ",", ",");
				// $list[$key]['s5k'] = number_format($r['pcs_5000'], 0, ",", ",");
				// $list[$key]['s2k'] = number_format($r['pcs_2000'], 0, ",", ",");
				// $list[$key]['s1k'] = number_format($r['pcs_1000'], 0, ",", ",");
				// $list[$key]['coin'] = number_format($r['pcs_coin'], 0, ",", ",");
				$list[$key]['bag_seal'] = $r['bag_seal'];
				$list[$key]['bag_no'] = $r['bag_no'];
			} else if($r['state']=="ro_cit") {
				$list[$key]['id1'] = $r['id'];
				$list[$key]['id'] = "T".date("Ymd").sprintf("%04d", $r['id']);
				$list[$key]['state'] = $r['state'];
				$list[$key]['ga'] = $r['run_number'];
				$list[$key]['branch'] = $r['branch_name'];
				$list[$key]['metode'] = ($r['metode']=="CP" ? "CASH PICKUP" : ($r['metode']=="CD" ? "CASH DELIVERY" : ""));
				$list[$key]['jenis'] = $r['jenis'];
				$list[$key]['lokasi'] = $r['lokasi'];
				
				$list[$key]['pengirim'] = ($r['id_pengirim']==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$r['id_pengirim']."'")->row()->nama_client);
				$list[$key]['penerima'] = ($r['id_penerima']==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$r['id_penerima']."'")->row()->nama_client);
				
				$list[$key]['police_number'] = $r['police_number'];
				// $list[$key]['km_status'] = $r['km_status'];
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
		
		// print_r($query);
		// print_r($list);
		
		echo json_encode($list);
	}
	
	function loading_runsheet2_get() {
		$id_user = $this->input->get('id_user');
		
		$param = "WHERE (`runsheet_operational`.`custodian_1` = '$id_user' AND 
					`cashtransit_detail`.`data_solve` = '' AND 
					`cashtransit_detail`.`loading` = '0' AND 
					`cashtransit_detail`.`state` = 'ro_atm')";
	
		
		$sql = "SELECT 
					cashtransit.id, 
					cashtransit_detail.id AS id_ticket, 
					cashtransit_detail.id_bank, 
					cashtransit_detail.state, 
					cashtransit_detail.jenis, 
					cashtransit_detail.ctr, 
					cashtransit_detail.pcs_100000 AS s100k, 
					cashtransit_detail.pcs_50000 AS s50k, 
					cashtransit_detail.pcs_20000 AS s20k, 
					cashtransit_detail.pcs_10000 AS s10k, 
					cashtransit_detail.pcs_5000 AS s5k, 
					cashtransit_detail.pcs_2000 AS s2k, 
					cashtransit_detail.pcs_1000 AS s1k, 
					cashtransit_detail.pcs_coin AS scoink, 
					cashtransit_detail.total, 
					IFNULL((client.sektor), client_cit.sektor) AS ga,
					master_branch.name, 
					client.bank, 
					client.type, 
					client.lokasi, 
					client.vendor AS brand, 
					client.type_mesin AS model, 
					client_cit.nama_client, 
					client_cit.lokasi AS lokasi_b, 
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
					runsheet_cashprocessing.bag_no, 
					runsheet_security.police_number, 
					runsheet_security.km_status, 
					runsheet_operational.custodian_1, 
					runsheet_operational.custodian_2, 
					runsheet_security.security_1, 
					runsheet_security.security_2, 
					runsheet_logistic.data 
				FROM cashtransit_detail
					LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
					LEFT JOIN master_branch ON (cashtransit.branch=master_branch.id)
					LEFT JOIN client ON (cashtransit_detail.id_bank=client.id)
					LEFT JOIN client_cit ON(cashtransit_detail.id_pengirim=client_cit.id)
					LEFT JOIN runsheet_operational ON((cashtransit_detail.id_cashtransit = runsheet_operational.id_cashtransit) AND (runsheet_operational.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor))
					LEFT JOIN runsheet_logistic ON((cashtransit_detail.id_cashtransit = runsheet_logistic.id_cashtransit) AND (runsheet_logistic.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor))
					LEFT JOIN runsheet_security ON((cashtransit_detail.id_cashtransit = runsheet_security.id_cashtransit) AND (runsheet_security.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor))
					LEFT JOIN runsheet_cashprocessing ON((cashtransit_detail.id = runsheet_cashprocessing.id) AND (runsheet_cashprocessing.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor)) 
					$param
				";
						
		$query = $this->db->query($sql)->result_array();
		
		$list = array();
		$key=0;
		foreach($query as $r) {
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
			}  else {
				$r['pcs_100000'] = $r['pcs_100000']*$r['ctr'];
				$r['pcs_50000'] = $r['pcs_50000']*$r['ctr'];
				$r['pcs_20000'] = $r['pcs_20000']*$r['ctr'];
				$r['pcs_10000'] = $r['pcs_10000']*$r['ctr'];
				$r['pcs_5000'] = $r['pcs_5000']*$r['ctr'];
				$r['pcs_1000'] = $r['pcs_1000']*$r['ctr'];
			}
			
			$list[$key]['id1'] = $r['id_ticket'];
			$list[$key]['id'] = "T".date("Ymd").sprintf("%04d", $r['id_ticket']);
			$list[$key]['id_bank'] = (!empty($r['id_bank']) ? $r['id_bank'] : $r['id']);
			$list[$key]['state'] = $r['state'];
			$list[$key]['ga'] = $r['ga'];
			$list[$key]['branch'] = $r['name'];
			$list[$key]['bank'] = (!empty($r['bank']) ? $r['bank'] : $r['nama_client']);
			$list[$key]['act'] = (!empty($r['type']) ? $r['type'] : '-');
			$list[$key]['brand'] = (!empty($r['brand']) ? $r['brand'] : '-');
			$list[$key]['model'] = (!empty($r['model']) ? $r['model'] : '-');
			$list[$key]['lokasi'] = (!empty($r['lokasi']) ? $r['lokasi'] : $r['lokasi_b']);
			$list[$key]['total'] = number_format($r['nominal'], 0, ",", ",");
			
			$list[$key]['ctr'] = $r['ctr'];
			$list[$key]['nominal'] = (!empty($r['nominal']) ? number_format($r['nominal'], 0, ",", ",") : number_format($r['total'], 0, ",", ","));
			
			$list[$key]['police_number'] = $r['police_number'];
			$list[$key]['km_status'] = $r['km_status'];
			$list[$key]['petty_cash'] = number_format($r['nominal'], 0, ",", ",");
			
			$list[$key]['custodian_1'] = !empty($r['custodian_1']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_1'])->limit(1)->get()->row()->nama : "N/A";
			$list[$key]['custodian_2'] = !empty($r['custodian_2']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_2'])->limit(1)->get()->row()->nama : "N/A";
			$list[$key]['security_1'] = $r['security_1'];
			$list[$key]['security_2'] = $r['security_2'];
			$list[$key]['s100k'] = number_format($r['pcs_100000'], 0, ",", ",");
			$list[$key]['s50k'] = number_format($r['pcs_50000'], 0, ",", ",");
			$list[$key]['s20k'] = number_format($r['pcs_20000'], 0, ",", ",");
			$list[$key]['s10k'] = number_format($r['pcs_10000'], 0, ",", ",");
			$list[$key]['s5k'] = number_format($r['pcs_5000'], 0, ",", ",");
			$list[$key]['s2k'] = number_format($r['pcs_2000'], 0, ",", ",");
			$list[$key]['s1k'] = number_format($r['pcs_1000'], 0, ",", ",");
			$list[$key]['coin'] = number_format($r['pcs_coin'], 0, ",", ",");
			$list[$key]['bag_seal'] = $r['bag_seal'];
			$list[$key]['bag_no'] = $r['bag_no'];
			
			$key++;
		}
		
		
		$result['data'] = $list;
		
		echo json_encode($list);
	}
	
	function loading_runsheet_cit_get() {
		$id_user = $this->input->get('id_user');
		
		$param = "WHERE (`runsheet_operational`.`custodian_1` = '$id_user' AND 
					`cashtransit_detail`.`data_solve` = '' AND 
					`cashtransit_detail`.`loading` = '0' AND 
					`cashtransit_detail`.`state` = 'ro_cit')";
	
		
		$sql = "SELECT 
					master_branch.name as branch_name, 
					cashtransit_detail.id as id_ct, 
					cashtransit_detail.id_cashtransit, 
					cashtransit_detail.id_bank, 
					cashtransit_detail.id_penerima, 
					cashtransit_detail.id_pengirim, 
					cashtransit_detail.metode, 
					cashtransit_detail.jenis, 
					cashtransit_detail.state as state,
					cashtransit_detail.ctr as ctr2, 
					cashtransit_detail.pcs_100000 as pcs_100000, 
					cashtransit_detail.pcs_50000 as pcs_50000, 
					cashtransit_detail.pcs_20000 as pcs_20000, 
					cashtransit_detail.pcs_10000 as pcs_10000, 
					cashtransit_detail.pcs_5000 as pcs_5000, 
					cashtransit_detail.pcs_2000 as pcs_2000, 
					cashtransit_detail.pcs_1000 as pcs_1000, 
					cashtransit_detail.pcs_coin as pcs_coin, 
					cashtransit_detail.detail_uang as detail_uang,
					cashtransit_detail.total as total, 
					client_cit.cabang as branchz,
					client_cit.lokasi,
					client_cit.sektor,
					cashtransit_detail.jenis,
					cashtransit_detail.ctr as ctr,
					runsheet_cashprocessing.pcs_100000 as s100k,
					runsheet_cashprocessing.pcs_50000 as s50k,
					runsheet_cashprocessing.pcs_20000 as s20k,
					runsheet_cashprocessing.pcs_10000 as s10k,
					runsheet_cashprocessing.pcs_5000 as s5k,
					runsheet_cashprocessing.pcs_2000 as s2k,
					runsheet_cashprocessing.pcs_1000 as s1k,
					runsheet_cashprocessing.pcs_coin as coin,
					runsheet_cashprocessing.total as nominal,
					runsheet_cashprocessing.ctr_1_no,
					runsheet_cashprocessing.ctr_2_no,
					runsheet_cashprocessing.ctr_3_no,
					runsheet_cashprocessing.ctr_4_no,
					runsheet_cashprocessing.ctr_5_no,
					runsheet_cashprocessing.cart_1_seal,
					runsheet_cashprocessing.cart_2_seal,
					runsheet_cashprocessing.cart_3_seal,
					runsheet_cashprocessing.cart_4_seal,
					runsheet_cashprocessing.cart_5_seal,
					runsheet_cashprocessing.divert,
					runsheet_cashprocessing.bag_seal,
					runsheet_cashprocessing.bag_no
				from cashtransit_detail 
					LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
					LEFT JOIN master_branch ON (cashtransit.branch=master_branch.id)
					LEFT JOIN client_cit on(cashtransit_detail.id_pengirim=client_cit.id)  
					LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
					LEFT JOIN runsheet_operational ON(cashtransit_detail.id_cashtransit = runsheet_operational.id_cashtransit)
					$param
				";
						
		$query = $this->db->query($sql)->result();
		
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			$detailuang = json_decode($row->detail_uang, true);
			
			$items[$i]['id1'] = $row->id_ct;
			$items[$i]['id'] = "T".date("Ymd").sprintf("%04d", $row->id_ct);
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['state'] = $row->state;
			$items[$i]['branch'] = $row->branch_name;
			$items[$i]['metode'] = ($row->metode=="CP" ? "CASH PICKUP" : ($row->metode=="CD" ? "CASH DELIVERY" : ""));
			$items[$i]['jenis'] = $row->jenis;
			$items[$i]['lokasi'] = $row->lokasi;
			if($row->id_pengirim!=0){
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_pengirim."'")->row()->sektor;
			} else {
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_penerima."'")->row()->sektor;
			}
			$items[$i]['pengirim'] = ($row->id_pengirim==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_pengirim."'")->row()->nama_client);
			$items[$i]['penerima'] = ($row->id_penerima==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_penerima."'")->row()->nama_client);
			$items[$i]['total'] = $row->total;
			$items[$i]['bag_seal'] = $row->bag_seal;
			$items[$i]['bag_no'] = $row->bag_no;
			$i++;
		}
		
		echo json_encode($items);
		// echo "<pre>";
		// echo print_r($items);
	}
	
	function get_prev_cr_get() {
	    $id_bank = $this->input->get('id_bank');
		$id_ticket = $this->input->get('id_ticket');
	    
	    // $sql = "SELECT * FROM (SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `data_solve`, `cpc_process`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id_ticket)";
		
	    $sql = "SELECT * FROM (SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `data_solve`, `cpc_process`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id_ticket AND id_bank='$id_bank' AND cashtransit_detail.data_solve!='batal')";
		
	    
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
			$data['bag_seal_return'] 			= $query['bag_seal_return'];
			$data['bag_no'] 					= $query['bag_no'];
			$data['t_bag'] 					= $query['t_bag'];
			$data['data_seal'] 				= $query['data_seal'];
			$data['updated_date_cpc'] 		= $query['updated_date_cpc'];
			
			echo json_encode($data);
		} else {
			echo json_encode($query);
		}
	}
	
	function getdelivery_get() {
		$id_user = $this->input->get('id_user');
		$id_ga = $this->input->get('id_ga');
		// `runsheet_operational`.`custodian_1` = '$id_user' AND 
		$param = "WHERE (
					`runsheet_operational`.`custodian_1` = '$id_user' AND 
					`cashtransit_detail`.`state` = 'ro_cit' AND 
					`cashtransit_detail`.`data_solve` = '' AND
					`runsheet_cashprocessing`.detail_uang IS NOT NULL
				)";
		
		$sql = "SELECT 
						*,
						cashtransit_detail.id as id1
					FROM cashtransit_detail
						LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
						LEFT JOIN master_branch ON (cashtransit.branch=master_branch.id)
						LEFT JOIN 
							(SELECT id, sektor FROM client) AS client ON(cashtransit_detail.id_bank=client.id) 
						LEFT JOIN 
							(SELECT id, sektor FROM client_cit) AS client_cit ON(IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
						LEFT JOIN runsheet_operational ON (cashtransit_detail.id_cashtransit = runsheet_operational.id_cashtransit)
						LEFT JOIN runsheet_cashprocessing
						ON (
							(runsheet_cashprocessing.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
							(runsheet_cashprocessing.id = cashtransit_detail.id)
						)
						$param
					";
					
		$query = $this->db->query($sql)->result_array();
		
		// echo "<pre>";
		// print_r($query);
		$list = array();
		$key=0;
		foreach($query as $r) {
			$list[$key]['id1'] = $r['id1'];
			$list[$key]['id'] = "T".date("Ymd").sprintf("%04d", $r['id1']);
			$list[$key]['metode'] = $r['metode'];
			$list[$key]['jenis'] = $r['jenis'];
			$list[$key]['pengirim'] = ($r['id_pengirim']==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$r['id_pengirim']."'")->row()->nama_client);
			$list[$key]['penerima'] = ($r['id_penerima']==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$r['id_penerima']."'")->row()->nama_client);
			$list[$key]['runsheet'] = $id_ga;
			$list[$key]['branch'] = $r['name'];
			$list[$key]['act'] = $r['jenis'];
			$list[$key]['detail_uang'] = $r['detail_uang'];
			$list[$key]['total'] = number_format($r['total'], 0, ",", ",");
			
			$key++;
		}
		
		$result['data'] = $list;
		
		echo json_encode($result);
	}
	
	function getdelivery_detail_get() {
		$id_user = $this->input->get('id_user');
		$id_ticket = $this->input->get('id_ticket');
		$id_ga = $this->input->get('id_ga');
		
		$param = "WHERE (`runsheet_operational`.`custodian_1` = '$id_user' AND 
					`cashtransit_detail`.`id` = '$id_ticket' AND 
					`cashtransit_detail`.`state` = 'ro_cit' AND 
					`cashtransit_detail`.`data_solve` = '')";
		
		$sql = "SELECT 
						*,
						cashtransit_detail.id as id1
					FROM cashtransit_detail
						LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
						LEFT JOIN master_branch ON (cashtransit.branch=master_branch.id)
						LEFT JOIN runsheet_operational ON (cashtransit_detail.id_cashtransit = runsheet_operational.id_cashtransit)
						LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
						$param
					";
					
		$query = $this->db->query($sql)->result_array();
		
		// echo "<pre>";
		// print_r($query);
		
		$list = array();
		$key=0;
		foreach($query as $r) {
			$list[$key]['id1'] = $r['id1'];
			$list[$key]['id'] = "T".date("Ymd").sprintf("%04d", $r['id']);
			$list[$key]['metode'] = $r['metode'];
			$list[$key]['jenis'] = $r['jenis'];
			$list[$key]['pengirim'] = ($r['id_pengirim']==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$r['id_pengirim']."'")->row()->nama_client);
			$list[$key]['penerima'] = ($r['id_penerima']==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$r['id_penerima']."'")->row()->nama_client);
			$list[$key]['runsheet'] = $id_ga;
			$list[$key]['branch'] = $r['name'];
			$list[$key]['act'] = $r['jenis'];
			$list[$key]['bag_seal'] = $r['bag_seal'];
			$list[$key]['bag_no'] = $r['bag_no'];
			$list[$key]['total'] = $r['total'];
			$list[$key]['detail_uang'] = $r['detail_uang'];
			
			$key++;
		}
		
		$result['data'] = $list;
		
		echo json_encode($result);
	}
	
	function gethandover_get() {
		$id_user = $this->input->get('id_user');
		
		$param = "WHERE (detail_ho.custodian = '$id_user' AND client_ho.status = 'onprogress')";

		$sql = "
			SELECT *, client_ho.alamat as lokasi, client_ho.id as id FROM client_ho 
			LEFT JOIN detail_ho ON (client_ho.id_detail=detail_ho.id)
			LEFT JOIN karyawan ON(client_ho.custodian=karyawan.nik) 
			$param
		";
		
		$query = $this->db->query($sql)->result_array();
		
		// echo "<pre>";
		// print_r($query);
		
		$list = array();
		$key=0;
		foreach($query as $r) {
			$detail = json_decode($r['detail'], true);
			
			$list[$key]['id'] = $r['id'];
			$list[$key]['type'] = strtoupper($detail['type']);
			$list[$key]['lokasi'] = $this->custom_echo($r['lokasi'], 20);
			$list[$key]['lokasi_full'] = $r['lokasi'];
			$list[$key]['bank'] = $r['bank'];
			$list[$key]['wsid'] = $r['wsid'];
			$list[$key]['ctr'] = $r['ctr'];
			$list[$key]['reject'] = $r['reject'];
			$list[$key]['status'] = ucfirst($r['status']);
			
			$key++;
		}
		
		$result['data'] = $list;
		
		echo json_encode($result);
	}
	
	function custom_echo($x, $length) {
		if(strlen($x)<=$length) {
			return $x;
		}
		else {
			$y=substr($x,0,$length) . '...';
			return $y;
		}
	}
	
	function getreplenish_get() {
		$id_cashtransit = $this->input->get('id_cashtransit');
		$id_user = $this->input->get('id_user');
		$id_ga = $this->input->get('id_ga');
		
		$param = "WHERE 
					(
						`cashtransit`.`id` = '$id_cashtransit' AND 
						`cashtransit_detail`.`state` = 'ro_atm' AND 
						`cashtransit_detail`.`data_solve` = '' AND
						`cashtransit_detail`.`id` IN (SELECT id FROM runsheet_cashprocessing)
					)";
	
			
		$sql = "SELECT 
						cashtransit.id, 
						cashtransit_detail.id AS id_ticket, 
						cashtransit_detail.id_bank, 
						cashtransit_detail.jenis, 
						cashtransit_detail.ctr, 
						cashtransit_detail.total, 
						client.sektor AS ga, 
						master_branch.name, 
						client.wsid, 
						client.bank, 
						client.type, 
						client.lokasi, 
						client.vendor AS brand, 
						client.type_mesin AS model, 
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
						runsheet_cashprocessing.bag_no, 
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
						LEFT JOIN runsheet_logistic ON (cashtransit_detail.id_cashtransit=runsheet_logistic.id_cashtransit && runsheet_logistic.run_number=client.sektor)
						LEFT JOIN runsheet_security ON (cashtransit_detail.id_cashtransit=runsheet_security.id_cashtransit && runsheet_security.run_number=client.sektor)
						LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
						LEFT JOIN vehicle ON (vehicle.police_number=runsheet_security.police_number)
						$param
					";
		// echo $sql;
		$query = $this->db->query($sql)->result_array();
		
		$list = array();
		$key=0;
		foreach($query as $r) {
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
			}  else {
				$r['pcs_100000'] = $r['pcs_100000']*$r['ctr'];
				$r['pcs_50000'] = $r['pcs_50000']*$r['ctr'];
				$r['pcs_20000'] = $r['pcs_20000']*$r['ctr'];
				$r['pcs_10000'] = $r['pcs_10000']*$r['ctr'];
				$r['pcs_5000'] = $r['pcs_5000']*$r['ctr'];
				$r['pcs_1000'] = $r['pcs_1000']*$r['ctr'];
			}
			
			$list[$key]['id1'] = $r['id_ticket'];
			$list[$key]['id'] = "T".date("Ymd").sprintf("%04d", $r['id_ticket']);
			$list[$key]['id_bank'] = $r['wsid'];
			$list[$key]['ga'] = $r['ga'];
			$list[$key]['branch'] = $r['name'];
			$list[$key]['bank'] = $r['bank'];
			$list[$key]['act'] = $r['type'];
			$list[$key]['brand'] = $r['brand'];
			$list[$key]['model'] = $r['model'];
			$list[$key]['lokasi'] = $r['lokasi'];
			$list[$key]['nominal'] = number_format($r['nominal'], 0, ",", ",");
			
			$list[$key]['ctr'] = $r['ctr'];
			$list[$key]['total'] = $r['nominal'];
			
			$list[$key]['police_number'] = $r['police_number'];
			$list[$key]['km_status'] = $r['km_status'];
			$list[$key]['petty_cash'] = number_format($r['nominal'], 0, ",", ",");
			
			
			$list[$key]['custodian_1'] = !empty($r['custodian_1']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_1'])->limit(1)->get()->row()->nama : "N/A";
			$list[$key]['custodian_2'] = !empty($r['custodian_2']) ? $this->db->select('nama')->from('karyawan')->where('nik', $r['custodian_2'])->limit(1)->get()->row()->nama : "N/A";
			$list[$key]['security_1'] = $r['security_1'];
			$list[$key]['security_2'] = $r['security_2'];
			$list[$key]['s100k'] = number_format($r['pcs_100000'], 0, ",", ",");
			$list[$key]['s50k'] = number_format($r['pcs_50000'], 0, ",", ",");
			$list[$key]['s20k'] = number_format($r['pcs_20000'], 0, ",", ",");
			$list[$key]['s10k'] = number_format($r['pcs_10000'], 0, ",", ",");
			$list[$key]['s5k'] = number_format($r['pcs_5000'], 0, ",", ",");
			$list[$key]['s2k'] = number_format($r['pcs_2000'], 0, ",", ",");
			$list[$key]['s1k'] = number_format($r['pcs_1000'], 0, ",", ",");
			$list[$key]['coin'] = number_format($r['pcs_coin'], 0, ",", ",");
			$list[$key]['bag_seal'] = $r['bag_seal'];
			$list[$key]['bag_no'] = $r['bag_no'];
			
			$key++;
		}
		
		
		$result['data'] = $list;
		
		echo json_encode($result);
	}

	function getreplenish_detail_get() {
		$id_user = $this->input->get('id_user');
		$id_ticket = $this->input->get('id_ticket');
		
		$param = "WHERE (`runsheet_operational`.`custodian_1` = '$id_user' AND 
					`cashtransit_detail`.`id` = '$id_ticket' AND 
					`cashtransit_detail`.`state` = 'ro_atm' AND 
					`cashtransit_detail`.`data_solve` = '')";
	
		
		$sql = "SELECT 
						cashtransit.id, 
						cashtransit_detail.id AS id_ticket, 
						cashtransit_detail.id_bank, 
						cashtransit_detail.jenis, 
						cashtransit_detail.ctr, 
						cashtransit_detail.total, 
						cashtransit_detail.fraud_indicated, 
						client.wsid, 
						client.sektor AS ga, 
						client.sektor AS run_number, 
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
						runsheet_cashprocessing.bag_no, 
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
						LEFT JOIN runsheet_logistic ON (cashtransit_detail.id_cashtransit=runsheet_logistic.id_cashtransit && runsheet_logistic.run_number=client.sektor)
						LEFT JOIN runsheet_security ON (cashtransit_detail.id_cashtransit=runsheet_security.id_cashtransit && runsheet_security.run_number=client.sektor)
						LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
						LEFT JOIN vehicle ON (vehicle.police_number=runsheet_security.police_number)
						$param
					";
					
		$query = $this->db->query($sql)->result_array();
		
		
		$list = array();
		$key=0;
		foreach($query as $r) {
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
			$list[$key]['id'] = "T".date("Ymd").sprintf("%04d", $r['id_ticket']);
			$list[$key]['id_bank'] = $r['id_bank'];
			$list[$key]['data_location'] = $r['data_location'];
			$list[$key]['wsid'] = $r['wsid'];
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
	
	function getcashtransit_detail_get() {
	    ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	    
		$id_user = $this->input->get('id_user');
		$id_ticket = $this->input->get('id_ticket');
		
		$param = "WHERE (`runsheet_operational`.`custodian_1` = '$id_user' AND 
					`cashtransit_detail`.`id` = '$id_ticket' AND 
					`cashtransit_detail`.`data_solve` = '')";
	
		
		$sql = "SELECT 
						cashtransit.id, 
						cashtransit_detail.id AS id_ticket, 
						cashtransit_detail.id_bank, 
						cashtransit_detail.jenis, 
						cashtransit_detail.ctr, 
						cashtransit_detail.total, 
						cashtransit_detail.fraud_indicated, 
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
						runsheet_cashprocessing.bag_no
					FROM cashtransit_detail
						LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
						LEFT JOIN master_branch ON (cashtransit.branch=master_branch.id)
						LEFT JOIN runsheet_operational ON (cashtransit_detail.id_cashtransit=runsheet_operational.id_cashtransit)
						LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
						$param
					";
		
		$query = $this->db->query($sql)->result_array();
		
		
		$list = array();
		$key=0;
		foreach($query as $r) {
		
			
			$list['id'] = $r['id_ticket'];
			$list['cart_1_seal'] = $r['cart_1_seal'];
			$list['cart_2_seal'] = $r['cart_2_seal'];
			
			
			$key++;
		}
		
		
		$result['data'] = $list;
		
		echo json_encode($list);
	}
	
	function save_atm_get() {
		$id_ticket = $this->input->get('id_ticket');
		$data_solve = $this->input->get('data_solve');
		
		$bag_seal = json_decode($data_solve)->bag_seal;
		
		$kode = json_decode($data_solve)->bag_seal;
		$this->db->query("UPDATE master_seal SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$kode'");
		
		$id_bank = $this->db->query("SELECT id_bank FROM cashtransit_detail WHERE id='$id_ticket'")->row_array()['id_bank'];
	    
	    $prev = $this->db->query("SELECT * FROM (SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `data_solve`, `cpc_process`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id_ticket)")->result_array();
		
	    $prev = $this->db->query("SELECT * FROM (SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `data_solve`, `cpc_process`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id_ticket AND id_bank='$id_bank')")->result_array();
		
		if(count($prev)==0) {
			$data['data_solve'] = $data_solve;
			$data['cpc_process'] = "pengisian";
		} else {
			$data_det['bag_seal_return '] = $bag_seal;
			$data['data_solve'] = $data_solve;
		
            $this->db->where('id', $id_ticket);
            $this->db->update('runsheet_cashprocessing', $data_det);
		}
		
		
		$this->db->where('id', $id_ticket);
        $update = $this->db->update('cashtransit_detail', $data);
		
		if ($update) {
			$result['data'] = "success";
		} else {
			$result['data'] = "failed";
		}
		
		echo json_encode($result);
	}
	
	function save_cit_get() {
		$id_ticket = $this->input->get('id_ticket');
		$data_solve = $this->input->get('data_solve');
		
		$data['data_solve'] = $data_solve;
		
		$metode = $this->db->query("SELECT * FROM cashtransit_detail WHERE id='$id_ticket'")->row()->metode;

		if($metode=="CP") {
    		$num = $this->db->query("SELECT * FROM jurnal")->row();
    		if(count($num)==0) {
    		    $keterangan = "saldo_awal";
    		} else {
    		    $keterangan = "cash_supply";
    		}
    		$data_jurnal['id_detail'] = $id_ticket;
    		$data_jurnal['tanggal'] = date("Y-m-d");
    		$data_jurnal['keterangan'] = $keterangan;
    		$data_jurnal['posisi'] = "debit";
    		$data_jurnal['debit_100'] = trim(json_decode($data['data_solve'])->kertas_100k)*100000;
    		$data_jurnal['debit_50'] = trim(json_decode($data['data_solve'])->kertas_50k)*50000;
    		$data_jurnal['debit_20'] = trim(json_decode($data['data_solve'])->kertas_20k)*20000;
    		$this->db->insert('jurnal', $data_jurnal);
    
            // print_r($this->db->last_query());
			
			// UPDATE PAPER SEAL
			$paper_seal = json_decode(json_decode($data['data_solve'])->paper_seal);
			foreach($paper_seal as $seal) {
				// echo $seal;
				$this->db->where("kode", $seal);
				$q = $this->db->get('master_seal');
				
				if($q->num_rows()>0) {
					if($q->row()->status=="available") {
						$udt['status'] = "used";
						$this->db->where("kode", $seal);
						$this->db->update("master_seal", $udt);
					}
				}
			}
		}
		
		$this->db->where('id', $id_ticket);
        $update = $this->db->update('cashtransit_detail', $data);
		
		if ($update) {
			$result['data'] = "success";
		} else {
			$result['data'] = "failed";
		}
				
		echo json_encode($result);
	}

	function save_ho_get() {
		$id = $this->input->get('id');
		$data_handover = $this->input->get('data_handover');
		$data_hox = json_decode($data_handover);
// 		print_r($data_hox);
		
		$data_simpan['data_handover'] = $data_handover;
		$data_simpan['status'] = 'done';

		$row = $this->db->query("SELECT * FROM client_ho WHERE id='$id'")->row();
		
        $cassette = $data_hox[2]->jumlah;
		$divert = $data_hox[3]->jumlah;
		$wsid = $row->wsid;
		
		$query = "DELETE FROM `master_cassette` WHERE kode LIKE '%$wsid%'";
		$this->db->query($query);
		
// 		echo $cassette.' '.$divert.' '.$wsid;
		
		for($i = 1; $i<=$cassette; $i++) {
			$kode = $wsid.".CST.".sprintf('%02d', $i);
			$this->db->where('kode',$kode);
			$q = $this->db->get('master_cassette');
			if ( $q->num_rows() == 0 ) { 
				$datax = array(
					"wsid" 	 => $wsid,
					"kode" 	 => $kode,
					"jenis"  => 'cassette',
					"status" => "available"
				);
				
				$this->db->insert('master_cassette', $datax);
				// array_push($array, $datax);
			}
		}
		for($i = 1; $i<=$divert; $i++) {
			$kode = $wsid.".DIV.".sprintf('%02d', $i);
			$this->db->where('kode',$kode);
			$q = $this->db->get('master_cassette');
			if ( $q->num_rows() == 0 ) { 
				$datax = array(
					"wsid" 	 => $wsid,
					"kode" 	 => $kode,
					"jenis"  => 'divert',
					"status" => "available"
				);
				
				$this->db->insert('master_cassette', $datax);
				// array_push($array, $datax);
			}
		}
		
		$count_combi = $this->db->query("SELECT * FROM combi_lock WHERE wsid='".$row->wsid."'")->result();
		$data_ho = json_decode($data_handover, true);
		$combi_lock = $data_ho['24']['jumlah'];
		if(count($count_combi)==0) {
			$data_combi['wsid'] = $row->wsid;
			$data_combi['combination'] = $combi_lock;
			$data_combi['status'] = "active";
			$this->db->insert('combi_lock', $data_combi);
		}

		$detail = json_decode($row->detail);
		
		$wsid				= $row->wsid;
		$bank				= $row->bank;
		$lokasi				= $row->alamat;
		$sektor				= '';
		$cabang				= '';
		$type				= strtoupper($detail->type);
		$type_mesin			= '';
		$jam_operasional	= '';
		$vendor				= '';
		$status				= '1';
		$tgl_ho				= date("Y-m-d H:i:s");
		$denom				= $detail->denom;
		$ctr				= 0;
		$reject				= 0;
		$tgl_min_dari		= $detail->tgl_min_dari;
		$tgl_min_hingga		= $detail->tgl_min_hingga;
		$limit_min			= $detail->limit_min;
		$tgl_max_dari		= $detail->tgl_max_dari;
		$tgl_max_hingga		= $detail->tgl_max_hingga;
		$limit_max			= $detail->limit_max;
		$interval_isi		= $detail->interval_isi;
		$serial_number		= '';
		$keterangan			= '';
		$keterangan2		= '';
		$latlng				= '';

		$data['wsid'] = $wsid;
		$data['bank'] = $bank;
		$data['lokasi'] = $lokasi;
		$data['sektor'] = $sektor;
		$data['cabang'] = $cabang;
		$data['type'] = $type;
		$data['type_mesin'] = $type_mesin;
		$data['jam_operasional'] = $jam_operasional;
		$data['vendor'] = $vendor;
		$data['status'] = $status;
		$data['tgl_ho'] = $tgl_ho;
		$data['denom'] = $denom;
		$data['ctr'] = $ctr;
		$data['reject'] = $reject;
		$data['tgl_min'] = $tgl_min_dari."-".$tgl_min_hingga;
		$data['limit_min'] = $limit_min;
		$data['tgl_max'] = $tgl_max_dari."-".$tgl_max_hingga;
		$data['limit_max'] = $limit_max;
		$data['interval_isi'] = $interval_isi;
		$data['serial_number'] = $serial_number;
		$data['picture'] = '';
		$data['keterangan'] = $keterangan;
		$data['keterangan2'] = $keterangan2;
		if($latlng!=="") {
			$data['data_location'] = $latlng;
		}

// 		echo "<pre>";
// 		print_r($row);
// 		print_r($detail);
// 		print_r($data);

		$insert = $this->db->insert('client', $data);

		if ($insert) {
			$this->db->where('id', $id);
			$update = $this->db->update('client_ho', $data_simpan);

			if ($update) {
				$result['data'] = "success";
			} else {
				$result['data'] = "failed";
			}
		}
		// $this->db->where('id', $id);
		// $update = $this->db->update('client_ho', $data);
		
		// if ($update) {
		// 	$result['data'] = "success";
		// } else {
		// 	$result['data'] = "failed";
		// }
		
		echo json_encode($result);
	}
	
	function check_seal_get() {
		$id_detail = $this->input->get('id_detail');
		$value = $this->input->get('value');
		
		$param = "WHERE (cashtransit_detail.id = '$id_detail' AND 
					 runsheet_cashprocessing.bag_seal = '$value')";
					 
		$sql = "SELECT 
					cashtransit.id, 
					runsheet_cashprocessing.bag_seal, 
					runsheet_cashprocessing.bag_no
				FROM cashtransit_detail
					LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
					LEFT JOIN master_branch ON (cashtransit.branch=master_branch.id)
					LEFT JOIN client ON (cashtransit_detail.id_bank=client.id)
					LEFT JOIN master_zone ON (client.sektor=master_zone.id)
					LEFT JOIN runsheet_operational ON (cashtransit_detail.id_cashtransit=runsheet_operational.id_cashtransit)
					LEFT JOIN runsheet_logistic ON (cashtransit_detail.id_cashtransit=runsheet_logistic.id_cashtransit)
					LEFT JOIN runsheet_security ON (cashtransit_detail.id_cashtransit=runsheet_security.id_cashtransit)
					LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
					$param
				";
				
		// echo $sql;
		
		$query = $this->db->query($sql)->result_array();
		$count = count($query);
		
		$result['data'] = $count;
		
		echo $count;
	}
	
	function check_bag_get() {
		$id_detail = $this->input->get('id_detail');
		$value = $this->input->get('value');
		
		$param = "WHERE (cashtransit_detail.id = '$id_detail' AND 
					 runsheet_cashprocessing.bag_no = '$value')";
	
		
		$sql = "SELECT 
						cashtransit.id, 
						runsheet_cashprocessing.bag_seal, 
						runsheet_cashprocessing.bag_no
					FROM cashtransit_detail
						LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
						LEFT JOIN master_branch ON (cashtransit.branch=master_branch.id)
						LEFT JOIN client ON (cashtransit_detail.id_bank=client.id)
						LEFT JOIN master_zone ON (client.sektor=master_zone.id)
						LEFT JOIN runsheet_operational ON (cashtransit_detail.id_cashtransit=runsheet_operational.id_cashtransit)
						LEFT JOIN runsheet_logistic ON (cashtransit_detail.id_cashtransit=runsheet_logistic.id_cashtransit)
						LEFT JOIN runsheet_security ON (cashtransit_detail.id_cashtransit=runsheet_security.id_cashtransit)
						LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
						$param
					";
		
		$query = $this->db->query($sql)->result_array();
		$count = count($query);
		
		$result['data'] = $count;
		
		echo $count;
	}
	
	function check_submit_get() {
		$id_detail = $this->input->get('id_detail');
		
		$data['loading'] = "1";
		$this->db->where('id', $id_detail);
        $update = $this->db->update('cashtransit_detail', $data);
		
		if ($update) {
			$result = "success";
		} else {
			$result = "failed";
		}
		
		echo $result;
	}
	
	function check_submit2_get() {
		$id_detail = $this->input->get('id_detail');
		
		$data['unloading'] = "1";
		$this->db->where('id', $id_detail);
        $update = $this->db->update('cashtransit_detail', $data);
		
		if ($update) {
			$result = "success";
		} else {
			$result = "failed";
		}
		
		echo $result;
	}
	
	function save_image_post() {
		$id = $this->input->post('id');
		$field = $this->input->post('field');
		$picture = $this->input->post('picture');
		
		$data[$field] = $picture;
		
		$this->db->where('id', $id);
        $update = $this->db->update('cashtransit_detail', $data);
		
		if ($update) {
			$result['data'] = "success";
		} else {
			$result['data'] = "failed";
		}
		
		echo json_encode($result);
	}
	
	public function check_seal_prev_get() {
		$id_ticket = $this->input->get('id_ticket');
		$id_bank = $this->input->get('id_bank');
		$seal = $this->input->get('seal');
		
		$sql = "SELECT * FROM (SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `data_solve`, `cpc_process`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id_ticket AND id_bank='$id_bank' AND cashtransit_detail.data_solve!='batal') AND (
			runsheet_cashprocessing.cart_1_seal LIKE '$seal%' OR
			runsheet_cashprocessing.cart_2_seal LIKE '$seal%' OR
			runsheet_cashprocessing.cart_3_seal LIKE '$seal%' OR
			runsheet_cashprocessing.cart_4_seal LIKE '$seal%' OR
			runsheet_cashprocessing.cart_5_seal LIKE '$seal%' OR
			runsheet_cashprocessing.divert LIKE '$seal%'
		)";
		
		// echo $sql;
		
		$query = $this->db->query($sql)->result_array();
		$count = count($query);
		
		$result['count'] = $count;
		$result['seal'] = $seal;
		
		echo json_encode($result);
	}
	
	public function check_seal_now_get() {
		$id_ticket = $this->input->get('id_ticket');
		$id_bank = $this->input->get('id_bank');
		$seal = $this->input->get('seal');
		
		if (strpos($seal, 'A') !== false) {
			$sql = "SELECT kode, status FROM master_seal WHERE UPPER(kode) = BINARY(kode) AND kode='$seal'";
			$query = $this->db->query($sql)->row_array();
		} else {
			$query = null;
		}
		
		// echo $sql;
		
		echo json_encode($query);
	}
	
	function upload_get() {
		$wsid = $this->input->post('wsid');
		if($wsid!=="") {
			$dir = "upload/".$wsid;
			if( is_dir($dir) === false ) {
				mkdir($dir);
			}
			
			// Set new file name
			$new_image_name = "newimage_".mt_rand().".jpg";

			// upload file
			move_uploaded_file($_FILES["file"]["tmp_name"], 'upload/'.$wsid.'/'.$new_image_name);
			echo $new_image_name ;
		}
	}
}