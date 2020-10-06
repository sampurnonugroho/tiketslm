<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
    
    public function proses_login($user, $pass) {
        $akses = $this->db->query("select A.username, B.nama, A.level, B.id_jabatan, C.id_dept FROM user A 
            LEFT JOIN karyawan B ON B.nik = A.username
            LEFT JOIN bagian_departemen C ON C.id_bagian_dept = B.id_bagian_dept
        WHERE A.username = '$user' AND A.password = '$pass'");

        return $akses;
    }
}