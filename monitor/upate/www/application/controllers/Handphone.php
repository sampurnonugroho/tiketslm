<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Handphone extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("ticket_model");
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
        $this->data['active_menu'] = "handphone";

        $this->data['datahandphone'] = json_decode($this->curl->simple_get(rest_api().'/master_handphone'));
        return view('admin/handphone/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "handphone";
		$this->data['url'] = "handphone/save";
		$this->data['flag'] = "add";
		
		$this->data['id'] = "";		
		$this->data['type'] = "";
		$this->data['no'] = "";
		$this->data['imei'] = "";
		$this->data['number'] = "";
		
        return view('admin/handphone/form', $this->data);
    }
	
	function save() {
		$type = trim($this->input->post('type'));
		$no = trim($this->input->post('no'));
		$imei = trim($this->input->post('imei'));
		$number = trim($this->input->post('number'));

		$data['type'] = $type;
		$data['no'] = $no;
		$data['imei'] = $imei;
		$data['number'] = $number;

		$insert = $this->curl->simple_post(rest_api().'/master_handphone',$data, array(CURLOPT_BUFFERSIZE => 10));
		
		if (!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('handphone');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('handphone');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "handphone";
		$this->data['url'] = "handphone/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM handphone WHERE id = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));

		$this->data['url'] = "handphone/update";
			
		$this->data['id'] = $id;		
		$this->data['type'] = $row->type;
		$this->data['no'] = $row->no;
		$this->data['imei'] = $row->imei;
		$this->data['number'] = $row->number;
		
		return view('admin/handphone/form', $this->data);
	}
	
	function update() {
		$id = trim($this->input->post('id'));
		$type = trim($this->input->post('type'));
		$no = trim($this->input->post('no'));
		$imei = trim($this->input->post('imei'));
		$number = trim($this->input->post('number'));

		$data['id'] = $id;
		$data['type'] = $type;
		$data['no'] = $no;
		$data['imei'] = $imei;
		$data['number'] = $number;

		$update = $this->curl->simple_put(rest_api().'/master_handphone',$data,array(CURLOPT_BUFFERSIZE => 10));
		
		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('handphone');	
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('handphone');	
		}
	}
	
	function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_handphone', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));
		
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}