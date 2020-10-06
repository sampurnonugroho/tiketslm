<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cashprocessing_return extends CI_Controller {
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

			// $this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			// $this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			// $this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			// $this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
	}
	
	public function index() {
		$this->data['active_menu'] = "cashprocessing_return";
		
		// $query = "select *, cashtransit.id as id_ct, IFNULL((SELECT COUNT(DISTINCT client.sektor) FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.id_cashtransit=cashtransit.id AND cashtransit_detail.data_solve!='' AND client.sektor IN (SELECT run_number FROM runsheet_cashprocessing WHERE id_cashtransit=cashtransit.id) GROUP BY cashtransit_detail.id_cashtransit), 0) as count FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) ORDER BY cashtransit.id DESC";
		$query = "SELECT *, 
						cashtransit.id as id_ct, 
						IFNULL((SELECT COUNT(DISTINCT cashtransit_detail.id) FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.id_cashtransit=cashtransit.id AND cashtransit_detail.data_solve!='' GROUP BY cashtransit_detail.id_cashtransit), 0) as count FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) ORDER BY cashtransit.id DESC";
		$query = "	SELECT *, cashtransit.id as id_ct, 
					IFNULL((SELECT COUNT(DISTINCT cashtransit_detail.id) FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.id_cashtransit=cashtransit.id AND cashtransit_detail.data_solve!='' AND cashtransit_detail.cpc_process='' GROUP BY cashtransit_detail.id_cashtransit), 0) as count 
					FROM cashtransit 
					LEFT JOIN cashtransit_detail ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
					LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) 
					WHERE cashtransit_detail.data_solve!='batal'
					ORDER BY cashtransit.id DESC";
		
		
        $this->data['data_cashprocessing'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($this->data['data_cashprocessing']);
		// echo "</pre>";
		
        return view('admin/cashprocessing_return/index', $this->data);
	}
	
	public function add() {
        $this->data['active_menu'] = "cashprocessing_return";
		$this->data['url'] = "cashprocessing/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$branch = $this->db->query("SELECT branch FROM cashtransit WHERE id='$id'")->row()->branch;
		$branch = $this->db->query("SELECT name FROM master_branch WHERE id='$branch'")->row()->name;
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/cashprocessing_return/form', $this->data);
	}
	
	public function edit() {
		$this->data['active_menu'] = "cashprocessing_return";
		$this->data['url'] = "cashprocessing/save";
		$this->data['flag'] = "edit";
		
		$id = $this->uri->segment(3);
		
		$query = "SELECT name FROM master_branch WHERE id IN (SELECT branch FROM cashtransit WHERE id='$id')";
		$branch = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->name;
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/cashprocessing_return/form', $this->data);
	}
	
	function delete() {
		
	}
	
	public function get_data() {
		// header('Content-Type: application/json');
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		
		$result = $this->curl->simple_post(rest_api().'/cpc_return/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $result;
	}
	
	public function get_data_cit() {
		// header('Content-Type: application/json');
		$id = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		
		$result = $this->curl->simple_post(rest_api().'/cpc_return/get_data_cit',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $result;
	}
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['id'] = $this->input->get('id');
		$this->data['id_ct'] = $this->input->get('id_ct');
		$this->data['index'] = $this->input->get('index');
		$this->data['state'] = $this->input->get('state');
		$this->data['act'] = $this->input->get('act');
		$this->data['row'] = json_decode($this->input->get('row'));
		
		// if($session->userdata['level']=="LEVEL1") {
			if($this->input->get('act')=="ATM") {
				return view('admin/cashprocessing_return/show_form_atm', $this->data);
			} else if($this->input->get('act')=="CRM") {
				return view('admin/cashprocessing_return/show_form_crm', $this->data);
			} else if($this->input->get('act')=="CDM") {
				return view('admin/cashprocessing_return/show_form_cdm', $this->data);
			}
		// }
	}
	
	public function show_form2() {
		$this->data['flag'] = "show_form";
		$this->data['id'] = $this->input->get('id');
		$this->data['id_ct'] = $this->input->get('id_ct');
		$this->data['index'] = $this->input->get('index');
		$this->data['state'] = $this->input->get('state');
		$this->data['act'] = $this->input->get('act');
		$this->data['row'] = json_decode($this->input->get('row'));
		
		return view('admin/cashprocessing_return/show_form', $this->data);
		
		// if($this->input->get('act')=="ATM") {
			// return view('admin/cashprocessing_return/show_form_atm', $this->data);
		// } else if($this->input->get('act')=="CRM") {
			// return view('admin/cashprocessing_return/show_form_crm', $this->data);
		// } else if($this->input->get('act')=="CDM") {
			// return view('admin/cashprocessing_return/show_form_cdm', $this->data);
		// }
	}
	
	public function get_data_kasir() {
		$query = "SELECT * FROM karyawan LEFT JOIN jabatan ON(karyawan.id_jabatan=jabatan.id_jabatan) WHERE jabatan.nama_jabatan='CASHIER'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->nik;
				$list[$key]['text'] = $row->nama; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	// public function suggest() {
		// $search = $this->input->post('search');
		// $id_cashtransit = $this->input->post('id_cashtransit');
		
		// $sql = "SELECT * FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN master_zone ON(client.sektor=master_zone.id) WHERE cashtransit_detail.id_cashtransit='$id_cashtransit' AND client.sektor NOT IN (SELECT run_number FROM runsheet_cashprocessing WHERE id_cashtransit='$id_cashtransit') GROUP BY client.sektor";
		// // echo $sql;
		// $result = $this->db->query($sql);
		// // print_r($result->result());
		
		// $list = array();
		// if ($result->num_rows() > 0) {
			// $key=0;
			// foreach ($result->result() as $row) {
				// $list[$key]['id'] = $row->sektor;
				// $list[$key]['text'] = "(".$row->sektor.") ".$row->name; 
				// $key++;
			// }
			// echo json_encode($list);
		// } else {
			// echo json_encode($list);
		// }
	// }
	
	// function save_data() {
		// // print_r($this->input->post());
		
		// $id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
		// $run_number			= strtoupper(trim($this->input->post('run_number')));
		// $petty_cash				= strtoupper(trim($this->input->post('petty_cash')));
		
		// $data['id_cashtransit'] = $id_cashtransit;
		// $data['run_number'] = $run_number;
		// $data['petty_cash'] = $petty_cash;
		
		// $this->db->trans_start();

		// $this->db->insert('runsheet_cashprocessing', $data);

		// $this->db->trans_complete();
		

		// echo json_encode(array(
			// 'id_cashtransit' => $id_cashtransit,
			// 'run_number' => $run_number,
			// 'petty_cash' => $petty_cash
		// ));
	// }
	
	function update_data_cit() {
		$id = $this->input->get("id");
		
		$query = "SELECT data_solve FROM cashtransit_detail WHERE id='$id'";
		$data_solve = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->data_solve;
		
		$data = json_decode($data_solve);
		
		// echo "<pre>";
		// print_r($_REQUEST);
		// print_r($data);
		
		$data_save = array(
			// "cashier"			=> $this->input->post('cashier'),
			// "nomeja"			=> $this->input->post('nomeja'),
			// "jamproses"			=> $this->input->post('jamproses'),
			"kertas_100ks"	=> $this->input->post('kertas_100ks'),
			"kertas_50ks"	=> $this->input->post('kertas_50ks'),
			"kertas_20ks"	=> $this->input->post('kertas_20ks'),
			"kertas_10ks"	=> $this->input->post('kertas_10ks'),
			"kertas_5ks"	=> $this->input->post('kertas_5ks'),
			"kertas_2ks"	=> $this->input->post('kertas_2ks'),
			"kertas_1ks"	=> $this->input->post('kertas_1ks'),
			"logam_1ks"		=> $this->input->post('logam_1ks'),
			"logam_500s"	=> $this->input->post('logam_500s'),
			"logam_200s"	=> $this->input->post('logam_200s'),
			"logam_100s"	=> $this->input->post('logam_100s'),
			"kertas_100k"	=> $data->kertas_100k,
			"kertas_50k"	=> $data->kertas_50k,
			"kertas_20k"	=> $data->kertas_20k,
			"kertas_10k"	=> $data->kertas_10k,
			"kertas_5k"		=> $data->kertas_5k,
			"kertas_2k"		=> $data->kertas_2k,
			"kertas_1k"		=> $data->kertas_1k,
			"logam_1k"		=> $data->logam_1k,
			"logam_500"		=> $data->logam_500,
			"logam_200"		=> $data->logam_200,
			"logam_100"		=> $data->logam_100,
			"logam_50"		=> $data->logam_50,
			"bag_seal"		=> $data->bag_seal,
			"bag_no"		=> $data->bag_no,
			"cashier"		=>$this->input->post('cashier'),
			"meja"			=>$this->input->post('nomeja'),
			"jam_proses"	=>$this->input->post('jamproses')
		);
		
		$data_simpan['id'] = $id;
		$data_simpan['cpc_process'] = json_encode($data_save);
		
		$table = "cashtransit_detail";
		$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data_simpan), array(CURLOPT_BUFFERSIZE => 10));
		
		echo json_encode(array());
	}
	
	function update_data() {
		$id = $this->input->get("id");
		
		$query = "SELECT data_solve FROM cashtransit_detail WHERE id='$id'";
		$data_solve = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->data_solve;
		
		$data = json_decode($data_solve);
		
		$state				= trim($this->input->post('state'));
		$act				= trim($this->input->post('act'));
		
		if($state=="ro_atm") {
			if($act=="ATM") {
				$data_jurnal['id_detail'] = $id;
				$data_jurnal['tanggal'] = date("Y-m-d");
				$data_jurnal['keterangan'] = "return";
				$data_jurnal['posisi'] = "debit";
				
				$debit_100 = (json_decode($data_solve)->s100k!="" ? 
					intval($this->input->post('cart_1_val')) +
					intval($this->input->post('cart_2_val')) +
					intval($this->input->post('cart_3_val')) +
					intval($this->input->post('cart_4_val')) +
					intval($this->input->post('div_val'))
				: 0);
				$data_jurnal['debit_100'] = $debit_100*100000;
				
				$debit_50 = (json_decode($data_solve)->s50k!="" ? 
					intval($this->input->post('cart_1_val')) +
					intval($this->input->post('cart_2_val')) +
					intval($this->input->post('cart_3_val')) +
					intval($this->input->post('cart_4_val')) +
					intval($this->input->post('div_val'))
				: 0);
				$data_jurnal['debit_50'] = $debit_50*50000;
				
				
				$table = "jurnal";
				$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_jurnal), array(CURLOPT_BUFFERSIZE => 10));
				// print_r($data_jurnal);
				
				$this->curl->simple_get(rest_api().'/select/query2', array('query'=>"
					UPDATE `jurnal` SET `tanggal`='".date("Y-m-d")."' WHERE `id_detail`='".$id."'
				"), array(CURLOPT_BUFFERSIZE => 10));
				
				$data_save = array(
					"cashier"			=> $this->input->post('cashier'),
					"nomeja"			=> $this->input->post('nomeja'),
					"jamproses"			=> $this->input->post('jamproses'),
					"s100k"				=> $data->s100k,
					"s50k"				=> $data->s50k,
					"cart_1_no"			=> ($this->input->post('cart_1_val')!==null) ? $this->input->post('cart_1_val') : "",
					"cart_1_seal"		=> $data->cart_1_seal,
					"cart_2_no"			=> ($this->input->post('cart_2_val')!==null) ? $this->input->post('cart_2_val') : "",
					"cart_2_seal"		=> $data->cart_2_seal,
					"cart_3_no"			=> ($this->input->post('cart_3_val')!==null) ? $this->input->post('cart_3_val') : "",
					"cart_3_seal"		=> $data->cart_3_seal,
					"cart_4_no"			=> ($this->input->post('cart_4_val')!==null) ? $this->input->post('cart_4_val') : "",
					"cart_4_seal"		=> $data->cart_4_seal,
					"div_no"			=> ($this->input->post('div_val')!==null) ? $this->input->post('div_val') : "",
					"div_seal"			=> $data->div_seal,
					"t_bag_no"			=> ($this->input->post('t_bag')!==null) ? $this->input->post('t_bag') : "",
					"t_bag"				=> $data->t_bag,
					"bag_seal"			=> $data->bag_seal,
					"bag_no"			=> $data->bag_no,
					"ej_copy"			=> $data->ej_copy,
					"receipt"			=> $data->receipt,
					"ac"				=> $data->ac,
					"keypad"			=> $data->keypad,
					"card_taken"		=> $data->card_taken,
					"card_clear"		=> $data->card_clear,
					"test_dispense"		=> $data->test_dispense,
					"key"				=> $data->key,
					"admin_card"		=> $data->admin_card,
					"sparepart1"		=> $data->sparepart1,
					"sparepart2"		=> $data->sparepart2,
					"note_atm"			=> $data->note_atm,
					"return_type"		=> $data->return_type,
					"return_withdraw"	=> $data->return_withdraw,
					"return_cassette"	=> $data->return_cassette,
					"return_rejected"	=> $data->return_rejected,
					"return_remaining"	=> $data->return_remaining,
					"return_dispensed"	=> $data->return_dispensed,
					"last_clear"		=> $data->last_clear
				);
			} else if($act=="CRM") {
				// $jumlah = intval($this->input->post('seal_1_50')) + intval($this->input->post('seal_1_100')) +
						  // intval($this->input->post('seal_2_50')) + intval($this->input->post('seal_2_100')) +
						  // intval($this->input->post('seal_3_50')) + intval($this->input->post('seal_3_100')) +
						  // intval($this->input->post('seal_4_50')) + intval($this->input->post('seal_4_100')) +
						  // intval($this->input->post('seal_5_50')) + intval($this->input->post('seal_5_100')) +
						  // intval($this->input->post('div_50')) + intval($this->input->post('div_100'));
				
				$data_seal = array(
					"seal1" => array("50"=>$this->input->post('seal_1_50'), "100"=>$this->input->post('seal_1_100')),
					"seal2" => array("50"=>$this->input->post('seal_2_50'), "100"=>$this->input->post('seal_2_100')),
					"seal3" => array("50"=>$this->input->post('seal_3_50'), "100"=>$this->input->post('seal_3_100')),
					"seal4" => array("50"=>$this->input->post('seal_4_50'), "100"=>$this->input->post('seal_4_100')),
					"seal5" => array("50"=>$this->input->post('seal_5_50'), "100"=>$this->input->post('seal_5_100')),
					"divert" => array("50"=>$this->input->post('div_50'), "100"=>$this->input->post('div_100')),
					"tbag" => array("50"=>$this->input->post('tbag_50'), "100"=>$this->input->post('tbag_100'))
				);
				
				$sum = 0;
				$debit_100 = 0;
				$debit_50 = 0;
				foreach ($data_seal as $item) {
					$debit_100 = $debit_100 + $item['100'];
					$debit_50 = $debit_50 + $item['50'];
					$sum += $item['50'];
					$sum += $item['100'];
				}
				
				$data_jurnal['id_detail'] = $id;
				$data_jurnal['tanggal'] = date("Y-m-d");
				$data_jurnal['keterangan'] = "return";
				$data_jurnal['posisi'] = "debit";
				$data_jurnal['debit_100'] = $debit_100*100000;
				$data_jurnal['debit_50'] = $debit_50*50000;
				
				$table = "jurnal";
				$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_jurnal), array(CURLOPT_BUFFERSIZE => 10));
				
				$data_save = array(
					"cashier"				=> $this->input->post('cashier'),
					"nomeja"				=> $this->input->post('nomeja'),
					"jamproses"				=> $this->input->post('jamproses'),
					"s100k"					=> $data->s100k,
					"s50k"					=> $data->s50k,
					"cart_1_no"				=> json_encode($data_seal['seal1']),
					"cart_1_seal"			=> $data->cart_1_seal,
					"cart_2_no"				=> json_encode($data_seal['seal2']),
					"cart_2_seal"			=> $data->cart_2_seal,
					"cart_3_no"				=> json_encode($data_seal['seal3']),
					"cart_3_seal"			=> $data->cart_3_seal,
					"cart_4_no"				=> json_encode($data_seal['seal4']),
					"cart_4_seal"			=> $data->cart_4_seal,
					"cart_5_no"				=> json_encode($data_seal['seal5']),
					"cart_5_seal"			=> $data->cart_5_seal,
					"div_no"				=> json_encode($data_seal['divert']),
					"div_seal"				=> $data->div_seal,
					"t_bag_no"				=> json_encode($data_seal['tbag']),
					"t_bag"					=> $data->t_bag,
					"data_seal"				=> json_encode($data_seal),
					"bag_seal"				=> $data->bag_seal,
					"bag_no"				=> $data->bag_no,
					"ej_copy"				=> $data->ej_copy,
					"receipt"				=> $data->receipt,
					"ac"					=> $data->ac,
					"keypad"				=> $data->keypad,
					"card_taken"			=> $data->card_taken,
					"card_clear"			=> $data->card_clear,
					"test_dispense"			=> $data->test_dispense,
					"key"					=> $data->key,
					"admin_card"			=> $data->admin_card,
					"sparepart1"			=> $data->sparepart1,
					"sparepart2"			=> $data->sparepart2,
					"note_atm"				=> $data->note_atm,
					"return_type"			=> $data->return_type,
					"return_crm_starting" 	=> $data->return_crm_starting,
					"return_crm_cashin"		=> $data->return_crm_cashin,
					"return_crm_cashout"	=> $data->return_crm_cashout,
					"return_crm_balance"	=> $data->return_crm_balance,
					"last_clear"			=> $data->last_clear
				);
			} else if($act=="CDM") {
				// $jumlah = intval($this->input->post('seal_1_20')) + intval($this->input->post('seal_1_50')) + intval($this->input->post('seal_1_100')) + 
						  // intval($this->input->post('seal_2_20')) + intval($this->input->post('seal_2_50')) + intval($this->input->post('seal_2_100')) + 
						  // intval($this->input->post('seal_3_20')) + intval($this->input->post('seal_3_50')) + intval($this->input->post('seal_3_100')) + 
						  // intval($this->input->post('seal_4_20')) + intval($this->input->post('seal_4_50')) + intval($this->input->post('seal_4_100')) + 
						  // intval($this->input->post('div_20')) + intval($this->input->post('div_50')) + intval($this->input->post('div_100'));
		  
				$data_seal = array(
					"seal1" => array("20"=>$this->input->post('seal_1_20'), "50"=>$this->input->post('seal_1_50'), "100"=>$this->input->post('seal_1_100')),
					"seal2" => array("20"=>$this->input->post('seal_2_20'), "50"=>$this->input->post('seal_2_50'), "100"=>$this->input->post('seal_2_100')),
					"seal3" => array("20"=>$this->input->post('seal_3_20'), "50"=>$this->input->post('seal_3_50'), "100"=>$this->input->post('seal_3_100')),
					"seal4" => array("20"=>$this->input->post('seal_4_20'), "50"=>$this->input->post('seal_4_50'), "100"=>$this->input->post('seal_4_100')),
					"divert" => array("20"=>$this->input->post('div_20'), "50"=>$this->input->post('div_50'), "100"=>$this->input->post('div_100')),
					"tbag" => array("20"=>$this->input->post('tbag_20'), "50"=>$this->input->post('tbag_50'), "100"=>$this->input->post('tbag_100'))
				);
				
				$debit_100 = 0;
				$debit_50 = 0;
				$debit_20 = 0;
				foreach ($data_seal as $item) {
					$debit_100 = $debit_100 + $item['100'];
					$debit_50 = $debit_50 + $item['50'];
					$debit_20 = $debit_20 + $item['20'];
				}
				
				$data_jurnal['id_detail'] = $id;
				$data_jurnal['tanggal'] = date("Y-m-d");
				$data_jurnal['keterangan'] = "return";
				$data_jurnal['posisi'] = "debit";
				$data_jurnal['debit_100'] = $debit_100*100000;
				$data_jurnal['debit_50'] = $debit_50*50000;
				$data_jurnal['debit_20'] = $debit_20*20000;
				
				$table = "jurnal";
				$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data_jurnal), array(CURLOPT_BUFFERSIZE => 10));
				
				// print_r($data_jurnal);
				
				
				$data_save = array(
					"cashier"				=> $this->input->post('cashier'),
					"nomeja"				=> $this->input->post('nomeja'),
					"jamproses"				=> $this->input->post('jamproses'),
					"s100k"					=> $data->s100k,
					"s50k"					=> $data->s50k,
					"cart_1_no"				=> json_encode($data_seal['seal1']),
					"cart_1_seal"			=> $data->cart_1_seal,
					"cart_2_no"				=> json_encode($data_seal['seal2']),
					"cart_2_seal"			=> $data->cart_2_seal,
					"cart_3_no"				=> json_encode($data_seal['seal3']),
					"cart_3_seal"			=> $data->cart_3_seal,
					"cart_4_no"				=> json_encode($data_seal['seal4']),
					"cart_4_seal"			=> $data->cart_4_seal,
					"cart_5_no"				=> ($this->input->post('cart_5_val')!==null) ? $this->input->post('cart_5_val') : "",
					"cart_5_seal"			=> $data->cart_5_seal,
					"div_no"				=> json_encode($data_seal['divert']),
					"div_seal"				=> $data->div_seal,
					"t_bag_no"				=> json_encode($data_seal['tbag']),
					"t_bag"					=> $data->t_bag,
					"data_seal"				=> json_encode($data_seal),
					"bag_seal"				=> $data->bag_seal,
					"bag_no"				=> $data->bag_no,
					"ej_copy"				=> $data->ej_copy,
					"receipt"				=> $data->receipt,
					"ac"					=> $data->ac,
					"keypad"				=> $data->keypad,
					"card_taken"			=> $data->card_taken,
					"card_clear"			=> $data->card_clear,
					"test_dispense"			=> $data->test_dispense,
					"key"					=> $data->key,
					"admin_card"			=> $data->admin_card,
					"sparepart1"			=> $data->sparepart1,
					"sparepart2"			=> $data->sparepart2,
					"note_atm"				=> $data->note_atm,
					"return_type"			=> $data->return_type,
					"return_cdm_deposit" 	=> $data->return_cdm_deposit,
					"return_cdm_denom100"	=> $data->return_cdm_denom100,
					"return_cdm_denom50"	=> $data->return_cdm_denom50,
					"return_cdm_denom20"	=> $data->return_cdm_denom20,
					"last_clear"			=> $data->last_clear
				);
				
				// print_r($data_save);
			}
			
			// print_r($data_save);
			// print_r($data_seal);
			// print_r($_REQUEST);
			// print_r(json_encode($data_seal));
			
			
			$data_simpan['id'] = $id;
			$data_simpan['cpc_process'] = json_encode($data_save);
			
			$table = "cashtransit_detail";
			$this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data_simpan), array(CURLOPT_BUFFERSIZE => 10));
		}
		
		
		$query = "select *,
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
			WHERE runsheet_cashprocessing.id='".$id."'";
		
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));

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
		$id = $this->input->get("id");
		
		$data_solve = $this->db->query("SELECT data_solve FROM cashtransit_detail WHERE id='$id'")->row()->data_solve;
		$data = json_decode($data_solve);
		
		// print_r($data_solve);
		// echo $data_solve;
		// {
		// "s100k":"2000",
		// "s50k":"",
		// "cart_1_no":"",
		// "cart_1_seal":"388742",
		// "cart_2_no":"",
		// "cart_2_seal":"388741",
		// "cart_3_no":"",
		// "cart_3_seal":"",
		// "cart_4_no":"",
		// "cart_4_seal":"",
		// "div_no":"",
		// "div_seal":"388799",
		// "bag_seal":"973782",
		// "bag_no":"04/180",
		// "ej_copy":"1",
		// "receipt":"1",
		// "ac":"1",
		// "keypad":"1",
		// "card_taken":"1",
		// "card_clear":"1",
		// "test_dispense":"1",
		// "key":"1",
		// "admin_card":"1",
		// "sparepart1":"1",
		// "sparepart2":"1",
		// "note_atm":"\n                                                ",
		// "return_type":"1",
		// "return_withdraw":"384400000",
		// "return_cassette":"1999",
		// "return_rejected":"1",
		// "return_remaining":"0",
		// "return_dispensed":"2000"
		// }
		
		$state				= trim($this->input->post('state'));
		if($state=="ro_atm") {
			// print_r($_REQUEST);
			$data_save = array(
				"cashier"			=> $this->input->post('cashier'),
				"nomeja"			=> $this->input->post('nomeja'),
				"jamproses"			=> $this->input->post('jamproses'),
				"s100k"				=> $data->s100k,
				"s50k"				=> $data->s50k,
				"cart_1_no"			=> ($this->input->post('cart_1_val')!==null) ? $this->input->post('cart_1_val') : "",
				"cart_1_seal"		=> $data->cart_1_seal,
				"cart_2_no"			=> ($this->input->post('cart_2_val')!==null) ? $this->input->post('cart_2_val') : "",
				"cart_2_seal"		=> $data->cart_2_seal,
				"cart_3_no"			=> ($this->input->post('cart_3_val')!==null) ? $this->input->post('cart_3_val') : "",
				"cart_3_seal"		=> $data->cart_3_seal,
				"cart_4_no"			=> ($this->input->post('cart_4_val')!==null) ? $this->input->post('cart_4_val') : "",
				"cart_4_seal"		=> $data->cart_4_seal,
				"div_no"			=> ($this->input->post('div_val')!==null) ? $this->input->post('div_val') : "",
				"div_seal"			=> $data->div_seal,
				"bag_seal"			=> $data->bag_seal,
				"bag_no"			=> $data->bag_no,
				"ej_copy"			=> $data->ej_copy,
				"receipt"			=> $data->receipt,
				"ac"				=> $data->ac,
				"keypad"			=> $data->keypad,
				"card_taken"		=> $data->card_taken,
				"card_clear"		=> $data->card_clear,
				"test_dispense"		=> $data->test_dispense,
				"key"				=> $data->key,
				"admin_card"		=> $data->admin_card,
				"sparepart1"		=> $data->sparepart1,
				"sparepart2"		=> $data->sparepart2,
				"note_atm"			=> $data->note_atm,
				"return_type"		=> $data->return_type,
				"return_withdraw"	=> $data->return_withdraw,
				"return_cassette"	=> $data->return_cassette,
				"return_rejected"	=> $data->return_rejected,
				"return_remaining"	=> $data->return_remaining,
				"return_dispensed"	=> $data->return_dispensed,
				"last_clear"		=> $data->last_clear
			);
			
			// print_r($data_save);
			$data_simpan['cpc_process'] = json_encode($data_save);
			
			$this->db->trans_start();
			$this->db->where('id', $id);
			$this->db->update('cashtransit_detail', $data_simpan);
			$this->db->trans_complete();
		}
		// if($state=="ro_cit") {
			// $id					= strtoupper(trim($this->input->post('id')));
			// $id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
			// $run_number			= strtoupper(trim($this->input->post('runsheet')));
			// $pcs_100000			= strtoupper(trim($this->input->post('100k')));
			// $pcs_50000			= strtoupper(trim($this->input->post('50k')));
			// $pcs_20000			= strtoupper(trim($this->input->post('20k')));
			// $pcs_10000			= strtoupper(trim($this->input->post('10k')));
			// $pcs_5000			= strtoupper(trim($this->input->post('5k')));
			// $pcs_2000			= strtoupper(trim($this->input->post('2k')));
			// $pcs_1000			= strtoupper(trim($this->input->post('1k')));
			// $pcs_coin			= strtoupper(trim($this->input->post('coins')));
			// $bag_seal			= strtoupper(trim($this->input->post('bag_seal')));
			// $bag_no				= strtoupper(trim($this->input->post('bag_no')));
			// $nominal			= strtoupper(trim($this->input->post('nominal')));
			
			// $data['id'] = $id;
			// $data['id_cashtransit'] = $id_cashtransit;
			// $data['run_number'] = $run_number;
			// $data['pcs_100000'] = ($pcs_100000!=="") ? $pcs_100000 : 0;
			// $data['pcs_50000'] = ($pcs_50000!=="") ? $pcs_50000 : 0;
			// $data['pcs_20000'] = ($pcs_20000!=="") ? $pcs_20000 : 0;
			// $data['pcs_10000'] = ($pcs_10000!=="") ? $pcs_10000 : 0;
			// $data['pcs_5000'] = ($pcs_5000!=="") ? $pcs_5000 : 0;
			// $data['pcs_2000'] = ($pcs_2000!=="") ? $pcs_2000 : 0;
			// $data['pcs_1000'] = ($pcs_1000!=="") ? $pcs_1000 : 0;
			// $data['pcs_coin'] = ($pcs_coin!=="") ? $pcs_coin : 0;
			// $data['bag_seal'] = $bag_seal;
			// $data['bag_no'] = $bag_no;
			// $data['total'] = $nominal;
			
			
		
			// // print_r($data);
			
			// $count = $this->db->query("SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'")->row();
			
			// if($count->cnt==0) {
				// $this->db->trans_start();
				// $this->db->insert('runsheet_cashprocessing', $data);
				// $this->db->trans_complete();
			// } else {
				// $this->db->trans_start();
				// $this->db->where('id', $id);
				// $this->db->update('runsheet_cashprocessing', $data);
				// $this->db->trans_complete();
			// }
			
			
			// $row = $this->db->query("select *,
				// cashtransit_detail.id as id_ct, 
				// cashtransit_detail.id_cashtransit, 
				// cashtransit_detail.id_bank, 
				// cashtransit_detail.state as state,
				// cashtransit_detail.ctr as ctr, 
				// cashtransit_detail.pcs_100000 as pcs_100000, 
				// cashtransit_detail.pcs_50000 as pcs_50000, 
				// cashtransit_detail.pcs_20000 as pcs_20000, 
				// cashtransit_detail.pcs_10000 as pcs_10000, 
				// cashtransit_detail.pcs_5000 as pcs_5000, 
				// cashtransit_detail.pcs_2000 as pcs_2000, 
				// cashtransit_detail.pcs_1000 as pcs_1000, 
				// cashtransit_detail.pcs_coin as pcs_coin, 
				// cashtransit_detail.total as total, 
				// client.cabang as branch,
				// client.bank,
				// client.lokasi,
				// client.sektor,
				// cashtransit_detail.jenis,
				// client.denom,
				// client.vendor,
				// client.type_mesin,
				// runsheet_cashprocessing.pcs_100000 as s100k,
				// runsheet_cashprocessing.pcs_50000 as s50k,
				// runsheet_cashprocessing.pcs_20000 as s20k,
				// runsheet_cashprocessing.pcs_10000 as s10k,
				// runsheet_cashprocessing.pcs_5000 as s5k,
				// runsheet_cashprocessing.pcs_2000 as s2k,
				// runsheet_cashprocessing.pcs_1000 as s1k,
				// runsheet_cashprocessing.pcs_coin as coin,
				// runsheet_cashprocessing.total as nominal,
				// runsheet_cashprocessing.bag_seal,
				// runsheet_cashprocessing.bag_no
			// from cashtransit_detail 
				// LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
				// LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
				// LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
				// WHERE runsheet_cashprocessing.id='".$id."'")->row();

			// echo json_encode(array(
				// 'id' => $row->id_ct,
				// 'id_cashtransit' => $row->id_cashtransit,
				// 'id_bank' => $row->id_bank,
				// 'state' => $row->state,
				// 'branch' => $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name,
				// 'bank' => $row->bank,
				// 'lokasi' => $row->lokasi,
				// 'runsheet' => $row->sektor,
				// 'jenis' => $row->jenis,
				// 'denom' => $row->denom,
				// 'brand' => $row->vendor,
				// 'model' => $row->type_mesin,
				// 'pcs_100000' => $row->pcs_100000,
				// 'pcs_50000' => $row->pcs_50000,
				// 'pcs_20000' => $row->pcs_20000,
				// 'pcs_10000' => $row->pcs_10000,
				// 'pcs_5000' => $row->pcs_5000,
				// 'pcs_2000' => $row->pcs_2000,
				// 'pcs_1000' => $row->pcs_1000,
				// 'pcs_coin' => $row->pcs_coin,
				// 's100k' => $row->s100k,
				// 's50k' => $row->s50k,
				// 's20k' => $row->s20k,
				// 's10k' => $row->s10k,
				// 's5k' => $row->s5k,
				// 's2k' => $row->s2k,
				// 's1k' => $row->s1k,
				// 'coin' => $row->coin,
				// 'ctr' => $row->ctr,
				// 'total' => $row->total,
				// 'nominal' => (100000*$row->s100k)+(50000*$row->s50k)+(20000*$row->s20k)+(10000*$row->s10k)+(5000*$row->s5k)+(2000*$row->s2k)+(1000*$row->s1k)+(1*$row->coin),
				// 'bag_seal' => $row->bag_seal,
				// 'bag_no' => $row->bag_no
			// ));
		// } else {
			// $id					= strtoupper(trim($this->input->post('id')));
			// $id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
			// $run_number			= strtoupper(trim($this->input->post('runsheet')));
			// $pcs_100000			= strtoupper(trim($this->input->post('s100k')));
			// $pcs_50000			= strtoupper(trim($this->input->post('s50k')));
			// $cart_1_no			= strtoupper(trim($this->input->post('cart_1_no')));
			// $cart_2_no			= strtoupper(trim($this->input->post('cart_2_no')));
			// $cart_3_no			= strtoupper(trim($this->input->post('cart_3_no')));
			// $cart_4_no			= strtoupper(trim($this->input->post('cart_4_no')));
			// $cart_1_seal		= strtoupper(trim($this->input->post('cart_1_seal')));
			// $cart_2_seal		= strtoupper(trim($this->input->post('cart_2_seal')));
			// $cart_3_seal		= strtoupper(trim($this->input->post('cart_3_seal')));
			// $cart_4_seal		= strtoupper(trim($this->input->post('cart_4_seal')));
			// $divert				= strtoupper(trim($this->input->post('divert')));
			// $bag_seal			= strtoupper(trim($this->input->post('bag_seal')));
			// $bag_no				= strtoupper(trim($this->input->post('bag_no')));
			// $nominal			= strtoupper(trim($this->input->post('nominal')));
			
			// $data['id'] = $id;
			// $data['id_cashtransit'] = $id_cashtransit;
			// $data['run_number'] = $run_number;
			// $data['pcs_100000'] = ($pcs_100000!=="") ? $pcs_100000 : 0;
			// $data['pcs_50000'] = ($pcs_50000!=="") ? $pcs_50000 : 0;
			// $data['ctr_1_no'] = ($cart_1_no!=="") ? $cart_1_no : "";
			// $data['ctr_2_no'] = ($cart_2_no!=="") ? $cart_2_no : "";
			// $data['ctr_3_no'] = ($cart_3_no!=="") ? $cart_3_no : "";
			// $data['ctr_4_no'] = ($cart_4_no!=="") ? $cart_4_no : "";
			// $data['cart_1_seal'] = ($cart_1_seal!=="") ? $cart_1_seal : "";
			// $data['cart_2_seal'] = ($cart_2_seal!=="") ? $cart_2_seal : "";
			// $data['cart_3_seal'] = ($cart_3_seal!=="") ? $cart_3_seal : "";
			// $data['cart_4_seal'] = ($cart_4_seal!=="") ? $cart_4_seal : "";
			// $data['divert'] = $divert;
			// $data['bag_seal'] = $bag_seal;
			// $data['bag_no'] = $bag_no;
			
			// $ctr_1 = ($data['cart_1_seal']!=="") ? 1 : 0;
			// $ctr_2 = ($data['cart_2_seal']!=="") ? 1 : 0;
			// $ctr_3 = ($data['cart_3_seal']!=="") ? 1 : 0;
			// $ctr_4 = ($data['cart_4_seal']!=="") ? 1 : 0;
			
			// $data['total'] = ($ctr_1+$ctr_2+$ctr_3+$ctr_4)*((100000*$pcs_100000)+(50000*$pcs_50000));
			
			// // print_r($data);
			// // print_r($this->input->post());
			// $count = $this->db->query("SELECT count(*) as cnt FROM runsheet_cashprocessing WHERE id='$id'")->row();
			
			// if($count->cnt==0) {
				// $this->db->trans_start();
				// $this->db->insert('runsheet_cashprocessing', $data);
				// $this->db->trans_complete();
			// } else {
				// $this->db->trans_start();
				// $this->db->where('id', $id);
				// $this->db->update('runsheet_cashprocessing', $data);
				// $this->db->trans_complete();
			// }
			
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
		// }
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
	
	function check_seal() {
		$id = $this->input->post('id');
		$value = $this->input->post('value');
		
		$data_solve = json_decode(json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT data_solve FROM cashtransit_detail WHERE id='$id'"), array(CURLOPT_BUFFERSIZE => 10)))->data_solve);
		
		if (strpos($value, 'A') !== false) {
			$compare = $data_solve->bag_seal;
		}
		if (strpos($value, '.') !== false) {
			$compare = $data_solve->bag_no;
		}
		// print_r($data_solve);
		// echo $value.' '.$compare;
		
		if($value==$compare) {
			echo "true";
		} else {
			echo "false";
		}
	}
}