<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Cr extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function index_get() {
		echo "AA";
	}
	
	function request_combi_get() {
		$wsid = $this->input->get('wsid');
		$id_detail = $this->input->get('id_detail');
		
		$query = "SELECT combination FROM combi_lock WHERE wsid='$wsid'";
		$combi = $this->db->query($query)->row();
		
		$count_request = $this->db->query("SELECT req_combi FROM cashtransit_detail WHERE id='$id_detail'")->row();
		// echo $count_request->req_combi;
		if($count_request->req_combi<2) {
			// echo $combi->combination;
			
			$data = array('req_combi' => $count_request->req_combi+1);
			$this->db->where('id', $id_detail);
			$update = $this->db->update('cashtransit_detail', $data);
			
			echo json_encode(array('combi'=>$combi->combination, 'count'=>(intval($count_request->req_combi)+1)));
		} else {
			// echo "ANDA TELAH MENCAPAI MAKSIMUM REQUEST COMBINATION LOCK";
			echo "invalid";
		}
	}
	
	function dashboard_post() {
		$id_user = $this->input->post('id_user');
		
		$param = "
			WHERE runsheet_operational.custodian_1='".$id_user."'
			GROUP BY cashtransit.id
		";
		
		$query  = "
			SELECT * FROM cashtransit
				LEFT JOIN runsheet_operational 
					ON(cashtransit.id=runsheet_operational.id_cashtransit)
				LEFT JOIN runsheet_logistic 
					ON(cashtransit.id=runsheet_logistic.id_cashtransit)
				LEFT JOIN runsheet_cashprocessing 
					ON(cashtransit.id=runsheet_cashprocessing.id_cashtransit)
				LEFT JOIN runsheet_security 
					ON(cashtransit.id=runsheet_security.id_cashtransit)
				LEFT JOIN cashtransit_detail 
					ON(cashtransit.id=cashtransit_detail.id_cashtransit)
				LEFT JOIN master_branch 
					ON(cashtransit.branch=master_branch.id) ".$param."
		";
		
		$result = $this->db->query($query)->result_array();
		
		$list = array();
		$key=0;
		foreach($result as $r) {
			$list[$key]['id'] = $r['id'];
			$list[$key]['branch'] = $r['name'];
			$list[$key]['act'] = $r['jenis'];
			$list[$key]['ga'] = $r['run_number'];
			$list[$key]['police_number'] = $r['police_number'];
			$list[$key]['km_status'] = $r['km_status'];
			$list[$key]['petty_cash'] = $r['total'];
			
			$i = 0;
			foreach(json_decode($r['data']) as $k => $r) {
				$log[$i]['name'] = $this->db->query("SELECT name FROM inventory WHERE id='".$k."'")->row()->name;
				$log[$i]['qty'] = $r;
				$i++;
			}
			
			$list[$key]['logistic'] = $log;
			
			$key++;
		}
		
		$res['data'] = $list;
		
		echo json_encode($res);
	}
	
	function invalid_seal_get() {
		$wsid = $this->input->get('wsid');
		$id_detail = $this->input->get('id_detail');
		$seal = $this->input->get('seal');
		// SELECT * FROM runsheet_cashprocessing INSERT INTO cashtransit_detail_problem WHERE runsheet_cashprocessing.id='195'
		

		$query = $this->db->query("SELECT * FROM cashtransit_detail_problem WHERE id='$id_detail'");
		echo $query->num_rows();
		if($query->num_rows()==0) {
			$query = "INSERT INTO cashtransit_detail_problem SELECT * FROM runsheet_cashprocessing WHERE runsheet_cashprocessing.id='$id_detail'";
			$this->db->query($query);
			
			if($this->db->query("SELECT * FROM cashtransit_detail_invalid WHERE seal='$seal'")->num_rows()==0) {
				$this->db->query("INSERT INTO `cashtransit_detail_invalid`(`id_detail`, `seal`) VALUES ('$id_detail','$seal')");
			} else {
				$seal_ = $this->db->query("SELECT * FROM cashtransit_detail_invalid WHERE seal='$seal'")->row()->seal;
				$this->db->query("UPDATE `cashtransit_detail_invalid` SET `seal`='$seal' WHERE `seal`='$seal_'");
			}
		} else {
			$query = "
				UPDATE
					cashtransit_detail_problem,
					runsheet_cashprocessing
				SET
					cashtransit_detail_problem.id_cashtransit		   = runsheet_cashprocessing.id_cashtransit,
					cashtransit_detail_problem.id_cashtransit_detail   = runsheet_cashprocessing.id_cashtransit_detail,
					cashtransit_detail_problem.run_number              = runsheet_cashprocessing.run_number, 
					cashtransit_detail_problem.pcs_100000              = runsheet_cashprocessing.pcs_100000,
					cashtransit_detail_problem.pcs_50000               = runsheet_cashprocessing.pcs_50000,
					cashtransit_detail_problem.pcs_20000               = runsheet_cashprocessing.pcs_20000,  
					cashtransit_detail_problem.pcs_10000               = runsheet_cashprocessing.pcs_10000,  
					cashtransit_detail_problem.pcs_5000                = runsheet_cashprocessing.pcs_5000,   
					cashtransit_detail_problem.pcs_2000                = runsheet_cashprocessing.pcs_2000,   
					cashtransit_detail_problem.pcs_1000                = runsheet_cashprocessing.pcs_1000,   
					cashtransit_detail_problem.pcs_coin                = runsheet_cashprocessing.pcs_coin,   
					cashtransit_detail_problem.detail_uang             = runsheet_cashprocessing.detail_uang,
					cashtransit_detail_problem.ctr_1_no                = runsheet_cashprocessing.ctr_1_no,   
					cashtransit_detail_problem.ctr_2_no                = runsheet_cashprocessing.ctr_2_no,   
					cashtransit_detail_problem.ctr_3_no                = runsheet_cashprocessing.ctr_3_no,   
					cashtransit_detail_problem.ctr_4_no                = runsheet_cashprocessing.ctr_4_no,   
					cashtransit_detail_problem.ctr_5_no                = runsheet_cashprocessing.ctr_5_no,   
					cashtransit_detail_problem.cart_1_seal             = runsheet_cashprocessing.cart_1_seal,
					cashtransit_detail_problem.cart_2_seal             = runsheet_cashprocessing.cart_2_seal,
					cashtransit_detail_problem.cart_3_seal             = runsheet_cashprocessing.cart_3_seal,     
					cashtransit_detail_problem.cart_4_seal             = runsheet_cashprocessing.cart_4_seal,     
					cashtransit_detail_problem.cart_5_seal             = runsheet_cashprocessing.cart_5_seal,     
					cashtransit_detail_problem.divert                  = runsheet_cashprocessing.divert,          
					cashtransit_detail_problem.total                   = runsheet_cashprocessing.total,           
					cashtransit_detail_problem.bag_seal                = runsheet_cashprocessing.bag_seal,        
					cashtransit_detail_problem.bag_no                  = runsheet_cashprocessing.bag_no,          
					cashtransit_detail_problem.t_bag                   = runsheet_cashprocessing.t_bag,           
					cashtransit_detail_problem.data_seal               = runsheet_cashprocessing.data_seal,       
					cashtransit_detail_problem.updated_date_cpc        = runsheet_cashprocessing.updated_date_cpc

				WHERE
					cashtransit_detail_problem.id = runsheet_cashprocessing.id	AND
					runsheet_cashprocessing.id = '$id_detail'	
			";
			$this->db->query($query);
			
			if($this->db->query("SELECT * FROM cashtransit_detail_invalid WHERE seal='$seal'")->num_rows()==0) {
				$this->db->query("INSERT INTO `cashtransit_detail_invalid`(`id_detail`, `seal`) VALUES ('$id_detail','$seal')");
			} else {
				$seal_ = $this->db->query("SELECT * FROM cashtransit_detail_invalid WHERE seal='$seal'")->row()->seal;
				$this->db->query("UPDATE `cashtransit_detail_invalid` SET `seal`='$seal' WHERE `seal`='$seal_'");
			}
		}
		
		echo $query;
	}
	
	function getUpdateInfo_get() {
	    $id = $this->input->get('id');
	    $query = "
	        SELECT data_solve FROM cashtransit_detail WHERE id='$id'
	    ";
	    
	    echo $this->db->query($query)->row()->data_solve;
	}
	
	function updateBatal_get() {
	    echo "A";
	}
}