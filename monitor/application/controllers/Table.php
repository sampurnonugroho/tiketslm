<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Table extends CI_Controller {
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
        return view('table', $this->data);
	}
	
	function json() {
		$query = "
			SELECT jurnal_sync.* FROM jurnal_sync
		";
		
		$param['query'] = $query; //nama tabel dari database
		$param['column_order'] = array('id'); //field yang ada di table user
		$param['column_search'] = array('wsid'); //field yang diizin untuk pencarian 
		$param['order'] = array(array('id' => 'ASC'));
		// $param['group'] = array('action_date');
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		// echo ;
		$data = $this->curl->simple_get(rest_api().'/datatables', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		$r = json_decode($data, true);
		
		$out = array();
		$out['draw'] = $r['draw'];
		$out['recordsTotal'] = $r['recordsTotal'];
		$out['recordsFiltered'] = $r['recordsFiltered'];
		$datas = array();
		$i = 0;
		$no = $_REQUEST['start'];
		foreach($r['data'] as $row) {
			$no++;
			
			$datas[$i]['no'] 			= $no; 			 
			$datas[$i]['id'] 			= $row['id']; 			 
			$datas[$i]['id_detail'] 	= $row['id_detail']; 
			$datas[$i]['wsid'] 			= $row['wsid']; 		 
			$datas[$i]['tanggal'] 		= $row['tanggal']; 		 
			$datas[$i]['catatan'] 		= $row['catatan']; 		 
			$datas[$i]['keterangan'] 	= $row['keterangan']; 
			$datas[$i]['posisi'] 		= $row['posisi']; 		 
			$datas[$i]['debit_100'] 	= ($row['debit_100']==0 ? 0 : number_format($row['debit_100'], 0, ",", ",")); 
			$datas[$i]['debit_50'] 		= ($row['debit_50']==0 ? 0 : number_format($row['debit_50'], 0, ",", ",")); 
			$datas[$i]['debit_20'] 		= ($row['debit_20']==0 ? 0 : number_format($row['debit_20'], 0, ",", ",")); 
			$datas[$i]['kredit_100'] 	= ($row['kredit_100']==0 ? 0 : number_format($row['kredit_100'], 0, ",", ","));  
			$datas[$i]['kredit_50'] 	= ($row['kredit_50']==0 ? 0 : number_format($row['kredit_50'], 0, ",", ",")); 
			$datas[$i]['kredit_20'] 	= ($row['kredit_20']==0 ? 0 : number_format($row['kredit_20'], 0, ",", ",")); 
			$datas[$i]['saldo_100'] 	= ($row['saldo_100']==0 ? 0 : number_format($row['saldo_100'], 0, ",", ",")); 
			$datas[$i]['saldo_50'] 		= ($row['saldo_50']==0 ? 0 : number_format($row['saldo_50'], 0, ",", ",")); 
			$datas[$i]['saldo_20'] 		= ($row['saldo_20']==0 ? 0 : number_format($row['saldo_20'], 0, ",", ",")); 
			$datas[$i]['sum_saldo'] 	= ($row['sum_saldo']==0 ? 0 : number_format($row['sum_saldo'], 0, ",", ",")); 

			$i++;
		}
		// $out['data'] = $r['data'];
		$out['data'] = $datas;
		// $out['total_jurnal'] = "200000";
		
		// print_r($out);
		echo json_encode($out);
	}
	
	function show() {
		$query = "
			SELECT jurnal_sync.* FROM jurnal_sync
		";
		
		$param['query'] = $query; //nama tabel dari database
		$param['column_order'] = array('id'); //field yang ada di table user
		$param['column_search'] = array('wsid'); //field yang diizin untuk pencarian 
		$param['order'] = array(array('id' => 'ASC'));
		// $param['group'] = array('action_date');
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		echo $this->curl->simple_get(rest_api().'/datatables/show', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
	
	
	function sync_jurnal2() {
		$query = "SELECT 
			*, jurnal.keterangan as keterangan_jurnal
		FROM jurnal 
		LEFT JOIN (SELECT id, id_bank FROM cashtransit_detail) AS cashtransit_detail ON(cashtransit_detail.id=jurnal.id_detail)
		LEFT JOIN (SELECT id, wsid FROM client) AS client ON(client.id=cashtransit_detail.id_bank)
		WHERE 
			NOT EXISTS (
				SELECT * FROM jurnal_sync 
				WHERE tanggal = jurnal.tanggal AND id_detail = jurnal.id_detail
			)
		ORDER BY jurnal.tanggal ASC, jurnal.id_detail ASC, client.wsid ASC";
		$data_record = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		echo "<pre>";
		print_r($data_record);
		
		// $no = 0;
		// $prev_debit_100 = 0;
		// $prev_kredit_100 = 0;
		// $prev_debit_50 = 0;
		// $prev_kredit_50 = 0;
		// $prev_debit_20 = 0;
		// $prev_kredit_20 = 0;
		// $prev_saldo_100 = 0;
		// $prev_saldo_50 = 0;
		// $prev_saldo_20 = 0;
		// $prev_saldo = 0;
		// $saldo_100 = 0;
		// $saldo_50 = 0;
		// $saldo_20 = 0;
		// $saldo = 0;
		// $prev_ket = "";
		// foreach($data_record as $r) {
			// $no++;
			// if($r->kredit_100==0) {
				// $saldo_100 = ($this->prev_jurnal('saldo_100')==0 ? $prev_saldo_100 : $this->prev_jurnal('saldo_100')) + $r->debit_100;
			// } else {
				// $saldo_100 = ($this->prev_jurnal('saldo_100')==0 ? $prev_saldo_100 : $this->prev_jurnal('saldo_100')) - $r->kredit_100;
			// }
			
			// if($r->kredit_50==0) {
				// $saldo_50 = ($this->prev_jurnal('saldo_50')==0 ? $prev_saldo_100 : $this->prev_jurnal('saldo_50')) + $r->debit_50;
			// } else {
				// $saldo_50 = ($this->prev_jurnal('saldo_50')==0 ? $prev_saldo_100 : $this->prev_jurnal('saldo_50')) - $r->kredit_50;
			// }
			
			// if($r->kredit_20==0) {
				// $saldo_20 = ($this->prev_jurnal('saldo_20')==0 ? $prev_saldo_100 : $this->prev_jurnal('saldo_20')) + $r->debit_20;
			// } else {
				// $saldo_20 = ($this->prev_jurnal('saldo_20')==0 ? $prev_saldo_100 : $this->prev_jurnal('saldo_20')) - $r->kredit_20;
			// }
			
			// $saldo = $saldo_100 + $saldo_50 + $saldo_20;
			
			
			// $data_save = array();
			// $data_save['id_detail']			= $r->id_detail;
			// $data_save['wsid']				= $r->wsid;
			// $data_save['tanggal']			= $r->tanggal;
			// $data_save['catatan']			= $r->catatan;
			// $data_save['keterangan']		= $r->keterangan;
			// $data_save['posisi']			= $r->posisi;
			// $data_save['debit_100']			= $r->debit_100;
			// $data_save['debit_50']			= $r->debit_50;
			// $data_save['debit_20']			= $r->debit_20;
			// $data_save['kredit_100']		= $r->kredit_100;
			// $data_save['kredit_50']			= $r->kredit_50;
			// $data_save['kredit_20']			= $r->kredit_20;
			// $data_save['saldo_100']			= ($saldo_100==0 ? 0 : $saldo_100);
			// $data_save['saldo_50']			= ($saldo_50==0 ? 0 : $saldo_50);
			// $data_save['saldo_20']			= ($saldo_20==0 ? 0 : $saldo_20);
			// $data_save['sum_saldo']			= ($saldo==0 ? 0 : $saldo);
			
			// // $table = "jurnal_sync";
			// // $res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_save), array(CURLOPT_BUFFERSIZE => 10));
			
			// // echo "<pre>";
			// // print_r($data_save);
			
		
			// // echo "<pre>";
			// // print_r($data_save);
			
			// // break;
			
			// $prev_debit_100 = $r->debit_100;
			// $prev_kredit_100 = $r->kredit_100;
			// $prev_debit_50= $r->debit_50;
			// $prev_kredit_50 = $r->kredit_50;
			// $prev_debit_20= $r->debit_20;
			// $prev_kredit_20 = $r->kredit_20;
			
			// $prev_saldo_100 = $saldo_100;
			// $prev_saldo_50 = $saldo_50;
			// $prev_saldo_20 = $saldo_20;
			// $prev_ket = $r->keterangan;
		// }
	}
	
	public function prev_jurnal($field) {
		$query = "SELECT saldo_100, saldo_50, saldo_20 FROM jurnal_sync ORDER BY id DESC LIMIT 0,1";
		$data_record = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)), true);
		
		return $data_record[$field];
	}
	
	public function worker() {
		return view('worker', $this->data);
	}
	function count_rows() {
		$query = "SELECT count(*) as cnt FROM jurnal";
		$data_record = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		return $data_record->cnt;
	}
	
	public function tes() {
		// $this->sync_jurnal();
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$offset = ($page-1)*$rows;
		
		$totalpages = ceil($this->count_rows() / $rows);
		// $totalpages = 2;
		
		// echo $page." ".$rows." ".$offset." ".$totalpages." ".($page-1);
		
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
				echo json_encode(array("result"=>"success", "page"=>($page), "tes"=>($offset)." ".$rows." ".($page-1)." ".$this->count_rows()));
			}
		}
	}
	
	function sync_jurnal($offset=1, $rows=10) {
		$query = "SELECT 
			*, jurnal.id as ids, jurnal.keterangan as keterangan_jurnal
		FROM jurnal 
		LEFT JOIN (SELECT id, id_bank FROM cashtransit_detail) AS cashtransit_detail ON(cashtransit_detail.id=jurnal.id_detail)
		LEFT JOIN (SELECT id, wsid FROM client) AS client ON(client.id=cashtransit_detail.id_bank)
		ORDER BY jurnal.tanggal, jurnal.id_detail, client.wsid ASC limit $offset,$rows";
		$data_record = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$count = count($data_record);
		
		
		$no = 0;
		$prev_debit_100 = 0;
		$prev_kredit_100 = 0;
		$prev_debit_50 = 0;
		$prev_kredit_50 = 0;
		$prev_debit_20 = 0;
		$prev_kredit_20 = 0;
		$prev_saldo_100 = 0;
		$prev_saldo_50 = 0;
		$prev_saldo_20 = 0;
		$prev_saldo = 0;
		$saldo_100 = 0;
		$saldo_50 = 0;
		$saldo_20 = 0;
		$saldo = 0;
		$prev_ket = "";
		foreach($data_record as $r) {
			$no++;
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
			$data_save['wsid']				= $r->wsid;
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
			$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			if($res->cnt==0) {
				// $table = "jurnal_sync";
				// $res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_save), array(CURLOPT_BUFFERSIZE => 10));
				
				echo "<pre>";
				print_r($data_save);
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
		}
		
		if($count==$no) {
			return "done";
		}
	}
}
