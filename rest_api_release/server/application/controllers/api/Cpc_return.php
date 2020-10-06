<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Cpc_return extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function get_data_post() {
		$id = $this->post('id'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$qry = "select *,
			cashtransit_detail.id as id_ct, 
			cashtransit_detail.id_cashtransit, 
			cashtransit_detail.id_bank, 
			cashtransit_detail.state as state,
			cashtransit_detail.ctr as ctr2, 
			cashtransit_detail.pcs_100000 as pcs_100000, 
			cashtransit_detail.pcs_50000 as pcs_50000, 
			cashtransit_detail.pcs_20000 as pcs_20000, 
			cashtransit_detail.pcs_10000 as pcs_10000, 
			cashtransit_detail.pcs_5000 as pcs_5000, 
			cashtransit_detail.pcs_2000 as pcs_2000, 
			cashtransit_detail.pcs_1000 as pcs_1000, 
			cashtransit_detail.pcs_coin as pcs_coin, 
			cashtransit_detail.total as total, 
			cashtransit_detail.data_solve as data_solve, 
			cashtransit_detail.cpc_process as cpc_process, 
			client.cabang as branch,
			client.wsid,
			client.bank,
			client.lokasi,
			client.sektor,
			cashtransit_detail.jenis,
			cashtransit_detail.ctr as ctr,
			client.denom,
			client.vendor,
			client.type,
			client.type_mesin,
			client.ctr as ctr2,
			runsheet_cashprocessing.pcs_100000 as s100k,
			runsheet_cashprocessing.pcs_50000 as s50k,
			runsheet_cashprocessing.pcs_20000 as s20k,
			runsheet_cashprocessing.pcs_10000 as s10k,
			runsheet_cashprocessing.pcs_5000 as s5k,
			runsheet_cashprocessing.pcs_2000 as s2k,
			runsheet_cashprocessing.pcs_1000 as s1k,
			runsheet_cashprocessing.pcs_coin as coin,
			runsheet_cashprocessing.total as nominal,
			runsheet_cashprocessing.ctr_1_no,
			runsheet_cashprocessing.ctr_2_no,
			runsheet_cashprocessing.ctr_3_no,
			runsheet_cashprocessing.ctr_4_no,
			runsheet_cashprocessing.cart_1_seal,
			runsheet_cashprocessing.cart_2_seal,
			runsheet_cashprocessing.cart_3_seal,
			runsheet_cashprocessing.cart_4_seal,
			runsheet_cashprocessing.divert,
			runsheet_cashprocessing.bag_seal,
			runsheet_cashprocessing.bag_no
		from 
			(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, jam_cash_in, cpc_process, updated_date, loading, unloading, req_combi, fraud_indicated FROM cashtransit_detail) AS cashtransit_detail 
			LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
			LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
			LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
			WHERE 
				cashtransit_detail.data_solve!='batal' AND 
				cashtransit_detail.id_cashtransit='".$id."' AND 
				cashtransit_detail.data_solve!='' AND 
				#cashtransit_detail.cpc_process='' AND 
				cashtransit_detail.state='ro_atm' 
				limit $offset,$rows";
				
		$query = $this->db->query($qry)->result();
		$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
		$result["total"] = $rows['found_rows'];
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['wsid'] = $row->wsid;
			$items[$i]['id_bank'] = $row->id_bank;
			$items[$i]['state'] = $row->state;
			$items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
			$items[$i]['bank'] = $row->bank;
			$items[$i]['lokasi'] = $row->lokasi;
			$items[$i]['runsheet'] = $row->sektor;
			$items[$i]['jenis'] = $row->type;
			$items[$i]['denom'] = $row->denom;
			$items[$i]['brand'] = $row->vendor;
			$items[$i]['model'] = $row->type_mesin;
			$items[$i]['pcs_100000'] = $row->pcs_100000;
			$items[$i]['pcs_50000'] = $row->pcs_50000;
			$items[$i]['pcs_20000'] = $row->pcs_20000;
			$items[$i]['pcs_10000'] = $row->pcs_10000;
			$items[$i]['pcs_5000'] = $row->pcs_5000;
			$items[$i]['pcs_2000'] = $row->pcs_2000;
			$items[$i]['pcs_1000'] = $row->pcs_1000;
			$items[$i]['pcs_coin'] = $row->pcs_coin;
			$items[$i]['s100k'] = $row->s100k;
			$items[$i]['s50k'] = $row->s50k;
			$items[$i]['s20k'] = $row->s20k;
			$items[$i]['s10k'] = $row->s10k;
			$items[$i]['s5k'] = $row->s5k;
			$items[$i]['s2k'] = $row->s2k;
			$items[$i]['s1k'] = $row->s1k;
			$items[$i]['coin'] = $row->coin;
			$items[$i]['ctr'] = $row->ctr;
			$items[$i]['cart_1_no'] = $row->ctr_1_no;
			$items[$i]['cart_2_no'] = $row->ctr_2_no;
			$items[$i]['cart_3_no'] = $row->ctr_3_no;
			$items[$i]['cart_4_no'] = $row->ctr_4_no;
			$items[$i]['cart_1_seal'] = $row->cart_1_seal;
			$items[$i]['cart_2_seal'] = $row->cart_2_seal;
			$items[$i]['cart_3_seal'] = $row->cart_3_seal;
			$items[$i]['cart_4_seal'] = $row->cart_4_seal;
			$items[$i]['divert'] = $row->divert;
			$items[$i]['total'] = $row->total;
			$items[$i]['nominal'] = (100000*$row->s100k)+(50000*$row->s50k)+(20000*$row->s20k)+(10000*$row->s10k)+(5000*$row->s5k)+(2000*$row->s2k)+(1000*$row->s1k)+(1*$row->coin);
			$items[$i]['bag_seal'] = $row->bag_seal;
			$items[$i]['bag_no'] = $row->bag_no;
			$items[$i]['data_solve'] = $row->data_solve;
			$items[$i]['cpc_process'] = $row->cpc_process;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	function get_data_batal_post() {
		$id = $this->post('id'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$qry = "select *,
			cashtransit_detail.id as id_ct, 
			cashtransit_detail.id_cashtransit, 
			cashtransit_detail.id_bank, 
			cashtransit_detail.state as state,
			cashtransit_detail.ctr as ctr2, 
			cashtransit_detail.pcs_100000 as pcs_100000, 
			cashtransit_detail.pcs_50000 as pcs_50000, 
			cashtransit_detail.pcs_20000 as pcs_20000, 
			cashtransit_detail.pcs_10000 as pcs_10000, 
			cashtransit_detail.pcs_5000 as pcs_5000, 
			cashtransit_detail.pcs_2000 as pcs_2000, 
			cashtransit_detail.pcs_1000 as pcs_1000, 
			cashtransit_detail.pcs_coin as pcs_coin, 
			cashtransit_detail.total as total, 
			cashtransit_detail.data_solve as data_solve, 
			cashtransit_detail.cpc_process as cpc_process, 
			client.cabang as branch,
			client.wsid,
			client.bank,
			client.lokasi,
			client.sektor,
			cashtransit_detail.jenis,
			cashtransit_detail.ctr as ctr,
			client.denom,
			client.vendor,
			client.type,
			client.type_mesin,
			client.ctr as ctr2,
			runsheet_cashprocessing.pcs_100000 as s100k,
			runsheet_cashprocessing.pcs_50000 as s50k,
			runsheet_cashprocessing.pcs_20000 as s20k,
			runsheet_cashprocessing.pcs_10000 as s10k,
			runsheet_cashprocessing.pcs_5000 as s5k,
			runsheet_cashprocessing.pcs_2000 as s2k,
			runsheet_cashprocessing.pcs_1000 as s1k,
			runsheet_cashprocessing.pcs_coin as coin,
			runsheet_cashprocessing.total as nominal,
			runsheet_cashprocessing.ctr_1_no,
			runsheet_cashprocessing.ctr_2_no,
			runsheet_cashprocessing.ctr_3_no,
			runsheet_cashprocessing.ctr_4_no,
			runsheet_cashprocessing.cart_1_seal,
			runsheet_cashprocessing.cart_2_seal,
			runsheet_cashprocessing.cart_3_seal,
			runsheet_cashprocessing.cart_4_seal,
			runsheet_cashprocessing.divert,
			runsheet_cashprocessing.bag_seal,
			runsheet_cashprocessing.bag_no
		from 
			(SELECT id, id_cashtransit, id_bank, id_pengirim, id_penerima, no_boc, state, metode, jenis, denom, pcs_100000, pcs_50000, pcs_20000, pcs_10000, pcs_5000, pcs_2000, pcs_1000, pcs_coin, detail_uang, ctr, divert, total, date, data_solve, jam_cash_in, cpc_process, updated_date, loading, unloading, req_combi, fraud_indicated FROM cashtransit_detail) AS cashtransit_detail 
			LEFT JOIN client on(cashtransit_detail.id_bank=client.id) 
			LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
			LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
			WHERE 
				cashtransit_detail.data_solve='batal' AND 
				cashtransit_detail.id_cashtransit='".$id."' AND 
				cashtransit_detail.cpc_process='' AND 
				cashtransit_detail.state='ro_atm' 
				limit $offset,$rows";
		$query = $this->db->query($qry)->result();
		$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
		$result["total"] = $rows['found_rows'];
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['id_bank'] = $row->id_bank;
			$items[$i]['state'] = $row->state;
			$items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
			$items[$i]['bank'] = $row->bank;
			$items[$i]['wsid'] = $row->wsid;
			$items[$i]['lokasi'] = $row->lokasi;
			$items[$i]['runsheet'] = $row->sektor;
			$items[$i]['jenis'] = $row->type;
			$items[$i]['denom'] = $row->denom;
			$items[$i]['brand'] = $row->vendor;
			$items[$i]['model'] = $row->type_mesin;
			$items[$i]['pcs_100000'] = $row->pcs_100000;
			$items[$i]['pcs_50000'] = $row->pcs_50000;
			$items[$i]['pcs_20000'] = $row->pcs_20000;
			$items[$i]['pcs_10000'] = $row->pcs_10000;
			$items[$i]['pcs_5000'] = $row->pcs_5000;
			$items[$i]['pcs_2000'] = $row->pcs_2000;
			$items[$i]['pcs_1000'] = $row->pcs_1000;
			$items[$i]['pcs_coin'] = $row->pcs_coin;
			$items[$i]['s100k'] = $row->s100k;
			$items[$i]['s50k'] = $row->s50k;
			$items[$i]['s20k'] = $row->s20k;
			$items[$i]['s10k'] = $row->s10k;
			$items[$i]['s5k'] = $row->s5k;
			$items[$i]['s2k'] = $row->s2k;
			$items[$i]['s1k'] = $row->s1k;
			$items[$i]['coin'] = $row->coin;
			$items[$i]['ctr'] = $row->ctr;
			$items[$i]['cart_1_no'] = $row->ctr_1_no;
			$items[$i]['cart_2_no'] = $row->ctr_2_no;
			$items[$i]['cart_3_no'] = $row->ctr_3_no;
			$items[$i]['cart_4_no'] = $row->ctr_4_no;
			$items[$i]['cart_1_seal'] = $row->cart_1_seal;
			$items[$i]['cart_2_seal'] = $row->cart_2_seal;
			$items[$i]['cart_3_seal'] = $row->cart_3_seal;
			$items[$i]['cart_4_seal'] = $row->cart_4_seal;
			$items[$i]['divert'] = $row->divert;
			$items[$i]['total'] = $row->total;
			$items[$i]['nominal'] = (100000*$row->s100k)+(50000*$row->s50k)+(20000*$row->s20k)+(10000*$row->s10k)+(5000*$row->s5k)+(2000*$row->s2k)+(1000*$row->s1k)+(1*$row->coin);
			$items[$i]['bag_seal'] = $row->bag_seal;
			$items[$i]['bag_no'] = $row->bag_no;
			$items[$i]['data_solve'] = $row->data_solve;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	function get_data_cit_post() {
		$id = $this->post('id'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$qry = "select SQL_CALC_FOUND_ROWS *,
			cashtransit_detail.id as id_ct, 
			cashtransit_detail.id_cashtransit, 
			cashtransit_detail.id_bank, 
			cashtransit_detail.state as state,
			cashtransit_detail.ctr as ctr2, 
			cashtransit_detail.pcs_100000 as pcs_100000, 
			cashtransit_detail.pcs_50000 as pcs_50000, 
			cashtransit_detail.pcs_20000 as pcs_20000, 
			cashtransit_detail.pcs_10000 as pcs_10000, 
			cashtransit_detail.pcs_5000 as pcs_5000, 
			cashtransit_detail.pcs_2000 as pcs_2000, 
			cashtransit_detail.pcs_1000 as pcs_1000, 
			cashtransit_detail.pcs_coin as pcs_coin, 
			cashtransit_detail.detail_uang as detail_uang,
			cashtransit_detail.total as total, 
			client_cit.cabang as branchz,
			client_cit.lokasi,
			client_cit.sektor,
			cashtransit_detail.jenis,
			cashtransit_detail.ctr as ctr,
			runsheet_cashprocessing.pcs_100000 as s100k,
			runsheet_cashprocessing.pcs_50000 as s50k,
			runsheet_cashprocessing.pcs_20000 as s20k,
			runsheet_cashprocessing.pcs_10000 as s10k,
			runsheet_cashprocessing.pcs_5000 as s5k,
			runsheet_cashprocessing.pcs_2000 as s2k,
			runsheet_cashprocessing.pcs_1000 as s1k,
			runsheet_cashprocessing.pcs_coin as coin,
			runsheet_cashprocessing.total as nominal,
			runsheet_cashprocessing.ctr_1_no,
			runsheet_cashprocessing.ctr_2_no,
			runsheet_cashprocessing.ctr_3_no,
			runsheet_cashprocessing.ctr_4_no,
			runsheet_cashprocessing.ctr_5_no,
			runsheet_cashprocessing.cart_1_seal,
			runsheet_cashprocessing.cart_2_seal,
			runsheet_cashprocessing.cart_3_seal,
			runsheet_cashprocessing.cart_4_seal,
			runsheet_cashprocessing.cart_5_seal,
			runsheet_cashprocessing.divert,
			runsheet_cashprocessing.bag_seal,
			runsheet_cashprocessing.bag_no
		from cashtransit_detail 
			LEFT JOIN client_cit on(cashtransit_detail.id_pengirim=client_cit.id) 
			LEFT JOIN cashtransit ON(cashtransit_detail.id_cashtransit=cashtransit.id) 
			LEFT JOIN runsheet_cashprocessing ON(cashtransit_detail.id=runsheet_cashprocessing.id) 
			WHERE 
				cashtransit_detail.id_cashtransit='$id' AND 
				cashtransit_detail.cpc_process='' AND 
				cashtransit_detail.state='ro_cit' limit $offset,$rows";
			
			
			// echo $qry;
			// echo "<br>";
		$query = $this->db->query($qry)->result();
		
		// echo "<pre>";
		// print_r($query);
		$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
		$result["total"] = $rows['found_rows'];
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			$detailuang = json_decode($row->detail_uang, true);
			
			$items[$i]['id'] = $row->id_ct;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['state'] = $row->state;
			$items[$i]['branch'] = $this->db->query("SELECT name FROM master_branch where id='".$row->branch."'")->row()->name;
			$items[$i]['metode'] = ($row->metode=="CP" ? "CASH PICKUP" : ($row->metode=="CD" ? "CASH DELIVERY" : ""));
			$items[$i]['jenis'] = $row->jenis;
			$items[$i]['lokasi'] = $row->lokasi;
			if($row->id_pengirim!=0){
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_pengirim."'")->row()->sektor;
			} else {
				$items[$i]['runsheet'] = $this->db->query("SELECT sektor FROM client_cit where id='".$row->id_penerima."'")->row()->sektor;
			}
			$items[$i]['pengirim'] = ($row->id_pengirim==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_pengirim."'")->row()->nama_client);
			$items[$i]['penerima'] = ($row->id_penerima==0 ? "PT BIJAK" : $this->db->query("SELECT nama_client FROM client_cit where id='".$row->id_penerima."'")->row()->nama_client);
			$items[$i]['pcs_100000'] = $row->pcs_100000;
			$items[$i]['pcs_50000'] = $row->pcs_50000;
			$items[$i]['pcs_20000'] = $row->pcs_20000;
			$items[$i]['pcs_10000'] = $row->pcs_10000;
			$items[$i]['pcs_5000'] = $row->pcs_5000;
			$items[$i]['pcs_2000'] = $row->pcs_2000;
			$items[$i]['pcs_1000'] = $row->pcs_1000;
			$items[$i]['pcs_coin'] = $row->pcs_coin;
			$items[$i]['s100k'] = $row->s100k;
			$items[$i]['s50k'] = $row->s50k;
			$items[$i]['s20k'] = $row->s20k;
			$items[$i]['s10k'] = $row->s10k;
			$items[$i]['s5k'] = $row->s5k;
			$items[$i]['s2k'] = $row->s2k;
			$items[$i]['s1k'] = $row->s1k;
			$items[$i]['coin'] = $row->coin;
			$items[$i]['detail_uang'] = $row->detail_uang;
			$items[$i]['kertas_100k'] = $detailuang['kertas_100k'];
			$items[$i]['kertas_50k'] = $detailuang['kertas_50k'];
			$items[$i]['kertas_20k'] = $detailuang['kertas_20k'];
			$items[$i]['kertas_10k'] = $detailuang['kertas_10k'];
			$items[$i]['kertas_5k'] = $detailuang['kertas_5k'];
			$items[$i]['kertas_2k'] = $detailuang['kertas_2k'];
			$items[$i]['kertas_1k'] = $detailuang['kertas_1k'];
			$items[$i]['logam_1k'] = $detailuang['logam_1k'];
			$items[$i]['logam_500'] = $detailuang['logam_500'];
			$items[$i]['logam_200'] = $detailuang['logam_200'];
			$items[$i]['logam_100'] = $detailuang['logam_100'];
			$items[$i]['logam_50'] = $detailuang['logam_50'];
			$items[$i]['total'] = $row->total;
			$items[$i]['bag_seal'] = $row->bag_seal;
			$items[$i]['bag_no'] = $row->bag_no;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
}