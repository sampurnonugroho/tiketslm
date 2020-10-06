<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Select extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->load->model('model_app');
        $this->load->library('form_validation');
		$this->load->library('curl');
    }


	function select_bagian_departemen() {

 	   $id_departemen = $this->input->post('id_departemen');
		
		if(trim($id_departemen) == ""){
			$data['dd_bagian_departemen'] = $this->model_app->dropdown_bagian_departemen('ea');
			$data['id_bagian_departemen'] = "";
		}else{
			$data['dd_bagian_departemen'] = $this->model_app->dropdown_bagian_departemen($id_departemen);
			$data['id_bagian_departemen'] = "";
		}
		
		return view('combo/select_bagian_departemen', $data);
	}

	function select_view_job() {

 	   $id_teknisi = $this->input->post('id_teknisi');
		

	    $sql = "SELECT A.progress, A.status, A.id_ticket, D.nama, A.tanggal, B.nama_sub_kategori, C.nama_kategori
                                   FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported
                                    WHERE A.id_teknisi = '$id_teknisi'";
	     $data['dataassigment'] = $this->db->query($sql);
			
		return view('combo/select_view_job', $data);

	}

	function select_sub_kategori() {

 	   $id_kategori = $this->input->post('id_kategori');
		
		if(trim($id_kategori) == ""){
			$data['dd_sub_kategori'] = $this->model_app->dropdown_sub_kategori('ea');
			$data['id_sub_kategori'] = "";
		}else{
			$data['dd_sub_kategori'] = $this->model_app->dropdown_sub_kategori($id_kategori);
			$data['id_sub_kategori'] = "";
		}
		
		$this->load->view('combo/select_sub_kategori',$data);	

	}
 
	function select_client() {
		$search = $this->input->post('search');
		// if($search!="") {
		$search = "%".strtolower($search)."%";
		// }
		$query = "SELECT * FROM client WHERE bank LIKE '$search' OR lokasi LIKE '$search' OR wsid LIKE '$search'";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->wsid.' - '.$row->bank.' - '.$row->lokasi; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}

	
 
	function select_client_wsid() {
		$search = $this->input->post('search');
		// if($search!="") {
		$search = "%".strtolower($search)."%";
		// }
		$query = "SELECT * FROM client WHERE wsid NOT IN (SELECT wsid FROM combi_lock)";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->wsid;
				$list[$key]['text'] = $row->bank.' - '.$row->lokasi; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
 
	function select_bank() {
		$search = $this->input->post('search');
		// if($search!="") {
		$search = "%".strtolower($search)."%";
		// }
		$query = "SELECT * FROM client WHERE bank LIKE '%$search%' GROUP BY bank";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->bank;
				$list[$key]['text'] = $row->bank; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
 
	function select_bank_ho() {
		$search = $this->input->post('search');
		// if($search!="") {
		$search = "%".strtolower($search)."%";
		// }
		// $query = "SELECT * FROM client_ho WHERE bank LIKE '$search' GROUP BY bank";
		// // echo $sql;
		// $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// $list1 = array();
		// if (count($result) > 0) {
			// $key=0;
			// foreach ($result as $row) {
				// $list1[$key]['id'] = $row->id;
				// $list1[$key]['text'] = $row->bank; 
				// $key++;
			// }
		// }
		
		$query = "SELECT * FROM client_ho WHERE bank LIKE '$search' GROUP BY bank";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list2 = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list2[$key]['id'] = $row->bank;
				$list2[$key]['text'] = $row->bank; 
				$key++;
			}
		}
		
		// $tes = array_merge($list1, $list2);
		// $output = array_map("unserialize", array_unique(array_map("serialize", $tes)));
		
		echo json_encode($list2);
	}
	
	function select_bank2() {
		$search = $this->input->post('search');
		// if($search!="") {
		$search = "%".strtolower($search)."%";
		// }
		$query = "SELECT * FROM client WHERE bank LIKE '$search' GROUP BY bank";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->bank; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	function select_branch() {
		$data['search'] = $this->input->post('search');
		$data['bank'] = $this->input->post('bank');
		
		$result = $this->curl->simple_post(rest_api().'/select/select_branch',$data,array(CURLOPT_BUFFERSIZE => 10));

		echo $result;
	}
	
	function select_atm() {
		$data['search'] = $this->input->post('search');	
		$data['bank'] = $this->input->post('bank');
		
		
		$query = "SELECT * FROM client";	
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));

		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->wsid;
				$list[$key]['text'] = "(".$row->wsid.") ".$row->lokasi; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
	
	
	function select_area() {
		$search = $this->input->post('search');
		$branch = $this->input->post('branch');
		if($search!=="") {
			$search = strtolower($search);
		}
		if($branch!=="") {
			$branch = strtolower($branch);
		}
		// $query = "SELECT * FROM master_zone WHERE id_branch LIKE '%$branch%' AND name LIKE '%$search%'";
		$query = "SELECT * FROM master_zone WHERE id_branch LIKE '%$branch%' AND name LIKE '%$search%'";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->name; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}

	function getdataclient() {
		$datas = $this->input->post('datas');
		$sql = "SELECT * FROM client WHERE bank LIKE '$datas' OR cabang LIKE '$datas' OR sektor LIKE '$datas'";
		// echo $sql;
		$result = $this->db->query($sql);
		
		$list = array();
		if ($result->num_rows() > 0) {
			$key=0;
			$no=0;
			foreach ($result->result() as $row) {
				$no++;	
				$list[$key][] = $no;
				$list[$key][] = $row->bank;
				$list[$key][] = $row->cabang;
				$list[$key][] = $row->lokasi;
				$list[$key][] = $row->sektor;
				$list[$key][] = $row->tgl_ho;
				$list[$key][] = $row->tgl_isi;
				$list[$key][] = ($row->tgl_isi==1?"NON AKTIF":"AKTIF");
				$list[$key][] = '
					<button type="button" class="button green" onClick="window.location.href=\''.base_url().'client/edit/'.$row->id.'\'" title="Edit"><span class="smaller">Edit</span></button>
					<button type="button" class="button green" onClick="openDelete(\''.$row->id.'\', \''.base_url().'client/delete\')" title="Edit"><span class="smaller">Edit</span></button>
				';
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
    
	function select_problem() {
		$search = $this->input->post('search');
		// if($search!="") {
		$search = "%".strtolower($search)."%";
		// }
		$sql = "SELECT * FROM client WHERE bank LIKE '$search' OR lokasi LIKE '$search'";
		// echo $sql;
		$result = $this->db->query($sql);
		
		$list = array();
		if ($result->num_rows() > 0) {
			$key=0;
			foreach ($result->result() as $row) {
				$list[$key]['id'] = $row->id;
				$list[$key]['text'] = $row->bank.' - '.$row->lokasi; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
}
