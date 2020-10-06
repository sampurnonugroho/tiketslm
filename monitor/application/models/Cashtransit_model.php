<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cashtransit_model extends CI_Model {

    public function datacashtransit() {
        return $this->db->get("cashtransit")->result();
    }

    public function getById($id) {
        return $this->db->get_where($this->_table, ["id" => $id])->row();
    }

    public function save() {
        $post = $this->input->post();
        $this->name = $post["name"];
        $this->db->insert($this->_table, $this);
    }

    public function update() {
        $post = $this->input->post();
        $this->name = $post["name"];
        $this->db->update($this->_table, $this, array('id' => $post['id']));
    }

    public function delete($id) {
        return $this->db->delete($this->_table, array("id" => $id));
    }
}