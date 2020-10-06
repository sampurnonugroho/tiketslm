<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Atm extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("atm_model");
        $this->load->library('form_validation');
    }

    public function index() {
        $data["atm"] = $this->atm_model->getAll();

        $data["title"] = "ATM";
        $data["session"] = $this->session;

        return view('admin/atm/index', $data);
    }

    public function add() {
        $atm = $this->atm_model;
        $validation = $this->form_validation;
        $validation->set_rules($atm->rules());
        $data["title"] = "ATM";  
        $data["session"] = $this->session;
        
        if ($validation->run()) {
            $atm->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
            
            redirect('atm');
        }

        return view('admin/atm/new_form', $data);
    }
}