<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_model extends CI_Model {

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
        $query = $this->db->query('SELECT A.nama, A.nik, A.alamat, A.jk, C.nama_bagian_dept, B.nama_jabatan, D.nama_dept
                               FROM karyawan A LEFT JOIN jabatan B ON B.id_jabatan = A.id_jabatan
                                               LEFT JOIN bagian_departemen C ON C.id_bagian_dept = A.id_bagian_dept
                                               LEFT JOIN departemen D ON D.id_dept = C.id_dept');
											   
		 // $query = $this->db->query('SELECT * FROM karyawan A LEFT JOIN bagian_departemen C ON C.id_bagian_dept = A.id_bagian_dept LEFT JOIN departemen D ON D.id_dept = C.id_dept LEFT JOIN jabatan B ON B.id_jabatan = C.id_jabatan ');
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