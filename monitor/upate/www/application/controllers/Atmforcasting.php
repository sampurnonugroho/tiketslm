<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Atmforcasting extends CI_Controller {
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
		$this->data['active_menu'] = "atmforcasting";
		
		// $query = $this->db->query("select * FROM atmforcasting ORDER BY id DESC");
		$query = $this->db->query("select *, atmforcasting.id as id_ct FROM atmforcasting LEFT JOIN master_branch ON(atmforcasting.branch=master_branch.id) ORDER BY atmforcasting.id DESC");
        $this->data['data_atmforcasting'] = $query->result();
		
        return view('admin/atmforcasting/index', $this->data);
	}
	
	public function add_master() {
		$id = $this->input->post("id");
		
		$query = $this->db->query('SELECT * FROM atmforcasting WHERE date="'.date("Y-m-d").'" AND branch="'.$id.'"');
		if($query->num_rows()==0) {
			$data['date'] = date("Y-m-d");
			$data['branch'] = $id;
			$this->db->insert('atmforcasting', $data);
			$this->data['id'] = $this->db->insert_id();
		} else {
			$row = $query->row();
			$this->data['id'] = $row->id;
		}
		
		echo $this->data['id'];
	}
	
	public function add() {
        $this->data['active_menu'] = "atmforcasting";
		$this->data['url'] = "atmforcasting/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$this->data['id'] = $id;
		
        return view('admin/atmforcasting/form', $this->data);
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "atmforcasting";
		$this->data['url'] = "atmforcasting/save";
		$this->data['flag'] = "edit";
		
		$id = $this->uri->segment(3);
		
		$this->data['id'] = $id;
		
		// print_r($this->data['id']);

		
        return view('admin/atmforcasting/form', $this->data);
	}
	
	function delete() {
		
	}
	
	public function get_data() {
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$query = $this->db->query("select count(*) as cnt FROM atmforcasting_detail WHERE id_atmforcasting='".$id."'");
        $row = $query->row_array();
		$result["total"] = $row['cnt'];
		$query = $this->db->query("select *, atmforcasting_detail.id as id_ct, atmforcasting_detail.status as sts from atmforcasting_detail left join client on(atmforcasting_detail.id_bank=client.id) WHERE id_atmforcasting='".$id."' limit $offset,$rows");
		
		$items = array();
		$i = 0;
		foreach($query->result() as $row){
			// array_push($items, $row);
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_atmforcasting'] = $row->id_atmforcasting;
			$items[$i]['id_bank'] = $row->id_bank;
			$items[$i]['bank'] = $row->bank;
			$items[$i]['lokasi'] = $row->lokasi;
			$items[$i]['sektor'] = $row->sektor;
			$items[$i]['jenis'] = $row->jenis;
			$items[$i]['merk'] = $row->merk;
			$items[$i]['denom'] = $row->denom;
			$items[$i]['cartridge'] = $row->cartridge;
			$items[$i]['no_cartridge'] = $row->no_cartridge;
			$items[$i]['status'] = $row->sts;
			$items[$i]['total'] = $row->total;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['id'] = $this->input->get('id');
		
		return view('admin/atmforcasting/show_form', $this->data);
	}
	
	public function suggest() {
		$src = addslashes($_POST['src']);
		$id = addslashes($_POST['id']);
		
		$sql = "select * from atmforcasting WHERE id = '$id'";
		$row = $this->db->query($sql)->row();
		
		$id_branch = $row->branch;
		// $src = 'bc';
		
		$query = $this->db->query('select * from client where cabang = "'.$id_branch.'" AND bank LIKE "%'.$src.'%" OR lokasi LIKE "%'.$src.'%" ');
		if($query->num_rows()==0) {
			echo '<span class="pilihan">Item not found</span>';
		} else {
			foreach($query->result() as $data){
				echo '<span class="pilihan" onclick="pilih_kota(\''.$data->id.'\', \''.$data->bank.'-'.$data->lokasi.'-'.$data->sektor.'-'.$data->denom.'-'.$data->ctr.'\');hideStuff(\'suggest\');">'.$data->bank.'-'.$data->lokasi.'</span>';
			}
		}
	}
	
	function save_data() {
		// print_r($this->input->post());
		
		$id_atmforcasting	= strtoupper(trim($this->input->post('id_atmforcasting')));
		$id_bank			= strtoupper(trim($this->input->post('id_bank')));
		$jenis				= strtoupper(trim($this->input->post('jenis')));
		$merk				= strtoupper(trim($this->input->post('merk')));
		$denom				= strtoupper(trim($this->input->post('denom')));
		$cartridge			= strtoupper(trim($this->input->post('cartridge')));
		$no_cartridge		= strtoupper(trim($this->input->post('no_cartridge')));
		$status				= strtoupper(trim($this->input->post('status')));
		$total				= ($denom*$cartridge);
		
		$data['id_atmforcasting'] = $id_atmforcasting;
		$data['id_bank'] = $id_bank;
		$data['jenis'] = $jenis;
		$data['merk'] = $merk;
		$data['denom'] = $denom;
		$data['cartridge'] = $cartridge;
		$data['no_cartridge'] = $no_cartridge;
		$data['status'] = $status;
		$data['total'] = $total;
		
		$this->db->trans_start();

		$this->db->insert('atmforcasting_detail', $data);

		$this->db->trans_complete();
		
		$sql = "select * from atmforcasting_detail left join client on(atmforcasting_detail.id_bank=client.id) WHERE atmforcasting_detail.id_bank = '$id_bank'";
		$row = $this->db->query($sql)->row();

		echo json_encode(array(
			'id_bank' => $id_bank,
			'bank' => $row->bank,
			'lokasi' => $row->lokasi,
			'sektor' => $row->sektor,
			'jenis' => $jenis,
			'merk' => $merk,
			'denom' => $denom,
			'cartridge' => $cartridge,
			'no_cartridge' => $no_cartridge,
			'status' => $status,
			'total' => $total
		));
	}
	
	function update_data() {
		$id = $this->input->get("id");
		
		$id_atmforcasting	= strtoupper(trim($this->input->post('id_atmforcasting')));
		$id_bank			= strtoupper(trim($this->input->post('id_bank')));
		$jenis				= strtoupper(trim($this->input->post('jenis')));
		$merk				= strtoupper(trim($this->input->post('merk')));
		$denom				= strtoupper(trim($this->input->post('denom')));
		$cartridge			= strtoupper(trim($this->input->post('cartridge')));
		$no_cartridge		= strtoupper(trim($this->input->post('no_cartridge')));
		$status				= strtoupper(trim($this->input->post('status')));
		$total				= ($denom*$cartridge);
		
		$data['id_atmforcasting'] = $id_atmforcasting;
		$data['id_bank'] = $id_bank;
		$data['jenis'] = $jenis;
		$data['merk'] = $merk;
		$data['denom'] = $denom;
		$data['cartridge'] = $cartridge;
		$data['no_cartridge'] = $no_cartridge;
		$data['status'] = $status;
		$data['total'] = $total;
		
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->update('atmforcasting_detail', $data);

		$this->db->trans_complete();
		
		$sql = "select * from atmforcasting_detail left join client on(atmforcasting_detail.id_bank=client.id) WHERE atmforcasting_detail.id_bank = '$id_bank'";
		$row = $this->db->query($sql)->row();

		echo json_encode(array(
			'id_bank' => $id_bank,
			'bank' => $row->bank,
			'lokasi' => $row->lokasi,
			'sektor' => $row->sektor,
			'jenis' => $jenis,
			'merk' => $merk,
			'denom' => $denom,
			'cartridge' => $cartridge,
			'no_cartridge' => $no_cartridge,
			'status' => $status,
			'total' => $total
		));
	}
	
	function delete_data() {
		$id = $_POST['id'];

		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('atmforcasting_detail');

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