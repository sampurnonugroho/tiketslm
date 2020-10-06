<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MyTicket_model extends CI_Model {
	
	public function datamyticket($id) {
		$query = $this->db->query("SELECT A.progress, A.tanggal_proses, A.tanggal_solved, A.id_teknisi, D.feedback, A.status, A.id_ticket, A.tanggal, B.nama_sub_kategori, C.nama_kategori
                                   FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN history_feedback D ON D.id_ticket = A.id_ticket
                                   WHERE A.reported = '$id'");
		return $query->result();
	}
}