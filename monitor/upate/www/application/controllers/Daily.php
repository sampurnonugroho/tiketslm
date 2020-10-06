<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Mpdf\Mpdf;

class Daily extends CI_Controller {
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
		
		$sql = "select *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail, cashtransit_detail.ctr as jum_ctr FROM cashtransit LEFT JOIN master_branch ON(cashtransit.branch=master_branch.id) LEFT JOIN cashtransit_detail ON(cashtransit.id=cashtransit_detail.id_cashtransit) LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) WHERE cashtransit_detail.state='ro_atm' AND cashtransit_detail.data_solve='' AND cashtransit.id='$id_ct' AND client.sektor IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit='$id_ct' AND run_number='$id_ga') GROUP BY cashtransit_detail.id ORDER BY cashtransit.id DESC ";
		
		$result = $this->db->query($sql)->result();
		
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
 <col class=xl826467 width=110 style="mso-width-source:userset;mso-width-alt:
 3925;width:83pt">
 <col class=xl956467 width=12 style="mso-width-source:userset;mso-width-alt:
 426;width:9pt">
 <col class=xl956467 width=115 style="mso-width-source:userset;mso-width-alt:
 4096;width:86pt">
 <col class=xl956467 width=190 style="mso-width-source:userset;mso-width-alt:
 6741;width:142pt">
 <col class=xl956467 width=266 style="mso-width-source:userset;mso-width-alt:
 9472;width:200pt">
 <col class=xl956467 width=132 span=2 style="mso-width-source:userset;
 mso-width-alt:4693;width:99pt">
 <col class=xl956467 width=120 style="mso-width-source:userset;mso-width-alt:
 4266;width:90pt">
 <col class=xl956467 width=65 style="mso-width-source:userset;mso-width-alt:
 2304;width:49pt">
 <col class=xl956467 width=55 style="mso-width-source:userset;mso-width-alt:
 1962;width:41pt">
 <col class=xl956467 width=98 style="mso-width-source:userset;mso-width-alt:
 3470;width:73pt">
 <col class=xl956467 width=61 style="mso-width-source:userset;mso-width-alt:
 2161;width:46pt">
 <col class=xl956467 width=46 style="mso-width-source:userset;mso-width-alt:
 1621;width:34pt">
 <col class=xl956467 width=91 style="mso-width-source:userset;mso-width-alt:
 3242;width:68pt">
 <col class=xl956467 width=12 style="mso-width-source:userset;mso-width-alt:
 426;width:9pt">
 <col class=xl956467 width=130 style="mso-width-source:userset;mso-width-alt:
 4608;width:97pt">
 <col class=xl956467 width=325 style="mso-width-source:userset;mso-width-alt:
 11548;width:244pt">
 <tr class=xl836467 height=21 style="mso-height-source:userset;height:15.9pt">
  <td height=21 class=xl846467 width=110 style="height:15.9pt;width:83pt"><a
  name="RANGE!A1:Q45">Kepada<span style="mso-spacerun:yes"> </span></a></td>
  <td class=xl856467 colspan=2 width=127 style="width:95pt">: Ibu. Ririn</td>
  <td class=xl836467 width=190 style="width:142pt"></td>
  <td class=xl836467 width=266 style="width:200pt"></td>
  <td class=xl836467 width=132 style="width:99pt"></td>
  <td class=xl866467 width=132 style="width:99pt">Dari</td>
  <td class=xl876467 colspan=3 width=240 style="width:180pt">:<span
  style="mso-spacerun:yes">  </span>Cash Processing Center</td>
  <td class=xl836467 width=98 style="width:73pt"></td>
  <td class=xl836467 width=61 style="width:46pt"></td>
  <td class=xl836467 width=46 style="width:34pt"></td>
  <td class=xl836467 width=91 style="width:68pt"></td>
  <td class=xl836467 width=12 style="width:9pt"></td>
  <td class=xl836467 width=130 style="width:97pt"></td>
  <td class=xl836467 width=325 style="width:244pt"></td>
 </tr>
 <tr class=xl836467 height=21 style="mso-height-source:userset;height:15.9pt">
  <td height=21 class=xl846467 style="height:15.9pt">Bank<span
  style="mso-spacerun:yes"> </span></td>
  <td class=xl856467 colspan=3>: BANK CIMB NIAGA - NCC</td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl866467>Company</td>
  <td class=xl876467 colspan=3><span style="mso-spacerun:yes">    </span>PT.
  Alpha EMS - Jakarta</td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td colspan=5 rowspan=3 class=xl1676467 width=604 style="border-right:1.0pt solid black;
  border-bottom:1.0pt solid black;width:452pt">CONFIDENTIAL</td>
 </tr>
 <tr class=xl836467 height=21 style="mso-height-source:userset;height:15.9pt">
  <td height=21 class=xl846467 style="height:15.9pt">No. Fax.<span
  style="mso-spacerun:yes"> </span></td>
  <td class=xl856467 colspan=3>: (021) 6344217</td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl866467>No. Telp</td>
  <td class=xl876467 colspan=2>:<span style="mso-spacerun:yes">  </span>(021)
  7225138</td>
  <td class=xl886467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
 </tr>
 <tr class=xl836467 height=21 style="mso-height-source:userset;height:15.9pt">
  <td height=21 class=xl846467 style="height:15.9pt">No. Telp<span
  style="mso-spacerun:yes"> </span></td>
  <td class=xl896467 colspan=3>: (021) 299 72400<span
  style="mso-spacerun:yes">  </span>Ext. 85639</td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl866467>No. Faks.</td>
  <td class=xl876467></td>
  <td class=xl876467></td>
  <td class=xl876467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
 </tr>
 <tr class=xl836467 height=21 style="mso-height-source:userset;height:15.9pt">
  <td height=21 class=xl846467 style="height:15.9pt">Tanggal<span
  style="mso-spacerun:yes"> </span></td>
  <td class=xl896467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl866467></td>
  <td class=xl876467></td>
  <td class=xl876467></td>
  <td class=xl876467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl906467 width=46 style="width:34pt"></td>
  <td class=xl906467 width=91 style="width:68pt"></td>
  <td class=xl906467 width=12 style="width:9pt"></td>
  <td class=xl906467 width=130 style="width:97pt"></td>
  <td class=xl906467 width=325 style="width:244pt"></td>
 </tr>
 <tr class=xl836467 height=21 style="mso-height-source:userset;height:15.9pt">
  <td height=21 class=xl846467 style="height:15.9pt">Perihal<span
  style="mso-spacerun:yes"> </span></td>
  <td class=xl856467 colspan=3>: Laporan saldo harian ATM<span
  style="mso-spacerun:yes"> </span></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td colspan=2 class=xl926467></td>
  <td class=xl846467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
 </tr>
 <tr height=10 style="mso-height-source:userset;height:8.1pt">
  <td height=10 class=xl916467 style="height:8.1pt"></td>
  <td class=xl926467></td>
  <td class=xl936467></td>
  <td class=xl936467></td>
  <td class=xl936467></td>
  <td class=xl946467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl846467></td>
  <td class=xl846467></td>
  <td class=xl846467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
 </tr>
 <tr class=xl966467 height=33 style="height:24.6pt">
  <td colspan=17 height=33 class=xl2386467 style="height:24.6pt">SALDO HARIAN
  ATM</td>
 </tr>
 <tr height=6 style="mso-height-source:userset;height:4.5pt">
  <td height=6 class=xl826467 style="height:4.5pt"></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
 </tr>
 <tr height=25 style="mso-height-source:userset;height:18.75pt">
  <td colspan=2 rowspan=2 height=50 class=xl1756467 style="border-right:.5pt solid black;
  border-bottom:1.0pt solid black;height:37.35pt">Tanggal</td>
  <td colspan=3 rowspan=2 class=xl1796467 style="border-right:.5pt solid black">Uraian</td>
  <td colspan=5 class=xl1846467 style="border-right:.5pt solid black;
  border-left:none">Deno<span style="mso-spacerun:yes">  </span>(lembar)</td>
  <td colspan=3 rowspan=2 class=xl1876467 width=205 style="border-right:.5pt solid black;
  width:153pt">Total Rupiah x1.000</td>
  <td colspan=4 rowspan=2 class=xl2406467>Keterangan</td>
 </tr>
 <tr height=25 style="height:18.6pt">
  <td height=25 class=xl1266467 style="height:18.6pt;border-left:none">100,000 </td>
  <td class=xl1266467 style="border-left:none">50,000 </td>
  <td class=xl1266467 style="border-left:none">20,000 </td>
  <td class=xl1266467 style="border-left:none">10,000 </td>
  <td class=xl1266467 style="border-left:none">5,000 </td>
 </tr>
 <tr height=25 style="height:18.6pt">
  <td colspan=2 rowspan=12 height=303 class=xl1486467 style="border-bottom:
  1.0pt solid black;height:228.15pt">Tanggal laporan</td>
  <td colspan=3 class=xl2196467 style="border-right:.5pt solid black">SALDO
  AWAL</td>
  <td class=xl1326467 style="border-left:none"><span
  style="mso-spacerun:yes">       </span>6,865,700 </td>
  <td class=xl1336467 style="border-left:none"><span
  style="mso-spacerun:yes">     </span>17,263,754 </td>
  <td class=xl1336467 style="border-left:none"><span
  style="mso-spacerun:yes">            </span>1,610 </td>
  <td class=xl1336467 style="border-left:none">&nbsp;</td>
  <td class=xl1336467 style="border-left:none">&nbsp;</td>
  <td colspan=3 class=xl1546467 style="border-left:none"><span
  style="mso-spacerun:yes">                    </span>24,131,064 </td>
  <td colspan=4 class=xl2426467 style="border-left:none">&nbsp;</td>
 </tr>
 <tr height=26 style="mso-height-source:userset;height:19.5pt">
  <td rowspan=6 height=149 class=xl2336467 style="border-bottom:1.0pt solid black;
  height:112.5pt">Penerimaan</td>
  <td colspan=2 class=xl1936467>Dari Rekonsiliasi Cartridge ATM dan CRM</td>
  <td class=xl1026467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>1,132,400 </td>
  <td class=xl1026467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>1,683,450 </td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                         </span>2,815,850 </td>
  <td colspan=4 class=xl1576467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=2 height=24 class=xl1936467 style="height:18.0pt">Hasil bongkaran
  CDM Niaga<span style="mso-spacerun:yes"> </span></td>
  <td class=xl976467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl976467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl976467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl976467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl976467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=2 height=24 class=xl1936467 style="height:18.0pt">UANG NYANGKUT
  CRM</td>
  <td class=xl986467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl986467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl986467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=2 height=24 class=xl1936467 style="height:18.0pt">Dari CIMB Niaga
  CIT</td>
  <td class=xl986467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>2,000,000 </td>
  <td class=xl986467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">        </span>27,000,000 </td>
  <td class=xl996467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl996467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl996467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                       </span>29,000,000 </td>
  <td colspan=4 class=xl1616467 style="border-right:1.0pt solid black;
  border-left:none">Uang yang di pick up dari CIMB Niaga</td>
 </tr>
 <tr height=25 style="mso-height-source:userset;height:18.75pt">
  <td colspan=2 height=25 class=xl1936467 style="height:18.75pt">Penyelesaian
  Klaim Nasabah</td>
  <td class=xl996467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1006467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl996467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl996467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl996467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1616467 style="border-right:1.0pt solid black;
  border-left:none">ID dan NO memo klaim pada kolom keterangan<span
  style="mso-spacerun:yes"> </span></td>
 </tr>
 <tr height=26 style="mso-height-source:userset;height:20.25pt">
  <td colspan=2 height=26 class=xl1936467 style="height:20.25pt">Penyelesaian
  Klaim Selisih Kurang Fisik</td>
  <td class=xl996467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                 </span>2,900 </td>
  <td class=xl1006467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                   </span>150 </td>
  <td class=xl996467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl996467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl996467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                </span>3,050 </td>
  <td colspan=4 class=xl1616467 style="border-right:1.0pt solid black;
  border-left:none">PEMBAYARAN SELISIH</td>
 </tr>
 <tr height=25 style="height:18.6pt">
  <td colspan=3 height=25 class=xl2246467 style="border-right:.5pt solid black;
  height:18.6pt">Subtotal Penerimaan<span style="mso-spacerun:yes"> </span></td>
  <td class=xl1206467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>3,135,300 </td>
  <td class=xl1206467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">        </span>28,683,600 </td>
  <td class=xl1206467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl1206467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>- </td>
  <td class=xl1206467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">        </span>- </td>
  <td colspan=3 class=xl2036467 style="border-left:none"><span
  style="mso-spacerun:yes">                       </span>31,818,900 </td>
  <td colspan=4 class=xl1956467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td rowspan=2 height=49 class=xl2336467 style="border-bottom:1.0pt solid black;
  height:36.6pt">Pengeluaran</td>
  <td colspan=2 class=xl1936467>Untuk Cartridge Replenishment ATM &amp; CRM</td>
  <td class=xl1026467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">         </span>(3,240,000)</td>
  <td class=xl1026467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">         </span>(6,020,000)</td>
  <td class=xl1026467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1026467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1026467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                        </span>(9,260,000)</td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr class=xl1466467 height=25 style="height:18.6pt">
  <td colspan=2 height=25 class=xl2366467 style="height:18.6pt">Setoran Ke cash
  Management &amp; Kelebihan Adjustment Pembayaran Selisih ID A314 tanggal 17
  Juli 2019</td>
  <td class=xl1446467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1456467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                  </span>(550)</td>
  <td class=xl1456467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1446467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1446467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl2046467 style="border-left:none"><span
  style="mso-spacerun:yes">                                 </span>(550)</td>
  <td colspan=4 class=xl1616467 style="border-right:1.0pt solid black;
  border-left:none">-BA Uang Kurang Collect PT.TAG tgl 30 Juli 2019 sebesar Rp
  50.000</td>
 </tr>
 <tr height=25 style="height:18.6pt">
  <td colspan=3 height=25 class=xl2246467 style="border-right:.5pt solid black;
  height:18.6pt">Subtotal Pengeluaran</td>
  <td class=xl1346467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">         </span>(3,240,000)</td>
  <td class=xl1346467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">         </span>(6,020,550)</td>
  <td class=xl1346467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl1346467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>- </td>
  <td class=xl1346467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">        </span>- </td>
  <td colspan=3 class=xl1646467 style="border-left:none"><span
  style="mso-spacerun:yes">                        </span>(9,260,550)</td>
  <td colspan=4 class=xl1656467 style="border-right:1.0pt solid black;
  border-left:none">-Kelebihan Adjustment Pembayaran Selisih ID A314 tanggal 17
  Juli 2019</td>
 </tr>
 <tr height=30 style="mso-height-source:userset;height:23.25pt">
  <td height=30 class=xl1276467 style="height:23.25pt;border-left:none">SALDO
  AKHI<span style="display:none">R SAMPAI PUKUL 00:00</span></td>
  <td class=xl1286467>&nbsp;</td>
  <td class=xl1296467>&nbsp;</td>
  <td class=xl1226467 style="border-left:none"><span
  style="mso-spacerun:yes">       </span>6,761,000 </td>
  <td class=xl1226467 style="border-left:none"><span
  style="mso-spacerun:yes">     </span>39,926,804 </td>
  <td class=xl1226467 style="border-left:none"><span
  style="mso-spacerun:yes">            </span>1,610 </td>
  <td class=xl1236467 style="border-left:none"><span
  style="mso-spacerun:yes">         </span>- </td>
  <td class=xl1236467 style="border-left:none"><span
  style="mso-spacerun:yes">       </span>- </td>
  <td colspan=3 class=xl1976467 style="border-right:.5pt solid black;
  border-left:none"><span style="mso-spacerun:yes">                   
  </span>46,689,414 </td>
  <td colspan=4 class=xl2006467 style="border-right:1.0pt solid black;
  border-left:none">Memo No.261/ATM/ALPHA/2019 sebesar Rp 500.000,-</td>
 </tr>
 <tr height=22 style="mso-height-source:userset;height:16.5pt">
  <td height=22 class=xl1246467 style="height:16.5pt;border-top:none">&nbsp;</td>
  <td class=xl1246467 style="border-top:none">&nbsp;</td>
  <td class=xl1246467>&nbsp;</td>
  <td class=xl1246467>&nbsp;</td>
  <td class=xl1246467>&nbsp;</td>
  <td class=xl1246467>&nbsp;</td>
  <td class=xl1246467>&nbsp;</td>
  <td class=xl1256467>&nbsp;</td>
  <td class=xl1246467>&nbsp;</td>
  <td class=xl1246467>&nbsp;</td>
  <td class=xl1246467>&nbsp;</td>
  <td class=xl1246467>&nbsp;</td>
  <td class=xl1246467>&nbsp;</td>
  <td class=xl1246467 style="border-top:none">&nbsp;</td>
  <td class=xl1246467 style="border-top:none">&nbsp;</td>
  <td class=xl1246467 style="border-top:none">&nbsp;</td>
  <td class=xl1246467 style="border-top:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=2 rowspan=15 height=378 class=xl1486467 style="border-bottom:
  1.0pt solid black;height:283.95pt">H+1 Tanggal Laporan</td>
  <td class=xl1306467 style="border-top:none">STATUS SA<span style="display:
  none">LDO SEMENTARA MULAI PUKUL 00:01</span></td>
  <td class=xl1316467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1316467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1326467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1336467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1336467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1336467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1336467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl1546467 style="border-left:none">&nbsp;</td>
  <td colspan=4 class=xl1556467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td rowspan=3 height=72 class=xl2176467 style="height:54.0pt;border-top:none">Penerimaan</td>
  <td colspan=2 class=xl2186467 style="border-left:none">Dari Rekonsiliasi
  Cartridge</td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1356467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td height=24 class=xl1366467 style="height:18.0pt;border-top:none;
  border-left:none">Dari Batal Replenishmen<span style="display:none">t</span></td>
  <td class=xl1366467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1356467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=2 height=24 class=xl2186467 style="height:18.0pt;border-left:
  none">Dari Lain-lain</td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1356467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=3 height=24 class=xl2276467 style="border-right:.5pt solid black;
  height:18.0pt">Subtotal Penerimaan<span style="mso-spacerun:yes"> </span></td>
  <td class=xl1216467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1216467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1216467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl1216467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>- </td>
  <td class=xl1216467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">        </span>- </td>
  <td colspan=3 class=xl2036467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1956467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td rowspan=3 height=72 class=xl2176467 style="height:54.0pt;border-top:none">Pengeluaran</td>
  <td class=xl1376467 style="border-top:none;border-left:none">Untuk Cartridge
  Repleni<span style="display:none">shment</span></td>
  <td class=xl1376467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>1,600,000 </td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">        </span>16,000,000 </td>
  <td class=xl1356467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                       </span>17,600,000 </td>
  <td colspan=4 class=xl1616467 style="border-right:1.0pt solid black;
  border-left:none">proyeksi pengisian berdasarkan hasil packing</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=2 height=24 class=xl2186467 style="height:18.0pt;border-left:
  none">Untuk Kas Cadangan</td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1356467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=2 height=24 class=xl2186467 style="height:18.0pt;border-left:
  none">Untuk Lain-lain</td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1186467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                        </span>- </td>
  <td class=xl1356467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl1016467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1016467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=25 style="height:18.6pt">
  <td colspan=3 height=25 class=xl2306467 style="border-right:.5pt solid black;
  height:18.6pt">Subtotal Pengeluaran</td>
  <td class=xl1346467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>1,600,000 </td>
  <td class=xl1346467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">        </span>16,000,000 </td>
  <td class=xl1346467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">                     </span>- </td>
  <td class=xl1346467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>- </td>
  <td class=xl1346467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">        </span>- </td>
  <td colspan=3 class=xl1646467 style="border-left:none"><span
  style="mso-spacerun:yes">                       </span>17,600,000 </td>
  <td colspan=4 class=xl1656467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=30 style="mso-height-source:userset;height:23.25pt">
  <td colspan=3 height=30 class=xl2226467 style="height:23.25pt">SALDO
  SEMENTARA SAMPAI PUKUL 09:00</td>
  <td class=xl1326467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">       </span>5,161,000 </td>
  <td class=xl1326467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">     </span>23,926,804 </td>
  <td class=xl1326467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">            </span>1,610 </td>
  <td class=xl1326467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">         </span>- </td>
  <td class=xl1326467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">       </span>- </td>
  <td colspan=3 class=xl1546467 style="border-left:none"><span
  style="mso-spacerun:yes">                    </span>29,089,414 </td>
  <td colspan=4 class=xl1556467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=3 height=24 class=xl2156467 style="height:18.0pt">STATUS<span
  style="mso-spacerun:yes">  </span>UANG CADANGAN ATM</td>
  <td class=xl1026467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1026467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=3 height=24 class=xl2156467 style="height:18.0pt">&nbsp;</td>
  <td class=xl1026467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1026467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl986467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=24 style="height:18.0pt">
  <td colspan=3 height=24 class=xl2116467 style="height:18.0pt">Kondisi Layak
  Edar</td>
  <td class=xl1196467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>5,161,000 </td>
  <td class=xl1196467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">        </span>23,926,804 </td>
  <td class=xl1196467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">              </span>1,610 </td>
  <td class=xl1196467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">          </span>- </td>
  <td class=xl1196467 style="border-top:none;border-left:none"><span
  style="mso-spacerun:yes">        </span>- </td>
  <td colspan=3 class=xl986467 style="border-left:none"><span
  style="mso-spacerun:yes">                       </span>29,089,414 </td>
  <td colspan=4 class=xl1596467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=25 style="height:18.6pt">
  <td colspan=3 height=25 class=xl2136467 style="height:18.6pt">Kondisi Rusak
  &amp; Tidak Layak Edar</td>
  <td class=xl1386467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1386467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1396467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1396467 style="border-top:none;border-left:none">&nbsp;</td>
  <td class=xl1396467 style="border-top:none;border-left:none">&nbsp;</td>
  <td colspan=3 class=xl1396467 style="border-left:none"><span
  style="mso-spacerun:yes">                                       </span>- </td>
  <td colspan=4 class=xl2066467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr height=34 style="mso-height-source:userset;height:25.5pt">
  <td height=34 class=xl1406467 style="height:25.5pt">TOTAL STAT<span
  style="display:none">US UANG CADANGAN ATM</span></td>
  <td class=xl1416467 style="border-left:none">&nbsp;</td>
  <td class=xl1416467 style="border-left:none">&nbsp;</td>
  <td class=xl1426467 style="border-left:none"><span
  style="mso-spacerun:yes">       </span>5,161,000 </td>
  <td class=xl1426467 style="border-left:none"><span
  style="mso-spacerun:yes">     </span>23,926,804 </td>
  <td class=xl1426467 style="border-left:none"><span
  style="mso-spacerun:yes">            </span>1,610 </td>
  <td class=xl1426467 style="border-left:none"><span
  style="mso-spacerun:yes">         </span>- </td>
  <td class=xl1426467 style="border-left:none"><span
  style="mso-spacerun:yes">       </span>- </td>
  <td colspan=3 class=xl2086467 style="border-left:none"><span
  style="mso-spacerun:yes">                       </span>29,089,414 </td>
  <td colspan=4 class=xl2096467 style="border-right:1.0pt solid black;
  border-left:none">&nbsp;</td>
 </tr>
 <tr class=xl946467 height=30 style="mso-height-source:userset;height:23.25pt">
  <td height=30 class=xl1036467 style="height:23.25pt"></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
  <td class=xl946467></td>
 </tr>
 <tr height=28 style="height:21.0pt">
  <td height=28 class=xl1046467 style="height:21.0pt"></td>
  <td class=xl1046467></td>
  <td class=xl1046467></td>
  <td class=xl1056467>Dibuat</td>
  <td class=xl1066467></td>
  <td class=xl956467></td>
  <td class=xl1056467></td>
  <td class=xl1076467></td>
  <td class=xl1056467><span style="mso-spacerun:yes">        </span>Diperiksa</td>
  <td class=xl956467></td>
  <td class=xl956467></td>
  <td class=xl956467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl1076467></td>
  <td class=xl1056467>Disetujui</td>
 </tr>
 <tr height=28 style="height:21.0pt">
  <td height=28 class=xl1086467 style="height:21.0pt"></td>
  <td class=xl1096467></td>
  <td class=xl1096467></td>
  <td class=xl1106467></td>
  <td class=xl1106467></td>
  <td class=xl956467></td>
  <td class=xl1106467></td>
  <td class=xl1106467></td>
  <td class=xl1116467></td>
  <td class=xl956467></td>
  <td class=xl956467></td>
  <td class=xl956467></td>
  <td class=xl1096467></td>
  <td class=xl1476467></td>
  <td class=xl1476467></td>
  <td class=xl1106467></td>
  <td class=xl1116467></td>
 </tr>
 <tr height=26 style="height:19.2pt">
  <td height=26 class=xl1126467 style="height:19.2pt"></td>
  <td class=xl1126467></td>
  <td class=xl1126467></td>
  <td class=xl1116467></td>
  <td class=xl1056467></td>
  <td class=xl956467></td>
  <td class=xl1056467></td>
  <td class=xl1056467></td>
  <td class=xl1056467></td>
  <td class=xl956467></td>
  <td class=xl956467></td>
  <td class=xl956467></td>
  <td class=xl936467></td>
  <td class=xl1476467></td>
  <td class=xl1476467></td>
  <td class=xl1056467></td>
  <td class=xl1056467></td>
 </tr>
 <tr height=26 style="height:19.8pt">
  <td height=26 class=xl1136467 style="height:19.8pt"></td>
  <td class=xl1146467></td>
  <td class=xl1136467></td>
  <td class=xl1156467></td>
  <td class=xl1156467></td>
  <td class=xl956467></td>
  <td class=xl1076467></td>
  <td class=xl1076467></td>
  <td class=xl1056467></td>
  <td class=xl956467></td>
  <td class=xl956467></td>
  <td class=xl956467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl836467></td>
  <td class=xl1076467></td>
  <td class=xl1056467></td>
 </tr>
 <tr height=26 style="height:19.8pt">
  <td height=26 class=xl1166467 style="height:19.8pt"></td>
  <td class=xl1136467></td>
  <td class=xl1136467></td>
  <td class=xl1176467>( Della Amethia Z )</td>
  <td class=xl1066467></td>
  <td class=xl956467></td>
  <td class=xl1076467></td>
  <td class=xl1076467></td>
  <td class=xl1436467 colspan=2>( Dodi Ariyanto )</td>
  <td class=xl956467></td>
  <td class=xl956467></td>
  <td class=xl836467></td>
  <td class=xl1126467></td>
  <td class=xl1126467></td>
  <td class=xl1076467></td>
  <td class=xl1176467>( Ade Ferdiantono )</td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style="display:none">
  <td width=110 style="width:83pt"></td>
  <td width=12 style="width:9pt"></td>
  <td width=115 style="width:86pt"></td>
  <td width=190 style="width:142pt"></td>
  <td width=266 style="width:200pt"></td>
  <td width=132 style="width:99pt"></td>
  <td width=132 style="width:99pt"></td>
  <td width=120 style="width:90pt"></td>
  <td width=65 style="width:49pt"></td>
  <td width=55 style="width:41pt"></td>
  <td width=98 style="width:73pt"></td>
  <td width=61 style="width:46pt"></td>
  <td width=46 style="width:34pt"></td>
  <td width=91 style="width:68pt"></td>
  <td width=12 style="width:9pt"></td>
  <td width=130 style="width:97pt"></td>
  <td width=325 style="width:244pt"></td>
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