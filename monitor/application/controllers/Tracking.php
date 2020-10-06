<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tracking extends CI_Controller {
	var $data = array();
	
	public function __construct() {
        parent::__construct();
		$this->load->library('curl');
	}
	
	public function index() {
		$this->data['active_menu'] = "tracking";
		
		$query = "	SELECT 
						* 
						FROM user
							LEFT JOIN karyawan ON(user.username=karyawan.nik) WHERE user.latlng!=''";	
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['result'] = $result;
		
		return view('admin/tracking/index', $this->data);
	}
	
	public function tes() {
		$id = $this->input->post('id');
		
		$query = "	SELECT 
						* 
						FROM teknisi
							LEFT JOIN karyawan ON(teknisi.nik=karyawan.nik)
							LEFT JOIN user ON(teknisi.nik=user.username)
					WHERE user.username='$id'";	
		
		$query = "	SELECT 
						* 
						FROM user
							LEFT JOIN karyawan ON(user.username=karyawan.nik) WHERE user.username='$id' AND user.latlng!=''";	
		$row = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->notification($row->token);
	}
	
	function notification($token) {
		define('AUTHORIZATION_KEY', 'AAAAnLcRlWk:APA91bF0J5GW72L1GeWozjX8LNSSq5usc8twk0mUL53izRdPK9HiVz_yZziH7XUq_rXizKFk_bEv26FeQaRFe3YY5sPIpeTaV23CA1-s2kfPFYhdIp4ZzyaviG3Iv59uJW-pHb6-pZSI');

		$_REQUEST['to'] = $token;

		$fields = array(
			'to' => $_REQUEST['to'],
			'data' => array(
				"notification_body" => "Data message body",
				"notification_title"=> "Data message title",
				"notification_foreground"=> "false",
				"notification_android_channel_id"=> "my_channel_id2",
				"notification_android_priority"=> "0",
				"notification_android_visibility"=> "0",
				"notification_android_color"=> "#ff0000",
				"notification_android_icon"=> "thumbs_up",
				"notification_android_sound"=> "alarm2",
				"notification_android_vibrate"=> "500, 200, 500",
				"notification_android_lights"=> "#ffff0000, 250, 250",
				"command" => "open:lib:tracking"
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
		
		print_r($fields);
		print_r($result);
	}
}