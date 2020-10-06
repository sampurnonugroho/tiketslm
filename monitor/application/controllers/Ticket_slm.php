<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_slm extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("ticket_model");
        $this->load->library('form_validation');
		$this->load->library('curl');

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));
			$level = trim($this->session->userdata('level'));

			$this->data['level'] = $level;
			$this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			$this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			$this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			$this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        // $data["atm"] = $this->atm_model->getAll();

        // $data["title"] = "Ticket";
        // $data["session"] = $this->session;

        // return view('admin/ticket_slm/index', $data);
    }

    public function add_ticket_slm() {
		$this->data['active_menu'] = "add_ticket_slm";
		$this->data['url'] = "ticket/save";
		$this->data['flag'] = "add";
		
		return view('admin/ticket_slm/index', $this->data);
	}
	
	function json() {
		$query = "SELECT 
						#*,
						flm_trouble_ticket_slm.teknisi_1 as teknisi_slm,
						client.wsid,
						client.type as act,
						client.type_mesin as model,
						client.lokasi,
						flm_trouble_ticket.id,
						flm_trouble_ticket.id_ticket,
						flm_trouble_ticket.ticket_client,
						flm_trouble_ticket.status,
						flm_trouble_ticket.problem_type as problem
					FROM flm_trouble_ticket 
					LEFT JOIN flm_trouble_ticket_slm ON(flm_trouble_ticket_slm.id_ticket=flm_trouble_ticket.id_ticket)
					LEFT JOIN client ON(flm_trouble_ticket.id_bank=client.id)
			";
		
		$param['query'] = $query; //nama tabel dari database
		$param['column_order'] = array('flm_trouble_ticket.id'); //field yang ada di table user
		$param['column_search'] = array('flm_trouble_ticket.id_ticket'); //field yang diizin untuk pencarian 
		$param['order'] = array(array('flm_trouble_ticket.id' => 'DESC'));
		$param['where'] = array(array('flm_trouble_ticket.status' => 'SLM'));
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		echo $this->curl->simple_get(rest_api().'/datatables', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
	
    public function add_ticket_slm2() {
        $this->data['active_menu'] = "add_ticket_slm";
		$this->data['url'] = "ticket/save";
		$this->data['flag'] = "add";
		
		$id_user = trim($this->session->userdata('id_user'));

        $cari_data = "select A.nik, A.nama, C.nama_dept, B.nama_bagian_dept FROM karyawan A
        							   LEFT JOIN bagian_departemen B ON B.id_bagian_dept = A.id_bagian_dept
        							   LEFT JOIN departemen C ON C.id_dept = B.id_dept
        							   WHERE A.nik = '$id_user'";

        $row = $this->db->query($cari_data)->row();

        $this->data['id_ticket'] = "";

        $this->data['id_user'] = $id_user;
        $this->data['nama'] = $row->nama;
        $this->data['departemen'] = $row->nama_dept;
        $this->data['bagian_departemen'] = $row->nama_bagian_dept;		
		
		$this->data['dd_kategori'] = $this->model_app->dropdown_kategori();
		$this->data['id_kategori'] = "";

		$this->data['dd_kondisi'] = $this->model_app->dropdown_kondisi();
		$this->data['id_kondisi'] = "";

		$this->data['problem_summary'] = "";
		$this->data['problem_detail'] = "";

		$this->data['status'] = "";
		$this->data['progress'] = "";

        $this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
        return view('admin/ticket_slm/form', $this->data);
    }

    public function ticket_list() {
        // $data["data"] = $this->ticket_model->getAll();
        
        $this->data['active_menu'] = "ticket_list";

        $this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
        return view('admin/ticket_slm/index', $this->data);
    }
	
	function save()
	{

		$getkodeticket = $this->model_app->getkodeticket();

		$ticket = $getkodeticket;

		$id_user = strtoupper(trim($this->input->post('id_user')));
		$tanggal = $time = date("Y-m-d  H:i:s");

		$id_sub_kategori = strtoupper(trim($this->input->post('id_sub_kategori')));
		$problem_summary = strtoupper(trim($this->input->post('problem_summary')));
		$problem_detail = strtoupper(trim($this->input->post('problem_detail')));
		$id_teknisi = strtoupper(trim($this->input->post('id_teknisi')));

		$data['id_ticket'] = $ticket;
		$data['reported'] = $id_user;
		$data['tanggal'] = $tanggal;
		$data['id_sub_kategori'] = $id_sub_kategori;
		$data['problem_summary'] = $problem_summary;
		$data['problem_detail'] = $problem_detail;
		$data['id_teknisi'] = $id_teknisi;
		$data['status'] = 1;
		$data['progress'] = 0;

		$tracking['id_ticket'] = $ticket;
		$tracking['tanggal'] = $tanggal;
		$tracking['status'] = "Created Ticket";
		$tracking['deskripsi'] = "";
		$tracking['id_user'] = $id_user;

		$this->db->trans_start();

		$this->db->insert('ticket', $data);
		$this->db->insert('tracking', $tracking);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('approval/approval_list');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('approval/approval_list');	
		}
			
	}
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		
		return view('admin/ticket_slm/show_form', $this->data);
	}
	
	function get_data() {
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		
		// $query = $this->db->query("SELECT count(*) as cnt FROM flm_trouble_ticket LEFT JOIN client ON(flm_trouble_ticket.id_bank=client.id) WHERE flm_trouble_ticket.status='SLM'");
        // $row = $query->row_array();
		// $result["total"] = $row['cnt'];
		
		// $query = $this->db->query("");
		
		$query = "SELECT * FROM flm_trouble_ticket LEFT JOIN client ON(flm_trouble_ticket.id_bank=client.id) WHERE flm_trouble_ticket.status='FLM' limit $offset,$rows";
		$results = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$rows = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT FOUND_ROWS() AS `found_rows`;"), array(CURLOPT_BUFFERSIZE => 10)));
		$result["total"] = $rows->found_rows;
		
		// print_r($results);
		
		$items = array();
		$i = 0;
		foreach($results as $row){
			$branch = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM master_branch WHERE id='$row->cabang'"), array(CURLOPT_BUFFERSIZE => 10)));
			$pro_type = json_decode($row->problem_type);	
			$ary = array();
			foreach($pro_type as $arr) {
				$ary[] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>'SELECT nama_kategori FROM kategori WHERE id_kategori="'.$arr.'"'), array(CURLOPT_BUFFERSIZE => 10)))->nama_kategori;
			}
			
			$items[$i]['id'] = $row->id;
			$items[$i]['id_ticket'] = $row->id_ticket;
			$items[$i]['branch'] = $branch->name;
			$items[$i]['ga'] = $row->sektor;
			$items[$i]['bank'] = $row->bank;
			$items[$i]['act'] = $row->type;
			$items[$i]['brand'] = $row->vendor;
			$items[$i]['model'] = $row->type_mesin;
			$items[$i]['location'] = $row->lokasi;
			$items[$i]['problem_type'] = implode(", ",$ary);
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	function get_data_client() {
		$id = $this->input->get('id');
		
		$client = $this->db->query("SELECT * FROM client WHERE id='$id'")->row();
		
		$data['branch'] = $client->cabang;
		$data['ga'] = $client->sektor;
		$data['bank'] = $client->bank;
		$data['act'] = $client->type;
		$data['brand'] = $client->vendor;
		$data['model'] = $client->type_mesin;
		$data['location'] = $client->lokasi;
		$data['denom'] = $client->denom;
		$data['ctr'] = $client->ctr;
		
		echo json_encode($data);
	}
	
	function select_problem() {
		$search = $this->input->post('search');
		// if($search!="") {
		$search = "%".strtolower($search)."%";
		// }
		$sql = "SELECT * FROM kategori WHERE nama_kategori LIKE '$search'";
		// echo $sql;
		$result = $this->db->query($sql);
		
		$list = array();
		if ($result->num_rows() > 0) {
			$key=0;
			foreach ($result->result() as $row) {
				$list[$key]['id'] = $row->id_kategori;
				$list[$key]['text'] = $row->nama_kategori; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function select_slm() {
		$search = $this->input->post('search');
		// if($search!="") {
		$search = "%".strtolower($search)."%";
		// }
		$query = "SELECT * FROM teknisi LEFT JOIN karyawan ON(teknisi.nik=karyawan.nik) LEFT JOIN user ON(teknisi.nik=user.username) WHERE user.level='SLM' AND karyawan.nama LIKE '$search'";
		// echo $query;	
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->id_teknisi;
				$list[$key]['text'] = $row->nama; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function getkodeticket()
    {
        $query = $this->db->query("select max(id_ticket) as max_code FROM flm_trouble_ticket");

        $row = $query->row_array();

        $max_id = $row['max_code'];
        $max_fix = (int) substr($max_id, 9, 4);

        $max_nik = $max_fix + 1;

        $tanggal = $time = date("d");
        $bulan = $time = date("m");
        $tahun = $time = date("Y");

        $nik = "T".$tahun.$bulan.$tanggal.sprintf("%04s", $max_nik);
        return $nik;
    }
	
	function  save_data() {
		$ticket = $this->getkodeticket();
		$data['id_ticket'] = $ticket;
		$data['id_bank'] = strtoupper(trim($this->input->post('id_bank')));
		$data['problem_type'] = json_encode($this->input->post('problem_type'));
		$data['teknisi_1'] = strtoupper(trim($this->input->post('teknisi_1')));
		$data['time'] = date("H:i:s");
		
		$this->db->trans_start();

		$this->db->insert('flm_trouble_ticket', $data);

		$this->db->trans_complete();
		
		$row = $this->db->query("SELECT * FROM flm_trouble_ticket LEFT JOIN client ON(flm_trouble_ticket.id_bank=client.id) WHERE flm_trouble_ticket.id_ticket='$ticket'")->row();
		$branch = $this->db->query("SELECT * FROM master_branch WHERE id='$row->cabang'")->row();
		
		$pro_type = json_decode($row->problem_type);
		
		$ary = array();
		foreach($pro_type as $arr) {
			$ary[] = $this->db->query('SELECT nama_kategori FROM kategori WHERE id_kategori="'.$arr.'"')->row()->nama_kategori;
		}
		
		echo json_encode(array(
			'id' => $row->id_bank,
			'branch' => $branch->name,
			'ga' => $row->sektor,
			'bank' => $row->bank,
			'act' => $row->type,
			'brand' => $row->vendor,
			'model' => $row->type_mesin,
			'location' => $row->lokasi,
			'problem_type' => implode(", ",$ary)
		));
		
		$this->notification();
	}
	
	public function save_data2() {
		$query = "SELECT * FROM flm_trouble_ticket_slm WHERE id_ticket='".$_REQUEST['id_ticket']."'";
		$num_rows = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		if(count($num_rows)==0) {
			$query = "INSERT INTO flm_trouble_ticket_slm (`id_ticket`, `ticket_client`, `id_bank`, `problem_type`, `time`)
						SELECT id_ticket, ticket_client, id_bank, problem_type, time FROM flm_trouble_ticket
						WHERE flm_trouble_ticket.id_ticket='".$_REQUEST['id_ticket']."';";
						
			json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			$query = "UPDATE flm_trouble_ticket_slm SET vendor='".$_REQUEST['vendor']."', teknisi_1='".$_REQUEST['teknisi_1']."' WHERE id_ticket='".$_REQUEST['id_ticket']."'";
			$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		} else {		
			$query = "UPDATE flm_trouble_ticket_slm SET vendor='".$_REQUEST['vendor']."', teknisi_1='".$_REQUEST['teknisi_1']."' WHERE id_ticket='".$_REQUEST['id_ticket']."'";
			$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		}
		
		if(!$res) {
			echo "success";
		} else {
			echo "failed";
		}
	}
	
	function  update_data() {
		
		// print_r($_REQUEST);
		
		$query = "SELECT * FROM flm_trouble_ticket_slm WHERE id_ticket='".$_REQUEST['id_ticket']."'";
		$num_rows = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		if(count($num_rows)==0) {
			$query = "INSERT INTO flm_trouble_ticket_slm (`id_ticket`, `id_bank`, `problem_type`, `time`, `status`, `data_solve`)
						SELECT id_ticket, id_bank, problem_type, time FROM flm_trouble_ticket
						WHERE flm_trouble_ticket.id_ticket='".$_REQUEST['id_ticket']."';";
						
			json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			$query = "UPDATE flm_trouble_ticket_slm SET teknisi_2='".$_REQUEST['teknisi_2']."' WHERE id_ticket='".$_REQUEST['id_ticket']."'";
			json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		} else {		
			$query = "UPDATE flm_trouble_ticket_slm SET teknisi_2='".$_REQUEST['teknisi_2']."' WHERE id_ticket='".$_REQUEST['id_ticket']."'";
			json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		}
		
		// $ticket = $this->getkodeticket();
		// $data['id_ticket'] = $ticket;
		// $data['id_bank'] = strtoupper(trim($this->input->post('id_bank')));
		// $data['problem_type'] = json_encode($this->input->post('problem_type'));
		// $data['teknisi_2'] = strtoupper(trim($this->input->post('teknisi_2')));
		// $data['time'] = date("H:i:s");
		
		// $this->db->trans_start();

		// $this->db->insert('flm_trouble_ticket_slm', $data);

		// $this->db->trans_complete();
		
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM flm_trouble_ticket_slm LEFT JOIN client ON(flm_trouble_ticket_slm.id_bank=client.id) WHERE flm_trouble_ticket_slm.id_ticket='".$_REQUEST['id_ticket']."'"), array(CURLOPT_BUFFERSIZE => 10)));;
		$branch = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT * FROM master_branch WHERE id='$row->cabang'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		// $pro_type = json_decode($row->problem_type);
		
		// $ary = array();
		// foreach($pro_type as $arr) {
			// $ary[] = $this->db->query('SELECT nama_kategori FROM kategori WHERE id_kategori="'.$arr.'"')->row()->nama_kategori;
		// }
		
		echo json_encode(array(
			'id' => $row->id_bank,
			'branch' => $branch->name,
			'ga' => $row->sektor,
			'bank' => $row->bank,
			'act' => $row->type,
			'brand' => $row->vendor,
			'model' => $row->type_mesin,
			'location' => $row->lokasi
			// 'problem_type' => implode(", ",$ary)
		));
		$query = 'SELECT user.token FROM teknisi LEFT JOIN user ON(teknisi.nik=user.username) WHERE teknisi.id_teknisi="'.$_REQUEST['teknisi_2'].'"';
		$token = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->token;
		$this->notification($token);
	}

	function notification($token) {
		
		define('AUTHORIZATION_KEY', 'AAAAnLcRlWk:APA91bF0J5GW72L1GeWozjX8LNSSq5usc8twk0mUL53izRdPK9HiVz_yZziH7XUq_rXizKFk_bEv26FeQaRFe3YY5sPIpeTaV23CA1-s2kfPFYhdIp4ZzyaviG3Iv59uJW-pHb6-pZSI');

		$_REQUEST['to'] = $token;

		$fields = array(
			'to' => $_REQUEST['to'],
			'data' => array(
				"notification_body" => "Data message body",
				"notification_title"=> "Data message title",
				"notification_foreground"=> "true",
				"notification_android_channel_id"=> "my_channel_id",
				"notification_android_priority"=> "2",
				"notification_android_visibility"=> "1",
				"notification_android_color"=> "#ff0000",
				"notification_android_icon"=> "thumbs_up",
				"notification_android_sound"=> "alarm2",
				"notification_android_vibrate"=> "500, 200, 500",
				"notification_android_lights"=> "#ffff0000, 250, 250",
				"key_1" => "Data for key one"
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
	
	function status_slm() {
		$this->data['active_menu'] = "status_slm";
		
		$this->data['statusflm'] = $this->db->query("SELECT *, flm_trouble_ticket_slm.status as status_ticket FROM flm_trouble_ticket_slm LEFT JOIN client ON(flm_trouble_ticket_slm.id_bank=client.id)")->result();
		$this->data['db'] = $this->db;
		// print_r($this->data['statusflm']);
		
		return view('admin/ticket_slm/status', $this->data);
	}
}