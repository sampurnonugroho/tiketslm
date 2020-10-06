<?php
error_reporting(E_ALL);
defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class Client extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("ticket_model");
        $this->load->library('form_validation');
		$this->load->library('curl');
		// $this->load->library('encrypt');

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
        $this->data['active_menu'] = "client";

        // $this->data['data_client'] = json_decode($this->curl->simple_get(rest_api().'/client_bank'));
		
		$query = "SELECT *, client.id as id, master_branch.name AS name_branch FROM client LEFT JOIN master_zone ON(master_zone.id=client.sektor) LEFt JOIN master_branch ON(master_branch.id=master_zone.id_branch)";
		$data_client = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		$key=0;
		foreach($data_client as $row) {
			// $qrCode = new QrCode($this->encrypt->encode($row->wsid));
			$qrCode = new QrCode($row->wsid);
			$qrCode->setSize(300);
			
			$qrCode->writeFile(realpath(__DIR__ . '/../../upload/qrcode').'/'.$row->wsid.'.png');
		}
		
		// print_r($list);
		$this->data['data_client'] = $data_client;
		
        return view('admin/client/index', $this->data);
    }
	
	public function server_processing() {
		$param['table'] = 'client'; //nama tabel dari database
		$param['column_order'] = array('id', 'wsid', 'lokasi'); //field yang ada di table user
		$param['column_search'] = array('id','wsid','lokasi'); //field yang diizin untuk pencarian 
		$param['order'] = array('id' => 'asc');
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		echo $this->curl->simple_get(rest_api().'/select/datatables3', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}

    public function add() {
        $this->data['active_menu'] = "client";
		$this->data['url'] = "client/save";
		$this->data['flag'] = "add";
		
		$this->data['id'] = '';
		$this->data['wsid'] = '';
		$this->data['bank'] = '';
		$this->data['lokasi'] = '';
		$this->data['sektor'] = '';
		$this->data['cabang'] = '';
		$this->data['type'] = '';
		$this->data['type_mesin'] = '';
		$this->data['jam_operasional'] = '';
		// $this->data['durasi'] = '';
		$this->data['vendor'] = '';
		$this->data['status'] = '';
		$this->data['tgl_ho'] = '';
		// $this->data['tgl_isi'] = '';
		$this->data['denom'] = '';
		$this->data['ctr'] = '';
		$this->data['reject'] = '';
		$this->data['ctr2'] = '';
		$this->data['reject2'] = '';
		$this->data['tgl_min'] = '';
		$this->data['limit_min'] = '';
		$this->data['tgl_max'] = '';
		$this->data['limit_max'] = '';
		$this->data['serial_number'] = '';
		$this->data['picture'] = '';
		$this->data['keterangan'] = '';
		$this->data['keterangan2'] = '';
		$this->data['latlng'] = '';

		
        return view('admin/client/form', $this->data);
    }
	
	function save() {
		$wsid				= strtoupper(trim($this->input->post('wsid')));
		$bank				= strtoupper(trim($this->input->post('bank')));
		$lokasi				= strtoupper(trim($this->input->post('lokasi')));
		$sektor				= strtoupper(trim($this->input->post('sektor')));
		$cabang				= strtoupper(trim($this->input->post('cabang')));
		$type				= strtoupper(trim($this->input->post('type')));
		$type_mesin			= strtoupper(trim($this->input->post('type_mesin')));
		$jam_operasional	= strtoupper(trim($this->input->post('jam_operasional')));
		// $durasi				= strtoupper(trim($this->input->post('durasi')));
		$vendor				= strtoupper(trim($this->input->post('vendor')));
		$status				= strtoupper(trim($this->input->post('status')));
		$tgl_ho				= strtoupper(trim($this->input->post('tgl_ho')));
		// $tgl_isi			= strtoupper(trim($this->input->post('tgl_isi')));
		$denom				= strtoupper(trim($this->input->post('denom')));
		$ctr				= strtoupper(trim($this->input->post('ctr')));
		$ctr2				= strtoupper(trim($this->input->post('ctr2')));
		$reject				= strtoupper(trim($this->input->post('reject')));
		$reject2			= strtoupper(trim($this->input->post('reject2')));
		$tgl_min_dari		= strtoupper(trim($this->input->post('tgl_min_dari')));
		$tgl_min_hingga		= strtoupper(trim($this->input->post('tgl_min_hingga')));
		$limit_min			= strtoupper(trim($this->input->post('limit_min')));
		$tgl_max_dari		= strtoupper(trim($this->input->post('tgl_max_dari')));
		$tgl_max_hingga		= strtoupper(trim($this->input->post('tgl_max_hingga')));
		$limit_max			= strtoupper(trim($this->input->post('limit_max')));
		$interval_isi		= strtoupper(trim($this->input->post('interval_isi')));
		$serial_number		= strtoupper(trim($this->input->post('serial_number')));
		$keterangan			= strtoupper(trim($this->input->post('keterangan')));
		$keterangan2		= strtoupper(trim($this->input->post('keterangan2')));
		$latlng				= strtoupper(trim($this->input->post('latlng')));

		$data['id'] = $id;
		$data['wsid'] = $wsid;
		$data['bank'] = $bank;
		$data['lokasi'] = $lokasi;
		$data['sektor'] = $sektor;
		$data['cabang'] = $cabang;
		$data['type'] = $type;
		$data['type_mesin'] = $type_mesin;
		$data['jam_operasional'] = $jam_operasional;
		// $data['durasi'] = $durasi;
		$data['vendor'] = $vendor;
		$data['status'] = $status;
		$data['tgl_ho'] = $tgl_ho;
		// $data['tgl_isi'] = $tgl_isi;
		$data['denom'] = $denom;
		$data['ctr'] = $ctr;
		$data['ctr2'] = $ctr2;
		$data['reject'] = $reject;
		$data['reject2'] = $reject2;
		$data['tgl_min'] = $tgl_min_dari."-".$tgl_min_hingga;
		$data['limit_min'] = $limit_min;
		$data['tgl_max'] = $tgl_max_dari."-".$tgl_max_hingga;
		$data['limit_max'] = $limit_max;
		$data['interval_isi'] = $interval_isi;
		$data['serial_number'] = $serial_number;
		$data['picture'] = $this->_uploadImage();
		$data['keterangan'] = $keterangan;
		$data['keterangan2'] = $keterangan2;
		if($latlng!=="") {
			$data['data_location'] = $latlng;
		}
		
		$value = $data['wsid']."-".$data['ctr']."-".$data['reject'];
		$data_cassette = array('value' => $value);
		$this->curl->simple_post(rest_api().'/master_cassette', $data_cassette, array(CURLOPT_BUFFERSIZE => 10)); 
		
		$table = "client";
		$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('client');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('client');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "client";
		$this->data['url'] = "client/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM client WHERE id = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$query = "SELECT * FROM client_ho WHERE wsid='$row->wsid'";
		$ho = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$det_ho = json_decode($ho->detail);
		$dat_ho = json_decode($ho->data_handover);
			
		$this->data['id'] 				= $row->id;
		$this->data['wsid'] 			= $row->wsid;
		$this->data['bank'] 			= $row->bank;
		$this->data['lokasi'] 			= $row->lokasi;
		$this->data['id_sektor'] 		= $row->sektor;
		$this->data['sektor'] 			= json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM master_zone WHERE id='".$row->sektor."'"), array(CURLOPT_BUFFERSIZE => 10)))->name;;
		$this->data['id_cabang'] 		= $row->cabang;
		$this->data['cabang'] 			= json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM master_branch WHERE id='".$row->cabang."'"), array(CURLOPT_BUFFERSIZE => 10)))->name;
		$this->data['type'] 			= strtoupper($det_ho->type);
		$this->data['type_mesin'] 		= ($row->type_mesin=="" ? $dat_ho[0]->jumlah : $row->type_mesin);
		$this->data['jam_operasional'] 	= $row->jam_operasional;
		// $this->data['durasi'] 			= $row->durasi;
		$this->data['vendor'] 			= $row->vendor;
		$this->data['status'] 			= $row->status;
		$this->data['tgl_ho'] 			= $row->tgl_ho;
		// $this->data['tgl_isi'] 			= $row->tgl_isi;
		$this->data['denom'] 			= $row->denom;
		$this->data['ctr'] 				= $row->ctr;
		$this->data['ctr2'] 			= $row->ctr2;
		$this->data['reject'] 			= $row->reject;
		$this->data['reject2'] 			= $row->reject2;
		$this->data['tgl_min_dari'] 	= (!empty($row->tgl_min) ? explode("-", $row->tgl_min)[0] : $det_ho->tgl_min_dari);
		$this->data['tgl_min_hingga'] 	= (!empty($row->tgl_min) ? explode("-", $row->tgl_min)[1] : $det_ho->tgl_min_hingga);
		$this->data['limit_min'] 		= (!empty($row->limit_min) ? $row->limit_min : $det_ho->limit_min);
		$this->data['tgl_max_dari'] 	= (!empty($row->tgl_max) ? explode("-", $row->tgl_max)[0] : $det_ho->tgl_max_dari);
		$this->data['tgl_max_hingga'] 	= (!empty($row->tgl_max) ? explode("-", $row->tgl_max)[1] : $det_ho->tgl_max_hingga);
		$this->data['limit_max'] 		= (!empty($row->limit_max) ? $row->limit_max : $det_ho->limit_max);
		$this->data['limit_max'] 		= $det_ho->limit_max;
		$this->data['interval_isi'] 	= $row->interval_isi;
		$this->data['serial_number'] 	= $dat_ho[1]->jumlah;
		$this->data['picture'] 			= $row->picture;
		$this->data['keterangan'] 		= $row->keterangan;
		$this->data['keterangan2'] 		= $row->keterangan2;
		
		$latlng = array(
			"lat"=>json_decode($dat_ho[25]->jumlah)->lat,
			"lng"=>json_decode($dat_ho[25]->jumlah)->lng,
		);
		// print_r($latlng);
		$this->data['latlng'] 			= (empty($row->data_location) ? "(".implode($latlng, ", ").")" : "(".implode($latlng, ", ").")");
		
		// echo "<pre>";
		// print_r($dat_ho);
		// print_r($this->data);
		return view('admin/client/form', $this->data);
	}
	
	function summary($wsid) {
		$this->data['active_menu'] = "client";
		
		$query = "SELECT * FROM client_ho WHERE wsid='$wsid'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// if(count($result)==0) {
			
		// }
		$this->data['wsid'] = $wsid;
		$this->data['data_summary'] = $result;
		
		return view('admin/client/summary', $this->data);
	}
	
	function update() {
// 		echo "<pre>";
// 		print_r($_REQUEST);
		
		$id 				= strtoupper(trim($this->input->post('id')));
		
		$sql = "SELECT * FROM client WHERE id = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$query = "SELECT * FROM client_ho WHERE wsid='$row->wsid'";
		$ho = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$det_ho = json_decode($ho->detail);
		$dat_ho = json_decode($ho->data_handover);
		
		$latlng = array(
			"lat"=>json_decode($dat_ho[25]->jumlah)->lat,
			"lng"=>json_decode($dat_ho[25]->jumlah)->lng,
		);
		
		$latlatlng = json_decode(trim($this->input->post('latlng')))[0]->latlng;
		
		if($latlatlng=="") {
			$latlng = array(
				"lat"=>json_decode($dat_ho[25]->jumlah)->lat,
				"lng"=>json_decode($dat_ho[25]->jumlah)->lng,
			);
		} else {
			$latlng = array(
				"lat"=>$latlatlng->lat,
				"lng"=>$latlatlng->lng,
			);
			
		}
		
		$wsid				= strtoupper(trim($this->input->post('wsid')));
		$bank				= strtoupper(trim($this->input->post('bank')));
		$lokasi				= strtoupper(trim($this->input->post('lokasi')));
		$sektor				= strtoupper(trim($this->input->post('sektor')));
		$cabang				= strtoupper(trim($this->input->post('cabang')));
		$type				= strtoupper(trim($this->input->post('type')));
		$type_mesin			= strtoupper(trim($this->input->post('type_mesin')));
		$jam_operasional	= strtoupper(trim($this->input->post('jam_operasional')));
		// $durasi				= strtoupper(trim($this->input->post('durasi')));
		$vendor				= strtoupper(trim($this->input->post('vendor')));
		$status				= strtoupper(trim($this->input->post('status')));
		$tgl_ho				= strtoupper(trim($this->input->post('tgl_ho')));
		// $tgl_isi			= strtoupper(trim($this->input->post('tgl_isi')));
		$denom				= strtoupper(trim($this->input->post('denom')));
		$ctr				= strtoupper(trim($this->input->post('ctr')));
		$ctr2				= strtoupper(trim($this->input->post('ctr2')));
		$reject				= strtoupper(trim($this->input->post('reject')));
		$reject2			= strtoupper(trim($this->input->post('reject2')));
		$tgl_min_dari		= strtoupper(trim($this->input->post('tgl_min_dari')));
		$tgl_min_hingga		= strtoupper(trim($this->input->post('tgl_min_hingga')));
		$limit_min			= strtoupper(trim($this->input->post('limit_min')));
		$tgl_max_dari		= strtoupper(trim($this->input->post('tgl_max_dari')));
		$tgl_max_hingga		= strtoupper(trim($this->input->post('tgl_max_hingga')));
		$limit_max			= strtoupper(trim($this->input->post('limit_max')));
		$interval_isi		= strtoupper(trim($this->input->post('interval_isi')));
		$serial_number		= strtoupper(trim($this->input->post('serial_number')));
		$keterangan			= strtoupper(trim($this->input->post('keterangan')));
		$keterangan2		= strtoupper(trim($this->input->post('keterangan2')));
		// $latlng				= strtoupper(trim($this->input->post('latlng')));
		$latlng				= json_encode($latlng);

		$data['id'] = $id;
		$data['wsid'] = $wsid;
		$data['bank'] = $bank;
		$data['lokasi'] = $lokasi;
		$data['sektor'] = $sektor;
		$data['cabang'] = $cabang;
		$data['type'] = $type;
		$data['type_mesin'] = $type_mesin;
		$data['jam_operasional'] = $jam_operasional;
		// $data['durasi'] = $durasi;
		$data['vendor'] = $vendor;
		$data['status'] = $status;
		$data['tgl_ho'] = $tgl_ho;
		// $data['tgl_isi'] = $tgl_isi;
		$data['denom'] = $denom;
		$data['ctr'] = $ctr;
		$data['ctr2'] = $ctr2;
		$data['reject'] = $reject;
		$data['reject2'] = $reject2;
		$data['tgl_min'] = $tgl_min_dari."-".$tgl_min_hingga;
		$data['limit_min'] = $limit_min;
		$data['tgl_max'] = $tgl_max_dari."-".$tgl_max_hingga;
		$data['limit_max'] = $limit_max;
		$data['interval_isi'] = $interval_isi;
		$data['serial_number'] = $serial_number;
		if (!empty($_FILES["image"]["name"])) {
			$data['picture'] = $this->_uploadImage();
		} else {
			$data['picture'] = $this->input->post("old_image");
		}
		$data['keterangan'] = $keterangan;
		$data['keterangan2'] = $keterangan2;
		if($latlng!=="") {
			$data['data_location'] = $latlng;
		}

        // $value = $data['wsid']."-".$data['ctr']."-".$data['reject'];
		// $data_cassette = array('value' => $value);
		// $this->curl->simple_post(rest_api().'/master_cassette', $data_cassette, array(CURLOPT_BUFFERSIZE => 10)); 

		// print_r($data);
		// print_r($_FILES);
		$table = "client";
		$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('client');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('client');	
		}
	}
	
	function delete() {
// 		$id = $_POST['id'];

// 		$this->db->trans_start();

// 		$this->db->where('id', $id);
// 		$this->db->delete('client');

// 		$this->db->trans_complete();
		
// 		if ($this->db->trans_status() === FALSE) {
// 			$this->session->set_flashdata('error', 'Data gagal dihapus.');
// 			echo "failed";
// 		} else  {
// 			$this->session->set_flashdata('success', 'Data dihapus.');
// 			echo "success";
// 		}

        $data['id'] = $_POST['id'];
		
		$query = "SELECT wsid FROM client WHERE id='".$data['id']."'";
		$wsid = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->wsid;

		$query = "DELETE FROM `master_cassette` WHERE kode LIKE '$wsid%'";
		$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10));
		
		$query = "DELETE FROM `client_ho` WHERE kode='$wsid'";
		$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10));
		
		$table = "client";
		$delete = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
	
	private function _uploadImage() {
		$config['upload_path']          = './upload/client/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['file_name']            = $this->input->post('wsid');
		$config['overwrite']			= true;
		$config['max_size']             = 1024; // 1MB
		// $config['max_width']            = 1024;
		// $config['max_height']           = 768;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('image')) {
			return $this->upload->data("file_name");
		}
		
		return "default.jpg";
	}
}