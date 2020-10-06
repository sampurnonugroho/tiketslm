<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Flm extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function dashboard_get() {
		$id_user = $this->input->get('id_user');
		$id_teknisi = $this->db->select('id_teknisi')->from('teknisi')->where('nik', $id_user)->limit(1)->get()->row()->id_teknisi;
		
		// COUNT INCOMING JOB
		$query_flm = $this->db->query("SELECT * FROM 
		(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket
		WHERE teknisi_1='$id_teknisi' AND accept_time IS NULL")->num_rows();
		$query_slm = $this->db->query("SELECT * FROM 
		(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket_slm) AS flm_trouble_ticket_slm
		WHERE teknisi_1='$id_teknisi' AND accept_time IS NULL")->num_rows();
		
		// COUNT ACCEPTED JOB
		$count_flm = $this->db->query("SELECT * FROM 
		(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket
		WHERE teknisi_1='$id_teknisi' AND data_solve=''")->num_rows();
		$count_slm = $this->db->query("SELECT * FROM 
		(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket_slm) AS flm_trouble_ticket_slm
		WHERE teknisi_1='$id_teknisi' AND data_solve=''")->num_rows();
		$query = array(
			"incoming" => $query_flm+$query_slm,
			"flm" => $count_flm,
			"slm" => $count_slm,
		);
		echo json_encode($query);
	}
	
	function get_data_flm_get() {
		$id_user = $this->input->get('id_user');
		$id_teknisi = $this->db->select('id_teknisi')->from('teknisi')->where('nik', $id_user)->limit(1)->get()->row()->id_teknisi;
		$list = array();
		$key=0;
		
		
		$query_flm = $this->db->query("SELECT * FROM 
		(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket
		WHERE teknisi_1='$id_teknisi' AND accept_time IS NULL")->result_array();
		foreach($query_flm as $r) {
			$list[$key]['action'] = "FLM";
			$list[$key]['id'] 				= $r['id'];
			$list[$key]['id_ticket'] 		= $r['id_ticket'];
			$list[$key]['ticket_client'] 	= $r['ticket_client'];
			$list[$key]['id_bank'] 			= $r['id_bank'];
			$list[$key]['vendor'] 			= $r['vendor'];
			$list[$key]['problem_type'] 	= $r['problem_type'];
			$list[$key]['entry_date'] 		= $r['entry_date'];
			$list[$key]['email_date'] 		= $r['email_date'];
			$list[$key]['time'] 			= $r['time'];
			$list[$key]['down_time'] 		= $r['down_time'];
			$list[$key]['accept_time'] 		= $r['accept_time'];
			$list[$key]['run_time'] 		= $r['run_time'];
			$list[$key]['action_time'] 		= $r['action_time'];
			$list[$key]['arrival_date'] 	= $r['arrival_date'];
			$list[$key]['start_scan'] 		= $r['start_scan'];
			$list[$key]['end_apply'] 		= $r['end_apply'];
			$list[$key]['teknisi_1'] 		= $r['teknisi_1'];
			$list[$key]['teknisi_2'] 		= $r['teknisi_2'];
			$list[$key]['guard'] 			= $r['guard'];
			$list[$key]['status'] 			= $r['status'];
			$list[$key]['data_solve'] 		= $r['data_solve'];
			$list[$key]['images1'] 			= $r['images1'];
			$list[$key]['images2'] 			= $r['images2'];
			$list[$key]['images3'] 			= $r['images3'];
			$list[$key]['images4'] 			= $r['images4'];
			$list[$key]['images5'] 			= $r['images5'];
			$list[$key]['receipt_roll'] 	= $r['receipt_roll'];
			$list[$key]['picture_mesin'] 	= $r['picture_mesin'];
			$list[$key]['picture_booth'] 	= $r['picture_booth'];
			$list[$key]['req_combi'] 		= $r['req_combi'];
			$list[$key]['updated'] 			= $r['updated '];
			
			$key++;
		}
		
		$query_slm = $this->db->query("SELECT * FROM 
		(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket_slm) AS flm_trouble_ticket_slm	
		WHERE teknisi_1='$id_teknisi' AND accept_time IS NULL")->result_array();
		foreach($query_slm as $r) {
			$list[$key]['action'] = "SLM";
			$list[$key]['id'] 				= $r['id'];
			$list[$key]['id_ticket'] 		= $r['id_ticket'];
			$list[$key]['ticket_client'] 	= $r['ticket_client'];
			$list[$key]['id_bank'] 			= $r['id_bank'];
			$list[$key]['vendor'] 			= $r['vendor'];
			$list[$key]['problem_type'] 	= $r['problem_type'];
			$list[$key]['entry_date'] 		= $r['entry_date'];
			$list[$key]['email_date'] 		= $r['email_date'];
			$list[$key]['time'] 			= $r['time'];
			$list[$key]['down_time'] 		= $r['down_time'];
			$list[$key]['accept_time'] 		= $r['accept_time'];
			$list[$key]['run_time'] 		= $r['run_time'];
			$list[$key]['action_time'] 		= $r['action_time'];
			$list[$key]['arrival_date'] 	= $r['arrival_date'];
			$list[$key]['start_scan'] 		= $r['start_scan'];
			$list[$key]['end_apply'] 		= $r['end_apply'];
			$list[$key]['teknisi_1'] 		= $r['teknisi_1'];
			$list[$key]['teknisi_2'] 		= $r['teknisi_2'];
			$list[$key]['guard'] 			= $r['guard'];
			$list[$key]['status'] 			= $r['status'];
			$list[$key]['data_solve'] 		= $r['data_solve'];
			$list[$key]['images1'] 			= $r['images1'];
			$list[$key]['images2'] 			= $r['images2'];
			$list[$key]['images3'] 			= $r['images3'];
			$list[$key]['images4'] 			= $r['images4'];
			$list[$key]['images5'] 			= $r['images5'];
			$list[$key]['receipt_roll'] 	= $r['receipt_roll'];
			$list[$key]['picture_mesin'] 	= $r['picture_mesin'];
			$list[$key]['picture_booth'] 	= $r['picture_booth'];
			$list[$key]['req_combi'] 		= $r['req_combi'];
			$list[$key]['updated'] 			= $r['updated '];
			
			$key++;
		}
		
		echo json_encode($query_flm);
	}
	
	function get_data_current_get() {
		$id_user = $this->input->get('id_user');
		$id_teknisi = $this->db->select('id_teknisi')->from('teknisi')->where('nik', $id_user)->limit(1)->get()->row()->id_teknisi;
		$list = array();
		$key=0;
		
		$query_flm = $this->db->query("SELECT * FROM 
		(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket
		WHERE teknisi_1='$id_teknisi' AND accept_time IS NOT NULL AND data_solve=''")->result_array();
		foreach($query_flm as $r) {
			$list[$key]['action'] = "FLM";
			$list[$key]['id'] 				= $r['id'];
			$list[$key]['id_ticket'] 		= $r['id_ticket'];
			$list[$key]['ticket_client'] 	= $r['ticket_client'];
			$list[$key]['id_bank'] 			= $r['id_bank'];
			$list[$key]['vendor'] 			= $r['vendor'];
			$list[$key]['problem_type'] 	= $r['problem_type'];
			$list[$key]['entry_date'] 		= $r['entry_date'];
			$list[$key]['email_date'] 		= $r['email_date'];
			$list[$key]['time'] 			= $r['time'];
			$list[$key]['down_time'] 		= $r['down_time'];
			$list[$key]['accept_time'] 		= $r['accept_time'];
			$list[$key]['run_time'] 		= $r['run_time'];
			$list[$key]['action_time'] 		= $r['action_time'];
			$list[$key]['arrival_date'] 	= $r['arrival_date'];
			$list[$key]['start_scan'] 		= $r['start_scan'];
			$list[$key]['end_apply'] 		= $r['end_apply'];
			$list[$key]['teknisi_1'] 		= $r['teknisi_1'];
			$list[$key]['teknisi_2'] 		= $r['teknisi_2'];
			$list[$key]['guard'] 			= $r['guard'];
			$list[$key]['status'] 			= $r['status'];
			$list[$key]['data_solve'] 		= $r['data_solve'];
			$list[$key]['foto_selfie'] 		= $r['foto_selfie'];
			$list[$key]['images1'] 			= $r['images1'];
			$list[$key]['images2'] 			= $r['images2'];
			$list[$key]['images3'] 			= $r['images3'];
			$list[$key]['images4'] 			= $r['images4'];
			$list[$key]['images5'] 			= $r['images5'];
			$list[$key]['receipt_roll'] 	= $r['receipt_roll'];
			$list[$key]['picture_mesin'] 	= $r['picture_mesin'];
			$list[$key]['picture_booth'] 	= $r['picture_booth'];
			$list[$key]['req_combi'] 		= $r['req_combi'];
			$list[$key]['updated'] 			= $r['updated '];
			
			$key++;
		}
		
		$query_slm = $this->db->query("SELECT * FROM 
		(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket_slm) AS flm_trouble_ticket_slm
		WHERE teknisi_1='$id_teknisi' AND accept_time IS NOT NULL AND data_solve=''")->result_array();
		foreach($query_slm as $r) {
			$list[$key]['action'] = "SLM";
			$list[$key]['id'] 				= $r['id'];
			$list[$key]['id_ticket'] 		= $r['id_ticket'];
			$list[$key]['ticket_client'] 	= $r['ticket_client'];
			$list[$key]['id_bank'] 			= $r['id_bank'];
			$list[$key]['vendor'] 			= $r['vendor'];
			$list[$key]['problem_type'] 	= $r['problem_type'];
			$list[$key]['entry_date'] 		= $r['entry_date'];
			$list[$key]['email_date'] 		= $r['email_date'];
			$list[$key]['time'] 			= $r['time'];
			$list[$key]['down_time'] 		= $r['down_time'];
			$list[$key]['accept_time'] 		= $r['accept_time'];
			$list[$key]['run_time'] 		= $r['run_time'];
			$list[$key]['action_time'] 		= $r['action_time'];
			$list[$key]['arrival_date'] 	= $r['arrival_date'];
			$list[$key]['start_scan'] 		= $r['start_scan'];
			$list[$key]['end_apply'] 		= $r['end_apply'];
			$list[$key]['teknisi_1'] 		= $r['teknisi_1'];
			$list[$key]['teknisi_2'] 		= $r['teknisi_2'];
			$list[$key]['guard'] 			= $r['guard'];
			$list[$key]['status'] 			= $r['status'];
			$list[$key]['data_solve'] 		= $r['data_solve'];
			$list[$key]['foto_selfie'] 		= $r['foto_selfie'];
			$list[$key]['images1'] 			= $r['images1'];
			$list[$key]['images2'] 			= $r['images2'];
			$list[$key]['images3'] 			= $r['images3'];
			$list[$key]['images4'] 			= $r['images4'];
			$list[$key]['images5'] 			= $r['images5'];
			$list[$key]['receipt_roll'] 	= $r['receipt_roll'];
			$list[$key]['picture_mesin'] 	= $r['picture_mesin'];
			$list[$key]['picture_booth'] 	= $r['picture_booth'];
			$list[$key]['req_combi'] 		= $r['req_combi'];
			$list[$key]['updated'] 			= $r['updated '];
			
			$key++;
		}
		echo json_encode($list);
	}
	
	function dataflm_get() {
		$id_user = $this->input->get('id_user');
		
		
		$sql = "SELECT *, client.sektor as ga, client.vendor as brand, client.type_mesin as model FROM 
			(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket
			LEFT JOIN client ON(client.id=flm_trouble_ticket.id_bank)
			LEFT JOIN master_branch as branch ON(branch.id=client.cabang)
			WHERE teknisi_1='$id_user' OR accept_time IS NULL
		";
		
		$query = $this->db->query($sql)->result_array();
		
		$list = array();
		$key=0;
		foreach($query as $r) {
			$list[$key]['action'] = "FLM";
			$list[$key]['id'] = $r['id_ticket'];
			$list[$key]['wsid'] = $r['wsid'];
			$list[$key]['id_bank'] = $r['id_bank'];
			$list[$key]['ga'] = $r['ga'];
			$list[$key]['branch'] = $r['name'];
			$list[$key]['bank'] = $r['bank'];
			$list[$key]['act'] = $r['type'];
			$list[$key]['brand'] = $r['brand'];
			$list[$key]['model'] = $r['model'];
			$list[$key]['lokasi'] = $r['lokasi'];
			
			// $problem = array();
			// foreach(json_decode($r['problem_type']) as $p) {
				// $problem[] = $this->db->select('nama_sub_kategori')->from('sub_kategori')->where('id_sub_kategori', $p)->limit(1)->get()->row()->nama_sub_kategori;
			// }
			
			$problem = '<ol>';
			$problem .= '<li>' . implode('</li><li>', explode(", ", $r['problem_type'])).'</li>';
			$problem .= '</ol>';
			
			$list[$key]['problem_type'] = $problem;
			$list[$key]['time'] = $r['time'];
			$key++;
		}
		
		$sql = "SELECT *, client.sektor as ga, client.vendor as brand, client.type_mesin as model FROM 
			(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket_slm) AS flm_trouble_ticket_slm
			LEFT JOIN client ON(client.id=flm_trouble_ticket_slm.id_bank)
			LEFT JOIN master_branch as branch ON(branch.id=client.cabang)
			WHERE teknisi_1='$id_user' OR accept_time IS NULL
		";
		
		$query = $this->db->query($sql)->result_array();
		
		foreach($query as $r) {
			$list[$key]['action'] = "SLM";
			$list[$key]['id'] = $r['id_ticket'];
			$list[$key]['wsid'] = $r['wsid'];
			$list[$key]['id_bank'] = $r['id_bank'];
			$list[$key]['ga'] = $r['ga'];
			$list[$key]['branch'] = $r['name'];
			$list[$key]['bank'] = $r['bank'];
			$list[$key]['act'] = $r['type'];
			$list[$key]['brand'] = $r['brand'];
			$list[$key]['model'] = $r['model'];
			$list[$key]['lokasi'] = $r['lokasi'];
			
			$problem = '<ol>';
			$problem .= '<li>' . implode('</li><li>', explode(", ", $r['problem_type'])).'</li>';
			$problem .= '</ol>';
			
			$list[$key]['problem_type'] = $problem;
			$list[$key]['time'] = $r['time'];
			$key++;
		}
		
		$result['data'] = $list;
		
		echo json_encode($result);
	}
	
	function acceptjob_get() {
		$id_ticket = $this->input->get('id_ticket');
		$action = $this->input->get('action');
		
		
		if($action=="FLM") {
			$data['accept_time'] = date("Y-m-d H:i:s");
			$data['updated'] = 'true';
				
			$this->db->where('id_ticket', $id_ticket);
			$update = $this->db->update('flm_trouble_ticket', $data);
			
			if ($update) {
				$sql = "SELECT *, client.sektor as ga, client.vendor as brand, client.type_mesin as model FROM 
					(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket
					LEFT JOIN client ON(client.id=flm_trouble_ticket.id_bank)
					LEFT JOIN master_branch as branch ON(branch.id=client.cabang)
					WHERE id_ticket='$id_ticket'
				";
				
				$query = $this->db->query($sql)->result_array();
				
				$list = array();
				$key=0;
				foreach($query as $r) {
					$list[$key]['id'] = $r['id_ticket'];
					$list[$key]['id_bank'] = $r['id_bank'];
					$list[$key]['ga'] = $r['ga'];
					$list[$key]['branch'] = $r['name'];
					$list[$key]['bank'] = $r['bank'];
					$list[$key]['act'] = $r['type'];
					$list[$key]['brand'] = $r['brand'];
					$list[$key]['model'] = $r['model'];
					$list[$key]['lokasi'] = $r['lokasi'];
					
					$problem1 = array();
					$problem1[] = $this->db->query("SELECT nama_kategori FROM `kategori` LEFT JOIN sub_kategori ON(sub_kategori.id_kategori=kategori.id_kategori) WHERE nama_sub_kategori='".$r['problem_type']."'")->row()->nama_kategori;
					
					$problem = '<ol>';
					$problem .= '<li>' . implode('</li><li>', explode(", ", $r['problem_type'])).'</li>';
					$problem .= '</ol>';
					
					$list[$key]['problem_type'] = $problem;
					$list[$key]['problem_kategori'] = implode(', ', $problem1);
					$list[$key]['time'] = $r['time'];
					$key++;
				}
				
				$result['data'] = $list;
				
				echo json_encode($list);
			} 
		} else if($action=="SLM") {
			
			$data['accept_time'] = date("Y-m-d H:i:s");
			$data['updated'] = 'true';
				
			$this->db->where('id_ticket', $id_ticket);
			$update = $this->db->update('flm_trouble_ticket_slm', $data);
			
			if ($update) {
				$sql = "SELECT *, client.sektor as ga, client.vendor as brand, client.type_mesin as model FROM 
					(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket_slm) AS flm_trouble_ticket_slm
					LEFT JOIN client ON(client.id=flm_trouble_ticket_slm.id_bank)
					LEFT JOIN master_branch as branch ON(branch.id=client.cabang)
					WHERE id_ticket='$id_ticket'
				";
				
				$query = $this->db->query($sql)->result_array();
				
				$list = array();
				$key=0;
				foreach($query as $r) {
					$list[$key]['id'] = $r['id_ticket'];
					$list[$key]['id_bank'] = $r['id_bank'];
					$list[$key]['ga'] = $r['ga'];
					$list[$key]['branch'] = $r['name'];
					$list[$key]['bank'] = $r['bank'];
					$list[$key]['act'] = $r['type'];
					$list[$key]['brand'] = $r['brand'];
					$list[$key]['model'] = $r['model'];
					$list[$key]['lokasi'] = $r['lokasi'];
					
					$problem1 = array();
					$problem1[] = $this->db->query("SELECT nama_kategori FROM `kategori` LEFT JOIN sub_kategori ON(sub_kategori.id_kategori=kategori.id_kategori) WHERE nama_sub_kategori='".$r['problem_type']."'")->row()->nama_kategori;
					
					$problem = '<ol>';
					$problem .= '<li>' . implode('</li><li>', explode(", ", $r['problem_type'])).'</li>';
					$problem .= '</ol>';
					
					$list[$key]['problem_type'] = $problem;
					$list[$key]['problem_kategori'] = implode(', ', $problem1);
					$list[$key]['time'] = $r['time'];
					$key++;
				}
				
				$result['data'] = $list;
				
				echo json_encode($list);
			} 
		}
	}
	
	function acceptedjoblist_get() {
		$id_user = $this->input->get('id_user');
		
		$id_teknisi = $this->db->select('id_teknisi')->from('teknisi')->where('nik', $id_user)->limit(1)->get()->row()->id_teknisi;
		
		
		$sql = "SELECT *, flm_trouble_ticket.id as id1, client.sektor as ga, client.vendor as brand, client.type_mesin as model, client.data_location FROM 	
				(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket
				LEFT JOIN client ON(client.id=flm_trouble_ticket.id_bank)
				LEFT JOIN master_branch as branch ON(branch.id=client.cabang)
				WHERE teknisi_1='$id_teknisi' AND accept_time IS NOT NULL AND data_solve=''
			";
		
		$query = $this->db->query($sql)->result_array();
			
		$list = array();
		$key=0;
		foreach($query as $r) {
			$list[$key]['action'] = "FLM";
			$list[$key]['id1'] = $r['id1'];
			$list[$key]['wsid'] = $r['wsid'];
			$list[$key]['id'] = $r['id_ticket'];
			$list[$key]['id_bank'] = $r['id_bank'];
			$list[$key]['data_location'] = $r['data_location'];
			$list[$key]['ga'] = $r['ga'];
			$list[$key]['branch'] = $r['name'];
			$list[$key]['bank'] = $r['bank'];
			$list[$key]['act'] = $r['type'];
			$list[$key]['brand'] = $r['brand'];
			$list[$key]['model'] = $r['model'];
			$list[$key]['lokasi'] = $r['lokasi'];
			
			$problem = '<ol>';
			$problem .= '<li>' . implode('</li><li>', explode(", ", $r['problem_type'])).'</li>';
			$problem .= '</ol>';
			
			$list[$key]['problem_type'] = $problem;
			$list[$key]['time'] = $r['time'];
			$key++;
		}
		
		$sql = "SELECT *, flm_trouble_ticket_slm.id as id1, client.sektor as ga, client.vendor as brand, client.type_mesin as model, client.data_location FROM 	
				(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket_slm) AS flm_trouble_ticket_slm
				LEFT JOIN client ON(client.id=flm_trouble_ticket_slm.id_bank)
				LEFT JOIN master_branch as branch ON(branch.id=client.cabang)
				WHERE teknisi_1='$id_teknisi' AND accept_time IS NOT NULL AND data_solve=''
			";
		
		$query = $this->db->query($sql)->result_array();
			
		foreach($query as $r) {
			$list[$key]['action'] = "SLM";
			$list[$key]['id1'] = $r['id1'];
			$list[$key]['wsid'] = $r['wsid'];
			$list[$key]['id'] = $r['id_ticket'];
			$list[$key]['id_bank'] = $r['id_bank'];
			$list[$key]['data_location'] = $r['data_location'];
			$list[$key]['ga'] = $r['ga'];
			$list[$key]['branch'] = $r['name'];
			$list[$key]['bank'] = $r['bank'];
			$list[$key]['act'] = $r['type'];
			$list[$key]['brand'] = $r['brand'];
			$list[$key]['model'] = $r['model'];
			$list[$key]['lokasi'] = $r['lokasi'];
			
			$problem = '<ol>';
			$problem .= '<li>' . implode('</li><li>', explode(", ", $r['problem_type'])).'</li>';
			$problem .= '</ol>';
			
			$list[$key]['problem_type'] = $problem;
			$list[$key]['time'] = $r['time'];
			$key++;
		}
		
		$result['data'] = $list;
		
		echo json_encode($list);
	}
	
	function getacceptedjob_get() {
		$id_ticket = $this->input->get('id_ticket');
		$action = $this->input->get('action');
		
		
		
		if($action=="FLM") { 
			$data['arrival_date'] = date("Y-m-d H:i:s");
			$data['start_scan'] = date("Y-m-d H:i:s");
			$data['updated'] = 'true';
			
			$this->db->where('id_ticket', $id_ticket);
			$update = $this->db->update('flm_trouble_ticket', $data);
			if ($update) {
				$sql = "SELECT *, client.sektor as ga, client.vendor as brand, client.type_mesin as model FROM 
					(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket
					LEFT JOIN client ON(client.id=flm_trouble_ticket.id_bank)
					LEFT JOIN master_branch as branch ON(branch.id=client.cabang)
					WHERE flm_trouble_ticket.id_ticket='$id_ticket'
				";
					
				$query = $this->db->query($sql)->result_array();
					
				$list = array();
				$key=0;
				foreach($query as $r) {
					$list[$key]['id'] = $r['id_ticket'];
					$list[$key]['id_bank'] = $r['id_bank'];
					$list[$key]['ga'] = $r['ga'];
					$list[$key]['branch'] = $r['name'];
					$list[$key]['bank'] = $r['bank'];
					$list[$key]['act'] = $r['type'];
					$list[$key]['brand'] = $r['brand'];
					$list[$key]['model'] = $r['model'];
					$list[$key]['lokasi'] = $r['lokasi'];
					
					
					
					$problem1 = array();
					$problem1[] = $this->db->query("SELECT nama_kategori FROM `kategori` LEFT JOIN sub_kategori ON(sub_kategori.id_kategori=kategori.id_kategori) WHERE nama_sub_kategori='".$r['problem_type']."'")->row()->nama_kategori;
					
					$problem = '<ol>';
					$problem .= '<li>' . implode('</li><li>', explode(", ", $r['problem_type'])).'</li>';
					$problem .= '</ol>';
					
					$list[$key]['problem_type'] = $problem;
					$list[$key]['problem_kategori'] = implode(', ', $problem1);
					$list[$key]['time'] = $r['time'];
					$key++;
				}
				
				$result['data'] = $list;
				
				echo json_encode($list);
			} 
		} else if($action=="SLM") { 
			$data['arrival_date'] = date("Y-m-d H:i:s");
			$data['start_scan'] = date("Y-m-d H:i:s");
			$data['updated'] = 'true';
			
			$this->db->where('id_ticket', $id_ticket);
			$update = $this->db->update('flm_trouble_ticket_slm', $data);
			if ($update) {
				$sql = "SELECT *, client.sektor as ga, client.vendor as brand, client.type_mesin as model FROM 
					(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket_slm) AS flm_trouble_ticket_slm
					LEFT JOIN client ON(client.id=flm_trouble_ticket_slm.id_bank)
					LEFT JOIN master_branch as branch ON(branch.id=client.cabang)
					WHERE flm_trouble_ticket_slm.id_ticket='$id_ticket'
				";
					
				$query = $this->db->query($sql)->result_array();
					
				$list = array();
				$key=0;
				foreach($query as $r) {
					$list[$key]['id'] = $r['id_ticket'];
					$list[$key]['id_bank'] = $r['id_bank'];
					$list[$key]['ga'] = $r['ga'];
					$list[$key]['branch'] = $r['name'];
					$list[$key]['bank'] = $r['bank'];
					$list[$key]['act'] = $r['type'];
					$list[$key]['brand'] = $r['brand'];
					$list[$key]['model'] = $r['model'];
					$list[$key]['lokasi'] = $r['lokasi'];
					
					
					
					$problem1 = array();
					$problem1[] = $this->db->query("SELECT nama_kategori FROM `kategori` LEFT JOIN sub_kategori ON(sub_kategori.id_kategori=kategori.id_kategori) WHERE nama_sub_kategori='".$r['problem_type']."'")->row()->nama_kategori;
					
					$problem = '<ol>';
					$problem .= '<li>' . implode('</li><li>', explode(", ", $r['problem_type'])).'</li>';
					$problem .= '</ol>';
					
					$list[$key]['problem_type'] = $problem;
					$list[$key]['problem_kategori'] = implode(', ', $problem1);
					$list[$key]['time'] = $r['time'];
					$key++;
				}
				
				$result['data'] = $list;
				
				echo json_encode($list);
			} 
		}
	}
	
	function scanqrcode_get() {
		$id_ticket = $this->input->get('id_ticket');
		
		$sql = "SELECT *, client.sektor as ga, client.vendor as brand, client.type_mesin as model FROM 
			(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket
			LEFT JOIN client ON(client.id=flm_trouble_ticket.id_bank)
			LEFT JOIN master_branch as branch ON(branch.id=client.cabang)
			WHERE id_ticket='$id_ticket'
		";
		
		$query = $this->db->query($sql)->result_array();
		
		$list = array();
		$key=0;
		foreach($query as $r) {
			$list[$key]['id'] = $r['id_ticket'];
			$list[$key]['id_bank'] = $r['id_bank'];
			$list[$key]['wsid'] = $r['wsid'];
			$list[$key]['ga'] = $r['ga'];
			$list[$key]['branch'] = $r['name'];
			$list[$key]['bank'] = $r['bank'];
			$list[$key]['act'] = $r['type'];
			$list[$key]['brand'] = $r['brand'];
			$list[$key]['model'] = $r['model'];
			$list[$key]['lokasi'] = $r['lokasi'];
			
			$problem1 = array();
			$problem1[] = $this->db->query("SELECT nama_kategori FROM `kategori` LEFT JOIN sub_kategori ON(sub_kategori.id_kategori=kategori.id_kategori) WHERE nama_sub_kategori='".$r['problem_type']."'")->row()->nama_kategori;
			
			$problem = '<ol>';
			$problem .= '<li>' . implode('</li><li>', explode(", ", $r['problem_type'])).'</li>';
			$problem .= '</ol>';
			
			$list[$key]['problem_type'] = $problem;
			$list[$key]['problem_kategori'] = implode(', ', $problem1);
			$list[$key]['time'] = $r['time'];
			$key++;
		}
		
		$result['data'] = $list;
		
		echo json_encode($list);
	}
	
	function scanqrcode2_get() {
		$id_ticket = $this->input->get('id_ticket');
		
		$data['arrival_date'] = date("Y-m-d H:i:s");
		$data['start_scan'] = date("Y-m-d H:i:s");
		$data['updated'] = 'true';
		
		$this->db->where('id_ticket', $id_ticket);
        $update = $this->db->update('flm_trouble_ticket', $data);
		
		if ($update) {
			$sql = "SELECT *, client.sektor as ga, client.vendor as brand, client.type_mesin as model FROM 
				(SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket
				LEFT JOIN client ON(client.id=flm_trouble_ticket.id_bank)
				LEFT JOIN master_branch as branch ON(branch.id=client.cabang)
				WHERE id_ticket='$id_ticket'
			";
			
			$query = $this->db->query($sql)->result_array();
			
			$list = array();
			$key=0;
			foreach($query as $r) {
				$list[$key]['id'] = $r['id_ticket'];
				$list[$key]['id_bank'] = $r['id_bank'];
				$list[$key]['wsid'] = $r['wsid'];
				$list[$key]['ga'] = $r['ga'];
				$list[$key]['branch'] = $r['name'];
				$list[$key]['bank'] = $r['bank'];
				$list[$key]['act'] = $r['type'];
				$list[$key]['brand'] = $r['brand'];
				$list[$key]['model'] = $r['model'];
				$list[$key]['lokasi'] = $r['lokasi'];
				
				$problem = array();
				foreach(json_decode($r['problem_type']) as $p) {
					$problem[] = $this->db->select('nama_sub_kategori')->from('sub_kategori')->where('id_sub_kategori', $p)->limit(1)->get()->row()->nama_sub_kategori;
				}
				$list[$key]['problem_type'] = implode(', ', $problem);;
				$list[$key]['time'] = $r['time'];
				$key++;
			}
			
			$result['data'] = $list;
			
			echo json_encode($list);
		} 
	}
	
	function save_image_get() {
		$id_ticket = $this->input->get('id_ticket');
		
		$data['images'] = $this->input->get('images');
		
		$this->db->where('id_ticket', $id_ticket);
        $update = $this->db->update('flm_trouble_ticket', $data);
		
		if ($update) {
			$result['data'] = "success";
		} else {
			$result['error'] = $database->error();
		}
	}
	
	function save_image2_get() {
		$id_ticket = $this->input->get('id_ticket');
		
		$data['images2'] = $this->input->get('images2');
		
		$this->db->where('id_ticket', $id_ticket);
        $update = $this->db->update('flm_trouble_ticket', $data);
		
		if ($update) {
			$result['data'] = "success";
		} else {
			$result['error'] = $database->error();
		}
	}
	
	function savefinishing_get() {
		$id_ticket = $this->input->get('id_ticket');
		$action = $this->input->get('action');
		$status = $this->input->get('status');
		$data_solve = $this->input->get('data_solve');
		
		
		if($action=="FLM") {
			$data['end_apply'] = date("Y-m-d H:i:s");
			$data['updated'] = 'true';
			$data['status'] = $status;
			$data['data_solve'] = $data_solve;
			
			$this->db->where('id_ticket', $id_ticket);
			$update = $this->db->update('flm_trouble_ticket', $data);
			
			if ($update) {
				$result['data'] = "success";
			} else {
				$result['error'] = $database->error();
			}
		} else if($action=="SLM") {
			$data['end_apply'] = date("Y-m-d H:i:s");
			$data['updated'] = 'true';
			$data['status'] = $status;
			$data['data_solve'] = $data_solve;
			
			$this->db->where('id_ticket', $id_ticket);
			$update = $this->db->update('flm_trouble_ticket_slm', $data);
			
			if ($update) {
				$result['data'] = "success";
			} else {
				$result['error'] = $database->error();
			}
		}
	}
	
	function save_image_post() {
		$id = $this->input->post('id');
		$field = $this->input->post('field');
		$picture = $this->input->post('picture');
		
		$data[$field] = $picture;
		
		$this->db->where('id', $id);
        $update = $this->db->update('flm_trouble_ticket', $data);
		
		if ($update) {
			$result['data'] = "success";
		} else {
			$result['data'] = "failed";
		}
		
		echo json_encode($result);
	}
	
	function request_combi_get() {
		$wsid = $this->input->get('wsid');
		$id_detail = $this->input->get('id_detail');
		
		$query = "SELECT combination FROM combi_lock WHERE wsid='$wsid'";
		$combi = $this->db->query($query)->row();
		
		$count_request = $this->db->query("SELECT req_combi FROM flm_trouble_ticket WHERE id='$id_detail'")->row();
		// echo $count_request->req_combi;
		if($count_request->req_combi<2) {
			// echo $combi->combination;
			
			$data = array('req_combi' => $count_request->req_combi+1);
			$this->db->where('id', $id_detail);
			$update = $this->db->update('flm_trouble_ticket', $data);
			
			echo json_encode(array('combi'=>$combi->combination, 'count'=>(intval($count_request->req_combi)+1)));
		} else {
			// echo "ANDA TELAH MENCAPAI MAKSIMUM REQUEST COMBINATION LOCK";
			echo "invalid";
		}
	}
	
	function syncronize_get() {
		$data_plan = json_decode($_REQUEST['data_plan'], true);
		// echo "<pre>";
		// print_r($data_plan);
		
		foreach($data_plan as $r) {
			$data = array();
			if($r['status']!="")		{ $data['status']			= $r['status']; }
			if($r['data_solve']!="")	{ $data['data_solve']		= $r['data_solve']; }
			if($r['foto_selfie']!="")	{ $data['foto_selfie']		= $r['foto_selfie']; }
			if($r['images1']!="")		{ $data['images1']			= $r['images1']; }
			if($r['images2']!="")		{ $data['images2']			= $r['images2']; }
			if($r['images3']!="")		{ $data['images3']			= $r['images3']; }
			if($r['images4']!="")		{ $data['images4']			= $r['images4']; }
			if($r['images5']!="")		{ $data['images5']			= $r['images5']; }
			if($r['receipt_roll']!="")	{ $data['receipt_roll']		= $r['receipt_roll']; }
			if($r['picture_mesin']!="")	{ $data['picture_mesin']	= $r['picture_mesin']; }
			if($r['picture_booth']!="")	{ $data['picture_booth']	= $r['picture_booth']; }
			
			if($r['action']=="FLM") {
				$this->db->where('id', $r['id']);
				$update = $this->db->update('flm_trouble_ticket', $data);
			} else if($r['action']=="SLM") {
				$this->db->where('id', $r['id']);
				$update = $this->db->update('flm_trouble_ticket_slm', $data);
			}
		}
	}
}