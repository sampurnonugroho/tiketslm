<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Mpdf\Mpdf;

class Pdf_fix extends CI_Controller {
    public function __construct() {
        parent::__construct();
		$this->load->library('curl');
	}
	
	public function run() {
		$id_ct = $this->uri->segment(3);
		$id_ga = $this->uri->segment(4);
		$id_tes = (!empty($this->uri->segment(5)) ? $this->uri->segment(5) : 0);
		
		// $data = array(
			// "dataku" => array(
				// array(
					// "nama" => "Petani Kode",
					// "url" => "http://petanikode.com"
				// ),
				// array(
					// "nama" => "Petani Kode",
					// "url" => "http://petanikode.com"
				// )
			// )
		// );
		
		// echo "<pre>";
		// print_r("id_ct : ".$id_ct);
		// echo "<br>";
		// print_r("id_ga : ".$id_ga);
		
		$sql = "
			SELECT 
				*, 
				IFNULL(client.sektor, client_cit.sektor) as run, 
				IFNULL(client.lokasi, client_cit.lokasi) as lokasi, 
				cashtransit_detail.id as id, 
				cashtransit_detail.ctr as ctr, 
				master_branch.name as branch_name, 
				master_zone.name as zone_name, 
				cashtransit_detail.data_solve as solve,
				IFNULL((
					SELECT nama
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_1
				), '') AS nama_custody_1,
				IFNULL((
					SELECT id_karyawan
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_1
				), '') AS id_karyawan_1,
				IFNULL((
					SELECT nama
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_2
				), '') AS nama_custody_2,
				IFNULL((
					SELECT id_karyawan
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_2
				), '') AS id_karyawan_2
					FROM cashtransit_detail 
						LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
						LEFT JOIN client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
						LEFT JOIN master_zone ON(client.sektor=master_zone.id) 
						LEFT JOIN master_branch ON (master_zone.id_branch=master_branch.id) 
						LEFT JOIN runsheet_operational ON(runsheet_operational.id_cashtransit=cashtransit_detail.id_cashtransit)
						LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
							WHERE 
								cashtransit_detail.id_cashtransit='$id_ct' AND 
								IFNULL(client.sektor, client_cit.sektor)='$id_ga' AND
								cashtransit_detail.state='ro_atm' AND
								runsheet_cashprocessing.id IS NOT NULL";
								
		// $sql = "
			// SELECT 
				// *, 
				// cashtransit.id as id_ct, 
				// cashtransit_detail.id as id_detail, 
				// cashtransit_detail.ctr as jum_ctr,
				// IFNULL((
					// SELECT nama
					// FROM karyawan
					// WHERE karyawan.nik = runsheet_operational.custodian_1
				// ), '') AS nama_custody_1,
				// IFNULL((
					// SELECT id_karyawan
					// FROM karyawan
					// WHERE karyawan.nik = runsheet_operational.custodian_1
				// ), '') AS id_karyawan_1,
				// IFNULL((
					// SELECT nama
					// FROM karyawan
					// WHERE karyawan.nik = runsheet_operational.custodian_2
				// ), '') AS nama_custody_2,
				// IFNULL((
					// SELECT id_karyawan
					// FROM karyawan
					// WHERE karyawan.nik = runsheet_operational.custodian_2
				// ), '') AS id_karyawan_2
			// FROM cashtransit 
			// LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) 
			// LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) 
			// LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
			// LEFT JOIN runsheet_operational ON(runsheet_operational.id_cashtransit=cashtransit_detail.id_cashtransit)
			// LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
			// WHERE 
				// cashtransit_detail.state='ro_atm' AND 
				// cashtransit_detail.data_solve!='' AND 
				// cashtransit_detail.id='$id' 
			// GROUP BY cashtransit_detail.id 
			// ORDER BY cashtransit.id DESC
		// ";
								
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
	
		$lists = array();
		$key = 0;
		foreach($result as $row) {
			$denom = 	($row->pcs_100000!=0 ? 100000 : (
								$row->pcs_50000!=0 ? 50000 : (
									$row->pcs_20000!=0 ? 20000 : (
										$row->pcs_10000!=0 ? 10000 : (
											$row->pcs_5000!=0 ? 5000 : (
												$row->pcs_2000!=0 ? 2000 : (
													$row->pcs_1000!=0 ? 1000 : 0
												)
											)
										)
									)
								)
							)
						);
						
			$denom_value = 	($row->pcs_100000!=0 ? $row->pcs_100000 : (
								$row->pcs_50000!=0 ? $row->pcs_50000 : (
									$row->pcs_20000!=0 ? $row->pcs_20000 : (
										$row->pcs_10000!=0 ? $row->pcs_10000 : (
											$row->pcs_5000!=0 ? $row->pcs_5000 : (
												$row->pcs_2000!=0 ? $row->pcs_2000 : (
													$row->pcs_1000!=0 ? $row->pcs_1000 : ''
												)
											)
										)
									)
								)
							)
						);
						
			if($row->type=="CRM") {
				list($seal_1, $denom_1, $value_1) = explode(";", $row->cart_1_seal);
				list($seal_2, $denom_2, $value_2) = explode(";", $row->cart_2_seal);
				list($seal_3, $denom_3, $value_3) = explode(";", $row->cart_3_seal);
				list($seal_4, $denom_4, $value_4) = explode(";", $row->cart_4_seal);
				
				$row->pcs_100000 =  ($denom_1=="100" ? $value_1 : 0) +
									($denom_2=="100" ? $value_2 : 0) +
									($denom_3=="100" ? $value_3 : 0) +
									($denom_4=="100" ? $value_4 : 0);
				
				$row->pcs_50000 =   ($denom_1=="50" ? $value_1 : 0) +
									($denom_2=="50" ? $value_2 : 0) +
									($denom_3=="50" ? $value_3 : 0) +
									($denom_4=="50" ? $value_4 : 0);
				
				$row->total 	=  ((intval($denom_1) * intval($value_1)) +
									(intval($denom_2) * intval($value_2)) +
									(intval($denom_3) * intval($value_3)) +
									(intval($denom_4) * intval($value_4))) * 1000;
			
				$denom_value = $value_1+$value_2+$value_3+$value_4;
			}
			
			$lists[$key]['id'] = $row->id;
			$lists[$key]['date'] = $row->date;
			$lists[$key]['updated_date_cpc'] = $row->updated_date_cpc;
			$lists[$key]['run'] = $row->kode_zone;
			$lists[$key]['type'] = $row->type;
			$lists[$key]['wsid'] = $row->wsid;
			$lists[$key]['bank'] = $row->bank;
			$lists[$key]['lokasi'] = $row->lokasi;
			$lists[$key]['type_mesin'] = $row->type_mesin;
			$lists[$key]['denom'] = ($denom!=0 ? number_format($denom, 0, ",", ",") : '');
			$lists[$key]['ctr'] = '('.$row->ctr.') '.number_format($denom_value, 0, ",", ",");
			$lists[$key]['value'] = ($denom!=0 ? number_format($denom_value/$row->ctr, 0, ",", ",") : '');
			$lists[$key]['ttl_all'] = number_format($row->total, 0, ",", ",");
			$lists[$key]['terbilang'] = (!empty($row->total) ? ucwords($this->terbilang($row->total)) : '');
			
			$lists[$key]['id_karyawan_1'] = $row->id_karyawan_1;
			$lists[$key]['id_karyawan_2'] = $row->id_karyawan_2;
			$lists[$key]['nama_custody_1'] = $row->nama_custody_1;
			$lists[$key]['nama_custody_2'] = $row->nama_custody_2;
			$lists[$key]['custodian_2'] = $row->custodian_2;
			
			#$lists[$key]['denom_value'] = $denom_value;
			#$lists[$key]['cart_1_seal'] = $row->cart_1_seal;
			#$lists[$key]['cart_2_seal'] = $row->cart_2_seal;
			#$lists[$key]['cart_3_seal'] = $row->cart_3_seal;
			#$lists[$key]['cart_4_seal'] = $row->cart_4_seal;
			#$lists[$key]['divert'] = $row->divert;
			#$lists[$key]['t_bag'] = $row->t_bag;
			$lists[$key]['bag_no'] = $row->bag_no;
			$lists[$key]['bag_seal'] = $row->bag_seal;
			
			
			$lists[$key]['data'][] = array(
				"csst" => ($row->type!="CRM" ? "Catridge 1" : "1"),
				"seal" => $row->cart_1_seal
			);
			$lists[$key]['data'][] = array(
				"csst" => ($row->type!="CRM" ? "Catridge 2" : "2"),
				"seal" => $row->cart_2_seal
			);
			$lists[$key]['data'][] = array(
				"csst" => ($row->type!="CRM" ? "Catridge 3" : "3"),
				"seal" => $row->cart_3_seal
			);
			$lists[$key]['data'][] = array(
				"csst" => ($row->type!="CRM" ? "Catridge 4" : "4"),
				"seal" => $row->cart_4_seal
			);
			if($row->type=="CRM") {
				$lists[$key]['data'][] = array(
					"csst" => ($row->type!="CRM" ? "Catridge 5" : "5"),
					"seal" => "$row->cart_5_seal;;"
				);
				$lists[$key]['data'][] = array(
					"csst" => ($row->type!="CRM" ? "Divert" : "6"),
					"seal" => "$row->divert;Divert;"
				);
			} else {
				$lists[$key]['data'][] = array(
					"csst" => ($row->type!="CRM" ? "Divert" : "6"),
					"seal" => "$row->divert"
				);
			}
			
			if(!empty($row->t_bag)) {
				$lists[$key]['data'][] = array(
					"csst" => ($row->type!="CRM" ? "T-Bag" : ""),
					"seal" => "$row->t_bag;T-Bag;"
				);
			}
			
			$key++;
		}
		
		// echo "<pre>";
		// print_r($lists);
		
		$data['data'] = $lists;
		
		$this->load->library('pdf');
	
		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "laporan-petanikode.pdf";
		$this->pdf->load_view('admin/pdf/run', $data);
		
		// $this->load->view('admin/pdf/run', $data);
	}
	
