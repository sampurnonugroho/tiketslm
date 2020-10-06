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
		$query = "SELECT * FROM runsheet_operational 
					LEFT JOIN runsheet_security ON(runsheet_operational.id_cashtransit=runsheet_security.id_cashtransit)
					LEFT JOIN user ON(user.username=runsheet_operational.custodian_1)
					LEFT JOIN karyawan ON(user.username=karyawan.nik)
					WHERE user.latlng!=''
					GROUP BY custodian_1 ORDER BY runsheet_operational.id DESC";
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
	
	public function get_marker_flm() {
		$query = "SELECT flm_trouble_ticket.teknisi_1, teknisi.*, user.*, karyawan.* FROM flm_trouble_ticket 
					LEFT JOIN teknisi ON(teknisi.id_teknisi=flm_trouble_ticket.teknisi_1)
					LEFT JOIN user ON(user.username=teknisi.nik)
					LEFT JOIN karyawan ON(user.username=karyawan.nik)
					WHERE teknisi.nik IS NOT NULL AND user.latlng!=''
					GROUP BY flm_trouble_ticket.teknisi_1 ORDER BY flm_trouble_ticket.id DESC";
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