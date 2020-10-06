<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends CI_Model {

    public function datalist_ticket() {
        // $query = $this->db->query("SELECT D.nama, F.nama_dept, A.status, A.id_ticket, A.tanggal, B.nama_sub_kategori, C.nama_kategori
                                   // FROM ticket A 
                                   // LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   // LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   // LEFT JOIN karyawan D ON D.nik = A.reported
                                   // LEFT JOIN bagian_departemen E ON E.id_bagian_dept = D.id_bagian_dept
                                   // LEFT JOIN departemen F ON F.id_dept = E.id_dept
                                   // WHERE A.status IN (2,3,4,5,6)");
        // return $query->result();
    }

    public function getnotif_list() {
        // $sql_listticket = "SELECT COUNT(id_ticket) AS jml_list_ticket FROM ticket WHERE status = 2";
        // $row_listticket = $this->db->query($sql_listticket)->row();

        // return $row_listticket->jml_list_ticket;
    }

    public function getnotif_approval($id_dept) {
        // $sql_approvalticket = "SELECT COUNT(A.id_ticket) AS jml_approval_ticket FROM ticket A 
        // LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori 
        // LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
        // LEFT JOIN karyawan D ON D.nik = A.reported 
        // LEFT JOIN bagian_departemen E ON E.id_bagian_dept = D.id_bagian_dept WHERE E.id_dept = $id_dept AND status = 1";
        // $row_approvalticket = $this->db->query($sql_approvalticket)->row();

        // return $row_approvalticket->jml_approval_ticket;
    }

    public function getnotif_assign($id_user) {
        // $sql_assignmentticket = "SELECT COUNT(id_ticket) AS jml_assignment_ticket FROM ticket WHERE status = 3 AND id_teknisi='$id_user'";
        // $row_assignmentticket = $this->db->query($sql_assignmentticket)->row();

        // return $row_assignmentticket->jml_assignment_ticket;
    }
    
}