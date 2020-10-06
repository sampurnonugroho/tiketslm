<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	var $data = array();

	public function __construct() {
        parent::__construct();
        $this->load->model("ticket_model");
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
	
	public function index()
	{	
		// echo "<pre>";
		// print_r($this->data["session"]);
		
		// echo '<li class="logout"><a href="'.base_url().'"><span class="icon"></span>Logout</a></li>';
		
		$this->data['active_menu'] = "dashboard";
		
		$sql_ticket = "SELECT COUNT(id_ticket) AS jml_ticket FROM ticket";
		$row_ticket = $this->db->query($sql_ticket)->row();

		$sql_user = "SELECT COUNT(id_user) AS jml_user FROM user";
		$row_user = $this->db->query($sql_user)->row();
		
		$sql_user = "SELECT COUNT(id_user) AS jml_user FROM user";
        $row_user = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_user), array(CURLOPT_BUFFERSIZE => 10)));

		// $sql_karyawan = "SELECT COUNT(nik) AS jml_karyawan FROM karyawan";
		// $row_karyawan = $this->db->query($sql_karyawan)->row();
		
		$sql_karyawan = "SELECT COUNT(nik) AS jml_karyawan FROM karyawan";
        $row_karyawan = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_karyawan), array(CURLOPT_BUFFERSIZE => 10)));

		// $sql_teknisi = "SELECT COUNT(id_teknisi) AS jml_teknisi FROM teknisi";
		// $row_teknisi = $this->db->query($sql_teknisi)->row();
		
		$sql_teknisi = "SELECT COUNT(id_teknisi) AS jml_teknisi FROM teknisi";
        $row_teknisi = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_teknisi), array(CURLOPT_BUFFERSIZE => 10)));

		// $sql_flm = "SELECT *, client.bank as nama_bank FROM flm_trouble_ticket LEFT JOIN teknisi ON(flm_trouble_ticket.teknisi_1=teknisi.id_teknisi) LEFT JOIN karyawan ON(karyawan.nik=teknisi.nik) LEFT JOIN client ON(client.id=flm_trouble_ticket.id_bank)";
		
		$sql_ticket = "SELECT COUNT(id_ticket) AS jml_ticket FROM flm_trouble_ticket";
        $row_ticket = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_ticket), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		$sql_client_atm = "SELECT COUNT(id) AS jml_client FROM client";
        $row_client_atm = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_client_atm), array(CURLOPT_BUFFERSIZE => 10)));
		
		$sql_client_cit = "SELECT COUNT(id) AS jml_client FROM client_cit";
        $row_client_cit = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_client_cit), array(CURLOPT_BUFFERSIZE => 10)));
		
		$jml_client = 0;
		$jml_client = intval($row_client_atm->jml_client) + intval($row_client_cit->jml_client);
		
		$sql_runsheet = "SELECT COUNT(cashtransit_detail.id) AS jml_runsheet FROM cashtransit_detail 
							LEFT JOIN runsheet_cashprocessing ON (cashtransit_detail.id=runsheet_cashprocessing.id) 
								WHERE cashtransit_detail.id IN (SELECT id FROM runsheet_cashprocessing)";
        $row_runsheet = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_runsheet), array(CURLOPT_BUFFERSIZE => 10)));
		// $list = array();
		// $key=0;
		// // foreach($this->db->query($sql_flm)->result() as $r) {
			// // $list[$key]['id_ticket'] = $r->id_ticket;
			// // $list[$key]['nama'] = $r->nama;
			// // $list[$key]['nama_bank'] = $r->nama_bank;
			// // $list[$key]['lokasi'] = $r->lokasi;
			// // $list[$key]['lokasi'] = $r->lokasi;
			
			// // $problem = array();
			// // foreach(json_decode($r->problem_type) as $p) {
				// // $problem[] = $this->db->query("SELECT nama_kategori FROM kategori WHERE id_kategori='".$p."'")->row()->nama_kategori;
			// // }
			// // $list[$key]['problem_type'] = implode(', ', $problem);
			// // $key++;
		// // }
		
		$sql_statusflm = "SELECT *, flm_trouble_ticket.status as status_ticket FROM flm_trouble_ticket LEFT JOIN client 					ON(flm_trouble_ticket.id_bank=client.id) WHERE id_ticket NOT IN (SELECT id_ticket FROM slm_trouble_ticket)";
        $row_statusflm = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql_statusflm), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['statusflm'] = $row_statusflm;
		$this->data['db'] = $this->db;
		
		$this->data['jml_ticket'] = $row_ticket->jml_ticket;
		$this->data['jml_user'] = $row_user->jml_user;
		$this->data['jml_karyawan'] = $row_karyawan->jml_karyawan;
		$this->data['jml_teknisi'] = $row_teknisi->jml_teknisi;
		$this->data['jml_client'] = $jml_client;
		$this->data['jml_runsheet'] = intval($row_runsheet->jml_runsheet);
		
		return view('admin/dashboard', $this->data);
	}
	
	public function detail() {
		$id = $this->uri->segment(3);
		$this->data['active_menu'] = "dashboard";
		
		$this->data['id_ticket'] = $id;
		
		$sql_ticket = "SELECT * FROM flm_trouble_ticket WHERE id_ticket='$id'";
        $row_ticket = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql_ticket), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['data_ticket'] = $row_ticket;
		
		return view('admin/dashboard_detail', $this->data);
	}
}
