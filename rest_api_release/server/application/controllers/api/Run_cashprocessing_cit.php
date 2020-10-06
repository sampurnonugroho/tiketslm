<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Run_cashprocessing_cit extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function index_get() {
		$id = $this->get('id'); 
        if ($id == '') {
			$q = "SELECT *, 
						cashtransit.id as id_ct, 
						IFNULL((SELECT COUNT(DISTINCT cashtransit_detail.id) FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.id_cashtransit=cashtransit.id AND cashtransit_detail.id NOT IN (SELECT id FROM runsheet_cashprocessing WHERE id_cashtransit=cashtransit.id) AND state='ro_atm' GROUP BY cashtransit_detail.id_cashtransit), 0) as count FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) ORDER BY cashtransit.id DESC";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "SELECT *
                               FROM karyawan A LEFT JOIN jabatan B ON B.id_jabatan = A.id_jabatan
                                               LEFT JOIN bagian_departemen C ON C.id_bagian_dept = A.id_bagian_dept
                                               LEFT JOIN departemen D ON D.id_dept = C.id_dept 
								WHERE A.nik='$nik'";
            $user = $this->db->query($q)->result_array();
        }
		
		if (!empty($user)) {
			 $this->response($user, REST_Controller::HTTP_OK);
		} else {
			$this->set_response([
				'status' => FALSE,
				'message' => 'User could not be found'
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	
	function get_data_post() {
		$id = $this->post('id'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$qry = "select SQL_CALC_FOUND_ROWS *,
			cashtransit_detail.id as id_ct, 
			cashtransit_detail.id_cashtransit, 
			cashtransit_detail.id_bank, 
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
			runsheet_cashprocessing.id as id_process,
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
			LEFT JOIN client_cit on(cashtransit_detail.id_pengirim=client_cit.id) 
			LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
			LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
			WHERE cashtransit_detail.id_cashtransit='$id' AND cashtransit_detail.state='ro_cit' limit $offset,$rows";
			
			
			// echo $qry;
			// echo "<br>";
		$query = $this->db->query($qry)->result();
		
		// echo "<pre>";
		// print_r($query);
		$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
		$result["total"] = $rows['found_rows'];
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			$detailuang = json_decode($row->detail_uang, true);
			
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['state'] = $row->state;
			$items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
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
			$items[$i]['id_process'] = $row->id_process;
			$items[$i]['pcs_100000'] = $row->pcs_100000;
			$items[$i]['pcs_50000'] = $row->pcs_50000;
			$items[$i]['pcs_20000'] = $row->pcs_20000;
			$items[$i]['pcs_10000'] = $row->pcs_10000;
			$items[$i]['pcs_5000'] = $row->pcs_5000;
			$items[$i]['pcs_2000'] = $row->pcs_2000;
			$items[$i]['pcs_1000'] = $row->pcs_1000;
			$items[$i]['pcs_coin'] = $row->pcs_coin;
			$items[$i]['s100k'] = $row->s100k;
			$items[$i]['s50k'] = $row->s50k;
			$items[$i]['s20k'] = $row->s20k;
			$items[$i]['s10k'] = $row->s10k;
			$items[$i]['s5k'] = $row->s5k;
			$items[$i]['s2k'] = $row->s2k;
			$items[$i]['s1k'] = $row->s1k;
			$items[$i]['coin'] = $row->coin;
			$items[$i]['detail_uang'] = $row->detail_uang;
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
			$items[$i]['bag_seal'] = $row->bag_seal;
			$items[$i]['bag_no'] = $row->bag_no;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	function index_post() {
		
	}
	
	function index_put() {
		
	}
	
	function index_delete() {
		
	}
}