<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Sla_report extends REST_Controller {
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
	
	function index_post() {
		
	}
	
	function index_put() {
		
	}
	
	function index_delete() {
		
	}
	
	function get_data_get() {
		$query = "SELECT * FROM flm_trouble_ticket LEFT JOIN client ON (client.id=flm_trouble_ticket.id_bank)";
		$data = $this->db->query($query)->result();

    //    echo "<pre>";
    //    print_r($data);

        $list = array();
        $key=0;
        $response_time = "";
        foreach($data as $r) {
            $list[$key]['wsid'] = $r->wsid;
            $list[$key]['lokasi'] = $r->lokasi;
            $list[$key]['ticket'] = $r->id_ticket;
            
            $problem = array();
			foreach(json_decode($r->problem_type) as $p) {
				$problem[] = $this->db->select('nama_sub_kategori')->from('sub_kategori')->where('id_sub_kategori', $p)->limit(1)->get()->row()->nama_sub_kategori;
			}
			$list[$key]['problem_type'] = implode(', ', $problem);
            $list[$key]['entry_date'] = date("d-m-Y", strtotime($r->entry_date));
            $list[$key]['email_time'] = date("H:i:s", strtotime($r->entry_date));
            $list[$key]['arrival_date'] = date("d-m-Y", strtotime($r->arrival_date));
            $list[$key]['arrival_time'] = date("H:i:s", strtotime($r->arrival_date));
            $list[$key]['start_date'] = date("d-m-Y", strtotime($r->arrival_date));
            $list[$key]['start_time'] = date("H:i:s", strtotime($r->start_scan));
            $list[$key]['close_date'] = date("d-m-Y", strtotime($r->arrival_date));
            $list[$key]['close_time'] = date("H:i:s", strtotime($r->end_apply));
            $diff1 = $this->diff($r->entry_date, $r->start_scan);
            $list[$key]['response_time'] = $diff1;
            $list[$key]['minute_1'] = $this->hoursToMinutes($diff1);
            $diff2 = $this->diff($r->arrival_date, $r->end_apply);
            $list[$key]['repair_time'] = $diff2;
            $list[$key]['minute_2'] = $this->hoursToMinutes($diff2);
            $list[$key]['resolution_time'] = $this->tambah_waktu($diff1, $diff2);
            $list[$key]['minute_3'] = $this->hoursToMinutes($diff1)+$this->hoursToMinutes($diff2);
            $tes = ($this->hoursToMinutes($diff1)+$this->hoursToMinutes($diff2)) / (31*24*60);
            $list[$key]['dt'] = number_format($tes * 100, 2) . '%';
            $tes2 = 1-$tes;
            $list[$key]['uptime'] = number_format($tes2 * 100, 2) . '%';
            
            $key++;
        }

        
    //    print_r($list);
        echo json_encode($list);
	}
	
	function tambah_waktu($jam_mulai, $jam_selesai) {
        $times = array($jam_mulai,$jam_selesai); 
        $seconds = 0; 
        foreach ( $times as $time ) { 
            list( $g, $i, $s ) = explode( ':', $time ); 
            $seconds += $g * 3600; 
            $seconds += $i * 60; 
            $seconds += $s; 
        } 
        $hours = floor( $seconds / 3600 ); 
        $seconds -= $hours * 3600; 
        $minutes = floor( $seconds / 60 ); 
        $seconds -= $minutes * 60; 

        return sprintf('%02d', $hours).':'.sprintf('%02d', $minutes).':'.sprintf('%02d', $seconds);
    }

    function diff($a1, $a2) {
        $awal  = date_create($a1);
        $akhir = date_create($a2); // waktu sekarang
        $diff  = date_diff($awal, $akhir);

        // return $diff;
        return sprintf('%02d', $diff->h).':'.sprintf('%02d', $diff->i).':'.sprintf('%02d', $diff->s);
    }

    function hoursToMinutes($hours) { 
        $minutes = 0; 
        if (strpos($hours, ':') !== false) 
        { 
            // Split hours and minutes. 
            list($hours, $minutes) = explode(':', $hours); 
        } 
        return $hours * 60 + $minutes; 
    } 

    // Transform minutes like "105" into hours like "1:45". 
    function minutesToHours($minutes) { 
        $hours = (int)($minutes / 60); 
        $minutes -= $hours * 60; 
        return sprintf("%d:%02.0f", $hours, $minutes); 
    } 
}