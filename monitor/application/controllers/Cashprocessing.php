<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cashprocessing extends CI_Controller {
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
	
	public function index() {
		$this->data['active_menu'] = "cashprocessing";
		
		$query = '
			SELECT 
				*, 
				cashtransit.id as id_ct, 
				IFNULL(
					(
						SELECT 
							COUNT(DISTINCT cashtransit_detail.id) 
						FROM 
							cashtransit_detail 
						LEFT JOIN 
							client ON(cashtransit_detail.id_bank=client.id) 
						WHERE 
							cashtransit_detail.id_cashtransit=cashtransit.id AND 
							cashtransit_detail.id NOT IN (SELECT id FROM runsheet_cashprocessing WHERE id_cashtransit=cashtransit.id) 
						GROUP BY 
							cashtransit_detail.id_cashtransit
					), 0
				) as count 
			FROM 
				cashtransit 
			LEFT JOIN 
				master_branch ON(cashtransit.branch=master_branch.id) 
			ORDER BY 
				cashtransit.id DESC, cashtransit.h_min DESC
		';
		$this->data['data_cashprocessing'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
        return view('admin/cashprocessing/index', $this->data);
	}
	
	function json() {
		$query = '
			SELECT 
				*, 
				cashtransit.id as id_ct, 
				master_branch.name as branch,
				IFNULL(
					(
						SELECT 
							COUNT(DISTINCT cashtransit_detail.id) 
						FROM 
							cashtransit_detail 
						LEFT JOIN 
							client ON(cashtransit_detail.id_bank=client.id) 
						LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
						WHERE 
							cashtransit_detail.id_cashtransit=id_ct 
							AND cashtransit_detail.id NOT IN (SELECT id FROM runsheet_cashprocessing WHERE id_cashtransit=id_ct) 
							AND cashtransit_detail.data_solve!="batal"
						GROUP BY 
							cashtransit_detail.id_cashtransit
					), 0
				) as count 
			FROM 
				cashtransit 
			LEFT JOIN 
				master_branch ON(cashtransit.branch=master_branch.id) 
		';
		
		$param['query'] = $query; //nama tabel dari database
		$param['column_order'] = array('id_ct'); //field yang ada di table user
		$param['column_search'] = array('action_date'); //field yang diizin untuk pencarian 
		$param['order'] = array(
			array('id_ct' => 'DESC'),
			array('h_min' => 'DESC'),
		);
		$param['where'] = array(array('cashtransit.action_date[!]' => ''));
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		echo $this->curl->simple_get(rest_api().'/datatables', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
	
	public function add() {
        $this->data['active_menu'] = "cashprocessing";
		$this->data['url'] = "cashprocessing/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$branch = $this->db->query("SELECT branch FROM cashtransit WHERE id='$id'")->row()->branch;
		$branch = $this->db->query("SELECT name FROM master_branch WHERE id='$branch'")->row()->name;
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/cashprocessing/form', $this->data);
	}
	
	public function edit() {
		$this->data['active_menu'] = "cashprocessing";
		$this->data['url'] = "cashprocessing/save";
		$this->data['flag'] = "edit";
		
		$id = $this->uri->segment(3);
		
		$query = "SELECT name FROM master_branch WHERE id IN (SELECT branch FROM cashtransit WHERE id='$id')";
		$branch = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->name;
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/cashprocessing/form', $this->data);
	}
	
	function delete() {
		
	}
	
	public function get_data() {
		// header('Content-Type: application/json');
		
		// $id = $this->uri->segment(3);
		// $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		// $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		
		// $data['id'] = $id;
		// $data['page'] = $page;
		// $data['rows'] = $rows;
		
		// $result = $this->curl->simple_post(rest_api().'/run_cashprocessing/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));

		// echo $result;
		
		$id = $this->uri->segment(3);
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;
		$offset = ($page-1)*$rows;
		
		$query = "
				SELECT
					*,
					master_zone.name AS zone_name,
					cashtransit_detail.id as id_ct, 
					cashtransit_detail.id_cashtransit, 
					cashtransit_detail.id_bank, 
					cashtransit_detail.state as state,
					cashtransit_detail.ctr as ctr2, 
					cashtransit_detail.pcs_100000 as pcs_100000, 
					cashtransit_detail.pcs_50000 as pcs_50000, 
					cashtransit_detail.pcs_20000 as pcs_20000, 
					cashtransit_detail.pcs_10000 as pcs_10000, 
					cashtransit_detail.pcs_5000 as pcs_5000, 
					cashtransit_detail.pcs_2000 as pcs_2000, 
					cashtransit_detail.pcs_1000 as pcs_1000, 
					cashtransit_detail.pcs_coin as pcs_coin, 
					cashtransit_detail.total as total, 
					client.cabang as branchz,
					client.bank,
					client.lokasi,
					client.sektor,
					cashtransit_detail.jenis,
					cashtransit_detail.ctr as ctr,
					client.denom,
					client.vendor,
					client.type_mesin,
					client.type as act,
					client.ctr as ctr2,
					runsheet_cashprocessing.pcs_100000 as s100k,
					runsheet_cashprocessing.pcs_50000 as s50k,
					runsheet_cashprocessing.pcs_20000 as s20k,
					runsheet_cashprocessing.pcs_10000 as s10k,
					runsheet_cashprocessing.pcs_5000 as s5k,
					runsheet_cashprocessing.pcs_2000 as s2k,
					runsheet_cashprocessing.pcs_1000 as s1k,
					runsheet_cashprocessing.pcs_coin as coin,
					runsheet_cashprocessing.total as nominal,
					runsheet_cashprocessing.ctr_1_no,
					runsheet_cashprocessing.ctr_2_no,
					runsheet_cashprocessing.ctr_3_no,
					runsheet_cashprocessing.ctr_4_no,
					runsheet_cashprocessing.ctr_5_no,
					runsheet_cashprocessing.cart_1_seal,
					runsheet_cashprocessing.cart_2_seal,
					runsheet_cashprocessing.cart_3_seal,
					runsheet_cashprocessing.cart_4_seal,
					runsheet_cashprocessing.cart_5_seal,
					runsheet_cashprocessing.divert,
					runsheet_cashprocessing.bag_seal,
					runsheet_cashprocessing.bag_seal_return,
					runsheet_cashprocessing.bag_no
				FROM cashtransit_detail 
					LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
					LEFT JOIN master_zone ON(master_zone.id=client.sektor) 
					LEFt JOIN master_branch ON(master_branch.id=master_zone.id_branch)
					LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
					LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
					WHERE cashtransit_detail.id_cashtransit='".$id."' AND cashtransit_detail.state='ro_atm' limit $offset,$rows";
		
		// $result = $this->curl->simple_post(rest_api().'/run_operational/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$query = "
				SELECT
					*,
					master_zone.name AS zone_name,
					cashtransit_detail.id as id_ct, 
					cashtransit_detail.id_cashtransit, 
					cashtransit_detail.id_bank, 
					cashtransit_detail.state as state,
					cashtransit_detail.ctr as ctr2, 
					cashtransit_detail.pcs_100000 as pcs_100000, 
					cashtransit_detail.pcs_50000 as pcs_50000, 
					cashtransit_detail.pcs_20000 as pcs_20000, 
					cashtransit_detail.pcs_10000 as pcs_10000, 
					cashtransit_detail.pcs_5000 as pcs_5000, 
					cashtransit_detail.pcs_2000 as pcs_2000, 
					cashtransit_detail.pcs_1000 as pcs_1000, 
					cashtransit_detail.pcs_coin as pcs_coin, 
					cashtransit_detail.total as total, 
					client.cabang as branchz,
					client.bank,
					client.lokasi,
					client.sektor,
					cashtransit_detail.jenis,
					cashtransit_detail.ctr as ctr,
					client.denom,
					client.vendor,
					client.type_mesin,
					client.type as act,
					client.ctr as ctr2,
					runsheet_cashprocessing.pcs_100000 as s100k,
					runsheet_cashprocessing.pcs_50000 as s50k,
					runsheet_cashprocessing.pcs_20000 as s20k,
					runsheet_cashprocessing.pcs_10000 as s10k,
					runsheet_cashprocessing.pcs_5000 as s5k,
					runsheet_cashprocessing.pcs_2000 as s2k,
					runsheet_cashprocessing.pcs_1000 as s1k,
					runsheet_cashprocessing.pcs_coin as coin,
					runsheet_cashprocessing.total as nominal,
					runsheet_cashprocessing.ctr_1_no,
					runsheet_cashprocessing.ctr_2_no,
					runsheet_cashprocessing.ctr_3_no,
					runsheet_cashprocessing.ctr_4_no,
					runsheet_cashprocessing.ctr_5_no,
					runsheet_cashprocessing.cart_1_seal,
					runsheet_cashprocessing.cart_2_seal,
					runsheet_cashprocessing.cart_3_seal,
					runsheet_cashprocessing.cart_4_seal,
					runsheet_cashprocessing.cart_5_seal,
					runsheet_cashprocessing.divert,
					runsheet_cashprocessing.bag_seal,
					runsheet_cashprocessing.bag_seal_return,
					runsheet_cashprocessing.bag_no
				FROM cashtransit_detail 
					LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
					LEFT JOIN master_zone ON(master_zone.id=client.sektor) 
					LEFt JOIN master_branch ON(master_branch.id=master_zone.id_branch)
					LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
					LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
					WHERE cashtransit_detail.id_cashtransit='".$id."' AND cashtransit_detail.state='ro_atm'";
		
		// $result = $this->curl->simple_post(rest_api().'/run_operational/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));
		$total = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$result["total"] = count($total);

		$items = array();
		$i = 0;
		foreach($res as $row){
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['wsid'] = $row->wsid;
			$items[$i]['id_bank'] = $row->id_bank;
			$items[$i]['state'] = $row->state;
			$items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
			$items[$i]['bank'] = $row->bank;
			$items[$i]['lokasi'] = $row->lokasi;
			$items[$i]['runsheet'] = substr($row->zone_name, 0, 5)." ".$row->kode_zone;
			$items[$i]['act'] = $row->act;
			$items[$i]['jenis'] = $row->jenis;
			$items[$i]['denom'] = $row->denom;
			$items[$i]['brand'] = $row->vendor;
			$items[$i]['model'] = $row->type_mesin;
			$items[$i]['detail_denom'] = 
										(
											$row->pcs_100000!=0 ? '100K : '.number_format($row->pcs_100000, 0, ',', ',') : (
												$row->pcs_50000!=0 ? '50K : '.number_format($row->pcs_50000, 0, ',', ',') : (
													$row->pcs_20000!=0 ? '20K : '.number_format($row->pcs_20000, 0, ',', ',') : (
														$row->pcs_10000!=0 ? '10K : '.number_format($row->pcs_10000, 0, ',', ',') : (
															$row->pcs_5000!=0 ? '5K : '.number_format($row->pcs_5000, 0, ',', ',') : (
																$row->pcs_1000!=0 ? '1K : '.number_format($row->pcs_1000, 0, ',', ',') : ''
															)
														)
													)
												)
											)
										);
			$items[$i]['pcs_100000'] = $row->pcs_100000;
			$items[$i]['pcs_50000'] = $row->pcs_50000;
			$items[$i]['pcs_20000'] = $row->pcs_20000;
			$items[$i]['pcs_10000'] = $row->pcs_10000;
			$items[$i]['pcs_5000'] = $row->pcs_5000;
			$items[$i]['pcs_2000'] = $row->pcs_2000;
			$items[$i]['pcs_1000'] = $row->pcs_1000;
			$items[$i]['pcs_coin'] = $row->pcs_coin;
			$items[$i]['s100k'] = $row->s100k;
			$items[$i]['s50k'] = $row->s50k;
			$items[$i]['s20k'] = $row->s20k;
			$items[$i]['s10k'] = $row->s10k;
			$items[$i]['s5k'] = $row->s5k;
			$items[$i]['s2k'] = $row->s2k;
			$items[$i]['s1k'] = $row->s1k;
			$items[$i]['coin'] = $row->coin;
			$items[$i]['ctr'] = $row->ctr;
			$items[$i]['cart_1_no'] = $row->ctr_1_no;
			$items[$i]['cart_2_no'] = $row->ctr_2_no;
			$items[$i]['cart_3_no'] = $row->ctr_3_no;
			$items[$i]['cart_4_no'] = $row->ctr_4_no;
			$items[$i]['cart_5_no'] = $row->ctr_5_no;
			$items[$i]['cart_1_seal'] = $row->cart_1_seal;
			$items[$i]['cart_2_seal'] = $row->cart_2_seal;
			$items[$i]['cart_3_seal'] = $row->cart_3_seal;
			$items[$i]['cart_4_seal'] = $row->cart_4_seal;
			$items[$i]['cart_5_seal'] = $row->cart_5_seal;
			$items[$i]['divert'] = $row->divert;
			$items[$i]['total'] = $row->total;
			$items[$i]['nominal'] = (100000*$row->s100k)+(50000*$row->s50k)+(20000*$row->s20k)+(10000*$row->s10k)+(5000*$row->s5k)+(2000*$row->s2k)+(1000*$row->s1k)+(1*$row->coin);
			$items[$i]['bag_seal'] = $row->bag_seal;
			$items[$i]['bag_seal_return'] = $row->bag_seal_return;
			$items[$i]['bag_no'] = $row->bag_no;
			$items[$i]['t_bag'] = $row->t_bag;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['id'] = $this->input->get('id');
		$this->data['id_ct'] = $this->input->get('id_ct');
		$this->data['index'] = $this->input->get('index');
		$this->data['state'] = $this->input->get('state');
		$this->data['row'] = json_decode($this->input->get('row'));
		$id = $this->input->get('id');
		
		$id_bank = $this->data['row']->id_bank;
		$ctr = $this->data['row']->ctr;
		$act = $this->data['row']->act;
		$sql_prev = "SELECT 
						*
							FROM
								(SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail
									LEFT JOIN
										runsheet_cashprocessing
											ON(runsheet_cashprocessing.id=cashtransit_detail.id)
												WHERE 
													cashtransit_detail.id_bank='$id_bank' AND
													cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id AND id_bank='$id_bank')";
													
		$prev = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_prev), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo $sql_prev;
		// echo $ctr;
		// echo $prev->ctr;
		if($act=="ATM") {
			// $prev->ctr = 2;
			if($ctr>$prev->ctr) {
				$ket = "NAIK LIMIT";
				$info = "";
				$saran = "";
			} else if($ctr<$prev->ctr) {
				$ket = "TURUN LIMIT";
				$info = "PENGISIAN SEBELUMNYA ".$prev->ctr." CARTRIDGE";
				$saran = "DISARANKAN MEMBAWA BAG ISI ".$prev->ctr." CARTRIDGE";
			} else {
				$ket = "LIMIT NORMAL";
				$info = "";
				$saran = "";
			}
			
			echo "<p style='margin-bottom: -5px; color: #e87333'>";
			echo $ket."<br>";
			echo $info."<br>";
			echo $saran;
			echo "</p>";
			
			
		}
		
		
		$is_pengisian_awal = count($prev);
		$this->data['is_pengisian_awal'] = $is_pengisian_awal;
		
		// echo $id."<br>";
		// echo $id_ct."<br>";
		// echo $index."<br>";
		// echo $state."<br>";
		// echo $row."<br>";
		
		$check = count(json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM runsheet_cashprocessing WHERE id='$id'"), array(CURLOPT_BUFFERSIZE => 10))));
		
		// if($check==0) {
			return view('admin/cashprocessing/show_form', $this->data);
		// }
	}
	
	public function suggest() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		$sql = "SELECT * FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN master_zone ON(client.sektor=master_zone.id) WHERE cashtransit_detail.id_cashtransit='$id_cashtransit' AND client.sektor NOT IN (SELECT run_number FROM runsheet_cashprocessing WHERE id_cashtransit='$id_cashtransit') GROUP BY client.sektor";
		// echo $sql;
		$result = $this->db->query($sql);
		// print_r($result->result());
		
		$list = array();
		if ($result->num_rows() > 0) {
			$key=0;
			foreach ($result->result() as $row) {
				$list[$key]['id'] = $row->sektor;
				$list[$key]['text'] = "(".$row->sektor.") ".$row->name; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function save_data() {
		// print_r($this->input->post());
		
		$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
		$run_number			= strtoupper(trim($this->input->post('run_number')));
		$petty_cash			= strtoupper(trim($this->input->post('petty_cash')));
		
		$data['id_cashtransit'] = $id_cashtransit;
		$data['run_number'] = $run_number;
		$data['petty_cash'] = $petty_cash;
		
		$this->db->trans_start();

		$this->db->insert('runsheet_cashprocessing', $data);

		$this->db->trans_complete();

		echo json_encode(array(
			'id_cashtransit' => $id_cashtransit,
			'run_number' => $run_number,
			'petty_cash' => $petty_cash
		));
	}
	
	function update_data() {
		$state				= trim($this->input->post('state'));
		if($state=="ro_cit") {
			$this->update_cit();
		} else if($state=="ro_atm") {
			$this->update_atm();
		}
		
		// $this->notification();
		
		$query = "SELECT * FROM runsheet_operational LEFT JOIN user ON(runsheet_operational.custodian_1=user.username) WHERE runsheet_operational.run_number='".$this->input->post('runsheet')."'";
		$token = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->token;
		
		$this->notification($token);
	}
	
	function update_atm() {
		$act				= $this->input->post('act');
		
		if($act=="ATM") {
			// PROSES JURNAL
			$id					= strtoupper(trim($this->input->post('id')));
			$id_bank = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT id_bank FROM cashtransit_detail WHERE id='$id'"), array(CURLOPT_BUFFERSIZE => 10)))->id_bank;
			
			$query = "SELECT * FROM (SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `data_solve`, `cpc_process`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id AND id_bank='$id_bank')";
			$prev = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			$keterangan = "";
			if(count($prev)==0) {
				$keterangan = "pengisian awal";
				$data_jurnal['id_detail'] = $id;
				$data_jurnal['tanggal'] = date("Y-m-d");
				$data_jurnal['keterangan'] = "pengisian awal";
				$data_jurnal['posisi'] = "kredit";
				$data_jurnal['kredit_100'] = ($this->input->post('s100k')!="" ? $this->input->post('s100k')*100000 : 0);
				$data_jurnal['kredit_50'] = ($this->input->post('s50k')!="" ? $this->input->post('s50k')*50000 : 0);
				
				$data_jurnal2['id_detail'] = $id;
				$data_jurnal2['tanggal'] = date("Y-m-d");
				$data_jurnal2['keterangan'] = "return";
				$data_jurnal2['posisi'] = "kredit";
				$data_jurnal2['kredit_100'] = 0;
				$data_jurnal2['kredit_50'] = 0;
			} else {
				$keterangan = "replenishment";
				$data_jurnal['id_detail'] = $id;
				$data_jurnal['tanggal'] = date("Y-m-d");
				$data_jurnal['keterangan'] = "replenishment";
				$data_jurnal['posisi'] = "kredit";
				$data_jurnal['kredit_100'] = ($this->input->post('s100k')!="" ? $this->input->post('s100k')*100000 : 0);
				$data_jurnal['kredit_50'] = ($this->input->post('s50k')!="" ? $this->input->post('s50k')*50000 : 0);
			}
			
			$num = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
				SELECT * FROM jurnal WHERE id_detail='$id' AND keterangan='$keterangan'
			"), array(CURLOPT_BUFFERSIZE => 10)));
			
			if(count($num)==0) {
				$table = "jurnal";
				$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_jurnal), array(CURLOPT_BUFFERSIZE => 10));
				
				if($keterangan=="pengisian awal") {
					$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_jurnal2), array(CURLOPT_BUFFERSIZE => 10));
				}
			} else {
				$table = "jurnal";
				$data_jurnal['id'] = $num->id;
				$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data_jurnal), array(CURLOPT_BUFFERSIZE => 10));
				if($keterangan=="pengisian awal") {
					$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data_jurnal2), array(CURLOPT_BUFFERSIZE => 10));
				}
			}
			
			$id					= strtoupper(trim($this->input->post('id')));
			$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
			$run_number			= strtoupper(trim($this->input->post('runsheet')));
			$pcs_100000			= strtoupper(trim($this->input->post('s100k')));
			$pcs_50000			= strtoupper(trim($this->input->post('s50k')));
			$cart_1_no			= trim($this->input->post('cart_1_no'));
			$cart_2_no			= trim($this->input->post('cart_2_no'));
			$cart_3_no			= trim($this->input->post('cart_3_no'));
			$cart_4_no			= trim($this->input->post('cart_4_no'));
			$cart_1_seal		= trim($this->input->post('cart_1_seal'));
			$cart_2_seal		= trim($this->input->post('cart_2_seal'));
			$cart_3_seal		= trim($this->input->post('cart_3_seal'));
			$cart_4_seal		= trim($this->input->post('cart_4_seal'));
			$value_1			= strtoupper(trim($this->input->post('value_1')));
			$value_2			= strtoupper(trim($this->input->post('value_2')));
			$value_3			= strtoupper(trim($this->input->post('value_3')));
			$value_4			= strtoupper(trim($this->input->post('value_4')));
			$divert				= trim($this->input->post('divert'));
			$bag_seal			= trim($this->input->post('bag_seal'));
			$bag_seal_return			= trim($this->input->post('bag_seal_return'));
			$bag_no				= trim($this->input->post('bag_no'));
			$t_bag				= trim($this->input->post('t_bag'));
			$nominal			= strtoupper(trim($this->input->post('nominal')));
			
			$value_1 = ($value_1!=="") ? ";".$value_1 : "";
			$value_2 = ($value_1!=="") ? ";".$value_2 : "";
			$value_3 = ($value_1!=="") ? ";".$value_3 : "";
			$value_4 = ($value_1!=="") ? ";".$value_4 : "";
			
			$data['id'] = $id;
			$data['id_cashtransit'] = $id_cashtransit;
			$data['run_number'] = $run_number;
			$data['pcs_100000'] = ($pcs_100000!=="") ? $pcs_100000 : 0;
			$data['pcs_50000'] = ($pcs_50000!=="") ? $pcs_50000 : 0;
			$data['ctr_1_no'] = ($cart_1_no!=="") ? $cart_1_no : "";
			$data['ctr_2_no'] = ($cart_2_no!=="") ? $cart_2_no : "";
			$data['ctr_3_no'] = ($cart_3_no!=="") ? $cart_3_no : "";
			$data['ctr_4_no'] = ($cart_4_no!=="") ? $cart_4_no : "";
			$data['cart_1_seal'] = ($cart_1_seal!=="") ? $cart_1_seal."".$value_1 : "";
			$data['cart_2_seal'] = ($cart_2_seal!=="") ? $cart_2_seal."".$value_2 : "";
			$data['cart_3_seal'] = ($cart_3_seal!=="") ? $cart_3_seal."".$value_3 : "";
			$data['cart_4_seal'] = ($cart_4_seal!=="") ? $cart_4_seal."".$value_4 : "";
			$data['divert'] = $divert;
			$data['bag_seal'] = $bag_seal;
			$data['bag_seal_return'] = $bag_seal_return;
			$data['bag_no'] = $bag_no;
			$data['t_bag'] = $t_bag;
			
			$ctr_1 = ($data['cart_1_seal']!=="") ? 1 : 0;
			$ctr_2 = ($data['cart_2_seal']!=="") ? 1 : 0;
			$ctr_3 = ($data['cart_3_seal']!=="") ? 1 : 0;
			$ctr_4 = ($data['cart_4_seal']!=="") ? 1 : 0;
			
			// $data['total'] = ($ctr_1+$ctr_2+$ctr_3+$ctr_4)*((100000*$pcs_100000)+(50000*$pcs_50000));
			$data['total'] = (100000*intval($pcs_100000))+(50000*intval($pcs_50000));
			
			$query = "SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'";
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			$update_seal = array();
			if(!empty($bag_no)) { array_push($update_seal, $bag_no); } 
			if(!empty($cart_1_seal)) { array_push($update_seal, $cart_1_seal); } 
			if(!empty($cart_2_seal)) { array_push($update_seal, $cart_2_seal); } 
			if(!empty($cart_3_seal)) { array_push($update_seal, $cart_3_seal); } 
			if(!empty($cart_4_seal)) { array_push($update_seal, $cart_4_seal); } 
			if(!empty($divert)) { array_push($update_seal, $divert); } 
			if(!empty($bag_seal)) { array_push($update_seal, $bag_seal); } 
			if(!empty($bag_seal_return)) { array_push($update_seal, $bag_seal_return); } 
			if(!empty($t_bag)) { array_push($update_seal, $t_bag); } 
			
			foreach($update_seal as $seal) {
				// $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM master_seal WHERE LOWER(kode) = BINARY(kode) AND kode='$seal'";
				
				// echo $sql."\n";
				// echo substr($seal, 0, 1)."\n";
				if(substr($seal, 0, 1)=='a') {
					$sql = "UPDATE master_seal SET status='used' WHERE LOWER(kode) = BINARY(kode) AND kode='$seal'";
					$cpc_prepare_sql = "UPDATE cpc_prepared SET status='used' WHERE seal='$seal'";
					$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$cpc_prepare_sql), array(CURLOPT_BUFFERSIZE => 10));
				} else if(substr($seal, 0, 1)=='A') {
					$sql = "UPDATE master_seal SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$seal'";
				} else if(substr($seal, 0, 1)=='B') {
					$sql = "UPDATE master_tbag SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$seal'";
				} else {
					$sql = "UPDATE master_bag SET status='used' WHERE kode='$seal'";
				}
				
				$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10));
			}
			
			// $table = "master_seal";
			// $res = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			
			// error_reporting(0);
			// echo "<pre>";
			// print_r($count);
			
			if($count->cnt==0) {
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			} else {
				// UPDATE CASHPROCESSING
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			}
		} else if($act=="CRM") {
			$id					= strtoupper(trim($this->input->post('id')));
			$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
			$run_number			= strtoupper(trim($this->input->post('runsheet')));
			$pcs_100000			= strtoupper(trim($this->input->post('s100k')));
			$pcs_50000			= strtoupper(trim($this->input->post('s50k')));
			$cart_1_no			= trim($this->input->post('cart_1_no'));
			$cart_2_no			= trim($this->input->post('cart_2_no'));
			$cart_3_no			= trim($this->input->post('cart_3_no'));
			$cart_4_no			= trim($this->input->post('cart_4_no'));
			$cart_5_no			= trim($this->input->post('cart_5_no'));
			$cart_1_seal		= trim($this->input->post('cart_1_seal'));
			$cart_2_seal		= trim($this->input->post('cart_2_seal'));
			$cart_3_seal		= trim($this->input->post('cart_3_seal'));
			$cart_4_seal		= trim($this->input->post('cart_4_seal'));
			$cart_5_seal		= trim($this->input->post('cart_5_seal'));
			$denom_1			= strtoupper(trim($this->input->post('denom_1')));
			$denom_2			= strtoupper(trim($this->input->post('denom_2')));
			$denom_3			= strtoupper(trim($this->input->post('denom_3')));
			$denom_4			= strtoupper(trim($this->input->post('denom_4')));
			$value_1			= strtoupper(trim($this->input->post('value_1')));
			$value_2			= strtoupper(trim($this->input->post('value_2')));
			$value_3			= strtoupper(trim($this->input->post('value_3')));
			$value_4			= strtoupper(trim($this->input->post('value_4')));
			$divert				= trim($this->input->post('divert'));
			$bag_seal			= trim($this->input->post('bag_seal'));
			$bag_seal_return			= trim($this->input->post('bag_seal_return'));
			$bag_no				= trim($this->input->post('bag_no'));
			$t_bag				= trim($this->input->post('t_bag'));
			$nominal			= strtoupper(trim($this->input->post('nominal')));
			
			$jurnal_100 = 0;
			$jurnal_50 = 0;
			if($denom_1==100) {
				$jurnal_100 = $jurnal_100 + ($value_1*100000);
			} else {
				$jurnal_50 = $jurnal_50 + ($value_1*50000);
			}
			if($denom_2==100) {
				$jurnal_100 = $jurnal_100 + ($value_2*100000);
			} else {
				$jurnal_50 = $jurnal_50 + ($value_2*50000);
			}
			if($denom_3==100) {
				$jurnal_100 = $jurnal_100 + ($value_3*100000);
			} else {
				$jurnal_50 = $jurnal_50 + ($value_3*50000);
			}
			if($denom_4==100) {
				$jurnal_100 = $jurnal_100 + ($value_4*100000);
			} else {
				$jurnal_50 = $jurnal_50 + ($value_4*50000);
			}
			
			
			// PROSES JURNAL
			$id					= strtoupper(trim($this->input->post('id')));
			$id_bank = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT id_bank FROM cashtransit_detail WHERE id='$id'"), array(CURLOPT_BUFFERSIZE => 10)))->id_bank;
			
			$query = "SELECT * FROM (SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `data_solve`, `cpc_process`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id AND id_bank='$id_bank')";
			$prev = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			$keterangan = "";
			if(count($prev)==0) {
				$keterangan = "pengisian awal";
				$data_jurnal['id_detail'] = $id;
				$data_jurnal['tanggal'] = date("Y-m-d");
				$data_jurnal['keterangan'] = "pengisian awal";
				$data_jurnal['posisi'] = "kredit";
				$data_jurnal['kredit_100'] = $jurnal_100;
				$data_jurnal['kredit_50'] = $jurnal_50;
				
				$data_jurnal2['id_detail'] = $id;
				$data_jurnal2['tanggal'] = date("Y-m-d");
				$data_jurnal2['keterangan'] = "return";
				$data_jurnal2['posisi'] = "kredit";
				$data_jurnal2['kredit_100'] = 0;
				$data_jurnal2['kredit_50'] = 0;
			} else {
				$keterangan = "replenishment";
				$data_jurnal['id_detail'] = $id;
				$data_jurnal['tanggal'] = date("Y-m-d");
				$data_jurnal['keterangan'] = "replenishment";
				$data_jurnal['posisi'] = "kredit";
				$data_jurnal['kredit_100'] = $jurnal_100;
				$data_jurnal['kredit_50'] = $jurnal_50;
			}
			
			$num = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
				SELECT * FROM jurnal WHERE id_detail='$id' AND keterangan='$keterangan'
			"), array(CURLOPT_BUFFERSIZE => 10)));
			
			if(count($num)==0) {
				$table = "jurnal";
				$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_jurnal), array(CURLOPT_BUFFERSIZE => 10));
				
				if($keterangan=="pengisian awal") {
					$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_jurnal2), array(CURLOPT_BUFFERSIZE => 10));
				}
			} else {
				$table = "jurnal";
				$data_jurnal['id'] = $num->id;
				$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data_jurnal), array(CURLOPT_BUFFERSIZE => 10));
				if($keterangan=="pengisian awal") {
					$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data_jurnal2), array(CURLOPT_BUFFERSIZE => 10));
				}
			}
			
			
			
			$data['id'] = $id;
			$data['id_cashtransit'] = $id_cashtransit;
			$data['run_number'] = $run_number;
			$data['pcs_100000'] = ($pcs_100000!=="") ? $pcs_100000 : 0;
			$data['pcs_50000'] = ($pcs_50000!=="") ? $pcs_50000 : 0;
			$data['ctr_1_no'] = ($cart_1_no!=="") ? $cart_1_no : "";
			$data['ctr_2_no'] = ($cart_2_no!=="") ? $cart_2_no : "";
			$data['ctr_3_no'] = ($cart_3_no!=="") ? $cart_3_no : "";
			$data['ctr_4_no'] = ($cart_4_no!=="") ? $cart_4_no : "";
			$data['ctr_5_no'] = ($cart_4_no!=="") ? $cart_5_no : "";
			$data['cart_1_seal'] = ($cart_1_seal!=="") ? $cart_1_seal.";".$denom_1.";".$value_1 : "";
			$data['cart_2_seal'] = ($cart_2_seal!=="") ? $cart_2_seal.";".$denom_2.";".$value_2 : "";
			$data['cart_3_seal'] = ($cart_3_seal!=="") ? $cart_3_seal.";".$denom_3.";".$value_3 : "";
			$data['cart_4_seal'] = ($cart_4_seal!=="") ? $cart_4_seal.";".$denom_4.";".$value_4 : "";
			$data['cart_5_seal'] = ($cart_5_seal!=="") ? $cart_5_seal : "";
			$data['divert'] = $divert;
			$data['bag_seal'] = $bag_seal;
			$data['bag_seal_return'] = $bag_seal_return;
			$data['bag_no'] = $bag_no;
			$data['t_bag'] = $t_bag;
			$ctr_1 = ($data['cart_1_seal']!=="") ? 1 : 0;
			$ctr_2 = ($data['cart_2_seal']!=="") ? 1 : 0;
			$ctr_3 = ($data['cart_3_seal']!=="") ? 1 : 0;
			$ctr_4 = ($data['cart_4_seal']!=="") ? 1 : 0;
			$ctr_5 = ($data['cart_5_seal']!=="") ? 1 : 0;
			// $data['total'] = ($ctr_1+$ctr_2+$ctr_3+$ctr_4)*((100000*$pcs_100000)+(50000*$pcs_50000));
			$t_cart_1 = $denom_1*$value_1;
			$t_cart_2 = $denom_2*$value_2;
			$t_cart_3 = $denom_3*$value_3;
			$t_cart_4 = $denom_4*$value_4;
			$data['total'] = ($t_cart_1+$t_cart_2+$t_cart_3+$t_cart_4)*1000;
			
			// print_r($data);
			
			$query = "SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'";
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			$update_seal = array();
			if(!empty($cart_1_seal)) { array_push($update_seal, $cart_1_seal); } 
			if(!empty($cart_2_seal)) { array_push($update_seal, $cart_2_seal); } 
			if(!empty($cart_3_seal)) { array_push($update_seal, $cart_3_seal); } 
			if(!empty($cart_4_seal)) { array_push($update_seal, $cart_4_seal); } 
			if(!empty($cart_5_seal)) { array_push($update_seal, $cart_5_seal); } 
			if(!empty($divert)) { array_push($update_seal, $divert); } 
			if(!empty($bag_seal)) { array_push($update_seal, $bag_seal); } 
			if(!empty($bag_seal_return)) { array_push($update_seal, $bag_seal_return); } 
			if(!empty($t_bag)) { array_push($update_seal, $t_bag); } 
			
			// foreach($update_seal as $seal) {
				// // $updated['status'] = "used";
				// // $this->db->where('kode', $seal);
				// // $this->db->update('master_seal', $updated);
				// $table = "master_seal";
				// $where = $seal;
				// $updated['status'] = "used";
				// $res = $this->curl->simple_get(rest_api().'/select/update_seal', array('table'=>$table, 'where'=>$where, 'data'=>$updated), array(CURLOPT_BUFFERSIZE => 10));
			// }
			
			foreach($update_seal as $seal) {
				if(substr($seal, 0, 1)=='a') {
					$sql = "UPDATE master_seal SET status='used' WHERE LOWER(kode) = BINARY(kode) AND kode='$seal'";
					$cpc_prepare_sql = "UPDATE cpc_prepared SET status='used' WHERE seal='$seal'";
					$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$cpc_prepare_sql), array(CURLOPT_BUFFERSIZE => 10));
				} else if(substr($seal, 0, 1)=='A') {
					$sql = "UPDATE master_seal SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$seal'";
				} else if(substr($seal, 0, 1)=='B') {
					$sql = "UPDATE master_tbag SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$seal'";
				} else {
					$sql = "UPDATE master_bag SET status='used' WHERE kode='$seal'";
				}
				$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10));
			}
			
			if($count->cnt==0) {
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			} else {
				// UPDATE CASHPROCESSING
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			}
		} else if($act=="CDM") {
			$id					= strtoupper(trim($this->input->post('id')));
			$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
			$run_number			= strtoupper(trim($this->input->post('runsheet')));
			$pcs_100000			= strtoupper(trim($this->input->post('s100k')));
			$pcs_50000			= strtoupper(trim($this->input->post('s50k')));
			$cart_1_no			= trim($this->input->post('cart_1_no'));
			$cart_2_no			= trim($this->input->post('cart_2_no'));
			$cart_3_no			= trim($this->input->post('cart_3_no'));
			$cart_4_no			= trim($this->input->post('cart_4_no'));
			$cart_5_no			= trim($this->input->post('cart_5_no'));
			$cart_1_seal		= trim($this->input->post('cart_1_seal'));
			$cart_2_seal		= trim($this->input->post('cart_2_seal'));
			$cart_3_seal		= trim($this->input->post('cart_3_seal'));
			$cart_4_seal		= trim($this->input->post('cart_4_seal'));
			$cart_5_seal		= trim($this->input->post('cart_5_seal'));
			$denom_1			= strtoupper(trim($this->input->post('denom_1')));
			$value_1			= strtoupper(trim($this->input->post('value_1')));
			$denom_2			= strtoupper(trim($this->input->post('denom_2')));
			$value_2			= strtoupper(trim($this->input->post('value_2')));
			$denom_3			= strtoupper(trim($this->input->post('denom_3')));
			$value_3			= strtoupper(trim($this->input->post('value_3')));
			$denom_4			= strtoupper(trim($this->input->post('denom_4')));
			$value_4			= strtoupper(trim($this->input->post('value_4')));
			$divert				= trim($this->input->post('divert'));
			$bag_seal			= trim($this->input->post('bag_seal'));
			$bag_seal_return			= trim($this->input->post('bag_seal_return'));
			$bag_no				= trim($this->input->post('bag_no'));
			$t_bag				= trim($this->input->post('t_bag'));
			$nominal			= strtoupper(trim($this->input->post('nominal')));
			
			
			// PROSES JURNAL
			$id					= strtoupper(trim($this->input->post('id')));
			$id_bank = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT id_bank FROM 
			(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, jam_cash_in, cpc_process, updated_date, loading, unloading, req_combi, fraud_indicated FROM cashtransit_detail) AS cashtransit_detail
			WHERE id='$id'"), array(CURLOPT_BUFFERSIZE => 10)))->id_bank;
			
			$query = "SELECT * FROM (SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `data_solve`, `cpc_process`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail LEFT JOIN runsheet_cashprocessing ON (runsheet_cashprocessing.id=cashtransit_detail.id) WHERE cashtransit_detail.id_bank='$id_bank' AND cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id AND id_bank='$id_bank')";
			$prev = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			$keterangan = "";
			if(count($prev)==0) {
				$keterangan = "pengisian awal";
				$data_jurnal['id_detail'] = $id;
				$data_jurnal['tanggal'] = date("Y-m-d");
				$data_jurnal['keterangan'] = "pengisian awal";
				$data_jurnal['posisi'] = "kredit";
				$data_jurnal['kredit_100'] = 0;
				$data_jurnal['kredit_50'] = 0;
				
				$data_jurnal2['id_detail'] = $id;
				$data_jurnal2['tanggal'] = date("Y-m-d");
				$data_jurnal2['keterangan'] = "return";
				$data_jurnal2['posisi'] = "kredit";
				$data_jurnal2['kredit_100'] = 0;
				$data_jurnal2['kredit_50'] = 0;
			} else {
				$keterangan = "replenishment";
				$data_jurnal['id_detail'] = $id;
				$data_jurnal['tanggal'] = date("Y-m-d");
				$data_jurnal['keterangan'] = "replenishment";
				$data_jurnal['posisi'] = "kredit";
				$data_jurnal['kredit_100'] = 0;
				$data_jurnal['kredit_50'] = 0;
			}
			
			$num = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
				SELECT * FROM jurnal WHERE id_detail='$id' AND keterangan='$keterangan'
			"), array(CURLOPT_BUFFERSIZE => 10)));
			
			if(count($num)==0) {
				$table = "jurnal";
				$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_jurnal), array(CURLOPT_BUFFERSIZE => 10));
				
				if($keterangan=="pengisian awal") {
					$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_jurnal2), array(CURLOPT_BUFFERSIZE => 10));
				}
			} else {
				$table = "jurnal";
				$data_jurnal['id'] = $num->id;
				$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data_jurnal), array(CURLOPT_BUFFERSIZE => 10));
				if($keterangan=="pengisian awal") {
					$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data_jurnal2), array(CURLOPT_BUFFERSIZE => 10));
				}
			}
			
			
			$data['id'] = $id;
			$data['id_cashtransit'] = $id_cashtransit;
			$data['run_number'] = $run_number;
			$data['pcs_100000'] = ($pcs_100000!=="") ? $pcs_100000 : 0;
			$data['pcs_50000'] = ($pcs_50000!=="") ? $pcs_50000 : 0;
			$data['ctr_1_no'] = ($cart_1_no!=="") ? $cart_1_no : "";
			$data['ctr_2_no'] = ($cart_2_no!=="") ? $cart_2_no : "";
			$data['ctr_3_no'] = ($cart_3_no!=="") ? $cart_3_no : "";
			$data['ctr_4_no'] = ($cart_4_no!=="") ? $cart_4_no : "";
			$data['ctr_5_no'] = ($cart_4_no!=="") ? $cart_5_no : "";
			$data['cart_1_seal'] = ($cart_1_seal!=="") ? $cart_1_seal : "";
			$data['cart_2_seal'] = ($cart_2_seal!=="") ? $cart_2_seal : "";
			$data['cart_3_seal'] = ($cart_3_seal!=="") ? $cart_3_seal : "";
			$data['cart_4_seal'] = ($cart_4_seal!=="") ? $cart_4_seal : "";
			$data['cart_5_seal'] = ($cart_5_seal!=="") ? $cart_5_seal : "";
			$data['divert'] = $divert;
			$data['bag_seal'] = $bag_seal;
			$data['bag_seal_return'] = $bag_seal_return;
			$data['bag_no'] = $bag_no;
			$data['t_bag'] = $t_bag;
			$ctr_1 = ($data['cart_1_seal']!=="") ? 1 : 0;
			$ctr_2 = ($data['cart_2_seal']!=="") ? 1 : 0;
			$ctr_3 = ($data['cart_3_seal']!=="") ? 1 : 0;
			$ctr_4 = ($data['cart_4_seal']!=="") ? 1 : 0;
			$ctr_5 = ($data['cart_5_seal']!=="") ? 1 : 0;
			$data['total'] = ($ctr_1+$ctr_2+$ctr_3+$ctr_4)*((100000*$pcs_100000)+(50000*$pcs_50000));
			
			$query = "SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'";
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			$update_seal = array();
			if(!empty($cart_1_seal)) { array_push($update_seal, $cart_1_seal); } 
			if(!empty($cart_2_seal)) { array_push($update_seal, $cart_2_seal); } 
			if(!empty($cart_3_seal)) { array_push($update_seal, $cart_3_seal); } 
			if(!empty($cart_4_seal)) { array_push($update_seal, $cart_4_seal); } 
			if(!empty($cart_5_seal)) { array_push($update_seal, $cart_5_seal); } 
			if(!empty($divert)) { array_push($update_seal, $divert); } 
			if(!empty($bag_seal)) { array_push($update_seal, $bag_seal); } 
			if(!empty($bag_seal_return)) { array_push($update_seal, $bag_seal_return); } 
			if(!empty($t_bag)) { array_push($update_seal, $t_bag); } 
			
			// foreach($update_seal as $seal) {
				// // $updated['status'] = "used";
				// // $this->db->where('kode', $seal);
				// // $this->db->update('master_seal', $updated);
				// $table = "master_seal";
				// $where = $seal;
				// $updated['status'] = "used";
				// $res = $this->curl->simple_get(rest_api().'/select/update_seal', array('table'=>$table, 'where'=>$where, 'data'=>$updated), array(CURLOPT_BUFFERSIZE => 10));
			// }
			
			foreach($update_seal as $seal) {
				if(substr($seal, 0, 1)=='a') {
					$sql = "UPDATE master_seal SET status='used' WHERE LOWER(kode) = BINARY(kode) AND kode='$seal'";
					$cpc_prepare_sql = "UPDATE cpc_prepared SET status='used' WHERE seal='$seal'";
					$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$cpc_prepare_sql), array(CURLOPT_BUFFERSIZE => 10));
				} else if(substr($seal, 0, 1)=='A') {
					$sql = "UPDATE master_seal SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$seal'";
				} else if(substr($seal, 0, 1)=='B') {
					$sql = "UPDATE master_tbag SET status='used' WHERE UPPER(kode) = BINARY(kode) AND kode='$seal'";
				} else {
					$sql = "UPDATE master_bag SET status='used' WHERE kode='$seal'";
				}
				$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10));
			}
			
			if($count->cnt==0) {
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			} else {
				// UPDATE CASHPROCESSING
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			}

		}
		
		$querys = "select SQL_CALC_FOUND_ROWS *,
				cashtransit_detail.id as id_ct, 
				cashtransit_detail.id_cashtransit, 
				cashtransit_detail.id_bank, 
				cashtransit_detail.state as state,
				cashtransit_detail.ctr as ctr2, 
				cashtransit_detail.pcs_100000 as pcs_100000, 
				cashtransit_detail.pcs_50000 as pcs_50000, 
				cashtransit_detail.pcs_20000 as pcs_20000, 
				cashtransit_detail.pcs_10000 as pcs_10000, 
				cashtransit_detail.pcs_5000 as pcs_5000, 
				cashtransit_detail.pcs_2000 as pcs_2000, 
				cashtransit_detail.pcs_1000 as pcs_1000, 
				cashtransit_detail.pcs_coin as pcs_coin, 
				cashtransit_detail.total as total, 
				client.cabang as branch,
				client.bank,
				client.lokasi,
				client.sektor,
				cashtransit_detail.jenis,
				cashtransit_detail.ctr as ctr,
				client.denom,
				client.vendor,
				client.type_mesin,
				client.type as act,
				client.ctr as ctr2,
				runsheet_cashprocessing.pcs_100000 as s100k,
				runsheet_cashprocessing.pcs_50000 as s50k,
				runsheet_cashprocessing.pcs_20000 as s20k,
				runsheet_cashprocessing.pcs_10000 as s10k,
				runsheet_cashprocessing.pcs_5000 as s5k,
				runsheet_cashprocessing.pcs_2000 as s2k,
				runsheet_cashprocessing.pcs_1000 as s1k,
				runsheet_cashprocessing.pcs_coin as coin,
				runsheet_cashprocessing.total as nominal,
				runsheet_cashprocessing.ctr_1_no,
				runsheet_cashprocessing.ctr_2_no,
				runsheet_cashprocessing.ctr_3_no,
				runsheet_cashprocessing.ctr_4_no,
				runsheet_cashprocessing.ctr_5_no,
				runsheet_cashprocessing.cart_1_seal,
				runsheet_cashprocessing.cart_2_seal,
				runsheet_cashprocessing.cart_3_seal,
				runsheet_cashprocessing.cart_4_seal,
				runsheet_cashprocessing.cart_5_seal,
				runsheet_cashprocessing.divert,
				runsheet_cashprocessing.bag_seal,
				runsheet_cashprocessing.bag_no
			from 
				(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, jam_cash_in, cpc_process, updated_date, loading, unloading, req_combi, fraud_indicated FROM cashtransit_detail) AS cashtransit_detail
				LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
				LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
				LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
				WHERE runsheet_cashprocessing.id='".$id."'";
				
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$querys), array(CURLOPT_BUFFERSIZE => 10)));
		
		echo json_encode(array(
			'id' => $row->id_ct,
			'id_cashtransit' => $row->id_cashtransit,
			'wsid' => $row->wsid,
			'id_bank' => $row->id_bank,
			'state' => $row->state,
			'branch' => json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT name FROM master_branch where id='".$row->branch."'"), array(CURLOPT_BUFFERSIZE => 10)))->name,
			'bank' => $row->bank,
			'lokasi' => $row->lokasi,
			'runsheet' => $row->sektor,
			'act' => $row->act,
			'jenis' => $row->jenis,
			'denom' => $row->denom,
			'brand' => $row->vendor,
			'model' => $row->type_mesin,
			'pcs_100000' => $row->pcs_100000,
			'pcs_50000' => $row->pcs_50000,
			'pcs_20000' => $row->pcs_20000,
			'pcs_10000' => $row->pcs_10000,
			'pcs_5000' => $row->pcs_5000,
			'pcs_2000' => $row->pcs_2000,
			'pcs_1000' => $row->pcs_1000,
			'pcs_coin' => $row->pcs_coin,
			's100k' => $row->s100k,
			's50k' => $row->s50k,
			's20k' => $row->s20k,
			's10k' => $row->s10k,
			's5k' => $row->s5k,
			's2k' => $row->s2k,
			's1k' => $row->s1k,
			'coin' => $row->coin,
			'ctr' => $row->ctr,
			'cart_1_no' => $row->ctr_1_no,
			'cart_2_no' => $row->ctr_2_no,
			'cart_3_no' => $row->ctr_3_no,
			'cart_4_no' => $row->ctr_4_no,
			'cart_5_no' => $row->ctr_5_no,
			'cart_1_seal' => $row->cart_1_seal,
			'cart_2_seal' => $row->cart_2_seal,
			'cart_3_seal' => $row->cart_3_seal,
			'cart_4_seal' => $row->cart_4_seal,
			'cart_5_seal' => $row->cart_5_seal,
			'divert' => $row->divert,
			'total' => $row->total,
			'nominal' => (100000*$row->s100k)+(50000*$row->s50k)+(20000*$row->s20k)+(10000*$row->s10k)+(5000*$row->s5k)+(2000*$row->s2k)+(1000*$row->s1k)+(1*$row->coin),
			'bag_seal' => $row->bag_seal,
			'bag_no' => $row->bag_no,
			// 't_bag' => $row->t_bag
		));
	}
	
	function update_data2() {
		// $id = $this->input->get("id");
		
		
		$state				= trim($this->input->post('state'));
		if($state=="ro_cit") {
			$id					= strtoupper(trim($this->input->post('id')));
			$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
			$run_number			= strtoupper(trim($this->input->post('runsheet')));
			$pcs_100000			= strtoupper(trim($this->input->post('100k')));
			$pcs_50000			= strtoupper(trim($this->input->post('50k')));
			$pcs_20000			= strtoupper(trim($this->input->post('20k')));
			$pcs_10000			= strtoupper(trim($this->input->post('10k')));
			$pcs_5000			= strtoupper(trim($this->input->post('5k')));
			$pcs_2000			= strtoupper(trim($this->input->post('2k')));
			$pcs_1000			= strtoupper(trim($this->input->post('1k')));
			$pcs_coin			= strtoupper(trim($this->input->post('coins')));
			$bag_seal			= strtoupper(trim($this->input->post('bag_seal')));
			$bag_no				= strtoupper(trim($this->input->post('bag_no')));
			$nominal			= strtoupper(trim($this->input->post('nominal')));
			
			$data['id'] = $id;
			$data['id_cashtransit'] = $id_cashtransit;
			$data['run_number'] = $run_number;
			$data['pcs_100000'] = ($pcs_100000!=="") ? $pcs_100000 : 0;
			$data['pcs_50000'] = ($pcs_50000!=="") ? $pcs_50000 : 0;
			$data['pcs_20000'] = ($pcs_20000!=="") ? $pcs_20000 : 0;
			$data['pcs_10000'] = ($pcs_10000!=="") ? $pcs_10000 : 0;
			$data['pcs_5000'] = ($pcs_5000!=="") ? $pcs_5000 : 0;
			$data['pcs_2000'] = ($pcs_2000!=="") ? $pcs_2000 : 0;
			$data['pcs_1000'] = ($pcs_1000!=="") ? $pcs_1000 : 0;
			$data['pcs_coin'] = ($pcs_coin!=="") ? $pcs_coin : 0;
			$data['bag_seal'] = $bag_seal;
			$data['bag_no'] = $bag_no;
			$data['total'] = $nominal;
			
			
		
			// print_r($data);
			
			$count = $this->db->query("SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'")->row();
			
			if($count->cnt==0) {
				$this->db->trans_start();
				$this->db->insert('runsheet_cashprocessing', $data);
				$this->db->trans_complete();
			} else {
				$this->db->trans_start();
				$this->db->where('id', $id);
				$this->db->update('runsheet_cashprocessing', $data);
				$this->db->trans_complete();
			}
			
			
			$row = $this->db->query("select *,
				cashtransit_detail.id as id_ct, 
				cashtransit_detail.id_cashtransit, 
				cashtransit_detail.id_bank, 
				cashtransit_detail.state as state,
				cashtransit_detail.ctr as ctr, 
				cashtransit_detail.pcs_100000 as pcs_100000, 
				cashtransit_detail.pcs_50000 as pcs_50000, 
				cashtransit_detail.pcs_20000 as pcs_20000, 
				cashtransit_detail.pcs_10000 as pcs_10000, 
				cashtransit_detail.pcs_5000 as pcs_5000, 
				cashtransit_detail.pcs_2000 as pcs_2000, 
				cashtransit_detail.pcs_1000 as pcs_1000, 
				cashtransit_detail.pcs_coin as pcs_coin, 
				cashtransit_detail.total as total, 
				client.cabang as branch,
				client.bank,
				client.lokasi,
				client.sektor,
				cashtransit_detail.jenis,
				client.denom,
				client.vendor,
				client.type_mesin,
				runsheet_cashprocessing.pcs_100000 as s100k,
				runsheet_cashprocessing.pcs_50000 as s50k,
				runsheet_cashprocessing.pcs_20000 as s20k,
				runsheet_cashprocessing.pcs_10000 as s10k,
				runsheet_cashprocessing.pcs_5000 as s5k,
				runsheet_cashprocessing.pcs_2000 as s2k,
				runsheet_cashprocessing.pcs_1000 as s1k,
				runsheet_cashprocessing.pcs_coin as coin,
				runsheet_cashprocessing.total as nominal,
				runsheet_cashprocessing.bag_seal,
				runsheet_cashprocessing.bag_no
			from cashtransit_detail 
				LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
				LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
				LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
				WHERE runsheet_cashprocessing.id='".$id."'")->row();

			echo json_encode(array(
				'id' => $row->id_ct,
				'id_cashtransit' => $row->id_cashtransit,
				'id_bank' => $row->id_bank,
				'state' => $row->state,
				'branch' => $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name,
				'bank' => $row->bank,
				'lokasi' => $row->lokasi,
				'runsheet' => $row->sektor,
				'jenis' => $row->jenis,
				'denom' => $row->denom,
				'brand' => $row->vendor,
				'model' => $row->type_mesin,
				'pcs_100000' => $row->pcs_100000,
				'pcs_50000' => $row->pcs_50000,
				'pcs_20000' => $row->pcs_20000,
				'pcs_10000' => $row->pcs_10000,
				'pcs_5000' => $row->pcs_5000,
				'pcs_2000' => $row->pcs_2000,
				'pcs_1000' => $row->pcs_1000,
				'pcs_coin' => $row->pcs_coin,
				's100k' => $row->s100k,
				's50k' => $row->s50k,
				's20k' => $row->s20k,
				's10k' => $row->s10k,
				's5k' => $row->s5k,
				's2k' => $row->s2k,
				's1k' => $row->s1k,
				'coin' => $row->coin,
				'ctr' => $row->ctr,
				'total' => $row->total,
				'nominal' => (100000*$row->s100k)+(50000*$row->s50k)+(20000*$row->s20k)+(10000*$row->s10k)+(5000*$row->s5k)+(2000*$row->s2k)+(1000*$row->s1k)+(1*$row->coin),
				'bag_seal' => $row->bag_seal,
				'bag_no' => $row->bag_no
			));
		} else {
			
			$act				= $this->input->post('act');
			
			if($act=="ATM") {
				$id					= strtoupper(trim($this->input->post('id')));
				$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
				$run_number			= strtoupper(trim($this->input->post('runsheet')));
				$pcs_100000			= strtoupper(trim($this->input->post('s100k')));
				$pcs_50000			= strtoupper(trim($this->input->post('s50k')));
				$cart_1_no			= strtoupper(trim($this->input->post('cart_1_no')));
				$cart_2_no			= strtoupper(trim($this->input->post('cart_2_no')));
				$cart_3_no			= strtoupper(trim($this->input->post('cart_3_no')));
				$cart_4_no			= strtoupper(trim($this->input->post('cart_4_no')));
				$cart_1_seal		= strtoupper(trim($this->input->post('cart_1_seal')));
				$cart_2_seal		= strtoupper(trim($this->input->post('cart_2_seal')));
				$cart_3_seal		= strtoupper(trim($this->input->post('cart_3_seal')));
				$cart_4_seal		= strtoupper(trim($this->input->post('cart_4_seal')));
				$divert				= strtoupper(trim($this->input->post('divert')));
				$bag_seal			= strtoupper(trim($this->input->post('bag_seal')));
				$bag_no				= strtoupper(trim($this->input->post('bag_no')));
				$nominal			= strtoupper(trim($this->input->post('nominal')));
				
				$data['id'] = $id;
				$data['id_cashtransit'] = $id_cashtransit;
				$data['run_number'] = $run_number;
				$data['pcs_100000'] = ($pcs_100000!=="") ? $pcs_100000 : 0;
				$data['pcs_50000'] = ($pcs_50000!=="") ? $pcs_50000 : 0;
				$data['ctr_1_no'] = ($cart_1_no!=="") ? $cart_1_no : "";
				$data['ctr_2_no'] = ($cart_2_no!=="") ? $cart_2_no : "";
				$data['ctr_3_no'] = ($cart_3_no!=="") ? $cart_3_no : "";
				$data['ctr_4_no'] = ($cart_4_no!=="") ? $cart_4_no : "";
				$data['cart_1_seal'] = ($cart_1_seal!=="") ? $cart_1_seal : "";
				$data['cart_2_seal'] = ($cart_2_seal!=="") ? $cart_2_seal : "";
				$data['cart_3_seal'] = ($cart_3_seal!=="") ? $cart_3_seal : "";
				$data['cart_4_seal'] = ($cart_4_seal!=="") ? $cart_4_seal : "";
				$data['divert'] = $divert;
				$data['bag_seal'] = $bag_seal;
				$data['bag_no'] = $bag_no;
				
				$ctr_1 = ($data['cart_1_seal']!=="") ? 1 : 0;
				$ctr_2 = ($data['cart_2_seal']!=="") ? 1 : 0;
				$ctr_3 = ($data['cart_3_seal']!=="") ? 1 : 0;
				$ctr_4 = ($data['cart_4_seal']!=="") ? 1 : 0;
				
				$data['total'] = ($ctr_1+$ctr_2+$ctr_3+$ctr_4)*((100000*$pcs_100000)+(50000*$pcs_50000));
				
				// print_r($this->input->post());
				$count = $this->db->query("SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'")->row();
				
				$update_seal = array();
				if(!empty($cart_1_seal)) { array_push($update_seal, $cart_1_seal); } 
				if(!empty($cart_2_seal)) { array_push($update_seal, $cart_2_seal); } 
				if(!empty($cart_3_seal)) { array_push($update_seal, $cart_3_seal); } 
				if(!empty($cart_4_seal)) { array_push($update_seal, $cart_4_seal); } 
				if(!empty($divert)) { array_push($update_seal, $divert); } 
				if(!empty($bag_seal)) { array_push($update_seal, $bag_seal); } 
				
				foreach($update_seal as $seal) {
					$updated['status'] = "used";
					$this->db->where('kode', $seal);
					$this->db->update('master_seal', $updated);
				}
				
				// error_reporting(0);
				// echo "<pre>";
				// print_r($update_seal);
				
				if($count->cnt==0) {
					$this->db->trans_start();
					$this->db->insert('runsheet_cashprocessing', $data);
					$this->db->trans_complete();
				} else {
					$this->db->trans_start();
					$this->db->where('id', $id);
					$this->db->update('runsheet_cashprocessing', $data);
					$this->db->trans_complete();
				}
			} else if($act=="CRM") {
				
				$id					= strtoupper(trim($this->input->post('id')));
				$id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
				$run_number			= strtoupper(trim($this->input->post('runsheet')));
				$pcs_100000			= strtoupper(trim($this->input->post('s100k')));
				$pcs_50000			= strtoupper(trim($this->input->post('s50k')));
				$cart_1_no			= strtoupper(trim($this->input->post('cart_1_no')));
				$cart_2_no			= strtoupper(trim($this->input->post('cart_2_no')));
				$cart_3_no			= strtoupper(trim($this->input->post('cart_3_no')));
				$cart_4_no			= strtoupper(trim($this->input->post('cart_4_no')));
				$cart_5_no			= strtoupper(trim($this->input->post('cart_5_no')));
				$cart_1_seal		= strtoupper(trim($this->input->post('cart_1_seal')));
				$cart_2_seal		= strtoupper(trim($this->input->post('cart_2_seal')));
				$cart_3_seal		= strtoupper(trim($this->input->post('cart_3_seal')));
				$cart_4_seal		= strtoupper(trim($this->input->post('cart_4_seal')));
				$cart_5_seal		= strtoupper(trim($this->input->post('cart_5_seal')));
				$denom_1			= strtoupper(trim($this->input->post('denom_1')));
				$value_1			= strtoupper(trim($this->input->post('value_1')));
				$denom_2			= strtoupper(trim($this->input->post('denom_2')));
				$value_2			= strtoupper(trim($this->input->post('value_2')));
				$denom_3			= strtoupper(trim($this->input->post('denom_3')));
				$value_3			= strtoupper(trim($this->input->post('value_3')));
				$denom_4			= strtoupper(trim($this->input->post('denom_4')));
				$value_4			= strtoupper(trim($this->input->post('value_4')));
				$divert				= strtoupper(trim($this->input->post('divert')));
				$bag_seal			= strtoupper(trim($this->input->post('bag_seal')));
				$bag_no				= strtoupper(trim($this->input->post('bag_no')));
				$nominal			= strtoupper(trim($this->input->post('nominal')));
				
				
				$data['id'] = $id;
				$data['id_cashtransit'] = $id_cashtransit;
				$data['run_number'] = $run_number;
				$data['pcs_100000'] = ($pcs_100000!=="") ? $pcs_100000 : 0;
				$data['pcs_50000'] = ($pcs_50000!=="") ? $pcs_50000 : 0;
				$data['ctr_1_no'] = ($cart_1_no!=="") ? $cart_1_no : "";
				$data['ctr_2_no'] = ($cart_2_no!=="") ? $cart_2_no : "";
				$data['ctr_3_no'] = ($cart_3_no!=="") ? $cart_3_no : "";
				$data['ctr_4_no'] = ($cart_4_no!=="") ? $cart_4_no : "";
				$data['ctr_5_no'] = ($cart_4_no!=="") ? $cart_5_no : "";
				$data['cart_1_seal'] = ($cart_1_seal!=="") ? $cart_1_seal.";".$denom_1.";".$value_1 : "";
				$data['cart_2_seal'] = ($cart_2_seal!=="") ? $cart_2_seal.";".$denom_2.";".$value_2 : "";
				$data['cart_3_seal'] = ($cart_3_seal!=="") ? $cart_3_seal.";".$denom_3.";".$value_3 : "";
				$data['cart_4_seal'] = ($cart_4_seal!=="") ? $cart_4_seal.";".$denom_4.";".$value_4 : "";
				$data['cart_5_seal'] = ($cart_5_seal!=="") ? $cart_5_seal : "";
				$data['divert'] = $divert;
				$data['bag_seal'] = $bag_seal;
				$data['bag_no'] = $bag_no;
				$ctr_1 = ($data['cart_1_seal']!=="") ? 1 : 0;
				$ctr_2 = ($data['cart_2_seal']!=="") ? 1 : 0;
				$ctr_3 = ($data['cart_3_seal']!=="") ? 1 : 0;
				$ctr_4 = ($data['cart_4_seal']!=="") ? 1 : 0;
				$ctr_5 = ($data['cart_5_seal']!=="") ? 1 : 0;
				$data['total'] = ($ctr_1+$ctr_2+$ctr_3+$ctr_4)*((100000*$pcs_100000)+(50000*$pcs_50000));
				
				// print_r($_REQUEST);
				// print_r($data);
				
				$count = $this->db->query("SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'")->row();
				
				if($count->cnt==0) {
					$this->db->trans_start();
					$this->db->insert('runsheet_cashprocessing', $data);
					$this->db->trans_complete();
				} else {
					$this->db->trans_start();
					$this->db->where('id', $id);
					$this->db->update('runsheet_cashprocessing', $data);
					$this->db->trans_complete();
				}
			}
			
			$row = $this->db->query("select *,
				cashtransit_detail.id as id_ct, 
				cashtransit_detail.id_cashtransit, 
				cashtransit_detail.id_bank, 
				cashtransit_detail.state as state,
				cashtransit_detail.ctr as ctr2, 
				cashtransit_detail.pcs_100000 as pcs_100000, 
				cashtransit_detail.pcs_50000 as pcs_50000, 
				cashtransit_detail.pcs_20000 as pcs_20000, 
				cashtransit_detail.pcs_10000 as pcs_10000, 
				cashtransit_detail.pcs_5000 as pcs_5000, 
				cashtransit_detail.pcs_2000 as pcs_2000, 
				cashtransit_detail.pcs_1000 as pcs_1000, 
				cashtransit_detail.pcs_coin as pcs_coin, 
				cashtransit_detail.total as total, 
				client.cabang as branch,
				client.bank,
				client.lokasi,
				client.sektor,
				cashtransit_detail.jenis,
				client.denom,
				client.vendor,
				client.type_mesin,
				client.ctr as ctr,
				runsheet_cashprocessing.pcs_100000 as s100k,
				runsheet_cashprocessing.pcs_50000 as s50k,
				runsheet_cashprocessing.pcs_20000 as s20k,
				runsheet_cashprocessing.pcs_10000 as s10k,
				runsheet_cashprocessing.pcs_5000 as s5k,
				runsheet_cashprocessing.pcs_2000 as s2k,
				runsheet_cashprocessing.pcs_1000 as s1k,
				runsheet_cashprocessing.pcs_coin as coin,
				runsheet_cashprocessing.total as nominal,
				runsheet_cashprocessing.bag_seal,
				runsheet_cashprocessing.bag_no
			from cashtransit_detail 
				LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
				LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
				LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
				WHERE runsheet_cashprocessing.id='".$id."'")->row();

			echo json_encode(array(
				'id' => $row->id_ct,
				'id_cashtransit' => $row->id_cashtransit,
				'id_bank' => $row->id_bank,
				'state' => $row->state,
				'branch' => $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name,
				'bank' => $row->bank,
				'lokasi' => $row->lokasi,
				'runsheet' => $row->sektor,
				'jenis' => $row->jenis,
				'denom' => $row->denom,
				'brand' => $row->vendor,
				'model' => $row->type_mesin,
				'pcs_100000' => $row->pcs_100000,
				'pcs_50000' => $row->pcs_50000,
				'pcs_20000' => $row->pcs_20000,
				'pcs_10000' => $row->pcs_10000,
				'pcs_5000' => $row->pcs_5000,
				'pcs_2000' => $row->pcs_2000,
				'pcs_1000' => $row->pcs_1000,
				'pcs_coin' => $row->pcs_coin,
				's100k' => $row->s100k,
				's50k' => $row->s50k,
				's20k' => $row->s20k,
				's10k' => $row->s10k,
				's5k' => $row->s5k,
				's2k' => $row->s2k,
				's1k' => $row->s1k,
				'coin' => $row->coin,
				'ctr' => $row->ctr,
				'total' => $row->total,
				'nominal' => (100000*$row->s100k)+(50000*$row->s50k)+(20000*$row->s20k)+(10000*$row->s10k)+(5000*$row->s5k)+(2000*$row->s2k)+(1000*$row->s1k)+(1*$row->coin),
				'bag_seal' => $row->bag_seal,
				'bag_no' => $row->bag_no
			));
		}
	}
	
	function delete_data() {
		$id = $_POST['id'];

		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('runsheet_cashprocessing');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
	
	public function check_seal_atmcrmX() {
		$kode = $this->input->post('value');
		$type = $this->input->post('type');
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)));
		if($count->cnt==0) {
			echo -1;
		} else {
			$count1 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE LOWER(kode) = BINARY(kode) AND kode='$kode' AND status='used'"), array(CURLOPT_BUFFERSIZE => 10)));
			
			$count2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
				SELECT C.cart_1_seal, C.cart_2_seal, C.cart_3_seal, C.cart_4_seal, C.cart_5_seal, C.divert, count(*) as cnt 
				FROM cashtransit_detail AS A LEFT JOIN runsheet_cashprocessing AS C ON(C.id=A.id) 
				WHERE
					LOWER(C.cart_1_seal) LIKE '".$kode."' OR
					LOWER(C.cart_2_seal) LIKE '".$kode."' OR
					LOWER(C.cart_3_seal) LIKE '".$kode."' OR
					LOWER(C.cart_4_seal) LIKE '".$kode."' OR
					LOWER(C.cart_5_seal) LIKE '".$kode."' OR
					LOWER(C.divert) LIKE '".$kode."'
			"), array(CURLOPT_BUFFERSIZE => 10)));
			
			$count = $count1->cnt + ($count2->cnt);
			
			if($count==0) {
				$cpc_prepare = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
					SELECT * FROM cpc_prepared WHERE seal='".$kode."'
				"), array(CURLOPT_BUFFERSIZE => 10)));
				
				// $num2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
					// SELECT count(*) as cnt FROM cpc_prepared WHERE seal='".$kode."'
				// "), array(CURLOPT_BUFFERSIZE => 10)));
				
				
				// if($num2->cnt==0) {
					// echo 1;
				// } else {
					if($type!==$cpc_prepare->type) {
						echo 2;
					} else {
						echo 1;
					}
				// }
			} else {
				echo 0;
			}
		}
	}
	
	public function check_seal() {
		$kode = $this->input->post('value');
		$id_bank = $this->input->post('id_bank');
		$divert = $this->input->post('divert');
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		if($divert==true) {
			echo 1;
		} else {
			if($count->cnt==0) {
				echo -1;
			} else {
				$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE kode='$kode' AND status!='used'"), array(CURLOPT_BUFFERSIZE => 10)));
				
				if($count->cnt==0) {
					echo 0;
				} else {
					$type_cassette = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
						SELECT type_cassette FROM cpc_prepared WHERE seal='".$kode."'
					"), array(CURLOPT_BUFFERSIZE => 10)))->type_cassette;
					
					$type_mesin = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
						SELECT type_mesin FROM client WHERE id='".$id_bank."'
					"), array(CURLOPT_BUFFERSIZE => 10)))->type_mesin;
					
					$type = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
						SELECT type FROM client WHERE id='".$id_bank."'
					"), array(CURLOPT_BUFFERSIZE => 10)))->type;
					
					// echo $prep." ".$type;
					if($type!="CDM") {
						if($type_mesin!=$type_cassette) {
							echo "INVALID\n";
							echo "TYPE MESIN ATM  : \t".$type_mesin."\n";
							echo "TYPE PREPARED  : \t".$type_cassette."\n";
						} else {
							echo 1;
						}
					} else {
						echo 1;
					}
				}
			}
		}
	}
	
	public function check_seal_atmcrm() {
		$kode = $this->input->post('value');
		$type = $this->input->post('type');
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE LOWER(kode) = BINARY(kode) AND kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)));
		if($count->cnt==0) {
			echo "DATA TIDAK ADA DI MASTER SEAL\n";
		} else {
			// echo "DATA ADA DI MASTER SEAL<br>";
			
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE LOWER(kode) = BINARY(kode) AND kode='$kode' AND status!='used'"), array(CURLOPT_BUFFERSIZE => 10)));
			
			if($count->cnt==0) {
				echo "DATA USED DI MASTER SEAL\n";
			} else {
				// echo "DATA AVAILABLE DI MASTER SEAL<br>";
				
				$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
					SELECT C.cart_1_seal, C.cart_2_seal, C.cart_3_seal, C.cart_4_seal, C.cart_5_seal, C.divert, count(*) as cnt 
					FROM cashtransit_detail AS A LEFT JOIN runsheet_cashprocessing AS C ON(C.id=A.id) 
					WHERE
						LOWER(C.cart_1_seal) LIKE '".$kode."' OR
						LOWER(C.cart_2_seal) LIKE '".$kode."' OR
						LOWER(C.cart_3_seal) LIKE '".$kode."' OR
						LOWER(C.cart_4_seal) LIKE '".$kode."' OR
						LOWER(C.cart_5_seal) LIKE '".$kode."' OR
						LOWER(C.divert) LIKE '".$kode."'
				"), array(CURLOPT_BUFFERSIZE => 10)));
				
				if($count->cnt!=0) {
					echo "DATA ADA DI CASHPROCESSING\n";
				} else {
					// echo "DATA TIDAK ADA DI CASHPROCESSING<br>";
					
					$cpc_prepare = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
						SELECT * FROM cpc_prepared WHERE seal='".$kode."'
					"), array(CURLOPT_BUFFERSIZE => 10)));
					
					if($type!==$cpc_prepare->type) {
						echo "TYPE TIDAK SAMA";
					} else {
						// echo "TYPE SAMA";
					}
				}
			}
		}
	}
	
	public function tes_check($kode, $type) {
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE LOWER(kode) = BINARY(kode) AND kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)));
		if($count->cnt==0) {
			echo "DATA TIDAK ADA DI MASTER SEAL<br>";
		} else {
			// echo "DATA ADA DI MASTER SEAL<br>";
			
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE LOWER(kode) = BINARY(kode) AND kode='$kode' AND status!='used'"), array(CURLOPT_BUFFERSIZE => 10)));
			
			if($count->cnt==0) {
				echo "DATA USED DI MASTER SEAL<br>";
			} else {
				// echo "DATA AVAILABLE DI MASTER SEAL<br>";
				
				$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
					SELECT C.cart_1_seal, C.cart_2_seal, C.cart_3_seal, C.cart_4_seal, C.cart_5_seal, C.divert, count(*) as cnt 
					FROM cashtransit_detail AS A LEFT JOIN runsheet_cashprocessing AS C ON(C.id=A.id) 
					WHERE
						LOWER(C.cart_1_seal) LIKE '".$kode."' OR
						LOWER(C.cart_2_seal) LIKE '".$kode."' OR
						LOWER(C.cart_3_seal) LIKE '".$kode."' OR
						LOWER(C.cart_4_seal) LIKE '".$kode."' OR
						LOWER(C.cart_5_seal) LIKE '".$kode."' OR
						LOWER(C.divert) LIKE '".$kode."'
				"), array(CURLOPT_BUFFERSIZE => 10)));
				
				if($count->cnt!=0) {
					echo "DATA ADA DI CASHPROCESSING<br>";
				} else {
					// echo "DATA TIDAK ADA DI CASHPROCESSING<br>";
					
					$cpc_prepare = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
						SELECT * FROM cpc_prepared WHERE seal='".$kode."'
					"), array(CURLOPT_BUFFERSIZE => 10)));
					
					if($type!==$cpc_prepare->type) {
						echo "TYPE TIDAK SAMA";
					} else {
						echo "TYPE SAMA";
					}
				}
			}
		}
	}
	
	public function check_bag() {
		$kode = $this->input->post('value');
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_bag WHERE kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)));
		if($count->cnt==0) {
			echo -1;
		} else {
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_bag WHERE kode='$kode' AND status!='used'"), array(CURLOPT_BUFFERSIZE => 10)));
			
			if($count->cnt==0) {
				echo 0;
			} else {
				echo 1;
			}
		}
	}
	
	public function check_big_seal() {
		$kode = $this->input->post('value');
		$id = $this->input->post('id');
		$id_bank = $this->input->post('id_bank');
		$ctr = $this->input->post('ctr');
		$act = $this->input->post('act');
		
		
		$ctr = substr($kode, 4, 1);
		// echo $id_bank."<br>";
		// echo $ctr;
		$sql_prev = "SELECT 
						*
							FROM
								(SELECT `id`, `id_cashtransit`, `id_bank`, `id_pengirim`, `id_penerima`, `no_boc`, `state`, `metode`, `jenis`, `denom`, `pcs_100000`, `pcs_50000`, `pcs_20000`, `pcs_10000`, `pcs_5000`, `pcs_2000`, `pcs_1000`, `pcs_coin`, `detail_uang`, `ctr`, `divert`, `total`, `date`, `updated_date` FROM cashtransit_detail) AS cashtransit_detail
									LEFT JOIN
										runsheet_cashprocessing
											ON(runsheet_cashprocessing.id=cashtransit_detail.id)
												WHERE 
													cashtransit_detail.id_bank='$id_bank' AND
													cashtransit_detail.id = (SELECT MAX(id) FROM cashtransit_detail WHERE id < $id AND id_bank='$id_bank')";
													
		$prev = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql_prev), array(CURLOPT_BUFFERSIZE => 10)));
		
		if($act=="ATM") {
			// $prev->ctr = 4;
			if($ctr>$prev->ctr) {
				$ket = "NAIK LIMIT";
				$info = "";
				$saran = "";
			} else if($ctr<$prev->ctr) {
				$ket = "TURUN LIMIT";
				$info = "PENGISIAN SEBELUMNYA ".$prev->ctr." CARTRIDGE";
				$saran = "DISARANKAN MEMBAWA BAG ISI ".$prev->ctr." CARTRIDGE";
			} else {
				$ket = "LIMIT NORMAL";
				$info = "";
				$saran = "";
			}
			
			// echo "<p style='margin-bottom: -5px; color: #e87333'>";
			// echo $ket."<br>";
			// echo $info."<br>";
			// echo $saran;
			// echo "</p>";
		}
		
		echo $saran;
		
		// $count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)));
		// if($count->cnt==0) {
			// echo -1;
		// } else {
			// $count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE kode='$kode' AND status!='used'"), array(CURLOPT_BUFFERSIZE => 10)));
			
			// if($count->cnt==0) {
				// echo 0;
			// } else {
				// echo 1;
			// }
		// }
	}
	
	public function delete_seal() {
		$kode = $this->input->post('kode');
		$jenis = $this->input->post('jenis');
		
		$sql = "DELETE FROM `master_seal` WHERE `kode`='$kode'";
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		$row = json_decode($this->curl->simple_get(rest_api().'/master_seal/index2?kode='.$kode))[0];
		
		if (!$res) {
			echo "failed";
		} else {
			echo "success";
		}
	}
	
	public function update_all_seal() {
		$kode = $this->input->post('data');
		$status = $this->input->post('status');
		
		$data = json_decode($kode, true);
		// print_r($data);
		$where = array();
		$where2 = array();
		foreach($data as $r) {
			// echo $r['item_kode']."\n";
			$where[] = "kode='".$r['item_kode']."'";
			$where2[] = "'".$r['item_kode']."'";
		}
		
		$sql = "UPDATE `master_seal` SET `status`='$status' WHERE kode IN (".implode(', ', $where2).")";
		json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		$query = "SELECT * FROM master_seal WHERE kode IN (".implode(', ', $where2).")";
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		echo json_encode($res);
	}
	
	public function update_seal() {
		$kode = $this->input->post('kode');
		$jenis = $this->input->post('jenis');
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE kode='$kode'"), array(CURLOPT_BUFFERSIZE => 10)));
		if($count->cnt==0) {
			// echo -1;
			$data['kode'] = $kode;
			$data['jenis'] = $jenis;
			$data['status'] = "available";
			$table = "master_seal";
			$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			$row = json_decode($this->curl->simple_get(rest_api().'/master_seal/index2?kode='.$kode))[0];
				
			echo json_encode($row);
		} else {
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE kode='$kode' AND status!='used'"), array(CURLOPT_BUFFERSIZE => 10)));
			
			if($count->cnt==0) {
				// echo 0;
				$sql = "UPDATE `master_seal` SET `status`='available' WHERE `kode`='$kode'";
				$res = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
				$row = json_decode($this->curl->simple_get(rest_api().'/master_seal/index2?kode='.$kode))[0];
				
				echo json_encode($row);
			} else {
				// echo 1;
				$sql = "UPDATE `master_seal` SET `status`='used' WHERE `kode`='$kode'";
				$res = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
				$row = json_decode($this->curl->simple_get(rest_api().'/master_seal/index2?kode='.$kode))[0];
				
				echo json_encode($row);
			}
		}
	}
	
	function notification2() {
		define('AUTHORIZATION_KEY', 'AAAAnLcRlWk:APA91bF0J5GW72L1GeWozjX8LNSSq5usc8twk0mUL53izRdPK9HiVz_yZziH7XUq_rXizKFk_bEv26FeQaRFe3YY5sPIpeTaV23CA1-s2kfPFYhdIp4ZzyaviG3Iv59uJW-pHb6-pZSI');

		$_REQUEST['to'] = "cKPPKjo1M2w:APA91bF30tvgdDvVVClN9t0a4yigFz_oAGJb8W_LQnVUjjYfkqJShBM3uEFzWLt6qVxe8l7L8ZdEkOho2YPGI8M6-VlfXrxiUs4JmhtAORYf6si-FHzIcFHoXC_l8vBqClEdBXrZODU9";

		// $fields = array(
			// 'to' => $_REQUEST['to'],
			// 'data' => array(
				// "notification_body" => "Pekerjaan Cash Replenish",
				// "notification_title"=> "CIT Notification",
				// "notification_foreground"=> "true",
				// "notification_android_channel_id"=> "my_channel_id",
				// "notification_android_priority"=> "2",
				// "notification_android_visibility"=> "1",
				// "notification_android_color"=> "#ff0000",
				// "notification_android_icon"=> "thumbs_up",
				// "notification_android_sound"=> "blackberry",
				// "notification_android_vibrate"=> "500, 200, 500",
				// "notification_android_lights"=> "#ffff0000, 250, 250",
				// "key_1" => "Data for key one"
			// )
		// );
		$fields = array(
			'to' => $_REQUEST['to'],
			'data' => array(
				'notificationOptions' => array(
					'text' => 'Pekerjaan FLM',
					// 'summary' => "4 messages",
					// 'textLines' => array("Message 1", "Message 2", "Message 3", "Message 4"),
					'title' => 'Info',
					// 'smallIcon' => 'mipmap/icon',
					// 'largeIcon' => 'https://avatars2.githubusercontent.com/u/1174345?v=3&s=96',
					// 'bigPicture' => "https://cloud.githubusercontent.com/assets/7321362/24875178/1e58d2ec-1ddc-11e7-96ed-ed8bf011146c.png",
					'vibrate' => [100,500,100,500],
					// 'sound' => true,
					// 'sound' => 'default',
					// 'sound' => 'http://asg.angkasapura1.co.id/mysound.mp3',
					// 'sound' => 'https://lagu123.mobi/play/karna-su-sayang~lagu123.MOBI~515011656.mp3',
					// 'sound' => "http://tindeck.com/download/pro/yjuow/Not_That_Guy.mp3",
					// 'sound' => "http://192.168.1.5/Fearless.mp3",
					'sound' => "http://192.168.1.102/Alarm.mp3",
					// 'sound' => "res://raw/lost_european_the_beginning_of_the_end_mp3", // Downloaded from http://www.freemusicpublicdomain.com
					// 'color' => '000000ff',
					// 'color' => '0000ff',
					'color' => 0x000000ff,
					'autoCancel' => true,
					// 'openApp' => true,
					'priority' => 'high'
				)
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

	function notification($token) {
		define('AUTHORIZATION_KEY', 'AAAAnLcRlWk:APA91bF0J5GW72L1GeWozjX8LNSSq5usc8twk0mUL53izRdPK9HiVz_yZziH7XUq_rXizKFk_bEv26FeQaRFe3YY5sPIpeTaV23CA1-s2kfPFYhdIp4ZzyaviG3Iv59uJW-pHb6-pZSI');

		$_REQUEST['to'] = $token;

		$title = "Informasi";
		$body = "Pekerjaan pengisian/replenishment telah didapatkan, silahkan cek kembali aplikasi anda";

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