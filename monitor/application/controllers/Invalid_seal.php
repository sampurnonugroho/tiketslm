<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Invalid_seal extends CI_Controller {
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
		$this->data['active_menu'] = "invalid_seal";
		
        $query = "SELECT *, cashtransit_detail.id as id_ct, runsheet_operational.run_number as run_number FROM cashtransit_detail_problem 
					LEFT JOIN cashtransit_detail ON(cashtransit_detail.id=cashtransit_detail_problem.id)
					LEFT JOIN client ON(client.id=cashtransit_detail.id_bank)
					LEFT JOIN runsheet_operational ON(runsheet_operational.id_cashtransit=cashtransit_detail.id_cashtransit)";
		$this->data['data_invalid'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($this->data['data_invalid']);
		
        return view('admin/invalid_seal/index', $this->data);
	}
	
	public function process() {
		$id = $this->uri->segment(3);
		$runsheet = $this->uri->segment(4);
		$query = "UPDATE `cashtransit_detail` SET `fraud_indicated`='1' WHERE id='$id'";
		$this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10));
		
		$query = "SELECT * FROM runsheet_operational LEFT JOIN user ON(runsheet_operational.custodian_1=user.username) WHERE runsheet_operational.run_number='".$runsheet."'";
		$token = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->token;
		
		// echo $query;
		
		$this->notification($token);
		
		redirect('invalid_seal');
	}
	
	function notification($token) {
		define('AUTHORIZATION_KEY', 'AAAAnLcRlWk:APA91bF0J5GW72L1GeWozjX8LNSSq5usc8twk0mUL53izRdPK9HiVz_yZziH7XUq_rXizKFk_bEv26FeQaRFe3YY5sPIpeTaV23CA1-s2kfPFYhdIp4ZzyaviG3Iv59uJW-pHb6-pZSI');

		$_REQUEST['to'] = $token;

		$title = "ACCEPTED FRAUD PROBLEM";
		$body = "fraud indicated has been accepted by duty";

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