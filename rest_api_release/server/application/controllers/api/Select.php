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
            // $this->response($result, REST_Controller::HTTP_OK);
            echo $this->db->insert_id();
        } else {
            $this->response(array('status' => 'fail', REST_Controller::HTTP_BAD_GATEWAY));
        }
	}
	
	function query_delete_get() {
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
	
	function insert_get_id_get() {
		$table = $this->input->get('table');
		$data = $this->input->get('data');
		
		// print_r($table);
		// print_r($data);
		$insert = $this->db->insert($table, $data);
		echo $this->db->insert_id();
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
    
    private function _get_datatables_query()
    {
        $param = json_decode($this->input->get('data')['param'], true); 
        $post = $this->input->get('data')['post']; 
        
        $this->db->from($param['table']);
 
        $i = 0;
     
        foreach ($param['column_search'] as $item) // looping awal
        {
            if($post['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                 
                if($i===0) // looping awal
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $post['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $post['search']['value']);
                }
 
                if(count($param['column_search']) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($post['order'])) 
        {
            $this->db->order_by($param['column_order'][$post['order']['0']['column']], $post['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $param['order'];
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    
    function get_datatables()
    {
        $post = $this->input->get('data')['post']; 
        $this->_get_datatables_query();
        if($post['length'] != -1)
        $this->db->limit($post['length'], $post['start']);
        $this->db->where('status', 'available');
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_datatables2()
    {
        $post = $this->input->get('data')['post']; 
        $this->_get_datatables_query();
        if($post['length'] != -1)
        $this->db->limit($post['length'], $post['start']);
		$this->db->order_by('tanggal', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_datatables3()
    {
        $post = $this->input->get('data')['post']; 
        $this->_get_datatables_query();
        if($post['length'] != -1)
        $this->db->limit($post['length'], $post['start']);
		// $this->db->order_by('tanggal', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $param = json_decode($this->input->get('data')['param'], true); 
        $this->db->from($param['table']);
        return $this->db->count_all_results();
    }
	
	function datatables_get() {
	    $post = $this->input->get('data')['post']; 
	    $list = $this->get_datatables();
	    $data = array();
        $no = $post['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $field->kode;
            $row[] = $field->jenis;
            $row[] = $field->status;
            $row[] = '<div id="id" hidden>'.$field->kode.'</div><span><a class="button" id="detail_preview" href="#" title="Print">Print</a></span>';
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $post['draw'],
            "recordsTotal" => $this->count_all(),
            "recordsFiltered" => $this->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}
	
	
	
	function datatables2_get() {
	    $post = $this->input->get('data')['post']; 
		$param = json_decode($this->input->get('data')['param'], true); 
	    $list = $this->get_datatables2();
		
	    $data = array();
        $no = $post['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date("d-m-Y", strtotime($field->tanggal));
            $row[] = $field->bank;
            $row[] = $field->denom;
            $row[] = $field->value;
            $row[] = $field->seal;
            $row[] = date("H:i", strtotime($field->date_time));
            $row[] = $field->type_cassette;
            $row[] = $field->no_table;
            $row[] = $field->nama;
            $row[] = strtoupper($field->status);
            $row[] = '<div id="id" hidden>'.$field->id.'</div><span><a class="button" id="detail_preview" href="#" title="Print">Print</a></span>';
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $post['draw'],
            "recordsTotal" => $this->count_all(),
            "recordsFiltered" => $this->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}
	
	function datatables3_get() {
	    $post = $this->input->get('data')['post']; 
		$param = json_decode($this->input->get('data')['param'], true); 
	    $list = $this->get_datatables3();
		
		$base_url = "";
		
	    $data = array();
        $no = $post['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            // $row[] = $no;
            $row[] = $field->wsid;
            $row[] = $field->bank;
            $row[] = $field->type_mesin;
            $row[] = $field->type;
            $row[] = $field->tgl_ho;
            $row[] = '
				<span><a onClick="window.location.href=\''.$base_url.'client/summary/'.$field->wsid.'\'" href="#" title="Detail">Detail</a></span>
				<span><a class="action-icons c-edit" onClick="window.location.href=\''.$base_url.'client/edit/'.$field->id.'\'" href="#" title="Edit">Edit</a></span>
				
				<span><a class="action-icons c-delete" onClick="openDelete(\''.$field->id.'\', \''.$base_url.'client/delete\')" href="#" title="delete">Delete</a></span>
			';
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $post['draw'],
            "recordsTotal" => $this->count_all(),
            "recordsFiltered" => $this->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}
}