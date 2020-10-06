<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cashreplenish extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("cashtransit_model");
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

    public function index() {
        $this->data['active_menu'] = "cashreplenish";
		
		$this->data['data_cashreplenish'] = json_decode($this->curl->simple_get(rest_api().'/plan_cashreplenish'));
		
		return view('admin/cashreplenish/index', $this->data);
    }
	
	public function add_master() {
		$data['id'] = $this->input->post("id");
		
		$id = $this->curl->simple_post(rest_api().'/plan_cashreplenish/add_master',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $id;
	}
	
	public function add() {
        $this->data['active_menu'] = "cashreplenish";
		$this->data['url'] = "cashtransit/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$this->data['id'] = $id;
		
        return view('admin/cashreplenish/form', $this->data);
    }
	
	public function edit($id) {
		$this->data['active_menu'] = "cashreplenish";
		$this->data['url'] = "cashtransit/save";
		$this->data['flag'] = "edit";
		
		$id = $this->uri->segment(3);
		
		$this->data['id'] = $id;
		
        return view('admin/cashreplenish/form', $this->data);
	}
	
	public function get_data() {
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		
		$result = $this->curl->simple_post(rest_api().'/plan_cashreplenish/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $result;
	}
	
	public function show_form() {
		$id = $this->input->get('id');
		$this->data['id'] = $id;
		$this->data['index'] = $this->input->get('index');
		$this->data['id_bank'] = $this->input->get('id_bank');
		$this->data['row'] = json_decode($this->input->get('row'));
		
		if($_REQUEST['id_bank']=="undefined") {
			$this->data['flag'] = "ADD";
		} else {
			$this->data['flag'] = "EDIT";
		}
		
		
		$row = json_decode($this->curl->simple_get(rest_api().'/plan_cashreplenish?id='.$id))[0];
		$id_branch = $row->branch;
		
		$this->data['client'] = json_decode($this->curl->simple_get(rest_api().'/Client_bank?branch='.$id_branch));
		
		return view('admin/cashreplenish/show_form', $this->data);
	}
	
	function suggest_data_client() {
		$data['search'] = $this->input->post('search');
		$data['id_cashtransit'] = $this->input->post('id_cashtransit');
		
		$result = $this->curl->simple_post(rest_api().'/plan_cashreplenish/suggest_data_client',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $result;
	}
	
	function suggest_data_client2() {
		$data['search'] = $this->input->post('search');
		$data['id_client'] = $this->input->post('id_client');
		$data['id_cashtransit'] = $this->input->post('id_cashtransit');
		
		$result = $this->curl->simple_post(rest_api().'/plan_cashreplenish/suggest_data_client2',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $result;
	}
	
	function get_data_client() {
		$id = $this->input->get('id');
		
		// echo $id;
		
		$sql_prev = "SELECT 
						*
							FROM
								(SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail
									LEFT JOIN
										runsheet_cashprocessing
											ON(runsheet_cashprocessing.id=cashtransit_detail.id)
												WHERE 
													cashtransit_detail.id_bank='$id' AND
													cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id_bank='$id')";
													
		$prev = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_prev), array(CURLOPT_BUFFERSIZE => 10)));
		
		// print_r($prev->ctr);
		
		$client = json_decode($this->curl->simple_get(rest_api().'/Client_bank?id='.$id))[0];
		
		$data['id'] = $id;
		$data['branch'] = $client->cabang;
		$data['ga'] = $client->sektor;
		$data['bank'] = $client->bank;
		$data['act'] = $client->type;
		$data['brand'] = $client->vendor;
		$data['model'] = $client->type_mesin;
		$data['location'] = $client->lokasi;
		$data['denom'] = $client->denom;
		// $data['ctr'] = $client->ctr;
		$data['tgl_min'] = $client->tgl_min;
		$data['tgl_max'] = $client->tgl_max;
		
		if($client->type=="ATM") {
			$skrng = date("d");
			$tglmin_dari	= explode("-", $client->tgl_min)[0];
			$tglmin_hingga 	= explode("-", $client->tgl_min)[1];
			// echo "tgl sekarang ".date("d");
			// echo "<br>";
			// echo "tglmin_dari ".$tglmin_dari;
			// echo "<br>";
			// echo "tglmin_hingga ".$tglmin_hingga;
			// echo "<br>";
			
			$tglmax_dari	= explode("-", $client->tgl_max)[0];
			$tglmax_hingga 	= explode("-", $client->tgl_max)[1];
			// echo "tgl sekarang ".date("d");
			// echo "<br>";
			// echo "tglmax_dari ".$tglmax_dari;
			// echo "<br>";
			// echo "tglmax_hingga ".$tglmax_hingga;
			// echo "<br>";
			// echo "<br>";
			// echo "<br>";
			
			
			// $date = date("Y-m")."-".sprintf("%02d", $tglmax_dari);
			// $time = strtotime($date);
			// $final = date("Y-m-d", strtotime("+1 month", $time));
			// echo date("Y-m-d", $_SERVER['REQUEST_TIME']);
			
			// $tglmin_dari = date("Y-m-d", strtotime(date("Y-m")."-".sprintf("%02d", $tglmin_dari)));
			// $tglmin_hingga = date("Y-m-d", strtotime(date("Y-m")."-".sprintf("%02d", $tglmin_hingga)));
			// $tglmax_dari = date("Y-m-d", strtotime(date("Y-m")."-".sprintf("%02d", $tglmax_dari)));
			// $tglmax_hingga = date("Y-m-d", strtotime("+1 month", strtotime(date("Y-m")."-".sprintf("%02d", $tglmax_hingga))));
			// echo "<br>";
			// echo '$tglmin_dari '.$tglmin_dari;
			// echo "<br>";
			// echo '$tglmin_hingga '.$tglmin_hingga;
			// echo "<br>";
			// echo '$tglmax_dari '.$tglmax_dari;
			// echo "<br>";
			// echo '$tglmax_hingga '.$tglmax_hingga;
			
			// echo "<br>";
			// echo "<br>";
			
			$dateToTest = date("Y-m-d", strtotime(date("Y-m")."-".sprintf("%02d", $tglmax_dari)));
			// echo "<br>";
			$lastday = date('t',strtotime($dateToTest));
			
			// echo "<br>";
			// echo "<br>";
			
			// // $skrng = "1";
			// echo $skrng." ".$tglmin_dari." ".$tglmin_hingga;
			// echo "<br>";
			// echo "<br>";
			
			// echo $skrng." ".$tglmax_dari." ".$lastday;
			// echo "<br>";
			// echo "<br>";
			
			// echo $skrng." "."1"." ".$tglmax_hingga;
			// echo "<br>";
			// echo "<br>";
			
			if(intval($skrng)>=intval($tglmin_dari) && intval($skrng)<=intval($tglmin_hingga)) {
				// echo "AAAAA";
				$data['ctr'] = (($client->limit_min/$client->denom)>2000 ? ($client->limit_min/$client->denom)/2000 : 1);
				$data['val_denom'] = ($client->limit_min/$client->denom);
			} else if(intval($skrng)>=intval($tglmax_dari) && intval($skrng)<=intval($lastday) || intval($skrng)<=intval($tglmax_hingga)) {
				// echo "BBBBB";
				$data['ctr'] =(($client->limit_max/$client->denom)>2000 ? ($client->limit_max/$client->denom)/2000 : 1);
				$data['val_denom'] = ($client->limit_max/$client->denom);
			} 
			
			// $prev->ctr = 2;
			if($data['ctr']>$prev->ctr) {
				$ket = "NAIK LIMIT";
			} else if($data['ctr']<$prev->ctr) {
				$ket = "TURUN LIMIT";
				$info = "PENGISIAN SEBELUMNYA ".$prev->ctr." CARTRIDGE";
				$saran = "DISARANKAN MEMBAWA BAG ISI ".$prev->ctr." CARTRIDGE";
			} else {
				$ket = "LIMIT NORMAL";
			}
			
			$data['keterangan'] = $ket;
			$data['info'] = $info;
			$data['saran'] = $saran;
		}
		
		echo json_encode($data);
	}
	
	function tess() {
		$id = $this->input->get('id');
		
		$client = json_decode($this->curl->simple_get(rest_api().'/Client_bank?id='.$id))[0];
		
		
		// echo "<br>";
		// echo "<br>";
		// echo $client->tgl_min;
		// echo "<br>";
		// echo $client->tgl_max;
		// echo "<br>";
		// echo date("d");
		
		$data['id'] = $client->id;
		$data['branch'] = $client->cabang;
		$data['ga'] = $client->sektor;
		$data['bank'] = $client->bank;
		$data['act'] = $client->type;
		$data['brand'] = $client->vendor;
		$data['model'] = $client->type_mesin;
		$data['location'] = $client->lokasi;
		$data['denom'] = $client->denom;
		
		
		
		if(date("d")>=explode("-", $client->tgl_min)[0] && date("d")<=explode("-", $client->tgl_min)[1]) {
			$data['ctr'] = ($client->limit_min/$client->denom)/2000;
			$data['val_denom'] = ($client->limit_min/$client->denom)/(($client->limit_min/$client->denom)/2000);
			// echo "<br>";
			// echo "<br>";
			// echo "MIN DATE";
			// echo $client->tgl_min;
			// echo "<br>";
			// echo $client->tgl_max;
			// echo "<br>";
			// echo date("d");
		} else if(date("d")>=explode("-", $client->tgl_max)[1] && date("d")<=explode("-", $client->tgl_max)[0]) {
			$data['ctr'] = ($client->limit_max/$client->denom)/2000;
			$data['val_denom'] = ($client->limit_max/$client->denom)/(($client->limit_max/$client->denom)/2000);
			// echo "<br>";
			// echo "<br>";
			// echo "MAX DATE";
			// echo $client->tgl_min;
			// echo "<br>";
			// echo $client->tgl_max;
			// echo "<br>";
			// echo date("d");
		}
		
		echo json_encode($data);
	}
	
	function get_data_client2() {
		$data['id_ct'] = $this->input->get('id_ct');
		$data['id_bank'] = $this->input->get('id_bank');
		
		
		$client = json_decode($this->curl->simple_get(rest_api().'/Client_bank/index2',$data,array(CURLOPT_BUFFERSIZE => 10)));
		
		// print_r(rest_api().'/Client_bank/index2');
		
		$data['branch'] = $client->cabang;
		$data['ga'] = $client->sektor;
		$data['bank'] = $client->bank;
		$data['act'] = $client->type;
		$data['brand'] = $client->vendor;
		$data['model'] = $client->type_mesin;
		$data['location'] = $client->lokasi;
		$data['denom'] = $client->denom;
		$data['ctr'] = $client->ttl_ctr;
		$data['pcs_100000'] = $client->pcs_100000;
		$data['pcs_50000'] = $client->pcs_50000;
		
		echo json_encode($data);
	}
	
	function save_data() {
		$data['id_cashtransit']		= strtoupper(trim($this->input->post('id_cashtransit')));
		$data['id_bank']			= strtoupper(trim($this->input->post('id_bank')));
		$data['state'] 				= "ro_atm";
		$data['jenis']				= strtoupper(trim($this->input->post('act')));
		$data['ctr']				= strtoupper(trim($this->input->post('ctr')));
		$data['pcs_100000']			= intval(strtoupper(trim($this->input->post('pcs_100000'))));
		$data['pcs_50000']			= intval(strtoupper(trim($this->input->post('pcs_50000'))));
		// $data['total'] 				= ($data['pcs_100000']!="")?(100000*$data['pcs_100000'])*$data['ctr'] : (50000*$data['pcs_50000'])*$data['ctr'];
		$data['total'] 				= ($data['pcs_100000']!="")?(100000*$data['pcs_100000']) : (50000*$data['pcs_50000']);
		
		$table = "cashtransit_detail";
		$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		$query = "SELECT *, cashtransit_detail.id as id_detail FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN master_branch ON(master_branch.id=client.cabang) WHERE cashtransit_detail.id_bank = '".$data['id_bank']."' AND cashtransit_detail.id_cashtransit='".$data['id_cashtransit']."'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
	
		$data['id'] = $row->id_detail;
		$data['wsid'] = $row->wsid;
		$data['branch'] = $row->name;
		$data['sektor'] = $row->sektor;
		$data['bank'] = $row->bank;
		$data['jenis'] = $row->type;
		$data['brand'] = $row->vendor;
		$data['model'] = $row->type_mesin;
		$data['lokasi'] = $row->lokasi;
	
		echo json_encode($data);
	}
	
	function update_data() {
		$data['id']					= strtoupper(trim($this->input->post('id_detail')));
		$data['id_cashtransit']		= strtoupper(trim($this->input->post('id_cashtransit')));
		$data['id_bank']			= strtoupper(trim($this->input->post('id_bank2')));
		$data['state'] 				= "ro_atm";
		$data['jenis']				= strtoupper(trim($this->input->post('act')));
		$data['ctr']				= strtoupper(trim($this->input->post('ctr')));
		$data['pcs_100000']			= intval(strtoupper(trim($this->input->post('pcs_100000'))));
		$data['pcs_50000']			= intval(strtoupper(trim($this->input->post('pcs_50000'))));
		// $data['total'] 				= ($data['pcs_100000']!="")?(100000*$data['pcs_100000'])*$data['ctr'] : (50000*$data['pcs_50000'])*$data['ctr'];
		$data['total'] 				= ($data['pcs_100000']!="")?(100000*$data['pcs_100000']) : (50000*$data['pcs_50000']);
		
		$table = "cashtransit_detail";
		$res = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		$query = "SELECT *, cashtransit_detail.id as id_detail FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN master_branch ON(master_branch.id=client.cabang) WHERE cashtransit_detail.id_bank = '".$data['id_bank']."' AND cashtransit_detail.id_cashtransit='".$data['id_cashtransit']."'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
	
		$data['id'] = $row->id_detail;
		$data['wsid'] = $row->wsid;
		$data['branch'] = $row->name;
		$data['sektor'] = $row->sektor;
		$data['bank'] = $row->bank;
		$data['jenis'] = $row->type;
		$data['brand'] = $row->vendor;
		$data['model'] = $row->type_mesin;
		$data['lokasi'] = $row->lokasi;
	
		echo json_encode($data);
	}
	
	function delete() {
		$data['id'] = $_POST['id'];

		$table = "cashtransit";
		$delete = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
	
	function delete_data() {
		$data['id'] = $_POST['id'];

		$table = "cashtransit_detail";
		$res = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
}