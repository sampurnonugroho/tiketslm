<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt extends CI_Controller {
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
		} else {
            redirect('');
        }
	}
	
	public function index() {
		$this->data['active_menu'] = "receipt";

		$query = "
			SELECT 
				* 
			FROM 
				master_receipt
		";
		$seal_awal = json_decode(
						$this->curl->simple_get(rest_api().'/select/query', 
							array('query'=>"
								SELECT kode FROM master_receipt ORDER BY id ASC
							"), 
							array(CURLOPT_BUFFERSIZE => 10)
						)
					)->kode;
		$seal_akhir = json_decode(
						$this->curl->simple_get(rest_api().'/select/query', 
							array('query'=>"
								SELECT kode FROM master_receipt ORDER BY id DESC
							"), 
							array(CURLOPT_BUFFERSIZE => 10)
						)
					)->kode;
		$count = json_decode(
						$this->curl->simple_get(rest_api().'/select/query', 
							array('query'=>"
								SELECT COUNT(*) as cnt FROM master_receipt ORDER BY id DESC
							"), 
							array(CURLOPT_BUFFERSIZE => 10)
						)
					)->cnt;
		
		$res = array(
			array(
				"seal" => $seal_awal." - ".$seal_akhir,
				"count" => $count
			)
		);
		
		$query = "
			SELECT 
				*,
				(SELECT COUNT(*) as cnt FROM master_receipt WHERE date=A.date) as count,
				(SELECT kode FROM master_receipt WHERE date=A.date ORDER BY id ASC LIMIT 0,1) as seal_awal,
				(SELECT kode FROM master_receipt WHERE date=A.date ORDER BY id DESC LIMIT 0,1) as seal_akhir
			FROM 
				master_receipt as A
			GROUP BY A.date
		";
		
		$result = json_decode(
					$this->curl->simple_get(rest_api().'/select/query_all', 
						array('query'=>$query), 
						array(CURLOPT_BUFFERSIZE => 10)
					)
				);
				
		$list = array();
		$i = 0;
		foreach($result as $r) {
			$list[$i]['date'] = $r->date;
			$list[$i]['seal'] = $r->seal_awal." - ".$r->seal_akhir;
			$list[$i]['count'] = $r->count;
			
			$i++;
		}
		
		// echo "<pre>";
		// print_r($result);
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$this->data['data_receipt'] = $list;
		
		
        return view('admin/receipt/index', $this->data);
	}
	
	public function detail() {
		$this->data['active_menu'] = "receipt";

		$query = "SELECT * FROM master_receipt";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$this->data['data_receipt'] = $result;
		
		
        return view('admin/receipt/index', $this->data);
	}
	
	public function save() {
		$value = $this->uri->segment(3);
		list($dari, $hingga) = explode("-", $value);
		$date = date("Y-m-d H:i:s");
		
		for($i = $dari; $i<=$hingga; $i++) {
			$kode = "RC".sprintf('%05d', $i);
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM master_receipt WHERE UPPER(kode) = BINARY(kode) AND kode='$kode'";
			$result = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			if(count($result)==0) {
				$query2 = "INSERT INTO `master_receipt`(`kode`, `jenis`, `status`, `date`) VALUES ('$kode','receipt','available','$date')";
				$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query2), array(CURLOPT_BUFFERSIZE => 10));
			}
		}
	}
	
	public function delete() {
		$id = $_POST['id'];

		// $delete =  $this->curl->simple_delete(rest_api().'/master_receipt', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10)); 
		
		// if (!$delete) {
			// $this->session->set_flashdata('error', 'Data gagal dihapus.');
			// echo "failed";
		// } else  {
			// $this->session->set_flashdata('success', 'Data dihapus.');
			// echo "success";
		// }
		
		$query = "DELETE FROM master_receipt WHERE date='$id'";
		$delete = $this->curl->simple_get(rest_api().'/select/query_delete', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10));
		
		if ($delete) {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		} else  {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		}
	}
}