<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Run_security extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	function index_get() {
		$id = $this->get('id'); 
        if ($id == '') {
			$q = "
					SELECT
						*, 
						cashtransit.id as id_ct,
						IFNULL((
							SELECT 
								COUNT(DISTINCT IFNULL(client.sektor, client_cit.sektor))
									FROM cashtransit_detail
										LEFT JOIN client ON(cashtransit_detail.id_bank=client.id)
										LEFT JOIN client_cit ON(IF(cashtransit_detail.id_pengirim=0, cashtransit_detail.id_penerima, cashtransit_detail.id_pengirim)=client_cit.id)
											WHERE 
												cashtransit_detail.id_cashtransit=cashtransit.id AND 
												IFNULL(client.sektor, client_cit.sektor) NOT IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit=cashtransit.id)
						), 0) AS count
						FROM
							cashtransit
							LEFT JOIN
								master_branch
									ON (cashtransit.branch=master_branch.id) ORDER BY cashtransit.id DESC";
            $user = $this->db->query($q)->result_array();
        } else {
			$q = "SELECT *
                               FROM karyawan A LEFT JOIN jabatan B ON B.id_jabatan = A.id_jabatan
                                               LEFT JOIN bagian_departemen C ON C.id_bagian_dept = A.id_bagian_dept
                                               LEFT JOIN departemen D ON D.id_dept = C.id_dept 
								WHERE A.nik='$nik'";
            $user = $this->db->query($q)->result_array();
        }
		
		if (!empty($user)) {
			 $this->response($user, REST_Controller::HTTP_OK);
		} else {
			$this->set_response([
				'status' => FALSE,
				'message' => 'User could not be found'
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	
	function get_data_post() {
		$id = $this->post('id'); 
		$page = isset($this->post['page']) ? intval($this->post['page']) : 1;
		$rows = isset($this->post['rows']) ? intval($this->post['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result = array();
		
		$qry = "SELECT 
					SQL_CALC_FOUND_ROWS 
						* 
						FROM runsheet_security 
							LEFT JOIN vehicle ON (vehicle.police_number=runsheet_security.police_number)
						WHERE id_cashtransit='".$id."' limit $offset,$rows";
						
		$query = $this->db->query($qry)->result();
		$rows = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`;")->row_array();
		$result["total"] = $rows['found_rows'];
		
		$items = array();
		$i = 0;
		foreach($query as $row){
			$items[$i]['id'] = $row->id;
			$items[$i]['id_cashtransit'] = $row->id_cashtransit;
			$items[$i]['run_number'] = $row->run_number;
			$items[$i]['type'] = $row->type;
			$items[$i]['police_number'] = $row->police_number;
			$items[$i]['km_status'] = $row->km_status;
			$items[$i]['security_1'] = $row->security_1;
			$items[$i]['security_2'] = $row->security_2;
			$i++;
		}
		$result["rows"] = $items;
		
		echo json_encode($result);
	}
	
	function suggest_data_client_post() {
		$search = $this->input->post('search');
		$id_cashtransit = $this->input->post('id_cashtransit');
		
		// $sql = "SELECT * FROM cashtransit_detail 
					// LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
					// LEFT JOIN client_cit ON(cashtransit_detail.id_pengirim=client_cit.id)
					// LEFT JOIN master_zone ON(client.sektor=master_zone.id OR client_cit.sektor=master_zone.id) 
				// WHERE 
					// cashtransit_detail.id_cashtransit='$id_cashtransit' AND 
					// client.sektor NOT IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit='$id_cashtransit') AND 
					// client_cit.sektor NOT IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit='$id_cashtransit') 
					// GROUP BY client.sektor";
		
		$sql = "SELECT *, client_cit.sektor as sektor_1, client.sektor as sektor_2 FROM cashtransit_detail 
					LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) 
					LEFT JOIN client_cit ON(cashtransit_detail.id_pengirim=client_cit.id)
					LEFT JOIN master_zone ON(client_cit.sektor=master_zone.id OR client.sektor=master_zone.id) 
				WHERE 
					cashtransit_detail.id_cashtransit='$id_cashtransit' AND 
					(client.sektor NOT IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit='$id_cashtransit') OR
					client_cit.sektor NOT IN (SELECT run_number FROM runsheet_security WHERE id_cashtransit='$id_cashtransit'))
					GROUP BY cashtransit_detail.id";
		
		// echo $sql;
		// echo "<br>";
		// echo "<br>";
		
		$result = $this->db->query($sql);
		
		$list = array();
		$key = 0;
		$prev_id = "";
		foreach($result->result() as $row) {
			if(array_search($row->id, array_column($list, 'id')) !== false) {
				// echo "FOUND";
			} else {
				if($row->sektor_1!=null) {
					$list[$key]['id'] = $row->id;
					$list[$key]['text'] = "(".$row->sektor_1.") ".$row->name; 
				} else if($row->sektor_2!=null) {
					$list[$key]['id'] = $row->id;
					$list[$key]['text'] = "(".$row->sektor_2.") ".$row->name; 
				}
				
				$key++;
			}
		}

		// echo "<pre>";
		// print_r($list);
		// print_r($output);
		
		
		echo json_encode($list);
	}
	
	function index_post() {
		
	}
	
	function index_put() {
		
	}
	
	function index_delete() {
		
	}
}