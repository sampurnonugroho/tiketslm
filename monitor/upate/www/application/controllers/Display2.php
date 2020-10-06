<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Display2 extends CI_Controller {
	var $data = array();

	public function __construct() {
        parent::__construct();
        $this->load->model("ticket_model");
		$this->load->library('form_validation');
		
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
		$this->data['active_menu'] = "display2";
		
		// $sql_ticket = "SELECT COUNT(id_ticket) AS jml_ticket FROM ticket";
		// $row_ticket = $this->db->query($sql_ticket)->row();

		// $sql_user = "SELECT COUNT(id_user) AS jml_user FROM user";
		// $row_user = $this->db->query($sql_user)->row();

		// $sql_karyawan = "SELECT COUNT(nik) AS jml_karyawan FROM karyawan";
		// $row_karyawan = $this->db->query($sql_karyawan)->row();

		// $sql_teknisi = "SELECT COUNT(id_teknisi) AS jml_teknisi FROM teknisi";
		// $row_teknisi = $this->db->query($sql_teknisi)->row();

		// $sql_flm = "SELECT *, client.bank as nama_bank FROM flm_trouble_ticket LEFT JOIN teknisi ON(flm_trouble_ticket.teknisi_1=teknisi.id_teknisi) LEFT JOIN karyawan ON(karyawan.nik=teknisi.nik) LEFT JOIN client ON(client.id=flm_trouble_ticket.id_bank)";
		
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
		
		// $this->data['jml_ticket'] = $row_ticket->jml_ticket;
		// $this->data['jml_user'] = $row_user->jml_user;
		// $this->data['jml_karyawan'] = $row_karyawan->jml_karyawan;
		// $this->data['jml_teknisi'] = $row_teknisi->jml_teknisi;
		// $this->data['data_flm'] = $list;
		
		return view('admin/display2/index', $this->data);
	}
}
