<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tes extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->library('curl');
	}
	
	public function index() {
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
					
				WHERE 
					(cashtransit_detail.cpc_process = '' OR cashtransit_detail.cpc_process = 'pengisian') AND 
					cashtransit_detail.unloading = '0'
				";
				
		$sql = "
			SELECT
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
				runsheet_cashprocessing ON((cashtransit_detail.id = runsheet_cashprocessing.id) AND (runsheet_cashprocessing.run_number = IFNULL((client.sektor), client_cit.sektor))) 
			WHERE 
				(cashtransit_detail.cpc_process = '' OR cashtransit_detail.cpc_process = 'pengisian') AND 
				cashtransit_detail.unloading = '0'
		";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		echo "<pre>";
		print_r("JUMLAH : ".count($result));
		echo "<br>";
		print_r($result);
	}
	
	function tes2() {
		$id_detail = '203';
		$value = 'A50005';
		
		$sql = "
			SELECT 
				cashtransit.id, 
				runsheet_cashprocessing.bag_seal, 
				runsheet_cashprocessing.bag_no
			FROM cashtransit_detail
				LEFT JOIN cashtransit ON (cashtransit_detail.id_cashtransit=cashtransit.id)
				LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id)
			WHERE 
				(cashtransit_detail.id = '$id_detail' AND runsheet_cashprocessing.bag_seal = '$value')
		";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		echo "<pre>";
		print_r($result);
	}

	function tes3() {
		$id_ticket = '209';
		
		$sql = "SELECT 
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
		
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($res);
		// print_r(json_decode($res->data_solve));
		
		$result['data'] = json_decode($res->data_solve)->paper_seal;
		echo json_encode($result);
	}
}