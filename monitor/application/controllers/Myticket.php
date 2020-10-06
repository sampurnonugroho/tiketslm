<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Myticket extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("ticket_model");
        $this->load->model("myticket_model");
        $this->load->library('form_validation');

        if(is_logged_in()) {
			$this->data["session"] = $this->session;
			$id_dept = trim($this->session->userdata('id_dept'));
			$id_user = trim($this->session->userdata('id_user'));

			$this->data['notif_list_ticket'] = $this->ticket_model->getnotif_list();
			$this->data['notif_approval'] = $this->ticket_model->getnotif_approval($id_dept);
			$this->data['notif_assignment'] = $this->ticket_model->getnotif_assign($id_user);
			$this->data['datalist_ticket'] = $this->ticket_model->datalist_ticket();
		} else {
            redirect('');
        }
    }

    public function index() {
        // $data["atm"] = $this->atm_model->getAll();

        // $data["title"] = "Ticket";
        // $data["session"] = $this->session;

        // return view('admin/ticket/index', $data);
    }

    public function myticket_list() {
        // $data["data"] = $this->ticket_model->getAll();
        
        $this->data['active_menu'] = "myticket_list";

		$id_user = trim($this->session->userdata('id_user'));
        $this->data['datalist_myticket'] = $this->myticket_model->datamyticket($id_user);
        return view('admin/myticket/index', $this->data);
    }
}