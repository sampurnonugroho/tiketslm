<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Mpdf\Mpdf;

class Boc extends CI_Controller {
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
		error_reporting(0);
		$id_ct = $this->uri->segment(3);
		$id_ga = $this->uri->segment(4);
		
		// echo $id_ct." ";
		// echo $id_zona;
		
		$id = $this->uri->segment(3);
		
		// $sql = "select *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail, cashtransit_detail.ctr as jum_ctr FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) WHERE cashtransit_detail.state='ro_atm' AND cashtransit_detail.data_solve='' AND cashtransit.id='$id_ct' AND client.sektor IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit='$id_ct' AND run_number='$id_ga') GROUP BY cashtransit_detail.id ORDER BY cashtransit.id DESC ";
		
		// $result = $this->db->query($sql)->result();
		
		$mpdf = new \Mpdf\Mpdf();

		$html = '
		<html>
			<head>
				<style>
					@page {
						size: auto;
						odd-header-name: html_myHeader1;
						even-header-name: html_myHeader2;
						odd-footer-name: html_myFooter1;
						even-footer-name: html_myFooter2;
						page-break-before: right;
					}
					div.chapter1 {
						page-break-before: right;
					}
				</style>
			</head>

			<body>';
			$html .= '
				<div class="container">
					<div class="row">
						<div class="col-xs-14 col-sm-8">
						<br>
							<table border=1 style="width:100%">
								 <tr height=0 style="display:none">
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								 </tr>
								 
