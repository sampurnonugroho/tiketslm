<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Datadashboard extends CI_Controller {
    var $data = array();
	
	public function __construct() {
        parent::__construct();
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
        echo "datadashboard";
    }

    public function get_data_ticket() {
        $query = "SELECT * FROM (SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket WHERE data_solve=''";
        $data = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
        $count_open = count($data);
        
        $query = "SELECT * FROM (SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket WHERE data_solve!=''";
        $data = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
        $count_close = count($data);

        // echo "<pre>";
        // echo "COUNT TICKET OPEN :";
        // print_r($count_open);
        // echo "<br>";
        // echo "COUNT TICKET CLOSE :";
        // print_r($count_close);

        $data = array(
            array(
                'name' => 'OPEN : '.$count_open.' TICKET',
                'y' => $count_open
            ),
            array(
                'name' => 'DONE : '.$count_close.' TICKET',
                'y' => $count_close
            )
        );

        echo json_encode($data);
    }

    public function get_data_runsheet() {
        $query = "SELECT * FROM (SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, jam_cash_in, cpc_process, updated_date, loading, unloading, req_combi, fraud_indicated FROM cashtransit_detail) AS cashtransit_detail WHERE data_solve=''";
        $data = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
        $count_open = count($data);
        
        $query = "SELECT * FROM (SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, jam_cash_in, cpc_process, updated_date, loading, unloading, req_combi, fraud_indicated FROM cashtransit_detail) AS cashtransit_detail WHERE data_solve!=''";
        $data = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
        $count_close = count($data);

        // [{
        //     name: 'Chrome',
        //     y: 61.41,
        //     // sliced: true,
        //     // selected: true
        // }, {
        //     name: 'Internet Explorer',
        //     y: 11.84
        // }]

        // echo "<pre>";
        // echo "COUNT RUNSHEET OPEN :";
        // print_r($count_open);
        // echo "<br>";
        // echo "COUNT RUNSHEET CLOSE :";
        // print_r($count_close);

        $data = array(
            array(
                'name' => 'OPEN : '.$count_open.' RUNSHEET',
                'y' => $count_open
            ),
            array(
                'name' => 'DONE : '.$count_close.' RUNSHEET',
                'y' => $count_close
            )
        );

        echo json_encode($data);
    }

    public function get_data_trouble() {
        $query = "SELECT 
                    IFNULL((
                        SELECT 
                            COUNT(DISTINCT id)
                                FROM
                                    (SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket 
                                        WHERE flm_trouble_ticket.id_bank = client.id
                    ), 0) AS cnt,
                    client.*
                    FROM 
                        client
                            LEFT JOIN (SELECT id, id_ticket, ticket_client, id_bank, problem_type, entry_date, email_date, time, down_time, accept_time, run_time, action_time, arrival_date, start_scan, end_apply, teknisi_1, teknisi_2, guard, status, data_solve, req_combi, updated FROM flm_trouble_ticket) AS flm_trouble_ticket ON (flm_trouble_ticket.id_bank = client.id)
                                WHERE flm_trouble_ticket.id_bank IS NOT NULL GROUP BY wsid ORDER BY cnt DESC";

        $data = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));

        echo "<pre>";
        print_r($data);
    }

    public function get_data_client() {
        $query = "SELECT * FROM client";
        $data = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		

        $list = array();
        $key=0;
        foreach($data as $r) {
            $data_location = json_decode($r->data_location);

            if($data_location->lat!="") {
                $list[$key]['wsid'] = $r->wsid;
                $list[$key]['latlng'] = json_encode(array("lat"=>$data_location->lat, "lng"=>$data_location->lng));
                $key++;
            }
        }
        echo json_encode($list);
    }

    public function chart() {
        return view('chart', $this->data);
    }

    public function jumlah_kelola_atm() {
        $query = "SELECT 
                    bank,
                    IFNULL((
                        SELECT  COUNT(DISTINCT C.id) FROM client AS C WHERE bank = client.bank AND type = 'ATM'
                    ),0) AS ATM,
                    IFNULL((
                        SELECT  COUNT(DISTINCT C.id) FROM client AS C WHERE bank = client.bank AND type = 'CRM'
                    ),0) AS CRM,
                    IFNULL((
                        SELECT  COUNT(DISTINCT C.id) FROM client AS C WHERE bank = client.bank AND type = 'CDM'
                    ),0) AS CDM
                        FROM 
                            client
                                GROUP BY bank";

        $data = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));  

        // print_r($data);

        $name = array();
        $atm = array();
        $crm = array();
        $cdm = array();
        foreach($data as $r) {
            $name[] = $r->bank;
            $atm[] = intval($r->ATM);
            $crm[] = intval($r->CRM);
            $cdm[] = intval($r->CDM);
        }

        // print_r($crm);
        // print_r($cdm);

        $data_atm = array('name'=>'ATM', 'data'=>$atm);
        $data_crm = array('name'=>'CRM', 'data'=>$crm);
        $data_cdm = array('name'=>'CDM', 'data'=>$cdm);
        echo json_encode(
            array(
                'bank'=>$name,
                'data'=>array($data_atm, $data_crm, $data_cdm)
            )
        );

        // $list = array();
        // $key=0;
        // $name = array();
        // foreach($data as $r) {
        //     $name[] = $r->bank
        //     // $list[$key]['name'] = $r->bank;
        //     // $list[$key]['data'] = array("lat"=>$data_location->LATLNG->LAT, "lng"=>$data_location->LATLNG->LNG);
        // }

        // print_r($name);
    }
}