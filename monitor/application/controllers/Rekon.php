<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Rekon extends CI_Controller {
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
		
		// $this->data['table'] = $this->show_table($this->get_data4());
		
		// return view('admin/rekon_atm/index', $this->data);
		$this->get_data4();
    }
	
	public function review() {
		$id = $_REQUEST['id'];
		// $jam_cash_in = date("Y-d-m H:i:s", strtotime($_REQUEST['jam_cash_in']));
		$jam_cash_in = $_REQUEST['jam_cash_in'];
		if(!empty($id) AND !empty($jam_cash_in)) {
			$sqlz = "UPDATE `cashtransit_detail` SET `jam_cash_in`='$jam_cash_in' WHERE `id`='$id'";
			echo $sqlz;
			$res = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sqlz), array(CURLOPT_BUFFERSIZE => 10)));
			
			unset($_REQUEST['jam_cash_in']);
			unset($_REQUEST['id']);
		}
		
		
		$sql = "
			SELECT 
				A.id, 
				D.`type`,
				A.date AS full_date,
				EXTRACT(DAY FROM A.date) AS day,
				EXTRACT(MONTH FROM A.date) AS month,
				EXTRACT(YEAR FROM A.date) AS year,
				DATE(A.date) AS date,
				TIME(A.date) AS time,
				A.jam_cash_in, 
				A.updated_date, 
				A.id_cashtransit, 
				A.id_bank, 
				A.ctr, 
				A.state, 
				A.data_solve, 
				A.cpc_process
			FROM cashtransit_detail A
			LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
			LEFT JOIN master_branch C ON (C.id=B.branch) 
			LEFT JOIN client D ON(D.id=A.id_bank) 
			LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
			WHERE A.data_solve!='batal' 
			AND A.state='ro_atm' 
			AND A.data_solve!='' 
			AND D.type!='CDM' 
			AND A.jam_cash_in LIKE '0000-00-00%'
			AND A.date >= '2020-07-01%'
			GROUP BY DATE(A.date)
		";
		
			// #AND A.jam_cash_in = '0000-00-00%'
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		$datea = $_REQUEST['datea'];
		?>
			<!DOCTYPE html>
			<html>

			<head>
				<title>
					How to zoom-in and zoom-out
					image using JavaScript ?
				</title>
				<style>
					table {
						border-collapse: collapse;
						margin-left: auto;
						margin-right: auto;
					}
					
					table, th, td {
						border: 1px solid black;
						text-align: center;
					}
				</style>

				<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
				<script src="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
				<link href="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet"/>

			</head>

			<body style="text-align:center;">

				<h1 style="color:green;">
					GeeksForGeeks
				</h1>

				<h3>
					JavaScript | Increase and
					Decrease image size
				</h3>
				<hr>

				<div class="box">
					<img src="https://media.geeksforgeeks.org/wp-content/uploads/20190912174307/qwe1.png" id="geeksZZ" GFG="250" alt="Geeksforgeeks">
				</div>
				<hr>

				

				<script type="text/javascript">
					function zoomin(id) {
						var GFG = document.getElementById(id);
						var currHeight = GFG.clientHeight;
						var currWidth = GFG.clientWidth;
						GFG.style.height = (currHeight + 320) + "px";
						GFG.style.width = (currWidth + 320) + "px";
					}

					function zoomout(id) {
						var GFG = document.getElementById(id);
						var currHeight = GFG.clientHeight;
						var currWidth = GFG.clientWidth;
						GFG.style.height = (currHeight - 320) + "px";
						GFG.style.width = (currWidth - 320) + "px";
					}
				</script>
			</body>

			</html>
			<form>
				<input type="text" name="datea" value="<?=$datea?>">
				<input type="submit" value="Submit">
			</form>
		<?php
		
		
		if(!empty($datea)) {
			$sql = "
				SELECT 
					A.id, 
					B.id as cash_id, 
					B.h_min, 
					B.run_number, 
					D.`wsid`,
					D.`type`,
					E.`updated_date_cpc`,
					A.date AS full_date,
					EXTRACT(DAY FROM A.date) AS day,
					EXTRACT(MONTH FROM A.date) AS month,
					EXTRACT(YEAR FROM A.date) AS year,
					DATE(A.date) AS date,
					TIME(A.date) AS time,
					A.jam_cash_in, 
					A.updated_date, 
					A.id_cashtransit, 
					A.id_bank, 
					A.ctr, 
					A.state, 
					A.data_solve, 
					A.cpc_process, 
					A.receipt_1, 
					A.receipt_2, 
					A.receipt_3, 
					A.receipt_4
				FROM cashtransit_detail A
				LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
				LEFT JOIN master_branch C ON (C.id=B.branch) 
				LEFT JOIN client D ON(D.id=A.id_bank) 
				LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
				WHERE 
				A.state='ro_atm' 
				AND A.data_solve!='' 
				AND D.type!='CDM' 
				AND D.wsid LIKE '$datea%'
				ORDER BY A.id DESC
			";
			
				// #AND A.jam_cash_in = '0000-00-00%'
				
			$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
			echo "<table border>";
			echo '<tr>';
				echo '<td colspan=5><span style="font-size: 28px; font-weight: bold">TOTAL : '.count($result).' DATA</span><td>';
			echo '</tr>';
			$timea = "00:00";
			if(isset($jam_cash_in)) {
				$datea = date("Y-m-d", strtotime($jam_cash_in));
				$timea = date("H:i", strtotime($jam_cash_in));
				
				echo $datea;
			}
			foreach($result as $r) { 
				$batal = ($r->data_solve=="batal" ? "(BATAL)" : "");
			
				echo '<tr>';
					echo '<td>';
						echo '<span style="font-size: 28px; font-weight: bold">'.$r->date.'<br>'.$r->id.'<br>'.$r->wsid.'<br>H-'.$r->h_min.'<br>RUN-'.$r->run_number.'<br>'.$batal.'</span>';
					echo '</td>';
					echo '<td>';
						echo '<img width="210px"  height="290px" id="geeks_1_'.$r->id.'" src="'.$r->receipt_1.'"/><br>';
						echo '<button type="button" onclick="zoomin(\'geeks_1_'.$r->id.'\')">Zoom-In</button>';;
						echo '<button type="button" onclick="zoomout(\'geeks_1_'.$r->id.'\')">Zoom-Out</button>';
					echo '</td>';
					echo '<td>';
						echo '<img width="210px"  height="290px" id="geeks_2_'.$r->id.'" src="'.$r->receipt_2.'"/><br>';
						echo '<button type="button" onclick="zoomin(\'geeks_2_'.$r->id.'\')">Zoom-In</button>';;
						echo '<button type="button" onclick="zoomout(\'geeks_2_'.$r->id.'\')">Zoom-Out</button>';
					echo '</td>';
					echo '<td>';
						echo '<img width="210px"  height="290px" id="geeks_3_'.$r->id.'" src="'.$r->receipt_3.'"/><br>';
						echo '<button type="button" onclick="zoomin(\'geeks_3_'.$r->id.'\')">Zoom-In</button>';;
						echo '<button type="button" onclick="zoomout(\'geeks_3_'.$r->id.'\')">Zoom-Out</button>';
					echo '</td>';
					echo '<td>';
						echo '<img width="210px"  height="290px" id="geeks_4_'.$r->id.'" src="'.$r->receipt_4.'"/><br>';
						echo '<button type="button" onclick="zoomin(\'geeks_4_'.$r->id.'\')">Zoom-In</button>';;
						echo '<button type="button" onclick="zoomout(\'geeks_4_'.$r->id.'\')">Zoom-Out</button>';
					echo '</td>';
					echo '<td>';
						$timea = date("H:i", strtotime($_REQUEST[$r->wsid.'_cash_in_time']));
						echo "<form method='post' onchange='this.form.submit()'>";
						echo '<input type="date" value="'.$datea.'" name="'.$r->wsid.'_cash_in_date"><br>';
						echo '<input type="time" value="'.$timea.'" name="'.$r->wsid.'_cash_in_time"><br>';
						echo 'AM 00:00 - 12:00<br>';
						echo 'PM 12:00 - 24:00<br>';
						echo '<input type="submit" value="Validation">';
						echo '</form>';
						
						$cash_in_date = $_REQUEST[$r->wsid.'_cash_in_date'];
						$cash_in_time = $_REQUEST[$r->wsid.'_cash_in_time'];
						// echo $cash_in_date." ".$cash_in_time;
						$jam_cash_in = date("Y-m-d H:i:s", strtotime($cash_in_date." ".$cash_in_time));
						if(!empty($cash_in_date) AND !empty($cash_in_time)) {
							echo "<br><br><form method='post' onchange='this.form.submit()' style='border: solid black 2px; padding: 10px'>";
							echo '<input type="text" value="'.$r->id.'" name="id" readonly><br>';
							echo '<input type="text" value="'.$jam_cash_in.'" name="jam_cash_in" readonly><br>';
							echo '<input type="submit" value="Save">';
							echo '</form>';
						}
					echo '</td>';
				echo '</tr>';
			}
			echo "<table>";
			
			?>
				<script>
					var timepicker = new TimePicker('time', {
						lang: 'en',
						theme: 'dark'
					});
					timepicker.on('change', function(evt) {
						if(evt.hour.length==1) {
							hour = pad(evt.hour, 2);
						} else {
							hour = evt.hour;
						}
						var value = (hour || '00') + ':' + (evt.minute || '00');
						evt.element.value = value;
					});
					
					function pad(num, size) {
						var s = num+"";
						while (s.length < size) s = "0" + s;
						return s;
					}
				</script>
			<?php
		}
	}
	
	public function get_data1() {
			
		$id = $_REQUEST['id'];
		// $jam_cash_in = date("Y-d-m H:i:s", strtotime($_REQUEST['jam_cash_in']));
		$jam_cash_in = $_REQUEST['jam_cash_in'];
		if(!empty($id) AND !empty($jam_cash_in)) {
			$sqlz = "UPDATE `cashtransit_detail` SET `jam_cash_in`='$jam_cash_in' WHERE `id`='$id'";
			echo $sqlz;
			$res = json_decode($this->curl->simple_get(rest_api().'/select/query2', array('query'=>$sqlz), array(CURLOPT_BUFFERSIZE => 10)));
			
			unset($_REQUEST['jam_cash_in']);
			unset($_REQUEST['id']);
		}
		
		
		$sql = "
			SELECT 
				A.id, 
				D.`type`,
				A.date AS full_date,
				EXTRACT(DAY FROM A.date) AS day,
				EXTRACT(MONTH FROM A.date) AS month,
				EXTRACT(YEAR FROM A.date) AS year,
				DATE(A.date) AS date,
				TIME(A.date) AS time,
				A.jam_cash_in, 
				A.updated_date, 
				A.id_cashtransit, 
				A.id_bank, 
				A.ctr, 
				A.state, 
				A.data_solve, 
				A.cpc_process
			FROM cashtransit_detail A
			LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
			LEFT JOIN master_branch C ON (C.id=B.branch) 
			LEFT JOIN client D ON(D.id=A.id_bank) 
			LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
			WHERE A.data_solve!='batal' 
			AND A.state='ro_atm' 
			AND A.data_solve!='' 
			AND D.type!='CDM' 
			#AND A.jam_cash_in LIKE '0000-00-00%'
			AND A.date >= '2020-07-01%'
			GROUP BY DATE(A.date)
		";
		
			// #AND A.jam_cash_in = '0000-00-00%'
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		$datea = $_REQUEST['datea'];
		?>
			<!DOCTYPE html>
			<html>

			<head>
				<title>
					How to zoom-in and zoom-out
					image using JavaScript ?
				</title>
				<style>
					table {
						border-collapse: collapse;
						margin-left: auto;
						margin-right: auto;
					}
					
					table, th, td {
						border: 1px solid black;
						text-align: center;
					}
				</style>

				<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
				<script src="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
				<link href="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet"/>

			</head>

			<body style="text-align:center;">

				<h1 style="color:green;">
					TESTER PAGES @ by me
				</h1>
				<hr>

				

				<script type="text/javascript">
					function zoomin(id) {
						var GFG = document.getElementById(id);
						var currHeight = GFG.clientHeight;
						var currWidth = GFG.clientWidth;
						GFG.style.height = (currHeight + 320) + "px";
						GFG.style.width = (currWidth + 320) + "px";
					}

					function zoomout(id) {
						var GFG = document.getElementById(id);
						var currHeight = GFG.clientHeight;
						var currWidth = GFG.clientWidth;
						GFG.style.height = (currHeight - 320) + "px";
						GFG.style.width = (currWidth - 320) + "px";
					}
				</script>
			</body>

			</html>
			<form>
				<select method='post' name='datea' onchange='this.form.submit()'>
					<?php	foreach($result as $r) { 	?>
								<option <?=($datea==$r->date?"selected":"")?> value="<?=$r->date?>"><?=date("d-m-Y", strtotime($r->date))?></option>
					<?php	} 	?>
				</select>
				<noscript><input type="submit" value="Submit"></noscript>
			</form>
		<?php
		
		
		if(!empty($datea)) {
			$where1 = "
				A.data_solve!='batal' 
				AND A.state='ro_atm' 
				AND A.data_solve!='' 
				AND D.type!='CDM' 
				#AND A.jam_cash_in LIKE '0000-00-00%'
				AND A.date LIKE '$datea%'
				ORDER BY cash_id ASC, A.updated_date ASC
			";
			$where2 = "
				A.data_solve!='batal' 
				AND A.state='ro_atm' 
				AND A.data_solve!='' 
				AND D.type!='CDM' 
				#AND A.jam_cash_in LIKE '0000-00-00%'
				AND A.date LIKE '$datea%'
				ORDER BY cash_id ASC, A.updated_date ASC
			";
			
			$sql = "
				SELECT 
					A.id, 
					B.id as cash_id, 
					B.h_min, 
					B.run_number, 
					D.`wsid`,
					D.`type`,
					E.`updated_date_cpc`,
					A.date AS full_date,
					EXTRACT(DAY FROM A.date) AS day,
					EXTRACT(MONTH FROM A.date) AS month,
					EXTRACT(YEAR FROM A.date) AS year,
					DATE(A.date) AS date,
					TIME(A.date) AS time,
					A.jam_cash_in, 
					A.updated_date, 
					A.id_cashtransit, 
					A.id_bank, 
					A.ctr, 
					A.state, 
					A.data_solve, 
					A.cpc_process, 
					A.receipt_1, 
					A.receipt_2, 
					A.receipt_3, 
					A.receipt_4
				FROM cashtransit_detail A
				LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
				LEFT JOIN master_branch C ON (C.id=B.branch) 
				LEFT JOIN client D ON(D.id=A.id_bank) 
				LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
				WHERE 
				$where1
			";
			
				// #AND A.jam_cash_in = '0000-00-00%'
				
			$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
			echo "<table border>";
			echo '<tr>';
				echo '<td colspan=5><span style="font-size: 28px; font-weight: bold">TOTAL : '.count($result).' DATA</span><td>';
			echo '</tr>';
			$timea = "00:00";
			if(isset($jam_cash_in)) {
				$datea = date("Y-m-d", strtotime($jam_cash_in));
				$timea = date("H:i", strtotime($jam_cash_in));
				
				echo $datea;
			}
			foreach($result as $r) { 
				echo '<tr>';
					echo '<td>';
						echo '<span style="font-size: 28px; font-weight: bold">'.$r->wsid.'<br>H-'.$r->h_min.'<br>RUN-'.$r->run_number.'</span>';
					echo '</td>';
					echo '<td>';
						echo '<img width="210px"  height="290px" id="geeks_1_'.$r->wsid.'" src="'.$r->receipt_1.'"/><br>';
						echo '<button type="button" onclick="zoomin(\'geeks_1_'.$r->wsid.'\')">Zoom-In</button>';;
						echo '<button type="button" onclick="zoomout(\'geeks_1_'.$r->wsid.'\')">Zoom-Out</button>';
					echo '</td>';
					echo '<td>';
						echo '<img width="210px"  height="290px" id="geeks_2_'.$r->wsid.'" src="'.$r->receipt_2.'"/><br>';
						echo '<button type="button" onclick="zoomin(\'geeks_2_'.$r->wsid.'\')">Zoom-In</button>';;
						echo '<button type="button" onclick="zoomout(\'geeks_2_'.$r->wsid.'\')">Zoom-Out</button>';
					echo '</td>';
					echo '<td>';
						echo '<img width="210px"  height="290px" id="geeks_3_'.$r->wsid.'" src="'.$r->receipt_3.'"/><br>';
						echo '<button type="button" onclick="zoomin(\'geeks_3_'.$r->wsid.'\')">Zoom-In</button>';;
						echo '<button type="button" onclick="zoomout(\'geeks_3_'.$r->wsid.'\')">Zoom-Out</button>';
					echo '</td>';
					echo '<td>';
						echo '<img width="210px"  height="290px" id="geeks_4_'.$r->wsid.'" src="'.$r->receipt_4.'"/><br>';
						echo '<button type="button" onclick="zoomin(\'geeks_4_'.$r->wsid.'\')">Zoom-In</button>';;
						echo '<button type="button" onclick="zoomout(\'geeks_4_'.$r->wsid.'\')">Zoom-Out</button>';
					echo '</td>';
					echo '<td>';
						$timea = date("H:i", strtotime($_REQUEST[$r->wsid.'_cash_in_time']));
						echo "<form method='post' onchange='this.form.submit()'>";
						echo '<input type="date" value="'.$datea.'" name="'.$r->wsid.'_cash_in_date"><br>';
						echo '<input type="time" value="'.$timea.'" name="'.$r->wsid.'_cash_in_time"><br>';
						echo 'AM 00:00 - 12:00<br>';
						echo 'PM 12:00 - 24:00<br>';
						echo '<input type="submit" value="Validation">';
						echo '</form>';
						
						$cash_in_date = $_REQUEST[$r->wsid.'_cash_in_date'];
						$cash_in_time = $_REQUEST[$r->wsid.'_cash_in_time'];
						// echo $cash_in_date." ".$cash_in_time;
						$jam_cash_in = date("Y-m-d H:i:s", strtotime($cash_in_date." ".$cash_in_time));
						if(!empty($cash_in_date) AND !empty($cash_in_time)) {
							echo "<br><br><form method='post' onchange='this.form.submit()' style='border: solid black 2px; padding: 10px'>";
							echo '<input type="text" value="'.$r->id.'" name="id" readonly><br>';
							echo '<input type="text" value="'.$jam_cash_in.'" name="jam_cash_in" readonly><br>';
							echo '<input type="submit" value="Save">';
							echo '</form>';
						}
					echo '</td>';
				echo '</tr>';
			}
			echo "<table>";
			
			?>
				<script>
					var timepicker = new TimePicker('time', {
						lang: 'en',
						theme: 'dark'
					});
					timepicker.on('change', function(evt) {
						if(evt.hour.length==1) {
							hour = pad(evt.hour, 2);
						} else {
							hour = evt.hour;
						}
						var value = (hour || '00') + ':' + (evt.minute || '00');
						evt.element.value = value;
					});
					
					function pad(num, size) {
						var s = num+"";
						while (s.length < size) s = "0" + s;
						return s;
					}
				</script>
			<?php
		}
		
		
	}
	
	public function tanggal_html() {
		$sql = "
			SELECT 
				A.id, 
				D.`type`,
				A.date AS full_date,
				EXTRACT(DAY FROM A.date) AS day,
				EXTRACT(MONTH FROM A.date) AS month,
				EXTRACT(YEAR FROM A.date) AS year,
				DATE(A.date) AS date,
				TIME(A.date) AS time,
				A.jam_cash_in, 
				A.updated_date, 
				A.id_cashtransit, 
				A.id_bank, 
				A.ctr, 
				A.state, 
				A.data_solve, 
				A.cpc_process
			FROM cashtransit_detail A
			LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
			LEFT JOIN master_branch C ON (C.id=B.branch) 
			LEFT JOIN client D ON(D.id=A.id_bank) 
			LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
			WHERE A.data_solve!='batal' 
			AND A.state='ro_atm' 
			AND A.data_solve!='' 
			AND D.type!='CDM' 
			#AND A.jam_cash_in NOT LIKE '0000-00-00%'
			#AND A.date >= '2020-08-01%'
			GROUP BY DATE(A.date)
			ORDER BY DATE(A.date) DESC
		";
		
			// #AND A.jam_cash_in = '0000-00-00%'
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		$datea = $_REQUEST['datea'];
		$limit = $_REQUEST['limit'];
		$rekon_show_hode = $_REQUEST['rekon_show_hode'];
		// 
		?>
			<form>
				<input type="text" name="limit" value="<?=$limit?>" style="width: 40px">
				<select method='post' name='rekon_show_hode' onchange='this.form.submit()'>
					<option <?=($rekon_show_hode=="show"?"selected":"")?> value="show">SHOW REKONSILIASI</option>
					<option <?=($rekon_show_hode=="hide"?"selected":"")?> value="hide">HIDE REKONSILIASI</option>
				</select>
				<select method='post' name='datea' onchange='this.form.submit()'>
					<?php	foreach($result as $r) { 	?>
								<option <?=($datea==$r->date?"selected":"")?> value="<?=$r->date?>"><?=date("d-m-Y", strtotime($r->date))?></option>
					<?php	} 	?>
				</select>
				<noscript><input type="submit" value="Submit"></noscript>
				<input type="submit" value="Submit">
			</form>
		<?php
	}
	
	public function tanggal_html4() {
		$sql = "
			SELECT 
				A.id, 
				D.`type`,
				A.date AS full_date,
				EXTRACT(DAY FROM A.date) AS day,
				EXTRACT(MONTH FROM A.date) AS month,
				EXTRACT(YEAR FROM A.date) AS year,
				DATE(A.date) AS date,
				TIME(A.date) AS time,
				A.jam_cash_in, 
				A.updated_date, 
				A.id_cashtransit, 
				A.id_bank, 
				A.ctr, 
				A.state, 
				A.data_solve, 
				A.cpc_process
			FROM cashtransit_detail A
			LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
			LEFT JOIN master_branch C ON (C.id=B.branch) 
			LEFT JOIN client D ON(D.id=A.id_bank) 
			LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
			WHERE A.data_solve!='batal' 
			AND A.state='ro_atm' 
			AND A.data_solve!='' 
			AND D.type!='CDM' 
			#AND A.jam_cash_in NOT LIKE '0000-00-00%'
			AND A.date >= '2020-08-01%'
			GROUP BY DATE(A.date)
			ORDER BY DATE(A.date) DESC
		";
		
			// #AND A.jam_cash_in = '0000-00-00%'
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		
		$datea = $_REQUEST['datea'];
		$limit = $_REQUEST['limit'];
		$rekon_show_hode = $_REQUEST['rekon_show_hode'];
		// 
		?>
			<form>
				<input type="hidden" name="limit" value="<?=$limit?>" style="width: 40px">
				<select method='post' name='rekon_show_hode' onchange='this.form.submit()' hidden>
					<option <?=($rekon_show_hode=="show"?"selected":"")?> value="show">SHOW REKONSILIASI</option>
					<option <?=($rekon_show_hode=="hide"?"selected":"")?> value="hide">HIDE REKONSILIASI</option>
				</select>
				<select method='post' name='datea' onchange='this.form.submit()'>
					<?php	foreach($result as $r) { 	?>
								<option <?=($datea==$r->date?"selected":"")?> value="<?=$r->date?>"><?=date("d-m-Y", strtotime($r->date))?></option>
					<?php	} 	?>
				</select>
				<noscript><input type="submit" value="Submit"></noscript>
				<input type="submit" value="Submit">
			</form>
		<?php
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
	
	public function get_cancel($id) {
		$sql = "SELECT * FROM run_status_cancel WHERE id_detail='$id' AND cart_no!=''";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		$lembar = 0;
		foreach($result as $r) {
			list($seal, $value) = explode(";", $r->cart_seal);
			$lembar = $lembar + $value;
		}
		
		return array(
			'ctr' => count($result),
			'lembar' => $lembar
		);
	}
	
	public function get_data4() {
		$this->tanggal_html4();
		
		$tanggal = "%".date('Y-m-d')."%";
		$tanggal = (isset($_REQUEST['datea']) ? "%".$_REQUEST['datea']."%" : "");
		
		$limit = $_REQUEST['limit']-1;
		// echo $limit;
		if($limit<0) {
			$limit = "";
		} else {
			$limit = "LIMIT $limit,1";
		}
		
		$sql = "
			SELECT 
				A.id,
				A.jam_cash_in,
				A.data_solve,
				A.cpc_process,
				A.ctr as jum_ctr,
				D.wsid,
				D.lokasi,
				D.type,
				E.pcs_50000,
				E.pcs_100000,
				(SELECT id_detail FROM run_status_cancel WHERE id_detail=A.id LIMIT 0,1) as id_detail
			FROM cashtransit_detail A
			LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
			LEFT JOIN master_branch C ON (C.id=B.branch) 
			LEFT JOIN client D ON(D.id=A.id_bank) 
			LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
			WHERE A.data_solve!='batal' 
			AND A.state='ro_atm' 
			AND A.data_solve!='' 
			AND D.type!='CDM' 
			AND A.jam_cash_in LIKE '$tanggal'
			AND A.jam_cash_in NOT LIKE '0000-00-00%'
			ORDER BY A.jam_cash_in ASC $limit
		";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		if(count($result)==0) {
			$sql = "
				SELECT 
					A.id,
					A.jam_cash_in,
					A.data_solve,
					A.cpc_process,
					A.ctr as jum_ctr,
					D.wsid,
					D.lokasi,
					D.type,
					E.pcs_50000,
					E.pcs_100000,
					(SELECT id_detail FROM run_status_cancel WHERE id_detail=A.id LIMIT 0,1) as id_detail
				FROM cashtransit_detail A
				LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
				LEFT JOIN master_branch C ON (C.id=B.branch) 
				LEFT JOIN client D ON(D.id=A.id_bank) 
				LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
				WHERE A.data_solve!='batal' 
				AND A.state='ro_atm' 
				AND A.data_solve!='' 
				AND D.type!='CDM' 
				AND A.date LIKE '$tanggal'
				ORDER BY A.jam_cash_in ASC $limit
			";
			
			$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		}
		// echo "<pre>";
		// print_r($sql);
		// print_r(count($result));
		
		$no = 0;
		$dispensed = 0;
		foreach($result as $row) {
			$format_now_time = date("H:i", strtotime($row->jam_cash_in));
			
			$no++;
			$act = $row->type;
			
			$sql2 = "
				SELECT 
					A.*,
					A.ctr as jum_ctr,
					E.pcs_50000,
					E.pcs_100000,
					(SELECT id_detail FROM run_status_cancel WHERE id_detail=A.id LIMIT 0,1) as id_detail
				FROM (SELECT id, date, updated_date, jam_cash_in, id_cashtransit, id_bank, ctr, state, data_solve, cpc_process FROM cashtransit_detail) AS A
				LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
				LEFT JOIN master_branch C ON (C.id=B.branch) 
				LEFT JOIN client D ON(D.id=A.id_bank) 
				LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) E ON(E.id_ctrs=A.id) 
				WHERE A.data_solve!='batal' 
				AND A.state='ro_atm' 
				AND A.data_solve!='' 
				AND D.wsid='$row->wsid' 
				AND A.id<'$row->id' 
				ORDER BY A.id DESC LIMIT 0,1
			";
			
			$row2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql2), array(CURLOPT_BUFFERSIZE => 10)));
			
			$canceled1 = $this->get_cancel($row->id);
			$canceled2 = $this->get_cancel($row2->id);
			// echo "<pre>";
			// print_r($sql2);
			// print_r($canceled1['ctr']);
			
			$ctr1 = $row->jum_ctr-$canceled1['ctr'];
			$ctr2 = $row2->jum_ctr-$canceled2['ctr'];
			$data = json_decode($row->cpc_process);
			$data_x = json_decode($row->data_solve);
			$datax = json_decode($row2->data_solve);
			$data2 = json_decode($row2->cpc_process);
			list($date, $time) = explode(" ", $row2->date);
			
			// echo $ctr1."<br>";
			// echo $ctr2."<br>";
			
			// KETERANGAN 
			$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
			$now_total = ($ctr1 * (intval($row->pcs_50000)!==0 ? ($row->pcs_50000-$canceled1['lembar']) : ($row->pcs_100000-$canceled1['lembar']))*(intval($row->pcs_50000)!==0 ? 50 : 100));
			$s50k = ($ctr2 * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000-$canceled2['lembar']))*50;
			$s100k = ($ctr2 * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000-$canceled2['lembar']))*100;
			
			
			$dispensed = intval(str_replace(".", "", $data->return_withdraw))/$denom;
			$return_withdraw = str_replace(".", "", str_replace(",", "", $data->return_withdraw));
			$return_cassette = str_replace(".", "", str_replace(",", "", $data->return_cassette));
			$return_rejected = str_replace(".", "", str_replace(",", "", $data->return_rejected));
			$return_remaining = str_replace(".", "", str_replace(",", "", $data->return_remaining));
			$return_dispensed = str_replace(".", "", str_replace(",", "", $data->return_dispensed));
			
			// "(".$return_cassette.") "
			
			$total 		= ($ctr2*($row2->pcs_50000!=="0" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="0" ? 50 : 100));
			$csst1 		= ($data2->cart_1_no!=="" ? $data2->cart_1_no : "");
			$csst2 		= ($data2->cart_2_no!=="" ? $data2->cart_2_no : "");
			$csst3 		= ($data2->cart_3_no!=="" ? $data2->cart_3_no : "");
			$csst4 		= ($data2->cart_4_no!=="" ? $data2->cart_4_no : "");
			$reject 	= ($data2->div_no!=="" ? $data2->div_no : 0);
			$D50 		= (intval($row2->pcs_50000)==0 	 ? "" : $this->rupiah($row2->pcs_50000-$canceled2['lembar']));
			$D100 		= (intval($row2->pcs_100000)==0  ? "" : $this->rupiah($row2->pcs_100000-$canceled2['lembar']));
			$T50 		= ((intval($row2->pcs_50000)==0  ? 0 : $this->rupiah(($row2->pcs_50000-$canceled2['lembar']) * 50)));
			$T100 		= ((intval($row2->pcs_100000)==0 ? "-" : $this->rupiah(($row2->pcs_100000-$canceled2['lembar']) * 100)));
			$CSST1_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_1_no));
			$CSST1_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_1_no));
			$CSST2_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_2_no));
			$CSST2_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_2_no));
			$CSST3_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_3_no));
			$CSST3_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_3_no));
			$CSST4_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_4_no));
			$CSST4_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_4_no));
			$RJT50 		= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah(intval((isset($data->div_no) ? $data->div_no : 0))));
			$RJT100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah(intval((isset($data->div_no) ? $data->div_no : 0))));
			
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
			
			$CSST1B50 	= (intval($row->pcs_50000)==0 ? "" : $this->rupiah($dispensed));
			$CSST1B100 	= (intval($row->pcs_100000)==0 ? "" : $this->rupiah($dispensed));
			
			$TOTALB = 50*(intval($row->pcs_50000)==0 ? 0 : intval($dispensed)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($dispensed));
					  
			$t50 = intval($row2->pcs_50000)==0 ? 0 : ($row2->pcs_50000-$canceled2['lembar']) * 50;
			$t100 = intval($row2->pcs_100000)==0 ? 0 : ($row2->pcs_100000-$canceled2['lembar']) * 100;
			$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
			
			
			// if($limit!=="") {
				// echo ($t50+$t100)."<br>";
				// echo ($TOTALA+$TOTALB)."<br>";
				// echo $dispensed."<br>";
				// echo $denom."<br>";
				// echo $CSST1_100."<br>";
				// echo $RJT100."<br>";
				// echo $dispensed."<br>";
				
				// echo "<pre>";
				// print_r($data);
			// }
			
			// echo $now_total." ".$s100k;
			
			$hasil = 0;
			$ket = "";
			
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
			
			if(isset($row->id_detail)) {
				$ket .= "<br> CANCEL CASSETTE<br>(PENGISIAN SEKARANG)";
			}
			
			if(isset($row2->id_detail)) {
				$ket .= "<br> CANCEL CASSETTE<br>(PENGISIAN SEBELUMNYA)";
			}
			
			if($act=="ATM") {
				$data_prev[] = array(
					'id'			=> $row2->id, 
					// 'cancel' 		=> $row2->id_detail, 
					// 'cancel' 		=> 0, 
					'no' 			=> $no, 
					'date' 			=> $date, 
					// 'wsid' 			=> "ID NOW : ".$row->id.", "."ID PREV : ".$row2->id.", ".$row->wsid, 
					'wsid' 			=> $row->wsid, 
					'lokasi' 		=> $row->lokasi, 
					'type' 			=> $row->type, 
					'tanggal'		=> date("Y-m-d", strtotime($row2->jam_cash_in)), 
					'time' 			=> date("H:i", strtotime($row2->jam_cash_in)), 
					'ctr' 			=> $ctr2, 
					'total' 		=> $total, 
					'csst1' 		=> $csst1, 
					'csst2' 		=> $csst2, 
					'csst3' 		=> $csst3, 
					'csst4' 		=> $csst4, 
					'reject' 		=> $reject, 
					'D50' 			=> $D50, 
					'D100' 			=> $D100, 
					'T50' 			=> $T50, 
					'T100' 			=> $T100, 
					'CSST1_50'		=> $CSST1_50, 
					'CSST1_100'		=> $CSST1_100, 
					'CSST2_50' 		=> $CSST2_50, 
					'CSST2_100'		=> $CSST2_100, 
					'CSST3_50' 		=> $CSST3_50, 
					'CSST3_100'		=> $CSST3_100, 
					'CSST4_50' 		=> $CSST4_50, 
					'CSST4_100' 	=> $CSST4_100, 
					'RJT50' 		=> $RJT50, 
					'RJT100' 		=> $RJT100, 
					'TOTALA' 		=> ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)), 
					'CSST1B50' 		=> $CSST1B50, 
					'CSST1B100' 	=> $CSST1B100, 
					'TOTALB' 		=> ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)), 
					'selisih' 		=> ($selisih=="" ? "-" : $this->rupiah($selisih)), 
					'now_time' 		=> $format_now_time, 
					'now_ctr' 		=> $ctr1, 
					'now_D50' 		=> (intval($row->pcs_50000)==0 ? 0 : $this->rupiah(($row->pcs_50000-$canceled1['lembar']))), 
					'now_D100' 		=> (intval($row->pcs_100000)==0 ? 0 : $this->rupiah(($row->pcs_100000-$canceled1['lembar']))), 
					'now_T50' 		=> ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah(($row->pcs_50000-$canceled1['lembar']) * 50))), 
					'now_T100' 		=> ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah(($row->pcs_100000-$canceled1['lembar']) * 100))), 
					'now_total' 	=> $this->rupiah(((intval($row->pcs_50000)!==0 ? ($row->pcs_50000-$canceled1['lembar']) : ($row->pcs_100000-$canceled1['lembar']))*(intval($row->pcs_50000)!==0 ? 50 : 100))), 
					'keterangan' 	=> $ket
				);
			}else if($act=="CRM") { 
				
				$TOTALA = 50*(intval(json_decode($data->cart_1_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_1_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_1_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_1_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_2_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_2_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_2_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_2_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_3_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_3_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_3_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_3_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_4_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_4_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_4_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_4_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_5_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_5_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_5_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_5_no, true)['100'])) +
						  50*(intval(json_decode($data->div_no, true)['50'])==0 ? 0 : intval(json_decode($data->div_no, true)['50'])) +
						  100*(intval(json_decode($data->div_no, true)['100'])==0 ? 0 : intval(json_decode($data->div_no, true)['100']));
				
				$TOTALB = str_replace(".", "", $data->return_crm_cashout)/1000;
				// $data->return_crm_balance = "105.550.000";
				$CCRM = str_replace(".", "", $data->return_crm_balance)/1000;
				
				
				$t50 = ($ctr2 * (intval($data2->s50k)==0 ? 0 : $data2->s50k))*50;
				$t100 = ($ctr2 * (intval($data2->s100k)==0 ? 0 : $data2->s100k))*100;
				$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
				
				// echo "A : ".($TOTALA)."<br>";
				// echo "B : ".($TOTALB)."<br>";
				// echo "AB : ".($TOTALA+$TOTALB)."<br>";
				
				$data_prev[] = array(
					'no' => $no,
					'date' => $date,
					'wsid' => $row->wsid,
					'lokasi' => $row->lokasi,
					'type' => $row->type,
					'tanggal'		=> date("Y-m-d", strtotime($row2->jam_cash_in)), 
					'time' 			=> date("H:i", strtotime($row2->jam_cash_in)), 
					'ctr' => $ctr2,
					'T50' => $t50,
					'T100' => $t100,
					'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
					'csst1' => $data2->cart_1_no,
					'csst2' => $data2->cart_2_no,
					'csst3' => $data2->cart_3_no,
					'csst4' => $data2->cart_4_no,
					'csst5' => $data2->cart_5_no,
					'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
					'D50' => (intval($data2->s50k)==0 ? "" : $this->rupiah($data2->s50k)),	
					'D100' => (intval($data2->s100k)==0 ? "" : $this->rupiah($data2->s100k)),
					'T50' => ((intval($data2->s50k)==0 ? 0 : $this->rupiah($data2->s50k * 50))),
					'T100' => ((intval($data2->s100k)==0 ? "-" : $this->rupiah($data2->s100k * 100))),
					'CSST1_50' => intval(json_decode($data->cart_1_no, true)['50']),
					'CSST1_100' => intval(json_decode($data->cart_1_no, true)['100']),
					'CSST2_50' => intval(json_decode($data->cart_2_no, true)['50']),
					'CSST2_100' => intval(json_decode($data->cart_2_no, true)['100']),
					'CSST3_50' => intval(json_decode($data->cart_3_no, true)['50']),
					'CSST3_100' => intval(json_decode($data->cart_3_no, true)['100']),
					'CSST4_50' => intval(json_decode($data->cart_4_no, true)['50']),
					'CSST4_100' => intval(json_decode($data->cart_4_no, true)['100']),
					'CSST5_50' => intval(json_decode($data->cart_5_no, true)['50']),
					'CSST5_100' => intval(json_decode($data->cart_5_no, true)['100']),
					'RJT50' => intval(json_decode($data->div_no, true)['50']),
					'RJT100' => intval(json_decode($data->div_no, true)['100']),
					'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
					'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
					'CCRM' => ($CCRM=="" ? "-" : $this->rupiah($CCRM)),
					// 'selisih' => $selisih,
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
			
			$dispensed = 0;
		}
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
		
		if(!empty($data_prev)) {
			echo $this->data['table'] = $this->show_table($data_prev);
			echo $this->data['table2'] = $this->show_table2($data_prev2);
		} else {
			echo $this->data['table'] = "<center>SORRY, NO DATA!</center>";
		}
	}
	
	
	public function rekonsiliasi() {
		$this->tanggal_html();
		
		$tanggal = "%".date('Y-m-d')."%";
		$tanggal = (isset($_REQUEST['datea']) ? "%".$_REQUEST['datea']."%" : "");
		
		$limit = $_REQUEST['limit']-1;
		// echo $limit;
		if($limit<0) {
			$limit = "";
		} else {
			$limit = "LIMIT $limit,1";
		}
		
		$sql = "
			SELECT 
				A.id,
				B.id as id_ct,
				A.jam_cash_in,
				A.data_solve,
				A.cpc_process,
				A.ctr as jum_ctr,
				D.wsid,
				D.lokasi,
				D.type,
				E.pcs_50000,
				E.pcs_100000,
				(SELECT id_detail FROM run_status_cancel WHERE id_detail=A.id LIMIT 0,1) as id_detail
			FROM cashtransit_detail A
			LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
			LEFT JOIN master_branch C ON (C.id=B.branch) 
			LEFT JOIN client D ON(D.id=A.id_bank) 
			LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
			WHERE A.data_solve!='batal' 
			AND A.state='ro_atm' 
			AND A.data_solve!='' 
			AND D.type!='CDM' 
			AND A.jam_cash_in LIKE '$tanggal'
			AND A.jam_cash_in NOT LIKE '0000-00-00%'
			ORDER BY A.jam_cash_in ASC $limit
		";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		if(count($result)==0) {
			$sql = "
				SELECT 
					A.id,
					B.id as id_ct,
					A.jam_cash_in,
					A.data_solve,
					A.cpc_process,
					A.ctr as jum_ctr,
					D.wsid,
					D.lokasi,
					D.type,
					E.pcs_50000,
					E.pcs_100000,
					(SELECT id_detail FROM run_status_cancel WHERE id_detail=A.id LIMIT 0,1) as id_detail
				FROM cashtransit_detail A
				LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
				LEFT JOIN master_branch C ON (C.id=B.branch) 
				LEFT JOIN client D ON(D.id=A.id_bank) 
				LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
				WHERE A.data_solve!='batal' 
				AND A.state='ro_atm' 
				AND A.data_solve!='' 
				AND D.type!='CDM' 
				AND A.date LIKE '$tanggal'
				ORDER BY A.jam_cash_in ASC $limit
			";
			
			$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		}
		// echo "<pre>";
		// print_r($sql);
		// print_r(count($result));
		
		$no = 0;
		$dispensed = 0;
		foreach($result as $row) {
			$format_now_time = date("H:i", strtotime($row->jam_cash_in));
			
			$no++;
			$act = $row->type;
			
			$sql2 = "
				SELECT 
					A.id,
					B.id as id_ct,
					A.ctr as jum_ctr,
					E.pcs_50000,
					E.pcs_100000,
					(SELECT id_detail FROM run_status_cancel WHERE id_detail=A.id LIMIT 0,1) as id_detail
				FROM (SELECT id, date, updated_date, jam_cash_in, id_cashtransit, id_bank, ctr, state, data_solve, cpc_process FROM cashtransit_detail) AS A
				LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
				LEFT JOIN master_branch C ON (C.id=B.branch) 
				LEFT JOIN client D ON(D.id=A.id_bank) 
				LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) E ON(E.id_ctrs=A.id) 
				WHERE A.data_solve!='batal' 
				AND A.state='ro_atm' 
				AND A.data_solve!='' 
				AND D.wsid='$row->wsid' 
				AND A.id<'$row->id' 
				ORDER BY A.id DESC LIMIT 0,1
			";
			
			$row2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql2), array(CURLOPT_BUFFERSIZE => 10)));
			
			$canceled1 = $this->get_cancel($row->id);
			$canceled2 = $this->get_cancel($row2->id);
			// echo "<pre>";
			// print_r($sql2);
			// print_r($canceled1['ctr']);
			
			$ctr1 = $row->jum_ctr-$canceled1['ctr'];
			$ctr2 = $row2->jum_ctr-$canceled2['ctr'];
			$data = json_decode($row->cpc_process);
			$data_x = json_decode($row->data_solve);
			$datax = json_decode($row2->data_solve);
			$data2 = json_decode($row2->cpc_process);
			list($date, $time) = explode(" ", $row2->date);
			
			// echo $ctr1."<br>";
			// echo $ctr2."<br>";
			
			// KETERANGAN 
			$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
			$now_total = ($ctr1 * (intval($row->pcs_50000)!==0 ? ($row->pcs_50000-$canceled1['lembar']) : ($row->pcs_100000-$canceled1['lembar']))*(intval($row->pcs_50000)!==0 ? 50 : 100));
			$s50k = ($ctr2 * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000-$canceled2['lembar']))*50;
			$s100k = ($ctr2 * (intval($row2->pcs_100000)==0 ? 0 : $row2->pcs_100000-$canceled2['lembar']))*100;
			
			
			$dispensed = intval(str_replace(".", "", $data->return_withdraw))/$denom;
			$return_withdraw = str_replace(".", "", str_replace(",", "", $data->return_withdraw));
			$return_cassette = str_replace(".", "", str_replace(",", "", $data->return_cassette));
			$return_rejected = str_replace(".", "", str_replace(",", "", $data->return_rejected));
			$return_remaining = str_replace(".", "", str_replace(",", "", $data->return_remaining));
			$return_dispensed = str_replace(".", "", str_replace(",", "", $data->return_dispensed));
			
			// "(".$return_cassette.") "
			
			$total 		= ($ctr2*($row2->pcs_50000!=="0" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="0" ? 50 : 100));
			$csst1 		= ($data2->cart_1_no!=="" ? $data2->cart_1_no : "");
			$csst2 		= ($data2->cart_2_no!=="" ? $data2->cart_2_no : "");
			$csst3 		= ($data2->cart_3_no!=="" ? $data2->cart_3_no : "");
			$csst4 		= ($data2->cart_4_no!=="" ? $data2->cart_4_no : "");
			$reject 	= ($data2->div_no!=="" ? $data2->div_no : 0);
			$D50 		= (intval($row2->pcs_50000)==0 	 ? "" : $this->rupiah($row2->pcs_50000-$canceled2['lembar']));
			$D100 		= (intval($row2->pcs_100000)==0  ? "" : $this->rupiah($row2->pcs_100000-$canceled2['lembar']));
			$T50 		= ((intval($row2->pcs_50000)==0  ? 0 : $this->rupiah(($row2->pcs_50000-$canceled2['lembar']) * 50)));
			$T100 		= ((intval($row2->pcs_100000)==0 ? "-" : $this->rupiah(($row2->pcs_100000-$canceled2['lembar']) * 100)));
			$CSST1_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_1_no));
			$CSST1_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_1_no));
			$CSST2_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_2_no));
			$CSST2_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_2_no));
			$CSST3_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_3_no));
			$CSST3_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_3_no));
			$CSST4_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_4_no));
			$CSST4_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_4_no));
			$RJT50 		= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah(intval((isset($data->div_no) ? $data->div_no : 0))));
			$RJT100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah(intval((isset($data->div_no) ? $data->div_no : 0))));
			
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
			
			$CSST1B50 	= (intval($row->pcs_50000)==0 ? "" : $this->rupiah($dispensed));
			$CSST1B100 	= (intval($row->pcs_100000)==0 ? "" : $this->rupiah($dispensed));
			
			$TOTALB = 50*(intval($row->pcs_50000)==0 ? 0 : intval($dispensed)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($dispensed));
					  
			$t50 = intval($row2->pcs_50000)==0 ? 0 : ($row2->pcs_50000-$canceled2['lembar']) * 50;
			$t100 = intval($row2->pcs_100000)==0 ? 0 : ($row2->pcs_100000-$canceled2['lembar']) * 100;
			$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
			
			
			if($limit!=="") {
				echo ($t50+$t100)."<br>";
				echo ($TOTALA+$TOTALB)."<br>";
				echo $dispensed."<br>";
				echo $denom."<br>";
				echo $CSST1_100."<br>";
				echo $RJT100."<br>";
				echo $dispensed."<br>";
				
				echo "<pre>";
				// print_r($sql2);
				print_r($data);
			}
			
			$hasil = 0;
			$ket = "";
			// echo (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)."<br>";
			// echo $s100k;
			
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
			
			if(isset($row->id_detail)) {
				$ket .= "<br> CANCEL CASSETTE<br>(PENGISIAN SEKARANG)";
			}
			
			if(isset($row2->id_detail)) {
				$ket .= "<br> CANCEL CASSETTE<br>(PENGISIAN SEBELUMNYA)";
			}
			
			// $data_prev[] = array('id'=>null, 'no'=>null, 'date'=>null, 'wsid'=>null, 'lokasi'=>null, 'type'=>null, 'tanggal'=>null, 'time'=>null, 'ctr'=>null, 'total'=>null, 'csst1'=>null, 'csst2'=>null, 'csst3'=>null, 'csst4'=>null, 'reject'=>null, 'D50'=>null, 'D100'=>null, 'T50'=>null, 'T100'=>null, 'CSST1_50'=>null, 'CSST1_100'=>null, 'CSST2_50'=>null, 'CSST2_100'=>null, 'CSST3_50'=>null, 'CSST3_100'=>null, 'CSST4_50'=>null, 'CSST4_100'=>null, 'RJT50'=>null, 'RJT100'=>null, 'TOTALA'=>null, 'CSST1B50'=>null, 'CSST1B100'=>null, 'TOTALB'=>null, 'selisih'=>null, 'now_time'=>null, 'now_ctr'=>null, 'now_D50'=>null, 'now_D100'=>null, 'now_T50'=>null, 'now_T100'=>null, 'now_total'=>null, 'keterangan'=>null);
			
			// // echo ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100));
			// // echo $ctr2;
			// // echo "<br>";
			// // echo $row2->pcs_50000;
			// // echo "<br>";
			// // echo $row2->pcs_100000;
			// // echo "<br>";
			// // echo ($row2->pcs_50000!=="0" ? $row2->pcs_50000 : $row2->pcs_100000);
			// // echo "<br>";
			// // echo ($row2->pcs_50000!=="0" ? 50 : 100);
			// // echo intval($row2->pcs_100000);
			// // echo "<br>";
			
			// $data_prev[]['id'] 			= $row2->id;
			// $data_prev[]['no'] 			= $no;
			// $data_prev[]['date'] 		= $date;
			// $data_prev[]['wsid'] 		= $row->wsid;
			// $data_prev[]['lokasi'] 		= $row->lokasi;
			// $data_prev[]['type'] 		= $row->type;
			// $data_prev[]['tanggal'] 	= date("Y-m-d", strtotime($row2->jam_cash_in));
			// $data_prev[]['time'] 		= date("H:i", strtotime($row2->jam_cash_in));
			// $data_prev[]['ctr'] 		= $ctr2;
			// $data_prev[]['total'] 		= $total;
			// $data_prev[]['csst1'] 		= $csst1;
			// $data_prev[]['csst2'] 		= $csst2;
			// $data_prev[]['csst3'] 		= $csst3;
			// $data_prev[]['csst4'] 		= $csst4;
			// $data_prev[]['reject'] 		= $reject;
			// $data_prev[]['D50'] 		= $D50;
			// $data_prev[]['D100'] 		= $D100;
			// $data_prev[]['T50'] 		= $T50;
			// $data_prev[]['T100'] 		= $T100;
			// $data_prev[]['CSST1_50'] 	= $CSST1_50;
			// $data_prev[]['CSST1_100'] 	= $CSST1_100;
			// $data_prev[]['CSST2_50'] 	= $CSST2_50;
			// $data_prev[]['CSST2_100'] 	= $CSST2_100;
			// $data_prev[]['CSST3_50'] 	= $CSST3_50;
			// $data_prev[]['CSST3_100'] 	= $CSST3_100;
			// $data_prev[]['CSST4_50'] 	= $CSST4_50;
			// $data_prev[]['CSST4_100'] 	= $CSST4_100;
			// $data_prev[]['RJT50'] 		= $RJT50;
			// $data_prev[]['RJT100'] 		= $RJT100;
			// $data_prev[]['TOTALA'] 		= $TOTALA;
			// $data_prev[]['CSST1B50'] 	= $CSST1B50;
			// $data_prev[]['CSST1B100'] 	= $CSST1B100;
			// $data_prev[]['TOTALB'] 		= ($TOTALB=="" ? "-" : $this->rupiah($TOTALB));
			// $data_prev[]['selisih'] 	= ($selisih=="" ? "-" : $this->rupiah($selisih));
			// $data_prev[]['now_time'] 	= $format_now_time;
			// $data_prev[]['now_ctr'] 	= $row->jum_ctr;
			// $data_prev[]['now_D50'] 	= (intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_50000));
			// $data_prev[]['now_D100'] 	= (intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_100000));
			// $data_prev[]['now_T50'] 	= ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_50000 * 50)));
			// $data_prev[]['now_T100'] 	= ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_100000 * 100)));
			// $data_prev[]['now_total'] 	= $this->rupiah(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100)));
			// $data_prev[]['keterangan'] 	= $ket;
			
			if($act=="ATM") {
				$data_prev[] = array(
					'id'			=> $row2->id, 
					// 'cancel' 		=> $row2->id_detail, 
					// 'cancel' 		=> 0, 
					'no' 			=> $no, 
					'date' 			=> $date, 
					'wsid' 			=> "ID NOW : (".$row->id_ct.")".$row->id.", "."ID PREV : (".$row2->id_ct.")".$row2->id.", ".$row->wsid, 
					// 'wsid' 			=> $row->wsid, 
					'lokasi' 		=> $row->lokasi, 
					'type' 			=> $row->type, 
					'tanggal'		=> date("Y-m-d", strtotime($row2->jam_cash_in)), 
					'time' 			=> date("H:i", strtotime($row2->jam_cash_in)), 
					'ctr' 			=> $ctr2, 
					'total' 		=> $total, 
					'csst1' 		=> $csst1, 
					'csst2' 		=> $csst2, 
					'csst3' 		=> $csst3, 
					'csst4' 		=> $csst4, 
					'reject' 		=> $reject, 
					'D50' 			=> $D50, 
					'D100' 			=> $D100, 
					'T50' 			=> $T50, 
					'T100' 			=> $T100, 
					'CSST1_50'		=> $CSST1_50, 
					'CSST1_100'		=> $CSST1_100, 
					'CSST2_50' 		=> $CSST2_50, 
					'CSST2_100'		=> $CSST2_100, 
					'CSST3_50' 		=> $CSST3_50, 
					'CSST3_100'		=> $CSST3_100, 
					'CSST4_50' 		=> $CSST4_50, 
					'CSST4_100' 	=> $CSST4_100, 
					'RJT50' 		=> $RJT50, 
					'RJT100' 		=> $RJT100, 
					'TOTALA' 		=> ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)), 
					'CSST1B50' 		=> $CSST1B50, 
					'CSST1B100' 	=> $CSST1B100, 
					'TOTALB' 		=> ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)), 
					'selisih' 		=> ($selisih=="" ? "-" : $this->rupiah($selisih)), 
					'now_time' 		=> $format_now_time, 
					'now_ctr' 		=> $ctr1, 
					'now_D50' 		=> (intval($row->pcs_50000)==0 ? 0 : $this->rupiah(($row->pcs_50000-$canceled1['lembar']))), 
					'now_D100' 		=> (intval($row->pcs_100000)==0 ? 0 : $this->rupiah(($row->pcs_100000-$canceled1['lembar']))), 
					'now_T50' 		=> ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah(($row->pcs_50000-$canceled1['lembar']) * 50))), 
					'now_T100' 		=> ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah(($row->pcs_100000-$canceled1['lembar']) * 100))), 
					'now_total' 	=> $this->rupiah(((intval($row->pcs_50000)!==0 ? ($row->pcs_50000-$canceled1['lembar']) : ($row->pcs_100000-$canceled1['lembar']))*(intval($row->pcs_50000)!==0 ? 50 : 100))), 
					'keterangan' 	=> $ket
				);
			}else if($act=="CRM") { 
				
				$TOTALA = 50*(intval(json_decode($data->cart_1_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_1_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_1_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_1_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_2_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_2_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_2_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_2_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_3_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_3_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_3_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_3_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_4_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_4_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_4_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_4_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_5_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_5_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_5_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_5_no, true)['100'])) +
						  50*(intval(json_decode($data->div_no, true)['50'])==0 ? 0 : intval(json_decode($data->div_no, true)['50'])) +
						  100*(intval(json_decode($data->div_no, true)['100'])==0 ? 0 : intval(json_decode($data->div_no, true)['100']));
				
				$TOTALB = str_replace(".", "", $data->return_crm_cashout)/1000;
				// $data->return_crm_balance = "105.550.000";
				$CCRM = str_replace(".", "", $data->return_crm_balance)/1000;
				
				
				$t50 = ($ctr2 * (intval($data2->s50k)==0 ? 0 : $data2->s50k))*50;
				$t100 = ($ctr2 * (intval($data2->s100k)==0 ? 0 : $data2->s100k))*100;
				$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
				
				// echo "A : ".($TOTALA)."<br>";
				// echo "B : ".($TOTALB)."<br>";
				// echo "AB : ".($TOTALA+$TOTALB)."<br>";
				
				$data_prev[] = array(
					'no' => $no,
					'date' => $date,
					'wsid' => $row->wsid,
					'lokasi' => $row->lokasi,
					'type' => $row->type,
					'tanggal'		=> date("Y-m-d", strtotime($row2->jam_cash_in)), 
					'time' 			=> date("H:i", strtotime($row2->jam_cash_in)), 
					'ctr' => $ctr2,
					'T50' => $t50,
					'T100' => $t100,
					'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
					'csst1' => $data2->cart_1_no,
					'csst2' => $data2->cart_2_no,
					'csst3' => $data2->cart_3_no,
					'csst4' => $data2->cart_4_no,
					'csst5' => $data2->cart_5_no,
					'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
					'D50' => (intval($data2->s50k)==0 ? "" : $this->rupiah($data2->s50k)),	
					'D100' => (intval($data2->s100k)==0 ? "" : $this->rupiah($data2->s100k)),
					'T50' => ((intval($data2->s50k)==0 ? 0 : $this->rupiah($data2->s50k * 50))),
					'T100' => ((intval($data2->s100k)==0 ? "-" : $this->rupiah($data2->s100k * 100))),
					'CSST1_50' => intval(json_decode($data->cart_1_no, true)['50']),
					'CSST1_100' => intval(json_decode($data->cart_1_no, true)['100']),
					'CSST2_50' => intval(json_decode($data->cart_2_no, true)['50']),
					'CSST2_100' => intval(json_decode($data->cart_2_no, true)['100']),
					'CSST3_50' => intval(json_decode($data->cart_3_no, true)['50']),
					'CSST3_100' => intval(json_decode($data->cart_3_no, true)['100']),
					'CSST4_50' => intval(json_decode($data->cart_4_no, true)['50']),
					'CSST4_100' => intval(json_decode($data->cart_4_no, true)['100']),
					'CSST5_50' => intval(json_decode($data->cart_5_no, true)['50']),
					'CSST5_100' => intval(json_decode($data->cart_5_no, true)['100']),
					'RJT50' => intval(json_decode($data->div_no, true)['50']),
					'RJT100' => intval(json_decode($data->div_no, true)['100']),
					'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
					'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
					'CCRM' => ($CCRM=="" ? "-" : $this->rupiah($CCRM)),
					// 'selisih' => $selisih,
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
			
			$dispensed = 0;
		}
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
		
		if(!empty($data_prev)) {
			echo $this->data['table'] = $this->show_table($data_prev);
			echo $this->data['table2'] = $this->show_table2($data_prev2);
		} else {
			echo $this->data['table'] = "<center>SORRY, NO DATA!</center>";
		}
	}
	
	
	public function get_data_xls() {
		// $this->tanggal_html();
		
		$tanggal = "%".date('Y-m-d')."%";
		$tanggal = (isset($_REQUEST['datea']) ? "%".$_REQUEST['datea']."%" : "");
		// A.id, 
		// A.date, 
		// A.jam_cash_in,
		// A.updated_date, 
		// A.id_cashtransit, 
		// A.id_bank, 
		// A.ctr, 
		// A.state, 
		// A.data_solve, 
		// A.cpc_process
		
		$limit = $_REQUEST['limit']-1;
		// echo $limit;
		if($limit<0) {
			$limit = "";
		} else {
			$limit = "LIMIT $limit,1";
		}
		$sql = "
			SELECT 
				A.id,
				A.jam_cash_in,
				A.data_solve,
				A.cpc_process,
				A.ctr as jum_ctr,
				D.wsid,
				D.lokasi,
				D.type,
				E.pcs_50000,
				E.pcs_100000
			FROM cashtransit_detail A
			LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
			LEFT JOIN master_branch C ON (C.id=B.branch) 
			LEFT JOIN client D ON(D.id=A.id_bank) 
			LEFT JOIN runsheet_cashprocessing E ON(E.id=A.id) 
			WHERE A.data_solve!='batal' 
			AND A.state='ro_atm' 
			AND A.data_solve!='' 
			AND D.type!='CDM' 
			AND A.jam_cash_in LIKE '$tanggal' 
			ORDER BY A.jam_cash_in ASC $limit
		";
		
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		
		// echo "<pre>";
		// print_r($sql);
		// print_r($result);
		
		$no = 0;
		$dispensed = 0;
		foreach($result as $row) {
			$format_now_time = date("H:i", strtotime($row->jam_cash_in));
			
			$no++;
			$act = $row->type;
			
			$sql2 = "
				SELECT 
					A.*,
					A.ctr as jum_ctr,
					E.pcs_50000,
					E.pcs_100000,
					(SELECT id_detail FROM run_status_cancel WHERE id_detail=A.id LIMIT 0,1) as id_detail
				FROM (SELECT id, date, updated_date, jam_cash_in, id_cashtransit, id_bank, ctr, state, data_solve, cpc_process FROM cashtransit_detail) AS A
				LEFT JOIN cashtransit B ON (B.id=A.id_cashtransit) 
				LEFT JOIN master_branch C ON (C.id=B.branch) 
				LEFT JOIN client D ON(D.id=A.id_bank) 
				LEFT JOIN (SELECT id as id_ctrs, id_cashtransit, pcs_50000, pcs_100000 FROM runsheet_cashprocessing) E ON(E.id_ctrs=A.id) 
				WHERE A.data_solve!='batal' 
				AND A.state='ro_atm' 
				AND A.data_solve!='' 
				AND D.wsid='$row->wsid' 
				AND A.id<'$row->id' 
				ORDER BY A.id DESC LIMIT 0,1
			";
			
			$row2 = json_decode($this->curl->simple_get(rest_api().'/select/query', array('query'=>$sql2), array(CURLOPT_BUFFERSIZE => 10)));
			
			$canceled1 = $this->get_cancel($row->id);
			$canceled2 = $this->get_cancel($row2->id);
			// echo "<pre>";
			// print_r($sql2);
			// print_r($canceled1['ctr']);
			
			$ctr1 = $row->jum_ctr-$canceled1['ctr'];
			$ctr2 = $row2->jum_ctr-$canceled2['ctr'];
			$data = json_decode($row->cpc_process);
			$data_x = json_decode($row->data_solve);
			$datax = json_decode($row2->data_solve);
			$data2 = json_decode($row2->cpc_process);
			list($date, $time) = explode(" ", $row2->date);
			
			// echo $ctr1."<br>";
			// echo $ctr2."<br>";
			
			// KETERANGAN 
			$denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
			$now_total = ($row->jum_ctr * (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100));
			$s50k = ($ctr2 * (intval($row2->pcs_50000)==0 ? 0 : $row2->pcs_50000))*50;
			$s100k = ($ctr2 * (intval($row->pcs_100000)==0 ? 0 : $row->pcs_100000))*100;
			
			$dispensed = intval(str_replace(".", "", $data->return_withdraw))/$denom;
			$return_withdraw = str_replace(".", "", str_replace(",", "", $data->return_withdraw));
			$return_cassette = str_replace(".", "", str_replace(",", "", $data->return_cassette));
			$return_rejected = str_replace(".", "", str_replace(",", "", $data->return_rejected));
			$return_remaining = str_replace(".", "", str_replace(",", "", $data->return_remaining));
			$return_dispensed = str_replace(".", "", str_replace(",", "", $data->return_dispensed));
			
			// "(".$return_cassette.") "
			
			$total 		= ($ctr2*($row2->pcs_50000!=="0" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="0" ? 50 : 100));
			$csst1 		= ($data2->cart_1_no!=="" ? $data2->cart_1_no : "");
			$csst2 		= ($data2->cart_2_no!=="" ? $data2->cart_2_no : "");
			$csst3 		= ($data2->cart_3_no!=="" ? $data2->cart_3_no : "");
			$csst4 		= ($data2->cart_4_no!=="" ? $data2->cart_4_no : "");
			$reject 	= ($data2->div_no!=="" ? $data2->div_no : 0);
			$D50 		= (intval($row2->pcs_50000)==0 	 ? "" : $this->rupiah($row2->pcs_50000-$canceled2['lembar']));
			$D100 		= (intval($row2->pcs_100000)==0  ? "" : $this->rupiah($row2->pcs_100000-$canceled2['lembar']));
			$T50 		= ((intval($row2->pcs_50000)==0  ? 0 : $this->rupiah(($row2->pcs_50000-$canceled2['lembar']) * 50)));
			$T100 		= ((intval($row2->pcs_100000)==0 ? "-" : $this->rupiah(($row2->pcs_100000-$canceled2['lembar']) * 100)));
			$CSST1_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_1_no));
			$CSST1_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_1_no));
			$CSST2_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_2_no));
			$CSST2_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_2_no));
			$CSST3_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_3_no));
			$CSST3_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_3_no));
			$CSST4_50 	= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah($data->cart_4_no));
			$CSST4_100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah($data->cart_4_no));
			$RJT50 		= (intval($row->pcs_50000)==0 	 ? "" : $this->rupiah(intval((isset($data->div_no) ? $data->div_no : 0))));
			$RJT100 	= (intval($row->pcs_100000)==0  ? "" : $this->rupiah(intval((isset($data->div_no) ? $data->div_no : 0))));
			
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
			
			$CSST1B50 	= (intval($row->pcs_50000)==0 ? "" : $this->rupiah($dispensed));
			$CSST1B100 	= (intval($row->pcs_100000)==0 ? "" : $this->rupiah($dispensed));
			
			$TOTALB = 50*(intval($row->pcs_50000)==0 ? 0 : intval($dispensed)) +
					  100*(intval($row->pcs_100000)==0 ? 0 : intval($dispensed));
					  
			$t50 = intval($row2->pcs_50000)==0 ? 0 : ($row2->pcs_50000-$canceled2['lembar']) * 50;
			$t100 = intval($row2->pcs_100000)==0 ? 0 : ($row2->pcs_100000-$canceled2['lembar']) * 100;
			$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
			
			
			// if($limit!=="") {
				// echo ($t50+$t100)."<br>";
				// echo ($TOTALA+$TOTALB)."<br>";
				// echo $dispensed."<br>";
				// echo $denom."<br>";
				// echo $CSST1_100."<br>";
				// echo $RJT100."<br>";
				// echo $dispensed."<br>";
				
				// echo "<pre>";
				// // print_r($sql2);
				// print_r($data);
			// }
			
			$hasil = 0;
			$ket = "";
			// echo (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)."<br>";
			// echo $s100k;
			
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
			
			// $data_prev[] = array('id'=>null, 'no'=>null, 'date'=>null, 'wsid'=>null, 'lokasi'=>null, 'type'=>null, 'tanggal'=>null, 'time'=>null, 'ctr'=>null, 'total'=>null, 'csst1'=>null, 'csst2'=>null, 'csst3'=>null, 'csst4'=>null, 'reject'=>null, 'D50'=>null, 'D100'=>null, 'T50'=>null, 'T100'=>null, 'CSST1_50'=>null, 'CSST1_100'=>null, 'CSST2_50'=>null, 'CSST2_100'=>null, 'CSST3_50'=>null, 'CSST3_100'=>null, 'CSST4_50'=>null, 'CSST4_100'=>null, 'RJT50'=>null, 'RJT100'=>null, 'TOTALA'=>null, 'CSST1B50'=>null, 'CSST1B100'=>null, 'TOTALB'=>null, 'selisih'=>null, 'now_time'=>null, 'now_ctr'=>null, 'now_D50'=>null, 'now_D100'=>null, 'now_T50'=>null, 'now_T100'=>null, 'now_total'=>null, 'keterangan'=>null);
			
			// // echo ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100));
			// // echo $ctr2;
			// // echo "<br>";
			// // echo $row2->pcs_50000;
			// // echo "<br>";
			// // echo $row2->pcs_100000;
			// // echo "<br>";
			// // echo ($row2->pcs_50000!=="0" ? $row2->pcs_50000 : $row2->pcs_100000);
			// // echo "<br>";
			// // echo ($row2->pcs_50000!=="0" ? 50 : 100);
			// // echo intval($row2->pcs_100000);
			// // echo "<br>";
			
			// $data_prev[]['id'] 			= $row2->id;
			// $data_prev[]['no'] 			= $no;
			// $data_prev[]['date'] 		= $date;
			// $data_prev[]['wsid'] 		= $row->wsid;
			// $data_prev[]['lokasi'] 		= $row->lokasi;
			// $data_prev[]['type'] 		= $row->type;
			// $data_prev[]['tanggal'] 	= date("Y-m-d", strtotime($row2->jam_cash_in));
			// $data_prev[]['time'] 		= date("H:i", strtotime($row2->jam_cash_in));
			// $data_prev[]['ctr'] 		= $ctr2;
			// $data_prev[]['total'] 		= $total;
			// $data_prev[]['csst1'] 		= $csst1;
			// $data_prev[]['csst2'] 		= $csst2;
			// $data_prev[]['csst3'] 		= $csst3;
			// $data_prev[]['csst4'] 		= $csst4;
			// $data_prev[]['reject'] 		= $reject;
			// $data_prev[]['D50'] 		= $D50;
			// $data_prev[]['D100'] 		= $D100;
			// $data_prev[]['T50'] 		= $T50;
			// $data_prev[]['T100'] 		= $T100;
			// $data_prev[]['CSST1_50'] 	= $CSST1_50;
			// $data_prev[]['CSST1_100'] 	= $CSST1_100;
			// $data_prev[]['CSST2_50'] 	= $CSST2_50;
			// $data_prev[]['CSST2_100'] 	= $CSST2_100;
			// $data_prev[]['CSST3_50'] 	= $CSST3_50;
			// $data_prev[]['CSST3_100'] 	= $CSST3_100;
			// $data_prev[]['CSST4_50'] 	= $CSST4_50;
			// $data_prev[]['CSST4_100'] 	= $CSST4_100;
			// $data_prev[]['RJT50'] 		= $RJT50;
			// $data_prev[]['RJT100'] 		= $RJT100;
			// $data_prev[]['TOTALA'] 		= $TOTALA;
			// $data_prev[]['CSST1B50'] 	= $CSST1B50;
			// $data_prev[]['CSST1B100'] 	= $CSST1B100;
			// $data_prev[]['TOTALB'] 		= ($TOTALB=="" ? "-" : $this->rupiah($TOTALB));
			// $data_prev[]['selisih'] 	= ($selisih=="" ? "-" : $this->rupiah($selisih));
			// $data_prev[]['now_time'] 	= $format_now_time;
			// $data_prev[]['now_ctr'] 	= $row->jum_ctr;
			// $data_prev[]['now_D50'] 	= (intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_50000));
			// $data_prev[]['now_D100'] 	= (intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_100000));
			// $data_prev[]['now_T50'] 	= ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_50000 * 50)));
			// $data_prev[]['now_T100'] 	= ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr * $row->pcs_100000 * 100)));
			// $data_prev[]['now_total'] 	= $this->rupiah(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100)));
			// $data_prev[]['keterangan'] 	= $ket;
			
			if($act=="ATM") {
				$data_prev[] = array(
					'id'			=> $row2->id, 
					// 'cancel' 		=> $row2->id_detail, 
					// 'cancel' 		=> 0, 
					'no' 			=> $no, 
					'date' 			=> $date, 
					// 'wsid' 			=> "CANCEL : ".$row2->id_detail."<br>"."ID NOW : ".$row->id."<br>"."ID PREV : ".$row2->id."<br>".$row->wsid, 
					'wsid' 			=> $row->wsid, 
					'lokasi' 		=> $row->lokasi, 
					'type' 			=> $row->type, 
					'tanggal'		=> date("Y-m-d", strtotime($row2->jam_cash_in)), 
					'time' 			=> date("H:i", strtotime($row2->jam_cash_in)), 
					'ctr' 			=> $ctr2, 
					'total' 		=> $total, 
					'csst1' 		=> $csst1, 
					'csst2' 		=> $csst2, 
					'csst3' 		=> $csst3, 
					'csst4' 		=> $csst4, 
					'reject' 		=> $reject, 
					'D50' 			=> $D50, 
					'D100' 			=> $D100, 
					'T50' 			=> $T50, 
					'T100' 			=> $T100, 
					'CSST1_50'		=> $CSST1_50, 
					'CSST1_100'		=> $CSST1_100, 
					'CSST2_50' 		=> $CSST2_50, 
					'CSST2_100'		=> $CSST2_100, 
					'CSST3_50' 		=> $CSST3_50, 
					'CSST3_100'		=> $CSST3_100, 
					'CSST4_50' 		=> $CSST4_50, 
					'CSST4_100' 	=> $CSST4_100, 
					'RJT50' 		=> $RJT50, 
					'RJT100' 		=> $RJT100, 
					'TOTALA' 		=> ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)), 
					'CSST1B50' 		=> $CSST1B50, 
					'CSST1B100' 	=> $CSST1B100, 
					'TOTALB' 		=> ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)), 
					'selisih' 		=> ($selisih=="" ? "-" : $this->rupiah($selisih)), 
					'now_time' 		=> $format_now_time, 
					'now_ctr' 		=> $ctr1, 
					'now_D50' 		=> (intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr1 * $row->pcs_50000)), 
					'now_D100' 		=> (intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr1 * $row->pcs_100000)), 
					'now_T50' 		=> ((intval($row->pcs_50000)==0 ? 0 : $this->rupiah($ctr1 * $row->pcs_50000 * 50))), 
					'now_T100' 		=> ((intval($row->pcs_100000)==0 ? 0 : $this->rupiah($ctr1 * $row->pcs_100000 * 100))), 
					'now_total' 	=> $this->rupiah(($ctr1*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*(intval($row->pcs_50000)!==0 ? 50 : 100))), 
					'keterangan' 	=> $ket
				);
			}else if($act=="CRM") { 
				
				$TOTALA = 50*(intval(json_decode($data->cart_1_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_1_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_1_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_1_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_2_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_2_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_2_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_2_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_3_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_3_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_3_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_3_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_4_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_4_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_4_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_4_no, true)['100'])) +
						  50*(intval(json_decode($data->cart_5_no, true)['50'])==0 ? 0 : intval(json_decode($data->cart_5_no, true)['50'])) +
						  100*(intval(json_decode($data->cart_5_no, true)['100'])==0 ? 0 : intval(json_decode($data->cart_5_no, true)['100'])) +
						  50*(intval(json_decode($data->div_no, true)['50'])==0 ? 0 : intval(json_decode($data->div_no, true)['50'])) +
						  100*(intval(json_decode($data->div_no, true)['100'])==0 ? 0 : intval(json_decode($data->div_no, true)['100']));
				
				$TOTALB = str_replace(".", "", $data->return_crm_cashout)/1000;
				// $data->return_crm_balance = "105.550.000";
				$CCRM = str_replace(".", "", $data->return_crm_balance)/1000;
				
				
				$t50 = ($ctr2 * (intval($data2->s50k)==0 ? 0 : $data2->s50k))*50;
				$t100 = ($ctr2 * (intval($data2->s100k)==0 ? 0 : $data2->s100k))*100;
				$selisih = ($TOTALA+$TOTALB)-($t50+$t100);
				
				// echo "A : ".($TOTALA)."<br>";
				// echo "B : ".($TOTALB)."<br>";
				// echo "AB : ".($TOTALA+$TOTALB)."<br>";
				
				$data_prev[] = array(
					'no' => $no,
					'date' => $date,
					'wsid' => $row->wsid,
					'lokasi' => $row->lokasi,
					'type' => $row->type,
					'tanggal'		=> date("Y-m-d", strtotime($row2->jam_cash_in)), 
					'time' 			=> date("H:i", strtotime($row2->jam_cash_in)), 
					'ctr' => $ctr2,
					'T50' => $t50,
					'T100' => $t100,
					'total' => ($ctr2*($row2->pcs_50000!=="" ? $row2->pcs_50000 : $row2->pcs_100000)*($row2->pcs_50000!=="" ? 50 : 100)),
					'csst1' => $data2->cart_1_no,
					'csst2' => $data2->cart_2_no,
					'csst3' => $data2->cart_3_no,
					'csst4' => $data2->cart_4_no,
					'csst5' => $data2->cart_5_no,
					'reject' => (isset($data2->div_no) ? $data2->div_no : 0),
					'D50' => (intval($data2->s50k)==0 ? "" : $this->rupiah($data2->s50k)),	
					'D100' => (intval($data2->s100k)==0 ? "" : $this->rupiah($data2->s100k)),
					'T50' => ((intval($data2->s50k)==0 ? 0 : $this->rupiah($data2->s50k * 50))),
					'T100' => ((intval($data2->s100k)==0 ? "-" : $this->rupiah($data2->s100k * 100))),
					'CSST1_50' => intval(json_decode($data->cart_1_no, true)['50']),
					'CSST1_100' => intval(json_decode($data->cart_1_no, true)['100']),
					'CSST2_50' => intval(json_decode($data->cart_2_no, true)['50']),
					'CSST2_100' => intval(json_decode($data->cart_2_no, true)['100']),
					'CSST3_50' => intval(json_decode($data->cart_3_no, true)['50']),
					'CSST3_100' => intval(json_decode($data->cart_3_no, true)['100']),
					'CSST4_50' => intval(json_decode($data->cart_4_no, true)['50']),
					'CSST4_100' => intval(json_decode($data->cart_4_no, true)['100']),
					'CSST5_50' => intval(json_decode($data->cart_5_no, true)['50']),
					'CSST5_100' => intval(json_decode($data->cart_5_no, true)['100']),
					'RJT50' => intval(json_decode($data->div_no, true)['50']),
					'RJT100' => intval(json_decode($data->div_no, true)['100']),
					'TOTALA' =>  ($TOTALA=="" ? "-" : $this->rupiah($TOTALA)),
					'TOTALB' => ($TOTALB=="" ? "-" : $this->rupiah($TOTALB)),
					'CCRM' => ($CCRM=="" ? "-" : $this->rupiah($CCRM)),
					// 'selisih' => $selisih,
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
			
			$dispensed = 0;
		}
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
		
		// $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// $no = 0;
		// foreach($result as $row) {
			// $no++;
			// if($row->cpc_process!=="") {
				// $data = json_decode($row->cpc_process);
			// } else {
				// $data = json_decode($row->data_solve);
			// }
			
			// list($date, $time) = explode(" ", $row->updated_date);
			
			// $postArr = json_decode($data->data_seal, true);
			// $postArr = array_map('array_filter', $postArr);
			// $postArr = array_filter($postArr);
			
			// $total_20 = 0;
			// $total_50 = 0;
			// $total_100 = 0;
			// foreach ($postArr as $item) {
				// $total_20 += (empty($item['20']) ? 0 : $item['20']);
				// $total_50 += (empty($item['50']) ? 0 : $item['50']);
				// $total_100 += (empty($item['100']) ? 0 : $item['100']);
			// }
			
			// $mutasi = ($total_100*100)+($total_50*50)+($total_20*20);
			// $counter = ($data->return_cdm_denom100*100)+($data->return_cdm_denom50*50)+($data->return_cdm_denom20*20);
			
			// $data_prev2[] = array(
				// 'no' => $no,
				// 'date' => $date,
				// 'wsid' => $row->wsid,
				// 'lokasi' => $row->lokasi,
				// 'lbr_100' => $total_100,
				// 'lbr_50' => $total_50,
				// 'lbr_20' => $total_20,
				// 'fisik_100' => $total_100*100,
				// 'fisik_50' => $total_50*50,
				// 'fisik_20' => $total_20*20,
				// 'mutasi_kredit' => $mutasi,
				// 'document_counter' => $counter,
				// 'selisih' => $mutasi-$counter,
			// );
		// }
		
		// if(!empty($data_prev)) {
			// $this->data['table'] = $this->show_table($data_prev);
			// $this->data['table2'] = $this->show_table2($data_prev2);
		// } else {
			// $this->data['table'] = "<center>SORRY, NO DATA!</center>";
		// }
		
		// echo $this->show_table($data_prev);
		// $htmlString = $this->show_table($data_prev);
		
		$table = "<table><tr><td>AAAAAA</td></tr></table>";
	
		$htmlString = $this->show_table($data_prev);
		
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
		$spreadsheet = $reader->loadFromString($htmlString);
		$sheet = $spreadsheet->getActiveSheet();
		
		// $i = 0;
		// $array = array();
		// foreach ($sheet->getRowIterator() as $row) {
			// $cellIterator = $row->getCellIterator();
			// $cellIterator->setIterateOnlyExistingCells(FALSE);
			// foreach ($cellIterator as $cell) {
				// if($i==1) {
					// array_push($array, $cell->getColumn());
				// }
			// }
			// $i++;
		// }
		
		// $styleArray = [
			// 'borders' => [
				// 'allBorders' => [
					// 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					// 'color' => ['argb' => '00000000'],
				// ]
			// ],
		// ];
		
		// $border_style = current($array).'5'.':'.end($array).'44';
		// $spreadsheet->getActiveSheet()->getStyle($border_style)->applyFromArray($styleArray);
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
		
		
		if(isset($_REQUEST['html'])) {
			echo $htmlString;
		} else {
			$filename = 'report';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
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
	
	public function show_table($data) {
		$show_hide = ($_REQUEST['rekon_show_hode']=="hide" ? "hidden" : "");
		
		// echo "<script>alert('".$show_hide."')</script>";
		
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
					<td align="center" '.$show_hide.'>'.$r['tanggal'].'</td>
					<td align="center" '.$show_hide.'>'.$r['ctr'].'</td>
					<td align="center" '.$show_hide.'>'.$r['D50'].'</td>
					<td align="right"  '.$show_hide.'>'.$r['T50'].'</td>
					<td align="center" '.$show_hide.'>'.$r['D100'].'</td>
					<td align="right"  '.$show_hide.' style="background-color: #8db4e2">'.$r['T100'].'</td>
					<td align="center" '.$show_hide.'>'.$r['CSST1_50'].'</td>
					<td align="center" '.$show_hide.' style="background-color: #92d050">'.$r['CSST1_100'].'</td>
					<td align="center" '.$show_hide.'>'.$r['CSST2_50'].'</td>
					<td align="center" '.$show_hide.' style="background-color: #92d050">'.$r['CSST2_100'].'</td>
					<td align="center" '.$show_hide.'>'.$r['CSST3_50'].'</td>
					<td align="center" '.$show_hide.' style="background-color: #92d050">'.$r['CSST3_100'].'</td>
					<td align="center" '.$show_hide.'>'.$r['CSST4_50'].'</td>
					<td align="center" '.$show_hide.' style="background-color: #92d050">'.$r['CSST4_100'].'</td>
					<td align="center" '.$show_hide.'>'.$r['CSST5_50'].'</td>
					<td align="center" '.$show_hide.' style="background-color: #92d050">'.$r['CSST5_100'].'</td>
					<td align="center" '.$show_hide.'>'.$r['RJT50'].'</td>
					<td align="center" '.$show_hide.' style="background-color: #92d050">'.$r['RJT100'].'</td>
					<td align="right"  '.$show_hide.' style="background-color: #8db4e2">'.$r['TOTALA'].'</td>
					<td align="center" '.$show_hide.'>'.$r['CSST1B50'].'</td>
					<td align="center" '.$show_hide.' style="background-color: #92d050">'.$r['CSST1B100'].'</td>
					<td align="center" '.$show_hide.'></td>
					<td align="center" '.$show_hide.' style="background-color: #92d050"></td>
					<td align="center" '.$show_hide.'></td>
					<td align="center" '.$show_hide.' style="background-color: #92d050"></td>
					<td align="center" '.$show_hide.'></td>
					<td align="center" '.$show_hide.' style="background-color: #92d050"></td>
					<td align="center" '.$show_hide.'></td>
					<td align="center" '.$show_hide.' style="background-color: #92d050"></td>
					<td align="right"  '.$show_hide.' style="background-color: #8db4e2">'.$r['TOTALB'].'</td>
					<td align="center">'.$CCRM.'</td>
					<td align="right" style="background-color: #8db4e2">'.($r['cancel']!==null ? "(".$r['cancel'].")" : "").' '.$selisih_crm.'</td>
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
			<tr '.$show_hide.'>
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
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_csst5_50).'</td>
				<td align="right" style="background-color: #dce6f1">'.$this->rupiah2($total_csst5_100).'</td>
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
							<th style="vertical-align: middle" colspan="30" '.$show_hide.'>REKONSILIASI</th>
							<th style="vertical-align: middle" rowspan="4">Counter<br>(khusus CRM)<br>(x1000)</th>
							<th style="vertical-align: middle" rowspan="'.($show_hide=="hidden" ? "4" : "2").'">Selisih</th>
							<th style="vertical-align: middle" rowspan="4">Time</th>
							<th style="vertical-align: middle" colspan="4">PENGISIAN BERIKUTNYA</th>
							<th style="vertical-align: middle" rowspan="4" width="10%">KET <br>NAIK/TURUN LIMIT</th>
						</tr>
						<tr>
							<th style="vertical-align: middle" colspan="6" '.$show_hide.'>PENGISIAN SEBELUMNYA</th>
							<th style="vertical-align: middle" colspan="13" '.$show_hide.'>PERHITUNGAN FISIK UANG</th>
							<th style="vertical-align: middle" colspan="11" '.$show_hide.'>PERHITUNGAN DISPENSED COUNTER</th>
							<th style="vertical-align: middle" rowspan="3">Jml Csst</th>
							<th style="vertical-align: middle" rowspan="2" colspan="2">Jml Isi</th>
							<th style="vertical-align: middle" rowspan="3">Total</th>
						</tr>
						<tr>
							<th style="vertical-align: middle"rowspan="2" '.$show_hide.'>TANGGAL</th>
							<th style="vertical-align: middle"rowspan="2" '.$show_hide.'>JML CSST</th>
							<th style="vertical-align: middle"width="200px" '.$show_hide.' colspan="4">JML ISI</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>CSST 1</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>CSST 2</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>CSST 3</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>CSST 4</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>CSST 5</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>RJT.</th>
							<th style="vertical-align: middle" '.$show_hide.'>TOTAL RP</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>CSST 1</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>CSST 2</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>CSST 3</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>CSST 4</th>
							<th style="vertical-align: middle"colspan="2" '.$show_hide.'>RJT.</th>
							<th style="vertical-align: middle" '.$show_hide.'>TOTAL RP</th>
							<th style="vertical-align: middle" '.$show_hide.'>TOTAL RP</th>
						</tr>
						<tr>
							<th style="vertical-align: middle" '.$show_hide.'  colspan="2">50</th>
							<th style="vertical-align: middle" '.$show_hide.' colspan="2">100</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>(x1,000)</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>50</th>
							<th style="vertical-align: middle" '.$show_hide.'>100</th>
							<th style="vertical-align: middle" '.$show_hide.'>(x1,000)</th>
							<th style="vertical-align: middle" '.$show_hide.'>(x1,000)</th>
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
}