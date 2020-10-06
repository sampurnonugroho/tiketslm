<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Jurnal extends CI_Controller {
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
		$this->data['active_menu'] = "jurnal";
		
		$query = "SELECT 
						*, jurnal.keterangan as keterangan_jurnal
					FROM jurnal 
					LEFT JOIN cashtransit_detail ON(cashtransit_detail.id=jurnal.id_detail)
					LEFT JOIN client ON(client.id=cashtransit_detail.id_bank)
					#WHERE cashtransit_detail.cpc_process != ''
					ORDER BY jurnal.tanggal, jurnal.id_detail, client.wsid ASC";
		$this->data['data_record'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		return view('admin/jurnal/index', $this->data);
    }
	
	public function detail() {
		$this->data['active_menu'] = "jurnal";
		
		$query = "SELECT 
						*, jurnal.id as id_jurnal, jurnal.keterangan as keterangan_jurnal
					FROM jurnal 
					LEFT JOIN cashtransit_detail ON(cashtransit_detail.id=jurnal.id_detail)
					LEFT JOIN client ON(client.id=cashtransit_detail.id_bank)
					ORDER BY jurnal.id_detail, client.wsid ASC";
		$this->data['data_record'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		return view('admin/jurnal/index_detail', $this->data);
	}
	
    public function add() {    
		$this->data['active_menu'] = "jurnal";
		$this->data['url'] = "jurnal/save";
		$this->data['flag'] = "add";
		$this->data['newdata'] = true;
		
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM jurnal";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>"SELECT SQL_CALC_FOUND_ROWS * FROM jurnal"), array(CURLOPT_BUFFERSIZE => 10)));
		
		$total_rows = count($result);
		
		if($total_rows==0) {
			$this->data['newdata'] = true;
		} else {
			$this->data['newdata'] = false;
		}
		
		$this->data['id'] = "";
		$this->data['tanggal'] = "";
		$this->data['catatan'] = "";
		$this->data['keterangan'] = "";
		$this->data['posisi'] = "";
		$this->data['debit_100'] = "";
		$this->data['debit_50'] = "";
		$this->data['kredit_100'] = "";
		$this->data['kredit_50'] = "";
		
		// echo "<pre>";
		// print_r($result);
		// print_r($total_rows);
		// echo "</pre>";
		
		return view('admin/jurnal/form', $this->data);
    }
	
    public function edit($id) {    
		$this->data['active_menu'] = "jurnal";
		$this->data['url'] = "jurnal/update";
		$this->data['flag'] = "edit";
		$this->data['newdata'] = true;
		
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM jurnal WHERE id = '$id'";
		$row = $this->db->query($query)->row();
		$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
		$rows = $this->db->query($sql)->row_array();
		$total_rows = $rows['found_rows'];
		
		if($row->keterangan=="saldo_awal") {
			$this->data['newdata'] = true;
		} else {
			$this->data['newdata'] = false;
		}
		
		$this->data['id'] = $id;
		$this->data['tanggal'] = $row->tanggal;
		$this->data['catatan'] = $row->catatan;
		$this->data['keterangan'] = $row->keterangan;
		$this->data['posisi'] = $row->posisi;
		$this->data['debit_100'] = ($row->debit_100==0 ? "" : $row->debit_100);
		$this->data['debit_50'] = ($row->debit_50==0 ? "" : $row->debit_50);
		$this->data['kredit_100'] = ($row->kredit_100==0 ? "" : $row->kredit_100);
		$this->data['kredit_50'] = ($row->kredit_50==0 ? "" : $row->kredit_50);
		
		// echo "<pre>";
		// print_r($total_rows);
		// echo "</pre>";
		
		return view('admin/jurnal/form', $this->data);
    }
	
	public function save() {
		print_r($_REQUEST);
		$keterangan = trim($this->input->post('keterangan'));
		
		$data = array();
		if($keterangan=="saldo_awal") {
			$data['keterangan'] = $keterangan;
			$data['posisi'] = "debit";
			$data['debit_100'] = trim($this->input->post('debit_100'));
			$data['debit_50'] = trim($this->input->post('debit_50'));
			// $data['saldo_100'] = $data['debit_100'];
			// $data['saldo_50'] = $data['debit_50'];
			// $data['saldo'] = $data['debit_100'] + $data['debit_50'];
			
			$result = $this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM jurnal")->result();
			$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
			$total_rows = $rows['found_rows'];
			
			if($total_rows==0) {
				$this->db->trans_start();

				$this->db->insert('jurnal', $data);

				$this->db->trans_complete();
			}
		} else {
			$posisi = trim($this->input->post('posisi'));
			
			$data['tanggal'] = trim($this->input->post('tanggal'));
			$data['catatan'] = trim($this->input->post('catatan'));
			$data['keterangan'] = $keterangan;
			$data['posisi'] = $posisi;
			
			if($posisi=="kredit") {
				// $saldo = $this->db->query("SELECT saldo_100, saldo_50, saldo FROM jurnal ORDER BY id DESC")->row_array();
				$data['kredit_100'] = trim($this->input->post('kredit_100'));
				$data['kredit_50'] = trim($this->input->post('kredit_50'));
				// $data['saldo_100'] = $saldo['saldo_100'] - $data['kredit_100'];
				// $data['saldo_50'] = $saldo['saldo_50'] - $data['kredit_50'];
				// $data['saldo'] = $saldo['saldo'] - ($data['kredit_100'] + $data['kredit_50']);
				
				
				// $this->db->trans_start();

				// $this->db->insert('jurnal', $data);

				// $this->db->trans_complete();
				$table = "jurnal";
				$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			} else {
				// $saldo = $this->db->query("SELECT saldo_100, saldo_50, saldo FROM jurnal ORDER BY id DESC")->row_array();
				$data['debit_100'] = trim($this->input->post('debit_100'));
				$data['debit_50'] = trim($this->input->post('debit_50'));
				// $data['saldo_100'] = $saldo['saldo_100'] + $data['debit_100'];
				// $data['saldo_50'] = $saldo['saldo_50'] + $data['debit_50'];
				// $data['saldo'] = $saldo['saldo'] + ($data['debit_100'] + $data['debit_50']);
				
				// $this->db->trans_start();

				// $this->db->insert('jurnal', $data);

				// $this->db->trans_complete();
				$table = "jurnal";
				$this->curl->simple_get(rest_api().'/select/insert', array('table'=>$table, 'data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
			}
			
			// $result = $this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM jurnal")->result();
			// $rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
			// $total_rows = $rows['found_rows'];
		}
		
		// echo "<pre>";
		// print_r($data);
		// print_r($total_rows);
		// print_r($saldo);
		// echo "</pre>";
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('jurnal');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('jurnal');	
		}
	}
	
	public function update() {
		$id 				= trim($this->input->post('id'));
		
		$keterangan = trim($this->input->post('keterangan'));
		if($keterangan=="saldo_awal") {
			$data['keterangan'] = $keterangan;
			$data['posisi'] = "debit";
			$data['debit_100'] = trim($this->input->post('debit_100'));
			$data['debit_50'] = trim($this->input->post('debit_50'));
			// $data['saldo_100'] = $data['debit_100'];
			// $data['saldo_50'] = $data['debit_50'];
			// $data['saldo'] = $data['debit_100'] + $data['debit_50'];
		} else {
			$posisi = trim($this->input->post('posisi'));
			
			$data['tanggal'] = trim($this->input->post('tanggal'));
			$data['catatan'] = trim($this->input->post('catatan'));
			$data['keterangan'] = $keterangan;
			$data['posisi'] = $posisi;
			
			if($posisi=="kredit") {
				// $saldo = $this->db->query("SELECT saldo_100, saldo_50, saldo FROM jurnal ORDER BY id DESC")->row_array();
				$data['kredit_100'] = trim($this->input->post('kredit_100'));
				$data['kredit_50'] = trim($this->input->post('kredit_50'));
				// $data['saldo_100'] = $saldo['saldo_100'] - $data['kredit_100'];
				// $data['saldo_50'] = $saldo['saldo_50'] - $data['kredit_50'];
				// $data['saldo'] = $saldo['saldo'] - ($data['kredit_100'] + $data['kredit_50']);
				
				$this->db->trans_start();

				$this->db->where('id', $id);
				$this->db->update('jurnal', $data);

				$this->db->trans_complete();
			} else {
				// $saldo = $this->db->query("SELECT saldo_100, saldo_50, saldo FROM jurnal ORDER BY id DESC")->row_array();
				$data['debit_100'] = trim($this->input->post('debit_100'));
				$data['debit_50'] = trim($this->input->post('debit_50'));
				// $data['saldo_100'] = $saldo['saldo_100'] + $data['debit_100'];
				// $data['saldo_50'] = $saldo['saldo_50'] + $data['debit_50'];
				// $data['saldo'] = $saldo['saldo'] + ($data['debit_100'] + $data['debit_50']);
				
				$this->db->trans_start();

				$this->db->where('id', $id);
				$this->db->update('jurnal', $data);

				$this->db->trans_complete();
			}
		}
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal tersimpan.');
			redirect('jurnal');	
		} else  {
			$this->session->set_flashdata('success', 'Data tersimpan.');
			redirect('jurnal');	
		}
	}
	
	function delete() {
		$id = $_POST['id'];

		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('jurnal');

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Data gagal dihapus.');
			echo "failed";
		} else  {
			$this->session->set_flashdata('success', 'Data dihapus.');
			echo "success";
		}
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
	
	public function get_data_jurnal() {
		$query = "SELECT *, jurnal.keterangan as keterangan_jurnal
					FROM jurnal 
					LEFT JOIN cashtransit_detail ON(cashtransit_detail.id=jurnal.id_detail)
					LEFT JOIN client ON(client.id=cashtransit_detail.id_bank)
					ORDER BY jurnal.id_detail, client.wsid ASC";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		return $result;
	}
	
	public function export() {
		$data_record = $this->get_data_jurnal();
		// echo $htmlString;
		
		// print_r($data);
		$no = 0;
		$prev_debit_100 = 0;
		$prev_kredit_100 = 0;
		$prev_debit_50 = 0;
		$prev_kredit_50 = 0;
		$prev_saldo_100 = 0;
		$prev_saldo_50 = 0;
		$prev_saldo_20 = 0;
		$prev_saldo = 0;
		$saldo_100 = 0;
		$saldo_50 = 0;
		$saldo = 0;
		$prev_ket = "";
		$html_content = "";
		foreach($data_record as $r) {
			$no++;
			if($r->kredit_100==0) {
				$saldo_100 = $prev_saldo_100 + $r->debit_100;
			} else {
				$saldo_100 = $prev_saldo_100 - $r->kredit_100;
			}
			
			if($r->kredit_50==0) {
				$saldo_50 = $prev_saldo_50 + $r->debit_50;
			} else {
				$saldo_50 = $prev_saldo_50 - $r->kredit_50;
			}
			
			if($r->kredit_20==0) {
				$saldo_20 = $prev_saldo_20 + $r->debit_20;
			} else {
				$saldo_20 = $prev_saldo_20 - $r->kredit_20;
			}
			
			$saldo = $saldo_100 + $saldo_50;
			
			$html_content .= '
				<tr>
					<td>'.$no.'</td>
					<td style="text-align: center">'.($r->tanggal=="0000-00-00" ? "" : date("d-m-Y", strtotime($r->tanggal))).'</td>
					<td style="text-align: center">'.$r->wsid.'</td>
					<td style="text-align: left">'.strtoupper(str_replace("_", " ", $r->keterangan_jurnal)).'</td>
					<td style="text-align: right">'.($r->debit_100==0 ? 0 : number_format($r->debit_100, 0, ",", ",")).'</td>
					<td style="text-align: right">'.($r->kredit_100==0 ? 0 : number_format($r->kredit_100, 0, ",", ",")).'</td>
					<td style="background-color: #b3c984; text-align: right">'.($saldo_100==0 ? 0 : number_format($saldo_100, 0, ",", ",")).'</td>
					<td style="text-align: right">'.($r->debit_50==0 ? 0 : number_format($r->debit_50, 0, ",", ",")).'</td>
					<td style="text-align: right">'.($r->kredit_50==0 ? 0 : number_format($r->kredit_50, 0, ",", ",")).'</td>
					<td style="background-color: #b3c984; text-align: right">'.($saldo_50==0 ? 0 : number_format($saldo_50, 0, ",", ",")).'</td>
					<td style="text-align: right">'.($r->debit_20==0 ? 0 : number_format($r->debit_20, 0, ",", ",")).'</td>
					<td style="text-align: right">'.($r->kredit_20==0 ? 0 : number_format($r->kredit_20, 0, ",", ",")).'</td>
					<td style="background-color: #b3c984; text-align: right">'.($saldo_50==0 ? 0 : number_format($saldo_20, 0, ",", ",")).'</td>
					<td style="text-align: right">'.($saldo==0 ? 0 : number_format($saldo, 0, ",", ",")).'</td>
				</tr>
			';
			
			$prev_debit_100 = $r->debit_100;
			$prev_kredit_100 = $r->kredit_100;
			$prev_debit_50= $r->debit_50;
			$prev_kredit_50 = $r->kredit_50;
			
			$prev_saldo_100 = $saldo_100;
			$prev_saldo_50 = $saldo_50;
			$prev_ket = $r->keterangan;
		}
		
		
		$htmlString = '
			<table id="view_data">
				<thead>
					<tr>
						<th style="text-align:center; vertical-align: center; width: 5px;" rowspan=2>
							No.
						</th>
						<th style="text-align:center; vertical-align: center; width: 12px;" rowspan=2>
							Tanggal
						</th>
						<th style="text-align:center; vertical-align: center; width: 8px;" rowspan=2>
							ID ATM
						</th>
						<th style="text-align:center; vertical-align: center; width: 20px;" rowspan=2>
							Keterangan
						</th>
						<th style="text-align:center; vertical-align: center;" colspan=2>
							DENOM 100 	
						</th>
						<th rowspan=2 style="text-align:center; vertical-align: center; background-color: #b3c984; width: 15px;">
							SALDO 100
						</th>
						<th style="text-align:center; vertical-align: center;" colspan=2>
							DENOM 50 	
						</th>
						<th rowspan=2 style="text-align:center; vertical-align: center; background-color: #b3c984; width: 15px;">
							SALDO 50
						</th>
						<th style="text-align:center; vertical-align: center;" colspan=2>
							DENOM 20 	
						</th>
						<th rowspan=2 style="text-align:center; vertical-align: center; background-color: #b3c984; width: 15px;">
							SALDO 20
						</th>
						<th style="text-align:center; vertical-align: center; width: 15px;" rowspan=2>
							SALDO
						</th>
					</tr>
					<tr>
						<th style="text-align:center; vertical-align: center; width: 15px;">
							DEBET 100 
						</th>
						<th style="text-align:center; vertical-align: center; width: 15px;">
							KREDIT 100 
						</th>
						<th style="text-align:center; vertical-align: center; width: 15px;">
							DEBET 50 
						</th>
						<th style="text-align:center; vertical-align: center; width: 15px;">
							KREDIT 50 
						</th>
						<th style="text-align:center; vertical-align: center; width: 15px;">
							DEBET 20 
						</th>
						<th style="text-align:center; vertical-align: center; width: 15px;">
							KREDIT 20 
						</th>
					</tr>
				</thead>
				<tbody>
					'.$html_content.'
				</tbody>
			</table>';
			
		// echo $htmlString;
		
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
		$spreadsheet = $reader->loadFromString($htmlString);
		$sheet = $spreadsheet->getActiveSheet();
		
		$i = 0;
		$array = array();
		foreach ($sheet->getRowIterator() as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(FALSE);
			foreach ($cellIterator as $cell) {
				if($i==1) {
					array_push($array, $cell->getColumn());
				}
			}
			$i++;
		}
		
		$styleArray = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['argb' => '00000000'],
				]
			],
		];
		
		$border_style = current($array).'1'.':'.end($array).$i;
		$spreadsheet->getActiveSheet()->getStyle($border_style)->applyFromArray($styleArray);
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
		$filename = 'jurnal('.date("Ymd").')';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}
}