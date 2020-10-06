<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tes extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->library('curl');
	}
	
	public function index() {
		$tgl = "2020-09-09";
		$sql = "
			SELECT C.wsid, B.jam_cash_in, B.id_cashtransit, A.* FROM jurnal A 
			LEFT JOIN cashtransit_detail B ON B.id=A.id_detail
			LEFT JOIN client C ON C.id=B.id_bank
			WHERE 
			CASE 
				WHEN B.no_boc = '' AND A.keterangan != 'pengisian_awal' AND DATE(B.jam_cash_in) != '0000-00-00' THEN
					DATE(B.jam_cash_in) = '$tgl' 
				WHEN B.no_boc = '' AND A.keterangan != 'pengisian_awal' AND DATE(B.jam_cash_in) = '0000-00-00' THEN
					DATE(A.tanggal) = '$tgl'
				WHEN B.no_boc = '' AND A.keterangan = 'pengisian_awal' THEN
					DATE(B.jam_cash_in) = '0000-00-00' AND DATE(A.tanggal) = '$tgl'
				ELSE
					DATE(B.jam_cash_in) = '0000-00-00' AND DATE(A.tanggal) = '$tgl'
				END
			AND
			NOT EXISTS (
				SELECT * FROM jurnal_sync_new 
				WHERE ids = jurnal.id
			)
			#GROUP BY C.wsid
			ORDER BY A.id ASC
		";
		$sql = "
			SELECT count(*) as cnt FROM jurnal A WHERE 
			NOT EXISTS (
				SELECT * FROM jurnal_new 
				WHERE jurnal_new.ids = A.id
			) AND
			DATE(A.tanggal) = '$tgl' ORDER BY A.id ASC
		";
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		$halaman = 1;
		$page = isset($_REQUEST['halaman'])? (int)$_REQUEST["halaman"]: 1;
		$mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
		$total = $count;
		$pages = ceil($total/$halaman); 
		
		for ($i=1; $i<=$pages ; $i++) {
			if($i==1) {
				echo '<a href="?halaman='.$i.'"><button style="padding: 20px">'.($pages-($i-1)).'</button></a>';
			}
		}
		
		$sql = "
			SELECT A.* FROM jurnal A 
			LEFT JOIN cashtransit_detail B ON B.id=A.id_detail
			LEFT JOIN client C ON C.id=B.id_bank
			WHERE 
			NOT EXISTS (
				SELECT * FROM jurnal_new 
				WHERE jurnal_new.id_detail = A.id_detail
			) AND
			DATE(A.tanggal) = '$tgl'
			ORDER BY A.id ASC LIMIT $mulai, $halaman
		";
		
		$sql = "
			SELECT C.wsid, A.* FROM jurnal A 
			LEFT JOIN cashtransit_detail B ON B.id=A.id_detail
			LEFT JOIN client C ON C.id=B.id_bank
			WHERE 
			NOT EXISTS (
				SELECT * FROM jurnal_new 
				WHERE jurnal_new.ids = A.id
			) AND
			DATE(A.tanggal) = '$tgl' ORDER BY A.id ASC LIMIT $mulai, $halaman
		";
		
		// echo $sql;
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
	
		// echo "<pre>";
		// print_r($sql);
		
		$debit_100 = 0;
		$debit_50 = 0;
		$debit_20 = 0;
		
		$kredit_100 = 0;
		$kredit_50 = 0;
		$kredit_20 = 0;
		
		$no = 0;
		
		if($_REQUEST["halaman"]) {
			foreach($result as $r) {
				// $debit_100 = $debit_100 + $r->debit_100;
				// $debit_50 = $debit_50 + $r->debit_50;
				// $debit_20 = $debit_20 + $r->debit_20;
				
				// $kredit_100 = $kredit_100 + $r->kredit_100;
				// $kredit_50 = $kredit_50 + $r->kredit_50;
				// $kredit_20 = $kredit_20 + $r->kredit_20;
				
				// $no++;
				$sql = "INSERT INTO `jurnal_new`
						(
						`ids`, 
						`id_detail`, 
						`tanggal`, 
						`catatan`, 
						`keterangan`, 
						`posisi`, 
						`debit_100`, 
						`debit_50`, 
						`debit_20`, 
						`kredit_100`, 
						`kredit_50`, 
						`kredit_20`
						) 
						VALUES (
						'$r->id',
						'$r->id_detail',
						'$r->tanggal',
						'$r->catatan',
						'$r->keterangan',
						'$r->posisi',
						'$r->debit_100',
						'$r->debit_50',
						'$r->debit_20',
						'$r->kredit_100',
						'$r->kredit_50',
						'$r->kredit_20'
						)";
				
				if($r->posisi!=="kredit") {
					$sqlZ = "
						SELECT C.wsid, A.* FROM jurnal A 
						LEFT JOIN cashtransit_detail B ON B.id=A.id_detail
						LEFT JOIN client C ON C.id=B.id_bank
						WHERE 
						C.wsid='$r->wsid' 
						AND A.keterangan='replenishment' 
						AND A.id_detail='$r->id_detail' 
						ORDER BY A.id DESC
					";
					
					$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sqlZ), array(CURLOPT_BUFFERSIZE => 10)));
					
					echo "<pre>";
					echo $r->tanggal." ".$res->tanggal;
					print_r($sql);
					print_r($res);
					
					if($r->tanggal==$res->tanggal) {
						json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
						echo "<script>window.location.href='".base_url()."tes'</script>";
					} else {
						$sql = "INSERT INTO `jurnal_new`
						(
						`ids`, 
						`id_detail`, 
						`tanggal`, 
						`catatan`, 
						`keterangan`, 
						`posisi`, 
						`debit_100`, 
						`debit_50`, 
						`debit_20`, 
						`kredit_100`, 
						`kredit_50`, 
						`kredit_20`
						) 
						VALUES (
						'$r->id',
						'$r->id_detail',
						'$res->tanggal',
						'$r->catatan',
						'$r->keterangan',
						'$r->posisi',
						'$r->debit_100',
						'$r->debit_50',
						'$r->debit_20',
						'$r->kredit_100',
						'$r->kredit_50',
						'$r->kredit_20'
						)";
						
						json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
						echo "<script>window.location.href='".base_url()."tes'</script>";
					}
				} else {					
					json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
					echo "<script>window.location.href='".base_url()."tes'</script>";
				}
			}
			
		}
		
		echo "<style>table {   border-collapse: collapse; }  table, th, td {   border: 1px solid black; } th, td {   padding: 5px;   text-align: center; }</style>";
		echo "<table style='text-align: center; border: 1px solid black'>";
		echo "<tr>";
		echo "<th>NO</th>";
		echo "<th>ID</th>";
		echo "<th>WSID</th>";
		echo "<th>KETERANGAN</th>";
		echo "<th>POSISI</th>";
		echo "<th>DEBIT 100</th>";
		echo "<th>DEBIT 50</th>";
		echo "<th>DEBIT 20</th>";
		echo "<th>KREDIT 100</th>";
		echo "<th>KREDIT 50</th>";
		echo "<th>KREDIT 20</th>";
		echo "</tr>";
		foreach($result as $k => $r) {
			echo "<tr>";
			echo "<td>".($k+1)."</td>";
			echo "<td>".$r->id."</td>";
			echo "<td>".$r->wsid."</td>";
			echo "<td>".$r->keterangan."</td>";
			echo "<td>".$r->posisi."</td>";
			echo "<td>".$this->uang($r->debit_100/1000)."</td>";
			echo "<td>".$this->uang($r->debit_50/1000)."</td>";
			echo "<td>".$this->uang($r->debit_20/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_100/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_50/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_20/1000)."</td>";
			echo "</tr>";
		}
		echo "</table>";
		
		echo "<br>debit_100 : ".$debit_100/1000;
		echo "<br>debit_50 : ".$debit_50/1000;
		echo "<br>debit_20 : ".$debit_20/1000;
		
		echo "<br>";
		echo "<br>kredit_100 : ".$kredit_100/1000;
		echo "<br>kredit_50 : ".$kredit_50/1000;
		echo "<br>kredit_20 : ".$kredit_20/1000;
		
		$total_keluar = intval($kredit_100)+intval($kredit_50)+intval($kredit_20);
		echo "<br>";
		echo "<br>".$this->uang($total_keluar/1000);
	}
	
	public function a() {
		$tgl = "2020-09-09";
		$query = "SELECT 
			count(*) as cnt
		FROM jurnal_new
		LEFT JOIN (SELECT id, id_bank FROM cashtransit_detail) AS cashtransit_detail ON(cashtransit_detail.id=jurnal_new.id_detail)
		LEFT JOIN (SELECT id, wsid FROM client) AS client ON(client.id=cashtransit_detail.id_bank)
		WHERE 
			jurnal_new.tanggal LIKE '%".$tgl."%' AND 
			NOT EXISTS (
				SELECT * FROM jurnal_sync_new
				WHERE ids = jurnal_new.id
			)
		ORDER BY jurnal_new.tanggal, jurnal_new.id_detail, client.wsid ASC";
		
		$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
		
		$halaman = 1;
		$page = (int)$_REQUEST["halaman"];
		$mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
		$total = $count;
		$pages = ceil($total/$halaman); 
		for ($i=1; $i<=$pages ; $i++) {
			if($i==1) {
				echo '<a href="?halaman='.$i.'"><button style="padding: 20px">'.($pages-($i-1)).'</button></a>';
			}
		}
		
		$sql = "
			SELECT 
			*, cashtransit_detail.id_cashtransit as id_ct, jurnal_new.id as ids, jurnal_new.keterangan as keterangan_jurnal
			FROM jurnal_new
			LEFT JOIN (SELECT id, id_cashtransit, id_bank FROM cashtransit_detail) AS cashtransit_detail ON(cashtransit_detail.id=jurnal_new.id_detail)
			LEFT JOIN (SELECT id, wsid FROM client) AS client ON(client.id=cashtransit_detail.id_bank)
			WHERE 
				jurnal_new.tanggal LIKE '%".$tgl."%' AND 
				NOT EXISTS (
					SELECT * FROM jurnal_sync_new
					WHERE ids = jurnal_new.id
				)
			ORDER BY jurnal_new.tanggal, jurnal_new.id_detail, client.wsid ASC LIMIT $mulai, $halaman
		";
		
		// echo $sql;
		
		$data_record = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));	
		
		echo "<style>table {   border-collapse: collapse; }  table, th, td {   border: 1px solid black; } th, td {   padding: 5px;   text-align: center; }</style>";
		echo "<table style='text-align: center; border: 1px solid black'>";
		echo "<tr>";
		echo "<th>NO</th>";
		echo "<th>ID</th>";
		echo "<th>RUN</th>";
		echo "<th>WSID</th>";
		echo "<th>KETERANGAN</th>";
		echo "<th>POSISI</th>";
		echo "<th>DEBIT 100</th>";
		echo "<th>DEBIT 50</th>";
		echo "<th>DEBIT 20</th>";
		echo "<th>KREDIT 100</th>";
		echo "<th>KREDIT 50</th>";
		echo "<th>KREDIT 20</th>";
		echo "</tr>";
		foreach($data_record as $k => $r) {
			echo "<tr>";
			echo "<td>".($k+1)."</td>";
			echo "<td>".$r->id."</td>";
			echo "<td>".$r->id_ct."</td>";
			echo "<td>".$r->wsid."</td>";
			echo "<td>".$r->keterangan."</td>";
			echo "<td>".$r->posisi."</td>";
			echo "<td>".$this->uang($r->debit_100/1000)."</td>";
			echo "<td>".$this->uang($r->debit_50/1000)."</td>";
			echo "<td>".$this->uang($r->debit_20/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_100/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_50/1000)."</td>";
			echo "<td>".$this->uang($r->kredit_20/1000)."</td>";
			echo "</tr>";
		}
		echo "</table>";
		
		$no = 0;
		$prev_debit_100 = 0;
		$prev_kredit_100 = 0;
		$prev_debit_50 = 0;
		$prev_kredit_50 = 0;
		$prev_debit_20 = 0;
		$prev_kredit_20 = 0;
		$prev_saldo_100 = $this->prev_jurnal("saldo_100");
		$prev_saldo_50 = $this->prev_jurnal("saldo_50");
		$prev_saldo_20 = $this->prev_jurnal("saldo_20");
		$prev_saldo = 0;
		$saldo_100 = 0;
		$saldo_50 = 0;
		$saldo_20 = 0;
		$saldo = 0;
		$prev_ket = "";
		
		if($_REQUEST["halaman"]) {
			foreach($data_record as $r) {
				if($r->kredit_100==0) {
					$saldo_100 = $prev_saldo_100 + $r->debit_100;
				} else {
					$saldo_100 = $prev_saldo_100 - $r->kredit_100;
				}
				
				if($r->kredit_50==0) {
					$saldo_50 = $prev_saldo_50  + $r->debit_50;
				} else {
					$saldo_50 = $prev_saldo_50  - $r->kredit_50;
				}
				
				if($r->kredit_20==0) {
					$saldo_20 = $prev_saldo_20 + $r->debit_20;
				} else {
					$saldo_20 = $prev_saldo_20 - $r->kredit_20;
				}
				
				$saldo = $saldo_100 + $saldo_50 + $saldo_20;
				
				
				$data_save = array();
				$data_save['ids']				= $r->ids;
				$data_save['id_detail']			= $r->id_detail;
				$data_save['wsid']				= ($r->wsid==null ? "" : $r->wsid);
				$data_save['tanggal']			= $r->tanggal;
				$data_save['catatan']			= $r->catatan;
				$data_save['keterangan']		= $r->keterangan;
				$data_save['posisi']			= $r->posisi;
				$data_save['debit_100']			= $r->debit_100;
				$data_save['debit_50']			= $r->debit_50;
				$data_save['debit_20']			= $r->debit_20;
				$data_save['kredit_100']		= $r->kredit_100;
				$data_save['kredit_50']			= $r->kredit_50;
				$data_save['kredit_20']			= $r->kredit_20;
				$data_save['saldo_100']			= ($saldo_100==0 ? 0 : $saldo_100);
				$data_save['saldo_50']			= ($saldo_50==0 ? 0 : $saldo_50);
				$data_save['saldo_20']			= ($saldo_20==0 ? 0 : $saldo_20);
				$data_save['sum_saldo']			= ($saldo==0 ? 0 : $saldo);
				
				$query = "SELECT count(*) as cnt FROM jurnal_sync_new WHERE ids='".$r->ids."'";
				$count = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)))->cnt;
				
				if($count==0) {
					$this->curl->simple_get(rest_api().'/select/insert', array('table'=>'jurnal_sync_new', 'data'=>$data_save), array(CURLOPT_BUFFERSIZE => 10));
				}
				
				$prev_debit_100 = $r->debit_100;
				$prev_kredit_100 = $r->kredit_100;
				$prev_debit_50= $r->debit_50;
				$prev_kredit_50 = $r->kredit_50;
				$prev_debit_20= $r->debit_20;
				$prev_kredit_20 = $r->kredit_20;
				
				$prev_saldo_100 = $saldo_100;
				$prev_saldo_50 = $saldo_50;
				$prev_saldo_20 = $saldo_20;
				$prev_ket = $r->keterangan;
				
				$no++;
			}
			
			echo "<script>window.location.href='".base_url()."tes/a'</script>";
		}
	}
	
	public function prev_jurnal($field) {
		$query = "SELECT id, saldo_100, saldo_50, saldo_20 FROM jurnal_sync_new ORDER BY id DESC LIMIT 0,1 ";
		$data_record = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));	
		
		$data_record = json_decode(json_encode($data_record), true);
		
		return (int) $data_record[$field];
	}
	
	function uang($u) {
		return number_format($u, 0, ',', '.');
	}
	
	
	function check_seal() {
		// $query = "SELECT id, saldo_100, saldo_50, saldo_20 FROM jurnal_sync_new ORDER BY id DESC LIMIT 0,1 ";
		$seal = '56056, 54182, 56002, 50027, 53608, 56381, 55431, 57002, 52714';
		$seal = 'a'.implode(', a', explode(', ', $seal));
		$seal = explode(', ', $seal);
		
		$i = 0;
		foreach($seal as $s) {
			$i++;
			// echo $s."<br>";
			$query = "
				SELECT 
					c.wsid AS WSID, 
					c.type AS TYPE, 
					c.lokasi AS LOKASI, 
					SUBSTRING(a.cart_1_seal, 1, 6) AS CARTRIDGE_1,
					SUBSTRING(a.cart_2_seal, 1, 6) AS CARTRIDGE_2,
					SUBSTRING(a.cart_3_seal, 1, 6) AS CARTRIDGE_3,
					SUBSTRING(a.cart_4_seal, 1, 6) AS CARTRIDGE_4,
					SUBSTRING(a.cart_5_seal, 1, 6) AS CARTRIDGE_5,
					a.bag_seal AS BIG_SEAL,
					(case when (a.bag_seal_return='') 
						THEN
						   case when (b.data_solve='batal')
							THEN
								b.data_solve
							ELSE
								case when (b.cpc_process='pengisian')
									THEN
										b.cpc_process
									ELSE
										'TIDAK TERECORD'
									END
							END
						ELSE
						   a.bag_seal_return
					END) AS BIG_SEAL_RETURN
				FROM runsheet_cashprocessing AS a 
				LEFT JOIN cashtransit_detail AS b ON(a.id=b.id)
				LEFT JOIN client AS c ON(b.id_bank=c.id)
				WHERE b.state='ro_atm' AND b.unloading='1'
				AND (
					SUBSTRING(a.cart_1_seal, 1, 6)='$s' OR 
					SUBSTRING(a.cart_2_seal, 1, 6)='$s' OR 
					SUBSTRING(a.cart_3_seal, 1, 6)='$s' OR 
					SUBSTRING(a.cart_4_seal, 1, 6)='$s' OR 
					SUBSTRING(a.cart_5_seal, 1, 6)='$s'
				)
				GROUP BY a.bag_seal
				ORDER BY b.id ASC;
			";
			
			$data_record = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			// print_r($data_record);
			
			if(count($data_record)>0) {
				echo $i.". ".$s." => ADA<br>";
			} else {
				echo $i.". ".$s." => TIDAK ADA<br>";
			}
			
			// echo $query."<br><br><br>";
		}
		
	}
	
	function b() {
		$seal = 'B54039 B54200 B54035 B54986 B54719 B54712 B54780 B54839';
		$seal = explode(' ', $seal);
		
		$i = 0;
		foreach($seal as $s) {
			$i++;
			// echo $s."<br>";
			$query = "
				SELECT 
					a.id, 
					c.wsid AS WSID, 
					c.type AS TYPE, 
					c.lokasi AS LOKASI, 
					b.data_solve,
					b.cpc_process,					
					SUBSTRING(a.cart_1_seal, 1, 6) AS CARTRIDGE_1,
					SUBSTRING(a.cart_2_seal, 1, 6) AS CARTRIDGE_2,
					SUBSTRING(a.cart_3_seal, 1, 6) AS CARTRIDGE_3,
					SUBSTRING(a.cart_4_seal, 1, 6) AS CARTRIDGE_4,
					SUBSTRING(a.cart_5_seal, 1, 6) AS CARTRIDGE_5,
					a.bag_seal AS BIG_SEAL,
					(case when (a.bag_seal_return='') 
						THEN
						   case when (b.data_solve='batal')
							THEN
								b.data_solve
							ELSE
								case when (b.cpc_process='pengisian')
									THEN
										b.cpc_process
									ELSE
										'TIDAK TERECORD'
									END
							END
						ELSE
						   a.bag_seal_return
					END) AS BIG_SEAL_RETURN
				FROM runsheet_cashprocessing AS a 
				LEFT JOIN cashtransit_detail AS b ON(a.id=b.id)
				LEFT JOIN client AS c ON(b.id_bank=c.id)
				WHERE b.state='ro_atm'
				AND (
					a.bag_seal='$s' OR 
					a.bag_seal_return='$s' 
				)
				GROUP BY a.bag_seal
				ORDER BY b.id ASC;
			";
			
			$data_record = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
			
			echo "<pre>";
			// print_r($data_record);
			
			if(count($data_record)>0) {
				// echo $i.". ".$s." ".$data_record->cpc_process." => ADA<br>";
				echo $i.". ".$s." => ADA<br>";
				$query = "UPDATE runsheet_cashprocessing SET bag_seal_return='' WHERE bag_seal_return='$s' AND id='$data_record->id'";
				$result = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
				
				if(!$result) {
					$q = "SELECT * FROM master_seal WHERE kode='$s' AND jenis='big'";
					$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$q), array(CURLOPT_BUFFERSIZE => 10)));
					
					print_r($res);
					if($res->status=='used') {
						$qq = "UPDATE master_seal SET status='available' WHERE id='$res->id'";
						json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$qq), array(CURLOPT_BUFFERSIZE => 10)));
					}
				}
			} else {
				echo $i.". ".$s." => TIDAK ADA<br>";
				
				$q = "SELECT * FROM master_seal WHERE kode='$s' AND jenis='big'";
				$res = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$q), array(CURLOPT_BUFFERSIZE => 10)));
				print_r($res);	
			}
			
			// echo $query."<br><br><br>";
		}
	}
	
	
	function c() {
		$id = '3511';
		$wsid = '6476';
		$q_previous = "
			SELECT b.cart_1_seal, b.cart_2_seal, b.cart_3_seal, b.cart_4_seal, b.cart_5_seal, b.divert FROM cashtransit_detail AS a 
			LEFT JOIN runsheet_cashprocessing AS b ON(a.id=b.id)
			LEFT JOIN client AS c ON(a.id_bank=c.id)
			WHERE c.wsid='$wsid'
			AND a.id = (
				SELECT MAX(id) FROM cashtransit_detail WHERE id < $id AND id_bank=c.id AND data_solve!='batal'
			)
		";
		
		$res_previous = json_decode(
			$this->curl->simple_get(
				rest_api().'/select/query', 
				array('query'=>$q_previous), 
				array(CURLOPT_BUFFERSIZE => 10)
			)
		);
		
		$q_current = "
			SELECT a.data_solve, b.bag_seal_return, b.bag_no FROM cashtransit_detail AS a 
			LEFT JOIN runsheet_cashprocessing AS b ON(a.id=b.id)
			LEFT JOIN client AS c ON(a.id_bank=c.id)
			WHERE c.wsid='$wsid'
			AND a.id = '$id'
		";
		
		$res_current = json_decode(
			$this->curl->simple_get(
				rest_api().'/select/query', 
				array('query'=>$q_current), 
				array(CURLOPT_BUFFERSIZE => 10)
			)
		);
		
		$data_solve = json_decode($res_current->data_solve, true);
		$data_solve['cart_1_seal'] = $res_previous->cart_1_seal;
		$data_solve['cart_2_seal'] = $res_previous->cart_2_seal;
		$data_solve['cart_3_seal'] = $res_previous->cart_3_seal;
		$data_solve['cart_4_seal'] = $res_previous->cart_4_seal;
		$data_solve['cart_5_seal'] = $res_previous->cart_5_seal;
		$data_solve['div_seal'] = $res_previous->divert;
		$data_solve['bag_seal'] = $res_current->bag_seal_return;
		$data_solve['bag_no'] = $res_current->bag_no;
		// print_r();
		$solve = json_encode($data_solve);
		
		$query_update = "UPDATE cashtransit_detail SET data_solve='$solve' WHERE id='$id'";
		
		$res = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$query_update), array(CURLOPT_BUFFERSIZE => 10)));
		
		if(!$res) {
			echo "SUCCESS";
		} else {
			echo "fail";
		}
	}
}