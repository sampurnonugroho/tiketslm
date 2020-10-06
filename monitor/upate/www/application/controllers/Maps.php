<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Maps extends CI_Controller {
    public function __construct() {
        parent::__construct();
		$this->load->library('curl');
    }

    public function index() {
        return view('admin/maps/index');
    }
	
	public function get_marker() {
		$query = "SELECT * FROM user LEFT JOIN karyawan ON(user.username=karyawan.nik) WHERE latlng!=''";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['nik'] = $row->username;
				$list[$key]['nama'] = $row->nama;
				$list[$key]['latlng'] = $row->latlng;
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
}