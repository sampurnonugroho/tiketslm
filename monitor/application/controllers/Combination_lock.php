<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Combination_lock extends CI_Controller {
    var $data = array();
	
	public function __construct() {
        parent::__construct();
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
		$this->data['active_menu'] = "combination_lock";
		
		$query = "SELECT * FROM combi_lock";
		
		$result = json_decode(
					$this->curl->simple_get(rest_api().'/select/query_all', 
						array('query'=>$query), 
						array(CURLOPT_BUFFERSIZE => 10)
					)
				);
				
		$list = array();
		$i = 0;
		foreach($result as $r) {
			$list[$i]['id'] = $r->id;
			$list[$i]['wsid'] = $r->wsid;
			$list[$i]['combination'] = $r->combination;
			$list[$i]['status'] = $r->status;
			
			$i++;
		}
		
		// echo "<pre>";
		// print_r($list);
		$this->data['data_combination'] = $list;
		
		
        return view('admin/combilock/index', $this->data);
	}

    public function index2() {
        $this->data['active_menu'] = "combination_lock";
        
        $query = "SELECT * FROM combi_lock";
        $this->data['data_combination'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));

        // print_r($this->data['data_combination']);
		
        return view('admin/combilock/index', $this->data);
    }
	
	public function edit($id) {
		$query = "SELECT combination FROM combi_lock WHERE id='$id'";
        $this->data['id'] = $id;
        $this->data['combination'] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->combination;
		
		return view('admin/combilock/form', $this->data);
	}
	
	public function update() {
		$data['id'] = trim($this->input->post('id'));
		$data['combination'] = trim($this->input->post('combination'));
		
		$table = "combi_lock";
        $result = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
        
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('combination_lock');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('combination_lock');	
		}
	}

    public function save_data() {
        $data['wsid'] = trim($this->input->post('wsid'));
		$data['combination'] = trim($this->input->post('combi'));
		$data['status'] = trim($this->input->post('status'));   

		$table = "combi_lock";
        $result = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
        
        if ($result === FALSE) {
			echo "failed";
		} else  {
			echo "success";
		}
    }
}