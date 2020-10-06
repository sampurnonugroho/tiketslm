<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cashprocessing extends CI_Controller {
    var $data = array();
	
	public function __construct() {
        parent::__construct();
		$this->load->model("ticket_model");
        $this->load->library('form_validation');

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));

			$this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			$this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			$this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			$this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
	}
	
	public function index() {
		$this->data['active_menu'] = "cashprocessing";
		
		$query = $this->db->query("select *, cashtransit.id as id_ct, IFNULL((SELECT COUNT(DISTINCT client.sektor) FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.id_cashtransit=cashtransit.id AND client.sektor NOT IN (SELECT run_number FROM runsheet_cashprocessing WHERE id_cashtransit=cashtransit.id) GROUP BY cashtransit_detail.id_cashtransit), 0) as count FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) ORDER BY cashtransit.id DESC");
        $this->data['data_cashprocessing'] = $query->result();
		
		// echo "<pre>";
		// print_r($this->data['data_cashprocessing']);
		// echo "</pre>";
		
        return view('admin/cashprocessing/index', $this->data);
	}
	
	public function add() {
        $this->data['active_menu'] = "cashprocessing";
		$this->data['url'] = "cashprocessing/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$branch = $this->db->query("SELECT branch FROM cashtransit WHERE id='$id'")->row()->branch;
		$branch = $this->db->query("SELECT name FROM master_branch WHERE id='$branch'")->row()->name;
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/cashprocessing/form', $this->data);
	}
	
	public function edit() {
		$this->data['active_menu'] = "cashprocessing";
		$this->data['url'] = "cashprocessing/save";
		$this->data['flag'] = "edit";
		
		$id = $this->uri->segment(3);
		
		$branch = $this->db->query("SELECT branch FROM cashtransit WHERE id='$id'")->row()->branch;
		$branch = $this->db->query("SELECT name FROM master_branch WHERE id='$branch'")->row()->name;
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/cashprocessing/form', $this->data);
	}
	
	function delete() {
		
	}
	
	public function get_data() {
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		// echo "<pre>";
		// print_r($id);
		// echo "</pre>";
		
		$query = $this->db->query("select count(*) as cnt FROM runsheet_cashprocessing WHERE id_cashtransit='".$id."'");
        $row = $query->row_array();
		$result["total"] = $row['cnt'];
		
		$query = $this->db->query("select * from runsheet_cashprocessing WHERE id_cashtransit='".$id."' limit $offset,$rows");
		
		$items = array();
		$i = 0;
		foreach($query->result() as $row){
			// array_push($items, $row);
			$items[$i]['id'] = $row->id;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['run_number'] = $row->run_number;
			$items[$i]['petty_cash'] = $row->petty_cash;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['id'] = $this->input->get('id');
		
		return view('admin/cashprocessing/show_form', $this->data);
	}
	
	public function suggest() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		$sql = "SELECT * FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN master_zone ON(client.sektor=master_zone.id) WHERE cashtransit_detail.id_cashtransit='$id_cashtransit' AND client.sektor NOT IN (SELECT run_number FROM runsheet_cashprocessing WHERE id_cashtransit='$id_cashtransit') GROUP BY client.sektor";
		// echo $sql;
		$result = $this->db->query($sql);
		// print_r($result->result());
		
		$list = array();
		if ($result->num_rows() > 0) {
			$key=0;
			foreach ($result->result() as $row) {
				$list[$key]['id'] = $row->sektor;
				$list[$key]['text'] = "(".$row->sektor.") ".$row->name; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function save_data() {
		// print_r($this->input->post());
		
		$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
		$run_number			= strtoupper(trim($this->input->post('run_number')));
		$petty_cash				= strtoupper(trim($this->input->post('petty_cash')));
		
		$data['id_cashtransit'] = $id_cashtransit;
		$data['run_number'] = $run_number;
		$data['petty_cash'] = $petty_cash;
		
		$this->db->trans_start();

		$this->db->insert('runsheet_cashprocessing', $data);

		$this->db->trans_complete();

		echo json_encode(array(
			'id_cashtransit' => $id_cashtransit,
			'run_number' => $run_number,
			'petty_cash' => $petty_cash
		));
	}
	
	function update_data() {
		$id = $this->input->get("id");
		
		$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
		$run_number			= strtoupper(trim($this->input->post('run_number')));
		$petty_cash				= strtoupper(trim($this->input->post('petty_cash')));
		
		$data['id_cashtransit'] = $id_cashtransit;
		$data['run_number'] = $run_number;
		$data['petty_cash'] = $petty_cash;
		
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->update('runsheet_cashprocessing', $data);

		$this->db->trans_complete();

		echo json_encode(array(
			'id_cashtransit' => $id_cashtransit,
			'run_number' => $run_number,
			'petty_cash' => $petty_cash
		));
	}
	
	function delete_data() {
		$id = $_POST['id'];

		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('runsheet_cashprocessing');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}