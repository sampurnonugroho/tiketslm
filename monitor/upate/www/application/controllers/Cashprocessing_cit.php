<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cashprocessing_cit extends CI_Controller {
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
		
        $this->data['data_cashprocessing'] = json_decode($this->curl->simple_get(rest_api().'/Run_cashprocessing'));
		
        return view('admin/cashprocessing/index', $this->data);
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
		header('Content-Type: application/json');
		
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		
		$result = $this->curl->simple_post(rest_api().'/run_cashprocessing_cit/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $result;
	}
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['id'] = $this->input->get('id');
		$this->data['id_ct'] = $this->input->get('id_ct');
		$this->data['index'] = $this->input->get('index');
		$this->data['state'] = $this->input->get('state');
		$this->data['row'] = json_decode($this->input->get('row'));
		
		
		if($session->userdata['level']=="LEVEL1") {
			return view('admin/cashprocessing_cit/show_form', $this->data);
		}
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
		$petty_cash				= strtoupper(trim($this->input->post('petty_cash')));
		
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

		// echo $state;
		if($state=="ro_cit") {
			$this->update_cit();
		}
		
		$query = "SELECT * FROM runsheet_operational LEFT JOIN user ON(runsheet_operational.custodian_1=user.username) WHERE runsheet_operational.run_number='".$this->input->post('runsheet')."'";
		$token = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->token;
		
		$this->notification($token);
	}

	function update_cit() {
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
		$total				= strtoupper(trim($this->input->post('total')));

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
		
		$data['id'] = $id;
		$data['id_cashtransit'] = $id_cashtransit;
		$data['run_number'] = $run_number;
		$data['pcs_100000'] = ($pcs_100000!=="") ? $pcs_100000 : 0;
		$data['pcs_50000'] = ($pcs_50000!=="") ? $pcs_50000 : 0;
		$data['detail_uang'] = ($detail_uang!=="") ? json_encode($detail_uang) : 0;
		$data['ctr_1_no'] = ($cart_1_no!=="") ? $cart_1_no : "";
		$data['ctr_2_no'] = ($cart_2_no!=="") ? $cart_2_no : "";
		$data['ctr_3_no'] = ($cart_3_no!=="") ? $cart_3_no : "";
		$data['ctr_4_no'] = ($cart_4_no!=="") ? $cart_4_no : "";
		$data['cart_1_seal'] = ($cart_1_seal!=="") ? $cart_1_seal : "";
		$data['cart_2_seal'] = ($cart_2_seal!=="") ? $cart_2_seal : "";
		$data['cart_3_seal'] = ($cart_3_seal!=="") ? $cart_3_seal : "";
		$data['cart_4_seal'] = ($cart_4_seal!=="") ? $cart_4_seal : "";
		$data['divert'] = $divert;
		$data['total'] = $total;
		$data['bag_seal'] = $bag_seal;
		$data['bag_no'] = $bag_no;

		$query = "SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'";
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
	
		$update_seal = array();
		if(!empty($bag_seal)) { array_push($update_seal, $bag_seal); } 

		foreach($update_seal as $seal) {
			$table = "master_seal";
			$where = $seal;
			$updated['status'] = "used";
			$res = $this->curl->simple_get(rest_api().'/select/update_seal', array('table'=>$table, 'where'=>$where, 'data'=>$updated), array(CURLOPT_BUFFERSIZE => 10));
		}

		if($count->cnt==0) {
			$table = "runsheet_cashprocessing";
			$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		} else {
			$table = "runsheet_cashprocessing";
			$res = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		}

		// print_r($this->db->last_query());

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
			from cashtransit_detail 
				LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
				LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
				LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
				WHERE runsheet_cashprocessing.id='".$id."'";
				
		$query = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$querys), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			$detailuang = json_decode($row->detail_uang, true);
			
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['state'] = $row->state;
			// $items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
			$items[$i]['metode'] = ($row->metode=="CP" ? "CASH PICKUP" : ($row->metode=="CD" ? "CASH DELIVERY" : ""));
			$items[$i]['jenis'] = $row->jenis;
			$items[$i]['lokasi'] = $row->lokasi;
			if($row->id_pengirim!=0){
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_pengirim."'")->row()->sektor;
			} else {
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_penerima."'")->row()->sektor;
			}
			$items[$i]['pengirim'] = ($row->id_pengirim==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_pengirim."'")->row()->nama_client);
			$items[$i]['penerima'] = ($row->id_penerima==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_penerima."'")->row()->nama_client);
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
			$items[$i]['detail_uang'] = $row->detail_uang;
			$items[$i]['kertas_100k'] = $detailuang['kertas_100k'];
			$items[$i]['kertas_50k'] = $detailuang['kertas_50k'];
			$items[$i]['kertas_20k'] = $detailuang['kertas_20k'];
			$items[$i]['kertas_10k'] = $detailuang['kertas_10k'];
			$items[$i]['kertas_5k'] = $detailuang['kertas_5k'];
			$items[$i]['kertas_2k'] = $detailuang['kertas_2k'];
			$items[$i]['kertas_1k'] = $detailuang['kertas_1k'];
			$items[$i]['logam_1k'] = $detailuang['logam_1k'];
			$items[$i]['logam_500'] = $detailuang['logam_500'];
			$items[$i]['logam_200'] = $detailuang['logam_200'];
			$items[$i]['logam_100'] = $detailuang['logam_100'];
			$items[$i]['logam_50'] = $detailuang['logam_50'];
			$items[$i]['total'] = $row->total;
			$items[$i]['bag_seal'] = $row->bag_seal;
			$items[$i]['bag_no'] = $row->bag_no;
			$i++;
		}

		echo json_encode($items[0]);
	}
	
	function update_atm() {
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
			
			$query = "SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'";
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			$update_seal = array();
			if(!empty($cart_1_seal)) { array_push($update_seal, $cart_1_seal); } 
			if(!empty($cart_2_seal)) { array_push($update_seal, $cart_2_seal); } 
			if(!empty($cart_3_seal)) { array_push($update_seal, $cart_3_seal); } 
			if(!empty($cart_4_seal)) { array_push($update_seal, $cart_4_seal); } 
			if(!empty($divert)) { array_push($update_seal, $divert); } 
			if(!empty($bag_seal)) { array_push($update_seal, $bag_seal); } 
			
			foreach($update_seal as $seal) {
				// $updated['status'] = "used";
				// $this->db->where('kode', $seal);
				// $this->db->update('master_seal', $updated);
				$table = "master_seal";
				$where = $seal;
				$updated['status'] = "used";
				$res = $this->curl->simple_get(rest_api().'/select/update_seal', array('table'=>$table, 'where'=>$where, 'data'=>$updated), array(CURLOPT_BUFFERSIZE => 10));
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
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
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
			
			$query = "SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'";
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			if($count->cnt==0) {
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			} else {
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			}
		} else if($act=="CDM") {
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
			$data['cart_1_seal'] = ($cart_1_seal!=="") ? $cart_1_seal : "";
			$data['cart_2_seal'] = ($cart_2_seal!=="") ? $cart_2_seal : "";
			$data['cart_3_seal'] = ($cart_3_seal!=="") ? $cart_3_seal : "";
			$data['cart_4_seal'] = ($cart_4_seal!=="") ? $cart_4_seal : "";
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
			
			$query = "SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'";
			$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			if($count->cnt==0) {
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			} else {
				$table = "runsheet_cashprocessing";
				$res = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			}

		}
		
		$querys = "select *,
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
				WHERE runsheet_cashprocessing.id='".$id."'";
				
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$querys), array(CURLOPT_BUFFERSIZE => 10)));
		
		echo json_encode(array(
			'id' => $row->id_ct,
			'id_cashtransit' => $row->id_cashtransit,
			'id_bank' => $row->id_bank,
			'state' => $row->state,
			'branch' => json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT name FROM master_branch where id='".$row->branch."'"), array(CURLOPT_BUFFERSIZE => 10)))->name,
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
	
	public function check_seal() {
		$kode = $this->input->post('value');
		
		$count = $this->db->query("SELECT count(*) as cnt FROM master_seal WHERE kode='$kode'")->row();
		echo $count->cnt;
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
	
	
	public function suggest_seal_1() {
		$search = $this->input->post('search');
		
		$sql = "SELECT * FROM master_seal WHERE jenis='paper' AND status='available'";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// print_r($result->result());
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->kode;
				$list[$key]['text'] = $row->kode; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
}