								 <tr height=19 style="height:14.4pt">
								  <td colspan=6 rowspan=5 class=xl69 style="border-right:.5pt solid black;
								  border-bottom:.5pt solid black"><h3>PT. BINTANG JASA ARTHA KELOLA</h3>
								  Jl. Dharmawangsa No.123 <br> Jakarta 12160 - INDONESIA<br>
								  Web : www.bintangjasa.co.id
								  </td>
								  <td colspan=6 rowspan=9 class=xl65>&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=6 rowspan=2 class=xl93 style="border-right:.5pt solid black;
								  border-bottom:.5pt solid black; text-align:center"><h4>BILL OF CARRIAGE</h4></td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=2 class=xl65>TANGGAL</td>
								  <td colspan=2 class=xl65 style="border-left:none">NO.SERI</td>
								  <td colspan=2 rowspan=2 class=xl80 valign="top">AN</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=2 class=xl65>&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=6 rowspan=3 class=xl80>PENGIRIM :</td>
								  <td colspan=6 rowspan=3 class=xl80>PENERIMA :</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=6 class=xl65 style="text-align:center">ALAMAT</td>
								  <td colspan=6 class=xl65 style="border-left:none; text-align:center">ALAMAT</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=6 class=xl82>CLIENT :
								  <br>
								  &nbsp;
								  </td>
								  <td colspan=6 class=xl82 style="border-right:.5pt solid black">CLIENT :
								  <br>
								  &nbsp;
								  </td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=6 rowspan=3 class=xl80>ALAMAT
								  <br>
								  &nbsp;
								  <br>
								  &nbsp;
								  <br>
								  &nbsp;
								  </td>
								  <td colspan=6 rowspan=3 class=xl80>ALAMAT
								  <br>
								  &nbsp;
								  <br>
								  &nbsp;
								  <br>
								  &nbsp;
								  </td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=3 class=xl85 style="border-right:.5pt solid black">KODE POS</td>
								  <td colspan=3 class=xl85 style="border-right:.5pt solid black;border-left:
								  none">WILAYAH</td>
								  <td colspan=3 class=xl88 style="border-left:none">KODE POS</td>
								  <td colspan=3 class=xl88 style="border-left:none">WILAYAH</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=6 rowspan=2 class=xl80>NAMA YANG DIHUBUNGI</td>
								  <td colspan=3 rowspan=2 class=xl80>NAMA YANG DIHUBUNGI</td>
								  <td colspan=3 rowspan=2 class=xl80>KM TIBA</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=6 class=xl88>TELP</td>
								  <td colspan=6 class=xl88 style="border-left:none">TELP</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=3 class=xl76 style="border-right:.5pt solid black">&nbsp;</td>
								  <td colspan=3 class=xl76 style="border-right:.5pt solid black;border-left:
								  none">&nbsp;</td>
								  <td colspan=3 class=xl65 style="border-left:none">&nbsp;</td>
								  <td colspan=3 class=xl65 style="border-left:none">&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=2 class=xl76 style="border-right:.5pt solid black; text-align:center">JENIS BARANG</td>
								  <td colspan=2 class=xl65 style="border-left:none; text-align:center">SATUAN</td>
								  <td colspan=4 class=xl65 style="border-left:none; text-align:center">JUMLAH</td>
								  <td colspan=4 class=xl65 style="border-left:none; text-align:center">JENIS TRANSAKSI</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=2 class=xl76 style="border-right:.5pt solid black">&nbsp;</td>
								  <td colspan=2 class=xl76 style="border-right:.5pt solid black;border-left:
								  none">&nbsp;</td>
								  <td colspan=4 class=xl65 style="border-left:none">&nbsp;</td>
								  <td colspan=4 class=xl65 style="border-left:none">&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=12 rowspan=3 class=xl80>TERBILANG
								  <br>
								  &nbsp;
								  </td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td class=xl68 style="border-top:none; text-align:center">PECAHAN</td>
								  <td colspan=2 class=xl65 style="border-left:none; text-align:center">MATA UANG</td>
								  <td class=xl65 style="border-top:none;border-left:none; text-align:center">JUMLAH</td>
								  <td colspan=2 class=xl65 style="border-left:none; text-align:center">NILAI</td>
								  <td class=xl68 style="border-top:none;border-left:none; text-align:center">PECAHAN</td>
								  <td colspan=2 class=xl65 style="border-left:none; text-align:center">MATA UANG</td>
								  <td class=xl65 style="border-top:none;border-left:none; text-align:center">JUMLAH</td>
								  <td colspan=2 class=xl65 style="border-left:none; text-align:center">NILAI</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td class=xl68 style="border-top:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td class=xl68 style="border-top:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td class=xl68 style="border-top:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td class=xl68 style="border-top:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none"><span
								  style="mso-spacerun:yes"> </span></td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td class=xl68 style="border-top:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=4 class=xl65 style="text-align:center">NO.KANTONG/TAS</td>
								  <td colspan=2 class=xl65 style="border-left:none; text-align:center">NO.SEGEL</td>
								  <td colspan=4 class=xl65 style="border-left:none; text-align:center">NO.KANTONG/TAS</td>
								  <td colspan=2 class=xl65 style="border-left:none; text-align:center">NO.SEGEL</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=4 rowspan=2 class=xl65>&nbsp;</td>
								  <td colspan=2 rowspan=2 class=xl65>&nbsp;</td>
								  <td colspan=4 rowspan=2 class=xl65>&nbsp;</td>
								  <td colspan=2 rowspan=2 class=xl65>&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=4 rowspan=2 class=xl65>&nbsp;</td>
								  <td colspan=2 rowspan=2 class=xl65>&nbsp;</td>
								  <td colspan=4 rowspan=2 class=xl65>&nbsp;</td>
								  <td colspan=2 rowspan=2 class=xl65>&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=6 class=xl65 style="text-align:center">SERAH / TERIMA</td>
								  <td colspan=6 class=xl65 style="border-left:none; text-align:center">SERAH / TERIMA</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=3 rowspan=2 class=xl80>NAMA</td>
								  <td colspan=3 rowspan=2 class=xl80>NAMA</td>
								  <td colspan=3 rowspan=2 class=xl80>NAMA</td>
								  <td colspan=3 rowspan=2 class=xl80>NAMA</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=3 rowspan=2 class=xl80>KTP/KPP</td>
								  <td colspan=3 rowspan=2 class=xl80>KTP/KPP</td>
								  <td colspan=3 rowspan=2 class=xl80>KTP/KPP</td>
								  <td colspan=3 rowspan=2 class=xl80>KTP/KPP</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=2 class=xl65>&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								  <td colspan=2 class=xl65 style="border-left:none">&nbsp;</td>
								  <td class=xl68 style="border-top:none;border-left:none">&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=3 rowspan=3 class=xl80 valign="top">TT
								  <br>
								  &nbsp;
								  <br>
								  &nbsp;
								  <br>
								  &nbsp;
								  <br>
								  &nbsp;
								  <br>
								  &nbsp;
								  </td>
								  <td colspan=3 rowspan=3 class=xl80 valign="top">TT</td>
								  <td colspan=3 rowspan=3 class=xl80 valign="top">TT</td>
								  <td colspan=3 rowspan=3 class=xl80 valign="top">TT</td>
								 </tr>
								 <tr height=19 style="height:14.4pt"></tr>
								 <tr height=19 style="height:14.4pt"></tr>
								 <tr height=19 style="height:14.4pt">
								  <td colspan=12 class=xl65>&nbsp;</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">
								  <td class=xl89 style="border-top:none">VAULT</td>
								  <td colspan=2 class=xl90 style="border-right:.5pt solid black;border-left:
								  none">ID</td>
								  <td colspan=3 rowspan=6 class=xl79 valign="top" style="text-align:center">TANDA TANGAN</td>
								  <td class=xl89 style="border-top:none;border-left:none">VAULT</td>
								  <td colspan=2 class=xl90 style="border-right:.5pt solid black;border-left:
								  none">ID</td>
								  <td colspan=3 rowspan=6 class=xl79 valign="top" style="text-align:center">TANDA TANGAN</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">

