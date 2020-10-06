<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Rekon_atm extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("ticket_model");
        $this->load->library('form_validation');

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
		$this->data['active_menu'] = "rekon_atm";
		
		$this->data['table'] = $this->show_table($this->get_data4());
		
		return view('admin/rekon_atm/index', $this->data);
    }
	
	public function get_data() {
		$pagenum = isset($_GET['pagenum']) ? intval($_GET['pagenum']) : 1;
		$pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : 10;
		$start = $pagenum*$pagesize;
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM client LIMIT $start, $pagesize";
		
		$result = $this->db->query($query)->result();
        $sql = "SELECT FOUND_ROWS() AS `found_rows`;";
		$rows = $this->db->query($sql)->row_array();
		$total_rows = $rows['found_rows'];
		
		foreach($result as $row) {
			$client[] = array(
				'id' => $row->id,
				'wsid' => $row->wsid,
				'lokasi' => $row->lokasi,
				'type' => $row->type,
				'lokasi1' => $row->lokasi,
				'lokasi2' => $row->lokasi,
				'lokasi3' => $row->lokasi,
				'lokasi4' => $row->lokasi
			);
		}
		
		$data[] = array(
		   'TotalRows' => $total_rows,	
		   'Rows' => $client
		);
		echo json_encode($data);
		
		// echo "{\"data\":" .json_encode($client). "}";
	}
	
	public function get_data2() {
		$sql = "select *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN runsheet_cashprocessing ON(cashtransit.id=runsheet_cashprocessing.id_cashtransit) WHERE cashtransit_detail.state='ro_atm' AND cashtransit_detail.data_solve!='' ORDER BY cashtransit.id DESC";
		$result = $this->db->query($sql)->result();
		
		// echo "<pre>";
		// print_r($result);
		// echo "</pre>";
		
		foreach($result as $row) {
			$data = json_decode($row->data_solve);
			
			// echo "<pre>";
			// print_r($data);
			// echo "</pre>";
			$ctr_1 = ($data->cart_1_seal!=="") ? 1 : 0;
			$ctr_2 = ($data->cart_2_seal!=="") ? 1 : 0;
			$ctr_3 = ($data->cart_3_seal!=="") ? 1 : 0;
			$ctr_4 = ($data->cart_4_seal!=="") ? 1 : 0;

			$ctr = $ctr_1+$ctr_2+ +$ctr_3+$ctr_4;
			
			$client[] = array(
				'id' => $row->id,
				'wsid' => $row->wsid,
				'lokasi' => $row->lokasi,
				'type' => $row->type,
				'tanggal' => date("Y-m-d", strtotime($row->date)),
				'ctr' => $ctr,
				'pcs_50000' => $row->pcs_50000,
				'pcs_100000' => $row->pcs_100000,
				'denom' => (intval($row->pcs_50000)!==0 ? "50000" : "100000"),
				'denom_50' => ($row->pcs_50000=="" ? "" : $row->pcs_50000),
				'denom_100' => ($row->pcs_100000=="" ? "" : $row->pcs_100000),
				'ttl_ctr' => ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)),
				'total' => ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100)),
				'csst1' => $data->cart_1_no,
				'csst2' => $data->cart_2_no,
				'csst3' => $data->cart_3_no,
				'csst4' => $data->cart_4_no,
				'reject' => $data->div_no,
			);
		}
		
		// echo "<pre>";
		// print_r($client);
		// echo "</pre>";
		
		return $client;
	}
	
	public function get_data4x() {
		// error_reporting(0);
		$act = $this->uri->segment(3);
		
		if($act=="show") {		
			
		} else if($act=="show_tanggal") {		
		} else {
			$var_tgl = "AND B.updated_date LIKE '%".date('Y-m-d')."%'";
		}
		
		$sql = "
		SELECT SQL_CALC_FOUND_ROWS
				A.id, 
				D.wsid, 
				D.lokasi, 
				D.type, 
				B.id_bank, 
				B.date, 
				B.updated_date, 
				D.ctr,
				B.pcs_50000,
				B.pcs_100000,
				B.data_solve,
				B.cpc_process,
				B.ctr as jum_ctr
									FROM cashtransit A
									LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, updated_date, ctr, pcs_50000, pcs_100000 FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
									LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
									LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
									LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(A.id=F.id_cashtransit)
									WHERE B.state='ro_atm' AND B.data_solve!='' AND 
									B.id IN (
										SELECT MAX(id)
										FROM cashtransit_detail
										WHERE state='ro_atm' AND data_solve!=''
										GROUP BY id_bank
									) 
									$var_tgl
									GROUP BY D.wsid";
									
		// echo $sql;
		
		$result = $this->db->query($sql)->result();
		
		$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
		$rows = $this->db->query($sql)->row_array();
		$total_rows = $rows['found_rows'];
		
		$no = 0;
		foreach($result as $row) {
			if($act=="show") {		
				$format_now_time = date("H:i", strtotime($row->updated_date));
			} else if($act=="show_tanggal") {		
				$format_now_time = date("Y-m-d H:i", strtotime($row->updated_date));
			} else {
				$format_now_time = date("H:i", strtotime($row->updated_date));
			}
			
			
			
			$no++;
			$data = json_decode($row->cpc_process);
			
			$ctr_1 = ($data->cart_1_seal!=="") ? 1 : 0;
			$ctr_2 = ($data->cart_2_seal!=="") ? 1 : 0;
			$ctr_3 = ($data->cart_3_seal!=="") ? 1 : 0;
			$ctr_4 = ($data->cart_4_seal!=="") ? 1 : 0;
			
			$ctr = $row->jum_ctr;
			
			$TOTALA = 50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_1_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_1_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_2_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_2_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_3_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_3_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_4_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_4_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->div_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->div_no));
			
			$TOTALB = 50*(intval($row->pcs_50000)==0 ? 0 : intval($data->return_dispensed)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->return_dispensed));
			
			list($date, $time) = explode(" ", $row->date);
			$data_now[] = array(
				'no' => $no,
				'date' => $date,
				'wsid' => $row->wsid,
				'lokasi' => $row->lokasi,
				'type' => $row->type,
				'tanggal' => date("Y-m-d", strtotime($row->date)),
				'time' => date("H:i", strtotime($row->updated_date)),
				'ctr' => $ctr,
				'D50' => $ctr * (intval($row->pcs_50000)==0 ? 0 : $row->pcs_50000),	
				'D100' => $ctr * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000),
				'T50' => ($ctr * (intval($row->pcs_50000)==0 ? 0 : $row->pcs_50000))*50,
				'T100' => ($ctr * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000))*100,
				'total' => ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100)),
			);
			
			// $sql = "
			// SELECT SQL_CALC_FOUND_ROWS
					// A.id, 
					// D.wsid, 
					// D.lokasi, 
					// D.type, 
					// B.id_bank, 
					// B.date, 
					// D.ctr,
					// F.pcs_50000,
					// F.pcs_100000,
					// B.data_solve,
					// B.cpc_process,
					// B.ctr as jum_ctr
										// FROM cashtransit A
										// LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, ctr FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
										// LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
										// LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
										// LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(A.id=F.id_cashtransit)
										// WHERE B.state='ro_atm' AND B.data_solve!='' AND D.wsid='$row->wsid' AND A.id<'$row->id' AND
										// B.id IN (
											// SELECT MAX(id)
											// FROM cashtransit_detail
											// WHERE state='ro_atm' AND data_solve!=''
											// GROUP BY id_bank
										// )";
			
			$sql2 = "
			SELECT SQL_CALC_FOUND_ROWS
					A.id, 
					D.wsid, 
					D.lokasi, 
					D.type, 
					B.id_bank, 
					B.date, 
					D.ctr,
					F.pcs_50000,
					F.pcs_100000,
					B.data_solve,
					B.cpc_process,
					B.ctr as jum_ctr
										FROM cashtransit A
										LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, ctr FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
										LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
										LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
										LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(B.id=F.id_ctrs)
										WHERE B.state='ro_atm' AND B.data_solve!='' AND D.wsid='$row->wsid' AND A.id<'$row->id' AND
										A.id IN (
											SELECT cashtransit.id 
											FROM cashtransit_detail LEFT JOIN cashtransit ON(cashtransit.id=cashtransit_detail.id_cashtransit) 
											WHERE state='ro_atm' AND data_solve!='' AND cashtransit.id<$row->id
										)";
			
			$row2 = $this->db->query($sql2)->row();
			
			
			// echo "<pre>";
			// print_r($row2);
			
			// echo $row->id ." ". $row->wsid;
			// echo $sql2;
			
			$ctr2 = $row2->jum_ctr;
			
			$TOTALA = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_1_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_1_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_2_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_2_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_3_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_3_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_4_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_4_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->div_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->div_no));
			
			
			// $data = json_decode($row->cpc_process);
			
			$data = json_decode($row2->data_solve);
			$data2 = json_decode($row->cpc_process);
			list($date, $time) = explode(" ", $row2->date);
			
			// KETERANGAN 
			$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
			$now_total = ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100));
			$s50k = ($ctr2 * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$s100k = ($ctr2 * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000))*100;
			
			
			$dispensed = $data2->return_dispensed;
			// $dispensed = 4699;
			
			$TOTALB = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($dispensed)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($dispensed));
					  
			// $dispensed = intval(str_replace(".", "", $data2->return_withdraw))/1000;
			// $TOTALB = $dispensed;
		
			$t50 = ($ctr2 * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$t100 = ($ctr2 * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000))*100;
			$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
			
			$hasil = 0;
			$ket = "";
			// echo (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)."<br>";
			if($denom=="50000") {
				if($now_total!==0) {
					if($s50k!==0) {
						$hasil = $now_total-$s50k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			} else {
				if($now_total!==0) {
					if($s100k!==0) {
						$hasil = $now_total-$s100k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			}
			 
			if($ket=="") {
				if($hasil==0) {
					$ket = "";
				} else if($hasil>0) {
					$ket = "NAIK";
				} else if($hasil<0) {
					$ket = "TURUN";
				}
			}
			
			
			$data_prev[] = array(
				'no' => $no,
				'date' => $date,
				'wsid' => $row->wsid,
				'lokasi' => $row->lokasi,
				'type' => $row->type,
				'tanggal' => ($data->last_clear==null ? "" : date("Y-m-d", strtotime($data->last_clear))),
				'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
				'ctr' => $ctr2,
				'T50' => $t50,
				'T100' => $t100,
				'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
				'csst1' => $data2->cart_1_no,
				'csst2' => $data2->cart_2_no,
				'csst3' => $data2->cart_3_no,
				'csst4' => $data2->cart_4_no,
				'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
				'D50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($ctr2 * $row2->pcs_50000)),	
				'D100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($ctr2 * $row2->pcs_100000)),
				'T50' => ((intval($row2->pcs_50000)==0 ? 0 : $this->rupiah($ctr2 * $row2->pcs_50000 * 50))),
				'T100' => ((intval($row2->pcs_100000)==0 ? "-" : $this->rupiah($ctr2 * $row2->pcs_100000 * 100))),
				'CSST1_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_1_no)),
				'CSST1_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_1_no)),
				'CSST2_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
				'CSST2_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
				'CSST3_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
				'CSST3_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
				'CSST4_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
				'CSST4_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
				'RJT50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
				'RJT100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
				'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
				'CSST1B50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($dispensed)),
				'CSST1B100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($dispensed)),
				'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
				'selisih' => ($selisih=="" ? "-" : $this->rupiah($selisih)),
				'now_time' => $format_now_time,
				'now_ctr' => $row->jum_ctr,
				'now_D50' => (intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_50000)),	
				'now_D100' => (intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_100000)),
				'now_T50' => ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_50000 * 50))),
				'now_T100' => ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_100000 * 100))),
				'now_total' => $this->rupiah(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100))),
				'keterangan' => $ket,
			);
		}
		
		// $datax[] = array(
		   // 'TotalRows' => $total_rows,	
		   // 'Rows' => $client
		// );
		// echo json_encode($datax);
		
		
		// echo $this->show_table($data_prev);
		
		// echo "<pre>";
		// print_r($result);
		// echo "<br>";
		// echo "DATA NOW => ";
		// print_r($data_now);
		// echo "DATA PREV => ";
		// print_r($data_prev);
		// echo "ROW 2 => ";
		// print_r($row2->updated_date);
		
		// return $data_prev;
		if(!empty($data_prev)) {
			$this->data['table'] = $this->show_table($data_prev);
		} else {
			$this->data['table'] = "<center>SORRY, NO DATA!</center>";
		}
		
		return view('admin/rekon_atm/index3', $this->data);
	}
	
	public function get_data_by_search() {
		$datessss = $this->input->post('date');
		
		$sql = "SELECT 
					*,
					(SELECT count(*) FROM run_status_cancel WHERE id_detail=cashtransit_detail.id) as TES,
					cashtransit_detail.ctr as ctr2,
					(cashtransit_detail.ctr-(SELECT count(*) FROM run_status_cancel WHERE id_detail=cashtransit_detail.id)) as ctr
						FROM 
							(SELECT id, date, updated_date, id_cashtransit, id_bank, ctr, state, data_solve, cpc_process FROM cashtransit_detail) AS cashtransit_detail
								LEFT JOIN 
									cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
								LEFT JOIN 
									master_branch ON(cashtransit.branch=master_branch.id)
								LEFT JOIN
									client ON(cashtransit_detail.id_bank=client.id)
								LEFT JOIN
									runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
									WHERE
										cashtransit_detail.data_solve!='batal' AND 
										cashtransit_detail.state='ro_atm' AND 
										cashtransit_detail.data_solve!='' AND
										client.type!='CDM' AND 
										cashtransit_detail.updated_date LIKE '%".$datessss."%'
		";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($sql);
		// print_r($result);
		
		$no = 0;
		foreach($result as $row) {
			$act = $row->type;
			
			$no++;
			$sql2 = "SELECT 
						*,
						cashtransit_detail.ctr as ctr
							FROM 
								(SELECT id, date, updated_date, id_cashtransit, id_bank, ctr, state, data_solve, cpc_process FROM cashtransit_detail) AS cashtransit_detail
									LEFT JOIN 
										cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
									LEFT JOIN 
										master_branch ON(cashtransit.branch=master_branch.id)
									LEFT JOIN
										client ON(cashtransit_detail.id_bank=client.id)
									LEFT JOIN
										runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
										WHERE
											cashtransit_detail.data_solve!='batal' AND 
											cashtransit_detail.state='ro_atm' AND 
											cashtransit_detail.data_solve!='' AND
											client.wsid='$row->wsid' AND 
											cashtransit_detail.id<'$row->id' ORDER BY cashtransit_detail.id DESC LIMIT 0,1";
			$data = json_decode($row->cpc_process);
			$data_x = json_decode($row->data_solve);
			
			
			if($data_x->jam_cash_in!=="") {
				$format_now_time = date("H:i", strtotime($data_x->jam_cash_in));
			}
				
			$row2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql2), array(CURLOPT_BUFFERSIZE => 10)));
			
			$TOTALA = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_1_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_1_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_2_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_2_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_3_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_3_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_4_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_4_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->div_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->div_no));
			
			list($date, $time) = explode(" ", $row->date);
			$ctr = $row->ctr;
			$data2 = json_decode($row->cpc_process);
			$data_solve = json_decode($row->data_solve);
			
			// KETERANGAN 
			$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
			$now_total = ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100));
			$s50k = ($ctr * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$s100k = ($ctr * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000))*100;
			
			
			$dispensed = str_replace(".", "", str_replace(",", "", $data2->return_dispensed));
			// $dispensed = 4699;
			
			$TOTALB = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($dispensed)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($dispensed));
					  
			// $dispensed = intval(str_replace(".", "", $data2->return_withdraw))/1000;
			// $TOTALB = $dispensed;
		
			// $t50 = ($ctr * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$t50 = ((intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000 * 50));
			// $t100 = ($ctr * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000))*100;
			$t100 = ((intval($row2->pcs_100000)==0 ? "-" : $row2->pcs_100000 * 100));
			$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
			
			// echo "TOTALA => ".$TOTALA."<br>";
			// echo "TOTALB => ".$TOTALB."<br>";
			// echo "T50 => ".$t50."<br>";
			// echo "T100 => ".$t100."<br>";
			// echo "selisih => ".$selisih."<br>";
			
			$hasil = 0;
			$ket = "";
			// echo (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)."<br>";
			
			// echo $now_total."<br>";
			// echo $s50k."<br>";
			// echo $s100k."<br>";
			if($denom=="50000") {
				if($now_total!==0) {
					if($s50k!==0) {
						$hasil = $now_total-$s50k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			} else {
				if($now_total!==0) {
					if($s100k!==0) {
						$hasil = $now_total-$s100k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			}
			 
			if($ket=="") {
				if($hasil==0) {
					$ket = "";
				} else if($hasil>0) {
					$ket = "NAIK";
				} else if($hasil<0) {
					$ket = "TURUN";
				}
			}
			
			if($act=="ATM") {
				$data_prev[] = array(
						'no' => $no,
						'date' => $date,
						'wsid' => $row->wsid,
						'lokasi' => $row->lokasi,
						'type' => $row->type,
						// 'tanggal' => ($data2->last_clear==null ? "" : date("Y-m-d", strtotime($data2->last_clear))),
						'tanggal' => ($row->date==null ? "" : date("Y-m-d", strtotime($row2->updated_date_cpc))),
						'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
						'ctr' => intval($row2->ctr),
						'total' => ($ctr*(intval($row2->pcs_50000)!==0 ? $row2->pcs_50000 : $row2->pcs_100000)*(intval($row2->pcs_50000)!==0 ? 50 : 100)),
						'csst1' => $data2->cart_1_no,
						'csst2' => $data2->cart_2_no,
						'csst3' => $data2->cart_3_no,
						'csst4' => $data2->cart_4_no,
						'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
						'D50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($row2->pcs_50000)),	
						'D100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($row2->pcs_100000)),
						'T50' => ((intval($row2->pcs_50000)==0 ? 0 : $this->rupiah($row2->pcs_50000 * 50))),
						'T100' => ((intval($row2->pcs_100000)==0 ? "-" : $this->rupiah($row2->pcs_100000 * 100))),
						'CSST1_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data->cart_1_no)),
						'CSST1_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_1_no)),
						'CSST2_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
						'CSST2_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
						'CSST3_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
						'CSST3_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
						'CSST4_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
						'CSST4_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
						'RJT50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
						'RJT100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
						'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
						'CSST1B50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($dispensed)),
						'CSST1B100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($dispensed)),
						'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
						'selisih' => ($selisih=="" ? "-" : $this->rupiah($selisih)),
						'now_time' => $format_now_time,
						'now_ctr' => intval($row->ctr),
						'now_D50' => (intval($row->pcs_50000)==0 ? 0 : $this->rupiah(($row->pcs_50000/$row->ctr2)*$row->ctr)),	
						'now_D100' => (intval($row->pcs_100000)==0 ? 0 : $this->rupiah(($row->pcs_100000/$row->ctr2)*$row->ctr)),
						'now_T50' => ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah($row->pcs_50000 * 50))),
						'now_T100' => ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah($row->pcs_100000 * 100))),
						'now_total' => $this->rupiah(((intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100))),
						'keterangan' => $ket,
				);
			} else if($act=="CRM") { 
				
				$t50 = ($ctr2 * (intval($data2->s50k)==0 ? 0 : $data2->s50k))*50;
				$t100 = ($ctr2 * (intval($data2->s100k)==0 ? 0 : $data2->s100k))*100;
				$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
				
				$TOTALA = 50*(intval(json_decode($data2->cart_1_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_1_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_1_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_1_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_2_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_2_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_2_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_2_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_3_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_3_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_3_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_3_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_4_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_4_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_4_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_4_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_5_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_5_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_5_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_5_no, true)['100'])) +
						  50*(intval(json_decode($data2->div_no, true)['50'])==0 ? 0 : intval(json_decode($data2->div_no, true)['50'])) +
						  100*(intval(json_decode($data2->div_no, true)['100'])==0 ? 0 : intval(json_decode($data2->div_no, true)['100']));
				
				$TOTALB = str_replace(".", "", $data2->return_crm_cashout)/1000;
				$CCRM = str_replace(".", "", $data2->return_crm_balance)/1000;
				
				
				$data_prev[] = array(
					'no' => $no,
					'date' => $date,
					'wsid' => $row->wsid,
					'lokasi' => $row->lokasi,
					'type' => $row->type,
					'tanggal' => ($row->date==null ? "" : date("Y-m-d", strtotime($row2->updated_date_cpc))),
					'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
					'ctr' => $ctr2,
					'T50' => $t50,
					'T100' => $t100,
					'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
					'csst1' => $data2->cart_1_no,
					'csst2' => $data2->cart_2_no,
					'csst3' => $data2->cart_3_no,
					'csst4' => $data2->cart_4_no,
					'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
					'D50' => (intval($data2->s50k)==0 ? "" : $this->rupiah($data2->s50k)),	
					'D100' => (intval($data2->s100k)==0 ? "" : $this->rupiah($data2->s100k)),
					'T50' => ((intval($data2->s50k)==0 ? 0 : $this->rupiah($data2->s50k * 50))),
					'T100' => ((intval($data2->s100k)==0 ? "-" : $this->rupiah($data2->s100k * 100))),
					'CSST1_50' => intval(json_decode($data2->cart_1_no, true)['50']),
					'CSST1_100' => intval(json_decode($data2->cart_1_no, true)['100']),
					'CSST2_50' => intval(json_decode($data2->cart_2_no, true)['50']),
					'CSST2_100' => intval(json_decode($data2->cart_2_no, true)['100']),
					'CSST3_50' => intval(json_decode($data2->cart_3_no, true)['50']),
					'CSST3_100' => intval(json_decode($data2->cart_3_no, true)['100']),
					'CSST4_50' => intval(json_decode($data2->cart_4_no, true)['50']),
					'CSST4_100' => intval(json_decode($data2->cart_4_no, true)['100']),
					'CSST5_50' => intval(json_decode($data2->cart_5_no, true)['50']),
					'CSST5_100' => intval(json_decode($data2->cart_5_no, true)['100']),
					'RJT50' => intval(json_decode($data2->div_no, true)['50']),
					'RJT100' => intval(json_decode($data2->div_no, true)['100']),
					'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
					'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
					'CCRM' => ($CCRM=="" ? "-" : $this->rupiah($CCRM)),
					'now_time' => $format_now_time,
					'now_ctr' => $row->jum_ctr,
					'now_D50' => (intval($data_x->s50k)==0 ? "" : $this->rupiah($data_x->s50k)),		
					'now_D100' => (intval($data_x->s100k)==0 ? "" : $this->rupiah($data_x->s100k)),
					'now_T50' => ((intval($data_x->s50k)==0 ? 0 : $this->rupiah($data_x->s50k * 50))),
					'now_T100' => ((intval($data_x->s100k)==0 ? "-" : $this->rupiah($data_x->s100k * 100))),
					'now_total' => $this->rupiah(($data_x->s50k * 50)+($data_x->s100k * 100)),
					// 'keterangan' => $ket,
				);
				
				// echo "<pre>";
				// print_r($data2);
				// print_r(json_decode($data2->cart_1_no, true)['100']);
			}
			// echo "<pre>";
			// print_r($row2);
		}
		
		// echo "<pre>";
		// print_r($data_prev);
		
		// $sql = "
				// SELECT SQL_CALC_FOUND_ROWS
						// A.id, 
						// D.wsid, 
						// D.lokasi, 
						// D.type, 
						// B.id_bank, 
						// B.date, 
						// B.updated_date, 
						// D.ctr,
						// B.pcs_50000,
						// B.pcs_100000,
						// B.data_solve,
						// B.cpc_process,
						// B.ctr as jum_ctr
							// FROM cashtransit A
							// LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, updated_date, ctr, pcs_50000, pcs_100000 FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
							// LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
							// LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
							// LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(A.id=F.id_cashtransit)
							// WHERE B.state='ro_atm' AND B.data_solve!='' AND 
							// D.type='CDM' AND
							// B.id IN (
								// SELECT MAX(id)
								// FROM cashtransit_detail
								// WHERE state='ro_atm' AND data_solve!=''
								// GROUP BY id_bank
							// ) 
							// GROUP BY D.wsid";
		
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS
						A.id, 
						D.wsid, 
						D.lokasi, 
						D.type, 
						B.id_bank, 
						B.date, 
						B.updated_date, 
						D.ctr,
						B.pcs_50000,
						B.pcs_100000,
						B.data_solve,
						B.cpc_process,
						B.ctr as jum_ctr
							FROM cashtransit A
							LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, updated_date, ctr, pcs_50000, pcs_100000 FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
							LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
							LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
							LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(A.id=F.id_cashtransit)
							WHERE 
							B.state='ro_atm' AND 
							B.data_solve!='' AND 
							D.type='CDM' AND
							B.date LIKE '%".$date."%' AND
							B.id IN (
								SELECT MAX(id)
								FROM (SELECT id, date, updated_date, id_cashtransit, id_bank, ctr, state, data_solve, cpc_process FROM cashtransit_detail) AS cashtransit_detail
								WHERE state='ro_atm' AND data_solve!=''
								GROUP BY id_bank
							) 
							GROUP BY D.wsid";
		
		$sql = "SELECT 
					*
					FROM 
						(SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, updated_date, ctr, pcs_50000, pcs_100000 FROM cashtransit_detail) AS cashtransit_detail
							LEFT JOIN 
								cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
							LEFT JOIN 
								master_branch ON(cashtransit.branch=master_branch.id)
							LEFT JOIN
								client ON(cashtransit_detail.id_bank=client.id)
							LEFT JOIN
								runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
								WHERE
									cashtransit_detail.data_solve!='batal' AND 
									cashtransit_detail.state='ro_atm' AND 
									cashtransit_detail.data_solve!='' AND
									client.type='CDM' AND 
									runsheet_cashprocessing.updated_date_cpc LIKE '%".$datessss."%'
		";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		$no = 0;
		foreach($result as $row) {
			$no++;
			if($row->cpc_process!=="") {
				$data = json_decode($row->cpc_process);
			} else {
				$data = json_decode($row->data_solve);
			}
			
			list($date, $time) = explode(" ", $row->updated_date);
			
			$postArr = json_decode($data->data_seal, true);
			$postArr = array_map('array_filter', $postArr);
			$postArr = array_filter($postArr);
			
			$total_20 = 0;
			$total_50 = 0;
			$total_100 = 0;
			foreach ($postArr as $item) {
				$total_20 += (empty($item['20']) ? 0 : $item['20']);
				$total_50 += (empty($item['50']) ? 0 : $item['50']);
				$total_100 += (empty($item['100']) ? 0 : $item['100']);
			}
			
			$mutasi = ($total_100*100)+($total_50*50)+($total_20*20);
			$counter = ($data->return_cdm_denom100*100)+($data->return_cdm_denom50*50)+($data->return_cdm_denom20*20);
			
			$data_prev2[] = array(
				'no' => $no,
				'date' => $date,
				'wsid' => $row->wsid,
				'lokasi' => $row->lokasi,
				'lbr_100' => $total_100,
				'lbr_50' => $total_50,
				'lbr_20' => $total_20,
				'fisik_100' => $total_100*100,
				'fisik_50' => $total_50*50,
				'fisik_20' => $total_20*20,
				'mutasi_kredit' => $mutasi,
				'document_counter' => $counter,
				'selisih' => $mutasi-$counter,
			);
		}
		
		$output = array();
		if(!empty($data_prev)) {
			$output['table1'] = $this->show_table($data_prev);
			$output['table2'] = $this->show_table2($data_prev2);
		} else {
			$output['table1'] = "<center>SORRY, NO DATA!</center>";
			$output['table2'] = "<center>SORRY, NO DATA!</center>";
		}
		
		echo json_encode($output);
	}
	
	public function get_data4() {
		$this->data['active_menu'] = "rekon_atm";
		
		error_reporting(0);
		$act = $this->uri->segment(3);
		$html = $this->uri->segment(4);
		
		if($act=="show") {		
			
		} else if($act=="show_tanggal") {		
		} else {
			$var_tgl = "AND B.updated_date LIKE '%".date('Y-m-d')."%'";
		}
		
		$sql = "SELECT 
					*,
					cashtransit_detail.ctr as ctr
						FROM 
							(SELECT id, date, updated_date, id_cashtransit, id_bank, ctr, state, data_solve, cpc_process FROM cashtransit_detail) AS cashtransit_detail
								LEFT JOIN 
									cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
								LEFT JOIN 
									master_branch ON(cashtransit.branch=master_branch.id)
								LEFT JOIN
									client ON(cashtransit_detail.id_bank=client.id)
								LEFT JOIN
									runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
									WHERE
										cashtransit_detail.data_solve!='batal' AND 
										cashtransit_detail.state='ro_atm' AND 
										cashtransit_detail.data_solve!='' AND
										client.type!='CDM' AND 
										cashtransit_detail.updated_date LIKE '%".date('Y-m-d')."%'
		";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($sql);
		// print_r($result);
		
		$no = 0;
		foreach($result as $row) {
			$act = $row->type;
			
			$no++;
			$sql2 = "SELECT 
						*,
						cashtransit_detail.ctr as ctr
							FROM 
								(SELECT id, date, updated_date, id_cashtransit, id_bank, ctr, state, data_solve, cpc_process FROM cashtransit_detail) AS cashtransit_detail
									LEFT JOIN 
										cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
									LEFT JOIN 
										master_branch ON(cashtransit.branch=master_branch.id)
									LEFT JOIN
										client ON(cashtransit_detail.id_bank=client.id)
									LEFT JOIN
										runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
										WHERE
											cashtransit_detail.data_solve!='batal' AND 
											cashtransit_detail.state='ro_atm' AND 
											cashtransit_detail.data_solve!='' AND
											client.wsid='$row->wsid' AND 
											cashtransit_detail.id<'$row->id' ORDER BY cashtransit_detail.id DESC LIMIT 0,1";
			$data = json_decode($row->cpc_process);
			$data_x = json_decode($row->data_solve);
			
			
			if($data_x->jam_cash_in!=="") {
				$format_now_time = date("H:i", strtotime($data_x->jam_cash_in));
			}
				
			$row2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql2), array(CURLOPT_BUFFERSIZE => 10)));
			
			$TOTALA = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_1_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_1_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_2_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_2_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_3_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_3_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_4_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_4_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->div_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->div_no));
			
			list($date, $time) = explode(" ", $row->date);
			$ctr = $row->ctr;
			$data2 = json_decode($row->cpc_process);
			$data_solve = json_decode($row->data_solve);
			
			// KETERANGAN 
			$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
			$now_total = ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100));
			$s50k = ($ctr * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$s100k = ($ctr * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000))*100;
			
			
			$dispensed = str_replace(".", "", str_replace(",", "", $data2->return_dispensed));
			// $dispensed = 4699;
			
			$TOTALB = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($dispensed)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($dispensed));
					  
			// $dispensed = intval(str_replace(".", "", $data2->return_withdraw))/1000;
			// $TOTALB = $dispensed;
		
			// $t50 = ($ctr * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$t50 = ((intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000 * 50));
			// $t100 = ($ctr * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000))*100;
			$t100 = ((intval($row2->pcs_100000)==0 ? "-" : $row2->pcs_100000 * 100));
			$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
			
			// echo "TOTALA => ".$TOTALA."<br>";
			// echo "TOTALB => ".$TOTALB."<br>";
			// echo "T50 => ".$t50."<br>";
			// echo "T100 => ".$t100."<br>";
			// echo "selisih => ".$selisih."<br>";
			
			$hasil = 0;
			$ket = "";
			// echo (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)."<br>";
			
			// echo $now_total."<br>";
			// echo $s50k."<br>";
			// echo $s100k."<br>";
			if($denom=="50000") {
				if($now_total!==0) {
					if($s50k!==0) {
						$hasil = $now_total-$s50k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			} else {
				if($now_total!==0) {
					if($s100k!==0) {
						$hasil = $now_total-$s100k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			}
			 
			if($ket=="") {
				if($hasil==0) {
					$ket = "";
				} else if($hasil>0) {
					$ket = "NAIK";
				} else if($hasil<0) {
					$ket = "TURUN";
				}
			}
			
			if($act=="ATM") {
				$data_prev[] = array(
						'no' => $no,
						'date' => $date,
						'wsid' => $row->wsid,
						'lokasi' => $row->lokasi,
						'type' => $row->type,
						// 'tanggal' => ($data2->last_clear==null ? "" : date("Y-m-d", strtotime($data2->last_clear))),
						'tanggal' => ($row->date==null ? "" : date("Y-m-d", strtotime($row2->updated_date_cpc))),
						'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
						'ctr' => intval($row2->ctr),
						'total' => ($ctr*(intval($row2->pcs_50000)!==0 ? $row2->pcs_50000 : $row2->pcs_100000)*(intval($row2->pcs_50000)!==0 ? 50 : 100)),
						'csst1' => $data2->cart_1_no,
						'csst2' => $data2->cart_2_no,
						'csst3' => $data2->cart_3_no,
						'csst4' => $data2->cart_4_no,
						'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
						'D50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($row2->pcs_50000)),	
						'D100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($row2->pcs_100000)),
						'T50' => ((intval($row2->pcs_50000)==0 ? 0 : $this->rupiah($row2->pcs_50000 * 50))),
						'T100' => ((intval($row2->pcs_100000)==0 ? "-" : $this->rupiah($row2->pcs_100000 * 100))),
						'CSST1_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data->cart_1_no)),
						'CSST1_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_1_no)),
						'CSST2_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
						'CSST2_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
						'CSST3_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
						'CSST3_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
						'CSST4_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
						'CSST4_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
						'RJT50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
						'RJT100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
						'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
						'CSST1B50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($dispensed)),
						'CSST1B100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($dispensed)),
						'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
						'selisih' => ($selisih=="" ? "-" : $this->rupiah($selisih)),
						'now_time' => $format_now_time,
						'now_ctr' => intval($row->ctr),
						'now_D50' => (intval($row->pcs_50000)==0 ? 0 : $this->rupiah($row->pcs_50000)),	
						'now_D100' => (intval($row->pcs_100000)==0 ? 0 : $this->rupiah($row->pcs_100000)),
						'now_T50' => ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah($row->pcs_50000 * 50))),
						'now_T100' => ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah($row->pcs_100000 * 100))),
						'now_total' => $this->rupiah(((intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100))),
						'keterangan' => $ket,
				);
			} else if($act=="CRM") { 
				
				$t50 = ($ctr2 * (intval($data2->s50k)==0 ? 0 : $data2->s50k))*50;
				$t100 = ($ctr2 * (intval($data2->s100k)==0 ? 0 : $data2->s100k))*100;
				$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
				
				$TOTALA = 50*(intval(json_decode($data2->cart_1_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_1_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_1_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_1_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_2_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_2_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_2_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_2_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_3_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_3_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_3_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_3_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_4_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_4_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_4_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_4_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_5_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_5_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_5_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_5_no, true)['100'])) +
						  50*(intval(json_decode($data2->div_no, true)['50'])==0 ? 0 : intval(json_decode($data2->div_no, true)['50'])) +
						  100*(intval(json_decode($data2->div_no, true)['100'])==0 ? 0 : intval(json_decode($data2->div_no, true)['100']));
				
				$TOTALB = str_replace(".", "", $data2->return_crm_cashout)/1000;
				$CCRM = str_replace(".", "", $data2->return_crm_balance)/1000;
				
				
				$data_prev[] = array(
					'no' => $no,
					'date' => $date,
					'wsid' => $row->wsid,
					'lokasi' => $row->lokasi,
					'type' => $row->type,
					'tanggal' => ($row->date==null ? "" : date("Y-m-d", strtotime($row2->updated_date_cpc))),
					'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
					'ctr' => $ctr2,
					'T50' => $t50,
					'T100' => $t100,
					'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
					'csst1' => $data2->cart_1_no,
					'csst2' => $data2->cart_2_no,
					'csst3' => $data2->cart_3_no,
					'csst4' => $data2->cart_4_no,
					'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
					'D50' => (intval($data2->s50k)==0 ? "" : $this->rupiah($data2->s50k)),	
					'D100' => (intval($data2->s100k)==0 ? "" : $this->rupiah($data2->s100k)),
					'T50' => ((intval($data2->s50k)==0 ? 0 : $this->rupiah($data2->s50k * 50))),
					'T100' => ((intval($data2->s100k)==0 ? "-" : $this->rupiah($data2->s100k * 100))),
					'CSST1_50' => intval(json_decode($data2->cart_1_no, true)['50']),
					'CSST1_100' => intval(json_decode($data2->cart_1_no, true)['100']),
					'CSST2_50' => intval(json_decode($data2->cart_2_no, true)['50']),
					'CSST2_100' => intval(json_decode($data2->cart_2_no, true)['100']),
					'CSST3_50' => intval(json_decode($data2->cart_3_no, true)['50']),
					'CSST3_100' => intval(json_decode($data2->cart_3_no, true)['100']),
					'CSST4_50' => intval(json_decode($data2->cart_4_no, true)['50']),
					'CSST4_100' => intval(json_decode($data2->cart_4_no, true)['100']),
					'CSST5_50' => intval(json_decode($data2->cart_5_no, true)['50']),
					'CSST5_100' => intval(json_decode($data2->cart_5_no, true)['100']),
					'RJT50' => intval(json_decode($data2->div_no, true)['50']),
					'RJT100' => intval(json_decode($data2->div_no, true)['100']),
					'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
					'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
					'CCRM' => ($CCRM=="" ? "-" : $this->rupiah($CCRM)),
					'now_time' => $format_now_time,
					'now_ctr' => $row->jum_ctr,
					'now_D50' => (intval($data_x->s50k)==0 ? "" : $this->rupiah($data_x->s50k)),		
					'now_D100' => (intval($data_x->s100k)==0 ? "" : $this->rupiah($data_x->s100k)),
					'now_T50' => ((intval($data_x->s50k)==0 ? 0 : $this->rupiah($data_x->s50k * 50))),
					'now_T100' => ((intval($data_x->s100k)==0 ? "-" : $this->rupiah($data_x->s100k * 100))),
					'now_total' => $this->rupiah(($data_x->s50k * 50)+($data_x->s100k * 100)),
					// 'keterangan' => $ket,
				);
				
				// echo "<pre>";
				// print_r($data2);
				// print_r(json_decode($data2->cart_1_no, true)['100']);
			}
			// echo "<pre>";
			// print_r($row2);
		}
		
		// echo "<pre>";
		// print_r($data_prev);
		
		if(!empty($data_prev)) {
			$this->data['table'] = $this->show_table($data_prev);
			// $this->data['table2'] = $this->show_table2($data_prev2);
			
			return view('admin/rekon_atm/index3', $this->data);
		} else {
			$this->data['table'] = "<center>SORRY, NO DATA!</center>";
		}
		
		return view('admin/rekon_atm/index3', $this->data);
	}
	
	public function get_data4XXXX() {
		$this->data['active_menu'] = "rekon_atm";
		
		error_reporting(0);
		$act = $this->uri->segment(3);
		$html = $this->uri->segment(4);
		
		if($act=="show") {		
			
		} else if($act=="show_tanggal") {		
		} else {
			$var_tgl = "AND B.updated_date LIKE '%".date('Y-m-d')."%'";
		}
		
		$sql = "SELECT 
					*,
					cashtransit_detail.ctr as ctr
						FROM 
							cashtransit_detail
								LEFT JOIN 
									cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
								LEFT JOIN 
									master_branch ON(cashtransit.branch=master_branch.id)
								LEFT JOIN
									client ON(cashtransit_detail.id_bank=client.id)
								LEFT JOIN
									runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
									WHERE
										cashtransit_detail.data_solve!='batal' AND 
										cashtransit_detail.state='ro_atm' AND 
										cashtransit_detail.data_solve!='' AND
										client.type!='CDM'";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$no = 0;
		foreach($result as $row) {
			$act = $row->type;
			if($act=="show") {		
				$format_now_time = date("H:i", strtotime($row->updated_date));
			} else if($act=="show_tanggal") {		
				$format_now_time = date("Y-m-d H:i", strtotime($row->updated_date));
			} else {
				$format_now_time = date("H:i", strtotime($row->updated_date));
			}
			$no++;
			$sql2 = "SELECT 
						*,
						cashtransit_detail.ctr as ctr
							FROM 
								cashtransit_detail
									LEFT JOIN 
										cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
									LEFT JOIN 
										master_branch ON(cashtransit.branch=master_branch.id)
									LEFT JOIN
										client ON(cashtransit_detail.id_bank=client.id)
									LEFT JOIN
										runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
										WHERE
											cashtransit_detail.data_solve!='batal' AND 
											cashtransit_detail.state='ro_atm' AND 
											cashtransit_detail.data_solve!='' AND
											client.wsid='$row->wsid' AND 
											cashtransit_detail.id<'$row->id' ORDER BY cashtransit_detail.id DESC LIMIT 0,1";
			$data = json_decode($row->cpc_process);
			$data_x = json_decode($row->data_solve);
			
			// echo "<pre>";
			// print_r($data_x->last_clear);
			// $format_now_time = date("H:i", strtotime($data_x->last_clear));
			if($data_x->jam_cash_in!=="") {
				$format_now_time = date("H:i", strtotime($data_x->jam_cash_in));
			}
				
			$row2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql2), array(CURLOPT_BUFFERSIZE => 10)));
			
			$TOTALA = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_1_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_1_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_2_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_2_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_3_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_3_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_4_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_4_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->div_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->div_no));
			
			list($date, $time) = explode(" ", $row->date);
			$ctr = $row->ctr;
			$data2 = json_decode($row->cpc_process);
			$data_solve = json_decode($row->data_solve);
			
			// KETERANGAN 
			$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
			$now_total = ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100));
			$s50k = ($ctr * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$s100k = ($ctr * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000))*100;
			
			
			$dispensed = str_replace(".", "", str_replace(",", "", $data2->return_dispensed));
			// $dispensed = 4699;
			
			$TOTALB = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($dispensed)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($dispensed));
					  
			// $dispensed = intval(str_replace(".", "", $data2->return_withdraw))/1000;
			// $TOTALB = $dispensed;
		
			// $t50 = ($ctr * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$t50 = ((intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000 * 50));
			// $t100 = ($ctr * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000))*100;
			$t100 = ((intval($row2->pcs_100000)==0 ? "-" : $row2->pcs_100000 * 100));
			$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
			
			// echo "TOTALA => ".$TOTALA."<br>";
			// echo "TOTALB => ".$TOTALB."<br>";
			// echo "T50 => ".$t50."<br>";
			// echo "T100 => ".$t100."<br>";
			// echo "selisih => ".$selisih."<br>";
			
			$hasil = 0;
			$ket = "";
			// echo (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)."<br>";
			
			// echo $now_total."<br>";
			// echo $s50k."<br>";
			// echo $s100k."<br>";
			if($denom=="50000") {
				if($now_total!==0) {
					if($s50k!==0) {
						$hasil = $now_total-$s50k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			} else {
				if($now_total!==0) {
					if($s100k!==0) {
						$hasil = $now_total-$s100k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			}
			 
			if($ket=="") {
				if($hasil==0) {
					$ket = "";
				} else if($hasil>0) {
					$ket = "NAIK";
				} else if($hasil<0) {
					$ket = "TURUN";
				}
			}
			
			if($act=="ATM") {
				$data_prev[] = array(
						'no' => $no,
						'date' => $date,
						'wsid' => $row->wsid,
						'lokasi' => $row->lokasi,
						'type' => $row->type,
						// 'tanggal' => ($data2->last_clear==null ? "" : date("Y-m-d", strtotime($data2->last_clear))),
						'tanggal' => ($row->date==null ? "" : date("Y-m-d", strtotime($data_solve->last_clear))),
						'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
						'ctr' => intval($row2->ctr),
						'total' => ($ctr*(intval($row2->pcs_50000)!==0 ? $row2->pcs_50000 : $row2->pcs_100000)*(intval($row2->pcs_50000)!==0 ? 50 : 100)),
						'csst1' => $data2->cart_1_no,
						'csst2' => $data2->cart_2_no,
						'csst3' => $data2->cart_3_no,
						'csst4' => $data2->cart_4_no,
						'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
						'D50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($row2->pcs_50000)),	
						'D100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($row2->pcs_100000)),
						'T50' => ((intval($row2->pcs_50000)==0 ? 0 : $this->rupiah($row2->pcs_50000 * 50))),
						'T100' => ((intval($row2->pcs_100000)==0 ? "-" : $this->rupiah($row2->pcs_100000 * 100))),
						'CSST1_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data->cart_1_no)),
						'CSST1_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_1_no)),
						'CSST2_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
						'CSST2_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
						'CSST3_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
						'CSST3_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
						'CSST4_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
						'CSST4_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
						'RJT50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
						'RJT100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
						'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
						'CSST1B50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($dispensed)),
						'CSST1B100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($dispensed)),
						'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
						'selisih' => ($selisih=="" ? "-" : $this->rupiah($selisih)),
						'now_time' => $format_now_time,
						'now_ctr' => intval($row->ctr),
						'now_D50' => (intval($row->pcs_50000)==0 ? 0 : $this->rupiah($row->pcs_50000)),	
						'now_D100' => (intval($row->pcs_100000)==0 ? 0 : $this->rupiah($row->pcs_100000)),
						'now_T50' => ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah($row->pcs_50000 * 50))),
						'now_T100' => ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah($row->pcs_100000 * 100))),
						'now_total' => $this->rupiah(((intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100))),
						'keterangan' => $ket,
				);
			} else if($act=="CRM") { 
				
				$t50 = ($ctr2 * (intval($data2->s50k)==0 ? 0 : $data2->s50k))*50;
				$t100 = ($ctr2 * (intval($data2->s100k)==0 ? 0 : $data2->s100k))*100;
				$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
				
				$TOTALA = 50*(intval(json_decode($data2->cart_1_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_1_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_1_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_1_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_2_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_2_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_2_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_2_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_3_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_3_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_3_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_3_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_4_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_4_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_4_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_4_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_5_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_5_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_5_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_5_no, true)['100'])) +
						  50*(intval(json_decode($data2->div_no, true)['50'])==0 ? 0 : intval(json_decode($data2->div_no, true)['50'])) +
						  100*(intval(json_decode($data2->div_no, true)['100'])==0 ? 0 : intval(json_decode($data2->div_no, true)['100']));
				
				$TOTALB = str_replace(".", "", $data2->return_crm_cashout)/1000;
				$CCRM = str_replace(".", "", $data2->return_crm_balance)/1000;
				
				
				$data_prev[] = array(
					'no' => $no,
					'date' => $date,
					'wsid' => $row->wsid,
					'lokasi' => $row->lokasi,
					'type' => $row->type,
					'tanggal' => ($data2->last_clear==null ? "" : date("Y-m-d", strtotime($data2->last_clear))),
					'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
					'ctr' => $ctr2,
					'T50' => $t50,
					'T100' => $t100,
					'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
					'csst1' => $data2->cart_1_no,
					'csst2' => $data2->cart_2_no,
					'csst3' => $data2->cart_3_no,
					'csst4' => $data2->cart_4_no,
					'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
					'D50' => (intval($data2->s50k)==0 ? "" : $this->rupiah($data2->s50k)),	
					'D100' => (intval($data2->s100k)==0 ? "" : $this->rupiah($data2->s100k)),
					'T50' => ((intval($data2->s50k)==0 ? 0 : $this->rupiah($data2->s50k * 50))),
					'T100' => ((intval($data2->s100k)==0 ? "-" : $this->rupiah($data2->s100k * 100))),
					'CSST1_50' => intval(json_decode($data2->cart_1_no, true)['50']),
					'CSST1_100' => intval(json_decode($data2->cart_1_no, true)['100']),
					'CSST2_50' => intval(json_decode($data2->cart_2_no, true)['50']),
					'CSST2_100' => intval(json_decode($data2->cart_2_no, true)['100']),
					'CSST3_50' => intval(json_decode($data2->cart_3_no, true)['50']),
					'CSST3_100' => intval(json_decode($data2->cart_3_no, true)['100']),
					'CSST4_50' => intval(json_decode($data2->cart_4_no, true)['50']),
					'CSST4_100' => intval(json_decode($data2->cart_4_no, true)['100']),
					'CSST5_50' => intval(json_decode($data2->cart_5_no, true)['50']),
					'CSST5_100' => intval(json_decode($data2->cart_5_no, true)['100']),
					'RJT50' => intval(json_decode($data2->div_no, true)['50']),
					'RJT100' => intval(json_decode($data2->div_no, true)['100']),
					'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
					'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
					'CCRM' => ($CCRM=="" ? "-" : $this->rupiah($CCRM)),
					'now_time' => $format_now_time,
					'now_ctr' => $row->jum_ctr,
					'now_D50' => (intval($data_x->s50k)==0 ? "" : $this->rupiah($data_x->s50k)),		
					'now_D100' => (intval($data_x->s100k)==0 ? "" : $this->rupiah($data_x->s100k)),
					'now_T50' => ((intval($data_x->s50k)==0 ? 0 : $this->rupiah($data_x->s50k * 50))),
					'now_T100' => ((intval($data_x->s100k)==0 ? "-" : $this->rupiah($data_x->s100k * 100))),
					'now_total' => $this->rupiah(($data_x->s50k * 50)+($data_x->s100k * 100)),
					// 'keterangan' => $ket,
				);
				
				// echo "<pre>";
				// print_r($data2);
				// print_r(json_decode($data2->cart_1_no, true)['100']);
			}
			// echo "<pre>";
			// print_r($row2);
		}
		
		// echo "<pre>";
		// print_r($data_prev);
		
		if(!empty($data_prev)) {
			$this->data['table'] = $this->show_table($data_prev);
			// $this->data['table2'] = $this->show_table2($data_prev2);
			
			return view('admin/rekon_atm/index3', $this->data);
		} else {
			$this->data['table'] = "<center>SORRY, NO DATA!</center>";
		}
		
		return view('admin/rekon_atm/index3', $this->data);
	}
	
	public function get_data5() {
		// header('Content-Type: application/json');
		
		$this->data['active_menu'] = "rekon_atm";
		
		error_reporting(0);
		$act = $this->uri->segment(3);
		$html = $this->uri->segment(4);
		
		if($act=="show") {		
			
		} else if($act=="show_tanggal") {		
		} else {
			$var_tgl = "AND B.updated_date LIKE '%".date('Y-m-d')."%'";
		}
		
		$sql = "
		SELECT SQL_CALC_FOUND_ROWS
				A.id, 
				D.wsid, 
				D.lokasi, 
				D.type, 
				B.id_bank, 
				B.date, 
				B.updated_date, 
				D.ctr,
				B.pcs_50000,
				B.pcs_100000,
				B.data_solve,
				B.cpc_process,
				B.ctr as jum_ctr
									FROM cashtransit A
									LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, updated_date, ctr, pcs_50000, pcs_100000 FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
									LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
									LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
									LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(A.id=F.id_cashtransit)
									WHERE B.state='ro_atm' AND B.data_solve!='' AND 
									D.type!='CDM' AND
									B.id IN (
										SELECT MAX(id)
										FROM cashtransit_detail
										WHERE state='ro_atm' AND data_solve!=''
										GROUP BY id_bank
									) 
									$var_tgl";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$no = 0;
		foreach($result as $row) {
			if($act=="show") {		
				$format_now_time = date("H:i", strtotime($row->updated_date));
			} else if($act=="show_tanggal") {		
				$format_now_time = date("Y-m-d H:i", strtotime($row->updated_date));
			} else {
				$format_now_time = date("H:i", strtotime($row->updated_date));
			}
			
			$act = $row->type;
			
			$no++;
			$data = json_decode($row->cpc_process);
			
			$ctr_1 = ($data->cart_1_seal!=="") ? 1 : 0;
			$ctr_2 = ($data->cart_2_seal!=="") ? 1 : 0;
			$ctr_3 = ($data->cart_3_seal!=="") ? 1 : 0;
			$ctr_4 = ($data->cart_4_seal!=="") ? 1 : 0;
			
			$ctr = $row->jum_ctr;
			
			$TOTALA = 50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_1_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_1_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_2_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_2_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_3_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_3_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_4_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_4_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->div_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->div_no));
			
			$TOTALB = 50*(intval($row->pcs_50000)==0 ? 0 : intval($data->return_dispensed)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->return_dispensed));
			
			list($date, $time) = explode(" ", $row->date);
			$data_now[] = array(
				'no' => $no,
				'date' => $date,
				'wsid' => $row->wsid,
				'lokasi' => $row->lokasi,
				'type' => $row->type,
				'tanggal' => date("Y-m-d", strtotime($row->date)),
				'time' => date("H:i", strtotime($row->updated_date)),
				'ctr' => $ctr,
				'D50' => $ctr * (intval($row->pcs_50000)==0 ? 0 : $row->pcs_50000),	
				'D100' => $ctr * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000),
				'T50' => ($ctr * (intval($row->pcs_50000)==0 ? 0 : $row->pcs_50000))*50,
				'T100' => ($ctr * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000))*100,
				'total' => ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100)),
			);
			
			$sql2 = "
			SELECT SQL_CALC_FOUND_ROWS
					A.id, 
					D.wsid, 
					D.lokasi, 
					D.type, 
					B.id_bank, 
					B.date, 
					D.act,
					D.ctr,
					F.pcs_50000,
					F.pcs_100000,
					B.data_solve,
					B.cpc_process,
					B.ctr as jum_ctr
										FROM cashtransit A
										LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, ctr FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
										LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
										LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
										LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(B.id=F.id_ctrs)
										WHERE B.state='ro_atm' AND B.data_solve!='' AND D.wsid='$row->wsid' AND A.id<'$row->id' AND
										A.id IN (
											SELECT cashtransit.id 
											FROM cashtransit_detail LEFT JOIN cashtransit ON(cashtransit.id=cashtransit_detail.id_cashtransit) 
											WHERE state='ro_atm' AND data_solve!='' AND cashtransit.id<$row->id
										)";
			
			$row2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql2), array(CURLOPT_BUFFERSIZE => 10)));
			
			// if(count($row2)==0) {
				// $row2 = $row;
			// }
			
			$ctr2 = $row2->jum_ctr;
			
			$TOTALA = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_1_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_1_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_2_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_2_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_3_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_3_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_4_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_4_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->div_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->div_no));
			
			
			// $data = json_decode($row->cpc_process);
			
			$datax = json_decode($row2->data_solve);
			$data2 = json_decode($row->cpc_process);
			list($date, $time) = explode(" ", $row2->date);
			
			// KETERANGAN 
			$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
			$now_total = ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100));
			$s50k = ($ctr2 * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$s100k = ($ctr2 * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000))*100;
			
			
			$dispensed = str_replace(".", "", str_replace(",", "", $data2->return_dispensed));
			// $dispensed = 4699;
			
			$TOTALB = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($dispensed)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($dispensed));
					  
			// $dispensed = intval(str_replace(".", "", $data2->return_withdraw))/1000;
			// $TOTALB = $dispensed;
		
			$t50 = ($ctr2 * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$t100 = ($ctr2 * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000))*100;
			$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
			
			$hasil = 0;
			$ket = "";
			// echo (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)."<br>";
			if($denom=="50000") {
				if($now_total!==0) {
					if($s50k!==0) {
						$hasil = $now_total-$s50k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			} else {
				if($now_total!==0) {
					if($s100k!==0) {
						$hasil = $now_total-$s100k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			}
			 
			if($ket=="") {
				if($hasil==0) {
					$ket = "";
				} else if($hasil>0) {
					$ket = "NAIK";
				} else if($hasil<0) {
					$ket = "TURUN";
				}
			}
			
			if($act=="ATM") {
				if($html) {
					echo "<pre>";
					print_r($row2);
					echo "<br>";
					// // echo $dispensed;
					echo "<br>";
				}
			
			
				$data_prev[] = array(
					'no' => $no,
					'date' => $date,
					'wsid' => $row->wsid,
					'lokasi' => $row->lokasi,
					'type' => $row->type,
					'tanggal' => ($data2->last_clear==null ? "" : date("Y-m-d", strtotime($data2->last_clear))),
					'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
					'ctr' => $ctr2,
					'T50' => $t50,
					'T100' => $t100,
					'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
					'csst1' => $data2->cart_1_no,
					'csst2' => $data2->cart_2_no,
					'csst3' => $data2->cart_3_no,
					'csst4' => $data2->cart_4_no,
					'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
					'D50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($ctr2 * $row2->pcs_50000)),	
					'D100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($ctr2 * $row2->pcs_100000)),
					'T50' => ((intval($row2->pcs_50000)==0 ? 0 : $this->rupiah($ctr2 * $row2->pcs_50000 * 50))),
					'T100' => ((intval($row2->pcs_100000)==0 ? "-" : $this->rupiah($ctr2 * $row2->pcs_100000 * 100))),
					'CSST1_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_1_no)),
					'CSST1_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_1_no)),
					'CSST2_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
					'CSST2_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
					'CSST3_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
					'CSST3_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
					'CSST4_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
					'CSST4_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
					'RJT50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
					'RJT100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
					'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
					'CSST1B50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($dispensed)),
					'CSST1B100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($dispensed)),
					'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
					'selisih' => ($selisih=="" ? "-" : $this->rupiah($selisih)),
					'now_time' => $format_now_time,
					'now_ctr' => $row->jum_ctr,
					'now_D50' => (intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_50000)),	
					'now_D100' => (intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_100000)),
					'now_T50' => ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_50000 * 50))),
					'now_T100' => ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_100000 * 100))),
					'now_total' => $this->rupiah(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100))),
					'keterangan' => $ket,
				);
			} else if($act=="CRM") { 
				if($html) {
					print_r($data2);
					echo "<br>";
					// // echo $dispensed;
					echo "<br>";
				}
				
				$t50 = ($ctr2 * (intval($data2->s50k)==0 ? 0 : $data2->s50k))*50;
				$t100 = ($ctr2 * (intval($data2->s100k)==0 ? 0 : $data2->s100k))*100;
				$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
				
				$TOTALA = 50*(intval(json_decode($data2->cart_1_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_1_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_1_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_1_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_2_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_2_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_2_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_2_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_3_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_3_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_3_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_3_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_4_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_4_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_4_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_4_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_5_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_5_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_5_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_5_no, true)['100'])) +
						  50*(intval(json_decode($data2->div_no, true)['50'])==0 ? 0 : intval(json_decode($data2->div_no, true)['50'])) +
						  100*(intval(json_decode($data2->div_no, true)['100'])==0 ? 0 : intval(json_decode($data2->div_no, true)['100']));
				
				$TOTALB = str_replace(".", "", $data2->return_crm_cashin)/1000;
				$CCRM = str_replace(".", "", $data2->return_crm_balance)/1000;
				
				
				$data_prev[] = array(
					'no' => $no,
					'date' => $date,
					'wsid' => $row->wsid,
					'lokasi' => $row->lokasi,
					'type' => $row->type,
					'tanggal' => ($data2->last_clear==null ? "" : date("Y-m-d", strtotime($data2->last_clear))),
					'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
					'ctr' => $ctr2,
					'T50' => $t50,
					'T100' => $t100,
					'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
					'csst1' => $data2->cart_1_no,
					'csst2' => $data2->cart_2_no,
					'csst3' => $data2->cart_3_no,
					'csst4' => $data2->cart_4_no,
					'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
					'D50' => (intval($data2->s50k)==0 ? "" : $this->rupiah($data2->s50k)),	
					'D100' => (intval($data2->s100k)==0 ? "" : $this->rupiah($data2->s100k)),
					'T50' => ((intval($data2->s50k)==0 ? 0 : $this->rupiah($data2->s50k * 50))),
					'T100' => ((intval($data2->s100k)==0 ? "-" : $this->rupiah($data2->s100k * 100))),
					'CSST1_50' => intval(json_decode($data2->cart_1_no, true)['50']),
					'CSST1_100' => intval(json_decode($data2->cart_1_no, true)['100']),
					'CSST2_50' => intval(json_decode($data2->cart_2_no, true)['50']),
					'CSST2_100' => intval(json_decode($data2->cart_2_no, true)['100']),
					'CSST3_50' => intval(json_decode($data2->cart_3_no, true)['50']),
					'CSST3_100' => intval(json_decode($data2->cart_3_no, true)['100']),
					'CSST4_50' => intval(json_decode($data2->cart_4_no, true)['50']),
					'CSST4_100' => intval(json_decode($data2->cart_4_no, true)['100']),
					'CSST5_50' => intval(json_decode($data2->cart_5_no, true)['50']),
					'CSST5_100' => intval(json_decode($data2->cart_5_no, true)['100']),
					'RJT50' => intval(json_decode($data2->div_no, true)['50']),
					'RJT100' => intval(json_decode($data2->div_no, true)['100']),
					'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
					'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
					'CCRM' => ($CCRM=="" ? "-" : $this->rupiah($CCRM)),
					'now_time' => $format_now_time,
					'now_ctr' => $row->jum_ctr,
					'now_D50' => (intval($data2->s50k)==0 ? "" : $this->rupiah($data2->s50k)),		
					'now_D100' => (intval($data2->s100k)==0 ? "" : $this->rupiah($data2->s100k)),
					'now_T50' => ((intval($data2->s50k)==0 ? 0 : $this->rupiah($data2->s50k * 50))),
					'now_T100' => ((intval($data2->s100k)==0 ? "-" : $this->rupiah($data2->s100k * 100))),
					'now_total' => $this->rupiah(($data2->s50k * 50)+($data2->s100k * 100)),
					// 'keterangan' => $ket,
				);
				
				
				if($html) {
					echo "<pre>";
					print_r($data_prev);
					echo "<br>";
					// // echo $dispensed;
					echo "<br>";
				}
			}
			
		}
		
		// echo "<pre>";
			// print_r($data_prev);
		
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS
						A.id, 
						D.wsid, 
						D.lokasi, 
						D.type, 
						B.id_bank, 
						B.date, 
						B.updated_date, 
						D.ctr,
						B.pcs_50000,
						B.pcs_100000,
						B.data_solve,
						B.cpc_process,
						B.ctr as jum_ctr
							FROM cashtransit A
							LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, updated_date, ctr, pcs_50000, pcs_100000 FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
							LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
							LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
							LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(A.id=F.id_cashtransit)
							WHERE B.state='ro_atm' AND B.data_solve!='' AND 
							D.type='CDM' AND
							B.id IN (
								SELECT MAX(id)
								FROM cashtransit_detail
								WHERE state='ro_atm' AND data_solve!=''
								GROUP BY id_bank
							) 
							GROUP BY D.wsid";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		$no = 0;
		foreach($result as $row) {
			$no++;
			if($row->cpc_process!=="") {
				$data = json_decode($row->cpc_process);
			} else {
				$data = json_decode($row->data_solve);
			}
			
			list($date, $time) = explode(" ", $row->updated_date);
			
			$postArr = json_decode($data->data_seal, true);
			$postArr = array_map('array_filter', $postArr);
			$postArr = array_filter($postArr);
			
			$total_20 = 0;
			$total_50 = 0;
			$total_100 = 0;
			foreach ($postArr as $item) {
				$total_20 += (empty($item['20']) ? 0 : $item['20']);
				$total_50 += (empty($item['50']) ? 0 : $item['50']);
				$total_100 += (empty($item['100']) ? 0 : $item['100']);
			}
			
			$mutasi = ($total_100*100)+($total_50*50)+($total_20*20);
			$counter = ($data->return_cdm_denom100*100)+($data->return_cdm_denom50*50)+($data->return_cdm_denom20*20);
			
			$data_prev2[] = array(
				'no' => $no,
				'date' => $date,
				'wsid' => $row->wsid,
				'lokasi' => $row->lokasi,
				'lbr_100' => $total_100,
				'lbr_50' => $total_50,
				'lbr_20' => $total_20,
				'fisik_100' => $total_100*100,
				'fisik_50' => $total_50*50,
				'fisik_20' => $total_20*20,
				'mutasi_kredit' => $mutasi,
				'document_counter' => $counter,
				'selisih' => $mutasi-$counter,
			);
		}
		
		// print_r($data_prev);	
		
		if(!empty($data_prev)) {
			$this->data['table'] = $this->show_table($data_prev);
			$this->data['table2'] = $this->show_table2($data_prev2);
		} else {
			$this->data['table'] = "<center>SORRY, NO DATA!</center>";
		}
		
		if($html=="") {
			return view('admin/rekon_atm/index3', $this->data);
		}
	}
	
	public function show_table($data) {
		$html1 = '';
		$html2 = '';
		$html_content1 = '';
		$html_content_footer = '';
		$html_content2 = '';
		
		$i = 0;
		$total_d50 = 0;
		$total_t50 = 0;
		$total_d100 = 0;
		$total_t100 = 0;
		$total_csst1_100 = 0;
		$total_csst2_100 = 0;
		$total_csst3_100 = 0;
		$total_csst4_100 = 0;
		$total_csst1_50 = 0;
		$total_csst2_50 = 0;
		$total_csst3_50 = 0;
		$total_csst4_50 = 0;
		$total_rjt_100 = 0;
		$total_rjt_50 = 0;
		$total2_csst1_100 = 0;
		$total2_csst2_100 = 0;
		$total2_csst3_100 = 0;
		$total2_csst4_100 = 0;
		$total2_csst1_50 = 0;
		$total2_csst2_50 = 0;
		$total2_csst3_50 = 0;
		$total2_csst4_50 = 0;
		$total2_rjt_100 = 0;
		$total2_rjt_50 = 0;
		$TOTALA = 0;
		$TOTALB = 0;
		$counter_crm = 0;
		$selisih = 0;
		$now_D50 = 0;
		$now_D100 = 0;
		$now_total = 0;
		
		$selisih_crm = 0;
		foreach($data as $r) {
			$i++;
			
			
			// if (strpos($r['D50'], ",") == false) {
				// $total_d50 = $total_d50 + intval($r['D50']);
			// } else {
				$total_d50 = $total_d50 + intval(str_replace(",", "", $r['D50']));
				$total_t50 = $total_t50 + intval(str_replace(",", "", $r['T50']));
				$total_d100 = $total_d100 + intval(str_replace(",", "", $r['D100']));
				$total_t100 = $total_t100 + intval(str_replace(",", "", $r['T100']));
				
				$total_csst1_100 = $total_csst1_100 + intval(str_replace(",", "", $r['CSST1_100']));
				$total_csst2_100 = $total_csst2_100 + intval(str_replace(",", "", $r['CSST2_100']));
				$total_csst3_100 = $total_csst3_100 + intval(str_replace(",", "", $r['CSST3_100']));
				$total_csst4_100 = $total_csst4_100 + intval(str_replace(",", "", $r['CSST4_100']));
				
				$total_csst1_50 = $total_csst1_50 + intval(str_replace(",", "", $r['CSST1_50']));
				$total_csst2_50 = $total_csst2_50 + intval(str_replace(",", "", $r['CSST2_50']));
				$total_csst3_50 = $total_csst3_50 + intval(str_replace(",", "", $r['CSST3_50']));
				$total_csst4_50 = $total_csst4_50 + intval(str_replace(",", "", $r['CSST4_50']));
				
				$total2_csst1_100 = $total2_csst1_100 + intval(str_replace(",", "", $r['CSST1B100']));
				$total2_csst2_100 = $total2_csst2_100 + intval(str_replace(",", "", $r['CSST2B100']));
				$total2_csst3_100 = $total2_csst3_100 + intval(str_replace(",", "", $r['CSST3B100']));
				$total2_csst4_100 = $total2_csst4_100 + intval(str_replace(",", "", $r['CSST4B100']));
				
				$total2_csst1_50 = $total2_csst1_50 + intval(str_replace(",", "", $r['CSST1B50']));
				$total2_csst2_50 = $total2_csst2_50 + intval(str_replace(",", "", $r['CSST2B50']));
				$total2_csst3_50 = $total2_csst3_50 + intval(str_replace(",", "", $r['CSST3B50']));
				$total2_csst4_50 = $total2_csst4_50 + intval(str_replace(",", "", $r['CSST4B50']));
				
				$total_rjt_100 = $total_rjt_100 + intval(str_replace(",", "", $r['RJT100']));
				$total_rjt_50 = $total_rjt_50 + intval(str_replace(",", "", $r['RJT50']));
				
				$TOTALA = $TOTALA + intval(str_replace(",", "", $r['TOTALA']));
				$TOTALB = $TOTALB + intval(str_replace(",", "", $r['TOTALB']));
				
				$counter_crm = $counter_crm + intval(str_replace(",", "", $r['counter_crm']));
				$CCRM = $r['CCRM'];
				
				if($r['type']=="CRM") {
					$selisih_crm =  intval(str_replace(",", "", $r['TOTALA'])) - intval(str_replace(",", "", $CCRM));
				} else {
					$selisih_crm = $selisih + intval(str_replace(",", "", $r['selisih']));
				}
				$now_D50 = $now_D50 + intval(str_replace(",", "", $r['now_D50']));
				$now_D100 = $now_D100 + intval(str_replace(",", "", $r['now_D100']));
				$now_total = $now_total + intval(str_replace(",", "", $r['now_total']));
			// }
			
			
			$html_content1 .= '
				<tr>
					<td align="center">'.$i.'.</td>
					<td align="center">'.$r['wsid'].'</td>
					<td align="left" style="background-color: #ffff99">'.$r['lokasi'].'</td>
					<td align="left" style="background-color: #8db4e2">'.$r['type'].'</td>
					<td align="center">'.$r['tanggal'].'</td>
					<td align="center">'.$r['ctr'].'</td>
					<td align="center">'.$r['D50'].'</td>
					<td align="right">'.$r['T50'].'</td>
					<td align="center">'.$r['D100'].'</td>
					<td align="right" style="background-color: #8db4e2">'.$r['T100'].'</td>
					<td align="center">'.$r['CSST1_50'].'</td>
					<td align="center" style="background-color: #92d050">'.$r['CSST1_100'].'</td>
					<td align="center">'.$r['CSST2_50'].'</td>
					<td align="center" style="background-color: #92d050">'.$r['CSST2_100'].'</td>
					<td align="center">'.$r['CSST3_50'].'</td>
					<td align="center" style="background-color: #92d050">'.$r['CSST3_100'].'</td>
					<td align="center">'.$r['CSST4_50'].'</td>
					<td align="center" style="background-color: #92d050">'.$r['CSST4_100'].'</td>
					<td align="center">'.$r['RJT50'].'</td>
					<td align="center" style="background-color: #92d050">'.$r['RJT100'].'</td>
					<td align="right" style="background-color: #8db4e2">'.$r['TOTALA'].'</td>
					<td align="center">'.$r['CSST1B50'].'</td>
					<td align="center" style="background-color: #92d050">'.$r['CSST1B100'].'</td>
					<td align="center"></td>
					<td align="center" style="background-color: #92d050"></td>
					<td align="center"></td>
					<td align="center" style="background-color: #92d050"></td>
					<td align="center"></td>
					<td align="center" style="background-color: #92d050"></td>
					<td align="center"></td>
					<td align="center" style="background-color: #92d050"></td>
					<td align="right" style="background-color: #8db4e2">'.$r['TOTALB'].'</td>
					<td align="center">'.$CCRM.'</td>
					<td align="right" style="background-color: #8db4e2">'.$selisih_crm.'</td>
					<td align="center">'.$r['now_time'].'</td>
					<td align="center">'.$r['now_ctr'].'</td>
					<td align="center">'.$r['now_D50'].'</td>
					<td align="center" style="background-color: #92d050">'.$r['now_D100'].'</td>
					<td align="right">'.$r['now_total'].'</td>
					<td align="center">'.$r['keterangan'].'</td>
				</tr>
			';
		}
		
		$html_content_footer .= '
			<tr>
				<td colspan="3">Jumlah Lokasi Pengisian <b>'.$i.'</b></td>
				<td colspan="3" style="background-color: #ffcc99">Total Pengisian Sebelumnya </td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_d50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_t50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_d100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_t100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_csst1_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_csst1_100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_csst2_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_csst2_100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_csst3_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_csst3_100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_csst4_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_csst4_100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_rjt_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_rjt_100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($TOTALA).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total2_csst1_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total2_csst1_100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total2_csst2_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total2_csst2_100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total2_csst3_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total2_csst3_100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total2_csst4_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total2_csst4_100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total2_rjt_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total2_rjt_100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($TOTALB).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($counter_crm).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($selisih).'</td>
				<td colspan="2">Total Pengisian Berikutnya </td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($now_D50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($now_D100).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($now_total).'</td>
				<td></td>
			</tr>
		';
		
		$html1 .= '
			<style>
				table, td, th {
					border: 1px solid black;
					font-size: 12px;
				}

				table {
					border-collapse: collapse;
				width: 100%;
				}

				th {
					padding: 5px;
					text-align: left;
					text-align: center;
				}
				
				td {
					padding: 5px;
				}
				
				tfoot td {
					border: 2px solid black;
				}
			</style>
			<table width="100%">
				<tr>
					<th rowspan="3">NO</th>
					<th rowspan="3">ATM ID</th>
					<th rowspan="3">Lokasi</th>
					<th rowspan="3">ATM/CRM</th>
					<th rowspan="3">Time</th>
					<th colspan="4">PENGISIAN BERIKUTNYA</th>
					<th rowspan="3" width="10%">KET <br>NAIK/TURUN LIMIT</th>
				</tr>
				<tr>
					<th rowspan="2">Jml Csst</th>
					<th colspan="2">Jml Isi</th>
					<th rowspan="2">Total</th>
				</tr>
				<tr>
					<th>50</th>
					<th>100</th>
				</tr>
			</table>
		';
		
		$html_content2 .= '
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		';
		
		$html2 .= '
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<style>
				* {
					box-sizing: border-box;
				}

				html {
					font-family: helvetica;
				}

				html,
				body {
					max-width: 100vw;
					
				}

				table {
					margin: auto;
					border-collapse: collapse;
					overflow-x: auto;
					display: block;
					width: fit-content;
					max-width: 100%;
					box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
					
				}

				td,
				th {
					border: solid rgb(200, 200, 200) 1px;
					padding: .5rem;
					font-size: 12px;
					
				}

				th {
					text-align: left;
					background-color: rgb(190, 220, 250);
					text-transform: uppercase;
					border: rgb(50, 50, 100) solid 1px;
					border-top: none;
					text-align: center;
				}

				td {
					white-space: nowrap;
					border-bottom: none;
					color: rgb(20, 20, 20);
					border: rgb(50, 50, 100) solid 1px;
				}

				td:first-of-type,
				th:first-of-type {
					border-left: none;
				}

				td:last-of-type,
				th:last-of-type {
					border-right: none;
				}
				
				table tfoot td {
					border: rgb(50, 50, 100) solid 2px;
				}
			</style>
			<div style="overflow-x:auto;">
				<table>
					<thead>
						<tr>
							<th style="vertical-align: middle" rowspan="4">NO</th>
							<th style="vertical-align: middle" rowspan="4">ATM ID</th>
							<th style="vertical-align: middle" rowspan="4">Lokasi</th>
							<th style="vertical-align: middle" rowspan="4">ATM/CRM</th>
							<th style="vertical-align: middle" colspan="28">REKONSILIASI</th>
							<th style="vertical-align: middle" rowspan="4">Counter (khusus CRM) (x1000)</th>
							<th style="vertical-align: middle" rowspan="2">Selisih</th>
							<th style="vertical-align: middle" rowspan="4">Time</th>
							<th style="vertical-align: middle" colspan="4">PENGISIAN BERIKUTNYA</th>
							<th style="vertical-align: middle" rowspan="4" width="10%">KET <br>NAIK/TURUN LIMIT</th>
						</tr>
						<tr>
							<th style="vertical-align: middle" colspan="6">PENGISIAN SEBELUMNYA</th>
							<th style="vertical-align: middle" colspan="11">PERHITUNGAN FISIK UANG</th>
							<th style="vertical-align: middle" colspan="11">PERHITUNGAN DISPENSED COUNTER</th>
							<th style="vertical-align: middle" rowspan="3">Jml Csst</th>
							<th style="vertical-align: middle" rowspan="2" colspan="2">Jml Isi</th>
							<th style="vertical-align: middle" rowspan="3">Total</th>
						</tr>
						<tr>
							<th style="vertical-align: middle"rowspan="2">TANGGAL</th>
							<th style="vertical-align: middle"rowspan="2">JML CSST</th>
							<th style="vertical-align: middle"width="200px" colspan="4">JML ISI</th>
							<th style="vertical-align: middle"colspan="2">CSST 1</th>
							<th style="vertical-align: middle"colspan="2">CSST 2</th>
							<th style="vertical-align: middle"colspan="2">CSST 3</th>
							<th style="vertical-align: middle"colspan="2">CSST 4</th>
							<th style="vertical-align: middle"colspan="2">RJT.</th>
							<th style="vertical-align: middle">TOTAL RP</th>
							<th style="vertical-align: middle"colspan="2">CSST 1</th>
							<th style="vertical-align: middle"colspan="2">CSST 2</th>
							<th style="vertical-align: middle"colspan="2">CSST 3</th>
							<th style="vertical-align: middle"colspan="2">CSST 4</th>
							<th style="vertical-align: middle"colspan="2">RJT.</th>
							<th style="vertical-align: middle">TOTAL RP</th>
							<th style="vertical-align: middle">TOTAL RP</th>
						</tr>
						<tr>
							<th style="vertical-align: middle" colspan="2">50</th>
							<th style="vertical-align: middle" colspan="2">100</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
							<th style="vertical-align: middle">(x1,000)</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
							<th style="vertical-align: middle">(x1,000)</th>
							<th style="vertical-align: middle">(x1,000)</th>
							<th style="vertical-align: middle">50</th>
							<th style="vertical-align: middle">100</th>
						</tr>
					</thead>
					<tbody>
						'.$html_content1.'
					</tbody>
					<tfoot>
						'.$html_content_footer.'
					</tfoot>
				</table>
			</div>
		';
		
		$html3 = '
			<style>
				* {
					box-sizing: border-box;
				}

				html {
					font-family: helvetica;
				}

				html,
				body {
					max-width: 100vw;
				}

				table {
					margin: auto;
					border-collapse: collapse;
					overflow-x: auto;
					display: block;
					width: fit-content;
					max-width: 100%;
					box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
				}

				td,
				th {
					border: solid rgb(200, 200, 200) 1px;
					padding: .5rem;
				}

				th {
					text-align: left;
					background-color: rgb(190, 220, 250);
					text-transform: uppercase;
					padding-top: 1rem;
					padding-bottom: 1rem;
					border-bottom: rgb(50, 50, 100) solid 2px;
					border-top: none;
				}

				td {
					white-space: nowrap;
					border-bottom: none;
					color: rgb(20, 20, 20);
				}

				td:first-of-type,
				th:first-of-type {
					border-left: none;
				}

				td:last-of-type,
				th:last-of-type {
					border-right: none;
				}
			</style>
			<table>
				<thead>
					<tr>
						<th>IP</th>
						<th>Server FQDN</th>
						<th>Type</th>
						<th>OS</th>
						<th>Memory</th>
						<th>CPU</th>
						<th>Bind Type</th>
						<th>Exim Type</th>
						<th>Instance(AWS)</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>56.208.157.93</td>
						<td>filler</td>
						<td>AWS</td>
						<td>Ubuntu</td>
						<td>3840 MB</td>
						<td>2.60 GHz</td>
						<td>master</td>
						<td>master</td>
						<td>m3.medium</td>
					</tr>
				</tbody>
			</table>
		';
		
		return $html2;
	}
	
	public function show_table2($data) {
		$inner_content_html = '';
		$footer_content_html = '';
		
		$i = 0;
		foreach($data as $r) {
			$i++;
			$inner_content_html .= '
				<tr>
					<td align="center">'.$i.'.</td>
					<td align="center">'.$r['date'].'</td>
					<td align="center">'.$r['wsid'].'</td>
					<td align="left" style="background-color: #ffff99">'.$r['lokasi'].'</td>
					<td align="right">'.$this->rupiah2($r['mutasi_kredit']).'</td>
					<td align="right">'.$this->rupiah2($r['document_counter']).'</td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="right">'.$r['selisih'].'</td>
					<td align="center"></td>
					<td align="center">'.$this->rupiah2($r['fisik_100']).'</td>
					<td align="center">'.$this->rupiah2($r['fisik_50']).'</td>
					<td align="center">'.$this->rupiah2($r['fisik_20']).'</td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center">'.$this->rupiah2($r['lbr_100']).'</td>
					<td align="center">'.$this->rupiah2($r['lbr_50']).'</td>
					<td align="center">'.$this->rupiah2($r['lbr_20']).'</td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
				</tr>
			';
		}
		
		return $this->template_html($inner_content_html, $footer_content_html);
	} 
	
	public function template_html($inner_content_html, $footer_content_html) {
		$template_html = '
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<style>
				* {
					box-sizing: border-box;
				}

				html {
					font-family: helvetica;
				}

				html,
				body {
					max-width: 100vw;
					
				}

				table {
					margin: auto;
					border-collapse: collapse;
					overflow-x: auto;
					display: block;
					width: fit-content;
					max-width: 100%;
					box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
					
				}

				td,
				th {
					border: solid rgb(200, 200, 200) 1px;
					padding: .5rem;
					font-size: 12px;
					
				}

				th {
					text-align: left;
					background-color: rgb(190, 220, 250);
					text-transform: uppercase;
					border: rgb(50, 50, 100) solid 1px;
					border-top: none;
					text-align: center;
				}

				td {
					white-space: nowrap;
					border-bottom: none;
					color: rgb(20, 20, 20);
					border: rgb(50, 50, 100) solid 1px;
				}

				td:first-of-type,
				th:first-of-type {
					border-left: none;
				}

				td:last-of-type,
				th:last-of-type {
					border-right: none;
				}
				
				table tfoot td {
					border: rgb(50, 50, 100) solid 2px;
				}
			</style>
			<div style="overflow-x:auto;">
				<table>
					<thead>
						<tr>
							<th style="vertical-align: middle" rowspan="4">
								NO
							</th>
							<th style="vertical-align: middle" rowspan="4">
								TGL REMOVE <br>
								(DD/MM/YYYY)
							</th>
							<th style="vertical-align: middle" rowspan="4">
								ATM ID
							</th>
							<th style="vertical-align: middle" rowspan="4">
								LOKASI CDM
							</th>
							<th style="vertical-align: middle" rowspan="4">
								MUTASI KREDIT <br>
								(PENYETORAN) <br>
								(x1000)
							</th>
							<th style="vertical-align: middle" rowspan="4">
								DOCUMENT COUNTER <br>
								SISA LOKASI (ADMIN CARD) <br>
								(x1000)
							</th>
							<th style="vertical-align: middle" rowspan="4">
								JAM ADMIN <br>
								(WIB)
							</th>
							<th style="vertical-align: middle" rowspan="4">
								TGL PROBLEM <br>
								(UANG NYANGKUT)
							</th>
							<th style="vertical-align: middle" rowspan="4">
								USER
							</th>
							<th style="vertical-align: middle" rowspan="4">
								UANG FISIK VS <br>
								ADMIN CARD
							</th>
							<th style="vertical-align: middle" rowspan="4">
								KETERANGAN
							</th>
							<th style="vertical-align: middle" rowspan="4">
								UANG FISIK <br>
								DENOM 100.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								UANG FISIK <br>
								DENOM 50.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								UANG FISIK <br>
								DENOM 20.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								UANG FISIK <br>
								DENOM 10.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								UANG FISIK <br>
								DENOM 5.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								UANG FISIK <br>
								DENOM 2.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								UANG FISIK <br>
								DENOM 1.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								JMH LBR <br>
								DENOM 100.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								JMH LBR <br>
								DENOM 50.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								JMH LBR <br>
								DENOM 20.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								JMH LBR <br>
								DENOM 10.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								JMH LBR <br>
								DENOM 5.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								JMH LBR <br>
								DENOM 2.000
							</th>
							<th style="vertical-align: middle" rowspan="4">
								JMH LBR <br>
								DENOM 1.000
							</th>
						</tr>
					</thead>
					<tbody>
						'.$inner_content_html.'
					</tbody>
					<tfoot>
						'.$footer_content_html.'
					</tfoot>
				</table>
			</div>
		';
		
		return $template_html;
	}
	
	public function get_data3() {
		// header('Content-Type: application/json');
		// error_reporting(0);
		$sql = "
		SELECT SQL_CALC_FOUND_ROWS
				D.wsid, 
				D.lokasi, 
				D.type, 
				B.id_bank, 
				B.date, 
				D.ctr,
				F.pcs_50000,
				F.pcs_100000,
				B.data_solve,
				B.cpc_process,
				B.ctr as jum_ctr
									FROM cashtransit A
									LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, ctr FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
									LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
									LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
									LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(A.id=F.id_cashtransit)
									WHERE B.state='ro_atm' AND B.data_solve!='' AND 
									B.id IN (
										SELECT MAX(id)
										FROM cashtransit_detail
										WHERE state='ro_atm' AND data_solve!=''
										GROUP BY id_bank
									)";
		
		// SELECT *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail 
									// FROM cashtransit 
									// LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) 
									// LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) 
									// LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
									// WHERE cashtransit_detail.state='ro_atm' AND cashtransit_detail.data_solve!='' AND 
									// cashtransit_detail.id IN (
										// SELECT MAX(id)
										// FROM cashtransit_detail
										// WHERE state='ro_atm'
										// GROUP BY id_bank
									// )
		$result = $this->db->query($sql)->result();
		
		$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
		$rows = $this->db->query($sql)->row_array();
		$total_rows = $rows['found_rows'];
		// echo "<pre>";
		// print_r($result);
		// echo "</pre>";
		
		$no = 0;
		foreach($result as $row) {
			$no++;
			$data = json_decode($row->cpc_process);
			
			// print_r($data);
			
			$query2 = "SELECT *, A.id FROM cashtransit_detail A LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) B ON(A.id_bank=B.id) WHERE A.id_bank='".$row->id_bank."' AND A.state='ro_atm' AND A.data_solve='' AND date >= '".date('Y-m-d')."%'";
			$rows = $this->db->query($query2)->row();
			// echo "<pre>";
			// print_r($rows);
			
			// echo "<pre>";
			// print_r($data);
			// echo "</pre>";
			$ctr_1 = ($data->cart_1_seal!=="") ? 1 : 0;
			$ctr_2 = ($data->cart_2_seal!=="") ? 1 : 0;
			$ctr_3 = ($data->cart_3_seal!=="") ? 1 : 0;
			$ctr_4 = ($data->cart_4_seal!=="") ? 1 : 0;

			// $ctr = $ctr_1+$ctr_2+ +$ctr_3+$ctr_4;
			$ctr = $row->jum_ctr;
			
			$TOTALA = 50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_1_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_1_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_2_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_2_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_3_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_3_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_4_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_4_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->div_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->div_no));
			
			$TOTALB = 50*(intval($row->pcs_50000)==0 ? 0 : intval($data->return_dispensed)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->return_dispensed));
			
			$client[] = array(
				'no' => $no,
				'wsid' => $row->wsid,
				'lokasi' => $row->lokasi,
				'type' => $row->type,
				'tanggal' => date("Y-m-d", strtotime($row->date)),
				'ctr' => $ctr,
				'pcs_50000' => $row->pcs_50000,
				'pcs_100000' => $row->pcs_100000,
				'denom' => (intval($row->pcs_50000)!==0 ? "50000" : "100000"),
				'denom_50' => ($row->pcs_50000=="" ? "" : $row->pcs_50000),
				'denom_100' => ($row->pcs_100000=="" ? "" : $row->pcs_100000),
				'ttl_ctr' => ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)),
				'total' => ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100)),
				'csst1' => $data->cart_1_no,
				'csst2' => $data->cart_2_no,
				'csst3' => $data->cart_3_no,
				'csst4' => $data->cart_4_no,
				'reject' => (isset($data->div_no) ? $data->div_no : 0),
				'D50' => $ctr * (intval($row->pcs_50000)==0 ? 0 : $row->pcs_50000),	
				'D100' => $ctr * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000),
				'T50' => ($ctr * (intval($row->pcs_50000)==0 ? 0 : $row->pcs_50000))*50,
				'T100' => ($ctr * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000))*100,
				'CSST1_50' => (intval($row->pcs_50000)==0 ? 0 : intval($data->cart_1_no)),
				'CSST1_100' => (intval($row->pcs_100000)==0 ? 0 : intval($data->cart_1_no)),
				'CSST2_50' => (intval($row->pcs_50000)==0 ? 0 : intval($data->cart_2_no)),
				'CSST2_100' => (intval($row->pcs_100000)==0 ? 0 : intval($data->cart_2_no)),
				'CSST3_50' => (intval($row->pcs_50000)==0 ? 0 : intval($data->cart_3_no)),
				'CSST3_100' => (intval($row->pcs_100000)==0 ? 0 : intval($data->cart_3_no)),
				'CSST4_50' => (intval($row->pcs_50000)==0 ? 0 : intval($data->cart_4_no)),
				'CSST4_100' => (intval($row->pcs_100000)==0 ? 0 : intval($data->cart_4_no)),
				'RJT50' => (intval($row->pcs_50000)==0 ? 0 : intval((isset($data->div_no) ? $data->div_no : 0))),
				'RJT100' => (intval($row->pcs_100000)==0 ? 0 : intval((isset($data->div_no) ? $data->div_no : 0))),
				'TOTALA' => $TOTALA,
				'CSST1B50' => (intval($row->pcs_50000)==0 ? 0 : $data->return_dispensed),
				'CSST1B100' => (intval($row->pcs_100000)==0 ? 0 : $data->return_dispensed),
				'TOTALB' => $TOTALB,
				'JML_CSST02' => (isset($rows->ctr) ? intval($rows->ctr) : 0),
				'D50B' => (isset($rows->ctr) ? intval($rows->ctr) : 0) * (isset($rows->pcs_50000) ? intval($rows->pcs_50000) : 0),
				'T50B' => (isset($rows->ctr) ? intval($rows->ctr) : 0) * (isset($rows->pcs_50000) ? intval($rows->pcs_50000) : 0) *50,
				'D100B' => (isset($rows->ctr) ? intval($rows->ctr) : 0) * (isset($rows->pcs_100000) ? intval($rows->pcs_100000) : 0),
				'T100B' => (isset($rows->ctr) ? intval($rows->ctr) : 0) * (isset($rows->pcs_100000) ? intval($rows->pcs_100000) : 0) * 100,
			);
		}
		
		// echo "<pre>";
		// print_r($client);
		// echo "</pre>";
		
		$datax[] = array(
		   'TotalRows' => $total_rows,	
		   'Rows' => $client
		);
		echo json_encode($datax);
	}
	
	public function rupiah($s) {
		$s = str_replace(".","",$s);
		$a = ($s==0 ? "" : number_format($s, 0, ",", ","));
		return number_format($s, 0, ",", ",");
	}
	
	public function rupiah2($s) {
		$a = ($s==0 ? 0 : number_format($s, 0, ",", ","));
		return $a;
	}
	
	public function get_data_fix($act="") {
		// $act = $this->uri->segment(3);
		error_reporting(0);
		$var_tgl = "";
		
		if($act=="show") {		
			
		} else if($act=="show_tanggal") {		
		} else {
			$var_tgl = "AND B.updated_date LIKE '%".date('Y-m-d')."%'";
		}
		
		$sql = "
		SELECT SQL_CALC_FOUND_ROWS
				A.id, 
				D.wsid, 
				D.lokasi, 
				D.type, 
				B.id_bank, 
				B.date, 
				B.updated_date, 
				D.ctr,
				B.pcs_50000,
				B.pcs_100000,
				B.data_solve,
				B.cpc_process,
				B.ctr as jum_ctr
									FROM cashtransit A
									LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, updated_date, ctr, pcs_50000, pcs_100000 FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
									LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
									LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
									LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(A.id=F.id_cashtransit)
									WHERE B.state='ro_atm' AND B.data_solve!='' AND 
									D.type!='CDM' AND
									B.id IN (
										SELECT MAX(id)
										FROM cashtransit_detail
										WHERE state='ro_atm' AND data_solve!=''
										GROUP BY id_bank
									) 
									$var_tgl
									GROUP BY D.wsid";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$no = 0;
		foreach($result as $row) {
			if($act=="show") {		
				$format_now_time = date("H:i", strtotime($row->updated_date));
			} else if($act=="show_tanggal") {		
				$format_now_time = date("Y-m-d H:i", strtotime($row->updated_date));
			} else {
				$format_now_time = date("H:i", strtotime($row->updated_date));
			}
			
			$act = $row->type;
			
			$no++;
			$data = json_decode($row->cpc_process);
			
			$ctr_1 = ($data->cart_1_seal!=="") ? 1 : 0;
			$ctr_2 = ($data->cart_2_seal!=="") ? 1 : 0;
			$ctr_3 = ($data->cart_3_seal!=="") ? 1 : 0;
			$ctr_4 = ($data->cart_4_seal!=="") ? 1 : 0;
			
			$ctr = $row->jum_ctr;
			
			$TOTALA = 50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_1_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_1_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_2_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_2_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_3_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_3_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->cart_4_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->cart_4_no))+
					  50*(intval($row->pcs_50000)==0 ? 0 : intval($data->div_no)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->div_no));
			
			$TOTALB = 50*(intval($row->pcs_50000)==0 ? 0 : intval($data->return_dispensed)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($data->return_dispensed));
			
			list($date, $time) = explode(" ", $row->date);
			$data_now[] = array(
				'no' => $no,
				'date' => $date,
				'wsid' => $row->wsid,
				'lokasi' => $row->lokasi,
				'type' => $row->type,
				'tanggal' => date("Y-m-d", strtotime($row->date)),
				'time' => date("H:i", strtotime($row->updated_date)),
				'ctr' => $ctr,
				'D50' => $ctr * (intval($row->pcs_50000)==0 ? 0 : $row->pcs_50000),	
				'D100' => $ctr * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000),
				'T50' => ($ctr * (intval($row->pcs_50000)==0 ? 0 : $row->pcs_50000))*50,
				'T100' => ($ctr * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000))*100,
				'total' => ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100)),
			);
			
			$sql2 = "
			SELECT SQL_CALC_FOUND_ROWS
					A.id, 
					D.wsid, 
					D.lokasi, 
					D.type, 
					B.id_bank, 
					B.date, 
					D.act,
					D.ctr,
					F.pcs_50000,
					F.pcs_100000,
					B.data_solve,
					B.cpc_process,
					B.ctr as jum_ctr
										FROM cashtransit A
										LEFT JOIN (SELECT id, id_bank, id_cashtransit, state, data_solve, cpc_process, date, ctr FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
										LEFT JOIN (SELECT id, name FROM master_branch) C ON(A.branch=C.id) 
										LEFT JOIN (SELECT id, wsid, lokasi, type, ctr FROM client) D ON(B.id_bank=D.id)  
										LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) F ON(B.id=F.id_ctrs)
										WHERE B.state='ro_atm' AND B.data_solve!='' AND D.wsid='$row->wsid' AND A.id<'$row->id' AND
										A.id IN (
											SELECT cashtransit.id 
											FROM cashtransit_detail LEFT JOIN cashtransit ON(cashtransit.id=cashtransit_detail.id_cashtransit) 
											WHERE state='ro_atm' AND data_solve!='' AND cashtransit.id<$row->id
										)";
			
			$row2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql2), array(CURLOPT_BUFFERSIZE => 10)));
			
			if(count($row2)==0) {
				$row2 = $row;
			}
			
			$ctr2 = $row2->jum_ctr;
			
			$TOTALA = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_1_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_1_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_2_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_2_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_3_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_3_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->cart_4_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->cart_4_no))+
					  50*(intval($row2->pcs_50000)==0 ? 0 : intval($data->div_no)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($data->div_no));
			
			
			// $data = json_decode($row->cpc_process);
			
			$datax = json_decode($row2->data_solve);
			$data2 = json_decode($row->cpc_process);
			list($date, $time) = explode(" ", $row2->date);
			
			// KETERANGAN 
			$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
			$now_total = ($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100));
			$s50k = ($ctr2 * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$s100k = ($ctr2 * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000))*100;
			
			
			$dispensed = str_replace(".", "", str_replace(",", "", $data2->return_dispensed));
			// $dispensed = 4699;
			
			$TOTALB = 50*(intval($row2->pcs_50000)==0 ? 0 : intval($dispensed)) +
					  100*(intval($row2->pcs_100000)==0 ? 0 : intval($dispensed));
					  
			// $dispensed = intval(str_replace(".", "", $data2->return_withdraw))/1000;
			// $TOTALB = $dispensed;
		
			$t50 = ($ctr2 * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$t100 = ($ctr2 * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000))*100;
			$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
			
			$hasil = 0;
			$ket = "";
			// echo (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)."<br>";
			if($denom=="50000") {
				if($now_total!==0) {
					if($s50k!==0) {
						$hasil = $now_total-$s50k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			} else {
				if($now_total!==0) {
					if($s100k!==0) {
						$hasil = $now_total-$s100k;
					} else {
						$ket = "PENGISIAN";
					}
				} else {
					$ket = "PENGOSONGAN";
				}
			}
			 
			if($ket=="") {
				if($hasil==0) {
					$ket = "";
				} else if($hasil>0) {
					$ket = "NAIK";
				} else if($hasil<0) {
					$ket = "TURUN";
				}
			}
			
			if($act=="ATM") {
			
				$data_prev[] = array(
					'no' => $no,
					'date' => $date,
					'wsid' => $row->wsid,
					'lokasi' => $row->lokasi,
					'type' => $row->type,
					'tanggal' => ($data2->last_clear==null ? "" : date("Y-m-d", strtotime($data2->last_clear))),
					'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
					'ctr' => $ctr2,
					'T50' => $t50,
					'T100' => $t100,
					'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
					'csst1' => $data2->cart_1_no,
					'csst2' => $data2->cart_2_no,
					'csst3' => $data2->cart_3_no,
					'csst4' => $data2->cart_4_no,
					'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
					'D50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($ctr2 * $row2->pcs_50000)),	
					'D100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($ctr2 * $row2->pcs_100000)),
					'T50' => ((intval($row2->pcs_50000)==0 ? 0 : $this->rupiah($ctr2 * $row2->pcs_50000 * 50))),
					'T100' => ((intval($row2->pcs_100000)==0 ? "-" : $this->rupiah($ctr2 * $row2->pcs_100000 * 100))),
					'CSST1_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_1_no)),
					'CSST1_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_1_no)),
					'CSST2_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
					'CSST2_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_2_no)),
					'CSST3_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
					'CSST3_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_3_no)),
					'CSST4_50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
					'CSST4_100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($data2->cart_4_no)),
					'RJT50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
					'RJT100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah(intval((isset($data2->div_no) ? $data2->div_no : 0)))),
					'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
					'CSST1B50' => (intval($row2->pcs_50000)==0 ? "" : $this->rupiah($dispensed)),
					'CSST1B100' => (intval($row2->pcs_100000)==0 ? "" : $this->rupiah($dispensed)),
					'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
					'selisih' => ($selisih=="" ? "-" : $this->rupiah($selisih)),
					'now_time' => $format_now_time,
					'now_ctr' => $row->jum_ctr,
					'now_D50' => (intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_50000)),	
					'now_D100' => (intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_100000)),
					'now_T50' => ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_50000 * 50))),
					'now_T100' => ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_100000 * 100))),
					'now_total' => $this->rupiah(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100))),
					'keterangan' => $ket,
				);
			} else if($act=="CRM") { 
				$t50 = ($ctr2 * (intval($data2->s50k)==0 ? 0 : $data2->s50k))*50;
				$t100 = ($ctr2 * (intval($data2->s100k)==0 ? 0 : $data2->s100k))*100;
				$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
				
				$TOTALA = 50*(intval(json_decode($data2->cart_1_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_1_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_1_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_1_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_2_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_2_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_2_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_2_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_3_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_3_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_3_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_3_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_4_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_4_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_4_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_4_no, true)['100'])) +
						  50*(intval(json_decode($data2->cart_5_no, true)['50'])==0 ? 0 : intval(json_decode($data2->cart_5_no, true)['50'])) +
						  100*(intval(json_decode($data2->cart_5_no, true)['100'])==0 ? 0 : intval(json_decode($data2->cart_5_no, true)['100'])) +
						  50*(intval(json_decode($data2->div_no, true)['50'])==0 ? 0 : intval(json_decode($data2->div_no, true)['50'])) +
						  100*(intval(json_decode($data2->div_no, true)['100'])==0 ? 0 : intval(json_decode($data2->div_no, true)['100']));
				
				$data_prev[] = array(
					'no' => $no,
					'date' => $date,
					'wsid' => $row->wsid,
					'lokasi' => $row->lokasi,
					'type' => $row->type,
					'tanggal' => ($data2->last_clear==null ? "" : date("Y-m-d", strtotime($data2->last_clear))),
					'time' => ($row2->updated_date==null ? "" : date("H:i", strtotime($row2->updated_date))),
					'ctr' => $ctr2,
					'T50' => $t50,
					'T100' => $t100,
					'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
					'csst1' => $data2->cart_1_no,
					'csst2' => $data2->cart_2_no,
					'csst3' => $data2->cart_3_no,
					'csst4' => $data2->cart_4_no,
					'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
					'D50' => (intval($data2->s50k)==0 ? "" : $this->rupiah($data2->s50k)),	
					'D100' => (intval($data2->s100k)==0 ? "" : $this->rupiah($data2->s100k)),
					'T50' => ((intval($data2->s50k)==0 ? 0 : $this->rupiah($data2->s50k * 50))),
					'T100' => ((intval($data2->s100k)==0 ? "-" : $this->rupiah($data2->s100k * 100))),
					'CSST1_50' => intval(json_decode($data2->cart_1_no, true)['50']),
					'CSST1_100' => intval(json_decode($data2->cart_1_no, true)['100']),
					'CSST2_50' => intval(json_decode($data2->cart_2_no, true)['50']),
					'CSST2_100' => intval(json_decode($data2->cart_2_no, true)['100']),
					'CSST3_50' => intval(json_decode($data2->cart_3_no, true)['50']),
					'CSST3_100' => intval(json_decode($data2->cart_3_no, true)['100']),
					'CSST4_50' => intval(json_decode($data2->cart_4_no, true)['50']),
					'CSST4_100' => intval(json_decode($data2->cart_4_no, true)['100']),
					'CSST5_50' => intval(json_decode($data2->cart_5_no, true)['50']),
					'CSST5_100' => intval(json_decode($data2->cart_5_no, true)['100']),
					'RJT50' => intval(json_decode($data2->div_no, true)['50']),
					'RJT100' => intval(json_decode($data2->div_no, true)['100']),
					'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
				);
			}
		}
		
		return $data_prev;
	}
	
	public function export_rekon() {
		$data_record = $this->get_data_fix("show");
		
		$i = 0;
		$total_d50 = 0;
		$total_t50 = 0;
		$total_d100 = 0;
		$total_t100 = 0;
		$total_csst1_100 = 0;
		$total_csst2_100 = 0;
		$total_csst3_100 = 0;
		$total_csst4_100 = 0;
		$total_csst1_50 = 0;
		$total_csst2_50 = 0;
		$total_csst3_50 = 0;
		$total_csst4_50 = 0;
		$total_rjt_100 = 0;
		$total_rjt_50 = 0;
		$total2_csst1_100 = 0;
		$total2_csst2_100 = 0;
		$total2_csst3_100 = 0;
		$total2_csst4_100 = 0;
		$total2_csst1_50 = 0;
		$total2_csst2_50 = 0;
		$total2_csst3_50 = 0;
		$total2_csst4_50 = 0;
		$total2_rjt_100 = 0;
		$total2_rjt_50 = 0;
		$TOTALA = 0;
		$TOTALB = 0;
		$counter_crm = 0;
		$selisih = 0;
		$now_D50 = 0;
		$now_D100 = 0;
		$now_total = 0;
		foreach($data_record as $r) {
			$i++;
			
			$total_d50 = $total_d50 + intval(str_replace(",", "", $r['D50']));
			$total_t50 = $total_t50 + intval(str_replace(",", "", $r['T50']));
			$total_d100 = $total_d100 + intval(str_replace(",", "", $r['D100']));
			$total_t100 = $total_t100 + intval(str_replace(",", "", $r['T100']));
			
			$total_csst1_100 = $total_csst1_100 + intval(str_replace(",", "", $r['CSST1_100']));
			$total_csst2_100 = $total_csst2_100 + intval(str_replace(",", "", $r['CSST2_100']));
			$total_csst3_100 = $total_csst3_100 + intval(str_replace(",", "", $r['CSST3_100']));
			$total_csst4_100 = $total_csst4_100 + intval(str_replace(",", "", $r['CSST4_100']));
			
			$total_csst1_50 = $total_csst1_50 + intval(str_replace(",", "", $r['CSST1_50']));
			$total_csst2_50 = $total_csst2_50 + intval(str_replace(",", "", $r['CSST2_50']));
			$total_csst3_50 = $total_csst3_50 + intval(str_replace(",", "", $r['CSST3_50']));
			$total_csst4_50 = $total_csst4_50 + intval(str_replace(",", "", $r['CSST4_50']));
			
			$total2_csst1_100 = $total2_csst1_100 + intval(str_replace(",", "", $r['CSST1B100']));
			$total2_csst2_100 = $total2_csst2_100 + intval(str_replace(",", "", $r['CSST2B100']));
			$total2_csst3_100 = $total2_csst3_100 + intval(str_replace(",", "", $r['CSST3B100']));
			$total2_csst4_100 = $total2_csst4_100 + intval(str_replace(",", "", $r['CSST4B100']));
			
			$total2_csst1_50 = $total2_csst1_50 + intval(str_replace(",", "", $r['CSST1B50']));
			$total2_csst2_50 = $total2_csst2_50 + intval(str_replace(",", "", $r['CSST2B50']));
			$total2_csst3_50 = $total2_csst3_50 + intval(str_replace(",", "", $r['CSST3B50']));
			$total2_csst4_50 = $total2_csst4_50 + intval(str_replace(",", "", $r['CSST4B50']));
			
			$total_rjt_100 = $total_rjt_100 + intval(str_replace(",", "", $r['RJT100']));
			$total_rjt_50 = $total_rjt_50 + intval(str_replace(",", "", $r['RJT50']));
			
			$TOTALA = $TOTALA + intval(str_replace(",", "", $r['TOTALA']));
			$TOTALB = $TOTALB + intval(str_replace(",", "", $r['TOTALB']));
			
			$counter_crm = $counter_crm + intval(str_replace(",", "", $r['counter_crm']));
			$selisih = $selisih + intval(str_replace(",", "", $r['selisih']));
			$now_D50 = $now_D50 + intval(str_replace(",", "", $r['now_D50']));
			$now_D100 = $now_D100 + intval(str_replace(",", "", $r['now_D100']));
			$now_total = $now_total + intval(str_replace(",", "", $r['now_total']));
		}
		$dari_rekon_100 = ($total_csst1_100 +
		                   $total_csst2_100 +
		                   $total_csst3_100 +
		                   $total_csst4_100 +
						   $total_rjt_100) * 100;
		
		$dari_rekon_50 =  ($total_csst1_50 +
		                   $total_csst2_50 +
		                   $total_csst3_50 +
		                   $total_csst4_50 +
						   $total_rjt_50) * 50;
						   
						   
		$pengisian_100 = $now_D100*100;
		$pengisian_50  = $now_D50*50;
		
		$reader = IOFactory::createReader('Xls');
		$spreadsheet = $reader->load('report_rekon_atm.xls');

		$sheet1 = $spreadsheet->getSheetByName('Daily');
		$sheet1->setCellValue('F13', $dari_rekon_100);
		$sheet1->setCellValue('G13', $dari_rekon_50);
		$sheet1->setCellValue('F20', $pengisian_100);
		$sheet1->setCellValue('G20', $pengisian_50);


		$sheet2 = $spreadsheet->getSheetByName('REKON ATM');
		
		$baseRow = 14;
		$i = 0;
		$row = 0;
		$sheet2->setCellValue('B9', date("d-m-Y"));
		foreach($data_record as $k => $r) {
			$row = $baseRow + $i;
			$sheet2->insertNewRowBefore($row+1,1);
			$sheet2->setCellValue('A'.$row,$i+1);
			$sheet2->setCellValue('B'.$row,$r['wsid']);
			$sheet2->setCellValue('C'.$row,$r['lokasi']);
			$sheet2->setCellValue('D'.$row,$r['type']);
			$sheet2->setCellValue('E'.$row,$r['tanggal']);
			$sheet2->setCellValue('F'.$row,$r['ctr']);
			$sheet2->setCellValue('G'.$row,$r['D50']);
			$sheet2->setCellValue('H'.$row,$r['T50']);
			$sheet2->setCellValue('I'.$row,$r['D100']);
			$sheet2->setCellValue('J'.$row,$r['T100']);
			$sheet2->setCellValue('K'.$row,$r['CSST1_50']);
			$sheet2->setCellValue('L'.$row,$r['CSST1_100']);
			$sheet2->setCellValue('M'.$row,$r['CSST2_50']);
			$sheet2->setCellValue('N'.$row,$r['CSST2_100']);
			$sheet2->setCellValue('O'.$row,$r['CSST3_50']);
			$sheet2->setCellValue('P'.$row,$r['CSST3_100']);
			$sheet2->setCellValue('Q'.$row,$r['CSST4_50']);
			$sheet2->setCellValue('R'.$row,$r['CSST4_100']);
			$sheet2->setCellValue('S'.$row,$r['RJT50']);
			$sheet2->setCellValue('T'.$row,$r['RJT100']);
			$sheet2->setCellValue('X'.$row,$r['TOTALA']);
			$sheet2->setCellValue('Y'.$row,$r['CSST1B50']);
			$sheet2->setCellValue('Z'.$row,$r['CSST1B100']);
			$sheet2->setCellValue('AL'.$row,$r['TOTALB']);
			$sheet2->setCellValue('AM'.$row,'0');
			$sheet2->setCellValue('AN'.$row,$r['selisih']);
			$sheet2->setCellValue('AO'.$row,$r['now_time']);
			$sheet2->setCellValue('AP'.$row,$r['now_ctr']);
			$sheet2->setCellValue('AQ'.$row,$r['now_D50']);
			$sheet2->setCellValue('AR'.$row,$r['now_D100']);
			$sheet2->setCellValue('AS'.$row,$r['now_total']);
			$sheet2->setCellValue('AT'.$row,$r['keterangan']);
			$i++;
		}
		$sheet2->removeRow($row+1,1);
		$sheet2->setCellValue('A'.($row+1),'Jumlah Lokasi Pengisian : '+$i);
		$sheet2->setCellValue('G'.($row+1),$this->rupiah2($total_d50));
		$sheet2->setCellValue('H'.($row+1),$this->rupiah2($total_t50));
		$sheet2->setCellValue('I'.($row+1),$this->rupiah2($total_d100));
		$sheet2->setCellValue('J'.($row+1),$this->rupiah2($total_t100));
		$sheet2->setCellValue('K'.($row+1),$this->rupiah2($total_csst1_50));
		$sheet2->setCellValue('L'.($row+1),$this->rupiah2($total_csst1_100));
		$sheet2->setCellValue('M'.($row+1),$this->rupiah2($total_csst2_50));
		$sheet2->setCellValue('N'.($row+1),$this->rupiah2($total_csst2_100));
		$sheet2->setCellValue('O'.($row+1),$this->rupiah2($total_csst3_50));
		$sheet2->setCellValue('P'.($row+1),$this->rupiah2($total_csst3_100));
		$sheet2->setCellValue('Q'.($row+1),$this->rupiah2($total_csst4_50));
		$sheet2->setCellValue('R'.($row+1),$this->rupiah2($total_csst4_100));
		$sheet2->setCellValue('S'.($row+1),$this->rupiah2($total_rjt_50));
		$sheet2->setCellValue('T'.($row+1),$this->rupiah2($total_rjt_100));
		$sheet2->setCellValue('X'.($row+1),$this->rupiah2($TOTALA));
		$sheet2->setCellValue('Y'.($row+1),$this->rupiah2($total2_csst1_50));
		$sheet2->setCellValue('Z'.($row+1),$this->rupiah2($total2_csst1_100));
		
		$sheet2->setCellValue('AA'.($row+1),'');
		$sheet2->setCellValue('AB'.($row+1),'');
		$sheet2->setCellValue('AC'.($row+1),'');
		$sheet2->setCellValue('AD'.($row+1),'');
		$sheet2->setCellValue('AE'.($row+1),'');
		$sheet2->setCellValue('AF'.($row+1),'');
		$sheet2->setCellValue('AG'.($row+1),'');
		$sheet2->setCellValue('AH'.($row+1),'');
		$sheet2->setCellValue('AI'.($row+1),'');
		$sheet2->setCellValue('AJ'.($row+1),'');
		$sheet2->setCellValue('AK'.($row+1),'');
		
		$sheet2->setCellValue('AL'.($row+1),$this->rupiah2($TOTALB));
		$sheet2->setCellValue('AM'.($row+1),$this->rupiah2($counter_crm));
		$sheet2->setCellValue('AN'.($row+1),$this->rupiah2($selisih));
		$sheet2->setCellValue('AP'.($row+1),'Total Pengisian Berikutnya');
		$sheet2->setCellValue('AQ'.($row+1),$this->rupiah2($now_D50*50));
		$sheet2->setCellValue('AR'.($row+1),$this->rupiah2($now_D100*100));
		$sheet2->setCellValue('AS'.($row+1),$this->rupiah2($now_total));

		$filename = 'REKON_ATM_'.date("M Y");
		// Redirect output to a client’s web browser (Xls)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, 'Xls');
		$writer->save('php://output');
	}
	
	public function export_rekon_atm() {
		$data_record = $this->get_data2();
		
		$reader = IOFactory::createReader('Xls');
		$spreadsheet = $reader->load('report_rekon_atm.xls');
		
		
		// echo "<pre>";
		// print_r($data_record);
		
		$baseRow = 14;
		$i = 0;
		$row = 0;
		
		$spreadsheet->getActiveSheet()->setCellValue('B9', date("d-m-Y"));
		foreach($data_record as $k => $r) {
			$row = $baseRow + $i;
			$spreadsheet->getActiveSheet()->insertNewRowBefore($row+1,1);
			$spreadsheet->getActiveSheet()->setCellValue('A'.$row,$i+1);
			$spreadsheet->getActiveSheet()->setCellValue('B'.$row,$r['wsid']);
			$spreadsheet->getActiveSheet()->setCellValue('C'.$row,$r['lokasi']);
			$spreadsheet->getActiveSheet()->setCellValue('D'.$row,$r['type']);
			$spreadsheet->getActiveSheet()->setCellValue('E'.$row,$r['tanggal']);
			$spreadsheet->getActiveSheet()->setCellValue('F'.$row,$r['ctr']);
			$spreadsheet->getActiveSheet()->setCellValue('G'.$row,'=+F'.$row.'*'.$r['denom_50']);
			$spreadsheet->getActiveSheet()->setCellValue('H'.$row,'=G'.$row.'*$G$13');
			$spreadsheet->getActiveSheet()->setCellValue('I'.$row,'=+F'.$row.'*'.$r['denom_100']);
			$spreadsheet->getActiveSheet()->setCellValue('J'.$row,'=I'.$row.'*$I$13');
			$spreadsheet->getActiveSheet()->setCellValue('K'.$row,($r['pcs_50000']==0 ? "" : $r['csst1']));
			$spreadsheet->getActiveSheet()->setCellValue('L'.$row,($r['pcs_100000']==0 ? "" : $r['csst1']));
			$spreadsheet->getActiveSheet()->setCellValue('M'.$row,($r['pcs_50000']==0 ? "" : $r['csst2']));
			$spreadsheet->getActiveSheet()->setCellValue('N'.$row,($r['pcs_100000']==0 ? "" : $r['csst2']));
			$spreadsheet->getActiveSheet()->setCellValue('O'.$row,($r['pcs_50000']==0 ? "" : $r['csst3']));
			$spreadsheet->getActiveSheet()->setCellValue('P'.$row,($r['pcs_100000']==0 ? "" : $r['csst3']));
			$spreadsheet->getActiveSheet()->setCellValue('Q'.$row,($r['pcs_50000']==0 ? "" : $r['csst4']));
			$spreadsheet->getActiveSheet()->setCellValue('R'.$row,($r['pcs_100000']==0 ? "" : $r['csst4']));
			$spreadsheet->getActiveSheet()->setCellValue('S'.$row,($r['pcs_50000']==0 ? "" : $r['reject']));
			$spreadsheet->getActiveSheet()->setCellValue('T'.$row,($r['pcs_100000']==0 ? "" : $r['reject']));
			$spreadsheet->getActiveSheet()->setCellValue('X'.$row,'=K'.$row.'*$K$13+L'.$row.'*$L$13+M'.$row.'*$M$13+N'.$row.'*$N$13+O'.$row.'*$O$13+P'.$row.'*$P$13+Q'.$row.'*$Q$13+R'.$row.'*$R$13+S'.$row.'*$S$13+T'.$row.'*$T$13+U'.$row.'*$U$13+V'.$row.'*$V$13+W'.$row.'*$W$13');
			$spreadsheet->getActiveSheet()->setCellValue('Y'.$row,($r['pcs_50000']==0 ? '=I'.$row.'-(K'.$row.'+L'.$row.'+M'.$row.'+N'.$row.'+O'.$row.'+P'.$row.'+Q'.$row.'+R'.$row.'+S'.$row.'+T'.$row.')' : '=G'.$row.'-(K'.$row.'+L'.$row.'+M'.$row.'+N'.$row.'+O'.$row.'+P'.$row.'+Q'.$row.'+R'.$row.'+S'.$row.'+T'.$row.')'));
			$spreadsheet->getActiveSheet()->setCellValue('AL'.$row,'=Y'.$row.'*$K$13+Z'.$row.'*$L$13+AA'.$row.'*$M$13+AB'.$row.'*$N$13+AC'.$row.'*$O$13+AD'.$row.'*$P$13+AE'.$row.'*$Q$13+AF'.$row.'*$R$13+AG'.$row.'*$S$13+AH'.$row.'*$T$13+AI'.$row.'*$U$13+AJ'.$row.'*$V$13+AK'.$row.'*$W$13');
			$spreadsheet->getActiveSheet()->setCellValue('AM'.$row,'=((0*50)+(0*100))');
			$spreadsheet->getActiveSheet()->setCellValue('AN'.$row,'=IF(D'.$row.'="ATM",((X'.$row.'+AL'.$row.')-(H'.$row.'+J'.$row.')),(X'.$row.'-AM'.$row.'))');
			$spreadsheet->getActiveSheet()->setCellValue('AP'.$row,'');
			$spreadsheet->getActiveSheet()->setCellValue('AQ'.$row,'');
			$spreadsheet->getActiveSheet()->setCellValue('AR'.$row,'');
			$spreadsheet->getActiveSheet()->setCellValue('AS'.$row,'');
			$spreadsheet->getActiveSheet()->setCellValue('AT'.$row,'');
			$i++;
		}
		// $spreadsheet->getActiveSheet()->duplicateStyle($spreadsheet->getActiveSheet()->getStyle('H'.$baseRow),'H'.($baseRow).':H'.($row));
		// $spreadsheet->getActiveSheet()->setCellValue('G'.($row+1),'=SUM(G'.$baseRow.':G'.($row+3).')');
		
		$spreadsheet->getActiveSheet()->removeRow($row+1,1);
		$spreadsheet->getActiveSheet()->setCellValue('G'.($row+1),'=SUM(G'.$baseRow.':G'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('H'.($row+1),'=SUM(H'.$baseRow.':H'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('I'.($row+1),'=SUM(I'.$baseRow.':I'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('J'.($row+1),'=SUM(J'.$baseRow.':J'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('K'.($row+1),'=SUM(K'.$baseRow.':K'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('L'.($row+1),'=SUM(L'.$baseRow.':L'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('M'.($row+1),'=SUM(M'.$baseRow.':M'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('N'.($row+1),'=SUM(N'.$baseRow.':N'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('O'.($row+1),'=SUM(O'.$baseRow.':O'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('P'.($row+1),'=SUM(P'.$baseRow.':P'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('Q'.($row+1),'=SUM(Q'.$baseRow.':Q'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('R'.($row+1),'=SUM(R'.$baseRow.':R'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('S'.($row+1),'=SUM(S'.$baseRow.':S'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('T'.($row+1),'=SUM(T'.$baseRow.':T'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('U'.($row+1),'=SUM(U'.$baseRow.':U'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('V'.($row+1),'=SUM(V'.$baseRow.':V'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('W'.($row+1),'=SUM(W'.$baseRow.':W'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('X'.($row+1),'=SUM(X'.$baseRow.':X'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('Y'.($row+1),'=SUM(Y'.$baseRow.':Y'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('Z'.($row+1),'=SUM(Z'.$baseRow.':Z'.($row).')');
		
		$spreadsheet->getActiveSheet()->setCellValue('AA'.($row+1),'=SUM(AA'.$baseRow.':AA'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AB'.($row+1),'=SUM(AB'.$baseRow.':AB'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AC'.($row+1),'=SUM(AC'.$baseRow.':AC'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AD'.($row+1),'=SUM(AD'.$baseRow.':AD'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AE'.($row+1),'=SUM(AE'.$baseRow.':AE'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AF'.($row+1),'=SUM(AF'.$baseRow.':AF'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AG'.($row+1),'=SUM(AG'.$baseRow.':AG'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AH'.($row+1),'=SUM(AH'.$baseRow.':AH'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AI'.($row+1),'=SUM(AI'.$baseRow.':AI'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AJ'.($row+1),'=SUM(AJ'.$baseRow.':AJ'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AK'.($row+1),'=SUM(AK'.$baseRow.':AK'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AL'.($row+1),'=SUM(AL'.$baseRow.':AL'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AM'.($row+1),'=SUM(AM'.$baseRow.':AM'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AN'.($row+1),'=SUM(AN'.$baseRow.':AN'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AO'.($row+1),'=SUM(AO'.$baseRow.':AO'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AP'.($row+1),'=SUM(AP'.$baseRow.':AP'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AQ'.($row+1),'=SUM(AQ'.$baseRow.':AQ'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AR'.($row+1),'=SUM(AR'.$baseRow.':AR'.($row).')');
		$spreadsheet->getActiveSheet()->setCellValue('AS'.($row+1),'=SUM(AS'.$baseRow.':AS'.($row).')');
		
		$filename = 'REKON_ATM_'.date("M Y");
		
		// Redirect output to a client’s web browser (Xls)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, 'Xls');
		$writer->save('php://output');
	}
}