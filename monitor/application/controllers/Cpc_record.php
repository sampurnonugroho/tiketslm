<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpc_record extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
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
	
    public function index() {    
		$this->data['active_menu'] = "cpc_record";
		
		$this->data['data_record'] = $this->db->query("SELECT * FROM cpc_record")->result();
		
		return view('admin/cpc_record/index', $this->data);
    }
	
    public function add() {    
		$this->data['active_menu'] = "cpc_record";
		$this->data['url'] = "cpc_record/save";
		$this->data['flag'] = "add";
		$this->data['newdata'] = true;
		
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM cpc_record";
		$result = $this->db->query($query)->result();
        $sql = "SELECT FOUND_ROWS() AS `found_rows`;";
		$rows = $this->db->query($sql)->row_array();
		$total_rows = $rows['found_rows'];
		
		if($total_rows==0) {
			$this->data['newdata'] = true;
		} else {
			$this->data['newdata'] = false;
		}
		
		$this->data['id'] = "";
		$this->data['tanggal'] = "";
		$this->data['catatan'] = "";
		$this->data['keterangan'] = "";
		$this->data['posisi'] = "";
		$this->data['debit_100'] = "";
		$this->data['debit_50'] = "";
		$this->data['kredit_100'] = "";
		$this->data['kredit_50'] = "";
		
		// echo "<pre>";
		// print_r($result);
		// print_r($total_rows);
		// echo "</pre>";
		
		return view('admin/cpc_record/form', $this->data);
    }
	
    public function edit($id) {    
		$this->data['active_menu'] = "cpc_record";
		$this->data['url'] = "cpc_record/update";
		$this->data['flag'] = "edit";
		$this->data['newdata'] = true;
		
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM cpc_record WHERE id = '$id'";
		$row = $this->db->query($query)->row();
		$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
		$rows = $this->db->query($sql)->row_array();
		$total_rows = $rows['found_rows'];
		
		if($row->keterangan=="saldo_awal") {
			$this->data['newdata'] = true;
		} else {
			$this->data['newdata'] = false;
		}
		
		$this->data['id'] = $id;
		$this->data['tanggal'] = $row->tanggal;
		$this->data['catatan'] = $row->catatan;
		$this->data['keterangan'] = $row->keterangan;
		$this->data['posisi'] = $row->posisi;
		$this->data['debit_100'] = ($row->debit_100==0 ? "" : $row->debit_100);
		$this->data['debit_50'] = ($row->debit_50==0 ? "" : $row->debit_50);
		$this->data['kredit_100'] = ($row->kredit_100==0 ? "" : $row->kredit_100);
		$this->data['kredit_50'] = ($row->kredit_50==0 ? "" : $row->kredit_50);
		
		// echo "<pre>";
		// print_r($total_rows);
		// echo "</pre>";
		
		return view('admin/cpc_record/form', $this->data);
    }
	
	public function save() {
		print_r($_REQUEST);
		$keterangan = trim($this->input->post('keterangan'));
		
		$data = array();
		if($keterangan=="saldo_awal") {
			$data['keterangan'] = $keterangan;
			$data['posisi'] = "debit";
			$data['debit_100'] = trim($this->input->post('debit_100'));
			$data['debit_50'] = trim($this->input->post('debit_50'));
			// $data['saldo_100'] = $data['debit_100'];
			// $data['saldo_50'] = $data['debit_50'];
			// $data['saldo'] = $data['debit_100'] + $data['debit_50'];
			
			$result = $this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM cpc_record")->result();
			$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
			$total_rows = $rows['found_rows'];
			
			if($total_rows==0) {
				$this->db->trans_start();

				$this->db->insert('cpc_record', $data);

				$this->db->trans_complete();
			}
		} else {
			$posisi = trim($this->input->post('posisi'));
			
			$data['tanggal'] = trim($this->input->post('tanggal'));
			$data['catatan'] = trim($this->input->post('catatan'));
			$data['keterangan'] = $keterangan;
			$data['posisi'] = $posisi;
			
			if($posisi=="kredit") {
				// $saldo = $this->db->query("SELECT saldo_100, saldo_50, saldo FROM cpc_record ORDER BY id DESC")->row_array();
				$data['kredit_100'] = trim($this->input->post('kredit_100'));
				$data['kredit_50'] = trim($this->input->post('kredit_50'));
				// $data['saldo_100'] = $saldo['saldo_100'] - $data['kredit_100'];
				// $data['saldo_50'] = $saldo['saldo_50'] - $data['kredit_50'];
				// $data['saldo'] = $saldo['saldo'] - ($data['kredit_100'] + $data['kredit_50']);
				
				$this->db->trans_start();

				$this->db->insert('cpc_record', $data);

				$this->db->trans_complete();
			} else {
				// $saldo = $this->db->query("SELECT saldo_100, saldo_50, saldo FROM cpc_record ORDER BY id DESC")->row_array();
				$data['debit_100'] = trim($this->input->post('debit_100'));
				$data['debit_50'] = trim($this->input->post('debit_50'));
				// $data['saldo_100'] = $saldo['saldo_100'] + $data['debit_100'];
				// $data['saldo_50'] = $saldo['saldo_50'] + $data['debit_50'];
				// $data['saldo'] = $saldo['saldo'] + ($data['debit_100'] + $data['debit_50']);
				
				$this->db->trans_start();

				$this->db->insert('cpc_record', $data);

				$this->db->trans_complete();
			}
			
			$result = $this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM cpc_record")->result();
			$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
			$total_rows = $rows['found_rows'];
		}
		
		// echo "<pre>";
		// print_r($data);
		// print_r($total_rows);
		// print_r($saldo);
		// echo "</pre>";
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('cpc_record');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('cpc_record');	
		}
	}
	
	public function update() {
		$id 				= trim($this->input->post('id'));
		
		$keterangan = trim($this->input->post('keterangan'));
		if($keterangan=="saldo_awal") {
			$data['keterangan'] = $keterangan;
			$data['posisi'] = "debit";
			$data['debit_100'] = trim($this->input->post('debit_100'));
			$data['debit_50'] = trim($this->input->post('debit_50'));
			// $data['saldo_100'] = $data['debit_100'];
			// $data['saldo_50'] = $data['debit_50'];
			// $data['saldo'] = $data['debit_100'] + $data['debit_50'];
		} else {
			$posisi = trim($this->input->post('posisi'));
			
			$data['tanggal'] = trim($this->input->post('tanggal'));
			$data['catatan'] = trim($this->input->post('catatan'));
			$data['keterangan'] = $keterangan;
			$data['posisi'] = $posisi;
			
			if($posisi=="kredit") {
				// $saldo = $this->db->query("SELECT saldo_100, saldo_50, saldo FROM cpc_record ORDER BY id DESC")->row_array();
				$data['kredit_100'] = trim($this->input->post('kredit_100'));
				$data['kredit_50'] = trim($this->input->post('kredit_50'));
				// $data['saldo_100'] = $saldo['saldo_100'] - $data['kredit_100'];
				// $data['saldo_50'] = $saldo['saldo_50'] - $data['kredit_50'];
				// $data['saldo'] = $saldo['saldo'] - ($data['kredit_100'] + $data['kredit_50']);
				
				$this->db->trans_start();

				$this->db->where('id', $id);
				$this->db->update('cpc_record', $data);

				$this->db->trans_complete();
			} else {
				// $saldo = $this->db->query("SELECT saldo_100, saldo_50, saldo FROM cpc_record ORDER BY id DESC")->row_array();
				$data['debit_100'] = trim($this->input->post('debit_100'));
				$data['debit_50'] = trim($this->input->post('debit_50'));
				// $data['saldo_100'] = $saldo['saldo_100'] + $data['debit_100'];
				// $data['saldo_50'] = $saldo['saldo_50'] + $data['debit_50'];
				// $data['saldo'] = $saldo['saldo'] + ($data['debit_100'] + $data['debit_50']);
				
				$this->db->trans_start();

				$this->db->where('id', $id);
				$this->db->update('cpc_record', $data);

				$this->db->trans_complete();
			}
		}
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('cpc_record');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('cpc_record');	
		}
	}
	
	function delete() {
		$id = $_POST['id'];

		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('cpc_record');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
	
	public function get_data() {
		$pagenum = isset($_GET['pagenum']) ? intval($_GET['pagenum']) : 1;
		$pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : 10;
		$start = $pagenum*$pagesize;
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM client LIMIT $start, $pagesize";
		
		$result = $this->db->query($query)->result();
        $sql = "SELECT FOUND_ROWS() AS `found_rows`;";
		$rows = $this->db->query($sql)->row_array();
		$total_rows = $rows['found_rows'];
		
		foreach($result as $row) {
			$client[] = array(
				'id' => $row->id,
				'wsid' => $row->wsid,
				'lokasi' => $row->lokasi,
				'type' => $row->type,
				'lokasi1' => $row->lokasi,
				'lokasi2' => $row->lokasi,
				'lokasi3' => $row->lokasi,
				'lokasi4' => $row->lokasi
			);
		}
		
		$data[] = array(
		   'TotalRows' => $total_rows,	
		   'Rows' => $client
		);
		echo json_encode($data);
		
		// echo "{\"data\":" .json_encode($client). "}";
	}
}