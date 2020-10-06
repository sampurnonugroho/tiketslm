<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Mpdf\Mpdf;

class atmcr_nas extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("ticket_model");
        $this->load->library('form_validation');
		$this->load->library('curl');

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));
			$level = trim($this->session->userdata('level'));

			$this->data['level'] = $level;

			$this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			$this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			$this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			$this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        $this->data['active_menu'] = "atmcr_nas";

        // $query = "SELECT *, A.id as id_ct, B.id as id_detail 
									// FROM cashtransit A
									// LEFT JOIN cashtransit_detail B ON(A.id=B.id_cashtransit) 
									// LEFT JOIN master_branch C ON(A.branch=C.id) 
									// LEFT JOIN client D ON(B.id_bank=D.id)  
									// WHERE B.state='ro_atm' AND B.data_solve!=''  AND 
									// B.id IN (
										// SELECT MAX(id)
										// FROM cashtransit_detail
										// WHERE state='ro_atm' AND data_solve!=''
										// GROUP BY id_bank
									// )";

        // $query = "SELECT *, A.id as id_ct, B.id as id_detail 
									// FROM cashtransit A
									// LEFT JOIN cashtransit_detail B ON(A.id=B.id_cashtransit) 
									// LEFT JOIN master_branch C ON(A.branch=C.id) 
									// LEFT JOIN client D ON(B.id_bank=D.id)  
									// WHERE B.state='ro_atm' AND B.data_solve!='' ORDER BY B.id DESC";
		

        // // $query = $this->db->query("SELECT *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail FROM cashtransit_detail LEFT JOIN cashtransit ON(cashtransit.id=cashtransit_detail.id_cashtransit) LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.data_solve!='' AND cashtransit_detail.state='ro_atm' ORDER BY cashtransit_detail.id DESC");
        // $this->data['data_cashreplenish'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// print_r($this->data['data_cashreplenish']);
		
        return view('admin/atmcr_nas/index', $this->data);
    }
	
	function json() {
		$query = "
			SELECT 
				#*, 
				A.id as id_ct, 
				A.action_date, 
				D.wsid, 
				D.bank, 
				D.lokasi, 
				D.type_mesin, 
				B.id as id_detail, 
				B.updated_date
					FROM cashtransit A
					LEFT JOIN 
					(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, jam_cash_in, cpc_process, updated_date, loading, unloading, req_combi, fraud_indicated FROM cashtransit_detail) B ON(A.id=B.id_cashtransit) 
					LEFT JOIN master_branch C ON(A.branch=C.id) 
					LEFT JOIN client D ON(B.id_bank=D.id)  
		";
		
		$param['query'] = $query; //nama tabel dari database
		$param['column_order'] = array('B.id'); //field yang ada di table user
		$param['column_search'] = array('A.action_date'); //field yang diizin untuk pencarian 
		$param['order'] = array(array('B.id' => 'DESC'));
		$param['where'] = array(
			array('B.cpc_process[!]' => ''), 
			array('B.state' => 'ro_atm'), 
			array('B.data_solve[!]' => '')
		);
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		echo $this->curl->simple_get(rest_api().'/datatables', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
	}
	
	public function pdf() {
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
				), '') AS id_karyawan_2,
				(SELECT cart_seal FROM run_status_cancel WHERE id_detail=cashtransit_detail.id AND cart_no='cart_1_seal') as cart_1_cancel,
				(SELECT cart_seal FROM run_status_cancel WHERE id_detail=cashtransit_detail.id AND cart_no='cart_2_seal') as cart_2_cancel,
				(SELECT cart_seal FROM run_status_cancel WHERE id_detail=cashtransit_detail.id AND cart_no='cart_3_seal') as cart_3_cancel,
				(SELECT cart_seal FROM run_status_cancel WHERE id_detail=cashtransit_detail.id AND cart_no='cart_4_seal') as cart_4_cancel
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
			
			// print_r($data);
			// echo $type;
			
			if($type=="ATM") {
				// $ttl_ctr = '('.$ctr.') '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)), 0, ",", ".").'';
				// $denom = (intval($row->pcs_50000)!==0 ? "50000" : "100000");
				// $value = (intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000);
				// $ttl_all = 'Rp. '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom), 0, ",", ".").'';
				// $terbilang = ucwords($this->terbilang(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom)));
				
				$row_cart_1_seal = explode(";", $row->cart_1_seal);
				$row_cart_2_seal = explode(";", $row->cart_2_seal);
				$row_cart_3_seal = explode(";", $row->cart_3_seal);
				$row_cart_4_seal = explode(";", $row->cart_4_seal);
				
				$data_cart_1_seal = explode(";", $data->cart_1_seal);
				$data_cart_2_seal = explode(";", $data->cart_2_seal);
				$data_cart_3_seal = explode(";", $data->cart_3_seal);
				$data_cart_4_seal = explode(";", $data->cart_4_seal);
				
				$cart_1_cancel = explode(";", $row->cart_1_cancel);
				$cart_2_cancel = explode(";", $row->cart_2_cancel);
				$cart_3_cancel = explode(";", $row->cart_3_cancel);
				$cart_4_cancel = explode(";", $row->cart_4_cancel);
				
				$cancel_ctr = ($cart_1_cancel[0]!=="" ? 1 : 0)+
							  ($cart_2_cancel[0]!=="" ? 1 : 0)+
							  ($cart_3_cancel[0]!=="" ? 1 : 0)+
							  ($cart_4_cancel[0]!=="" ? 1 : 0);
				
				$cancel_lembar = ($cart_1_cancel[0]!=="" ? $cart_1_cancel[1] : 0)+
								 ($cart_2_cancel[0]!=="" ? $cart_2_cancel[1] : 0)+
								 ($cart_3_cancel[0]!=="" ? $cart_3_cancel[1] : 0)+
								 ($cart_4_cancel[0]!=="" ? $cart_4_cancel[1] : 0);
				
				$ttl_ctr = '('.($ctr-$cancel_ctr).') '.(intval($row->pcs_50000)!==0 ? ($row->pcs_50000-$cancel_lembar) : ($row->pcs_100000-$cancel_lembar)).'';
				$denom = (intval($row->pcs_50000)!==0 ? "50000" : (intval($row->total)!==0 ? "100000" : $row->denom));
				$value = (intval($row->pcs_50000)!==0 ? ($row->pcs_50000-$cancel_lembar) : ($row->pcs_100000-$cancel_lembar));
				// $ttl_all = 'Rp. '.number_format(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom), 0, ",", ".").'';
				// $terbilang = ucwords($this->terbilang(($ctr*(intval($row->pcs_50000)!==0 ? $row->pcs_50000 : $row->pcs_100000)*$denom)));
				$total = ($row->total-($cancel_lembar*$denom));
				$ttl_all = 'Rp. '.number_format($total, 0, ",", ".").'';
				$terbilang = ucwords($this->terbilang($total));
				
				
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
								<td align="center">'.$row->cart_1_cancel.'</td>
								<td align="center">'.$data->cart_1_seal.'</td>
								<td align="center">'.($data_cart_1_seal[0]!=="" ? intval($data->cart_1_no) : "").'</td>
								<td align="left">'.($data->cart_1_seal!=="" ? 'Rp. <span class="alignright">'.number_format((intval($denom)*$data->cart_1_no), 0, ",", ".").'</span>' : "").'</td>
							</tr>
							<tr>
								<td>Catridge 2</td>
								<td align="center">'.$row->cart_2_seal.'</td>
								<td></td>
								<td align="center">'.$row->cart_2_cancel.'</td>
								<td align="center">'.$data->cart_2_seal.'</td>
								<td align="center">'.($data_cart_2_seal[0]!=="" ? intval($data->cart_2_no) : "").'</td>
								<td align="left">'.($data->cart_2_seal!=="" ? 'Rp. <span class="alignright">'.number_format((intval($denom)*$data->cart_2_no), 0, ",", ".").'</span>' : "").'</td>
							</tr>
							<tr>
								<td>Catridge 3</td>
								<td align="center">'.$row->cart_3_seal.'</td>
								<td></td>
								<td align="center">'.$row->cart_3_cancel.'</td>
								<td align="center">'.$data->cart_3_seal.'</td>
								<td align="center">'.($data_cart_3_seal[0]!=="" ? intval($data->cart_3_no) : "").'</td>
								<td align="left">'.($data->cart_3_seal!=="" ? 'Rp. <span class="alignright">'.number_format((intval($denom)*$data->cart_3_no), 0, ",", ".").'</span>' : "").'</td>
							</tr>
							<tr>
								<td>Catridge 4</td>
								<td align="center">'.$row->cart_4_seal.'</td>
								<td></td>
								<td align="center">'.$row->cart_4_cancel.'</td>
								<td align="center">'.$data->cart_4_seal.'</td>
								<td align="center">'.($data_cart_4_seal[0]!=="" ? intval($data->cart_4_no) : "").'</td>
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
				
				if($data->data_seal!==null) {
					$postArr = json_decode($data->data_seal, true);
					// $postArr = array_map('array_filter', $postArr);
					// $postArr = array_filter($postArr);
					
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
		
		// echo $template_html;
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
}