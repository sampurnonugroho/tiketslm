<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Master_zone extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function get_data_post() {
		$id = $this->post('id'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$query = $this->db->query("select SQL_CALC_FOUND_ROWS * FROM master_zone WHERE id_branch='$id' limit $offset,$rows")->result();
		$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
		$result["total"] = $rows['found_rows'];
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			// array_push($items, $row);
			$items[$i]['id'] = $row->id;
			$items[$i]['kode'] = $row->kode;
			$items[$i]['name'] = $row->name;
			$i++;
		}
		$result["rows"] = $items;
		
		$this->response($result, REST_Controller::HTTP_OK);
	}
	
	function index_get() {
		
	}
	
	function index_post() {
        $data = array(
                    'id_branch'          	 => $this->post('id_branch'),
                    'kode'          		 => $this->post('kode'),
                    'name'          		 => $this->post('name')
				);
        $insert = $this->db->insert('master_zone', $data);
        if ($insert) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
    }
	
	function index_put() {
		$id = $this->put('id');
		
        $data = array(
					'id'          	 		 => $this->put('id'),
					'id_branch'          	 => $this->put('id_branch'),
                    'kode'          		 => $this->put('kode'),
                    'name'          		 => $this->put('name')
				);
        $this->db->where('id', $id);
        $update = $this->db->update('master_zone', $data);
        if ($update) {
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function index_delete() {
		$id = $this->delete('id');
		
        $this->db->where('id', $id);
        $delete = $this->db->delete('master_zone');
        if ($delete) {
            $this->response(array('status' => 'success'), REST_Controller::HTTP_CREATED);
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
}