<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Troubleshoot_report extends CI_Controller {
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
		echo "<ul style='text-align: center'>";
		echo "<li><a href='".base_url()."troubleshoot_report/flm_report'>FLM REPORT</a></li>";
		echo "<li><a href='".base_url()."troubleshoot_report/slm_report'>SLM REPORT</a></li>";
		echo "</ul>";
    }
	
	public function flm_report() {
		$html_template = '';
		$html_content = '';
		$html_footer = '';
		
		$html_content .= '
			<tr>
				<td>1</td>
				<td></td>
				<td></td>
			</tr>
		';
		
		$html_footer .= '
			<tr>
				
			</tr>
		';
		
		$html_template .= '
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
							<th style="vertical-align: middle; text-align: center">NO</th>
							<th style="vertical-align: middle; text-align: center">EQUIPMENT ADDRESS</th>
							<th style="vertical-align: middle; text-align: center">Serial Number / T-ID</th>
							<th style="vertical-align: middle; text-align: center">Pengelola</th>
							<th style="vertical-align: middle; text-align: center">No. Tiket</th>
							<th style="vertical-align: middle; text-align: center">No. Job Card </th>
							<th style="vertical-align: middle; text-align: center">Problem</th>
							<th style="vertical-align: middle; text-align: center">Description</th>
							<th style="vertical-align: middle; text-align: center">Action Taken</th>
							<th style="vertical-align: middle; text-align: center">Entry Date</th>
							<th style="vertical-align: middle; text-align: center">Call in / Email Time</th>
							<th style="vertical-align: middle; text-align: center">Estimate Time Arrival / Appoinment</th>
							<th style="vertical-align: middle; text-align: center">Arrival Date</th>
							<th style="vertical-align: middle; text-align: center">Arrive Time</th>
							<th style="vertical-align: middle; text-align: center">Start Date</th>
							<th style="vertical-align: middle; text-align: center">Start Time</th>
							<th style="vertical-align: middle; text-align: center">Close Date</th>
							<th style="vertical-align: middle; text-align: center">Close Time</th>
							<th style="vertical-align: middle; text-align: center">Response Time</th>
							<th style="vertical-align: middle; text-align: center">Minute</th>
							<th style="vertical-align: middle; text-align: center">Repair time</th>
							<th style="vertical-align: middle; text-align: center">Minute</th>
							<th style="vertical-align: middle; text-align: center">Resolution Time</th>
							<th style="vertical-align: middle; text-align: center">Minute</th>
							<th style="vertical-align: middle; text-align: center">DT (%)</th>
							<th style="vertical-align: middle; text-align: center">Up time</th>
						</tr>
					</thead>
					<tbody>
						'.$html_content.'
					</tbody>
					<tfoot>
						'.$html_footer.'
					</tfoot>
				</table>
			</div>
		';
	
		echo $html_template;
	}
	
	public function slm_report() {
		$html_template = '';
		$html_content = '';
		$html_footer = '';
		
		$html_content .= '
			<tr>
				<td>1</td>
				<td></td>
				<td></td>
			</tr>
		';
		
		$html_footer .= '
			<tr>
				
			</tr>
		';
		
		$html_template .= '
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
							<th style="vertical-align: middle; text-align: center">NO</th>
							<th style="vertical-align: middle; text-align: center">EQUIPMENT ADDRESS</th>
							<th style="vertical-align: middle; text-align: center">Serial Number / T-ID</th>
							<th style="vertical-align: middle; text-align: center">Pengelola</th>
							<th style="vertical-align: middle; text-align: center">No. Tiket</th>
							<th style="vertical-align: middle; text-align: center">No. Job Card </th>
							<th style="vertical-align: middle; text-align: center">Problem</th>
							<th style="vertical-align: middle; text-align: center">Description</th>
							<th style="vertical-align: middle; text-align: center">Action Taken</th>
							<th style="vertical-align: middle; text-align: center">Entry Date</th>
							<th style="vertical-align: middle; text-align: center">Call in / Email Time</th>
							<th style="vertical-align: middle; text-align: center">Estimate Time Arrival / Appoinment</th>
							<th style="vertical-align: middle; text-align: center">Arrival Date</th>
							<th style="vertical-align: middle; text-align: center">Arrive Time</th>
							<th style="vertical-align: middle; text-align: center">Start Date</th>
							<th style="vertical-align: middle; text-align: center">Start Time</th>
							<th style="vertical-align: middle; text-align: center">Close Date</th>
							<th style="vertical-align: middle; text-align: center">Close Time</th>
							<th style="vertical-align: middle; text-align: center">Response Time</th>
							<th style="vertical-align: middle; text-align: center">Minute</th>
							<th style="vertical-align: middle; text-align: center">Repair time</th>
							<th style="vertical-align: middle; text-align: center">Minute</th>
							<th style="vertical-align: middle; text-align: center">Resolution Time</th>
							<th style="vertical-align: middle; text-align: center">Minute</th>
							<th style="vertical-align: middle; text-align: center">DT (%)</th>
							<th style="vertical-align: middle; text-align: center">Up time</th>
						</tr>
					</thead>
					<tbody>
						'.$html_content.'
					</tbody>
					<tfoot>
						'.$html_footer.'
					</tfoot>
				</table>
			</div>
		';
	
		echo $html_template;
	}
	
	public function rupiah($s) {
		$a = ($s==0 ? "" : number_format($s, 0, ",", ","));
		return number_format($s, 0, ",", ",");
	}
	
	public function rupiah2($s) {
		$a = ($s==0 ? "-" : number_format($s, 0, ",", ","));
		return $a;
	}
}