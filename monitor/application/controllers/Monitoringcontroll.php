<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoringcontroll extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("cashtransit_model");
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
        $this->data['active_menu'] = "cashtransit";
		
		$query = $this->db->query("select * FROM cashtransit ORDER BY id DESC");
        $this->data['data_cashtransit'] = $query->result();
		
		// print_r($this->data['data_cashtransit']);

        // $this->data['data_cashtransit'] = $this->cashtransit_model->datacashtransit();
        return view('admin/cashtransit/index', $this->data);
    }

    public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['id'] = $this->input->get('id');
		
		return view('admin/cashtransit/show_form', $this->data);
	}

    public function suggest() {
		$src = addslashes($_POST['src']);
		// $src = 'bc';
		
		$query = $this->db->query('select * from client where bank LIKE "%'.$src.'%" OR lokasi LIKE "%'.$src.'%" ');
		foreach($query->result() as $data){
			echo '<span class="pilihan" onclick="pilih_kota(\''.$data->id.'\', \''.$data->bank.'-'.$data->lokasi.'-'.$data->sektor.'\');hideStuff(\'suggest\');">'.$data->bank.'-'.$data->lokasi.'-'.$data->sektor.'</span>';
		}
	}
    public function get_data() {
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		// $bank = isset($_POST['bank']) ? mysql_real_escape_string($_POST['bank']) : '';
		$sektor = isset($_POST['sektor']) ? mysql_real_escape_string($_POST['sektor']) : '';
		$offset = ($page-1)*$rows;
		$result = array();
		
		$where = "and client.sektor like '$sektor%'";
		
		$query = $this->db->query("select count(*) as cnt FROM cashtransit_detail WHERE id_cashtransit='".$id."'");
        $row = $query->row_array();
		$result["total"] = $row['cnt'];
		$query = $this->db->query("select *, cashtransit_detail.id as id_ct from cashtransit_detail left join client on(cashtransit_detail.id_bank=client.id) WHERE id_cashtransit='".$id."' ".$where." limit $offset,$rows");
		
		$items = array();
		$i = 0;
		foreach($query->result() as $row){
			// array_push($items, $row);
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['id_bank'] = $row->id_bank;
			$items[$i]['bank'] = $row->bank;
			$items[$i]['lokasi'] = $row->lokasi;
			$items[$i]['sektor'] = $row->sektor;
			$items[$i]['jenis'] = $row->jenis;
			$items[$i]['denom'] = $row->denom;
			$items[$i]['pcs_100000'] = $row->pcs_100000;
			$items[$i]['pcs_50000'] = $row->pcs_50000;
			$items[$i]['pcs_20000'] = $row->pcs_20000;
			$items[$i]['pcs_10000'] = $row->pcs_10000;
			$items[$i]['pcs_5000'] = $row->pcs_5000;
			$items[$i]['pcs_2000'] = $row->pcs_2000;
			$items[$i]['pcs_1000'] = $row->pcs_1000;
			$items[$i]['pcs_coin'] = $row->pcs_coin;
			$items[$i]['total'] = $row->total;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
    public function add() {
		
        $this->data['active_menu'] = "cashtransit";
		$this->data['url'] = "cashtransit/save";
		$this->data['flag'] = "add";
		
		$this->data['id'] = '';
		$this->data['bank'] = '';
		$this->data['lokasi'] = '';
		$this->data['sektor'] = '';
		$this->data['cabang'] = '';
		$this->data['type'] = '';
		$this->data['type_mesin'] = '';
		$this->data['jam_operasional'] = '';
		$this->data['durasi'] = '';
		$this->data['vendor'] = '';
		$this->data['status'] = '';
		$this->data['tgl_ho'] = '';
		$this->data['tgl_isi'] = '';
		$this->data['denom'] = '';
		$this->data['ctr'] = '';
		$this->data['limit'] = '';
		$this->data['serial_number'] = '';
		$this->data['keterangan'] = '';
		$this->data['keterangan2'] = '';
		$this->data['latlng'] = '';
		
		$id = $this->uri->segment(3);
		
		if($id=="") {		
			$query = $this->db->query('SELECT * FROM cashtransit WHERE date="'.date("Y-m-d").'"');
			// echo $query->num_rows();
			if($query->num_rows()==0) {
				$data['date'] = date("Y-m-d");
				$this->db->insert('cashtransit', $data);
				$this->data['id'] = $this->db->insert_id();
			} else {
				$row = $query->row();
				$this->data['id'] = $row->id;
			}
		} else {
			$this->data['id'] = $id;
		}
		
		// print_r($this->data['id']);

		
        return view('admin/cashtransit/form', $this->data);
    }
	
	function update_data() {
		// print_r($this->input->get());
		// print_r($this->input->post());
		$id = $this->input->get("id");
		
		$bank				= strtoupper(trim($this->input->post('id_bank')));
		$jenis				= strtoupper(trim($this->input->post('jenis')));
		$denom				= strtoupper(trim($this->input->post('denom')));
		$pcs_100000			= strtoupper(trim($this->input->post('pcs_100000')));
		$pcs_50000			= strtoupper(trim($this->input->post('pcs_50000')));
		$pcs_20000			= strtoupper(trim($this->input->post('pcs_20000')));
		$pcs_10000			= strtoupper(trim($this->input->post('pcs_10000')));
		$pcs_5000			= strtoupper(trim($this->input->post('pcs_5000')));
		$pcs_2000			= strtoupper(trim($this->input->post('pcs_2000')));
		$pcs_1000			= strtoupper(trim($this->input->post('pcs_1000')));
		$pcs_coin			= strtoupper(trim($this->input->post('pcs_coin')));
		$total				= (100000*$pcs_100000)+(50000*$pcs_50000)+(20000*$pcs_20000)+(10000*$pcs_10000)+(5000*$pcs_5000)+(2000*$pcs_2000)+(1000*$pcs_1000)+(1*$pcs_coin);
	
		$data['id_bank'] = $bank;
		$data['jenis'] = $jenis;
		$data['denom'] = $denom;
		$data['pcs_100000'] = $pcs_100000;
		$data['pcs_50000'] = $pcs_50000;
		$data['pcs_20000'] = $pcs_20000;
		$data['pcs_10000'] = $pcs_10000;
		$data['pcs_5000'] = $pcs_5000;
		$data['pcs_2000'] = $pcs_2000;
		$data['pcs_1000'] = $pcs_1000;
		$data['pcs_coin'] = $pcs_coin;
		$data['total'] = $total;
		
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->update('cashtransit_detail', $data);

		$this->db->trans_complete();
		
		$sql = "select * from cashtransit_detail left join client on(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.id_bank = '$bank'";
		$row = $this->db->query($sql)->row();

		echo json_encode(array(
			'id_bank' => $bank,
			'bank' => $row->bank,
			'lokasi' => $row->lokasi,
			'sektor' => $row->sektor,
			'jenis' => $jenis,
			'denom' => $denom,
			'pcs_100000' => $pcs_100000,
			'pcs_50000' => $pcs_50000,
			'pcs_20000' => $pcs_20000,
			'pcs_10000' => $pcs_10000,
			'pcs_5000' => $pcs_5000,
			'pcs_2000' => $pcs_2000,
			'pcs_1000' => $pcs_1000,
			'pcs_coin' => $pcs_coin,
			'total' => $total
		));
	}
	function save_data() {
		$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
		$bank				= strtoupper(trim($this->input->post('id_bank')));
		$jenis				= strtoupper(trim($this->input->post('jenis')));
		$denom				= strtoupper(trim($this->input->post('denom')));
		$pcs_100000			= strtoupper(trim($this->input->post('pcs_100000')));
		$pcs_50000			= strtoupper(trim($this->input->post('pcs_50000')));
		$pcs_20000			= strtoupper(trim($this->input->post('pcs_20000')));
		$pcs_10000			= strtoupper(trim($this->input->post('pcs_10000')));
		$pcs_5000			= strtoupper(trim($this->input->post('pcs_5000')));
		$pcs_2000			= strtoupper(trim($this->input->post('pcs_2000')));
		$pcs_1000			= strtoupper(trim($this->input->post('pcs_1000')));
		$pcs_coin			= strtoupper(trim($this->input->post('pcs_coin')));
		$total				= (100000*$pcs_100000)+(50000*$pcs_50000)+(20000*$pcs_20000)+(10000*$pcs_10000)+(5000*$pcs_5000)+(2000*$pcs_2000)+(1000*$pcs_1000)+(1*$pcs_coin);
	
		$data['id_cashtransit'] = $id_cashtransit;
		$data['id_bank'] = $bank;
		$data['jenis'] = $jenis;
		$data['denom'] = $denom;
		$data['pcs_100000'] = $pcs_100000;
		$data['pcs_50000'] = $pcs_50000;
		$data['pcs_20000'] = $pcs_20000;
		$data['pcs_10000'] = $pcs_10000;
		$data['pcs_5000'] = $pcs_5000;
		$data['pcs_2000'] = $pcs_2000;
		$data['pcs_1000'] = $pcs_1000;
		$data['pcs_coin'] = $pcs_coin;
		$data['total'] = $total;
		
		$this->db->trans_start();

		$this->db->insert('cashtransit_detail', $data);

		$this->db->trans_complete();
		
		$sql = "select * from cashtransit_detail left join client on(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.id_bank = '$bank'";
		$row = $this->db->query($sql)->row();

		echo json_encode(array(
			'id_bank' => $bank,
			'bank' => $row->bank,
			'lokasi' => $row->lokasi,
			'sektor' => $row->sektor,
			'jenis' => $jenis,
			'denom' => $denom,
			'pcs_100000' => $pcs_100000,
			'pcs_50000' => $pcs_50000,
			'pcs_20000' => $pcs_20000,
			'pcs_10000' => $pcs_10000,
			'pcs_5000' => $pcs_5000,
			'pcs_2000' => $pcs_2000,
			'pcs_1000' => $pcs_1000,
			'pcs_coin' => $pcs_coin,
			'total' => $total
		));
	}
	
	function save() {
		$bank				= strtoupper(trim($this->input->post('bank')));
		$lokasi				= strtoupper(trim($this->input->post('lokasi')));
		$sektor				= strtoupper(trim($this->input->post('sektor')));
		$cabang				= strtoupper(trim($this->input->post('cabang')));
		$type				= strtoupper(trim($this->input->post('type')));
		$type_mesin			= strtoupper(trim($this->input->post('type_mesin')));
		$jam_operasional	= strtoupper(trim($this->input->post('jam_operasional')));
		$durasi				= strtoupper(trim($this->input->post('durasi')));
		$vendor				= strtoupper(trim($this->input->post('vendor')));
		$status				= strtoupper(trim($this->input->post('status')));
		$tgl_ho				= strtoupper(trim($this->input->post('tgl_ho')));
		$tgl_isi			= strtoupper(trim($this->input->post('tgl_isi')));
		$denom				= strtoupper(trim($this->input->post('denom')));
		$ctr				= strtoupper(trim($this->input->post('ctr')));
		$limit				= strtoupper(trim($this->input->post('limit')));
		$serial_number		= strtoupper(trim($this->input->post('serial_number')));
		$keterangan			= strtoupper(trim($this->input->post('keterangan')));
		$keterangan2		= strtoupper(trim($this->input->post('keterangan2')));
		$latlng				= strtoupper(trim($this->input->post('latlng')));

		$data['bank'] = $bank;
		$data['lokasi'] = $lokasi;
		$data['sektor'] = $sektor;
		$data['cabang'] = $cabang;
		$data['type'] = $type;
		$data['type_mesin'] = $type_mesin;
		$data['jam_operasional'] = $jam_operasional;
		$data['durasi'] = $durasi;
		$data['vendor'] = $vendor;
		$data['status'] = $status;
		$data['tgl_ho'] = $tgl_ho;
		$data['tgl_isi'] = $tgl_isi;
		$data['denom'] = $denom;
		$data['ctr'] = $ctr;
		$data['limit'] = $limit;
		$data['serial_number'] = $serial_number;
		$data['keterangan'] = $keterangan;
		$data['keterangan2'] = $keterangan2;
		$data['data_location'] = $latlng;

		$this->db->trans_start();

		$this->db->insert('client', $data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('client');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('client');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "cashtransit";
		$this->data['url'] = "cashtransit/save";
		$this->data['flag'] = "edit";
		
		$this->data['id'] = '';
		$this->data['bank'] = '';
		$this->data['lokasi'] = '';
		$this->data['sektor'] = '';
		$this->data['cabang'] = '';
		$this->data['type'] = '';
		$this->data['type_mesin'] = '';
		$this->data['jam_operasional'] = '';
		$this->data['durasi'] = '';
		$this->data['vendor'] = '';
		$this->data['status'] = '';
		$this->data['tgl_ho'] = '';
		$this->data['tgl_isi'] = '';
		$this->data['denom'] = '';
		$this->data['ctr'] = '';
		$this->data['limit'] = '';
		$this->data['serial_number'] = '';
		$this->data['keterangan'] = '';
		$this->data['keterangan2'] = '';
		$this->data['latlng'] = '';
		
		$id = $this->uri->segment(3);
		
		if($id=="") {		
			$query = $this->db->query('SELECT * FROM cashtransit WHERE date="'.date("Y-m-d").'"');
			// echo $query->num_rows();
			if($query->num_rows()==0) {
				$data['date'] = date("Y-m-d");
				$this->db->insert('cashtransit', $data);
				$this->data['id'] = $this->db->insert_id();
			} else {
				$row = $query->row();
				$this->data['id'] = $row->id;
			}
		} else {
			$this->data['id'] = $id;
		}
		
		// print_r($this->data['id']);

		
        return view('admin/cashtransit/form', $this->data);
	}
	
	function update() {
		$id 				= strtoupper(trim($this->input->post('id')));
		
		$bank				= strtoupper(trim($this->input->post('bank')));
		$lokasi				= strtoupper(trim($this->input->post('lokasi')));
		$sektor				= strtoupper(trim($this->input->post('sektor')));
		$cabang				= strtoupper(trim($this->input->post('cabang')));
		$type				= strtoupper(trim($this->input->post('type')));
		$type_mesin			= strtoupper(trim($this->input->post('type_mesin')));
		$jam_operasional	= strtoupper(trim($this->input->post('jam_operasional')));
		$durasi				= strtoupper(trim($this->input->post('durasi')));
		$vendor				= strtoupper(trim($this->input->post('vendor')));
		$status				= strtoupper(trim($this->input->post('status')));
		$tgl_ho				= strtoupper(trim($this->input->post('tgl_ho')));
		$tgl_isi			= strtoupper(trim($this->input->post('tgl_isi')));
		$denom				= strtoupper(trim($this->input->post('denom')));
		$ctr				= strtoupper(trim($this->input->post('ctr')));
		$limit				= strtoupper(trim($this->input->post('limit')));
		$serial_number		= strtoupper(trim($this->input->post('serial_number')));
		$keterangan			= strtoupper(trim($this->input->post('keterangan')));
		$keterangan2		= strtoupper(trim($this->input->post('keterangan2')));
		$latlng				= strtoupper(trim($this->input->post('latlng')));

		$data['bank'] = $bank;
		$data['lokasi'] = $lokasi;
		$data['sektor'] = $sektor;
		$data['cabang'] = $cabang;
		$data['type'] = $type;
		$data['type_mesin'] = $type_mesin;
		$data['jam_operasional'] = $jam_operasional;
		$data['durasi'] = $durasi;
		$data['vendor'] = $vendor;
		$data['status'] = $status;
		$data['tgl_ho'] = $tgl_ho;
		$data['tgl_isi'] = $tgl_isi;
		$data['denom'] = $denom;
		$data['ctr'] = $ctr;
		$data['limit'] = $limit;
		$data['serial_number'] = $serial_number;
		$data['keterangan'] = $keterangan;
		$data['keterangan2'] = $keterangan2;
		$data['data_location'] = $latlng;

		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->update('client', $data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('client');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('client');	
		}
	}
	
	function delete() {
		$id = $_POST['id'];

		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('cashtransit');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
	
	function delete_data() {
		$id = $_POST['id'];

		$this->db->trans_start();

		$this->db->where('id_cashtransit', $id);
		$this->db->delete('cashtransit_detail');

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