	public function boc() {
		$id_ct = $this->uri->segment(3);
		$id_ga = $this->uri->segment(4);

		// $sql = "select *, 
					// cashtransit_detail.id as id_ct, 
					// cashtransit_detail.ctr as ttl_ctr 
				// FROM cashtransit_detail 
					// LEFT JOIN client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
					// LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
					// LEFT JOIN runsheet_operational ON(runsheet_operational.id_cashtransit=cashtransit.id) 
					// LEFT JOIN runsheet_security ON(runsheet_security.id_cashtransit=cashtransit.id) 
					// LEFT JOIN runsheet_cashprocessing ON (
						// (runsheet_cashprocessing.id_cashtransit = cashtransit_detail.id_cashtransit)
					// ) 
				// WHERE 
					// cashtransit_detail.state='ro_cit' AND 
					// cashtransit_detail.id_cashtransit='".$id_ct."' AND
					// client_cit.sektor = '".$id_ga."'
					// ";

		// $sql = "SELECT 
				// *, 
				// cashtransit_detail.id as id_ct, 
				// IFNULL(client.sektor, client_cit.sektor) as run, 
				// IFNULL(client.lokasi, client_cit.lokasi) as lokasi, 
				// cashtransit_detail.id as id, 
				// cashtransit_detail.ctr as ctr, 
				// master_branch.name as branch_name, 
				// master_zone.name as zone_name, 
				// cashtransit_detail.data_solve as solve 
					// FROM cashtransit_detail 
						// LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
						// LEFT JOIN client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
						// LEFT JOIN master_zone ON(client.sektor=master_zone.id) 
						// LEFT JOIN master_branch ON (master_zone.id_branch=master_branch.id) 
						// LEFT JOIN runsheet_security ON(runsheet_security.id_cashtransit='$id_ct' AND runsheet_security.run_number='$id_ga') 
						// LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
							// WHERE 
								// cashtransit_detail.id_cashtransit='$id_ct' AND 
								// IFNULL(client.sektor, client_cit.sektor)='$id_ga' AND
								// cashtransit_detail.state='ro_cit'";
								
		$sql = "SELECT *, 
					cashtransit_detail.id as id_ct, 
					cashtransit_detail.ctr as ttl_ctr,
                    runsheet_cashprocessing.bag_seal,
                    runsheet_cashprocessing.bag_no
				FROM cashtransit_detail 
                	LEFT JOIN client ON (cashtransit_detail.id_bank=client.id) 
					LEFT JOIN client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
					LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
					LEFT JOIN runsheet_operational ON (
                        (runsheet_operational.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
                        (runsheet_operational.run_number = IFNULL(client.sektor, client_cit.sektor))
                    )
					LEFT JOIN runsheet_security ON (
                        (runsheet_security.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
                        (runsheet_security.run_number = IFNULL(client.sektor, client_cit.sektor))
                    )
					LEFT JOIN runsheet_cashprocessing ON (
                        (runsheet_cashprocessing.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
                        (runsheet_cashprocessing.id = cashtransit_detail.id)
                    ) 
				WHERE 
					cashtransit_detail.id_cashtransit='$id_ct' AND 
					IFNULL(client.sektor, client_cit.sektor)='$id_ga' AND
					cashtransit_detail.state='ro_cit'";
		
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// $data["total"] = count($res);
		// echo "<pre>";
		// print_r($sql);
		// print_r($res);

		$items = array();
		$i = 0;
		foreach($res as $row){
			$detailuang = json_decode($row->detail_uang, true);
			
			$no_boc = $row->no_boc;
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['date'] = date("d-m-Y", strtotime($row->date));
			$items[$i]['state'] = $row->state;
			$items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
			$items[$i]['metode'] = ($row->metode=="CP" ? "CASH PICKUP" : ($row->metode=="CD" ? "CASH DELIVERY" : ""));
			$items[$i]['jenis'] = $row->jenis;
			if($row->id_pengirim!=0){
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_pengirim."'")->row()->sektor;
			} else {
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_penerima."'")->row()->sektor;
			}
			$items[$i]['pengirim'] = ($row->id_pengirim==0 ? "PT. BINTANG JASA ARTHA KELOLA" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama_client FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama_client);
			$items[$i]['penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama_client FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama_client);
			$items[$i]['client_pengirim'] = ($row->id_pengirim==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT client FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->client);
			$items[$i]['client_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT client FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->client);
			$items[$i]['alamat_pengirim'] = ($row->id_pengirim==0 ? "JL. DHARMAWANGSA X NO. 21" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT alamat FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->alamat);
			$items[$i]['alamat_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT alamat FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->alamat);
			$items[$i]['kodepos_pengirim'] = ($row->id_pengirim==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT kode_pos FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->kode_pos);
			$items[$i]['kodepos_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT kode_pos FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->kode_pos);
			$items[$i]['wilayah_pengirim'] = ($row->id_pengirim==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT wilayah FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->wilayah);
			$items[$i]['wilayah_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT wilayah FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->wilayah);
			$items[$i]['pic_pengirim'] = ($row->id_pengirim==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT pic FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->pic);
			$items[$i]['pic_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT pic FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->pic);
			$items[$i]['telp_pengirim'] = ($row->id_pengirim==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT telp FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->telp);
			$items[$i]['telp_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT telp FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->telp);
			$items[$i]['custody_1'] = (empty($row->custodian_1) ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan where nik='".$row->custodian_1."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama);
			$items[$i]['custody_2'] = (empty($row->custodian_2) ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan where nik='".$row->custodian_2."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama);
			$items[$i]['security_1'] = (empty($row->security_1) ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan where nik='".$row->security_1."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama);
			$items[$i]['security_2'] = (empty($row->security_2) ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan where nik='".$row->security_2."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama);
			$items[$i]['police_number'] = $row->police_number;
			$items[$i]['no_boc'] = $row->no_boc;
			if(empty($row->data_solve)) {
				$items[$i]['bag_seal'] = $row->bag_seal;
				$items[$i]['bag_no'] = $row->bag_no;
				
				$items[$i]['kertas_50k'] = $detailuang['kertas_50k'];
				$items[$i]['kertas_20k'] = $detailuang['kertas_20k'];
				$items[$i]['kertas_10k'] = $detailuang['kertas_10k'];
				$items[$i]['kertas_5k'] = $detailuang['kertas_5k'];
				$items[$i]['kertas_2k'] = $detailuang['kertas_2k'];
				$items[$i]['kertas_1k'] = $detailuang['kertas_1k'];
				$items[$i]['logam_1k'] = $detailuang['logam_1k'];
				$items[$i]['logam_500'] = $detailuang['logam_500'];
				$items[$i]['logam_200'] = $detailuang['logam_200'];
				$items[$i]['logam_100'] = $detailuang['logam_100'];
				$items[$i]['logam_50'] = $detailuang['logam_50'];
				$items[$i]['uang'] = $detailuang;
				
				$items[$i]['total'] = $row->total;
				$items[$i]['terbilang'] = strtoupper($this->terbilang($row->total));
			} else {
				$detailuang = json_decode($row->data_solve, true);
				$items[$i]['bag_seal'] = $detailuang['bag_seal'];
				$items[$i]['bag_no'] = $detailuang['bag_no'];
				
				unset($detailuang['bag_seal']);
				unset($detailuang['bag_no']);
				$items[$i]['kertas_100k'] = $detailuang['kertas_100k'];
				$items[$i]['kertas_50k'] = $detailuang['kertas_50k'];
				$items[$i]['kertas_20k'] = $detailuang['kertas_20k'];
				$items[$i]['kertas_10k'] = $detailuang['kertas_10k'];
				$items[$i]['kertas_5k'] = $detailuang['kertas_5k'];
				$items[$i]['kertas_2k'] = $detailuang['kertas_2k'];
				$items[$i]['kertas_1k'] = $detailuang['kertas_1k'];
				$items[$i]['logam_1k'] = $detailuang['logam_1k'];
				$items[$i]['logam_500'] = $detailuang['logam_500'];
				$items[$i]['logam_200'] = $detailuang['logam_200'];
				$items[$i]['logam_100'] = $detailuang['logam_100'];
				$items[$i]['logam_50'] = $detailuang['logam_50'];
				$items[$i]['uang'] = $detailuang;
				
				$total = 0;
				foreach($detailuang as $k => $r) {
					if(!empty($r)) {
						if($k=="kertas_100k") { $pengali = 100000; }
						if($k=="kertas_50k") { $pengali = 50000; }
						if($k=="kertas_20k") { $pengali = 20000; }
						if($k=="kertas_10k") { $pengali = 10000; }
						if($k=="kertas_5k") { $pengali = 5000; }
						if($k=="kertas_2k") { $pengali = 2000; }
						if($k=="kertas_1k") { $pengali = 1000; }
						if($k=="logam_1k") { $pengali = 1000; }
						if($k=="logam_500") { $pengali = 500; }
						if($k=="logam_200") { $pengali = 200; }
						if($k=="logam_100") { $pengali = 100; }
						if($k=="logam_50") { $pengali = 50; }

						$total = $total + ($r * $pengali);
					}
				}
				$items[$i]['total'] = $total;
				$items[$i]['terbilang'] = strtoupper($this->terbilang($total));
			}
			$i++;
		}
		$data["rows"] = $items;
		
		// echo "<pre>";
		// print_r($data);
		
		if(empty($this->uri->segment(5))) {
			$this->load->library('pdf');
		
			$this->pdf->setPaper('A4', 'potrait');
			$this->pdf->filename = "laporan-petanikode.pdf";
			$this->pdf->load_view('admin/pdf/boc', $data);
		} else {
			echo "<pre>";
			// print_r($res);
			print_r($items);
			
			$this->load->view('admin/pdf/boc', $data);
		}
	}
	
	public function bast() {
		// berita acara serah terima
		
		$data = "";
		
		
		if(empty($this->uri->segment(5))) {
			$this->load->library('pdf');
		
			$this->pdf->setPaper('A4', 'potrait');
			$this->pdf->filename = "laporan-petanikode.pdf";
			$this->pdf->load_view('admin/pdf/bast', $data);
		} else {
			echo "<pre>";
			// print_r($res);
			print_r($items);
			
			$this->load->view('admin/pdf/bast', $data);
		}
	}
	
	public function bast_done() { 
	    $wsid = $this->uri->segment(3);
	    
	    $query = "SELECT * FROM client_ho WHERE wsid='$wsid'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
	    
	    $data['data_bast'] = $result;
		
		
		if(empty($this->uri->segment(4))) {
			$this->load->library('pdf');
		
			$this->pdf->setPaper('A4', 'potrait');
			$this->pdf->filename = "bast_done.pdf";
			$this->pdf->load_view('admin/pdf/bast_done', $data);
		} else {
			echo "<pre>";
			print_r($result);
			
			$this->load->view('admin/pdf/bast_done', $data);
		}
	}
	
	public function boc2() {
		$id = $this->uri->segment(3);

		$sql = "SELECT *, 
					cashtransit_detail.id as id_ct, 
					cashtransit_detail.date as date, 
					cashtransit_detail.ctr as ttl_ctr,
                    runsheet_cashprocessing.bag_seal,
                    runsheet_cashprocessing.bag_no
				FROM cashtransit_detail 
                	LEFT JOIN client ON (cashtransit_detail.id_bank=client.id) 
					LEFT JOIN client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
					LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
					LEFT JOIN runsheet_operational ON (
                        (runsheet_operational.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
                        (runsheet_operational.run_number = IFNULL(client.sektor, client_cit.sektor))
                    )
					LEFT JOIN runsheet_security ON (
                        (runsheet_security.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
                        (runsheet_security.run_number = IFNULL(client.sektor, client_cit.sektor))
                    )
					LEFT JOIN runsheet_cashprocessing ON (
                        (runsheet_cashprocessing.id_cashtransit = cashtransit_detail.id_cashtransit) AND 
                        (runsheet_cashprocessing.id = cashtransit_detail.id)
                    ) 
				WHERE 
					cashtransit_detail.state='ro_cit' AND 
					cashtransit_detail.id='$id'";
				
		
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// $data["total"] = count($res);
		// echo "<pre>";
		// print_r($sql);
		// print_r($res);

		$items = array();
		$i = 0;
		$no_boc = "";
		$datessss = "";
		foreach($res as $row){
			
			$detailuang = json_decode($row->detail_uang, true);
			
			$no_boc = $row->no_boc;
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['date'] = date("d-m-Y", strtotime($row->date));
			$items[$i]['state'] = $row->state;
			$items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
			$items[$i]['metode'] = ($row->metode=="CP" ? "CASH PICKUP" : ($row->metode=="CD" ? "CASH DELIVERY" : ""));
			$items[$i]['jenis'] = $row->jenis;
			if($row->id_pengirim!=0){
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_pengirim."'")->row()->sektor;
			} else {
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_penerima."'")->row()->sektor;
			}
			$items[$i]['pengirim'] = ($row->id_pengirim==0 ? "PT. BINTANG JASA ARTHA KELOLA" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama_client FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama_client);
			$items[$i]['penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama_client FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama_client);
			$items[$i]['client_pengirim'] = ($row->id_pengirim==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT client FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->client);
			$items[$i]['client_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT client FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->client);
			$items[$i]['alamat_pengirim'] = ($row->id_pengirim==0 ? "JL. DHARMAWANGSA X NO. 21" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT alamat FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->alamat);
			$items[$i]['alamat_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT alamat FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->alamat);
			$items[$i]['kodepos_pengirim'] = ($row->id_pengirim==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT kode_pos FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->kode_pos);
			$items[$i]['kodepos_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT kode_pos FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->kode_pos);
			$items[$i]['wilayah_pengirim'] = ($row->id_pengirim==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT wilayah FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->wilayah);
			$items[$i]['wilayah_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT wilayah FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->wilayah);
			$items[$i]['pic_pengirim'] = ($row->id_pengirim==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT pic FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->pic);
			$items[$i]['pic_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT pic FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->pic);
			$items[$i]['telp_pengirim'] = ($row->id_pengirim==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT telp FROM client_cit where id='".$row->id_pengirim."'"), array(CURLOPT_BUFFERSIZE => 10)))->telp);
			$items[$i]['telp_penerima'] = ($row->id_penerima==0 ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT telp FROM client_cit where id='".$row->id_penerima."'"), array(CURLOPT_BUFFERSIZE => 10)))->telp);
			$items[$i]['custody_1'] = (empty($row->custodian_1) ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan where nik='".$row->custodian_1."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama);
			$items[$i]['custody_2'] = (empty($row->custodian_2) ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan where nik='".$row->custodian_2."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama);
			$items[$i]['security_1'] = (empty($row->security_1) ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan where nik='".$row->security_1."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama);
			$items[$i]['security_2'] = (empty($row->security_2) ? "-" : json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT nama FROM karyawan where nik='".$row->security_2."'"), array(CURLOPT_BUFFERSIZE => 10)))->nama);
			$items[$i]['police_number'] = $row->police_number;
			$items[$i]['no_boc'] = $row->no_boc;
			if(empty($row->data_solve)) {
				$items[$i]['bag_seal'] = $row->bag_seal;
				$items[$i]['bag_no'] = $row->bag_no;
				
				$items[$i]['kertas_50k'] = $detailuang['kertas_50k'];
				$items[$i]['kertas_20k'] = $detailuang['kertas_20k'];
				$items[$i]['kertas_10k'] = $detailuang['kertas_10k'];
				$items[$i]['kertas_5k'] = $detailuang['kertas_5k'];
				$items[$i]['kertas_2k'] = $detailuang['kertas_2k'];
				$items[$i]['kertas_1k'] = $detailuang['kertas_1k'];
				$items[$i]['logam_1k'] = $detailuang['logam_1k'];
				$items[$i]['logam_500'] = $detailuang['logam_500'];
				$items[$i]['logam_200'] = $detailuang['logam_200'];
				$items[$i]['logam_100'] = $detailuang['logam_100'];
				$items[$i]['logam_50'] = $detailuang['logam_50'];
				$items[$i]['uang'] = $detailuang;
				
				$items[$i]['total'] = $row->total;
				$items[$i]['terbilang'] = strtoupper($this->terbilang($row->total));
			} else {
				$detailuang = json_decode($row->data_solve, true);
				$items[$i]['bag_seal'] = $detailuang['bag_seal'];
				$items[$i]['bag_no'] = $detailuang['bag_no'];
				
				unset($detailuang['bag_seal']);
				unset($detailuang['bag_no']);
				$items[$i]['kertas_100k'] = $detailuang['kertas_100k'];
				$items[$i]['kertas_50k'] = $detailuang['kertas_50k'];
				$items[$i]['kertas_20k'] = $detailuang['kertas_20k'];
				$items[$i]['kertas_10k'] = $detailuang['kertas_10k'];
				$items[$i]['kertas_5k'] = $detailuang['kertas_5k'];
				$items[$i]['kertas_2k'] = $detailuang['kertas_2k'];
				$items[$i]['kertas_1k'] = $detailuang['kertas_1k'];
				$items[$i]['logam_1k'] = $detailuang['logam_1k'];
				$items[$i]['logam_500'] = $detailuang['logam_500'];
				$items[$i]['logam_200'] = $detailuang['logam_200'];
				$items[$i]['logam_100'] = $detailuang['logam_100'];
				$items[$i]['logam_50'] = $detailuang['logam_50'];
				$items[$i]['uang'] = $detailuang;
				
				$total = 0;
				foreach($detailuang as $k => $r) {
					if(!empty($r)) {
						if($k=="kertas_100k") { $pengali = 100000; }
						if($k=="kertas_50k") { $pengali = 50000; }
						if($k=="kertas_20k") { $pengali = 20000; }
						if($k=="kertas_10k") { $pengali = 10000; }
						if($k=="kertas_5k") { $pengali = 5000; }
						if($k=="kertas_2k") { $pengali = 2000; }
						if($k=="kertas_1k") { $pengali = 1000; }
						if($k=="logam_1k") { $pengali = 1000; }
						if($k=="logam_500") { $pengali = 500; }
						if($k=="logam_200") { $pengali = 200; }
						if($k=="logam_100") { $pengali = 100; }
						if($k=="logam_50") { $pengali = 50; }

						$total = $total + ($r * $pengali);
					}
				}
				$items[$i]['total'] = $total;
				$items[$i]['terbilang'] = strtoupper($this->terbilang($total));
			}
			$i++;
		}
		$data["rows"] = $items;
		
		// print_r($data);
		
		if(empty($this->uri->segment(4))) {
			$this->load->library('pdf');
		
			$this->pdf->setPaper('A4', 'potrait');
			$this->pdf->filename = $no_boc.'('.date('Ymd', strtotime($row->date)).').pdf';
			$this->pdf->load_view('admin/pdf/boc', $data);
		} else {
			echo "<pre>";
			// print_r($res);
			print_r($items);
			
			$this->load->view('admin/pdf/boc', $data);
		}
	}
	
	public function runsheet() {
		error_reporting(0);
		$id_ct = $this->uri->segment(3);
		$id_ga = $this->uri->segment(4);
		
		// $sql = "select *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail, cashtransit_detail.ctr as jum_ctr FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) WHERE cashtransit_detail.state='ro_atm' AND cashtransit_detail.data_solve='' AND cashtransit.id='$id_ct' AND client.sektor IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit='$id_ct' AND run_number='$id_ga') GROUP BY cashtransit_detail.id ORDER BY cashtransit.id DESC ";
		
		$sql = "SELECT *, client.sektor as run1, client_cit.sektor as run2, cashtransit.id as id_ct, cashtransit_detail.id as id_detail, cashtransit_detail.ctr as jum_ctr FROM cashtransit
			LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id)
			LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) 
			LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
			LEFT JOIN client_cit ON(cashtransit_detail.id_pengirim=client_cit.id)
			LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id)
				WHERE cashtransit.id='$id_ct' GROUP BY cashtransit_detail.id ORDER BY cashtransit.id DESC";
				
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($sql);
		
		$content_html = '';
		$inner_content_html = '';
		
		foreach($result as $row) {
			$type = $row->type;
			$ctr = $row->jum_ctr;
			$denom = "-";
			$value = "-";
			$ttl_ctr = 0;
			$ttl_all = 0;
			$terbilang = '';
			
			if($row->state=="ro_atm") {
				
				if($type=="ATM") {
					$ttl_ctr = '('.$ctr.') '.(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000).'';
					$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
					$value = (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000);
					// $ttl_all = 'Rp. '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom), 0, ",", ".").'';
					// $terbilang = ucwords($this->terbilang(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom)));
					$ttl_all = 'Rp. '.number_format($row->total, 0, ",", ".").'';
					$terbilang = ucwords($this->terbilang($row->total));
					
					$inner_content_html = '
						<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
							<thead>
								<tr>
									<td rowspan="2" align="center">PREPARATION</td>
									<td rowspan="2" align="center">SEAL PREPARE</td>
									<td colspan="2" align="center">STATUS</td>
									<td rowspan="2" align="center">SEAL RETURN</td>
									<td rowspan="2" align="center">VALUE</td>
									<td rowspan="2" align="center">TOTAL RETURN</td>
								</tr>
								<tr>
									<td width="90" align="center">PENGALIHAN</td>
									<td width="90" align="center">CANCEL</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Catridge 1</td>
									<td align="center">'.$row->cart_1_seal.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td>Catridge 2</td>
									<td align="center">'.$row->cart_2_seal.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td>Catridge 3</td>
									<td align="center">'.$row->cart_3_seal.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td>Catridge 4</td>
									<td align="center">'.$row->cart_4_seal.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td>Divert</td>
									<td align="center">'.$row->divert.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td>T-BAG</td>
									<td align="center">'.$row->t_bag.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td colspan=5 id="noborder"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
							</tbody>
						</table>
					';
				} else if($type=="CRM") {
					list($seal_1, $denom_1, $value_1) = explode(";", $row->cart_1_seal);
					list($seal_2, $denom_2, $value_2) = explode(";", $row->cart_2_seal);
					list($seal_3, $denom_3, $value_3) = explode(";", $row->cart_3_seal);
					list($seal_4, $denom_4, $value_4) = explode(";", $row->cart_4_seal);
					$seal_5 = $row->cart_5_seal;
					
					$ttl_1 = 'Rp. '.number_format(($denom_1*$value_1)*1000, 0, ",", ".");
					$ttl_2 = 'Rp. '.number_format(($denom_2*$value_2)*1000, 0, ",", ".");
					$ttl_3 = 'Rp. '.number_format(($denom_3*$value_3)*1000, 0, ",", ".");
					$ttl_4 = 'Rp. '.number_format(($denom_4*$value_4)*1000, 0, ",", ".");
					
					$ttl_all1 = ($denom_1*$value_1) +
							   ($denom_2*$value_2) +
							   ($denom_3*$value_3) +
							   ($denom_4*$value_4);
					
					$ttl_all = 'Rp. '.number_format(($ttl_all1)*1000, 0, ",", ".");
					
					$ttl_ctr = ''.$ctr.'';
					
					$terbilang = ucwords($this->terbilang(($ttl_all1)*1000));
					

					$inner_content_html = '
						<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
							<thead>
								<tr>
									<td rowspan="2" align="center" width="20px">CSST</td>
									<td rowspan="2" align="center">DENOM</td>
									<td rowspan="2" align="center">TOTAL</td>
									<td rowspan="2" align="center">SEAL PREPARE</td>
									<td colspan="2" align="center">STATUS</td>
									<td rowspan="2" align="center">SEAL RETURN</td>
									<td rowspan="2" align="center">VALUE</td>
									<td rowspan="2" align="center">TOTAL RETURN</td>
								</tr>
								<tr>
									<td width="20" align="center">PENGALIHAN</td>
									<td width="20" align="center">CANCEL</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td align="center">1</td>
									<td align="center">
										<span class="alignleft">'.number_format(($denom_1*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_1.'</span>
									</td>
									<td align="center">'.$ttl_1.'</td>
									<td align="center">'.$seal_1.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center">2</td>
									<td align="center">
										<span class="alignleft">'.number_format(($denom_2*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_2.'</span>
									</td>
									<td align="center">'.$ttl_2.'</td>
									<td align="center">'.$seal_2.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center">3</td>
									<td align="center">
										<span class="alignleft">'.number_format(($denom_3*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_3.'</span>
									</td>
									<td align="center">'.$ttl_3.'</td>
									<td align="center">'.$seal_3.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center">4</td>
									<td align="center">
										<span class="alignleft">'.number_format(($denom_4*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_4.'</span>
									</td>
									<td align="center">'.$ttl_4.'</td>
									<td align="center">'.$seal_4.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center">5</td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center">'.$seal_5.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center">6</td>
									<td>DIVERT</td>
									<td align="center"></td>
									<td align="center">'.$row->divert.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center"></td>
									<td>T-BAG</td>
									<td align="center"></td>
									<td align="center">'.$row->t_bag.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td colspan="7" id="noborder"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
							</tbody>
						</table>
					';
				} else if($type=="CDM") {
					$seal_1 = $row->cart_1_seal;
					$seal_2 = $row->cart_2_seal;
					$seal_3 = $row->cart_3_seal;
					$seal_4 = $row->cart_4_seal;
					$seal_5 = $row->cart_5_seal;
					
					$ttl_1 = 'Rp. '.number_format(($denom_1*$value_1)*1000, 0, ",", ".");
					$ttl_2 = 'Rp. '.number_format(($denom_2*$value_2)*1000, 0, ",", ".");
					$ttl_3 = 'Rp. '.number_format(($denom_3*$value_3)*1000, 0, ",", ".");
					$ttl_4 = 'Rp. '.number_format(($denom_4*$value_4)*1000, 0, ",", ".");
					
					$ttl_all1 = ($denom_1*$value_1) +
							   ($denom_2*$value_2) +
							   ($denom_3*$value_3) +
							   ($denom_4*$value_4);
					
					$ttl_all = '-';
					
					$ttl_ctr = '-';
					
					$terbilang = '-';
					

					$inner_content_html = '
						<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
							<thead>
								<tr>
									<td rowspan="2" align="center" width="20px">CSST</td>
									<td rowspan="2" align="center">DENOM</td>
									<td rowspan="2" align="center">TOTAL</td>
									<td rowspan="2" align="center">SEAL PREPARE</td>
									<td colspan="2" align="center">STATUS</td>
									<td rowspan="2" align="center">SEAL RETURN</td>
									<td rowspan="2" align="center">VALUE</td>
									<td rowspan="2" align="center">TOTAL RETURN</td>
								</tr>
								<tr>
									<td width="20" align="center">PENGALIHAN</td>
									<td width="20" align="center">CANCEL</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td align="center">1</td>
									<td align="center">
										<span class="alignleft"></span>
										<span class="alignright"></span>
									</td>
									<td align="center"></td>
									<td align="center">'.$seal_1.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center">2</td>
									<td align="center">
										<span class="alignleft"></span>
										<span class="alignright"></span>
									</td>
									<td align="center"></td>
									<td align="center">'.$seal_2.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center">3</td>
									<td align="center">
										<span class="alignleft"></span>
										<span class="alignright"></span>
									</td>
									<td align="center"></td>
									<td align="center">'.$seal_3.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center">4</td>
									<td align="center">
										<span class="alignleft"></span>
										<span class="alignright"></span>
									</td>
									<td align="center"></td>
									<td align="center">'.$seal_4.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center">5</td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center">'.$seal_5.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center">6</td>
									<td>DIVERT</td>
									<td align="center"></td>
									<td align="center">'.$row->divert.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td align="center"></td>
									<td>T-BAG</td>
									<td align="center"></td>
									<td align="center">'.$row->t_bag.'</td>
									<td></td>
									<td></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
								<tr>
									<td colspan="7" id="noborder"></td>
									<td align="center"></td>
									<td align="left">Rp.</td>
								</tr>
							</tbody>
						</table>
					';
				}
				
				$content_html .= '
					<table class="first">
						<tr>
							<td width="50%">
								<p id="h3">PT. BINTANG JASA ARTHA KELOLA</p>
								<p>REPORT REPLENISH - RETURN ATM</p>
								<hr style="border-style: solid;height:1px;border:none;color:#333;background-color:#333;margin-top: -10px" />
								
								<table class="second">
									<tr>
										<td style="width: 60px">LOCATION</td>
										<td style="width: 10px">:</td>
										<td>'.$row->lokasi.'</td>
									</tr>
									<tr>
										<td style="width: 60px">ID</td>
										<td style="width: 10px">:</td>
										<td>'.$row->wsid.'</td>
									</tr>
								</table>
								<table class="second">
									<tr>
										<td style="width: 60px">BANK</td>
										<td style="width: 10px">:</td>
										<td>'.$row->bank.'</td>
										
										<td style="width: 60px">DENOM</td>
										<td style="width: 10px">:</td>
										<td>'.$denom.'</td>
									</tr>
									<tr>
										<td style="width: 60px">TYPE</td>
										<td style="width: 10px">:</td>
										<td>'.$row->type_mesin.'</td>
										
										<td style="width: 60px">VALUE</td>
										<td style="width: 10px">:</td>
										<td>'.($value/$ctr).'</td>
									</tr>
								</table>
							</td>
							<td width="15%">
								<center>
									
								</center>
							</td>
							<td width="35%">
								<table class="second">
									<tr>
										<td style="width: 150px">TANGGAL</td>
										<td style="width: 10px">:</td>
										<td>'.date("d-M-Y", strtotime(explode(" ", $row->date)[0])).'</td>
									</tr>
									<tr>
										<td>TIME REPLENISH(CSO)</td>
										<td>:</td>
										<td>...........................</td>
									</tr>
									<tr>
										<td>TIME PREPARE BAG(CPC)</td>
										<td>:</td>
										<td>'.date("H:i", strtotime(explode(" ", $row->updated_date_cpc)[1])).'</td>
									</tr>
								</table>
								<hr style="height:1px;border:none;color:#333;background-color:#333;width:100%; text-align: left; margin: 2px" />
								<table class="second">
									<tr>
										<td style="width: 150px">CASHIER</td>
										<td style="width: 10px">:</td>
										<td>...........................</td>
									</tr>
									<tr>
										<td>NO. MEJA</td>
										<td>:</td>
										<td>...........................</td>
									</tr>
									<tr>
										<td>JAM PROSES</td>
										<td>:</td>
										<td>...........................</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table class="third">
						<tr>
							<td style="width: 45px; text-align: center; border: 1px solid black; border-style: solid;">RUN</td>
						</tr>
						<tr>
							<td style="width: 45px; text-align: center; font-size: 24px;">'.$row->run1.'</td>
						</tr>
					</table>
					
					'.$inner_content_html.'
					
					<table style="width: 80%; font-size: 10px; margin-top: -14px">
						<tr>
							<td style="width: 120px">TOTAL CATRIDGE</td>
							<td style="width: 10px">:</td>
							<td>'.$ttl_ctr.'</td>
							
							<td style="width: 120px">NO. BAG</td>
							<td style="width: 10px">:</td>
							<td>'.$row->bag_no.'</td>
						</tr>
						<tr>
							<td style="width: 60px">TOTAL</td>
							<td style="width: 10px">:</td>
							<td>'.$ttl_all.'</td>
							
							<td style="width: 60px">SEAL BAG(CPC)</td>
							<td style="width: 10px">:</td>
							<td>'.$row->bag_seal.'</td>
						</tr>
						<tr>
							<td style="width: 60px">TERBILANG</td>
							<td style="width: 10px">:</td>
							<td style="font-weight: bold"># '.$terbilang.' #</td>
							
							<td style="width: 60px">SEAL BAG(CSO)</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
					</table>
					
					<table style="width: 100%; font-size: 10px" class="fifth">
						<tr>
							<td align="center" class="noborderbottom noborderright">Prepared By</td>
							<td align="center" class="noborderbottom noborderleft" colspan=2>Received By</td>
							<td align="center" class="noborderbottom" colspan=2>Ops,</td>
							<td align="center" class="noborderbottom">Approval By Return</td>
						</tr>
						<tr>
							<td class="nobordertop noborderbottom" align="center" style="height: 60px" colspan=3></td>
							<td class="nobordertop noborderbottom" colspan=2></td>
							<td class="nobordertop noborderbottom"></td>
						</tr>
						<tr>
							<td style="width: 16.6%" class="nobordertop noborderright" align="center">DUTY CPC</td>
							<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">CSO</td>
							<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">SCC</td>
							<td style="width: 16.6%" class="nobordertop noborderright" align="center">CSO/SCC</td>
							<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">CPC</td>
							<td style="width: 16.6%" class="nobordertop " align="center">DUTY CPC</td>
						</tr>
					</table>
				';
				
				$paper_size = "@page { margin: 0px; size: 22cm 14cm portrait; }";
			} else {
				$content_html .= '
					<table style="width: 100%; font-size: 9px;" border="1" class="sixth">
						<thead>
							<tr>
								<td colspan="6" align="left" width="50%">
									<h4 style="font-size: 12px; margin-top: 0px; margin-bottom: 0px">PT. BINTANG JASA ARTHA KELOLA</h4>
									<h6 style="font-size: 11px; font-weight: normal; margin-top: 0px; margin-bottom: 0px">
										Jl. Dharmawangsa No.123<br>
										Jakarta 12160 - INDONESIA<br>
										Web : www.bintangjasa.co.id
									</h6>
								</td>
								<td rowspan="3" colspan="6" align="center" width="50%">SEAL PREPARE</td>
							</tr>
							<tr>
								<td align="center" colspan="6">
									BILL OF CARRIAGE
								</td>
							</tr>
							<tr>
								<td align="left" colspan="3">
									TANGGAL
								</td>
								<td align="center" colspan="1">
									NO.SERI
								</td>
								<td align="center" colspan="2">
									
								</td>
							</tr>
							<tr>
								<td align="left" style="vertical-align: top;" colspan="6">
									PENGIRIM <br><br><br>
								</td>
								<td align="left" style="vertical-align: top;" colspan="6">
									PENERIMA
								</td>
							</tr>
							<tr>
								<td align="center" colspan="6">
									ALAMAT
								</td>
								<td align="center" colspan="6">
									ALAMAT
								</td>
							</tr>
							<tr>
								<td align="left" style="vertical-align: top;" colspan="6">
									CLIENT
								</td>
								<td align="left" style="vertical-align: top;" colspan="6">
									CLIENT
								</td>
							</tr>
							<tr>
								<td align="left" style="vertical-align: top;" colspan="6">
									ALAMAT<br><br><br>
								</td>
								<td align="left" style="vertical-align: top;" colspan="6">
									ALAMAT
								</td>
							</tr>
							<tr>
								<td align="left" colspan="3">
									KODE POS 
								</td>
								<td align="left" colspan="3">
									WILAYAH
								</td>
								<td align="left" colspan="3">
									KODE POS 
								</td>
								<td align="left" colspan="3">
									WILAYAH
								</td>
							</tr>
							<tr>
								<td align="left" style="vertical-align: top;" colspan="6">
									NAMA YANG DI HUBUNGI 
									<br><br>
								</td>
								<td align="left" style="vertical-align: top;" colspan="4">
									NAMA YANG DI HUBUNGI
								</td>
								<td align="left" style="vertical-align: top;" colspan="2">
									KM TIBA
								</td>
							</tr>
							<tr>
								<td align="left" style="vertical-align: top;" colspan="6">
									TELP
								</td>
								<td align="left" style="vertical-align: top;" colspan="6">
									TELP
								</td>
							</tr>
							<tr>
								<td align="left" colspan="3">
									CUSTODIAN 1
								</td>
								<td align="left" colspan="3">
									CUSTODIAN 2
								</td>
								<td align="left" colspan="3">
									GUARD
								</td>
								<td align="left" colspan="3">
									NO MOBIL
								</td>
							</tr>
							<tr>
								<td align="left" style="vertical-align: top;" colspan="12">
									TERBILANG <br><br><br>
								</td>
							</tr>
							<tr>
								<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
									PECAHAN
								</td>
								<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
									MATA UANG
								</td>
								<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
									JUMLAH
								</td>
								<td style="font-size: 7px; font-weight: bold" align="center" colspan="3">
									NILAI
								</td>
								<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
									PECAHAN
								</td>
								<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
									MATA UANG
								</td>
								<td style="font-size: 7px; font-weight: bold" align="center" colspan="1">
									JUMLAH
								</td>
								<td style="font-size: 7px; font-weight: bold" align="center" colspan="3">
									NILAI
								</td>
							</tr>
							<tr>
								<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
							</tr>
							<tr>
								<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
							</tr>
							<tr>
								<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
							</tr>
							<tr>
								<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
							</tr>
							<tr>
								<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
							</tr>
							<tr>
								<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
							</tr>
							<tr>
								<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
							</tr>
							<tr>
								<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
							</tr>
							<tr>
								<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
							</tr>
							<tr>
								<td><span style="visibility: hidden">a</span></td><td></td><td></td><td colspan="3"></td><td></td><td></td><td></td><td colspan="3"></td>
							</tr>
							<tr>
								<td align="left" style="vertical-align: top;" colspan="3">
									NO.KANTONG / TAS
								</td>
								<td align="left" style="vertical-align: top;" colspan="3">
									NO.SEGEL
								</td>
								<td align="left" style="vertical-align: top;" colspan="3">
									NO.KANTONG / TAS
								</td>
								<td align="left" style="vertical-align: top;" colspan="3">
									NO.SEGEL
								</td>
							</tr>
							<tr>
								<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
								<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
							</tr>
							<tr>
								<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
								<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
							</tr>
							<tr>
								<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
								<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
							</tr>
							<tr>
								<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
								<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
							</tr>
							<tr>
								<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
								<td colspan="3"><span style="visibility: hidden">a</span></td><td colspan="3"></td>
							</tr>
							
							<tr>
								<td align="center" colspan="6">
									SERAH / TERIMA
								</td>
								<td align="center" colspan="6">
									SERAH / TERIMA
								</td>
							</tr>
							
							<tr>
								<td colspan="3">NAMA</td><td colspan="3">NAMA</td>
								<td colspan="3">NAMA</td><td colspan="3">NAMA</td>
							</tr>
							<tr>
								<td colspan="3">KTP/KPP</td><td colspan="3">KTP/KPP</td>
								<td colspan="3">KTP/KPP</td><td colspan="3">KTP/KPP</td>
							</tr>
							<tr>
								<td colspan="2" style="vertical-align: top;" align="left">TANGGAL <BR><BR></td>
								<td colspan="1" style="vertical-align: top;" align="left">JAM</td>
								<td colspan="2" style="vertical-align: top;" align="left">TANGGAL</td>
								<td colspan="1" style="vertical-align: top;" align="left">JAM</td>
								<td colspan="2" style="vertical-align: top;" align="left">TANGGAL</td>
								<td colspan="1" style="vertical-align: top;" align="left">JAM</td>
								<td colspan="2" style="vertical-align: top;" align="left">TANGGAL</td>
								<td colspan="1" style="vertical-align: top;" align="left">JAM</td>
							</tr>
							<tr>
								<td colspan="3"><br><br><br><br></td>
								<td colspan="3"></td>
								<td colspan="3"></td>
								<td colspan="3"></td>
							</tr>
							<tr>
								<td colspan="12"><span style="visibility: hidden">a</span></td>
							</tr>
							<tr>
								<td colspan="1" align="center">CUSTODIAN<br><br></td>
								<td colspan="1" align="center">ID</td>
								<td colspan="4" rowspan="5" style="vertical-align: top;" align="center">TANDA TANGAN</td>
								<td colspan="1" align="center">VAULT</td>
								<td colspan="1" align="center">ID</td>
								<td colspan="4" rowspan="5" style="vertical-align: top;" align="center">TANDA TANGAN</td>
							</tr>
							<tr>
								<td colspan="1" style="vertical-align: top;" align="center">TERIMA<br><br></td>
								<td colspan="1" style="vertical-align: top;" align="center">DISERAHKAN</td>
								<td colspan="1" style="vertical-align: top;" align="center">TERIMA</td>
								<td colspan="1" style="vertical-align: top;" align="center">DISERAHKAN</td>
							</tr>
							<tr>
								<td colspan="1" style="vertical-align: top;" align="center">TANGGAL<br><br></td>
								<td colspan="1" style="vertical-align: top;" align="center">WAKTU</td>
								<td colspan="1" style="vertical-align: top;" align="center">TANGGAL</td>
								<td colspan="1" style="vertical-align: top;" align="center">WAKTU</td>
							</tr>
							<tr>
								<td colspan="1" style="vertical-align: top;" align="center">TANPA CATATAN<br><br></td>
								<td colspan="1" style="vertical-align: top;" align="center">ADA CATATAN</td>
								<td colspan="1" style="vertical-align: top;" align="center">TANPA CATATAN</td>
								<td colspan="1" style="vertical-align: top;" align="center">ADA CATATAN</td>
							</tr>
							<tr>
								<td colspan="2" align="center">NO BERITA ACARA (KALAU ADA)</td>
								<td colspan="2" align="center">NO BERITA ACARA (KALAU ADA)</td>
							</tr>
							<tr>
								<td colspan="2" align="center">ORIGINAL 1</td>
								<td colspan="4" align="center">ORIGINAL 2 - PENGIRIM</td>
								<td colspan="2" align="center">ORIGINAL 3 - PENERIMA</td>
								<td colspan="4" align="center">ORIGINAL 4 - ARSIP</td>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				';
				
				$paper_size = "@page { margin: 0px; size: 21cm 29.5cm portrait; }";
			}
		}
		
		$template_html = '
			<html>
				<head>
					<style>
						
						'.$paper_size.'
					
						@font-face {
						  font-family: "aaaaa";
						  src: URL("'.base_url().'depend/font/serif_dot_digital-7.ttf") format("truetype");
						}
					
						body {
							margin: 27px 30px 0px 30px; size: 14cm 22cm landscape;
							font-family: Calibri;            
						}
					
						table.first {
							font-family: Calibri;            
							font-size: 8pt;
							width: 100%;
						}
						
						#h3 {
							font-family: Calibri; 
							font-size: 12pt;
						}
						
						table.first td {
							line-height: 1px;
						}	
						
						table.second {
							width: 100%;
						}
						
						table.second td {
							line-height: 12px;
						}
						
						.third {
							font-family: Calibri;       
							font-size: 8pt;
							border: 1px solid black;
							border-collapse: collapse;
							position: absolute;
							top: 30;
							right: 260;
							border-style: solid;
						}
						
						.fourth {
							font-family: Calibri;       
							font-size: 8pt;
							border-collapse: collapse;
							border: 1px solid black;
							border-style: solid;
						}
						
						.fifth {
							font-family: Calibri;       
							font-size: 8pt;
							border-collapse: collapse;
							border: 1px solid black;
							border-style: solid;
						}
						
						.sixth {
							font-family: Calibri;       
							border: none;
							border-collapse: collapse;
						}
						
						.sixth td {
							padding: 4px;
							border: 1px solid black;
							border-style: solid;
						}
						
						table.fourth td {
						    padding: 4px;
							border: 1px solid black;
							border-style: solid;
						}
						
						#noborder {
							border: none;
						}
						#noborderbottom {
							border-bottom: 0px solid white;
						}
						#nobordertop {
							border-top: 0px solid white;
						}
						.noborder {
							border: 0px solid white;
						}
						.noborderbottom {
							border-bottom: 0px solid white;
						}
						.nobordertop {
							border-top: 0px solid white;
						}
						.noborderright {
							border-right: 0px solid white;
						}
						.noborderleft {
							border-left: 0px solid white;
						}
						
						.alignleft {
							float: left;
						}
						.alignright {
							float: right;
						}
					</style>
				</head>

				<body>
					'.$content_html.'
				</body>
			</html>
		';
		
		// echo $template_html;
		
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($template_html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}

	public function tes() {
		$data = array(
			"dataku" => array(
				array(
					"nama" => "Petani Kode",
					"url" => "http://petanikode.com"
				),
				array(
					"nama" => "Petani Kode",
					"url" => "http://petanikode.com"
				)
			)
		);

		$id_ct = $this->uri->segment(3);
		$id_ga = $this->uri->segment(4);

		$sql = "select *, 
					cashtransit_detail.id as id_ct, 
					cashtransit_detail.ctr as ttl_ctr 
				FROM cashtransit_detail 
					LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
					LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
				WHERE 
					cashtransit_detail.state='ro_cit' AND 
					id_cashtransit='".$id_ct."'";
				
		
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		$result["total"] = count($res);
		// echo "<pre>";
		// print_r($sql);
		// print_r($result);

		$items = array();
		$i = 0;
		foreach($res as $row){
			$detailuang = json_decode($row->detail_uang, true);
			
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['date'] = date("d-m-Y", strtotime($row->date));
			$items[$i]['state'] = $row->state;
			$items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
			$items[$i]['metode'] = ($row->metode=="CP" ? "CASH PICKUP" : ($row->metode=="CD" ? "CASH DELIVERY" : ""));
			$items[$i]['jenis'] = $row->jenis;
			if($row->id_pengirim!=0){
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_pengirim."'")->row()->sektor;
			} else {
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_penerima."'")->row()->sektor;
			}
			$items[$i]['pengirim'] = ($row->id_pengirim==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_pengirim."'")->row()->nama_client);
			$items[$i]['penerima'] = ($row->id_penerima==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_penerima."'")->row()->nama_client);
			$items[$i]['kertas_100k'] = $detailuang['kertas_100k'];
			$items[$i]['kertas_50k'] = $detailuang['kertas_50k'];
			$items[$i]['kertas_20k'] = $detailuang['kertas_20k'];
			$items[$i]['kertas_10k'] = $detailuang['kertas_10k'];
			$items[$i]['kertas_5k'] = $detailuang['kertas_5k'];
			$items[$i]['kertas_2k'] = $detailuang['kertas_2k'];
			$items[$i]['kertas_1k'] = $detailuang['kertas_1k'];
			$items[$i]['logam_1k'] = $detailuang['logam_1k'];
			$items[$i]['logam_500'] = $detailuang['logam_500'];
			$items[$i]['logam_200'] = $detailuang['logam_200'];
			$items[$i]['logam_100'] = $detailuang['logam_100'];
			$items[$i]['logam_50'] = $detailuang['logam_50'];
			$items[$i]['uang'] = $detailuang;
			$items[$i]['terbilang'] = strtoupper($this->terbilang($row->total));
			$items[$i]['total'] = $row->total;
			$i++;
		}
		$result["rows"] = $items;
		
		// echo json_encode($result);
	
		$this->load->library('pdf');
	
		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "laporan-petanikode.pdf";
		$this->pdf->load_view('laporan_pdf', $result);
	}
	
	public function report_daily() {
		$html = false;
		error_reporting(0);
		$id = $this->uri->segment(3);
		
		
		$sql = "
			SELECT 
				*, 
				cashtransit.id as id_ct, 
				cashtransit_detail.id as id_detail, 
				cashtransit_detail.ctr as jum_ctr,
				IFNULL((
					SELECT nama
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_1
				), '') AS nama_custody_1,
				IFNULL((
					SELECT id_karyawan
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_1
				), '') AS id_karyawan_1,
				IFNULL((
					SELECT nama
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_2
				), '') AS nama_custody_2,
				IFNULL((
					SELECT id_karyawan
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_2
				), '') AS id_karyawan_2
			FROM cashtransit 
			LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) 
			LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) 
			LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
			LEFT JOIN runsheet_operational ON(runsheet_operational.id_cashtransit=cashtransit_detail.id_cashtransit)
			LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
			WHERE 
				cashtransit_detail.state='ro_atm' AND 
				cashtransit_detail.data_solve!='' AND 
				cashtransit_detail.id='$id' 
			GROUP BY cashtransit_detail.id 
			ORDER BY cashtransit.id DESC
		";
		
		// echo $sql;
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($sql);
		
		$content_html = '';
		$inner_content_html = '';
		
		foreach($result as $row) {
			$type = $row->type;
			$ctr = $row->jum_ctr;
			$denom = "-";
			$value = "-";
			$ttl_ctr = 0;
			$ttl_all = 0;
			$terbilang = '';
			
			if($row->cpc_process!=="") {
				$data = json_decode($row->cpc_process);
			} else {
				$data = json_decode($row->data_solve);
			}
			
			
			// echo $type;
			
			if($type=="ATM") {
				// $ttl_ctr = '('.$ctr.') '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)), 0, ",", ".").'';
				// $denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
				// $value = (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000);
				// $ttl_all = 'Rp. '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom), 0, ",", ".").'';
				// $terbilang = ucwords($this->terbilang(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom)));
				
				$ttl_ctr = '('.$ctr.') '.(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000).'';
				$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
				$value = (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000);
				// $ttl_all = 'Rp. '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom), 0, ",", ".").'';
				// $terbilang = ucwords($this->terbilang(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom)));
				$ttl_all = 'Rp. '.number_format($row->total, 0, ",", ".").'';
				$terbilang = ucwords($this->terbilang($row->total));
				
				
				$tbag_html = '';
				if(!empty($data->t_bag)) {
					$tbag_html = '
						<tr>
							<td>T-BAG</td>
							<td align="center">'.$row->t_bag.'</td>
							<td></td>
							<td></td>
							<td align="center">'.$data->t_bag.'</td>
							<td align="center">'.($data->t_bag!=="" ? intval($data->t_bag_no) : "").'</td>
							<td align="left">'.($data->t_bag!=="" ? 'Rp. <span class="alignright">'.number_format((intval($denom)*$data->t_bag_no), 0, ",", ".").'</span>' : "").'</td>
						</tr>
					';
					$ttl_value = (intval($data->div_no)+
								  intval($data->cart_4_no)+
								  intval($data->cart_3_no)+
								  intval($data->cart_2_no)+
								  intval($data->cart_1_no)+
								  intval($data->t_bag_no));
								  
					$total = ($data->cart_1_no!=="" ? (intval($denom)*$data->cart_1_no) : "") +
							 ($data->cart_2_no!=="" ? (intval($denom)*$data->cart_2_no) : "") +
							 ($data->cart_3_no!=="" ? (intval($denom)*$data->cart_3_no) : "") +
							 ($data->cart_4_no!=="" ? (intval($denom)*$data->cart_4_no) : "") +
							 ($data->div_no!=="" ? (intval($denom)*$data->div_no) : "") +
							 ($data->t_bag_no!=="" ? (intval($denom)*$data->t_bag_no) : "");
				} else {
					$ttl_value = (intval($data->div_no)+
								  intval($data->cart_4_no)+
								  intval($data->cart_3_no)+
								  intval($data->cart_2_no)+
								  intval($data->cart_1_no));
								  
					$total = ($data->cart_1_no!=="" ? (intval($denom)*$data->cart_1_no) : "") +
							 ($data->cart_2_no!=="" ? (intval($denom)*$data->cart_2_no) : "") +
							 ($data->cart_3_no!=="" ? (intval($denom)*$data->cart_3_no) : "") +
							 ($data->cart_4_no!=="" ? (intval($denom)*$data->cart_4_no) : "") +
							 ($data->div_no!=="" ? (intval($denom)*$data->div_no) : "");
				}
				
				$inner_content_html = '
					<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
						<thead>
							<tr>
								<td rowspan="2" align="center">PREPARATION</td>
								<td rowspan="2" align="center">SEAL PREPARE</td>
								<td colspan="2" align="center">STATUS</td>
								<td rowspan="2" align="center">SEAL RETURN</td>
								<td rowspan="2" align="center">VALUE</td>
								<td rowspan="2" align="center">TOTAL RETURN</td>
							</tr>
							<tr>
								<td width="90" align="center">PENGALIHAN</td>
								<td width="90" align="center">CANCEL</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Catridge 1</td>
								<td align="center">'.$row->cart_1_seal.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->cart_1_seal.'</td>
								<td align="center">'.($data->cart_1_seal!=="" ? intval($data->cart_1_no) : "").'</td>
								<td align="left">'.($data->cart_1_seal!=="" ? 'Rp. <span class="alignright">'.number_format((intval($denom)*$data->cart_1_no), 0, ",", ".").'</span>' : "").'</td>
							</tr>
							<tr>
								<td>Catridge 2</td>
								<td align="center">'.$row->cart_2_seal.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->cart_2_seal.'</td>
								<td align="center">'.($data->cart_2_seal!=="" ? intval($data->cart_2_no) : "").'</td>
								<td align="left">'.($data->cart_2_seal!=="" ? 'Rp. <span class="alignright">'.number_format((intval($denom)*$data->cart_2_no), 0, ",", ".").'</span>' : "").'</td>
							</tr>
							<tr>
								<td>Catridge 3</td>
								<td align="center">'.$row->cart_3_seal.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->cart_3_seal.'</td>
								<td align="center">'.($data->cart_3_seal!=="" ? intval($data->cart_3_no) : "").'</td>
								<td align="left">'.($data->cart_3_seal!=="" ? 'Rp. <span class="alignright">'.number_format((intval($denom)*$data->cart_3_no), 0, ",", ".").'</span>' : "").'</td>
							</tr>
							<tr>
								<td>Catridge 4</td>
								<td align="center">'.$row->cart_4_seal.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->cart_4_seal.'</td>
								<td align="center">'.($data->cart_4_seal!=="" ? intval($data->cart_4_no) : "").'</td>
								<td align="left">'.($data->cart_4_seal!=="" ? 'Rp. <span class="alignright">'.number_format((intval($denom)*$data->cart_4_no), 0, ",", ".").'</span>' : "").'</td>
							</tr>
							<tr>
								<td>Divert</td>
								<td align="center">'.$row->divert.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->div_seal.'</td>
								<td align="center">'.($data->div_seal!=="" ? intval($data->div_no) : "").'</td>
								<td align="left">'.($data->div_seal!=="" ? 'Rp. <span class="alignright">'.number_format((intval($denom)*$data->div_no), 0, ",", ".").'</span>' : "").'</td>
							</tr>
							'.$tbag_html.'
							<tr>
								<td colspan=5 id="noborder"></td>
								<td align="center">'.$ttl_value.'</td>
								<td align="left">Rp. <span class="alignright">'.number_format($total, 0, ",", ".").'</span></td>
							</tr>
						</tbody>
					</table>
				';
			} else if($type=="CRM") {
				list($seal_1, $denom_1, $value_1) = explode(";", $row->cart_1_seal);
				list($seal_2, $denom_2, $value_2) = explode(";", $row->cart_2_seal);
				list($seal_3, $denom_3, $value_3) = explode(";", $row->cart_3_seal);
				list($seal_4, $denom_4, $value_4) = explode(";", $row->cart_4_seal);
				$seal_5 = $row->cart_5_seal;
				
				$ttl_1 = 'Rp. '.number_format(($denom_1*$value_1)*1000, 0, ",", ".");
				$ttl_2 = 'Rp. '.number_format(($denom_2*$value_2)*1000, 0, ",", ".");
				$ttl_3 = 'Rp. '.number_format(($denom_3*$value_3)*1000, 0, ",", ".");
				$ttl_4 = 'Rp. '.number_format(($denom_4*$value_4)*1000, 0, ",", ".");
				
				$ttl_all1 = ($denom_1*$value_1) +
						   ($denom_2*$value_2) +
						   ($denom_3*$value_3) +
						   ($denom_4*$value_4);
				
				$ttl_all = 'Rp. '.number_format(($ttl_all1)*1000, 0, ",", ".");
				
				$ttl_ctr = ''.$ctr.'';
				
				$terbilang = ucwords($this->terbilang(($ttl_all1)*1000));
				
				$postArr = json_decode($data->data_seal, true);
				$postArr = array_map('array_filter', $postArr);
				$postArr = array_filter($postArr);
				
				// echo "<pre>";
				// print_r($postArr);
				
				$total_value = 0;
				foreach ($postArr as $item) {
					$total_value += $item['50'];
					$total_value += $item['100'];
				}
				
				if($row->cpc_process!=="pengisian") {
					$ttl_seal1 = ($this->searchArrayValueByKey($postArr['seal1'], "50")!==false ? intval($this->searchArrayValueByKey($postArr['seal1'], "50")) * 50000 : 0) + ($this->searchArrayValueByKey($postArr['seal1'], "100")!==false ? intval($this->searchArrayValueByKey($postArr['seal1'], "100")) * 100000 : 0);
					$ttl_seal2 = ($this->searchArrayValueByKey($postArr['seal2'], "50")!==false ? intval($this->searchArrayValueByKey($postArr['seal2'], "50")) * 50000 : 0) + ($this->searchArrayValueByKey($postArr['seal2'], "100")!==false ? intval($this->searchArrayValueByKey($postArr['seal2'], "100")) * 100000 : 0);
					$ttl_seal3 = ($this->searchArrayValueByKey($postArr['seal3'], "50")!==false ? intval($this->searchArrayValueByKey($postArr['seal3'], "50")) * 50000 : 0) + ($this->searchArrayValueByKey($postArr['seal3'], "100")!==false ? intval($this->searchArrayValueByKey($postArr['seal3'], "100")) * 100000 : 0);
					$ttl_seal4 = ($this->searchArrayValueByKey($postArr['seal4'], "50")!==false ? intval($this->searchArrayValueByKey($postArr['seal4'], "50")) * 50000 : 0) + ($this->searchArrayValueByKey($postArr['seal4'], "100")!==false ? intval($this->searchArrayValueByKey($postArr['seal4'], "100")) * 100000 : 0);
					$ttl_seal5 = ($this->searchArrayValueByKey($postArr['seal5'], "50")!==false ? intval($this->searchArrayValueByKey($postArr['seal5'], "50")) * 50000 : 0) + ($this->searchArrayValueByKey($postArr['seal5'], "100")!==false ? intval($this->searchArrayValueByKey($postArr['seal5'], "100")) * 100000 : 0);
					$ttl_divert = ($this->searchArrayValueByKey($postArr['divert'], "50")!==false ? intval($this->searchArrayValueByKey($postArr['divert'], "50")) * 50000 : 0) + ($this->searchArrayValueByKey($postArr['divert'], "100")!==false ? intval($this->searchArrayValueByKey($postArr['divert'], "100")) * 100000 : 0);
					
					if(isset($postArr['tbag'])) {
						$ttl_tbag = ($this->searchArrayValueByKey($postArr['tbag'], "50")!==false ? intval($this->searchArrayValueByKey($postArr['tbag'], "50")) * 50000 : 0) + ($this->searchArrayValueByKey($postArr['tbag'], "100")!==false ? intval($this->searchArrayValueByKey($postArr['tbag'], "100")) * 100000 : 0);
					}

					$total_all = $ttl_seal1+$ttl_seal2+$ttl_seal3+$ttl_seal4+$ttl_seal5+$ttl_divert;
					
					$tbag_html = '';
					if(!empty($data->t_bag)) {
						$tbag_html = '
							<tr>
								<td align="center"></td>
								<td>T-BAG</td>
								<td align="center"></td>
								<td align="center">'.$row->t_bag.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->t_bag.'</td>
								<td align="left">'.($this->searchArrayValueByKey($postArr['tbag'], "50")!==false ? '50 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['tbag'], "50"))."</span>" : "").' '.(count($postArr['tbag']) > 1 ? "<br>" : "").' '.($this->searchArrayValueByKey($postArr['tbag'], "100")!==false ? '100 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['tbag'], "100"))."</span>" : "").' '.(count($postArr['tbag']) > 1 ? "<br>" : "").'</td>
								<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_tbag, 0, ".", ".").'</span></td>
							</tr>
						';
						
						$total_all = $ttl_seal1+$ttl_seal2+$ttl_seal3+$ttl_seal4+$ttl_seal5+$ttl_divert+$ttl_tbag;
					} else {
						$total_all = $ttl_seal1+$ttl_seal2+$ttl_seal3+$ttl_seal4+$ttl_seal5+$ttl_divert;
					}

					$inner_content_html = '
						<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
							<thead>
								<tr>
									<td rowspan="2" align="center" width="20px">CSST</td>
									<td rowspan="2" align="center">DENOM</td>
									<td rowspan="2" align="center">TOTAL</td>
									<td rowspan="2" align="center">SEAL PREPARE</td>
									<td colspan="2" align="center">STATUS</td>
									<td rowspan="2" align="center">SEAL RETURN</td>
									<td rowspan="2" align="center">VALUE</td>
									<td rowspan="2" align="center">TOTAL RETURN</td>
								</tr>
								<tr>
									<td width="20" align="center">PENGALIHAN</td>
									<td width="20" align="center">CANCEL</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td align="center">1</td>
									<td align="left">
										<span class="alignleft">'.number_format(($denom_1*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_1.'</span>
									</td>
									<td align="center">'.$ttl_1.'</td>
									<td align="center">'.$seal_1.'</td>
									<td></td>
									<td></td>
									<td align="center">'.explode(";", $data->cart_1_seal)[0].'</td>
									<td align="left">'.($this->searchArrayValueByKey($postArr['seal1'], "50")!==false ? '50 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['seal1'], "50"))."</span>" : "").' '.(count($postArr['seal1']) > 1 ? "<br>" : "").' '.($this->searchArrayValueByKey($postArr['seal1'], "100")!==false ? '100 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['seal1'], "100"))."</span>" : "").' '.(count($postArr['seal1']) > 1 ? "<br>" : "").'</td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_seal1, 0, ".", ".").'</span></td>
								</tr>
								<tr>
									<td align="center">2</td>
									<td align="left">
										<span class="alignleft">'.number_format(($denom_2*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_2.'</span>
									</td>
									<td align="center">'.$ttl_2.'</td>
									<td align="center">'.$seal_2.'</td>
									<td></td>
									<td></td>
									<td align="center">'.explode(";", $data->cart_2_seal)[0].'</td>
									<td align="left">'.($this->searchArrayValueByKey($postArr['seal2'], "50")!==false ? '50 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['seal2'], "50"))."</span>" : "").' '.(count($postArr['seal2']) > 1 ? "<br>" : "").' '.($this->searchArrayValueByKey($postArr['seal2'], "100")!==false ? '100 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['seal2'], "100"))."</span>" : "").' '.(count($postArr['seal2']) > 1 ? "<br>" : "").'</td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_seal2, 0, ".", ".").'</span></td>
								</tr>
								<tr>
									<td align="center">3</td>
									<td align="left">
										<span class="alignleft">'.number_format(($denom_3*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_3.'</span>
									</td>
									<td align="center">'.$ttl_3.'</td>
									<td align="center">'.$seal_3.'</td>
									<td></td>
									<td></td>
									<td align="center">'.explode(";", $data->cart_3_seal)[0].'</td>
									<td align="left">'.($this->searchArrayValueByKey($postArr['seal3'], "50")!==false ? '50 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['seal3'], "50"))."</span>" : "").' '.(count($postArr['seal3']) > 1 ? "<br>" : "").' '.($this->searchArrayValueByKey($postArr['seal3'], "100")!==false ? '100 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['seal3'], "100"))."</span>" : "").' '.(count($postArr['seal3']) > 1 ? "<br>" : "").'</td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_seal3, 0, ".", ".").'</span></td>
								</tr>
								<tr>
									<td align="center">4</td>
									<td align="left">
										<span class="alignleft">'.number_format(($denom_4*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_4.'</span>
									</td>
									<td align="center">'.$ttl_4.'</td>
									<td align="center">'.$seal_4.'</td>
									<td></td>
									<td></td>
									<td align="center">'.explode(";", $data->cart_4_seal)[0].'</td>
									<td align="left">'.($this->searchArrayValueByKey($postArr['seal4'], "50")!==false ? '50 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['seal4'], "50"))."</span>" : "").' '.(count($postArr['seal4']) > 1 ? "<br>" : "").' '.($this->searchArrayValueByKey($postArr['seal4'], "100")!==false ? '100 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['seal4'], "100"))."</span>" : "").' '.(count($postArr['seal4']) > 1 ? "<br>" : "").'</td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_seal4, 0, ".", ".").'</span></td>
								</tr>
								<tr>
									<td align="center">5</td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center">'.$seal_5.'</td>
									<td></td>
									<td></td>
									<td align="center">'.explode(";", $data->cart_5_seal)[0].'</td>
									<td align="left">'.($this->searchArrayValueByKey($postArr['seal5'], "50")!==false ? '50 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['seal5'], "50"))."</span>" : "").' '.(count($postArr['seal5']) > 1 ? "<br>" : "").' '.($this->searchArrayValueByKey($postArr['seal5'], "100")!=="" ? '100 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['seal5'], "100"))."</span>" : "").' '.(count($postArr['seal5']) > 1 ? "<br>" : "").'</td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_seal5, 0, ".", ".").'</span></td>
								</tr>
								<tr>
									<td align="center">6</td>
									<td>DIVERT</td>
									<td align="center"></td>
									<td align="center">'.$row->divert.'</td>
									<td></td>
									<td></td>
									<td align="center">'.$data->div_seal.'</td>
									<td align="left">'.($this->searchArrayValueByKey($postArr['divert'], "50")!==false ? '50 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['divert'], "50"))."</span>" : "").' '.(count($postArr['divert']) > 1 ? "<br>" : "").' '.($this->searchArrayValueByKey($postArr['divert'], "100")!==false ? '100 : <span class="alignright">'.intval($this->searchArrayValueByKey($postArr['divert'], "100"))."</span>" : "").' '.(count($postArr['divert']) > 1 ? "<br>" : "").'</td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_divert, 0, ".", ".").'</span></td>
								</tr>
								'.$tbag_html.'
								<tr>
									<td colspan="7" id="noborder"></td>
									<td align="center">'.number_format($total_value, 0, ",",".").'</td>
									<td align="left">Rp. <span class="alignright">'.number_format($total_all, 0, ",", ".").'</td>
								</tr>
							</tbody>
						</table>
					';
				} else {
					$inner_content_html = '
						<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
							<thead>
								<tr>
									<td rowspan="2" align="center" width="20px">CSST</td>
									<td rowspan="2" align="center">DENOM</td>
									<td rowspan="2" align="center">TOTAL</td>
									<td rowspan="2" align="center">SEAL PREPARE</td>
									<td colspan="2" align="center">STATUS</td>
									<td rowspan="2" align="center">SEAL RETURN</td>
									<td rowspan="2" align="center">VALUE</td>
									<td rowspan="2" align="center">TOTAL RETURN</td>
								</tr>
								<tr>
									<td width="20" align="center">PENGALIHAN</td>
									<td width="20" align="center">CANCEL</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td align="center">1</td>
									<td align="left">
										<span class="alignleft">'.number_format(($denom_1*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_1.'</span>
									</td>
									<td align="center">'.$ttl_1.'</td>
									<td align="center">'.$seal_1.'</td>
									<td></td>
									<td></td>
									<td align="center">'.$data->cart_1_seal.'</td>
									<td align="left"></td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_seal1, 0, ".", ".").'</span></td>
								</tr>
								<tr>
									<td align="center">2</td>
									<td align="left">
										<span class="alignleft">'.number_format(($denom_2*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_2.'</span>
									</td>
									<td align="center">'.$ttl_2.'</td>
									<td align="center">'.$seal_2.'</td>
									<td></td>
									<td></td>
									<td align="center">'.$data->cart_2_seal.'</td>
									<td align="left"></td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_seal2, 0, ".", ".").'</span></td>
								</tr>
								<tr>
									<td align="center">3</td>
									<td align="left">
										<span class="alignleft">'.number_format(($denom_3*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_3.'</span>
									</td>
									<td align="center">'.$ttl_3.'</td>
									<td align="center">'.$seal_3.'</td>
									<td></td>
									<td></td>
									<td align="center">'.$data->cart_3_seal.'</td>
									<td align="left"></td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_seal3, 0, ".", ".").'</span></td>
								</tr>
								<tr>
									<td align="center">4</td>
									<td align="left">
										<span class="alignleft">'.number_format(($denom_4*1000), 0, ",",".").'</span>
										<span class="alignright">'.$value_4.'</span>
									</td>
									<td align="center">'.$ttl_4.'</td>
									<td align="center">'.$seal_4.'</td>
									<td></td>
									<td></td>
									<td align="center">'.$data->cart_4_seal.'</td>
									<td align="left"></td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_seal4, 0, ".", ".").'</span></td>
								</tr>
								<tr>
									<td align="center">5</td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center">'.$seal_5.'</td>
									<td></td>
									<td></td>
									<td align="center">'.$data->cart_5_seal.'</td>
									<td align="left"></td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_seal5, 0, ".", ".").'</span></td>
								</tr>
								<tr>
									<td align="center">6</td>
									<td>DIVERT</td>
									<td align="center"></td>
									<td align="center">'.$row->divert.'</td>
									<td></td>
									<td></td>
									<td align="center">'.$data->div_seal.'</td>
									<td align="left"></td>
									<td align="left"><span class="alignleft">Rp.</span> <span class="alignright">'.number_format($ttl_divert, 0, ".", ".").'</span></td>
								</tr>
								'.$tbag_html.'
								<tr>
									<td colspan="7" id="noborder"></td>
									<td align="center">'.number_format($total_value, 0, ",",".").'</td>
									<td align="left">Rp. <span class="alignright">'.number_format($total_all, 0, ",", ".").'</td>
								</tr>
							</tbody>
						</table>
					';
				}
			} else if($type=="CDM") {
				$ttl_ctr = '('.$ctr.') '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)), 0, ",", ".").'';
				$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
				$value = (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000);
				$ttl_all = 'Rp. '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom), 0, ",", ".").'';
				$terbilang = ucwords($this->terbilang(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom)));
				
				$total = ($data->cart_1_no!=="" ? (intval($denom)*$data->cart_1_no) : "") +
						 ($data->cart_2_no!=="" ? (intval($denom)*$data->cart_2_no) : "") +
						 ($data->cart_3_no!=="" ? (intval($denom)*$data->cart_3_no) : "") +
						 ($data->cart_4_no!=="" ? (intval($denom)*$data->cart_4_no) : "") +
						 ($data->div_no!=="" ? (intval($denom)*$data->div_no) : "");
						 
				$postArr = json_decode($data->data_seal, true);
				// $postArr = array_map('array_filter', $postArr);
				// $postArr = array_filter($postArr);
				
				// print_r($this->arr($data->cart_2_no)["50"]);
				// echo "<br>";
				
				$total_value = 0;
				foreach ($postArr as $item) {
					$total_value += $item['20'];
					$total_value += $item['50'];
					$total_value += $item['100'];
				}
				
				$count_seal1 = count(array_filter(json_decode($data->cart_1_no, true)));
				$count_seal2 = count(array_filter(json_decode($data->cart_2_no, true)));
				$count_seal3 = count(array_filter(json_decode($data->cart_3_no, true)));
				$count_seal4 = count(array_filter(json_decode($data->cart_4_no, true)));
				$count_div = count(array_filter(json_decode($data->div_no, true)));
				
				$val_seal1 = "";
				$total_seal1 = 0;
				foreach($this->arr($data->cart_1_no) as $k => $r) {
					if($r!="") {
						$val_seal1 .= $k.': <span class="alignright">'.$r.'</span><br>';
						$total_seal1 += $r;
						$total_seal1_value_str .= 'Rp. <span class="alignright">'.number_format(($k*$r*1000), 0, ",", ".").'</span><br>';
						$total_seal1_value += $k*$r;
					}
				}
				$val_seal2 = "";
				$total_seal2 = 0;
				foreach($this->arr($data->cart_2_no) as $k => $r) {
					if($r!="") {
						$val_seal2 .= $k.': <span class="alignright">'.$r.'</span><br>';
						$total_seal2 += $r;
						$total_seal2_value_str .= 'Rp. <span class="alignright">'.number_format(($k*$r*1000), 0, ",", ".").'</span><br>';
						$total_seal2_value += $k*$r;
					}
				}
				$val_seal3 = "";
				$total_seal3 = 0;
				foreach($this->arr($data->cart_3_no) as $k => $r) {
					if($r!="") {
						$val_seal3 .= $k.': <span class="alignright">'.$r.'</span><br>';
						$total_seal3 += $r;
						$total_seal3_value_str .= 'Rp. <span class="alignright">'.number_format(($k*$r*1000), 0, ",", ".").'</span><br>';
						$total_seal3_value += $k*$r;
					}
				}
				$val_seal4 = "";
				$total_seal4 = 0;
				foreach($this->arr($data->cart_4_no) as $k => $r) {
					if($r!="") {
						$val_seal4 .= $k.': <span class="alignright">'.$r.'</span><br>';
						$total_seal4 += $r;
						$total_seal4_value_str .= 'Rp. <span class="alignright">'.number_format(($k*$r*1000), 0, ",", ".").'</span><br>';
						$total_seal4_value += $k*$r;
					}
				}
				$val_divert = "";
				$total_divert = 0;
				foreach($this->arr($data->div_no) as $k => $r) {
					if($r!="") {
						$val_divert .= $k.': <span class="alignright">'.$r.'</span><br>';
						$total_divert += $r;
						$total_divert_value_str .= 'Rp. <span class="alignright">'.number_format(($k*$r*1000), 0, ",", ".").'</span><br>';
						$total_divert_value += $k*$r;
					}
				}
				
				$total_all = $total_seal1+$total_seal2+$total_seal3+$total_seal4+$total_divert;
				$total_all_value = $total_seal1_value+$total_seal2_value+$total_seal3_value+$total_seal4_value+$total_divert_value;
				$total_all_value_str = 'Rp. <span class="alignright">'.number_format(($total_all_value*1000), 0, ",", ".").'</span>';
				
				if(empty($val_seal1)) {
					$val_seal1 = "<center style='font-weight: bold'>-</center>";
				}
				
				if(empty($val_seal2)) {
					$val_seal2 = "<center style='font-weight: bold'>-</center>";
				}
				
				if(empty($val_seal3)) {
					$val_seal3 = "<center style='font-weight: bold'>-</center>";
				}
				
				if(empty($val_seal4)) {
					$val_seal4 = "<center style='font-weight: bold'>-</center>";
				}
				
				if(empty($val_divert)) {
					$val_divert = "<center style='font-weight: bold'>-</center>";
				}
				
				// echo "<pre>";print_r($data);
				
				$tbag_html = '';
				if(!empty($data->t_bag)) {
					$val_tbag = "";
					$total_tbag = 0;
					foreach($this->arr($data->t_bag_no) as $k => $r) {
						if($r!="") {
							$val_tbag .= $k.': <span class="alignright">'.$r.'</span><br>';
							$total_tbag += $r;
							$total_tbag_value_str .= 'Rp. <span class="alignright">'.number_format(($k*$r*1000), 0, ",", ".").'</span><br>';
							$total_tbag_value += $k*$r;
						}
					}
					$total_all = $total_seal1+$total_seal2+$total_seal3+$total_seal4+$total_divert+$total_tbag;
					$total_all_value = $total_seal1_value+$total_seal2_value+$total_seal3_value+$total_seal4_value+$total_divert_value+$total_tbag_value;
					$total_all_value_str = 'Rp. <span class="alignright">'.number_format(($total_all_value*1000), 0, ",", ".").'</span>';
					
					$tbag_html = '
						<tr>
							<td>T-BAG</td>
							<td align="center">'.$row->t_bag.'</td>
							<td></td>
							<td></td>
							<td align="center">'.$data->t_bag.'</td>
							<td align="left">'.$val_tbag.'</td>
							<td align="left">'.$total_tbag_value_str.'</td>
						</tr>
					';
				} else {
					$total_all = $total_seal1+$total_seal2+$total_seal3+$total_seal4+$total_divert;
					$total_all_value = $total_seal1_value+$total_seal2_value+$total_seal3_value+$total_seal4_value+$total_divert_value;
					$total_all_value_str = 'Rp. <span class="alignright">'.number_format(($total_all_value*1000), 0, ",", ".").'</span>';
				}
				
				
				$inner_content_html = '
					<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
						<thead>
							<tr>
								<td rowspan="2" align="center">PREPARATION</td>
								<td rowspan="2" align="center">SEAL PREPARE</td>
								<td colspan="2" align="center">STATUS</td>
								<td rowspan="2" align="center">SEAL RETURN</td>
								<td rowspan="2" align="center">VALUE</td>
								<td rowspan="2" align="center">TOTAL RETURN</td>
							</tr>
							<tr>
								<td width="20" align="center">PENGALIHAN</td>
								<td width="20" align="center">CANCEL</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Catridge 1</td>
								<td align="center">'.$row->cart_1_seal.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->cart_1_seal.'</td>
								<td align="left">'.$val_seal1.'</td>
								<td align="left">'.$total_seal1_value_str.'</td>
							</tr>
							<tr>
								<td>Catridge 2</td>
								<td align="center">'.$row->cart_2_seal.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->cart_2_seal.'</td>
								<td align="left">'.$val_seal2.'</td>
								<td align="left">'.$total_seal2_value_str.'</td>
							</tr>
							<tr>
								<td>Catridge 3</td>
								<td align="center">'.$row->cart_3_seal.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->cart_3_seal.'</td>
								<td align="left">'.$val_seal3.'</td>
								<td align="left">'.$total_seal3_value_str.'</td>
							</tr>
							<tr>
								<td>Catridge 4</td>
								<td align="center">'.$row->cart_4_seal.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->cart_4_seal.'</td>
								<td align="left">'.$val_seal4.'</td>
								<td align="left">'.$total_seal4_value_str.'</td>
							</tr>
							<tr>
								<td>Divert</td>
								<td align="center">'.$row->divert.'</td>
								<td></td>
								<td></td>
								<td align="center">'.$data->div_seal.'</td>
								<td align="left">'.$val_divert.'</td>
								<td align="left">'.$total_divert_value_str.'</td>
							</tr>
							'.$tbag_html.'
							<tr>
								<td colspan=5 id="noborder"></td>
								<td align="center">'.number_format($total_all, 0, ",", ".").'</td>
								<td align="left">'.$total_all_value_str.'</td>
							</tr>
						</tbody>
					</table>
				';
			}
			
			$content_html .= '
				<table class="first">
					<tr>
						<td width="50%">
							<p id="h3">PT. BINTANG JASA ARTHA KELOLA</p>
							<p>REPORT REPLENISH - RETURN ATM</p>
							<hr style="border-style: solid;height:1px;border:none;color:#333;background-color:#333;margin-top: -10px" />
							
							<table class="second">
								<tr>
									<td style="width: 60px">LOCATION</td>
									<td style="width: 10px">:</td>
									<td>'.$row->lokasi.'</td>
								</tr>
								<tr>
									<td style="width: 60px">ID</td>
									<td style="width: 10px">:</td>
									<td>'.$row->wsid.'</td>
								</tr>
							</table>
							<table class="second">
								<tr>
									<td style="width: 60px">BANK</td>
									<td style="width: 10px">:</td>
									<td>'.$row->bank.'</td>
									
									<td style="width: 60px">DENOM</td>
									<td style="width: 10px">:</td>
									<td>'.number_format($denom, 0, ",", ".").'</td>
								</tr>
								<tr>
									<td style="width: 60px">TYPE</td>
									<td style="width: 10px">:</td>
									<td>'.$row->type_mesin.'</td>
									
									<td style="width: 60px">VALUE</td>
									<td style="width: 10px">:</td>
									<td>'.number_format($value, 0, ",", ".").'</td>
								</tr>
							</table>
						</td>
						<td width="15%">
							<center>
								
							</center>
						</td>
						<td width="35%">
							<table class="second">
								<tr>
									<td style="width: 150px">TANGGAL</td>
									<td style="width: 10px">:</td>
									<td>'.date("d-M-Y", strtotime(explode(" ", $row->date)[0])).'</td>
								</tr>
								<tr>
									<td>TIME REPLENISH(CSO)</td>
									<td>:</td>
									<td>'.date("H:i", strtotime(explode(" ", $row->updated_date)[1])).'</td>
								</tr>
								<tr>
									<td>TIME PREPARE BAG(CPC)</td>
									<td>:</td>
									<td>'.date("H:i", strtotime(explode(" ", $row->updated_date_cpc)[1])).'</td>
								</tr>
							</table>
							<hr style="height:1px;border:none;color:#333;background-color:#333;width:100%; text-align: left; margin: 2px" />
							<table class="second">
								<tr>
									<td style="width: 150px">CASHIER</td>
									<td style="width: 10px">:</td>
									<td>'.($data->cashier!==null ? 
									json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>
									"SELECT nama FROM karyawan WHERE nik='".$data->cashier."'"
									), array(CURLOPT_BUFFERSIZE => 10)))->nama : "...........................").'</td>
								</tr>
								<tr>
									<td>NO. MEJA</td>
									<td>:</td>
									<td>'.($data->nomeja!==null ? $data->nomeja : "...........................").'</td>
								</tr>
								<tr>
									<td>JAM PROSES</td>
									<td>:</td>
									<td>'.($data->jamproses!==null ? $data->jamproses : "...........................").'</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table class="third">
					<tr>
						<td style="width: 45px; text-align: center; border: 1px solid black; border-style: solid;">RUN</td>
					</tr>
					<tr>
						<td style="width: 45px; text-align: center; font-size: 24px;">'.$row->sektor.'</td>
					</tr>
				</table>
				
				'.$inner_content_html.'
				
				<table style="width: 80%; font-size: 10px; margin-top: -14px">
					<tr>
						<td style="width: 120px">TOTAL CATRIDGE</td>
						<td style="width: 10px">:</td>
						<td>'.$ttl_ctr.'</td>
						
						<td style="width: 120px">NO. BAG</td>
						<td style="width: 10px">:</td>
						<td>'.$row->bag_no.'</td>
					</tr>
					<tr>
						<td style="width: 60px">TOTAL</td>
						<td style="width: 10px">:</td>
						<td>'.$ttl_all.'</td>
						
						<td style="width: 60px">SEAL BAG(CPC)</td>
						<td style="width: 10px">:</td>
						<td>'.$row->bag_seal.'</td>
					</tr>
					<tr>
						<td style="width: 60px">TERBILANG</td>
						<td style="width: 10px">:</td>
						<td style="font-weight: bold"># '.$terbilang.' #</td>
						
						<td style="width: 60px">SEAL BAG(CSO)</td>
						<td style="width: 10px">:</td>
						<td>'.$data->bag_seal.'</td>
					</tr>
				</table>
				
				<table style="width: 100%; font-size: 10px" class="fifth">
					<tr>
						<td align="center" class="noborderbottom noborderright">Prepared By</td>
						<td align="center" class="noborderbottom noborderleft" colspan=2>Received By</td>
						<td align="center" class="noborderbottom noborderleft">Ops,</td>
						<td align="center" class="noborderbottom">Approval By Return</td>
					</tr>
					<tr>
						<td class="nobordertop noborderbottom" align="center" style="height: 60px"></td>
						<td class="nobordertop noborderbottom" align="center">
							<img style="padding-top: 2px; padding-left: 2px" src="'.realpath(__DIR__ . '/../../upload/qrcode_karyawan').'/'.$row->id_karyawan_1.'.png" width="62" height="62"></img>
						</td>
						<td class="nobordertop noborderbottom" align="center">
							<img style="padding-top: 2px; padding-left: 2px; visibility: '.($row->custodian_2=="" ? "hidden" : "visible	").'" src="'.realpath(__DIR__ . '/../../upload/qrcode_karyawan').'/'.$row->id_karyawan_2.'.png" width="62" height="62"></img>
						</td>
						<td class="nobordertop noborderbottom" align="center"></td>
						<td class="nobordertop noborderbottom" align="center"></td>
					</tr>
					<tr>
						<td style="width: 16.6%" class="nobordertop noborderright" align="center">DUTY CPC</td>
						<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center"><u>'.$row->nama_custody_1.'</u><br>CUSTODY</td>
						<td style="width: 16.6%" class="nobordertop noborderright" align="center"><u>'.($row->nama_custody_2=="" ? "-none-" : $row->nama_custody_2).'</u><br>CUSTODY</td>
						<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">DUTY OFFICER</td>
						<td style="width: 16.6%" class="nobordertop " align="center">DUTY CPC</td>
					</tr>
				</table>
			';
		}
		
		$template_html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 210mm 297mm portrait; }
					
						@font-face {
						  font-family: "aaaaa";
						  src: URL("'.base_url().'depend/font/serif_dot_digital-7.ttf") format("truetype");
						}
					
						body {
							margin: 27px 30px 0px 30px; size: 14cm 22cm landscape;
							font-family: Calibri;            
						}
					
						table.first {
							font-family: Calibri;            
							font-size: 8pt;
							width: 100%;
						}
						
						#h3 {
							font-family: Calibri; 
							font-size: 12pt;
						}
						
						table.first td {
							line-height: 1px;
						}	
						
						table.second {
							width: 100%;
						}
						
						table.second td {
							line-height: 12px;
						}
						
						.third {
							font-family: Calibri;       
							font-size: 8pt;
							border: 1px solid black;
							border-collapse: collapse;
							position: absolute;
							top: 30;
							right: 260;
							border-style: solid;
						}
						
						.fourth {
							font-family: Calibri;       
							font-size: 8pt;
							border-collapse: collapse;
							border: 1px solid black;
							border-style: solid;
						}
						
						.fifth {
							font-family: Calibri;       
							font-size: 8pt;
							border-collapse: collapse;
							border: 1px solid black;
							border-style: solid;
						}
						
						.sixth {
							font-family: Calibri;       
							border: none;
							border-collapse: collapse;
						}
						
						.sixth td {
							padding: 4px;
							border: 1px solid black;
							border-style: solid;
						}
						
						table.fourth td {
						    padding: 4px;
							border: 1px solid black;
							border-style: solid;
						}
						
						#noborder {
							border: none;
						}
						#noborderbottom {
							border-bottom: 0px solid white;
						}
						#nobordertop {
							border-top: 0px solid white;
						}
						.noborder {
							border: 0px solid white;
						}
						.noborderbottom {
							border-bottom: 0px solid white;
						}
						.nobordertop {
							border-top: 0px solid white;
						}
						.noborderright {
							border-right: 0px solid white;
						}
						.noborderleft {
							border-left: 0px solid white;
						}
						
						.alignleft {
							
						}
						.alignright {
							float: right;
						}
						#table_receipt tr td {
							padding: 10px;
						}
					</style>
				</head>

				<body>
					'.$content_html.'
					
					<br>
					<table id="table_receipt" style="border-collapse: collapse;" width="100%">
						<tr>
							<td align="center">
								<img width="210px"  height="290px" src="'.$row->receipt_1.'"/>
							</td>
							<td align="center">
								<img width="210px"  height="290px" src="'.$row->receipt_2.'"/>
							</td>
						</tr>
						<tr>
							<td align="center">
								<img width="210px"  height="290px" src="'.$row->receipt_3.'"/>
							</td>
							<td align="center">
								<img width="210px"  height="290px" src="'.$row->receipt_4.'"/>
							</td>
						</tr>
					</table>
				</body>
			</html>
		';
		
		
		
		
		if($html==true) {
			echo $template_html;
		} else {
			$dompdf = new DOMPDF();
			$dompdf->loadHtml($template_html);
			$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
			$dompdf->render();

			$dompdf->stream($row->wsid.'('.date('Ymd', strtotime($row->date)).').pdf', array("Attachment" => false));
		}
	}
	
	public function arr($arr) {
		return json_decode($arr, TRUE);
	}
	
	public function searchArrayValueByKey(array $array, $search) {
    	// foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $key => $value) {
			// echo "<pre>";
			// print_r($key);
    	    // // if ($search === $key)
    		// // return $value;
    	// }
		
		foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $key => $value) {
			// echo "<pre>";
			// print_r($key." = ".$value);
			if ($search == $key) {
				return $value;
			}
		}
		return false;
	}
	
	public function runsheet_atm() {
		$html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 22cm 14cm portrait; }
					
						@font-face {
						  font-family: "aaaaa";
						  src: URL("'.base_url().'depend/font/serif_dot_digital-7.ttf") format("truetype");
						}
					
						body {
							margin: 27px 30px 0px 30px; size: 14cm 22cm landscape;
							font-family: Calibri;            
						}
					
						table.first {
							font-family: Calibri;            
							font-size: 8pt;
							width: 100%;
						}
						
						#h3 {
							font-family: Calibri; 
							font-size: 12pt;
						}
						
						table.first td {
							line-height: 5px;
						}	
						
						table.second {
							width: 100%;
						}
						
						table.second td {
							line-height: 10px;
						}
						
						.third {
							font-family: Calibri;       
							font-size: 8pt;
							border: 1px solid black;
							border-collapse: collapse;
							position: absolute;
							top: 30;
							right: 260;
							border-style: solid;
						}
						
						.fourth {
							font-family: Calibri;       
							font-size: 8pt;
							border-collapse: collapse;
							border: 1px solid black;
							border-style: solid;
						}
						
						.fifth {
							font-family: Calibri;       
							font-size: 8pt;
							border-collapse: collapse;
							border: 1px solid black;
							border-style: solid;
						}
						
						.sixth {
							font-family: Calibri;       
							border: none;
							border-collapse: collapse;
						}
						
						.sixth td {
							padding: 4px;
							border: 1px solid black;
							border-style: solid;
						}
						
						table.fourth td {
						    padding: 4px;
							border: 1px solid black;
							border-style: solid;
						}
						
						#noborder {
							border: none;
						}
						#noborderbottom {
							border-bottom: 0px solid white;
						}
						#nobordertop {
							border-top: 0px solid white;
						}
						.noborder {
							border: 0px solid white;
						}
						.noborderbottom {
							border-bottom: 0px solid white;
						}
						.nobordertop {
							border-top: 0px solid white;
						}
						.noborderright {
							border-right: 0px solid white;
						}
						.noborderleft {
							border-left: 0px solid white;
						}
						
						.alignleft {
							float: left;
						}
						.alignright {
							float: right;
						}
					</style>
				</head>

				<body>
					<table class="first">
						<tr>
							<td width="50%">
								<p id="h3">PT. BINTANG JASA ARTHA KELOLA</p>
								<p>REPORT REPLENISH - RETURN ATM</p>
								<hr style="border-style: solid;height:1px;border:none;color:#333;background-color:#333;" />
								
								<table class="second">
									<tr>
										<td style="width: 60px">LOCATION</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 60px">ID</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
								</table>
								<table class="second">
									<tr>
										<td style="width: 60px">BANK</td>
										<td style="width: 10px">:</td>
										<td></td>
										
										<td style="width: 60px">DENOM</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 60px">TYPE</td>
										<td style="width: 10px">:</td>
										<td></td>
										
										<td style="width: 60px">VALUE</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
								</table>
							</td>
							<td width="15%">
								<center>
									
								</center>
							</td>
							<td width="35%">
								<table class="second">
									<tr>
										<td style="width: 150px">TANGGAL</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td>TIME REPLENISH(CSO)</td>
										<td>:</td>
										<td></td>
									</tr>
									<tr>
										<td>TIME PREPARE BAG(CPC)</td>
										<td>:</td>
										<td></td>
									</tr>
								</table>
								<hr style="height:1px;border:none;color:#333;background-color:#333;width:100%; text-align: left; margin: 2px" />
								<table class="second">
									<tr>
										<td style="width: 150px">CASHIER</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td>NO. MEJA</td>
										<td>:</td>
										<td></td>
									</tr>
									<tr>
										<td>JAM PROSES</td>
										<td>:</td>
										<td></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table class="third">
						<tr>
							<td style="width: 45px; text-align: center; border: 1px solid black; border-style: solid;">RUN</td>
						</tr>
						<tr>
							<td style="width: 45px; text-align: center; font-size: 24px;">12</td>
						</tr>
					</table>
					
					<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
						<thead>
							<tr>
								<td rowspan="2" align="center">PREPARATION</td>
								<td rowspan="2" align="center">SEAL PREPARE</td>
								<td colspan="2" align="center">STATUS</td>
								<td rowspan="2" align="center">SEAL RETURN</td>
								<td rowspan="2" align="center">VALUE</td>
								<td rowspan="2" align="center">TOTAL RETURN</td>
							</tr>
							<tr>
								<td width="90" align="center">PENGALIHAN</td>
								<td width="90" align="center">CANCEL</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Catridge 1</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 2</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 3</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Catridge 4</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td>Divert</td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td colspan=5 id="noborder"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
						</tbody>
					</table>
					
					
					<table style="width: 80%; font-size: 10px; margin-top: -14px">
						<tr>
							<td style="width: 120px">TOTAL CATRIDGE</td>
							<td style="width: 10px">:</td>
							<td>() </td>
							
							<td style="width: 120px">NO. BAG</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
						<tr>
							<td style="width: 60px">TOTAL</td>
							<td style="width: 10px">:</td>
							<td>Rp. </td>
							
							<td style="width: 60px">SEAL BAG(CPC)</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
						<tr>
							<td style="width: 60px">TERBILANG</td>
							<td style="width: 10px">:</td>
							<td style="font-weight: bold">#  #</td>
							
							<td style="width: 60px">SEAL BAG(CSO)</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
					</table>
					
					<table style="width: 100%; font-size: 10px" class="fifth">
						<tr>
							<td align="center" class="noborderbottom noborderright">Prepared By</td>
							<td align="center" class="noborderbottom noborderleft" colspan=2>Received By</td>
							<td align="center" class="noborderbottom" colspan=2>Ops,</td>
							<td align="center" class="noborderbottom">Approval By Return</td>
						</tr>
						<tr>
							<td class="nobordertop noborderbottom" align="center" style="height: 60px" colspan=3></td>
							<td class="nobordertop noborderbottom" colspan=2></td>
							<td class="nobordertop noborderbottom"></td>
						</tr>
						<tr>
							<td style="width: 16.6%" class="nobordertop noborderright" align="center">DUTY CPC</td>
							<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">CSO</td>
							<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">SCC</td>
							<td style="width: 16.6%" class="nobordertop noborderright" align="center">CSO/SCC</td>
							<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">CPC</td>
							<td style="width: 16.6%" class="nobordertop " align="center">DUTY CPC</td>
						</tr>
					</table>
				</body>
			</html>
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function runsheet_crm() {
		$html = '
			<html>
				<head>
					<style>
						
						@page { margin: 0px; size: 22cm 14cm portrait; }
					
						@font-face {
						  font-family: "aaaaa";
						  src: URL("'.base_url().'depend/font/serif_dot_digital-7.ttf") format("truetype");
						}
					
						body {
							margin: 27px 30px 0px 30px; size: 14cm 22cm landscape;
							font-family: Calibri;            
						}
					
						table.first {
							font-family: Calibri;            
							font-size: 8pt;
							width: 100%;
						}
						
						#h3 {
							font-family: Calibri; 
							font-size: 12pt;
						}
						
						table.first td {
							line-height: 5px;
						}	
						
						table.second {
							width: 100%;
						}
						
						table.second td {
							line-height: 10px;
						}
						
						.third {
							font-family: Calibri;       
							font-size: 8pt;
							border: 1px solid black;
							border-collapse: collapse;
							position: absolute;
							top: 30;
							right: 260;
							border-style: solid;
						}
						
						.fourth {
							font-family: Calibri;       
							font-size: 8pt;
							border-collapse: collapse;
							border: 1px solid black;
							border-style: solid;
						}
						
						.fifth {
							font-family: Calibri;       
							font-size: 8pt;
							border-collapse: collapse;
							border: 1px solid black;
							border-style: solid;
						}
						
						.sixth {
							font-family: Calibri;       
							border: none;
							border-collapse: collapse;
						}
						
						.sixth td {
							padding: 4px;
							border: 1px solid black;
							border-style: solid;
						}
						
						table.fourth td {
						    padding: 4px;
							border: 1px solid black;
							border-style: solid;
						}
						
						#noborder {
							border: none;
						}
						#noborderbottom {
							border-bottom: 0px solid white;
						}
						#nobordertop {
							border-top: 0px solid white;
						}
						.noborder {
							border: 0px solid white;
						}
						.noborderbottom {
							border-bottom: 0px solid white;
						}
						.nobordertop {
							border-top: 0px solid white;
						}
						.noborderright {
							border-right: 0px solid white;
						}
						.noborderleft {
							border-left: 0px solid white;
						}
						
						.alignleft {
							float: left;
						}
						.alignright {
							float: right;
						}
					</style>
				</head>

				<body>
					<table class="first">
						<tr>
							<td width="50%">
								<p id="h3">PT. BINTANG JASA ARTHA KELOLA</p>
								<p>REPORT REPLENISH - RETURN ATM</p>
								<hr style="border-style: solid;height:1px;border:none;color:#333;background-color:#333;" />
								
								<table class="second">
									<tr>
										<td style="width: 60px">LOCATION</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 60px">ID</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
								</table>
								<table class="second">
									<tr>
										<td style="width: 60px">BANK</td>
										<td style="width: 10px">:</td>
										<td></td>
										
										<td style="width: 60px">DENOM</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 60px">TYPE</td>
										<td style="width: 10px">:</td>
										<td></td>
										
										<td style="width: 60px">VALUE</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
								</table>
							</td>
							<td width="15%">
								<center>
									
								</center>
							</td>
							<td width="35%">
								<table class="second">
									<tr>
										<td style="width: 150px">TANGGAL</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td>TIME REPLENISH(CSO)</td>
										<td>:</td>
										<td></td>
									</tr>
									<tr>
										<td>TIME PREPARE BAG(CPC)</td>
										<td>:</td>
										<td></td>
									</tr>
								</table>
								<hr style="height:1px;border:none;color:#333;background-color:#333;width:100%; text-align: left; margin: 2px" />
								<table class="second">
									<tr>
										<td style="width: 150px">CASHIER</td>
										<td style="width: 10px">:</td>
										<td></td>
									</tr>
									<tr>
										<td>NO. MEJA</td>
										<td>:</td>
										<td></td>
									</tr>
									<tr>
										<td>JAM PROSES</td>
										<td>:</td>
										<td></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table class="third">
						<tr>
							<td style="width: 45px; text-align: center; border: 1px solid black; border-style: solid;">RUN</td>
						</tr>
						<tr>
							<td style="width: 45px; text-align: center; font-size: 24px;">12</td>
						</tr>
					</table>
					
					<table style="width: 100%; font-size: 10px;" border="1" class="sixth">
						<thead>
							<tr>
								<td rowspan="2" align="center" width="20px">CSST</td>
								<td rowspan="2" align="center">DENOM</td>
								<td rowspan="2" align="center">TOTAL</td>
								<td rowspan="2" align="center">SEAL PREPARE</td>
								<td colspan="2" align="center">STATUS</td>
								<td rowspan="2" align="center">SEAL RETURN</td>
								<td rowspan="2" align="center">VALUE</td>
								<td rowspan="2" align="center">TOTAL RETURN</td>
							</tr>
							<tr>
								<td width="20" align="center">PENGALIHAN</td>
								<td width="20" align="center">CANCEL</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td align="center">1</td>
								<td align="center">
									<span class="alignleft">100.000</span>
									<span class="alignright">400</span>
								</td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">2</td>
								<td align="center">
									<span class="alignleft">100.000</span>
									<span class="alignright">400</span>
								</td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">3</td>
								<td align="center">
									<span class="alignleft">50.000</span>
									<span class="alignright">400</span>
								</td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">4</td>
								<td align="center">
									<span class="alignleft">50.000</span>
									<span class="alignright">400</span>
								</td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">5</td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td align="center">6</td>
								<td>DIVERT</td>
								<td align="center"></td>
								<td align="center"></td>
								<td></td>
								<td></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
							<tr>
								<td colspan="7" id="noborder"></td>
								<td align="center"></td>
								<td align="left">Rp.</td>
							</tr>
						</tbody>
					</table>
					
					
					<table style="width: 80%; font-size: 10px; margin-top: -14px">
						<tr>
							<td style="width: 120px">TOTAL CATRIDGE</td>
							<td style="width: 10px">:</td>
							<td>() </td>
							
							<td style="width: 120px">NO. BAG</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
						<tr>
							<td style="width: 60px">TOTAL</td>
							<td style="width: 10px">:</td>
							<td>Rp. </td>
							
							<td style="width: 60px">SEAL BAG(CPC)</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
						<tr>
							<td style="width: 60px">TERBILANG</td>
							<td style="width: 10px">:</td>
							<td style="font-weight: bold">#  #</td>
							
							<td style="width: 60px">SEAL BAG(CSO)</td>
							<td style="width: 10px">:</td>
							<td></td>
						</tr>
					</table>
					
					<table style="width: 100%; font-size: 10px" class="fifth">
						<tr>
							<td align="center" class="noborderbottom noborderright">Prepared By</td>
							<td align="center" class="noborderbottom noborderleft" colspan=2>Received By</td>
							<td align="center" class="noborderbottom" colspan=2>Ops,</td>
							<td align="center" class="noborderbottom">Approval By Return</td>
						</tr>
						<tr>
							<td class="nobordertop noborderbottom" align="center" style="height: 60px" colspan=3></td>
							<td class="nobordertop noborderbottom" colspan=2></td>
							<td class="nobordertop noborderbottom"></td>
						</tr>
						<tr>
							<td style="width: 16.6%" class="nobordertop noborderright" align="center">DUTY CPC</td>
							<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">CSO</td>
							<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">SCC</td>
							<td style="width: 16.6%" class="nobordertop noborderright" align="center">CSO/SCC</td>
							<td style="width: 16.6%" class="nobordertop noborderright noborderleft" align="center">CPC</td>
							<td style="width: 16.6%" class="nobordertop " align="center">DUTY CPC</td>
						</tr>
					</table>
				</body>
			</html>
		';
		
		$dompdf = new DOMPDF();
		$dompdf->loadHtml($html);
		$dompdf->set_paper(array(0, 0, 227, 151), "portrait");
		$dompdf->render();

		$dompdf->stream('document.pdf', array("Attachment" => false));
	}
	
	public function runsheet_report() {
		
	}
	
	public function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = $this->penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . $this->penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}

	public function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim($this->penyebut($nilai))." Rupiah";
		} else if($nilai==0) {
			$hasil = '';
		} else {
			$hasil = "". trim($this->penyebut($nilai))." Rupiah";
		}     		
		return $hasil;
	}
}