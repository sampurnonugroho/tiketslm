<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Table extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function index_get() {
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$offset = ($page-1)*$rows;
		
		$totalpages = ceil($this->count_rows() / $rows);
		// $totalpages = 2;
		
		// echo $totalpages." ".($page-1);
		
		if($totalpages==($page-1)) {
			echo json_encode(array("result"=>"success", "page"=>"done"));
		} else {
			// 10000000
			// for($i=0; $i<=1000; $i++) {
				// // echo $i;
				// if($i==1000) {
					// echo json_encode(array("result"=>"success", "page"=>($page)));
				// }
			// }
			
			if($this->sync_jurnal($offset, $rows)=="done") {
				echo json_encode(array("result"=>"success", "total"=>($totalpages), "page"=>($page-1), "tes"=>($offset)." ".$rows." ".($page-1)." ".$this->count_rows()));
			}
		}
	}
	function index2_get() {
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$offset = ($page-1)*$rows;
		
		$totalpages = ceil($this->count_rows() / $rows);
		// $totalpages = 2;
		
		// echo $totalpages." ".($page-1);
		
		if($totalpages==($page-1)) {
			echo json_encode(array("result"=>"success", "page"=>"done"));
		} else {
			// 10000000
			// for($i=0; $i<=1000; $i++) {
				// // echo $i;
				// if($i==1000) {
					// echo json_encode(array("result"=>"success", "page"=>($page)));
				// }
			// }
			
			if($this->sync_jurnal($offset, $rows)=="done") {
				echo json_encode(array("result"=>"success", "total"=>($totalpages), "page"=>($page-1), "tes"=>($offset)." ".$rows." ".($page-1)." ".$this->count_rows()));
			}
		}
	}
	
	function delete_sync_get() {
		$query = "
			DELETE FROM `jurnal_sync` WHERE tanggal LIKE '%".date("Y-m")."%'
		";
		$this->db->query($query);
	}
	
	function count_rows() {
		$query = "SELECT count(*) as cnt FROM jurnal 
			WHERE tanggal LIKE '%".date("Y-m")."%' AND 
			NOT EXISTS (
				SELECT * FROM jurnal_sync 
				WHERE ids = jurnal.id
			)
		";
		$count = $this->db->query($query)->row();
		
		return $count->cnt;
	}
	
	public function prev_jurnal($field) {
		$query = "SELECT id, saldo_100, saldo_50, saldo_20 FROM jurnal_sync ORDER BY id DESC LIMIT 0,1 ";
		$data_record = $this->db->query($query)->row_array();
		
		return (int) $data_record[$field];
	}
	
	function sync_jurnal($offset=0, $rows=10) {
		$query = "SELECT 
			*, jurnal.id as ids, jurnal.keterangan as keterangan_jurnal
		FROM jurnal 
		LEFT JOIN (SELECT id, id_bank FROM cashtransit_detail) AS cashtransit_detail ON(cashtransit_detail.id=jurnal.id_detail)
		LEFT JOIN (SELECT id, wsid FROM client) AS client ON(client.id=cashtransit_detail.id_bank)
		WHERE 
			jurnal.tanggal LIKE '%".date("Y-m")."%' AND 
			NOT EXISTS (
				SELECT * FROM jurnal_sync 
				WHERE ids = jurnal.id
			)
		ORDER BY jurnal.tanggal, jurnal.id_detail, client.wsid ASC limit $offset,$rows";
		$data_record = $this->db->query($query)->result();
		$count = count($data_record);
		
		$no = 0;
		$prev_debit_100 = 0;
		$prev_kredit_100 = 0;
		$prev_debit_50 = 0;
		$prev_kredit_50 = 0;
		$prev_debit_20 = 0;
		$prev_kredit_20 = 0;
		$prev_saldo_100 = $this->prev_jurnal("saldo_100");
		$prev_saldo_50 = $this->prev_jurnal("saldo_50");
		$prev_saldo_20 = $this->prev_jurnal("saldo_20");
		$prev_saldo = 0;
		$saldo_100 = 0;
		$saldo_50 = 0;
		$saldo_20 = 0;
		$saldo = 0;
		$prev_ket = "";
		foreach($data_record as $r) {
			if($r->kredit_100==0) {
				$saldo_100 = $prev_saldo_100 + $r->debit_100;
			} else {
				$saldo_100 = $prev_saldo_100 - $r->kredit_100;
			}
			
			// echo $saldo_100."<br>";
			
			if($r->kredit_50==0) {
				$saldo_50 = $prev_saldo_50  + $r->debit_50;
			} else {
				$saldo_50 = $prev_saldo_50  - $r->kredit_50;
			}
			
			if($r->kredit_20==0) {
				$saldo_20 = $prev_saldo_20 + $r->debit_20;
			} else {
				$saldo_20 = $prev_saldo_20 - $r->kredit_20;
			}
			
			$saldo = $saldo_100 + $saldo_50 + $saldo_20;
			
			
			$data_save = array();
			$data_save['ids']				= $r->ids;
			$data_save['id_detail']			= $r->id_detail;
			$data_save['wsid']				= ($r->wsid==null ? "" : $r->wsid);
			$data_save['tanggal']			= $r->tanggal;
			$data_save['catatan']			= $r->catatan;
			$data_save['keterangan']		= $r->keterangan;
			$data_save['posisi']			= $r->posisi;
			$data_save['debit_100']			= $r->debit_100;
			$data_save['debit_50']			= $r->debit_50;
			$data_save['debit_20']			= $r->debit_20;
			$data_save['kredit_100']		= $r->kredit_100;
			$data_save['kredit_50']			= $r->kredit_50;
			$data_save['kredit_20']			= $r->kredit_20;
			$data_save['saldo_100']			= ($saldo_100==0 ? 0 : $saldo_100);
			$data_save['saldo_50']			= ($saldo_50==0 ? 0 : $saldo_50);
			$data_save['saldo_20']			= ($saldo_20==0 ? 0 : $saldo_20);
			$data_save['sum_saldo']			= ($saldo==0 ? 0 : $saldo);
			
			$query = "SELECT count(*) as cnt FROM jurnal_sync WHERE ids='".$r->ids."'";
			// echo $query;
			$cnt = $this->db->query($query)->row();
			
			if($cnt->cnt==0) {
				$this->db->insert('jurnal_sync', $data_save);
				
				// echo $this->db->last_query();
				
				// echo "<pre>";
				// print_r($data_save);
			}
			
		
			// echo "<pre>";
			// print_r($data_save);
			
			// break;
			
			$prev_debit_100 = $r->debit_100;
			$prev_kredit_100 = $r->kredit_100;
			$prev_debit_50= $r->debit_50;
			$prev_kredit_50 = $r->kredit_50;
			$prev_debit_20= $r->debit_20;
			$prev_kredit_20 = $r->kredit_20;
			
			$prev_saldo_100 = $saldo_100;
			$prev_saldo_50 = $saldo_50;
			$prev_saldo_20 = $saldo_20;
			$prev_ket = $r->keterangan;
			
			$no++;
		}
		
		// echo " \ncount:".$count." nomor:".$no;
		
		if($count==$no) {
			return "done";
		}
	}
}