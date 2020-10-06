<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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

        $query = "SELECT *, A.id as id_ct, B.id as id_detail 
									FROM cashtransit A
									LEFT JOIN cashtransit_detail B ON(A.id=B.id_cashtransit) 
									LEFT JOIN master_branch C ON(A.branch=C.id) 
									LEFT JOIN client D ON(B.id_bank=D.id)  
									WHERE B.state='ro_atm' AND B.data_solve!='' ORDER BY B.id DESC";
		

        // $query = $this->db->query("SELECT *, cashtransit.id as id_ct, cashtransit_detail.id as id_detail FROM cashtransit_detail LEFT JOIN cashtransit ON(cashtransit.id=cashtransit_detail.id_cashtransit) LEFT JOIN client ON(cashtransit_detail.id_bank=client.id) WHERE cashtransit_detail.data_solve!='' AND cashtransit_detail.state='ro_atm' ORDER BY cashtransit_detail.id DESC");
        $this->data['data_cashreplenish'] = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		
		// print_r($this->data['data_cashreplenish']);
		
        return view('admin/atmcr_nas/index', $this->data);
    }
}