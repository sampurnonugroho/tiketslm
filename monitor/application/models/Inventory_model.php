<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_model extends CI_Model {

    public function getAll() {
        $query = $this->db->query('SELECT * FROM inventory WHERE active="y"');
		return $query->result();
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
	
	public function getkodekaryawan() {
        $query = $this->db->query("select max(nik) as max_code FROM karyawan");

        $row = $query->row_array();

        $max_id = $row['max_code'];
        $max_fix = (int) substr($max_id, 1, 4);

        $max_nik = $max_fix + 1;

        $nik = "K".sprintf("%04s", $max_nik);
        return $nik;
    }
}