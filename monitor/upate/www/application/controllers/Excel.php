<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
 
class Excel extends CI_Controller {
    
    public function index()
    {       
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        
        $writer = new Xlsx($spreadsheet);
 
        $filename = 'name-of-the-generated-file.xlsx';
 
        $writer->save($filename); // will create and save the file in the root of the project
 
    }
 
    public function download()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        
        $writer = new Xlsx($spreadsheet);
 
        $filename = 'name-of-the-generated-file';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
 
    }
	
	public function generate() {
		$helper = new Sample();
		
		$reader = IOFactory::createReader('Xls');
		$spreadsheet = $reader->load('template.xls');
		
		// SHEET A
		$spreadsheet->getActiveSheet()->setCellValue('A1', 'RUNSHEET A');
		$spreadsheet->getActiveSheet()->setTitle("AAA");
		
		// SHEET B
		$clonedSheet = clone $spreadsheet->getActiveSheet();
		$clonedSheet->setCellValue('A1', 'RUNSHEET B');
		$clonedSheet->setTitle("BBB");
		$spreadsheet->addSheet($clonedSheet);
		
		// SHEET C
		$clonedSheet = clone $spreadsheet->getActiveSheet();
		$clonedSheet->setCellValue('A1', 'RUNSHEET C');
		$clonedSheet->setTitle("CCC");
		$spreadsheet->addSheet($clonedSheet);
		
		
		$filename = 'runsheet';
		
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
	
	public function print_xls() {
		error_reporting(0);
		$id_ct = $this->uri->segment(3);
		
		$query = "
			SELECT 
				cashtransit.id,
				cashtransit.date,
				branch.name as branch,
				runsheet_operational.run_number as ga,
				runsheet_security.police_number,
				runsheet_security.km_status,
				runsheet_logistic.data,
				sum(runsheet_cashprocessing.total) as petty_cash,
				runsheet_operational.custodian_1,
				runsheet_operational.custodian_2,
				runsheet_security.security_1,
				runsheet_security.security_2
			FROM cashtransit
				LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit)
				LEFT JOIN runsheet_operational ON(cashtransit.id=runsheet_operational.id_cashtransit)
				LEFT JOIN runsheet_logistic ON(cashtransit.id=runsheet_logistic.id_cashtransit)
				LEFT JOIN runsheet_security ON(cashtransit.id=runsheet_security.id_cashtransit)
				LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id)
				LEFT JOIN master_branch as branch ON(cashtransit.branch=branch.id)
			WHERE cashtransit.id='".$id_ct."'
			GROUP BY cashtransit_detail.id_cashtransit
		";
		
		$runsheet = $this->db->query($query);
		
		$reader = IOFactory::createReader('Xls');
		$spreadsheet = $reader->load('template.xls');
		
		$baseRow = 5;
		$i = 0;
		$row = 0;
		foreach($runsheet->result() as $k => $r) {
			if($k==0) {
				$custodian_1 = !empty($r->custodian_1) ? $this->db->query("SELECT nama FROM karyawan WHERE nik='".$r->custodian_1."'")->row()->nama : "N/A";
				$custodian_2 = !empty($r->custodian_2) ? $this->db->query("SELECT nama FROM karyawan WHERE nik='".$r->custodian_2."'")->row()->nama : "N/A";
				
				
				$spreadsheet->getActiveSheet()->setCellValue('D3', $r->branch)
											  ->setCellValue('D4', $r->ga)
											  ->setCellValue('E5', $r->police_number)
											  ->setCellValue('D6', $r->km_status)
											  ->setCellValue('I6', $r->petty_cash)
											  ->setCellValue('N3', $custodian_1)
											  ->setCellValue('N4', $custodian_2)
											  ->setCellValue('N5', $r->security_1)
											  ->setCellValue('N6', $r->security_2);
											  
				$i = 3;
				foreach(json_decode($r->data) as $k => $rs) {
					$name= $this->db->query("SELECT name FROM inventory WHERE id='".$k."'")->row()->name;
					$qty = $rs;
					$spreadsheet->getActiveSheet()->setCellValue('H'.$i, $name)
												  ->setCellValue('I'.$i, $qty);
					$i++;
				}
				
				
				$query_detail = "
					SELECT 
						cashtransit.id,
						cashtransit_detail.id as id_ticket,
						cashtransit_detail.id_bank,
						cashtransit_detail.jenis,
						client.sektor as ga,
						branch.name as branch_name,
						client.bank,
						client.type,
						client.lokasi,
						client.vendor as brand,
						client.type_mesin as model,
						client.type_mesin as model,
						client.ctr,
						runsheet_cashprocessing.total nominal,
						runsheet_cashprocessing.bag_seal,
						runsheet_cashprocessing.bag_no,
						runsheet_cashprocessing.pcs_100000,
						runsheet_cashprocessing.pcs_50000
					FROM cashtransit
						LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit)
						LEFT JOIN runsheet_operational ON(cashtransit.id=runsheet_operational.id_cashtransit)
						LEFT JOIN runsheet_logistic ON(cashtransit.id=runsheet_logistic.id_cashtransit)
						LEFT JOIN runsheet_security ON(cashtransit.id=runsheet_security.id_cashtransit)
						LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id)
						LEFT JOIN client ON(cashtransit_detail.id_bank=client.id)
						LEFT JOIN master_branch as branch ON(cashtransit.branch=branch.id)
					WHERE cashtransit.id='".$id_ct."'
				";
				$runsheet_detail = $this->db->query($query_detail);
				
				$baseRow2 = 10;
				$no2 = 0;
				foreach($runsheet_detail->result() as $rx) {
					$row2 = $baseRow2 + $no2;
					$spreadsheet->getActiveSheet()->insertNewRowBefore($row2+1	,1);
					
					if($rx->jenis=="DELIVERY") {
						$s100k = "N/A";
						$s50k = "N/A";
						$cart = "N/A";
					} else {
						$s100k = $rx->pcs_100000;
						$s50k = $rx->pcs_50000;
						$cart = $rx->ctr;
					}
					
					
					$spreadsheet->getActiveSheet()->setCellValue('B'.$row2, $rx->id)
												  ->setCellValue('C'.$row2, $rx->branch_name)
												  ->setCellValue('D'.$row2, $rx->ga)
												  ->setCellValue('E'.$row2, $rx->bank)
												  ->setCellValue('F'.$row2, $rx->jenis)
												  ->setCellValue('G'.$row2, $rx->brand)
												  ->setCellValue('H'.$row2, $rx->model)
												  ->setCellValue('I'.$row2, $rx->lokasi)
												  ->setCellValue('J'.$row2, $s100k)
												  ->setCellValue('K'.$row2, $s50k)
												  ->setCellValue('L'.$row2, $cart)
												  ->setCellValue('M'.$row2, $rx->nominal)
												  ->setCellValue('N'.$row2, $rx->bag_seal)
												  ->setCellValue('O'.$row2, $rx->bag_no);
					
					$no2++;
				}
				
				$spreadsheet->getActiveSheet()->removeRow($row2+1,1);
				
				$spreadsheet->getActiveSheet()->setTitle($r->date."(".$r->ga.")");
			} else {
				$clonedSheet = clone $spreadsheet->getActiveSheet();
				
				$clonedSheet->setTitle($r->date);
				$spreadsheet->addSheet($clonedSheet);
			}
		}
		
		$filename = 'runsheet';
		
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
	
	public function export_cpc_xls() {
		$query = "SELECT * FROM cpc_record";
		
		$data_record = $this->db->query($query)->result();
		
		$reader = IOFactory::createReader('Xls');
		$spreadsheet = $reader->load('report_cpc_record.xls');
		
		$baseRow = 3;
		$i = 0;
		$row = 0;
		$tgl = "";
		
		$prev_saldo_100 = 0;
		$prev_saldo_50 = 0;
		$saldo_100 = 0;
		$saldo_50 = 0;
		$saldo = 0;
		foreach($data_record as $k => $r) {
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
			
			$saldo = $saldo_100 + $saldo_50;
			
			if($i==1) {
				$tgl = date("M-y", strtotime($r->tanggal));	
			}
			$row = $baseRow + $i;
			$spreadsheet->getActiveSheet()->insertNewRowBefore($row+1,1);
			
			$spreadsheet->getActiveSheet()->setCellValue('A'.$row, ($i+1));
			if($r->keterangan!=="saldo_awal") {
				$spreadsheet->getActiveSheet()->setCellValue('B'.$row, date("d-M-y", strtotime($r->tanggal)));
				$spreadsheet->getActiveSheet()->setCellValue('D'.$row, ($r->catatan=="-" ? "" : $r->catatan));
				$spreadsheet->getActiveSheet()->setCellValue('E'.$row, strtoupper(str_replace("_", " ", $r->keterangan)));
				$spreadsheet->getActiveSheet()->setCellValue('F'.$row, ($r->debit_100==0 ? "" : $r->debit_100));
				$spreadsheet->getActiveSheet()->setCellValue('G'.$row, ($r->kredit_100==0 ? "" : $r->kredit_100));
				$spreadsheet->getActiveSheet()->setCellValue('H'.$row, ($saldo_100==0 ? "" : $saldo_100));
				$spreadsheet->getActiveSheet()->setCellValue('I'.$row, ($r->debit_50==0 ? "" : $r->debit_50));
				$spreadsheet->getActiveSheet()->setCellValue('J'.$row, ($r->kredit_50==0 ? "" : $r->kredit_50));
				$spreadsheet->getActiveSheet()->setCellValue('K'.$row, ($saldo_50==0 ? "" : $saldo_50));
				$spreadsheet->getActiveSheet()->setCellValue('L'.$row, $saldo);
			} else {
				// $spreadsheet->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal('center');
				$spreadsheet->getActiveSheet()->setCellValue('E'.$row, "SALDO AWAL");
				$spreadsheet->getActiveSheet()->setCellValue('F'.$row, ($r->debit_100==0 ? "" : $r->debit_100));
				$spreadsheet->getActiveSheet()->setCellValue('G'.$row, ($r->kredit_100==0 ? "" : $r->kredit_100));
				$spreadsheet->getActiveSheet()->setCellValue('H'.$row, ($saldo_100==0 ? "" : $saldo_100));
				$spreadsheet->getActiveSheet()->setCellValue('I'.$row, ($r->debit_50==0 ? "" : $r->debit_50));
				$spreadsheet->getActiveSheet()->setCellValue('J'.$row, ($r->kredit_50==0 ? "" : $r->kredit_50));
				$spreadsheet->getActiveSheet()->setCellValue('K'.$row, ($saldo_50==0 ? "" : $saldo_50));
				$spreadsheet->getActiveSheet()->setCellValue('L'.$row, $saldo);
			}
			
			$i++;
			
			$prev_saldo_100 = $saldo_100;
			$prev_saldo_50 = $saldo_50;
		}
		
		$spreadsheet->getActiveSheet()->setCellValue('E'.($i+5), $tgl);
		$spreadsheet->getActiveSheet()->setCellValue('L'.($i+5), $saldo);
		
		$spreadsheet->getActiveSheet()->removeRow($row+1,1);
		
		$filename = 'STOCK_OPNAME_'.date("M Y");
		
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