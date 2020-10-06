<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Handover extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
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
	
	public function index() {
        $this->data['active_menu'] = "handover";

		// $data_client = json_decode($this->curl->simple_get(rest_api().'/client_bank'));
	
		// $this->data['data_client'] = $data_client;
		
        // return view('admin/handover/index', $this->data);
		
		$sql = "UPDATE `client_ho` SET `status`='onprogress' WHERE wsid NOT IN (SELECT wsid FROM client)";
		$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10));
		
		// $sql = "SELECT *, client_ho.alamat as lokasi FROM client_ho LEFT JOIN karyawan ON(client_ho.custodian=karyawan.nik) ORDER BY client_ho.id DESC";
		$sql = "SELECT * FROM detail_ho ORDER BY date ASC";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		// print_r($result);
		
		$sql_run = "SELECT MAX(run) as run_number FROM detail_ho WHERE date='".date('Y-m-d')."'";
		$run = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_run), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['run_number'] = (int) $run->run_number + 1;
		
		$this->data['handover'] = $result;
		
		return view('admin/handover/index', $this->data);
    }
	
	public function save_data() {
		$run_number = trim($this->input->post('run_number'));
		$jumlah_lokasi = trim($this->input->post('jumlah_lokasi'));
		$bank = trim($this->input->post('bank'));
		$vehicle = trim($this->input->post('vehicle'));
		$guard = trim($this->input->post('guard'));
		$custodian = trim($this->input->post('custodian'));
		
		
		$cnt_detail = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>
			"SELECT count(*) as cnt FROM detail_ho WHERE run='$run_number' AND date='".date('Y-m-d')."'"
		), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		if($cnt_detail==0) {
			$id = $this->curl->simple_get(rest_api().'/select/query2', array('query'=>
				"
				INSERT INTO `detail_ho`
					(`run`, `date`, `bank`, `custodian`, `police_number`, `guard`) VALUES 
					('$run_number','".date('Y-m-d')."','$bank','$custodian','$vehicle','$guard')
				"
			), array(CURLOPT_BUFFERSIZE => 10));
		} else {
			$id = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>
				"SELECT MAX(id) as id FROM detail_ho"
			), array(CURLOPT_BUFFERSIZE => 10)))->id;
		}
		
		if($id!=="") {
			$cnt_client_ho = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>
				"SELECT count(*) as cnt FROM client_ho WHERE id_detail='$id'"
			), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
			
			if($cnt_client_ho<$jumlah_lokasi) {
				$cnt = $jumlah_lokasi - $cnt_client_ho;
				
				for($i=0; $i<$cnt; $i++) {
					$this->curl->simple_get(rest_api().'/select/query2', array('query'=>
						"INSERT INTO `client_ho` (`id`, `id_detail`, `bank`, `wsid`, `alamat`, `detail`, `custodian`, `ctr`, `reject`, `status`, `data_handover`, `updates`, `updated`) VALUES (NULL, '$id', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'onprogress', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);"
					), array(CURLOPT_BUFFERSIZE => 10));
				}
			}
		}
		
		echo $id;
	}
	
	public function add_ho() {
		$this->data['active_menu'] = "handover";
		$this->data['url'] = "handover/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$this->data['id'] = $id;
		
        return view('admin/handover/form', $this->data);
	}
	
	public function get_data() {
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$query = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>
			"
				SELECT * FROM client_ho WHERE id_detail='$id' LIMIT $offset, $rows
			"
		), array(CURLOPT_BUFFERSIZE => 10)));
		$result["total"] = count($query);
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			$detail = json_decode($row->detail);
		
			$items[$i]['id'] = $row->id;
			$items[$i]['id_detail'] = $row->id_detail;
			$items[$i]['wsida'] = $row->wsid;
			$items[$i]['wsid'] = $row->wsid;
			$items[$i]['bank'] = $row->bank;
			$items[$i]['lokasi'] = $row->alamat;
			$items[$i]['type'] = $detail->type;
			$items[$i]['paket'] = $detail->paket;
			$items[$i]['tgl_min_dari'] = $detail->tgl_min_dari;
			$items[$i]['tgl_min_hingga'] = $detail->tgl_min_hingga;
			$items[$i]['tgl_max_dari'] = $detail->tgl_max_dari;
			$items[$i]['tgl_max_hingga'] = $detail->tgl_max_hingga;
			$items[$i]['ctr'] = $row->ctr;
			$items[$i]['reject'] = $row->reject;
			$items[$i]['status'] = $row->status;
			$items[$i]['limit_min'] = $detail->limit_min;
			$items[$i]['limit_max'] = $detail->limit_max;
			$items[$i]['denom'] = $detail->denom;
			$items[$i]['interval_isi'] = $detail->interval_isi;
			$items[$i]['sifat'] = $detail->sifat;
			$items[$i]['tgl_efektif'] = $detail->tgl_efektif;
			
			$i++;
		}
		
		$result["rows"] = $items;
		
		// echo "<pre>";
		// print_r($result);
		echo json_encode($result);
	}
	
	public function show_form() {
		$id = $this->input->get('id');
		$index = $this->input->get('index');
		$this->data['flag'] = "show_form";
		$this->data['id'] = $id;
		$this->data['index'] = $index;
		
		return view('admin/handover/show_form', $this->data);
	}
	
	public function update_data() {
		// print_r($_REQUEST);
		$id = $this->input->post('id');
		$id_detail = $this->input->post('id_detail');
		
		$bank = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>
			"SELECT bank FROM detail_ho WHERE id='$id_detail'"
		), array(CURLOPT_BUFFERSIZE => 10)))->bank;
		
		$detail['type'] = trim($this->input->post('type'));
		$detail['paket'] = trim($this->input->post('paket'));
		$detail['tgl_min_dari'] = trim($this->input->post('tgl_min_dari'));
		$detail['tgl_min_hingga'] = trim($this->input->post('tgl_min_hingga'));
		$detail['tgl_max_dari'] = trim($this->input->post('tgl_max_dari'));
		$detail['tgl_max_hingga'] = trim($this->input->post('tgl_max_hingga'));
		$detail['ctr'] = trim($this->input->post('ctr'));
		$detail['reject'] = trim($this->input->post('reject'));
		$detail['limit_min'] = trim($this->input->post('limit_min'));
		$detail['limit_max'] = trim($this->input->post('limit_max'));
		$detail['denom'] = trim($this->input->post('denom'));
		$detail['interval_isi'] = trim($this->input->post('interval_isi'));
		$detail['sifat'] = trim($this->input->post('sifat'));
		$detail['tgl_efektif'] = trim($this->input->post('tgl_efektif'));
		
		$data['id'] = trim($this->input->post('id'));
		$data['wsid'] = trim($this->input->post('wsid'));
		$data['bank'] = $bank;
		$data['alamat'] = trim($this->input->post('lokasi'));
		$data['detail'] = json_encode($detail);
		$data['custodian'] = trim($this->input->post('custodian'));
		$data['ctr'] = trim($this->input->post('ctr'));
		$data['reject'] = trim($this->input->post('reject'));
		$data['status'] = "onprogress";
		
		// $value = $data['wsid']."-".$data['ctr']."-".$data['reject'];
		// $data_cassette = array('value' => $value);
		// $this->curl->simple_post(rest_api().'/master_cassette', $data_cassette, array(CURLOPT_BUFFERSIZE => 10)); 
		
		$table = "client_ho";
		$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		
		$cnt_detail = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>
			"SELECT count(*) as cnt FROM client_ho WHERE id_detail='$id_detail' AND wsid IS NULL"
		), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		if($cnt_detail==0) {
			// NOTIFICATION
			$query = "SELECT * FROM detail_ho LEFT JOIN user ON(user.username=detail_ho.custodian) WHERE detail_ho.id='$id_detail'";
			$token = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->token;
			
			$this->notification($token);
		}
		
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>
			"SELECT * FROM client_ho WHERE id='$id'"
		), array(CURLOPT_BUFFERSIZE => 10)));
		
		$detail = json_decode($row->detail);
		
		echo json_encode(array(
			'id' => $row->id,
			'id_detail' => $row->id_detail,
			'wsid' => $row->wsid,
			'bank' => $row->bank,
			'lokasi' => $row->alamat,
			'type' => $detail->type,
			'paket' => $detail->paket,
			'tgl_min_dari' => $detail->tgl_min_dari,
			'tgl_min_hingga' => $detail->tgl_min_hingga,
			'tgl_max_dari' => $detail->tgl_max_dari,
			'tgl_max_hingga' => $detail->tgl_max_hingga,
			'ctr' => $row->ctr,
			'reject' => $row->reject,
			'status' => $row->status,
			'limit_min' => $detail->limit_min,
			'limit_max' => $detail->limit_max,
			'denom' => $detail->denom,
			'interval_isi' => $detail->interval_isi,
			'sifat' => $detail->sifat,
			'tgl_efektif' => $detail->tgl_efektif
		));
	}
	
	public function add() {
		$this->data['active_menu'] = "handover";
		$this->data['url'] = "handover/save";
		$this->data['flag'] = "add";
		
		$this->data['id'] = '';
		$this->data['wsid'] = '';
		$this->data['bank'] = '';
		$this->data['lokasi'] = '';
		$this->data['type'] = '';
		$this->data['paket'] = '';
		$this->data['tgl_min_dari'] = '';
		$this->data['tgl_min_hingga'] = '';
		$this->data['tgl_max_dari'] = '';
		$this->data['tgl_max_hingga'] = '';
		$this->data['ctr'] = '';
		$this->data['reject'] = '';
		$this->data['limit_min'] = '';
		$this->data['limit_max'] = '';
		$this->data['denom'] = '';
		$this->data['interval_isi'] = '';
		$this->data['sifat'] = '';
		$this->data['tgl_efektif'] = '';
		
		return view('admin/handover/form2', $this->data);
	}
	
	function save() {
		$detail['type'] = trim($this->input->post('type'));
		$detail['paket'] = trim($this->input->post('paket'));
		$detail['tgl_min_dari'] = trim($this->input->post('tgl_min_dari'));
		$detail['tgl_min_hingga'] = trim($this->input->post('tgl_min_hingga'));
		$detail['tgl_max_dari'] = trim($this->input->post('tgl_max_dari'));
		$detail['tgl_max_hingga'] = trim($this->input->post('tgl_max_hingga'));
		$detail['ctr'] = trim($this->input->post('ctr'));
		$detail['reject'] = trim($this->input->post('reject'));
		$detail['limit_min'] = trim($this->input->post('limit_min'));
		$detail['limit_max'] = trim($this->input->post('limit_max'));
		$detail['denom'] = trim($this->input->post('denom'));
		$detail['interval_isi'] = trim($this->input->post('interval_isi'));
		$detail['sifat'] = trim($this->input->post('sifat'));
		$detail['tgl_efektif'] = trim($this->input->post('tgl_efektif'));
		
		$data['wsid'] = trim($this->input->post('wsid'));
		$data['bank'] = trim($this->input->post('bank'));
		$data['alamat'] = trim($this->input->post('lokasi'));
		$data['detail'] = json_encode($detail);
		$data['custodian'] = trim($this->input->post('custodian'));
		$data['ctr'] = trim($this->input->post('ctr'));
		$data['reject'] = trim($this->input->post('reject'));
		$data['status'] = "onprogress";
		
		$value = $data['wsid']."-".$data['ctr']."-".$data['reject'];
		$data_cassette = array('value' => $value);
		$this->curl->simple_post(rest_api().'/master_cassette', $data_cassette, array(CURLOPT_BUFFERSIZE => 10)); 

		$table = "client_ho";
		$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		$query = "SELECT * FROM user WHERE username='".$data['custodian']."'";
		$token = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->token;
		
		$this->notification($token);

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('handover');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('handover');	
		}
	}
	
	public function edit($id) {
		$this->data['active_menu'] = "handover";
		$this->data['url'] = "handover/update";
		$this->data['flag'] = "edit";
		
		$sql = "SELECT * FROM client_ho WHERE id = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$detail = json_decode($row->detail);
		
		$this->data['id'] = $row->id;
		$this->data['wsid'] = $row->wsid;
		$this->data['bank'] = $row->bank;
		$this->data['lokasi'] = $row->alamat;
		$this->data['type'] = $detail->type;
		$this->data['paket'] = $detail->paket;
		$this->data['tgl_min_dari'] = $detail->tgl_min_dari;
		$this->data['tgl_min_hingga'] = $detail->tgl_min_hingga;
		$this->data['tgl_max_dari'] = $detail->tgl_max_dari;
		$this->data['tgl_max_hingga'] = $detail->tgl_max_hingga;
		$this->data['ctr'] = $row->ctr;
		$this->data['reject'] = $row->reject;
		$this->data['limit_min'] = $detail->limit_min;
		$this->data['limit_max'] = $detail->limit_max;
		$this->data['denom'] = $detail->denom;
		$this->data['interval_isi'] = $detail->interval_isi;
		$this->data['sifat'] = $detail->sifat;
		$this->data['tgl_efektif'] = $detail->tgl_efektif;
		$this->data['custodian'] = $row->custodian;
		$this->data['nama_custodian'] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan WHERE nik='".$row->custodian."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama;
		
		
		
		return view('admin/handover/form', $this->data);
	}
	
	public function update() {
		$detail['type'] = trim($this->input->post('type'));
		$detail['paket'] = trim($this->input->post('paket'));
		$detail['tgl_min_dari'] = trim($this->input->post('tgl_min_dari'));
		$detail['tgl_min_hingga'] = trim($this->input->post('tgl_min_hingga'));
		$detail['tgl_max_dari'] = trim($this->input->post('tgl_max_dari'));
		$detail['tgl_max_hingga'] = trim($this->input->post('tgl_max_hingga'));
		$detail['ctr'] = trim($this->input->post('ctr'));
		$detail['reject'] = trim($this->input->post('reject'));
		$detail['limit_min'] = trim($this->input->post('limit_min'));
		$detail['limit_max'] = trim($this->input->post('limit_max'));
		$detail['denom'] = trim($this->input->post('denom'));
		$detail['interval_isi'] = trim($this->input->post('interval_isi'));
		$detail['sifat'] = trim($this->input->post('sifat'));
		$detail['tgl_efektif'] = trim($this->input->post('tgl_efektif'));
		
		$data['id'] = trim($this->input->post('id'));
		$data['wsid'] = trim($this->input->post('wsid'));
		$data['bank'] = trim($this->input->post('bank'));
		$data['alamat'] = trim($this->input->post('lokasi'));
		$data['detail'] = json_encode($detail);
		$data['custodian'] = trim($this->input->post('custodian'));
		$data['ctr'] = trim($this->input->post('ctr'));
		$data['reject'] = trim($this->input->post('reject'));
		$data['status'] = "onprogress";
		
		$value = $data['wsid']."-".$data['ctr']."-".$data['reject'];
		$data_cassette = array('value' => $value);
		$this->curl->simple_post(rest_api().'/master_cassette', $data_cassette, array(CURLOPT_BUFFERSIZE => 10)); 
		
		$table = "client_ho";
		$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		$query = "SELECT * FROM user WHERE username='".$data['custodian']."'";
		$token = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->token;
		
		$this->notification($token);

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('handover');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('handover');	
		}
	}
	
	public function delete() {
		$data['id'] = $_POST['id'];
		
		// $query = "SELECT wsid FROM client_ho WHERE id='".$data['id']."'";
		// $wsid = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->wsid;

		// $query = "DELETE FROM `master_cassette` WHERE kode LIKE '%$wsid%'";
		// echo $this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10));
		
		$table = "detail_ho";
		$delete = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
	
	public function testing_aja() {
		$query = "SELECT * FROM user WHERE username='K0030'";
		$token = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->token;
		
		echo $token;
		
		$this->notification($token);
	}
	
	public function suggest_custodi() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		// $sql = "SELECT * FROM user LEFT JOIN karyawan ON(user.username=karyawan.nik) WHERE user.level='CUSTODI' AND user.username NOT IN (SELECT custodian FROM client_ho WHERE status='onprogress')";
		$sql = "SELECT * FROM user LEFT JOIN karyawan ON(user.username=karyawan.nik) WHERE user.level='CUSTODI'";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// print_r($result->result());
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->username;
				$list[$key]['text'] = $row->nama; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function notification($token) {
		define('AUTHORIZATION_KEY', 'AAAAnLcRlWk:APA91bF0J5GW72L1GeWozjX8LNSSq5usc8twk0mUL53izRdPK9HiVz_yZziH7XUq_rXizKFk_bEv26FeQaRFe3YY5sPIpeTaV23CA1-s2kfPFYhdIp4ZzyaviG3Iv59uJW-pHb6-pZSI');

		$_REQUEST['to'] = $token;

		$title = "Informasi";
		$body = "Pekerjaan handover telah didapatkan, silahkan cek kembali aplikasi anda";

		$fields = array(
			'to' => $_REQUEST['to'],
			'data' => array(
				"notification_body" => $body,
				"notification_title"=> $title,
				"notification_foreground"=> "true",
				"notification_android_channel_id"=> "my_channel_id2",
				"notification_android_priority"=> "2",
				"notification_android_visibility"=> "1",
				"notification_android_color"=> "#ff0000",
				"notification_android_icon"=> "thumbs_up",
				"notification_android_sound"=> "alarm2",
				"notification_android_vibrate"=> "500, 200, 500",
				"notification_android_lights"=> "#ffff0000, 250, 250",
				"command" => "open:lib:refresh"
			)
		);

		$headers = array(
			'Authorization:key='.AUTHORIZATION_KEY,
			'Content-Type:application/json'
		);

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result, true);
	}
}