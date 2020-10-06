<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("bank_model");
        $this->load->library('form_validation');
    }

    public function index() {
        $data["bank"] = $this->bank_model->getAll();

        $data["title"] = "Bank";
        $data["session"] = $this->session;

        return view('admin/bank/index', $data);
    }

    public function add() {
        $bank = $this->bank_model;
        $validation = $this->form_validation;
        $validation->set_rules($bank->rules());
        $data["title"] = "Bank";  
        $data["session"] = $this->session;
        
        if ($validation->run()) {
            $bank->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
            
            redirect('bank');
        }

        return view('admin/bank/new_form', $data);
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('bank');
       
        $bank = $this->bank_model;
        $validation = $this->form_validation;
        $validation->set_rules($bank->rules());
        $data["title"] = "Bank";
        $data["session"] = $this->session;

        if ($validation->run()) {
            $bank->update();
            $this->session->set_flashdata('success', 'Berhasil disimpan');

            redirect('bank');
        }

        $data["bank"] = $bank->getById($id);
        if (!$data["bank"]) show_404();
        
        return view('admin/bank/edit_form', $data);
    }

    public function delete($id=null)
    {
        if (!isset($id)) show_404();
        
        if ($this->bank_model->delete($id)) {
            redirect(site_url('bank'));
        }
    }
}