								  <td class=xl81 style="border-top:none">TERIMA</td>
								  <td colspan=2 class=xl81 style="border-left:none">DISERAHKAN</td>
								  <td class=xl81 style="border-top:none;border-left:none">TERIMA</td>
								  <td colspan=2 class=xl81 style="border-left:none">DISERAHKAN</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">

								  <td class=xl88 style="border-top:none">TANGGAL</td>
								  <td colspan=2 class=xl85 style="border-right:.5pt solid black;border-left:
								  none">WAKTU</td>
								  <td class=xl88 style="border-top:none;border-left:none">TANGGAL</td>
								  <td colspan=2 class=xl85 style="border-right:.5pt solid black;border-left:
								  none">WAKTU</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">

								  <td colspan=3 class=xl88>TANPA CATATAN</td>
								  <td colspan=3 class=xl88 style="border-left:none">TANPA CATATAN</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">

								  <td colspan=3 class=xl88>ADA CATATAN</td>
								  <td colspan=3 class=xl88 style="border-left:none">ADA CATATAN</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">

								  <td colspan=3 class=xl90 style="border-right:.5pt solid black">NO BERITA
								  ACARA</td>
								  <td colspan=3 class=xl90 style="border-right:.5pt solid black;border-left:
								  none">NO BERITA ACARA</td>
								 </tr>
								 <tr height=19 style="height:14.4pt">

								  <td colspan=3 class=xl89 style="text-align:center">ORIGINAL 1</td>
								  <td colspan=3 class=xl89 style="border-left:none; text-align:center">ORIGINAL 2 - PENGIRIM<span
								  style="mso-spacerun:yes"> </span></td>
								  <td colspan=3 class=xl89 style="border-left:none; text-align:center">ORIGINAL 3 - PENERIMA</td>
								  <td colspan=3 class=xl89 style="border-left:none; text-align:center">ORIGINAL 4 - ARSIP</td>
								 </tr>
								 <![if supportMisalignedColumns]>
								 <tr height=0 style="display:none">
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								  <td width=64 style="width:48pt"></td>
								 </tr>
								 <![endif]>
							</table>

					';
					
		$html .= '
			</body>
		</html>';

		$stylesheet = file_get_contents(base_url().'depend/bootstrap.min.css');
		$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html);

		$mpdf->Output();
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
			$hasil = "minus ". trim($this->penyebut($nilai));
		} else {
			$hasil = trim($this->penyebut($nilai));
		}     		
		return $hasil." Rupiah";
	}
}