<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Notif extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->library('curl');
		}
		
		public function index() {
			$msg = $this->msg('flm');
			$data['to'] = "dQqfYVDHtvg:APA91bHdXbNrKag0mvrunwtbCvTJVXlrT3L1Uk4hXxCA7LehEttL3EfD-14Y_imQi7O2OSGDxjWhj0tP_SBB1iPU_hifXe-wh-uEL9yKnskT6kJbCd_FEE08wjDp6SD0vA6W1SWpOK47";
			$data['title'] = $msg['title'];
			$data['body'] = $msg['body'];
			$data['status'] = $msg['status'];
			
			$res = $this->curl->simple_get(rest_api().'/notif/push', $data, array(CURLOPT_BUFFERSIZE => 10)); 
			
			echo $res;
		}
		
		public function msg($krit='') {
			$arr = array(
				"cashout" => array(
					"title" => "Prioritas Replenishment",
					"body" => "Prioritas pengisian/replenishment telah dialihkan pada lokasi yang lain, silahkan cek kembali aplikasi anda",
					"status" => "priority"
				),
				"flm" => array(
					"title" => "Request Job",
					"body" => "Request maintenance segera melakukan perbaikan mesin ATM, silahkan cek kembali aplikasi anda",
					"status" => "normal"
				),
				"op" => array(
					"title" => "Request Job",
					"body" => "Request pengisian/replenishment, silahkan cek kembali aplikasi anda",
					"status" => "normal"
				)
			);
			
			return $arr[$krit];
		}
	}