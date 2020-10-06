<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Seal extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function get_seal_get() {
		$id_detail = ($this->input->get('id_detail')=="" ? 31 : $this->input->get('id_detail'));
		$seal = ($this->input->get('seal')=="" ? "A50003" : $this->input->get('seal'));
		$act = ($this->input->get('act')=="" ? "ATM" : $this->input->get('act'));
		
		$param = "WHERE A.id='$id_detail'";
		
		$sql = "SELECT * FROM 
					cashtransit_detail A
						LEFT JOIN runsheet_cashprocessing B ON(A.id=B.id)
					$param
				";
		
		$row = $this->db->query($sql)->row_array();
		
		$array2 = array();
		if($act=="ATM") {
			$array = array();
			$array2 = array();
			for($i=1;$i<=$row['ctr'];$i++) {
				$array["cart_".$i."_seal"] = $row["cart_".$i."_seal"];
				$array2[$row["cart_".$i."_seal"]] = "cart_".$i."_seal";
			}
		} else if($act=="CRM") {
			$array = array();
			for($i=1;$i<=$row['ctr'];$i++) {
				if (strpos($row["cart_".$i."_seal"], ';') !== false) {
					list($sealz, $denom, $value) = explode(";", $row["cart_".$i."_seal"]);
				} else {
					$sealz = $row["cart_".$i."_seal"];
				}
				$array["cart_".$i."_seal"] = $sealz;
			}
		} else if($act=="CDM") {
			$array = array();
			for($i=1;$i<=$row['ctr'];$i++) {
				$array["cart_".$i."_seal"] = $row["cart_".$i."_seal"];
			}
		}
		
		$val = $this->searchForId($seal, $array);
	    $cart_no = $this->searchForId2($seal, $array2);
		
		$result['val'] = $val;
		
// 		if($val=="valid") {
			$row_seal = $this->db->query("SELECT * FROM run_status_cancel WHERE id_detail='$id_detail' AND cart_seal='$seal'")->num_rows();
			if($row_seal==0) {
			    $this->db->query("UPDATE runsheet_cashprocessing SET $cart_no='' WHERE id='$id_detail'");
				$data['id_cashtransit'] = $row['id_cashtransit'];
				$data['id_detail'] = $id_detail;
				$data['act'] = $act;
				$data['cart_no'] = $cart_no;
				$data['cart_seal'] = $seal;
				$this->db->insert('run_status_cancel', $data);
			} else {
			    $cart_no = $this->db->query("SELECT cart_no FROM run_status_cancel WHERE id_detail='$id_detail' AND cart_seal='$seal'")->row()->cart_no;
			    $this->db->query("UPDATE runsheet_cashprocessing SET $cart_no='$seal' WHERE id='$id_detail'");
				$this->db->where('id_detail', $id_detail);
				$this->db->where('cart_seal', $seal);
				$this->db->delete('run_status_cancel');
			}
// 		}
		// $list = array();
		// $list
		
		// echo json_encode($list);
		echo json_encode($result);	
	}
	
	function reset_cancel_get() {
		$id_detail = ($this->input->get('id_detail')=="" ? 31 : $this->input->get('id_detail'));
		$sql = "DELETE FROM run_status_cancel WHERE id_detail='$id_detail'";
		
		$cart_no = $this->db->query("SELECT cart_no, cart_seal FROM run_status_cancel WHERE id_detail='$id_detail'")->result_array();
		foreach($cart_no as $r) {
		    $cart_no = $r['cart_no'];
		    $seal = $r['cart_seal'];
		    $this->db->query("UPDATE runsheet_cashprocessing SET $cart_no='$seal' WHERE id='$id_detail'");
		}
		
		$this->db->query($sql);
	}
	
	function update_receipt_get() {
		$kode = $this->input->get('kode');
		$id_detail = $this->input->get('id_detail');
		
		$row_seal = $this->db->query("SELECT * FROM master_receipt WHERE UPPER(kode) = BINARY(kode) AND kode='$kode' AND status='available'")->num_rows();
		if($row_seal>0) {
			$this->db->query("UPDATE runsheet_cashprocessing SET receipt_roll='$kode' WHERE id='$id_detail'");
			$update = $this->db->query("UPDATE master_receipt SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$kode'");
			if ($update) {
				$result['data'] = "success";
			} else {
				$result['data'] = "failed";
			}
		} else {
			$result['data'] = "Already Used";
		}
		// $update = $this->db->query("UPDATE master_receipt SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$kode'");
		
		// if ($update) {
			// $result['data'] = "success";
		// } else {
			// $result['data'] = "failed";
		// }
		
		echo json_encode($result);
	}
	
	function update_receipt_flm_get() {
		$kode = $this->input->get('kode');
		$id_detail = $this->input->get('id_detail');
		
		$row_seal = $this->db->query("SELECT * FROM master_receipt WHERE UPPER(kode) = BINARY(kode) AND kode='$kode' AND status='available'")->num_rows();
		if($row_seal>0) {
			$this->db->query("UPDATE flm_trouble_ticket SET receipt_roll2='$kode' WHERE id='$id_detail'");
			$update = $this->db->query("UPDATE master_receipt SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$kode'");
			if ($update) {
				$result['data'] = "success";
			} else {
				$result['data'] = "failed";
			}
		} else {
			$result['data'] = "Already Used";
		}
		// $update = $this->db->query("UPDATE master_receipt SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$kode'");
		
		// if ($update) {
			// $result['data'] = "success";
		// } else {
			// $result['data'] = "failed";
		// }
		
		echo json_encode($result);
	}
	
	function searchForId($id, $array) {
		foreach ($array as $key => $val) {
			if ($val === $id) {
				// return $val;
				return "valid";
			}
		}
		return "invalid";
	}
	
	function searchForId2($id, $array) {
		foreach ($array as $key => $val) {
			if ($key === $id) {
				// return $val;
				return $val;
			}
		}
		return "invalid";
	}
}