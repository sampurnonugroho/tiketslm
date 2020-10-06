<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Plan_cashtransit extends REST_Controller {
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
	
	function get_data_post() {
		$id = $this->post('id'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$query = $this->db->query("select SQL_CALC_FOUND_ROWS 
										*, 
										cashtransit_detail.id as id_ct, 
										cashtransit_detail.ctr as ttl_ctr 
									FROM cashtransit_detail 
										LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
										LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
									WHERE 
										cashtransit_detail.state='ro_cit' AND 
										id_cashtransit='".$id."' LIMIT $offset,$rows")->result();
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
			if($row->id_pengirim!=0){
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_pengirim."'")->row()->sektor;
			} else {
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_penerima."'")->row()->sektor;
			}
			$items[$i]['pengirim'] = ($row->id_pengirim==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_pengirim."'")->row()->nama_client);
			$items[$i]['penerima'] = ($row->id_penerima==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_penerima."'")->row()->nama_client);
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