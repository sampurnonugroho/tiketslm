<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Approval extends CI_Controller {
    var $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model("model_app");
        $this->load->model("ticket_model");
        $this->load->model("approval_model");
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

    public function approval_list() {
        // $data["data"] = $this->ticket_model->getAll();
        
        $this->data['active_menu'] = "approval_list";

		$id_dept = trim($this->session->userdata('id_dept'));
        $this->data['datalist_approval'] = $this->model_app->dataapproval($id_dept);
		
		// echo "<pre>".print_r($this->model_app->dataapproval($id_dept))."</pre>";
		
        return view('admin/approval/index', $this->data);
    }
	
	function approval_no($ticket)
 {
 	
    $data['status'] = 0;

    $id_user = trim($this->session->userdata('id_user'));
    $tanggal = $time = date("Y-m-d  H:i:s");

    $tracking['id_ticket'] = $ticket;
    $tracking['tanggal'] = $tanggal;
    $tracking['status'] = "Ticket tidak disetujui";
    $tracking['deskripsi'] = "";
    $tracking['id_user'] = $id_user;

  
    $this->db->trans_start();

 	$this->db->where('id_ticket', $ticket);
 	$this->db->update('ticket', $data);

    $this->db->insert('tracking', $tracking);

 	$this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
            {
               
                redirect('approval/approval_list');   
            } else 
            {
                
                redirect('approval/approval_list');   
            }

	
 }

 function approval_reaction($ticket)
 {

     $data['status'] = 1;

    $id_user = trim($this->session->userdata('id_user'));
    $tanggal = $time = date("Y-m-d  H:i:s");

    $tracking['id_ticket'] = $ticket;
    $tracking['tanggal'] = $tanggal;
    $tracking['status'] = "Ticket dikembalikan ke posisi belum di setujui";
    $tracking['deskripsi'] = "";
    $tracking['id_user'] = $id_user;

  
    $this->db->trans_start();

    $this->db->where('id_ticket', $ticket);
    $this->db->update('ticket', $data);

    $this->db->insert('tracking', $tracking);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
            {
               
                redirect('approval/approval_list');   
            } else 
            {
                
                redirect('approval/approval_list');   
            }

 }

  function approval_yes($ticket)
 {
   
    $data['status'] = 2;

    $id_user = trim($this->session->userdata('id_user'));
    $tanggal = $time = date("Y-m-d  H:i:s");

    $tracking['id_ticket'] = $ticket;
    $tracking['tanggal'] = $tanggal;
    $tracking['status'] = "Ticket disetujui";
    $tracking['deskripsi'] = "";
    $tracking['id_user'] = $id_user;
  
    $this->db->trans_start();

    $this->db->where('id_ticket', $ticket);
    $this->db->update('ticket', $data);

    $this->db->insert('tracking', $tracking);

    $this->db->trans_complete();

     if ($this->db->trans_status() === FALSE)
            {
               
                redirect('approval/approval_list');   
            } else 
            {
                
                redirect('approval/approval_list');   
            }
    
 }
}