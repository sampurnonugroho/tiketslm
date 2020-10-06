<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logistic_in_use extends CI_Controller {
    var $data = array();
	
	public function __construct() {
        parent::__construct();
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
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		echo "<h3>PROGRESS ANALISIS MASTER INVENTORY</h3>";
		echo "<pre>";
		
		$master = "
			SELECT 
			inventory.name,
			IFNULL((
				SELECT 
					COUNT(distinct `master_seal`.`id`) AS qty
						FROM
							master_seal
								WHERE 
									master_seal.jenis = inventory.jenis AND
									master_seal.status = 'available'
			),0) AS qty,
			IFNULL((
				SELECT 
					COUNT(distinct `master_seal`.`id`) AS used
						FROM
							master_seal
								WHERE 
									master_seal.jenis = inventory.jenis AND
									master_seal.status = 'used'
			),0) AS qty,
			inventory.unit,
			inventory.type
				FROM
					inventory 
						WHERE 
							inventory.jenis IN ('big','small','paper') AND
							inventory.active = 'y'
		";
		
		echo "<br>";
		$master = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$master), array(CURLOPT_BUFFERSIZE => 10)));
		echo "BERDASARKAN MASTER (MASTER SEAL)<br><hr>";
		print_r($master);
		echo "<hr>";
		
		$small = "
			SELECT 
			inventory.name,
			IFNULL((
				SELECT
					COUNT(distinct cart_1_seal)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.cart_1_seal=master_seal.kode)
					WHERE cart_1_seal IN (SELECT kode FROM master_seal)
			),0) AS cart_1_seal, 
			IFNULL((
				SELECT
					COUNT(distinct cart_2_seal)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.cart_2_seal=master_seal.kode)
					WHERE cart_2_seal IN (SELECT kode FROM master_seal)
			),0) AS cart_2_seal, 
			IFNULL((
				SELECT
					COUNT(distinct cart_3_seal)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.cart_3_seal=master_seal.kode)
					WHERE cart_3_seal IN (SELECT kode FROM master_seal)
			),0) AS cart_3_seal, 
			IFNULL((
				SELECT
					COUNT(distinct cart_4_seal)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.cart_4_seal=master_seal.kode)
					WHERE cart_4_seal IN (SELECT kode FROM master_seal)
			),0) AS cart_4_seal,  
			IFNULL((
				SELECT
					COUNT(distinct divert)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.divert=master_seal.kode)
					WHERE divert IN (SELECT kode FROM master_seal)
			),0) AS divert, 
		   (
			IFNULL((
				SELECT
					COUNT(distinct cart_1_seal)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.cart_1_seal=master_seal.kode)
					WHERE cart_1_seal IN (SELECT kode FROM master_seal)
			),0) +
			IFNULL((
				SELECT
					COUNT(distinct cart_2_seal)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.cart_2_seal=master_seal.kode)
					WHERE cart_2_seal IN (SELECT kode FROM master_seal)
			),0) +
			IFNULL((
				SELECT
					COUNT(distinct cart_3_seal)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.cart_3_seal=master_seal.kode)
					WHERE cart_3_seal IN (SELECT kode FROM master_seal)
			),0) +
			IFNULL((
				SELECT
					COUNT(distinct cart_4_seal)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.cart_4_seal=master_seal.kode)
					WHERE cart_4_seal IN (SELECT kode FROM master_seal)
			),0) +
			IFNULL((
				SELECT
					COUNT(distinct divert)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.divert=master_seal.kode)
					WHERE divert IN (SELECT kode FROM master_seal)
			),0)
		   ) as jumlah
		FROM
		inventory 
		WHERE 
			inventory.jenis = 'small' AND
			inventory.active = 'y'
				
		";
		
		echo "<br>";
		$small = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$small), array(CURLOPT_BUFFERSIZE => 10)));
		echo "BERDASARKAN CASHREPLENISH (SMALL SEAL)<br><hr>";
		print_r($small);
		echo "<hr>";
		
		$big = "
			SELECT 
			inventory.name,
			IFNULL((
				SELECT
					COUNT(distinct bag_seal)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.bag_seal=master_seal.kode)
					WHERE bag_seal IN (SELECT kode FROM master_seal)
			),0) AS bag_seal, 
		   (
			IFNULL((
				SELECT
					COUNT(distinct bag_seal)
				FROM 
					runsheet_cashprocessing
				LEFT JOIN master_seal ON(runsheet_cashprocessing.bag_seal=master_seal.kode)
					WHERE bag_seal IN (SELECT kode FROM master_seal)
			),0)
		   ) as jumlah
		FROM
		inventory 
		WHERE 
			inventory.jenis = 'big' AND
			inventory.active = 'y'
				
		";
		
		echo "<br>";
		$big = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$big), array(CURLOPT_BUFFERSIZE => 10)));
		echo "BERDASARKAN CASHREPLENISH (BIG SEAL)<br><hr>";
		print_r($big);
		echo "<hr>";
		
		$table = "
			SELECT 
			*,
			cashtransit_detail.id as id,
			IF(runsheet_cashprocessing.cart_1_seal = '', 0, 1) AS seal_1,
			IF(runsheet_cashprocessing.cart_2_seal = '', 0, 1) AS seal_2,
			IF(runsheet_cashprocessing.cart_3_seal = '', 0, 1) AS seal_3,
			IF(runsheet_cashprocessing.cart_4_seal = '', 0, 1) AS seal_4,
			IF(runsheet_cashprocessing.cart_5_seal = '', 0, 1) AS seal_5,
			(
				IF(runsheet_cashprocessing.cart_1_seal = '', 0, 1) +
				IF(runsheet_cashprocessing.cart_2_seal = '', 0, 1) +
				IF(runsheet_cashprocessing.cart_3_seal = '', 0, 1) +
				IF(runsheet_cashprocessing.cart_4_seal = '', 0, 1) +
				IF(runsheet_cashprocessing.cart_5_seal = '', 0, 1) +
				IF(runsheet_cashprocessing.divert = '', 0, 1)
			) as jum_small,
			(
				IF(runsheet_cashprocessing.bag_seal = '', 0, 1) +
				IF(runsheet_cashprocessing.bag_seal_return = '', 0, 1)
			) as jum_big
			FROM cashtransit_detail 
			LEFT JOIN runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
			LEFT JOIN client ON(client.id=cashtransit_detail.id_bank)
		";
		
		echo "<br>";
		$table = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$table), array(CURLOPT_BUFFERSIZE => 10)));
		echo "BERDASARKAN DETAIL TABLE<br><hr>";
		// print_r($table);
		// echo "<hr>";
		
		echo "<table border=1 style='border-collapse: collapse;'>";
		echo "<tr>";
		echo "<th style='padding: 5px'>ID</th>";
		echo "<th style='padding: 5px'>WSID</th>";
		echo "<th style='padding: 5px'>SEAL 1</th>";
		echo "<th style='padding: 5px'>SEAL 2</th>";
		echo "<th style='padding: 5px'>SEAL 3</th>";
		echo "<th style='padding: 5px'>SEAL 4</th>";
		echo "<th style='padding: 5px'>SEAL 5</th>";
		echo "<th style='padding: 5px'>DIVERT</th>";
		echo "<th style='padding: 5px'>BAG SEAL</th>";
		echo "<th style='padding: 5px'>BAG NUMBER</th>";
		echo "<th style='padding: 5px'>BAG SEAL RETURN</th>";
		echo "<th style='padding: 5px'>JUMLAH SMALL SEAL</th>";
		echo "<th style='padding: 5px'>JUMLAH BIG SEAL</th>";
		echo "</tr>";
		$sum_small = 0;
		$sum_big = 0;
		$jum_small = 0;
		$jum_big = 0;
		foreach($table as $r) {
			if (strpos($r->cart_1_seal, 'a') !== false) { $jum_small = $jum_small + 1; }
			if (strpos($r->cart_2_seal, 'a') !== false) { $jum_small = $jum_small + 1; }
			if (strpos($r->cart_3_seal, 'a') !== false) { $jum_small = $jum_small + 1; }
			if (strpos($r->cart_4_seal, 'a') !== false) { $jum_small = $jum_small + 1; }
			if (strpos($r->cart_5_seal, 'a') !== false) { $jum_small = $jum_small + 1; }
			if (strpos($r->divert, 'a') !== false) { $jum_small = $jum_small + 1; }
			
			if (strpos($r->bag_seal, 'A') !== false) { $jum_big = $jum_big + 1; }
			if (strpos($r->bag_seal_return, 'A') !== false) { $jum_big = $jum_big + 1; }
			
			echo "<tr>";
			echo "<th>".$r->id."</th>";
			echo "<th>(".$r->wsid.") ".$r->lokasi." </th>";
			echo "<th>".$r->cart_1_seal."</th>";
			echo "<th>".$r->cart_2_seal."</th>";
			echo "<th>".$r->cart_3_seal."</th>";
			echo "<th>".$r->cart_4_seal."</th>";
			echo "<th>".$r->cart_5_seal."</th>";
			echo "<th>".$r->divert."</th>";
			echo "<th>".$r->bag_seal."</th>";
			echo "<th>".$r->bag_no."</th>";
			echo "<th>".$r->bag_seal_return."</th>";
			echo "<th>".$jum_small."</th>";
			echo "<th>".$jum_big."</th>";
			echo "</tr>";
			
			$sum_small = $sum_small+$jum_small;
			$sum_big = $sum_big+$jum_big;
			
			$jum_small = 0;
			$jum_big = 0;
		}
		echo "<tr>";
		echo "<th></th>";
		echo "</tr>";
		echo "<tr>";
		echo "<th colspan=11 align=center>JUMLAH</th>";
		echo "<th>".$sum_small."</th>";
		echo "<th>".$sum_big."</th>";
		echo "</tr>";
		echo "<table>";
	}
	
	public function get_seal_demo() {
		$val = $this->input->post('value');
		$barcode = $this->input->post('barcode');
		
		if($val=='q') {
			$query = "SELECT kode FROM master_seal WHERE jenis='small' AND status='available' AND kode > '$barcode' LIMIT 0,1";
			
			$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->kode;
			
			echo $res;
		}
		if($val=='w') {
			$query = "SELECT kode FROM master_seal WHERE jenis='big' AND status='available' LIMIT 0,1";
			
			$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->kode;
			
			echo $res;
		}
		if($val=='e') {
			$query = "SELECT kode FROM master_bag WHERE jenis='bag' AND status='available' LIMIT 0,1";
			
			$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->kode;
			
			echo $res;
		}
	}
}