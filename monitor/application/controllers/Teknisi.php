<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Teknisi extends CI_Controller {
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
        $this->data['active_menu'] = "teknisi";

		$sql = "SELECT * FROM teknisi LEFT JOIN karyawan ON(teknisi.nik=karyawan.nik)";
		$datateknisi = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
	    $this->data['datateknisi'] = $datateknisi;
        return view('admin/teknisi/index', $this->data);
    }

	function delete() {
		$id = $_POST['id'];

		$delete =  $this->curl->simple_delete(rest_api().'/master_teknisi', array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10));

		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}

    public function add() {
        $this->data['active_menu'] = "teknisi";
		$this->data['url'] = "teknisi/save";
		$this->data['flag'] = "add";

		$this->data['dd_karyawan'] = $this->model_app->dropdown_karyawan();
		$this->data['id_karyawan'] = "";

		$this->data['dd_kategori'] = $this->model_app->dropdown_kategori();
		$this->data['id_kategori'] = "";

		$this->data['id_teknisi'] = "";

        return view('admin/teknisi/form', $this->data);
    }

	function save() {

		$id_karyawan = strtoupper(trim($this->input->post('id_karyawan')));
		$id_kategori = strtoupper(trim($this->input->post('id_kategori')));

		$data['nik'] = $id_karyawan;
		$data['id_kategori'] = $id_kategori;


		$insert = $this->curl->simple_post(rest_api().'/master_teknisi',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$insert) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('teknisi');
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('teknisi');
		}
	}

	public function edit($id) {
		$this->data['active_menu'] = "teknisi";
		$this->data['url'] = "teknisi/update";
		$this->data['flag'] = "edit";

		$sql = "SELECT * FROM teknisi WHERE id_teknisi = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));

		$this->data['dd_karyawan'] = $this->model_app->dropdown_karyawan();
		$this->data['id_karyawan'] = $row->nik;

		$this->data['dd_kategori'] = $this->model_app->dropdown_kategori();
		$this->data['id_kategori'] = $row->id_kategori;

		$this->data['id_teknisi'] = $id;

		return view('admin/teknisi/form', $this->data);
	}

	function update() {
		$id_teknisi = strtoupper(trim($this->input->post('id_teknisi')));

		$id_kategori = strtoupper(trim($this->input->post('id_kategori')));
		$data['id_teknisi'] = $id_teknisi;
		$data['id_kategori'] = $id_kategori;


		$update = $this->curl->simple_put(rest_api().'/master_teknisi',$data,array(CURLOPT_BUFFERSIZE => 10));

		if (!$update) {
			$this->session->set_flashdata('error', 'Data gagal diupdate.');
			redirect('teknisi');
		} else  {
			$this->session->set_flashdata('success', 'Data terupdate.');
			redirect('teknisi');
		}
	}
}