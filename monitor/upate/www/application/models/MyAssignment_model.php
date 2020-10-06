<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MyAssignment_model extends CI_Model {
	
	public function datamyassignment($id) {
		$query = $this->db->query("SELECT A.progress, A.status, A.id_ticket, A.reported, A.tanggal, B.nama_sub_kategori, C.nama_kategori
                                   FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported
                                   LEFT JOIN teknisi E ON E.id_teknisi = A.id_teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.nik
                                   WHERE F.nik = '$id'
                                   AND A.status IN (3,4,5,6)
                                   ");
        return $query->result();
	}
}