<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_model extends CI_Model {
	
	public function dataapproval($id) {
		$query = $this->db->query("SELECT A.status, D.nama, F.nama_dept, A.status, A.id_ticket, A.tanggal, B.nama_sub_kategori, C.nama_kategori 
			FROM ticket A 
			   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
			   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
			   LEFT JOIN karyawan D ON D.nik = A.reported
			   LEFT JOIN bagian_departemen E ON E.id_bagian_dept = D.id_bagian_dept
			   LEFT JOIN departemen F ON F.id_dept = E.id_dept WHERE E.id_dept = $id AND A.status = 1 OR  A.status= 0");
		return $query->result();
	}
}