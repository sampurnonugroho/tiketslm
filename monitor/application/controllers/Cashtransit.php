<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cashtransit extends CI_Controller {
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

			// $this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			// $this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			// $this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			// $this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index_1() {
        $this->data['active_menu'] = "cashtransit_1";
        $this->data['h_min'] = "1";
		
		$run = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT MAX(run_number) as run_number FROM cashtransit WHERE date='".date("Y-m-d")."' AND h_min='".$this->data['h_min']."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		$run_number = (int) $run->run_number+1;
        $this->data['run_number'] = $run_number;
		
		$date = date('Y-m-d',strtotime("-1 days"));
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
        $this->data['data_cashtransit'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
        return view('admin/cashtransit/index', $this->data);
    }

    public function index_0() {
        $this->data['active_menu'] = "cashtransit_0";
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
        $this->data['data_cashtransit'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// print_r($this->data['data_cashtransit']);

        // $this->data['data_cashtransit'] = $this->cashtransit_model->datacashtransit();
        return view('admin/cashtransit/index', $this->data);
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

    public function show_form() {
		$id = $this->input->get('id');
		$index = $this->input->get('index');
		$this->data['flag'] = "show_form";
		$this->data['id'] = $id;
		$this->data['index'] = $index;
		$rowz = json_decode($this->input->get('row'));
		
		$query = "select * from cashtransit WHERE id = '$id'";
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$id_branch = $row->branch;
		
		$query = "select * from client_cit";
		$client = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['client'] = $client;
		
		
		
		if($rowz->isNewRecord) {
			$query = "select max(no_boc) as boc FROM cashtransit_detail";
			$no_boc = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->boc;
			if($no_boc=="") {
				$this->data['no_boc'] = "BJK000001";
			} else {
				$this->data['no_boc'] = "BJK".str_pad((intval(substr($no_boc, 3)) + 1), 6, '0', STR_PAD_LEFT);
			} 
		} else {
			$query = "select max(no_boc) as boc FROM cashtransit_detail WHERE id='".$rowz->id."'";
			$no_boc = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->boc;
			$this->data['no_boc'] = $no_boc;
		}
		
		return view('admin/cashtransit/show_form', $this->data);
	}

    public function suggest() {
		$src = addslashes($_POST['src']);
		$id = addslashes($_POST['id']);
		
		$sql = "select * from cashtransit WHERE id = '$id'";
		$row = $this->db->query($sql)->row();
		
		$id_branch = $row->branch;
		// $src = 'bc';
		
		$result = $this->db->query('select * from client where cabang = "'.$id_branch.'" AND bank LIKE "%'.$src.'%" OR lokasi LIKE "%'.$src.'%" OR wsid LIKE "%'.$src.'%" ');
		// if($query->num_rows()==0) {
			// echo '<span class="pilihan">Item not found</span>';
		// } else {
			// foreach($query->result() as $data){
				// echo '<span class="pilihan" onclick="pilih_kota(\''.$data->id.'\', \''.$data->bank.'-'.$data->lokasi.'-'.$data->sektor.'-'.$data->denom.'\');hideStuff(\'suggest\');">'.$data->bank.'-'.$data->lokasi.'</span>';
			// }
		// }
		
		$list = array();
		if ($result->num_rows() > 0) {
			$key=0;
			foreach ($result->result() as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = "(".$row->bank.") ".$row->lokasi; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function get_suggest() {
		$id = addslashes($_POST['id']);

		$sql = "SELECT * FROM client WHERE id = '$id'";
		$row = $this->db->query($sql)->row();
		
		// print_r($row);	

		$list = array();
		$list['id'] = $row->id;
		$list['bank'] = $row->bank;
		$list['lokasi'] = $row->lokasi;
		$list['sektor'] = $row->sektor;
		$list['denom'] = $row->denom;

		echo json_encode($list);
	}

	
    public function get_data() {
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		
		$result = $this->curl->simple_post(rest_api().'/plan_cashtransit/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $result;
	}
	
    public function add_master() {
		
		$data['id'] = $this->input->post("id");
		$data['h_min'] = $this->input->post("h_min");
		$data['action_date'] = date("Y-m-d", strtotime($this->input->post("action_date")));
		$data['dibuat_oleh'] = $this->input->post("pic");
		
		$id = $this->curl->simple_post(rest_api().'/plan_cashreplenish/add_master',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $id;
	}
	
    public function add_cit() {
		$this->data['active_menu'] = "cashtransit";
		$this->data['url'] = "cashtransit/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$this->data['id'] = $id;
		
        return view('admin/cashtransit/form', $this->data);
	}
	
    public function add() {
		
        $this->data['active_menu'] = "cashtransit";
		$this->data['url'] = "cashtransit/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$this->data['id'] = $id;
		
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
		$data['state'] = "ro_cit";
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
		$this->db->update('cashtransit_cit_detail', $data);

		$this->db->trans_complete();
		
		$sql = "select * from cashtransit_cit_detail left join client on(cashtransit_cit_detail.id_bank=client.id) WHERE cashtransit_cit_detail.id_bank = '$bank'";
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
		$id_cashtransit		= trim($this->input->post('id_cashtransit'));
		$client_pengirim	= (empty($this->input->post('client_pengirim')) ? 0 : trim($this->input->post('client_pengirim')));
		$client_penerima	= strtoupper(trim($this->input->post('client_penerima')));
		$nomor_boc			= strtoupper(trim($this->input->post('nomor_boc')));
		$metode				= strtoupper(trim($this->input->post('metode')));
		$jenis				= strtoupper(trim($this->input->post('jenis')));
		$pcs_100000			= (empty($this->input->post('pcs_100000')) ? 0 : trim($this->input->post('pcs_100000')));
		$pcs_50000			= (empty($this->input->post('pcs_50000')) ? 0 : trim($this->input->post('pcs_50000')));
		$pcs_20000			= (empty($this->input->post('pcs_20000')) ? 0 : trim($this->input->post('pcs_20000')));
		$pcs_10000			= (empty($this->input->post('pcs_10000')) ? 0 : trim($this->input->post('pcs_10000')));
		$pcs_5000			= (empty($this->input->post('pcs_5000')) ? 0 : trim($this->input->post('pcs_5000')));
		$pcs_2000			= (empty($this->input->post('pcs_2000')) ? 0 : trim($this->input->post('pcs_2000')));
		$pcs_1000			= (empty($this->input->post('pcs_1000')) ? 0 : trim($this->input->post('pcs_1000')));
		$pcs_coin			= (empty($this->input->post('pcs_coin')) ? 0 : trim($this->input->post('pcs_coin')));
		
		$detail_uang = array(
			"kertas_100k" 	=> intval(trim($this->input->post('kertas_100k'))),
			"kertas_50k" 	=> intval(trim($this->input->post('kertas_50k'))),
			"kertas_20k" 	=> intval(trim($this->input->post('kertas_20k'))),
			"kertas_10k" 	=> intval(trim($this->input->post('kertas_10k'))),
			"kertas_5k" 	=> intval(trim($this->input->post('kertas_5k'))),
			"kertas_2k" 	=> intval(trim($this->input->post('kertas_2k'))),
			"kertas_1k" 	=> intval(trim($this->input->post('kertas_1k'))),
			"logam_1k" 		=> intval(trim($this->input->post('logam_1k'))),
			"logam_500" 	=> intval(trim($this->input->post('logam_500'))),
			"logam_200" 	=> intval(trim($this->input->post('logam_200'))),
			"logam_100" 	=> intval(trim($this->input->post('logam_100'))),
			"logam_50" 		=> intval(trim($this->input->post('logam_50')))
		);

		$total = 0;
		foreach($detail_uang as $k => $r) {
			if(!empty($r)) {
				if($k=="kertas_100k") { $pengali = 100000; }
				if($k=="kertas_50k") { $pengali = 50000; }
				if($k=="kertas_20k") { $pengali = 20000; }
				if($k=="kertas_10k") { $pengali = 10000; }
				if($k=="kertas_5k") { $pengali = 5000; }
				if($k=="kertas_2k") { $pengali = 2000; }
				if($k=="kertas_1k") { $pengali = 1000; }
				if($k=="logam_1k") { $pengali = 1000; }
				if($k=="logam_500") { $pengali = 500; }
				if($k=="logam_200") { $pengali = 200; }
				if($k=="logam_100") { $pengali = 100; }
				if($k=="logam_50") { $pengali = 50; }

				$total = $total + ($r * $pengali);
			}
		}

		$data['id_cashtransit'] = $id_cashtransit;
		$data['id_pengirim'] = $client_pengirim;
		$data['id_penerima'] = $client_penerima;
		$data['no_boc'] = $nomor_boc;
		$data['state'] = "ro_cit";
		$data['metode'] = $metode;
		$data['jenis'] = $jenis;
		$data['pcs_100000'] = $pcs_100000;
		$data['pcs_50000'] = $pcs_50000;
		$data['pcs_20000'] = $pcs_20000;
		$data['pcs_10000'] = $pcs_10000;
		$data['pcs_5000'] = $pcs_5000;
		$data['pcs_2000'] = $pcs_2000;
		$data['pcs_1000'] = $pcs_1000;
		$data['pcs_coin'] = $pcs_coin;
		$data['detail_uang'] = json_encode($detail_uang);
		$data['total'] = $total;
		
		// print_r($data);
		
		
		$table = "cashtransit_detail";
		$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		if($data['state']=="ro_cit") {
			error_reporting(0);
		}
		
		$query = "select *, cashtransit_detail.id as id_detail from cashtransit_detail left join client_cit on(cashtransit_detail.id_pengirim=client_cit.id) WHERE cashtransit_detail.id_pengirim = '$client_pengirim' ORDER BY cashtransit_detail.id DESC";
		$query = "
			SELECT 
				*, 
				cashtransit_detail.id as id_ct, 
				cashtransit_detail.ctr as ttl_ctr,
				IFNULL(client.sektor, client_cit.sektor) AS run_number,
				IF(cashtransit_detail.id_pengirim = 0, 'PT. BIJAK', client_cit.nama_client) AS nama_pengirim,
				(SELECT nama_client FROM client_cit WHERE client_cit.id=cashtransit_detail.id_penerima) AS nama_penerima,
				master_branch.name as nama_branch
			FROM 
				(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, jam_cash_in, cpc_process, updated_date, loading, unloading, req_combi, fraud_indicated FROM cashtransit_detail) AS cashtransit_detail
				LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
				LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
				LEFT JOIN client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
				LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id)
			WHERE 
				cashtransit_detail.state='ro_cit' AND cashtransit_detail.id_pengirim = '$client_pengirim' ORDER BY cashtransit_detail.id DESC
		";	
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$detailuang = json_decode($row->detail_uang, true);
		
		$arr = array(
			'id' => $row->id_ct,
			'id_cashtransit' => $row->id_cashtransit,
			'state' => $row->state,
			'branch' => $row->nama_branch,
			'metode' => ($row->metode=="CP" ? "CASH PICKUP" : ($row->metode=="CD" ? "CASH DELIVERY" : "")),
			'jenis' => $row->jenis,
			'runsheet' => $row->run_number,
			'pengirim' => $row->nama_pengirim,
			'penerima' => $row->nama_penerima,
			'kertas_100k' => $detailuang['kertas_100k'],
			'kertas_50k' => $detailuang['kertas_50k'],
			'kertas_20k' => $detailuang['kertas_20k'],
			'kertas_10k' => $detailuang['kertas_10k'],
			'kertas_5k' => $detailuang['kertas_5k'],
			'kertas_2k' => $detailuang['kertas_2k'],
			'kertas_1k' => $detailuang['kertas_1k'],
			'logam_1k' => $detailuang['logam_1k'],
			'logam_500' => $detailuang['logam_500'],
			'logam_200' => $detailuang['logam_200'],
			'logam_100' => $detailuang['logam_100'],
			'logam_50' => $detailuang['logam_50'],
			'total' => $row->total
		);
		
		// print_r($arr);

		echo json_encode($arr);
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
	
	public function edit_1($id) {
		$this->data['active_menu'] = "cashtransit_1";
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
			// $query = $this->db->query('SELECT * FROM cashtransit WHERE date="'.date("Y-m-d").'"');
			$query = $this->db->query('SELECT * FROM cashtransit WHERE date="'.date("Y-m-d").'" AND branch="'.$id.'"');
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
	
	public function edit_0($id) {
		$this->data['active_menu'] = "cashtransit_0";
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
			// $query = $this->db->query('SELECT * FROM cashtransit WHERE date="'.date("Y-m-d").'"');
			$query = $this->db->query('SELECT * FROM cashtransit WHERE date="'.date("Y-m-d").'" AND branch="'.$id.'"');
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
		$data['id'] = $_POST['id'];

		$table = "cashtransit";
		$res = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		if (!$res) {
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
	
	public function suggest_client1() {
		$search = $this->input->post('search');
		
		$query = "SELECT * FROM client_cit";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->nama_client; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function suggest_client2() {
		$search = $this->input->post('search');
		$prev_id = $this->input->post('prev_id');
		
		// $sql = "SELECT * FROM client_cit WHERE id!='$prev_id'";
		
		$query = "SELECT * FROM client_cit WHERE id!='$prev_id'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->nama_client; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
}