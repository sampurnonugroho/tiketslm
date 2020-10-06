<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("ticket_model");
        $this->load->model("inventory_model");
        $this->load->library('form_validation');
		$this->load->library('curl');

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));
			$level = trim($this->session->userdata('level'));

			$this->data['level'] = $level;

			// $this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			// $this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			// $this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			// $this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        $this->data['active_menu'] = "inventory";

        $this->data['datainventory1'] = json_decode($this->curl->simple_get(rest_api().'/master_inventory/index1'));
        $this->data['datainventory2'] = json_decode($this->curl->simple_get(rest_api().'/master_inventory/index2'));
        return view('admin/inventory/index', $this->data);
    }
	
	function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_inventory', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10)); 
		
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}

    public function add() {
        $this->data['active_menu'] = "inventory";
		$this->data['url'] = "inventory/save";
		$this->data['flag'] = "add";
		
		$this->data['id'] = "";		
		$this->data['name'] = "";
		$this->data['qty'] = "";
		$this->data['unit'] = "";
		$this->data['type'] = "";
		
        return view('admin/inventory/form', $this->data);
    }
	
	function save() {
		$name = strtoupper(trim($this->input->post('name')));
		$qty = strtoupper(trim($this->input->post('qty')));
		$unit = strtoupper(trim($this->input->post('unit')));
		$type = strtoupper(trim($this->input->post('type')));

		$data['name'] = $name;
		$data['qty'] = $qty;
		$data['unit'] = $unit;
		$data['type'] = $type;

		$insert =  $this->curl->simple_post(rest_api().'/master_inventory',$data, array(CURLOPT_BUFFERSIZE => 10)); 
		
		if (!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('inventory');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('inventory');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "inventory";
		$this->data['url'] = "inventory/update";
		$this->data['flag'] = "edit";
		
		$row = json_decode($this->curl->simple_get(rest_api().'/master_inventory?id='.$id))[0];

		$this->data['url'] = "inventory/update";
			
		$this->data['id'] = $id;		
		$this->data['name'] = $row->name;
		$this->data['qty'] = $row->qty;
		$this->data['unit'] = $row->unit;
		$this->data['type'] = $row->type;
		
		return view('admin/inventory/form', $this->data);
	}
	
	function update() {
		$id = strtoupper(trim($this->input->post('id')));
		$name = strtoupper(trim($this->input->post('name')));
		$qty = strtoupper(trim($this->input->post('qty')));
		$unit = strtoupper(trim($this->input->post('unit')));
		$type = strtoupper(trim($this->input->post('type')));

		$data['id'] = $id;
		$data['name'] = $name;
		$data['qty'] = $qty;
		$data['unit'] = $unit;
		$data['type'] = $type;

		$update =  $this->curl->simple_put(rest_api().'/master_inventory',$data, array(CURLOPT_BUFFERSIZE => 10)); 
		
		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('inventory');	
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('inventory');	
		}
	}
}