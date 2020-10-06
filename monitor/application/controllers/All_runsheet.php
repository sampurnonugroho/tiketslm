<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_runsheet extends CI_Controller {
	var $access_boc = false;
	
    public function __construct() {
        parent::__construct();
		$this->load->library('curl');	

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));
			$level = trim($this->session->userdata('level'));

			$this->data['level'] = $level;
			
			$nik = trim($this->session->userdata('id_user'));
			$id_karyawan = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>
				"SELECT id_karyawan FROM karyawan WHERE nik='$nik'"
			), array(CURLOPT_BUFFERSIZE => 10)))->id_karyawan;
			
			if($id_karyawan=="19008") {
				$this->access_boc = true;
			} else {
				$this->access_boc = false;
			}
			
		
			$this->data['access_boc'] = $this->access_boc;
		} else {
            redirect('');
        }
    }
	
    public function index() {    
		$this->data['active_menu'] = "all_runsheet";
		$date = date('Y-m-d');
		// $date = '2020-02-05';
		
		$query = "
			SELECT *, 
				cashtransit.run_number as run_number,
				IFNULL(client.bank, client_cit.nama_client) AS nama_client, 
				IFNULL(client.lokasi, client_cit.alamat) AS lokasi_client,
				IFNULL((
					SELECT id_karyawan
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_1
				), '') AS id_karyawan, 
				IFNULL((
					SELECT nama
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_1
				), '') AS nama_custody, 
				IFNULL((
					SELECT nama
					FROM karyawan
					WHERE karyawan.nik = runsheet_security.security_1
				), '') AS nama_security
				FROM 
					cashtransit_detail
						LEFT JOIN 
							cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
						LEFT JOIN 
							master_branch ON(cashtransit.branch=master_branch.id)
						LEFT JOIN
							client ON(cashtransit_detail.id_bank=client.id)
						LEFT JOIN 
							client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
						LEFT JOIN
							runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
						LEFT JOIN
							runsheet_security ON(runsheet_security.id_cashtransit=cashtransit_detail.id_cashtransit)
						LEFT JOIN
							runsheet_operational ON(runsheet_operational.id_cashtransit=cashtransit_detail.id_cashtransit)
						LEFT JOIN 
							(SELECT police_number, type as type_kendaraan FROM vehicle) AS vehicle   ON(runsheet_security.police_number=vehicle.police_number) 
				WHERE cashtransit.action_date LIKE '%".$date."%'
				GROUP BY cashtransit_detail.id_cashtransit
		";
		$this->data['data_run'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['tess'] = function($val){
			$query = "
				SELECT *, 
					cashtransit_detail.id as ids,
					runsheet_cashprocessing.id as ids2,
					IFNULL(client.bank, client_cit.nama_client) AS nama_client, 
					IFNULL(client.lokasi, client_cit.alamat) AS lokasi_client, 
					IFNULL((
						SELECT nama
						FROM karyawan
						WHERE karyawan.nik = runsheet_operational.custodian_1
					), '') AS nama_custody, 
					IFNULL((
						SELECT nama
						FROM karyawan
						WHERE karyawan.nik = runsheet_security.security_1
					), '') AS nama_security
					FROM 
						cashtransit_detail
							LEFT JOIN 
								cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
							LEFT JOIN 
								master_branch ON(cashtransit.branch=master_branch.id)
							LEFT JOIN
								client ON(cashtransit_detail.id_bank=client.id)
							LEFT JOIN 
								client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
							LEFT JOIN
								runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
							LEFT JOIN
								runsheet_security ON(runsheet_security.id_cashtransit=cashtransit_detail.id_cashtransit)
							LEFT JOIN
								runsheet_operational ON(runsheet_operational.id_cashtransit=cashtransit_detail.id_cashtransit)
							LEFT JOIN 
								(SELECT police_number, type as type_kendaraan FROM vehicle) AS vehicle ON(runsheet_security.police_number=vehicle.police_number) 
					WHERE 
						cashtransit.action_date LIKE '%".$date."%' 
						AND cashtransit_detail.id_cashtransit='".$val."'
			";
			
			$array = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			return $array;
	   };
		
		return view('admin/all_runsheet/index', $this->data);
    }
	
	function select_client() {
		// $id = "289";
		// $search = "";
		// $search = "%".strtolower($search)."%";
		// $id_bank = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT id_bank FROM cashtransit_detail WHERE cashtransit_detail.id='$id'"), array(CURLOPT_BUFFERSIZE => 10)))->id_bank;
		
		// $query = "SELECT * FROM client WHERE client.id!='$id_bank' AND (client.bank LIKE '$search' OR client.lokasi LIKE '$search' OR client.wsid LIKE '$search')";
		
		// echo $query;
		
		
		$id = $this->input->post('id');
		$search = $this->input->post('search');
		$search = "%".strtolower($search)."%";
		
		$id_bank = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT id_bank FROM cashtransit_detail WHERE cashtransit_detail.id='$id'
		"), array(CURLOPT_BUFFERSIZE => 10)))->id_bank;
		$client = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT 
				*, 
				client.denom as denom,
				master_branch.name as nama_branch FROM client 
			LEFT JOIN cashtransit_detail ON(cashtransit_detail.id_bank=client.id) 
			LEFT JOIN master_branch ON(master_branch.id=client.cabang) 
			WHERE cashtransit_detail.id='$id'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$cabang = $client->cabang;
		$bank = $client->bank;
		$sektor = $client->sektor;
		$denom = $client->denom;
		$mesin = $client->type_mesin;
		
		$query = "SELECT * FROM client 
			WHERE 
				client.cabang = '$cabang' AND 
				client.bank = '$bank' AND 
				client.sektor = '$sektor' AND 
				client.denom = '$denom' AND 
				client.type_mesin = '$mesin' AND 
				client.id!='$id_bank' AND 
				(client.bank LIKE '$search' OR client.lokasi LIKE '$search' OR client.wsid LIKE '$search')";
		
		// echo $query;
		// echo "<pre>";
		// print_r($query);
		
		// echo $cabang."<br>";
		// echo $bank."<br>";
		// echo $sektor."<br>";
		// echo $denom."<br>";
		// echo $mesin."<br>";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->wsid.' - '.$row->bank.' - '.$row->lokasi; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function select_client2() {
		$id = $this->input->post('id');
		$search = $this->input->post('search');
		// if($search!="") {
		$search = "%".strtolower($search)."%";
		// }
		
		$id_bank = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT id_bank FROM cashtransit_detail WHERE cashtransit_detail.id='$id'"), array(CURLOPT_BUFFERSIZE => 10)))->id_bank;
		
		$query = "SELECT * FROM client WHERE client.id!='$id_bank' AND (client.bank LIKE '$search' OR client.lokasi LIKE '$search' OR client.wsid LIKE '$search')";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->wsid.' - '.$row->bank.' - '.$row->lokasi; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function update_pengalihan() {
		// echo "AAAAA";
		$id = $this->input->post('id');
		$id_bank = $this->input->post('id_bank');
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT count(*) as count FROM cashtransit_detail_pengalihan WHERE id='$id'
		"), array(CURLOPT_BUFFERSIZE => 10)))->count;
		
		// echo "->".$count;
		
		if($count==0) {
			json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>"
				INSERT INTO cashtransit_detail_pengalihan
				SELECT * FROM cashtransit_detail
				WHERE cashtransit_detail.id='$id'
			"), array(CURLOPT_BUFFERSIZE => 10)));
		} else {
			json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>"
				DELETE FROM cashtransit_detail_pengalihan WHERE id='$id'
			"), array(CURLOPT_BUFFERSIZE => 10)));
			
			json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>"
				INSERT INTO cashtransit_detail_pengalihan
				SELECT * FROM cashtransit_detail
				WHERE cashtransit_detail.id='$id'
			"), array(CURLOPT_BUFFERSIZE => 10)));
		}
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>"
			UPDATE cashtransit_detail SET id_bank='$id_bank' WHERE id='$id'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		
		if(!$result) {
			echo "success";
		} else {
			echo "failed";
		}
	}
	
	
	function save_batal() {
		$id = $this->input->post('id');
		$query = "UPDATE cashtransit_detail SET data_solve='batal' WHERE id='$id'";
		
		// echo $query;
		$result = $this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10));
		if(!$result) {
			echo "success";
		} else {
			echo "failed";
		}
	}
	
	function get_data() {
		$date = $this->input->post('date');
		
		$query = "
			SELECT *, cashtransit.id as id_cashtransit,
				cashtransit.run_number as runs,
				IFNULL(client.bank, client_cit.nama_client) AS nama_client, 
				IFNULL(client.lokasi, client_cit.alamat) AS lokasi_client,
				IFNULL((
					SELECT id_karyawan
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_1
				), '') AS id_karyawan, 
				IFNULL((
					SELECT nama
					FROM karyawan
					WHERE karyawan.nik = runsheet_operational.custodian_1
				), '') AS nama_custody, 
				IFNULL((
					SELECT nama
					FROM karyawan
					WHERE karyawan.nik = runsheet_security.security_1
				), '') AS nama_security
			FROM cashtransit
				LEFT JOIN
					(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date,data_solve, jam_cash_in, cpc_process, updated_date, loading, unloading, req_combi, fraud_indicated FROM cashtransit_detail) 
					AS cashtransit_detail ON(cashtransit_detail.id_cashtransit=cashtransit.id)
				LEFT JOIN 
					master_branch ON(cashtransit.branch=master_branch.id)
				LEFT JOIN
					client ON(cashtransit_detail.id_bank=client.id)
				LEFT JOIN 
					client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
				LEFT JOIN
					runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
				LEFT JOIN
					runsheet_security ON(runsheet_security.id_cashtransit=cashtransit_detail.id_cashtransit)
				LEFT JOIN
					runsheet_operational ON(runsheet_operational.id_cashtransit=cashtransit_detail.id_cashtransit)
				LEFT JOIN 
					(SELECT police_number, type as type_kendaraan FROM vehicle) AS vehicle   ON(runsheet_security.police_number=vehicle.police_number) 
				WHERE cashtransit.action_date LIKE '%".$date."%' 
				GROUP BY cashtransit_detail.id_cashtransit
		";
		
		$tess = function($val){
			$query = "
				SELECT *, 
					cashtransit_detail.total as totalss,
					cashtransit_detail.id as ids,
					runsheet_cashprocessing.id as ids2,
					IFNULL(client.bank, client_cit.nama_client) AS nama_client, 
					IFNULL(client.lokasi, client_cit.alamat) AS lokasi_client, 
					IFNULL((
						SELECT nama
						FROM karyawan
						WHERE karyawan.nik = runsheet_operational.custodian_1
					), '') AS nama_custody, 
					IFNULL((
						SELECT nama
						FROM karyawan
						WHERE karyawan.nik = runsheet_security.security_1
					), '') AS nama_security
					FROM 
						cashtransit_detail
							LEFT JOIN 
								cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id)
							LEFT JOIN 
								master_branch ON(cashtransit.branch=master_branch.id)
							LEFT JOIN
								client ON(cashtransit_detail.id_bank=client.id)
							LEFT JOIN 
								client_cit ON (IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
							LEFT JOIN
								runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
							LEFT JOIN
								runsheet_security ON(runsheet_security.id_cashtransit=cashtransit_detail.id_cashtransit)
							LEFT JOIN
								runsheet_operational ON(runsheet_operational.id_cashtransit=cashtransit_detail.id_cashtransit)
							LEFT JOIN 
								(SELECT police_number, type as type_kendaraan FROM vehicle) AS vehicle ON(runsheet_security.police_number=vehicle.police_number) 
					WHERE 
						cashtransit.action_date LIKE '%".$date."%' 
						AND cashtransit_detail.id_cashtransit='".$val."'
			";
			
			$array = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			return $array;
	   };
		
		$data_run = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		echo '<div>';
		foreach($data_run as $d) { 
			
			$id_karyawan = isset($d->id_karyawan) ? "(".$d->id_karyawan.")" : "";
			echo '<table class="table">';
			echo '	<tr>';
			echo '		<th>ID RUN</th>';
			echo '		<th>RUN NUMBER</th>';
			echo '		<th>NOMOR POLISI</th>';
			echo '		<th>CUSTODY STAFF</th>';
			echo '		<th>GUARD</th>';
			echo '		<th>BANK / CLIENT</th>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td>'.$d->id_cashtransit.'</td>';
			echo '		<td>(H-'.$d->h_min.') RUN NUMBER '.$d->runs.'</td>';
			echo '		<td>'.$d->police_number.'</td>';
			echo '		<td>'.$id_karyawan.' '.$d->nama_custody.'</td>';
			echo '		<td>'.$d->nama_security.'</td>';
			echo '		<td>'.$d->nama_client.'</td>';
			echo '	</tr>';
			echo '</table>';
			
			echo '<div class="view" style="margin-bottom: 20px">';
			echo '	<div class="wrapper" style="margin-top: -20px">';
			echo '		<table class="table" style="width: 100%">';
			echo '			<thead>';
			echo '				<tr>';
			echo '					<th style="text-align: center" class="sticky-col first-col">No</th>';
			echo '					<th style="text-align: center">ID DETAIL</th>';
			echo '					<th style="text-align: center">Layanan</th>';
			echo '					<th style="text-align: center">ID ATM/NO BOC</th>';
			echo '					<th style="text-align: center">Lokasi</th>';
			echo '					<th style="text-align: center">Denom</th>';
			echo '					<th style="text-align: center">Jumlah</th>';
			echo '					<th style="text-align: center">Status</th>';
			echo '					<th style="text-align: center">Action</th>';
			echo '				</tr>';
			echo '			</thead>';
			echo '			<tbody>';
			$no = 0;
			foreach($tess($d->id_cashtransit) as $r) {
				$no++;
				echo '<tr>';
				echo '	<td class="sticky-col first-col">'.$no.'</td>';
				echo '	<td>'.$r->ids.'</td>';
				echo '	<td>'.($r->state=="ro_cit" ? "CASH PICKUP" : "REPLENISH").'</td>';
				echo '	<td style="text-align: center">'.($this->access_boc==true ? ($r->state=="ro_cit" ? '<button type="button" class="yellow" onclick="openEditBOC(\''.$r->ids.'\', \''.$r->no_boc.'\')" style="font-size: 10px">EDIT</button>' : "") : "").' '.($r->wsid=="" ? $r->no_boc : $r->wsid." (".$r->type.")").'</td>';
				echo '	<td>'.$r->lokasi_client.'</td>';
				echo '	<td>'.number_format($r->denom, 0, ',', '.').'</td>';
				echo '	<td>'.number_format($r->totalss, 0, ',', '.').'</td>';
				echo '	<td style="text-align: center">';
					if($r->loading=="1" && $r->data_solve=="" && $r->cpc_process=="") {
						echo "done loading";
					} else if($r->loading=="1" && $r->data_solve!=="" && $r->cpc_process=="") {
						echo "done android";
					} else if($r->loading=="1" && $r->data_solve!=="" && $r->cpc_process!=="") {
						echo "done cpc";
					} else {
						echo "-";
					}
				echo '	</td>';
				echo '	<td style="text-align: center">';
					if($r->loading==1) {
						if(($r->data_solve=="batal" && ($r->cpc_process!=="" OR $r->cpc_process=="")) || ($r->data_solve=="" && $r->cpc_process=="")) {
							if($r->data_solve=="batal") { $disabled = "disabled"; } else { $disabled = ""; }
							echo '<button type="button" class="red" onclick="openBatal(\''.$r->ids.'\')" style="font-size: 10px" '.$disabled.'>BATAL</button>';
						}
						if(($r->data_solve!=="batal" && $r->data_solve=="") && $r->cpc_process=="") {
							if($r->data_solve=="batal") { $disabled = "disabled"; } else { $disabled = ""; }
							echo '<button type="button" class="yellow" onclick="openPengalihan(\''.$r->ids.'\')" style="font-size: 10px" '.$disabled.'>PENGALIHAN</button>';
						}
						if($r->data_solve!=="" && $r->data_solve!=="batal" && $r->cpc_process=="") {
							if($r->data_solve=="batal") { $disabled = "hidden"; } else { $disabled = ""; }
							echo '<button type="button" class="yellow" style="font-size: 10px" '.$disabled.'>DONE ANDROID</button>';
						}
						if($r->data_solve!=="" && $r->data_solve!=="batal" && $r->cpc_process!=="") {
							if($r->data_solve=="batal") { $disabled = "hidden"; } else { $disabled = ""; }
							echo '<button type="button" class="green" style="font-size: 10px" '.$disabled.'>DONE</button>';
						}
					}
				echo '	</td>';
				echo '</tr>';
			}
			echo '			</tbody>';
			echo '		</table>';
			echo '	</div>';
			echo '</div>';
		}
		echo '</div>';
		// $this->data['data_run'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		
	}
	
	
	public function edit_boc() {
		$this->data['active_menu'] = "all_runsheet";
		$this->data['url'] = "all_runsheet/save";
		$this->data['flag'] = "edit";
		
		$id = $this->uri->segment(3);
		
		$id_karyawan = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>
			"SELECT id_karyawan FROM karyawan WHERE nik='$nik'"
		), array(CURLOPT_BUFFERSIZE => 10)))->id_karyawan;
		
		$this->data['id_karyawan'] = $id_karyawan;
		$this->data['id'] = $id;
		
		return view('admin/all_runsheet/form', $this->data);
	}
	
	public function save_edit_boc() {
		$id = $this->input->post('id');
		$no_boc = trim($this->input->post('no_boc'));
		$query = "UPDATE cashtransit_detail SET no_boc='$no_boc' WHERE id='$id'";
		
		// echo $query;
		$result = $this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10));
		if(!$result) {
			echo "success";
		} else {
			echo "failed";
		}
	}
	
	
	
    public function stock() {    
		$this->data['active_menu'] = "all_runsheet_stock";
		
		$query = "SELECT * FROM all_runsheet";
		$this->data['data_prepared'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$query = "SELECT count(*) as cnt FROM all_runsheet WHERE status='used'";
		$this->data['jumlah_used'] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		$query = "SELECT count(*) as cnt FROM all_runsheet WHERE status='ready'";
		$this->data['jumlah_ready'] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		$query = "SELECT count(*) as cnt FROM all_runsheet WHERE status!='used' AND status!='ready'";
		$this->data['jumlah_lain'] = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		return view('admin/all_runsheet/index_stock', $this->data);
	}
	
	public function server_processing() {
		$param['table'] = 'all_runsheet';
		$param['column_order'] = array('bank', 'seal'); //field yang ada di table user
		$param['column_search'] = array('bank', 'seal'); //field yang diizin untuk pencarian 
		$param['order'] = array('tanggal' => 'desc');
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		echo $this->curl->simple_get(rest_api().'/select/datatables2', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
    }
	
    public function detail($id) {    
		$this->data['active_menu'] = "cpc_record";
		
		$query = "SELECT * FROM cpc_repared_detail WHERE id_detail='$id'";
		$this->data['data_prepared'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$this->data['id_detail'] = $id;
		
		return view('admin/all_runsheet/index_detail', $this->data);
    }
	
	public function check_seal() {
		// print_r($_REQUEST);
		$kode = $this->input->post('value');
		
		$table = "
			SELECT *
			FROM cashtransit_detail 
			LEFT JOIN runsheet_cashprocessing ON(runsheet_cashprocessing.id=cashtransit_detail.id)
		";
		
		$num1 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT C.cart_1_seal, C.cart_2_seal, C.cart_3_seal, C.cart_4_seal, C.cart_5_seal, C.divert, count(*) as cnt 
			FROM cashtransit_detail AS A LEFT JOIN runsheet_cashprocessing AS C ON(C.id=A.id) 
			WHERE
				LOWER(C.cart_1_seal) = '".$kode."' OR
				LOWER(C.cart_2_seal) = '".$kode."' OR
				LOWER(C.cart_3_seal) = '".$kode."' OR
				LOWER(C.cart_4_seal) = '".$kode."' OR
				LOWER(C.cart_5_seal) = '".$kode."' OR
				LOWER(C.divert) = '".$kode."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$num2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT count(*) as cnt FROM all_runsheet WHERE seal='".$kode."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$num3 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"SELECT count(*) as cnt FROM master_seal WHERE LOWER(kode) = BINARY(kode) AND kode='$kode' AND status='used'"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$debug = "RESULT FROM cashprocessing : ".$num1->cnt;
		$debug .= "\nRESULT FROM all_runsheet : ".$num2->cnt;
		$debug .= "\nRESULT FROM master_seal : ".$num3->cnt;
		$debug .= "\nRESULT SUM : ".($num1->cnt+$num2->cnt+$num3->cnt);
		
		$data['debug'] = $debug;
		$data['result'] = ($num1->cnt+$num2->cnt+$num3->cnt);
		
		echo json_encode($data);
	}
	
	public function select_bank() {
		$search = $this->input->post('search');
		
		$query = "SELECT * FROM client WHERE bank LIKE '%$search%' GROUP BY bank";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->bank;
				$list[$key]['text'] = $row->bank; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function select_mesin() {
		$search = $this->input->post('search');
		$bank = $this->input->post('bank');
		
		$query = "SELECT * FROM client WHERE bank='$bank' AND wsid LIKE '%$search%'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->wsid;
				$list[$key]['text'] = $row->wsid; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function get_data_kasir() {
		$query = "SELECT * FROM karyawan LEFT JOIN jabatan ON(karyawan.id_jabatan=jabatan.id_jabatan) WHERE jabatan.nama_jabatan='CASHIER'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->nama;
				$list[$key]['text'] = $row->nama; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	public function save_data() {
		
		$data = array();
		$data['tanggal'] = date("Y-m-d");
		$data['bank'] = $_REQUEST['bank'];
		$data['denom'] = $_REQUEST['denom'];
		$data['value'] = str_replace(",", "", $_REQUEST['value']);
		$data['seal'] = $_REQUEST['seal'];
		$data['date_time'] = date("Y-m-d H:i:s");
		$data['type_cassette'] = $_REQUEST['type_cassette'];
		$data['type'] = $_REQUEST['type'];
		$data['no_table'] = $_REQUEST['table'];
		$data['nama'] = $_REQUEST['cashier'];
		$data['status'] = "ready";
		
		$num = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT count(*) as cnt, id FROM all_runsheet WHERE tanggal='".$data['tanggal']."' AND bank='".$data['bank']."' AND seal='".$data['seal']."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		
		if($num->cnt==0) {
			$table = "all_runsheet";
			$result = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			
			if($result) {
				echo "success";
			} else {
				echo "failed";
			}
		} else {
			$table = "all_runsheet";
			$data['id'] = $num->id;
			$result = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			if($result) {
				echo "success";
			} else {
				echo "failed";
			}
		}
	}
	
	public function save_data_audit() {
		$data = array();
		$seal = $_REQUEST['seal'];
		$status = $_REQUEST['status'];
		$remark = $_REQUEST['remark'];
		
		$query = "UPDATE all_runsheet SET status='$status', remark='$remark' WHERE seal='$seal'";
		$result = $this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10));
		if(!$result) {
			echo "success";
		} else {
			echo "failed";
		}
	}
	
	public function save_data_detail() {
		$data = array();
		$data['id_detail'] = $_REQUEST['id_detail'];
		$data['seal'] = $_REQUEST['seal'];
		$data['value'] = str_replace(",", "", $_REQUEST['value']);
		$data['type_cassette'] = $_REQUEST['type'];
		
		$num = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>"
			SELECT count(*) as cnt, id FROM cpc_repared_detail WHERE id_detail='".$data['id_detail']."' AND seal='".$data['seal']."'
		"), array(CURLOPT_BUFFERSIZE => 10)));
		
		if($num->cnt==0) {
			$table = "cpc_repared_detail";
			$result = $this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			
			if($result) {
				echo "success";
			} else {
				echo "failed";
			}
		} else {
			$table = "cpc_repared_detail";
			$data['id'] = $num->id;
			$result = $this->curl->simple_get(rest_api().'/select/update', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			if($result) {
				echo "success";
			} else {
				echo "failed";
			}
		}
	}
	
	public function delete() {
		$data['id'] = $_POST['id'];
		
		$table = "all_runsheet";
		$delete = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
	
	public function delete_detail() {
		$data['id'] = $_POST['id'];
		
		$table = "cpc_repared_detail";
		$delete = $this->curl->simple_get(rest_api().'/select/delete', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		if (!$delete) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
	}
}