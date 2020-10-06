<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Notif extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function index_get() {
		
	}
	
	
	function push_get() {
		define('AUTHORIZATION_KEY', 'AAAAnLcRlWk:APA91bF0J5GW72L1GeWozjX8LNSSq5usc8twk0mUL53izRdPK9HiVz_yZziH7XUq_rXizKFk_bEv26FeQaRFe3YY5sPIpeTaV23CA1-s2kfPFYhdIp4ZzyaviG3Iv59uJW-pHb6-pZSI');

		$to = $this->input->get('to');
		
		$title = $this->input->get('title');
		$body = $this->input->get('body');
		
		$type = ($this->input->get('status')=="priority") ? "priority" : "alarm2";
		$channel = ($this->input->get('status')=="priority") ? "my_channel_id1" : "my_channel_id2";
		
		$fields = array(
			'to' => $to,
			'data' => array(
				"notification_body" => $body,
				"notification_title"=> $title,
				"notification_foreground"=> "true",
				"notification_android_channel_id"=> $channel,
				"notification_android_priority"=> "2",
				"notification_android_visibility"=> "1",
				"notification_android_color"=> "#ff0000",
				"notification_android_smallIcon"=> "thumbs_up",
				"notification_android_icon"=> "thumbs_up",
				"notification_android_sound"=> $type,
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
		
		echo "<pre>";
		print_r($fields);
		print_r($result);
	}
}