<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_model extends CI_Model {
    private $_table = "master_bank";

    public $id;
    public $bank_name;

    public function rules() {
        return [
            [
                'field' => 'bank_name',
                'label' => 'Bank Name',
                'rules' => 'required'
            ]
        ];
    }

    public function getAll() {
        return $this->db->get($this->_table)->result();
    }

    public function getById($id) {
        return $this->db->get_where($this->_table, ["id" => $id])->row();
    }

    public function save() {
        $post = $this->input->post();
        $this->bank_name = $post["bank_name"];
        $this->db->insert($this->_table, $this);
    }

    public function update() {
        $post = $this->input->post();
        $this->id = $post["id"];
        $this->bank_name = $post["bank_name"];
        $this->db->update($this->_table, $this, array('id' => $post['id']));
    }

    public function delete($id) {
        return $this->db->delete($this->_table, array("id" => $id));
    }
}