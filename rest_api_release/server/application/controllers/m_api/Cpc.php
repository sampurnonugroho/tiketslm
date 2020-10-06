<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Cpc extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function dashboard_get() {
		$sql = "
			SELECT
				cashtransit_detail.*,
				master_branch.*,
				runsheet_operational.*,
				runsheet_security.*,
				runsheet_cashprocessing.petty_cash,
				IFNULL(client.sektor, client_cit.sektor) AS run_number
					FROM
						(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, state, metode, data_solve, cpc_process, unloading FROM cashtransit_detail) AS cashtransit_detail
							LEFT JOIN
								(SELECT id as id_cashtransit, date, branch FROM cashtransit) AS cashtransit 
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
								(SELECT id_cashtransit, run_number, custodian_1 AS custody FROM runsheet_operational) AS runsheet_operational
									ON (
										(runsheet_operational.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
										(runsheet_operational.run_number = IFNULL(client.sektor, client_cit.sektor))
									)
							LEFT JOIN 
								(SELECT id_cashtransit, run_number, police_number FROM runsheet_security) AS runsheet_security
									ON (
										(runsheet_security.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
										(runsheet_security.run_number = IFNULL(client.sektor, client_cit.sektor))
									)
							LEFT JOIN 
								(SELECT id as id_runsheet_cashprocessing, id_cashtransit, total as petty_cash FROM runsheet_cashprocessing) AS runsheet_cashprocessing
									ON (
										(runsheet_cashprocessing.id_runsheet_cashprocessing = cashtransit_detail.id)
									)
					WHERE 
						(data_solve != '' AND (cpc_process = '' OR cpc_process = 'pengisian')) AND unloading = '0'
		";
		
		// echo $sql;
					
		$query = $this->db->query($sql)->result_array();
		
		// echo "<pre>";
		// print_r($query);
		
		// $list = array();
		
		$petty = 0;
		foreach($query as $r) {
			$data_solve = json_decode($r['data_solve']);
			
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
			
			// print_r($data_solve);
			// $petty = $petty + intval($r['petty_cash']);
			
			$list['branch'] = $r['name_branch'];
			$list['run_number'] = $r['run_number'];
			$list['police_number'] = $r['police_number'];
		}
		
		$list['petty_cash'] = number_format($petty, 0, ",", ",");
		
		$data['data'] = $list;
		$data['count'] = count($query);
		
		echo json_encode($data);
	}
	
	function unloading_runsheet_get() {
		$sql = "
			SELECT
				cashtransit.id, 
				cashtransit_detail.id AS id_ticket, 
				cashtransit_detail.id_bank, 
				cashtransit_detail.state, 
				cashtransit_detail.metode, 
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
				cashtransit_detail.data_solve, 
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
			FROM
				cashtransit_detail
			LEFT JOIN
				cashtransit ON (cashtransit.id=cashtransit_detail.id_cashtransit)
			LEFT JOIN 
				master_branch ON (cashtransit.branch=master_branch.id)
			LEFT JOIN 
				client ON (cashtransit_detail.id_bank=client.id)
			LEFT JOIN 
				client_cit ON(cashtransit_detail.id_pengirim=client_cit.id)
			LEFT JOIN 
				runsheet_operational ON((cashtransit_detail.id_cashtransit = runsheet_operational.id_cashtransit) AND (runsheet_operational.run_number = client.sektor OR runsheet_operational.run_number = client_cit.sektor))
			LEFT JOIN 
				runsheet_logistic ON((cashtransit_detail.id_cashtransit = runsheet_logistic.id_cashtransit) AND (runsheet_logistic.run_number = IFNULL((client.sektor), client_cit.sektor)))
			LEFT JOIN 
				runsheet_security ON((cashtransit_detail.id_cashtransit = runsheet_security.id_cashtransit) AND (runsheet_security.run_number = IFNULL((client.sektor), client_cit.sektor)))
			LEFT JOIN 
				runsheet_cashprocessing ON((cashtransit_detail.id = runsheet_cashprocessing.id)) 
			WHERE 
				(cashtransit_detail.cpc_process = '' OR cashtransit_detail.cpc_process = 'pengisian') AND 
				cashtransit_detail.data_solve != '' AND
				cashtransit_detail.unloading = '0' 
				# AND IFNULL((client.sektor), client_cit.sektor) = '35'
		";
						
		$query = $this->db->query($sql)->result_array();
		
		$list = array();
		$key=0;
		$petty = 0;
		foreach($query as $r) {
			$data_solve = json_decode($r['data_solve']);
			
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
			
			if($r['state']=="ro_cit") {
				if($r['metode']=="CP") {
					$s100k = ($data_solve->kertas_100k='' ? 0 : $data_solve->kertas_100k*100000);
					$s50k = ($data_solve->kertas_50k='' ? 0 : $data_solve->kertas_50k*50000);
					$total = $s100k+$s50k;
					$petty = $petty + $total;
					$r['nominal'] = $r['total'] = $petty;
				}
			} else if($r['state']=="ro_atm") {
				$petty = $petty + $data_solve->return_withdraw;
				$r['nominal'] = $r['total'] = $petty;
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
			
			$petty = 0;
			$key++;
		}
		
		
		$result['data'] = $list;
		
		// echo "<pre>";
		// print_r($query);
		// print_r($result);
		
		echo json_encode($list);
	}
	
	function check_seal_get() {
		$id_detail = $this->input->get('id_detail');
		$value = $this->input->get('value');
		
		$data_solve = $this->db->query("SELECT data_solve FROM cashtransit_detail WHERE id='$id_detail'")->row_array()['data_solve'];
		
		if($data_solve=="batal") {
		    $param = "WHERE (cashtransit_detail.id = '$id_detail' AND 
    					  (runsheet_cashprocessing.bag_seal = '$value'))";
    					 
    		$sql = "SELECT 
    					cashtransit.id, 
    					runsheet_cashprocessing.bag_seal, 
    					runsheet_cashprocessing.bag_no
    				FROM cashtransit_detail
    					LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
    					LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
    					$param
    				";
    				
    		// echo $sql;
    		
    		$query = $this->db->query($sql)->result_array();
    		$count = count($query);
    		
    		$result['data'] = $count;
    		
    		echo $count;
		} else {
		    $param = "WHERE (cashtransit_detail.id = '$id_detail' AND 
    					  (runsheet_cashprocessing.bag_seal_return = '$value'))";
    					 
    		$sql = "SELECT 
    					cashtransit.id, 
    					runsheet_cashprocessing.bag_seal, 
    					runsheet_cashprocessing.bag_no
    				FROM cashtransit_detail
    					LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
    					LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
    					$param
    				";
    				
    		// echo $sql;
    		
    		$query = $this->db->query($sql)->result_array();
    		$count = count($query);
    		
    		$result['data'] = $count;
    		
    		echo $count;
		}
	}
	
	function check_seal_return_get() {
		$id_detail = $this->input->get('id_detail');
		$value = $this->input->get('value');
		
		$param = "WHERE (cashtransit_detail.id = '$id_detail' AND 
					 (runsheet_cashprocessing.bag_seal_return = '$value'))";
					 
		$sql = "SELECT 
					cashtransit.id, 
					runsheet_cashprocessing.bag_seal, 
					runsheet_cashprocessing.bag_no
				FROM cashtransit_detail
					LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
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
					LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
					$param
				";
				
		$this->db->query("UPDATE `master_bag` SET `status`='available' WHERE `kode`='$value'");
		
		$query = $this->db->query($sql)->result_array();
		$count = count($query);
		
		$result['data'] = $count;
		
		echo $count;
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
	
	function get_paperseal_get() {
		$id_ticket = $this->input->get('id_ticket');
		
		$sql = "
			SELECT 
				cashtransit.id, 
				cashtransit_detail.id AS id_ticket, 
				cashtransit_detail.id_bank, 
				cashtransit_detail.jenis, 
				cashtransit_detail.ctr, 
				cashtransit_detail.total, 
				cashtransit_detail.data_solve,
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
				LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
			WHERE 
				cashtransit_detail.id = '$id_ticket'
			";
		
		$res = $this->db->query($sql)->row();
		
		// echo "<pre>";
		// print_r($res);
		// print_r(json_decode($res->data_solve));
		
		$result['data'] = json_decode(json_decode($res->data_solve)->paper_seal);
		echo json_encode($result);
	}
}