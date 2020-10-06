<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Select extends REST_Controller {
	
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function query_get() {
		$query = $this->input->get('query');
		
		$result = $this->db->query($query)->row();
		
		$this->response($result, REST_Controller::HTTP_OK);
	}
	
	function query2_get() {
		$query = $this->input->get('query');
		
		$result = $this->db->query($query);
		
		if ($result) { 
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function query_all_get() {
		$query = $this->input->get('query');
		
		$result = $this->db->query($query)->result();
		
		$this->response($result, REST_Controller::HTTP_OK);
	}
	
	function insert_get() {
		$table = $this->input->get('table');
		$data = $this->input->get('data');
		
		// print_r($table);
		// print_r($data);
		$insert = $this->db->insert($table, $data);
		
		print_r($this->db->last_query()); 
		
		if ($insert) { 
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function update_seal_get() {
		$table = $this->input->get('table');
		$where = $this->input->get('where');
		$data = $this->input->get('data');
		
		$this->db->where('kode', $where);
		$update = $this->db->update($table, $data);
		
		if ($update) { 
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function update_get() {
		$table = $this->input->get('table');
		$data = $this->input->get('data');
		
		// $this->db->trans_start();
		$this->db->where('id', $data['id']);
		$update = $this->db->update($table, $data);
		
		if ($update) { 
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function delete_get() {
		$table = $this->input->get('table');
		$data = $this->input->get('data');
		
        $this->db->where('id', $data['id']);
        $delete = $this->db->delete($table);
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function index_get() {}
	function index_post() {}
	
	function select_branch_post() {
		$search = $this->input->post('search');
		$bank = $this->input->post('bank');
		if($search!="") {
			$search = "%".strtolower($search)."%";
		}
		if($bank!="") {
			$bank = "".strtolower($bank)."";
		}
		// $sql = "SELECT * FROM client WHERE bank LIKE '$bank' AND branch LIKE '$search'";
		$sql = "SELECT * FROM master_branch WHERE name LIKE '%$search%' GROUP BY name";
		// echo $sql;
		$result = $this->db->query($sql);
		
		$list = array();
		if ($result->num_rows() > 0) {
			$key=0;
			foreach ($result->result() as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->name; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
}