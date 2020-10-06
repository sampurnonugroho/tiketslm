<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Plan_cashreplenish extends REST_Controller {
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
			$q = "select *, cashtransit.id as id_ct FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) ORDER BY cashtransit.id DESC";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "select *, cashtransit.id as id_ct FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) WHERE cashtransit.id='$id' ORDER BY cashtransit.id DESC";
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
	
	function add_master_post() {
		$id = $this->input->post("id");
		
		$q = 'SELECT * FROM cashtransit WHERE date="'.date("Y-m-d").'" AND branch="'.$id.'"';
		$query = $this->db->query($q);
		
		if($query->num_rows()==0) {
			$data['date'] = date("Y-m-d");
			$data['branch'] = $id;
			$this->db->insert('cashtransit', $data);
			$this->data['id'] = $this->db->insert_id();
		} else {
			$row = $query->row();
			$this->data['id'] = $row->id;
		}
		
		echo $this->data['id'];
	}
	
	function suggest_data_client_post() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		$sql = "SELECT * FROM client WHERE client.id NOT IN (SELECT id_bank FROM cashtransit_detail WHERE cashtransit_detail.id_cashtransit='$id_cashtransit') AND client.cabang IN (SELECT branch FROM cashtransit WHERE id='$id_cashtransit')";
		
		$result = $this->db->query($sql);
		
		$list = array();
		if ($result->num_rows() > 0) {
			$key=0;
			foreach ($result->result() as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->wsid.' - '.$row->bank.' - '.$row->type; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function suggest_data_client2_post() {
		$search = $this->input->post('search');
		$id_client = $this->input->post('id_client');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		$sql = "SELECT * FROM client WHERE client.id NOT IN (SELECT id_bank FROM cashtransit_detail WHERE cashtransit_detail.id_cashtransit='$id_cashtransit' AND id_bank!='$id_client')";
		
		$result = $this->db->query($sql);
		
		$list = array();
		if ($result->num_rows() > 0) {
			$key=0;
			foreach ($result->result() as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->wsid.' - '.$row->bank.' - '.$row->lokasi; 
				$key++;
			}
			
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function get_data_post() {
		$id = $this->post('id'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$query = $this->db->query("select SQL_CALC_FOUND_ROWS *, cashtransit_detail.id as id_ct, cashtransit_detail.ctr as ttl_ctr from cashtransit_detail left join client on(cashtransit_detail.id_bank=client.id) LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) WHERE cashtransit_detail.state='ro_atm' AND id_cashtransit='".$id."' limit $offset,$rows")->result();
		$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
		$result["total"] = $rows['found_rows'];
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			// array_push($items, $row);
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['id_bank'] = $row->id_bank;
			$items[$i]['wsid'] = $row->wsid;
			$items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
			$items[$i]['bank'] = $row->bank;
			$items[$i]['lokasi'] = $row->lokasi;
			$items[$i]['sektor'] = $row->sektor;
			$items[$i]['jenis'] = $row->type;
			$items[$i]['denom'] = $row->denom;
			$items[$i]['brand'] = $row->vendor;
			$items[$i]['model'] = $row->type_mesin;
			$items[$i]['pcs_100000'] = $row->pcs_100000;
			$items[$i]['pcs_50000'] = $row->pcs_50000;
			$items[$i]['pcs_20000'] = $row->pcs_20000;
			$items[$i]['pcs_10000'] = $row->pcs_10000;
			$items[$i]['pcs_5000'] = $row->pcs_5000;
			$items[$i]['pcs_2000'] = $row->pcs_2000;
			$items[$i]['pcs_1000'] = $row->pcs_1000;
			$items[$i]['pcs_coin'] = $row->pcs_coin;
			$items[$i]['ctr'] = $row->ttl_ctr;
			$items[$i]['total'] = $row->total;
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