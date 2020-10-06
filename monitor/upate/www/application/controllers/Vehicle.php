<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("ticket_model");
        $this->load->model("vehicle_model");
        $this->load->library('form_validation');
		$this->load->library('curl');

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));
			$level = trim($this->session->userdata('level'));

			$this->data['level'] = $level;

			$this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			$this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			$this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			$this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        $this->data['active_menu'] = "vehicle";

        $this->data['datavehicle'] = json_decode($this->curl->simple_get(rest_api().'/master_vehicle'));
        return view('admin/vehicle/index', $this->data);
    }
	
	function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_vehicle', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));

		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}

    public function add() {
        $this->data['active_menu'] = "vehicle";
		$this->data['url'] = "vehicle/save";
		$this->data['flag'] = "add";
		
		$this->data['id'] = "";		
		$this->data['type'] = "";
		$this->data['police_number'] = "";
		$this->data['km_status'] = "";
		
		// $this->data['dd_driver'] = $this->model_app->dropdown_driver();
		// $this->data['id_driver'] = "";
		
		// $this->data['dd_custodian_1'] = $this->model_app->dropdown_custodian();
		// $this->data['id_custodian_1'] = "";
		
		// $this->data['dd_custodian_2'] = $this->model_app->dropdown_custodian();
		// $this->data['id_custodian_2'] = "";
		
		// $this->data['dd_army_1'] = $this->model_app->dropdown_army();
		// $this->data['id_army_1'] = "";
		
		// $this->data['dd_army_2'] = $this->model_app->dropdown_army();
		// $this->data['id_army_2'] = "";
		
        return view('admin/vehicle/form', $this->data);
    }
	
	function save() {
		$type = strtoupper(trim($this->input->post('type')));
		$police_number = strtoupper(trim($this->input->post('police_number')));
		$km_status = strtoupper(trim($this->input->post('km_status')));
		// $driver = strtoupper(trim($this->input->post('id_driver')));
		// $custodian_1 = strtoupper(trim($this->input->post('id_custodian_1')));
		// $custodian_2 = strtoupper(trim($this->input->post('id_custodian_2')));
		// $army_guard_1 = strtoupper(trim($this->input->post('id_army_1')));
		// $army_guard_2 = strtoupper(trim($this->input->post('id_army_2')));

		$data['type'] = $type;
		$data['police_number'] = $police_number;
		$data['km_status'] = $km_status;
		// $data['driver'] = $driver;
		// $data['custodian_1'] = $custodian_1;
		// $data['custodian_2'] = $custodian_2;
		// $data['army_guard_1'] = $army_guard_1;
		// $data['army_guard_2'] = $army_guard_2;

		$insert = $this->curl->simple_post(rest_api().'/master_vehicle',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('vehicle');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('vehicle');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "vehicle";
		$this->data['url'] = "vehicle/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM vehicle WHERE id = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));

		$this->data['url'] = "vehicle/update";
			
		$this->data['id'] = $id;		
		$this->data['type'] = $row->type;
		$this->data['police_number'] = $row->police_number;
		$this->data['km_status'] = $row->km_status;
		
		// $this->data['dd_driver'] = $this->model_app->dropdown_driver();
		// $this->data['id_driver'] = $row->driver;
		
		// $this->data['dd_custodian_1'] = $this->model_app->dropdown_custodian();
		// $this->data['id_custodian_1'] = $row->custodian_1;
		
		// $this->data['dd_custodian_2'] = $this->model_app->dropdown_custodian();
		// $this->data['id_custodian_2'] = $row->custodian_2;
		
		// $this->data['dd_army_1'] = $this->model_app->dropdown_army();
		// $this->data['id_army_1'] = $row->army_guard_1;
		
		// $this->data['dd_army_2'] = $this->model_app->dropdown_army();
		// $this->data['id_army_2'] = $row->army_guard_2;
		
		return view('admin/vehicle/form', $this->data);
	}
	
	function update() {
		$id = strtoupper(trim($this->input->post('id')));
		$type = strtoupper(trim($this->input->post('type')));
		$police_number = strtoupper(trim($this->input->post('police_number')));
		$km_status = strtoupper(trim($this->input->post('km_status')));
		// $driver = strtoupper(trim($this->input->post('id_driver')));
		// $custodian_1 = strtoupper(trim($this->input->post('id_custodian_1')));
		// $custodian_2 = strtoupper(trim($this->input->post('id_custodian_2')));
		// $army_guard_1 = strtoupper(trim($this->input->post('id_army_1')));
		// $army_guard_2 = strtoupper(trim($this->input->post('id_army_2')));

		$data['id'] = $id;
		$data['type'] = $type;
		$data['police_number'] = $police_number;
		$data['km_status'] = $km_status;
		// $data['driver'] = $driver;
		// $data['custodian_1'] = $custodian_1;
		// $data['custodian_2'] = $custodian_2;
		// $data['army_guard_1'] = $army_guard_1;
		// $data['army_guard_2'] = $army_guard_2;

		$update = $this->curl->simple_put(rest_api().'/master_vehicle',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('vehicle');	
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('vehicle');	
		}
	}
}