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
		$query = "SELECT * FROM flm_trouble_ticket LEFT JOIN client ON (client.id=flm_trouble_ticket.id_bank) WHERE flm_trouble_ticket.end_apply IS NOT NULL";
		$query = "
			SELECT 
				*,
				(SELECT nama FROM karyawan LEFT JOIN teknisi ON(teknisi.nik=karyawan.nik) WHERE teknisi.id_teknisi=flm_trouble_ticket.teknisi_1) AS nama_teknisi_1,
				(SELECT nama FROM karyawan LEFT JOIN teknisi ON(teknisi.nik=karyawan.nik) WHERE teknisi.id_teknisi=flm_trouble_ticket.teknisi_2) AS nama_teknisi_2,
				(SELECT nama FROM karyawan WHERE karyawan.nik=flm_trouble_ticket.guard) AS nama_guard
				FROM flm_trouble_ticket 
					LEFT JOIN client ON (client.id=flm_trouble_ticket.id_bank) 
						WHERE flm_trouble_ticket.end_apply IS NOT NULL
		";
		$data = $this->db->query($query)->result();

    //    echo "<pre>";
    //    print_r($data);

        $list = array();
        $key=0;
        $response_time = "";
        foreach($data as $r) {
			$data_solve = json_decode($r->data_solve);
			
            $list[$key]['wsid'] = $r->wsid;
            $list[$key]['lokasi'] = $r->lokasi;
            $list[$key]['ticket'] = $r->id_ticket;
            $list[$key]['ticket_client'] = $r->ticket_client;
            $list[$key]['teknisi_1'] = $r->teknisi_1;
            $list[$key]['nama_teknisi'] = $r->nama_teknisi_1;
            $list[$key]['guard'] = $r->guard;
            $list[$key]['nama_guard'] = $r->nama_guard;
            
            $problem = array();
			foreach(json_decode($r->problem_type) as $p) {
				$problem[] = $this->db->select('nama_sub_kategori')->from('sub_kategori')->where('id_sub_kategori', $p)->limit(1)->get()->row()->nama_sub_kategori;
			}
			$list[$key]['problem_type'] = implode(', ', $problem);
            
			$list[$key]['email_date'] = date("d-m-Y", strtotime($r->email_date));
            $list[$key]['email_time'] = date("H:i:s", strtotime($r->email_date));
			$list[$key]['entry_date'] = date("d-m-Y", strtotime($r->entry_date));
            $list[$key]['entry_time'] = date("H:i:s", strtotime($r->entry_date));
			$list[$key]['accept_time'] = date("H:i:s", strtotime($r->accept_time));
            $list[$key]['arrival_date'] = date("d-m-Y", strtotime($r->arrival_date));
            $list[$key]['arrival_time'] = date("H:i:s", strtotime($r->arrival_date));
            $list[$key]['start_date'] = date("d-m-Y", strtotime($r->arrival_date));
            $list[$key]['start_time'] = date("H:i:s", strtotime($r->start_scan));
            $list[$key]['close_date'] = date("d-m-Y", strtotime($r->arrival_date));
            $list[$key]['close_time'] = date("H:i:s", strtotime($r->end_apply));
			
			$response_duty = $this->diff($r->email_date, $r->entry_date);
            $list[$key]['response_duty'] = $response_duty;
			
			$response_flm = $this->diff($r->entry_date, $r->accept_time);
            $list[$key]['response_flm'] = $response_flm;
			
			$repair_time = $this->diff($r->arrival_date, $r->end_apply);
            $list[$key]['repair_time'] = $repair_time;
			
			$resolution_time = $this->diff($r->email_date, $r->end_apply);
            $list[$key]['resolution_time'] = $resolution_time;
			
			$down_time = $this->hoursToMinutes($resolution_time);
			$rumus = ($this->dayNumber(date("Y-m-d", strtotime($r->arrival_date)))*24);
			// $list[$key]['down_time'] = $down_time." ".$rumus." = ".$down_time/$rumus;
			$list[$key]['down_time'] = round($down_time/$rumus, 2);
			$list[$key]['up_time'] = 100-round($down_time/$rumus, 2);
			
            $list[$key]['keterangan'] = $data_solve->keterangan;
            
            $key++;
        }

        
    //    print_r($list);
        echo json_encode($list);
	}
	
	public function dayNumber($date=''){
		if($date==''){
			$t=date('d-m-Y');
		} else {
			$t=date('d-m-Y',strtotime($date));
		}

		$dayMonth = strtolower(date("m",strtotime($t)));
		$dayYear = strtolower(date("Y",strtotime($t)));
		$return = cal_days_in_month(CAL_GREGORIAN, $dayMonth, $dayYear);
		return $return;
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