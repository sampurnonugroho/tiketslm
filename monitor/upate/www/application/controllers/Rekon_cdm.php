<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Rekon_cdm extends CI_Controller {
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
	
	public function show() {
		$act = $this->uri->segment(3);
		
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
			
			$data_prev[] = array(
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
		
		
		
		if($act=="html") {
			echo "<pre>";
			print_r($postArr);
			echo "<br>";
			print_r($data);
			echo "<br>";
			print_r($data_prev);
			echo "<br>";
		} else {
			if(!empty($data_prev)) {
				$this->data['table'] = $this->show_table($data_prev);
			} else {
				$this->data['table'] = "<center>SORRY, NO DATA!</center>";
			}
			
			echo $this->data['table'];
		}
	}
	
	public function show_table($data) {
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
	
	public function show_table2($data) {
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
	
	
	public function rupiah($s) {
		$s = str_replace(".","",$s);
		$a = ($s==0 ? "" : number_format($s, 0, ",", ","));
		return number_format($s, 0, ",", ",");
	}
	
	public function rupiah2($s) {
		$a = ($s==0 ? 0 : number_format($s, 0, ",", ","));
		return $a;
	}
}