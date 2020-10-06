<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logistic extends CI_Controller {
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
		$this->data['active_menu'] = "logistic";
		
        $query = "
			SELECT
				*, 
				cashtransit.id as id_ct,
				IFNULL((
					SELECT 
						COUNT(DISTINCT c.id)
							FROM cashtransit as c
								WHERE 
									c.date = cashtransit.action_date AND 
									c.id NOT IN (SELECT id_cashtransit FROM runsheet_logistic)
				), 0) AS count
				FROM
					cashtransit
					LEFT JOIN
						master_branch
							ON (cashtransit.branch=master_branch.id) GROUP BY cashtransit.action_date ORDER BY cashtransit.id DESC 
		";
        $this->data['data_logistic'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($this->data['data_logistic']);
		// echo "</pre>";
		
        return view('admin/logistic/index', $this->data);
	}
	
	function json() {
		$query = "
			SELECT
				*, 
				cashtransit.id as id_ct,
				master_branch.name as branch,
				IFNULL((
					SELECT 
						COUNT(DISTINCT c.id)
							FROM cashtransit as c
								WHERE 
									c.action_date = cashtransit.action_date AND 
									c.id NOT IN (SELECT id_cashtransit FROM runsheet_logistic)
				), 0) AS count
				FROM
					cashtransit
					LEFT JOIN
						master_branch
							ON (cashtransit.branch=master_branch.id)
		";
		
		$param['query'] = $query; //nama tabel dari database
		$param['column_order'] = array('id_ct'); //field yang ada di table user
		$param['column_search'] = array('action_date'); //field yang diizin untuk pencarian 
		$param['order'] = array(array('id_ct' => 'DESC'));
		$param['group'] = array('action_date');
		$param['where'] = array(array('cashtransit.action_date[!]' => ''));
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		echo $this->curl->simple_get(rest_api().'/datatables', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
	
	public function add() {
        $this->data['active_menu'] = "logistic";
		$this->data['url'] = "logistic/save";
		$this->data['flag'] = "add";
		
		$id = $this->uri->segment(3);
		
		$branch = $this->db->query("SELECT branch FROM cashtransit WHERE id='$id'")->row()->branch;
		$branch = $this->db->query("SELECT name FROM master_branch WHERE id='$branch'")->row()->name;
		
		$this->data['id'] = $id;
		$this->data['branch'] = ": Branch ".$branch;
		
        return view('admin/logistic/form', $this->data);
	}
	
	public function edit() {
		$this->data['active_menu'] = "logistic";
		$this->data['url'] = "logistic/save";
		$this->data['flag'] = "edit";
		
		$date = $this->uri->segment(3);
		
		$this->data['date'] = $date;
		$query = "SELECT * FROM inventory WHERE type='supplies' AND name IN (SELECT name FROM view_inv_seal)";
		$this->data['inventory'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
        return view('admin/logistic/form', $this->data);
	}
	
	public function get_data() {
		// $id = $this->uri->segment(3);
		// $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		// $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		
		// $data['id'] = $id;
		// $data['page'] = $page;
		// $data['rows'] = $rows;
		
		// $result = $this->curl->simple_post(rest_api().'/run_logistic/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));

		// echo $result;
		
		$date = $this->uri->segment(3);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		
		$data['id'] = $id;
		$data['page'] = $page;
		$data['rows'] = $rows;
		
		$query = "
				SELECT 
					*,
					runsheet_logistic.id as ids
				FROM runsheet_logistic 
				LEFT JOIN cashtransit ON(cashtransit.id=runsheet_logistic.id_cashtransit) 
				WHERE cashtransit.action_date='".$date."' LIMIT $offset,$rows";
				
		
		// $result = $this->curl->simple_post(rest_api().'/run_operational/get_data',$data,array(CURLOPT_BUFFERSIZE => 10));
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		$result["total"] = count($res);
		
		// json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT name FROM inventory WHERE id='".$k."'"), array(CURLOPT_BUFFERSIZE => 10)))->name;

		$items = array();
		$i = 0;
		foreach($res as $row){
			$items[$i]['id'] = $row->ids;
			$items[$i]['run_number'] = "(H-".$row->h_min.") (RUN NUMBER ".$row->run_number.")"; 
			$datas = json_decode($row->data);
			$l=0;
			$html = "";
			foreach($datas as $k => $r) {
				$html .= "".json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT name FROM inventory WHERE id='".$k."'"), array(CURLOPT_BUFFERSIZE => 10)))->name." = ".$r."<br>";
				$items[$i][$k] = $r;
			}
			$items[$i]['datas'] = $html;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	public function show_form() {
		$this->data['flag'] = "show_form";
		$this->data['date'] = $this->input->get('date');
		
		if(isset($this->data['row']->isNewRecord)) {
			$this->data['flag'] = "ADD";
		} else {
			$this->data['flag'] = "EDIT";
		}
		
		$query = "SELECT * FROM inventory WHERE type='supplies' AND name IN (SELECT name FROM view_inv_seal)";
		$this->data['inventory'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		return view('admin/logistic/show_form', $this->data);
	}
	
	function suggest_data_client() {
		$search = $this->input->post('search');
		$date = $this->input->post('date');
		
		$query = "SELECT * FROM cashtransit WHERE action_date='$date' AND h_min!='' AND cashtransit.id NOT IN (SELECT id_cashtransit FROM runsheet_logistic)";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));

		$list = array();
		$key = 0;
		$prev_id = "";
		foreach($result as $row) {
			$list[$key]['id'] = $row->id;
			$list[$key]['text'] = "(H-".$row->h_min.") (RUN NUMBER ".$row->run_number.")"; 
			
			$key++;
		}
		
		echo json_encode($list);
	}
	
	// public function get_data_logistic() {
		// $search = $this->input->post('search');
		
		// $sql = "SELECT * FROM master_seal WHERE kode='$search'";
		// $result = $this->db->query($sql);
		
		// $list = array();
		// if ($result->num_rows() > 0) {
			// $key=0;
			// foreach ($result->result() as $row) {
				// $list[$key]['id'] = $row->id;
				// $list[$key]['text'] = $row->kode; 
				// $key++;
			// }
			// echo json_encode($list);
		// } else {
			// echo json_encode($list);
		// }
	// }
	
	// public function suggest() {
		// $search = $this->input->post('search');
		// $id_cashtransit = $this->input->post('id_cashtransit');
		
		// $sql = "SELECT * FROM cashtransit_detail LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN master_zone ON(client.sektor=master_zone.id) WHERE cashtransit_detail.id_cashtransit='$id_cashtransit' AND client.sektor NOT IN (SELECT run_number FROM runsheet_logistic WHERE id_cashtransit='$id_cashtransit') GROUP BY client.sektor";
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
	
	function save_data() {
		$datazxc = $this->input->post();
		
		$date				= strtoupper(trim($this->input->post('date')));
		$id_cashtransit		= strtoupper(trim($this->input->post('run_number')));
		$run_number			= "0";
		$datas				= json_encode($this->input->post('inventory'));
		
		$data['id_cashtransit'] = $id_cashtransit;
		$data['run_number'] = $run_number;
		$data['data'] = $datas;
		
		$table = "runsheet_logistic";
		$res = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		
		$query = "
				SELECT 
					*,
					runsheet_logistic.id as ids
				FROM runsheet_logistic 
				LEFT JOIN cashtransit ON(cashtransit.id=runsheet_logistic.id_cashtransit) 
				WHERE cashtransit.action_date='".$date."' AND cashtransit.id='".$id_cashtransit."'";
				
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$i=0;
		$html = "";
		$new_array = array(
			'id' => $row->ids,
			'run_number' => "(H-".$row->h_min.") (RUN NUMBER ".$row->run_number.")"
		);
		foreach($datazxc['inventory'] as $k => $r) {
			$name = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT name FROM inventory WHERE id='".$k."'"), array(CURLOPT_BUFFERSIZE => 10)))->name;
			$html .= "".$name." = ".$r."<br>";
			$new_array[$k] = $r;
		}
		$html .= "";
		$new_array['data'] = $html;

		echo json_encode($new_array);
		// $this->notification();
	}
	
	function update_data() {
		$id = $this->input->get("id");
		
		// $id_cashtransit		= strtoupper(trim($this->input->post('id_cashtransit')));
		// $run_number			= strtoupper(trim($this->input->post('run_number')));
		// $datas				= serialize($this->input->post());
		
		// $data['id_cashtransit'] = $id_cashtransit;
		// $data['run_number'] = $run_number;
		// $data['data'] = $datas;
		
		// // $this->db->trans_start();

		// // $this->db->where('id', $id);
		// // $this->db->update('runsheet_logistic', $data);

		// // $this->db->trans_complete();
		
		// $datas = unserialize($datas);
		
		// $i=0;
		// $html = "";
		// $new_array = array(
			// 'id_cashtransit' => $id_cashtransit,
			// 'run_number' => $run_number,
			// 'data' => $html
		// );
		// foreach($datas as $k => $r) {
			// $html .= "".$this->db->query("SELECT name FROM inventory WHERE id='".$k."'")->row()->name." = ".$r."<br>";
			// $new_array[$k] = $r;
		// }
		// $html .= "";

		// echo json_encode($new_array);
	}
	
	function delete_data() {
		$data['id'] = $_POST['id'];

		$table = "runsheet_logistic";
		$res = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
	
	function notification() {
		define('AUTHORIZATION_KEY', 'AAAAnLcRlWk:APA91bF0J5GW72L1GeWozjX8LNSSq5usc8twk0mUL53izRdPK9HiVz_yZziH7XUq_rXizKFk_bEv26FeQaRFe3YY5sPIpeTaV23CA1-s2kfPFYhdIp4ZzyaviG3Iv59uJW-pHb6-pZSI');

		$_REQUEST['to'] = "e3pOeo9mGPI:APA91bGvMUdzQVVNV3cpoZ2rOqf3p2i8W4WAPu8dAJaeFYYmbWilHMoTZp-JiTGJ0tA0Tc51Z1uO5gFJhLzsvR8PX80TQFDvw0UPQKQyP87D4ft9EGyO4tvXOpD3P32EOTMas-wuuZrZ";

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
}