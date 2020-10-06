<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Atm_model extends CI_Model {
    private $_table = "master_atm";

    public $id;
    public $branch_name;

    public function rules() {
        return [
            [
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'required'
            ]
        ];
    }

    public function getAll() {
        return $this->db->get($this->_table)->result();
        // $data = array(
		// 	array(
		// 		'nama' => 'Es Kepal Milo',
		// 		'deskripsi' => 'Deskripsi es kepal...'
		// 	),
		// 	array(
		// 		'nama' => 'Es Cincau',
		// 		'deskripsi' => 'Deskripsi es cincau...'
		// 	),
		// 	array(
		// 		'nama' => 'Es Teler',
		// 		'deskripsi' => 'Deskripsi es teler...'
		// 	),
        // );
        
        // return $data;
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