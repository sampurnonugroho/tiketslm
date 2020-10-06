<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpc_prepared extends CI_Controller {
    public function __construct() {
        parent::__construct();
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
		$this->data['active_menu'] = "cpc_prepared";
		
		
		$query = "SELECT * FROM cpc_prepared WHERE tanggal='".date("Y-m-d")."'";
		$this->data['data_prepared'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		return view('admin/cpc_prepared/index', $this->data);
    }
	
    public function stock() {    
		$this->data['active_menu'] = "cpc_prepared_stock";
		
		$query = "SELECT * FROM cpc_prepared";
		$this->data['data_prepared'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$query = "SELECT count(*) as cnt FROM cpc_prepared WHERE status='used'";
		$this->data['jumlah_used'] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		$query = "SELECT count(*) as cnt FROM cpc_prepared WHERE status='ready'";
		$this->data['jumlah_ready'] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		$query = "SELECT count(*) as cnt FROM cpc_prepared WHERE status!='used' AND status!='ready'";
		$this->data['jumlah_lain'] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		return view('admin/cpc_prepared/index_stock', $this->data);
	}
	
	public function server_processing() {
		$param['table'] = 'cpc_prepared';
		$param['column_order'] = array('bank', 'seal'); //field yang ada di table user
		$param['column_search'] = array('bank', 'seal'); //field yang diizin untuk pencarian 
		$param['order'] = array('tanggal' => 'desc');
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		echo $this->curl->simple_get(rest_api().'/select/datatables2', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
    }
	
    public function detail($id) {    
		$this->data['active_menu'] = "cpc_record";
		
		$query = "SELECT * FROM cpc_repared_detail WHERE id_detail='$id'";
		$this->data['data_prepared'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['id_detail'] = $id;
		
		return view('admin/cpc_prepared/index_detail', $this->data);
    }
	
	public function check_seal() {
		// print_r($_REQUEST);
		$kode = $this->input->post('value');
		
		$table = "
			SELECT *
			FROM cashtransit_detail 
			LEFT JOIN runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
		";
		
		$num1 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT C.cart_1_seal, C.cart_2_seal, C.cart_3_seal, C.cart_4_seal, C.cart_5_seal, C.divert, count(*) as cnt 
			FROM cashtransit_detail AS A LEFT JOIN runsheet_cashprocessing AS C ON(C.id=A.id) 
			WHERE
				LOWER(C.cart_1_seal) = '".$kode."' OR
				LOWER(C.cart_2_seal) = '".$kode."' OR
				LOWER(C.cart_3_seal) = '".$kode."' OR
				LOWER(C.cart_4_seal) = '".$kode."' OR
				LOWER(C.cart_5_seal) = '".$kode."' OR
				LOWER(C.divert) = '".$kode."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$num2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT count(*) as cnt FROM cpc_prepared WHERE seal='".$kode."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$num3 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE LOWER(kode) = BINARY(kode) AND kode='$kode' AND status='used'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$debug = "RESULT FROM cashprocessing : ".$num1->cnt;
		$debug .= "\nRESULT FROM cpc_prepared : ".$num2->cnt;
		$debug .= "\nRESULT FROM master_seal : ".$num3->cnt;
		$debug .= "\nRESULT SUM : ".($num1->cnt+$num2->cnt+$num3->cnt);
		
		$data['debug'] = $debug;
		$data['result'] = ($num1->cnt+$num2->cnt+$num3->cnt);
		
		echo json_encode($data);
	}
	
	public function select_bank() {
		$search = $this->input->post('search');
		
		$query = "SELECT * FROM client WHERE bank LIKE '%$search%' GROUP BY bank";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->bank;
				$list[$key]['text'] = $row->bank; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function select_mesin() {
		$search = $this->input->post('search');
		$bank = $this->input->post('bank');
		
		$query = "SELECT * FROM client WHERE bank='$bank' AND wsid LIKE '%$search%'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->wsid;
				$list[$key]['text'] = $row->wsid; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function get_data_kasir() {
		$query = "SELECT * FROM karyawan LEFT JOIN jabatan ON(karyawan.id_jabatan=jabatan.id_jabatan) WHERE jabatan.nama_jabatan='CASHIER'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->nama;
				$list[$key]['text'] = $row->nama; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function save_data() {
		
		$data = array();
		$data['tanggal'] = date("Y-m-d");
		$data['bank'] = $_REQUEST['bank'];
		$data['denom'] = $_REQUEST['denom'];
		$data['value'] = str_replace(",", "", $_REQUEST['value']);
		$data['seal'] = $_REQUEST['seal'];
		$data['date_time'] = date("Y-m-d H:i:s");
		$data['type_cassette'] = $_REQUEST['type_cassette'];
		$data['type'] = $_REQUEST['type'];
		$data['no_table'] = $_REQUEST['table'];
		$data['nama'] = $_REQUEST['cashier'];
		$data['status'] = "ready";
		
		$num = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT count(*) as cnt, id FROM cpc_prepared WHERE tanggal='".$data['tanggal']."' AND bank='".$data['bank']."' AND seal='".$data['seal']."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		
		if($num->cnt==0) {
			$table = "cpc_prepared";
			$result = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			
			if($result) {
				echo "success";
			} else {
				echo "failed";
			}
		} else {
			$table = "cpc_prepared";
			$data['id'] = $num->id;
			$result = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			if($result) {
				echo "success";
			} else {
				echo "failed";
			}
		}
	}
	
	public function save_data_audit() {
		$data = array();
		$seal = $_REQUEST['seal'];
		$status = $_REQUEST['status'];
		$remark = $_REQUEST['remark'];
		
		$query = "UPDATE cpc_prepared SET status='$status', remark='$remark' WHERE seal='$seal'";
		$result = $this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10));
		if(!$result) {
			echo "success";
		} else {
			echo "failed";
		}
	}
	
	public function save_data_detail() {
		$data = array();
		$data['id_detail'] = $_REQUEST['id_detail'];
		$data['seal'] = $_REQUEST['seal'];
		$data['value'] = str_replace(",", "", $_REQUEST['value']);
		$data['type_cassette'] = $_REQUEST['type'];
		
		$num = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT count(*) as cnt, id FROM cpc_repared_detail WHERE id_detail='".$data['id_detail']."' AND seal='".$data['seal']."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		
		if($num->cnt==0) {
			$table = "cpc_repared_detail";
			$result = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			
			if($result) {
				echo "success";
			} else {
				echo "failed";
			}
		} else {
			$table = "cpc_repared_detail";
			$data['id'] = $num->id;
			$result = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			if($result) {
				echo "success";
			} else {
				echo "failed";
			}
		}
	}
	
	public function delete() {
		$data['id'] = $_POST['id'];
		
		$table = "cpc_prepared";
		$delete = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
	
	public function delete_detail() {
		$data['id'] = $_POST['id'];
		
		$table = "cpc_repared_detail";
		$delete = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}