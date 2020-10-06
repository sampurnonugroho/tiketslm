<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kondisi extends CI_Controller {
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

			$this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			$this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			$this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			$this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        $this->data['active_menu'] = "kondisi";

        $datakondisi = json_decode($this->curl->simple_get(rest_api().'/master_kondisi'));
	    $this->data['datakondisi'] = $datakondisi;
		
        return view('admin/kondisi/index', $this->data);
    }

    public function add() {
        $this->data['active_menu'] = "kondisi";
		$this->data['url'] = "kondisi/save";
		$this->data['flag'] = "add";

		$this->data['id_kondisi'] = "";		
		$this->data['nama_kondisi'] = "";
		$this->data['waktu_respon'] = "";

        return view('admin/kondisi/form', $this->data);
    }

	function save() {
		$nama_kondisi = strtoupper(trim($this->input->post('nama_kondisi')));
		$waktu_respon = strtoupper(trim($this->input->post('waktu_respon')));

		$data['nama_kondisi'] = $nama_kondisi;
		$data['waktu_respon'] = $waktu_respon;

		$insert = $this->curl->simple_post(rest_api().'/master_kondisi',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('kondisi');
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('kondisi');
		}
	}

	public function edit($id) {
		$this->data['active_menu'] = "kondisi";
		$this->data['url'] = "kondisi/update";
		$this->data['flag'] = "edit";

		$sql = "SELECT * FROM kondisi WHERE id_kondisi = '$id'";
		$row = $this->db->query($sql)->row();

		$data['url'] = "kondisi/update";
			
		$this->data['id_kondisi'] = $id;		
		$this->data['nama_kondisi'] = $row->nama_kondisi;
		$this->data['waktu_respon'] = $row->waktu_respon;

		return view('admin/kondisi/form', $this->data);
	}

	function update() {
		$id_kondisi = strtoupper(trim($this->input->post('id_kondisi')));
		$nama_kondisi = strtoupper(trim($this->input->post('nama_kondisi')));
		$waktu_respon = strtoupper(trim($this->input->post('waktu_respon')));

		$data['id_kondisi'] = $id_kondisi;
		$data['nama_kondisi'] = $nama_kondisi;
		$data['waktu_respon'] = $waktu_respon;

		$update = $this->curl->simple_put(rest_api().'/master_kondisi',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('kondisi');
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('kondisi');
		}
	}

	function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_kondisi', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));

		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}