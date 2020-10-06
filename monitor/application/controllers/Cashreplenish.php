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

    public function index_1() {
        $this->data['active_menu'] = "cashreplenish_1";
        $this->data['h_min'] = "1";
		
		$run = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT MAX(run_number) as run_number FROM cashtransit WHERE date='".date("Y-m-d")."' AND h_min='".$this->data['h_min']."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		$run_number = (int) $run->run_number+1;
        $this->data['run_number'] = $run_number;
		
		$date = date('Y-m-d');
		$query = "
			SELECT 
				*, 
				cashtransit.id as id_ct 
			FROM cashtransit 
			LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) 
			WHERE cashtransit.date='".$date."' AND cashtransit.h_min='".$this->data['h_min']."'
			ORDER BY cashtransit.id DESC
		";
        $this->data['data_cashreplenish'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		return view('admin/cashreplenish/index', $this->data);
    }

    public function index_0() {
        $this->data['active_menu'] = "cashreplenish_0";
        $this->data['h_min'] = "0";
		
		$run = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT MAX(run_number) as run_number FROM cashtransit WHERE date='".date("Y-m-d")."' AND h_min='".$this->data['h_min']."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		$run_number = (int) $run->run_number+1;
        $this->data['run_number'] = $run_number;
		
		$date = date('Y-m-d');
		$query = "
			SELECT 
				*, 
				cashtransit.id as id_ct 
			FROM cashtransit 
			LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) 
			WHERE cashtransit.date='".$date."' AND cashtransit.h_min='".$this->data['h_min']."'
			ORDER BY cashtransit.id DESC
		";
        $this->data['data_cashreplenish'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		return view('admin/cashreplenish/index', $this->data);
    }
	
	public function suggest_pic() {
		$search = $this->input->post('search');
		
		$sql = "SELECT * FROM karyawan WHERE (karyawan.id_bagian_dept='12' OR karyawan.id_bagian_dept='13') AND karyawan.nama LIKE '%$search%' AND karyawan.status='Y'";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// print_r($result->result());
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->nama;
				$list[$key]['text'] = $row->nama; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function get_table() {
		$date = $this->input->post('date');
		$h_min = $this->input->post('h_min');
		$query = "
			SELECT 
				*, 
				cashtransit.id as id_ct 
			FROM cashtransit 
			LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) 
			WHERE cashtransit.date LIKE '%".$date."%' AND cashtransit.h_min='".$h_min."'
			ORDER BY cashtransit.id DESC
		";
        $data_cashtransit = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		echo '<div>';
		echo '	<table class="table " cellspacing="0" width="100%">';
		echo '		<thead>';
		echo '			<tr>';
		echo '				<th class="black-cell"><span class="loading"></span></th>';
		echo '				<th scope="col">';
		echo '					Run Number';
		echo '				</th>';
		echo '				<th scope="col">';
		echo '					Date';
		echo '				</th>';
		echo '				<th scope="col">';
		echo '					Branch';
		echo '				</th>';
		echo '				<th scope="col">';
		echo '					Action';
		echo '				</th>';
		echo '			</tr>';
		echo '		</thead>';
		echo '		<tbody>';
		$no = 0;
		if(count($data_cashtransit)==0) {
			echo "<tr><td colspan='5' style='text-align: center'>NO DATA</td></tr>";
		}
		foreach($data_cashtransit as $row) { 
			$no++;
			
			echo '		<tr>';
			echo '			<td class="th table-check-cell">'.$no.'</td>';
			echo '			<td>'.$row->run_number.'</td>';
			echo '			<td>'.$row->date.'</td>';
			echo '			<td>'.$row->name.'</td>';
			echo '			<td style="text-align: center">';
			echo '				<button type="button" class="button green" onClick="window.location.href=\''.base_url().'cashreplenish/edit_'.$row->h_min.'/'.$row->id_ct.'\'" title="Edit">';
			echo '					<span class="smaller">Detail</span>';
			echo '				</button>';
			echo '				<button type="button" class="button red" onClick="openDelete(\''.$row->id_ct.'\', \''.base_url().'cashreplenish/delete\')" title="Delete">';
			echo '					<span class="smaller">Delete</span>';
			echo '				</button>';
			echo '			</td>';
			echo '		</tr>';
		}
		echo '		</tbody>';
		echo '	</table>';
		echo '</div>';
	}
	
	public function add_master() {
		
		$data['id'] = $this->input->post("id");
		$data['h_min'] = $this->input->post("h_min");
		$data['action_date'] = date("Y-m-d", strtotime($this->input->post("action_date")));
		
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
	
	public function edit_1($id) {
		$this->data['active_menu'] = "cashreplenish_1";
		$this->data['url'] = "cashreplenish/save";
		$this->data['flag'] = "edit";
		
		$id = $this->uri->segment(3);
		
		$this->data['id'] = $id;
		
        return view('admin/cashreplenish/form', $this->data);
	}
	
	public function edit_0($id) {
		$this->data['active_menu'] = "cashreplenish_0";
		$this->data['url'] = "cashreplenish/save";
		$this->data['flag'] = "edit";
		
		$id = $this->uri->segment(3);
		
		$this->data['id'] = $id;
		
        return view('admin/cashreplenish/form', $this->data);
	}
	
	public function get_data() {
		$id = $this->uri->segment(3);
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;
		$offset = ($page-1)*$rows;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		
		$query = "
				SELECT
					SQL_CALC_FOUND_ROWS *, 
					cashtransit_detail.id as id_ct, 
					cashtransit_detail.ctr as ttl_ctr 
				FROM cashtransit_detail 
				LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
				LEFT JOIN master_zone ON(master_zone.id=client.sektor) LEFt JOIN master_branch ON(master_branch.id=master_zone.id_branch)
				LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
				WHERE cashtransit_detail.state='ro_atm' AND id_cashtransit='".$id."' limit $offset,$rows";
		
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$query = "
				SELECT
					SQL_CALC_FOUND_ROWS *, 
					cashtransit_detail.id as id_ct, 
					cashtransit_detail.ctr as ttl_ctr 
				FROM cashtransit_detail 
				LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
				LEFT JOIN master_zone ON(master_zone.id=client.sektor) LEFt JOIN master_branch ON(master_branch.id=master_zone.id_branch)
				LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
				WHERE cashtransit_detail.state='ro_atm' AND id_cashtransit='".$id."'";
		
		$total = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$result["total"] = count($total);

		$items = array();
		$i = 0;
		foreach($res as $row){
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['id_bank'] = $row->id_bank;
			$items[$i]['wsid'] = $row->wsid;
			$items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
			$items[$i]['bank'] = $row->bank;
			$items[$i]['lokasi'] = $row->lokasi;
			$items[$i]['sektor'] = $row->kode_zone;
			$items[$i]['jenis'] = $row->type;
			$items[$i]['denom'] = $row->denom;
			$items[$i]['brand'] = $row->vendor;
			$items[$i]['model'] = $row->type_mesin;
			$items[$i]['pcs_100000'] = $row->pcs_100000;
			$items[$i]['pcs_50000'] = $row->pcs_50000;
			$items[$i]['pcs_20000'] = $row->pcs_20000;
			$items[$i]['pcs_10000'] = $row->pcs_10000;
			$items[$i]['pcs_5000'] = $row->pcs_5000;
			$items[$i]['pcs_2000'] = $row->pcs_2000;
			$items[$i]['pcs_1000'] = $row->pcs_1000;
			$items[$i]['pcs_coin'] = $row->pcs_coin;
			$items[$i]['ctr'] = $row->ttl_ctr;
			$items[$i]['total'] = $row->total;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
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
		$act = strtoupper(trim($this->input->post('act')));
		
		$data['id_cashtransit']		= strtoupper(trim($this->input->post('id_cashtransit')));
		$data['id_bank']			= strtoupper(trim($this->input->post('id_bank')));
		$data['state'] 				= "ro_atm";
		$data['jenis']				= strtoupper(trim($this->input->post('act')));
		$data['ctr']				= strtoupper(trim($this->input->post('ctr')));
		$data['pcs_100000']			= intval(strtoupper(trim($this->input->post('pcs_100000'))));
		$data['pcs_50000']			= intval(strtoupper(trim($this->input->post('pcs_50000'))));
		// $data['total'] 				= ($data['pcs_100000']!="")?(100000*$data['pcs_100000'])*$data['ctr'] : (50000*$data['pcs_50000'])*$data['ctr'];
		
		if($act=="CRM") {
			$data['total'] 				= (100000*$data['pcs_100000']) + (50000*$data['pcs_50000']);
		} else {
			$data['total'] 				= ($data['pcs_100000']!="")?(100000*$data['pcs_100000']) : (50000*$data['pcs_50000']);
		}
		
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
		$data['total'] 				= ($data['pcs_100000']!="") ? (100000*$data['pcs_100000']) : (50000*$data['pcs_50000']);
		
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