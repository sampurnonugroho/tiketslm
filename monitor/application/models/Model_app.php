<?php

class Model_app extends CI_Model{
    function __construct(){
        parent::__construct();
		$this->load->library('curl');
    }

    //  ================= AUTOMATIC CODE ==================
    public function getkodeticket() {
        $query = "select max(id_ticket) as max_code FROM ticket";

        $row = $query->row_array();

        $max_id = $row['max_code'];
        $max_fix = (int) substr($max_id, 9, 4);

        $max_nik = $max_fix + 1;

        $tanggal = $time = date("d");
        $bulan = $time = date("m");
        $tahun = $time = date("Y");

        $nik = "T".$tahun.$bulan.$tanggal.sprintf("%04s", $max_nik);
        return $nik;
    }

    public function getkodekaryawan() {
        $query = "select max(nik) as max_code FROM karyawan";

        $row = $query->row_array();

        $max_id = $row['max_code'];
        $max_fix = (int) substr($max_id, 1, 4);

        $max_nik = $max_fix + 1;

        $nik = "K".sprintf("%04s", $max_nik);
        return $nik;
    }

    public function getkodeteknisi() {
        $query = "select max(id_teknisi) as max_code FROM teknisi";

        $row = $query->row_array();

        $max_id = $row['max_code'];
        $max_fix = (int) substr($max_id, 1, 4);

        $max_id_teknisi = $max_fix + 1;

        $id_teknisi = "T".sprintf("%04s", $max_id_teknisi);
        return $id_teknisi;
    }

    public function getkodeuser() {
        $query = "select max(id_user) as max_code FROM user";

        $row = $query->row_array();

        $max_id = $row['max_code'];
        $max_fix = (int) substr($max_id, 1, 4);

        $max_id_user = $max_fix + 1;

        $id_user = "U".sprintf("%04s", $max_id_user);
        return $id_user;
    }
    
    public function datajabatan() {
		$query = 'SELECT * FROM jabatan';
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function datakaryawan() {
		$query = 'SELECT A.nama, A.nik, A.alamat, A.jk, C.nama_bagian_dept, B.nama_jabatan, D.nama_dept
								   FROM karyawan A LEFT JOIN jabatan B ON B.id_jabatan = A.id_jabatan
												   LEFT JOIN bagian_departemen C ON C.id_bagian_dept = A.id_bagian_dept
												   LEFT JOIN departemen D ON D.id_dept = C.id_dept';
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function datalist_ticket() {
        $query = "SELECT D.nama, F.nama_dept, A.status, A.id_ticket, A.tanggal, B.nama_sub_kategori, C.nama_kategori
                                   FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported
                                   LEFT JOIN bagian_departemen E ON E.id_bagian_dept = D.id_bagian_dept
                                   LEFT JOIN departemen F ON F.id_dept = E.id_dept
                                   WHERE A.status IN (2,3,4,5,6)";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function data_trackingticket($id) {
        $query = "SELECT A.tanggal, A.status, A.deskripsi, B.nama
                                   FROM tracking A 
                                   LEFT JOIN karyawan B ON B.nik = A.id_user
                                   WHERE A.id_ticket ='$id'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }


    public function datainformasi() {
        $query = "SELECT A.tanggal, A.subject, A.pesan, C.nama, A.id_informasi
                                   FROM informasi A 
                                   LEFT JOIN karyawan C ON C.nik =  A.id_user
                                   WHERE A.status = 1";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function datamyticket($id) {
        $query = "SELECT A.progress, A.tanggal_proses, A.tanggal_solved, A.id_teknisi, D.feedback, A.status, A.id_ticket, A.tanggal, B.nama_sub_kategori, C.nama_kategori
                                   FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN history_feedback D ON D.id_ticket = A.id_ticket
                                   WHERE A.reported = '$id'";
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }


    public function datamyassignment($id) {
        $query = "SELECT A.progress, A.status, A.id_ticket, A.reported, A.tanggal, B.nama_sub_kategori, C.nama_kategori
                                   FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported
                                   LEFT JOIN teknisi E ON E.id_teknisi = A.id_teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.nik
                                   WHERE F.nik = '$id'
                                   AND A.status IN (3,4,5,6)";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function dataapproval($id) {
		$query = "SELECT A.status, D.nama ,A.status, A.id_ticket, A.tanggal, B.nama_sub_kategori, C.nama_kategori 
			FROM ticket A 
			LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori 
			LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
			LEFT JOIN karyawan D ON D.nik = A.reported 
			LEFT JOIN bagian_departemen E ON E.id_bagian_dept = D.id_bagian_dept WHERE E.id_dept = $id AND A.status = 1 OR  A.status= 0";
			
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function datadepartemen() {
		$query = 'SELECT * FROM departemen';
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function databagiandepartemen() {
		$query = 'SELECT * FROM bagian_departemen A LEFT JOIN departemen B ON B.id_dept = A.id_dept ';
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function datakondisi() {
		$query = 'SELECT * FROM kondisi';
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function datateknisi() {
		$query = $this->db->query('SELECT A.point, A.id_teknisi, B.nama, B.jk, C.nama_kategori, A.status, A.point FROM teknisi A 
									LEFT JOIN karyawan B ON B.nik = A.nik
									LEFT JOIN kategori C ON C.id_kategori = A.id_kategori
									
									 ');
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }


    public function datareportteknisi($id) {
		$query = $this->db->query("SELECT A.progress, A.tanggal_proses, A.status, A.problem_summary, B.nama, D.nama_kategori, F.nama_dept  FROM ticket A 
									LEFT JOIN karyawan B ON B.nik = A.reported
									LEFT JOIN sub_kategori C ON C.id_sub_kategori = A.id_sub_kategori
									LEFT JOIN kategori D ON D.id_kategori = C.id_kategori
									LEFT JOIN bagian_departemen E ON E.id_bagian_dept = B.id_bagian_dept
									LEFT JOIN departemen F ON F.id_dept = E.id_dept
									WHERE id_teknisi = '$id'"                        
									 );
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }


    public function datauser() {
        $query = $this->db->query('SELECT A.username, A.level, A.id_user, B.nik, B.nama, A.password, C.id_dept, D.nama_dept 
            FROM user A LEFT JOIN karyawan B ON B.nik = A.username 
            LEFT JOIN bagian_departemen C ON C.id_bagian_dept = B.id_bagian_dept 
            LEFT JOIN departemen D ON D.id_dept = C.id_dept
                                 ');

        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }


    public function datauser_client() {
         $query = 'SELECT * FROM user_client LEFT JOIN client ON (user_client.id_user_client=client.id)';

         $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function datakategori() {
		$query = 'SELECT * FROM kategori';
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function dataassigment($id) {
		$query = $this->db->query('SELECT A.status, D.nama, C.id_kategori, A.id_ticket, A.tanggal, B.nama_sub_kategori, C.nama_kategori
					FROM ticket A 
					LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
					LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
					LEFT JOIN karyawan D ON D.nik = A.reported 
					WHERE A.id_teknisi = "$id"');
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }

    public function datasubkategori() {
		$query = 'SELECT * FROM sub_kategori A LEFT JOIN kategori B ON B.id_kategori = A.id_kategori ';
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }
	
	public function dataclient() {
		$query = 'SELECT * FROM client WHERE 1';
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }
	
	public function dataclientcit() {
		$query = 'SELECT * FROM client_cit WHERE 1';
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }
	
	public function datahandphone() {
		$query = 'SELECT * FROM handphone WHERE 1';
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
		return $result;
    }


    public function dropdown_departemen() {
        $query = "SELECT * FROM departemen ORDER BY nama_dept";
         $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
            $value[''] = '-- PILIH --';
            foreach ($result as $row){
                $value[$row->id_dept] = $row->nama_dept;
            }
            return $value;
    }

    public function dropdown_kategori() {
        $query = "SELECT * FROM kategori ORDER BY nama_kategori";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
            $value[''] = '-- PILIH --';
            foreach ($result as $row){
                $value[$row->id_kategori] = $row->nama_kategori;
            }
            return $value;
    }

    public function dropdown_karyawan()
    {
        // $query = "SELECT A.nama, A.nik, D.level FROM karyawan A 
                // LEFT JOIN bagian_departemen B ON B.id_bagian_dept = A.id_bagian_dept
                // LEFT JOIN departemen C ON C.id_dept = B.id_dept 
                // LEFT JOIN user D ON A.nik = D.username
				// WHERE D.level='FLM' OR  D.level='SLM' AND A.nik NOT IN (SELECT nik FROM teknisi)
                // ORDER BY nama";
        $query = "SELECT A.nama, A.nik, D.level FROM karyawan A 
                LEFT JOIN bagian_departemen B ON B.id_bagian_dept = A.id_bagian_dept
                LEFT JOIN departemen C ON C.id_dept = B.id_dept 
                LEFT JOIN user D ON A.nik = D.username
				WHERE (D.level='FLM' OR D.level='SLM') AND A.nik NOT IN (SELECT nik FROM teknisi)
                ORDER BY nama";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
            $value[''] = '-- PILIH --';
            foreach ($result as $row){
                $value[$row->nik] = " (".$row->level.") ".$row->nama;
            }
            return $value;
    }
	
	public function dropdown_karyawan_user()
    {
        $query = "SELECT A.nama, A.nik FROM karyawan A 
                LEFT JOIN bagian_departemen B ON B.id_bagian_dept = A.id_bagian_dept
                LEFT JOIN departemen C ON C.id_dept = B.id_dept 
                WHERE A.nik NOT IN (SELECT username FROM `user` WHERE 1)
                ORDER BY nama";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
            $value[''] = '-- PILIH --';
            foreach ($result as $row){
                $value[$row->nik] = $row->nama;
            }
            return $value;
    }
	
	
	public function dropdown_user_client()
    {
        $query = "SELECT bank, lokasi, id FROM client WHERE id NOT IN (SELECT id_user_client FROM `user_client` WHERE 1)";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
            $value[''] = '-- PILIH --';
            foreach ($result as $row){
                $value[$row->id] = $row->bank." (".$row->lokasi.")";
            }
            return $value;
    }
	
	public function dropdown_user_client2()
    {
        $query = "SELECT bank, lokasi, id FROM client WHERE 1";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
            $value[''] = '-- PILIH --';
            foreach ($result as $row){
                $value[$row->id] = $row->bank." (".$row->lokasi.")";
            }
            return $value;
    }
	
	public function dropdown_driver()
    {
        $query = "SELECT A.nama, A.nik FROM karyawan A 
                LEFT JOIN bagian_departemen B ON B.id_bagian_dept = A.id_bagian_dept
                LEFT JOIN departemen C ON C.id_dept = b.id_dept 
				LEFT JOIN jabatan D ON A.id_jabatan=D.id_jabatan
                WHERE D.nama_jabatan='DRIVER'
                ORDER BY nama";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
            $value[''] = '-- PILIH --';
            foreach ($result as $row){
                $value[$row->nik] = $row->nama;
            }
            return $value;
    }
	
	public function dropdown_custodian()
    {
        $query = "SELECT A.nama, A.nik FROM karyawan A 
                LEFT JOIN bagian_departemen B ON B.id_bagian_dept = A.id_bagian_dept
                LEFT JOIN departemen C ON C.id_dept = b.id_dept 
				LEFT JOIN jabatan D ON A.id_jabatan=D.id_jabatan
                WHERE D.nama_jabatan='CUSTADIAN'
                ORDER BY nama";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
            $value[''] = '-- PILIH --';
            foreach ($result as $row){
                $value[$row->nik] = $row->nama;
            }
            return $value;
    }
	
	public function dropdown_army()
    {
        $query = "SELECT A.nama, A.nik FROM karyawan A 
                LEFT JOIN bagian_departemen B ON B.id_bagian_dept = A.id_bagian_dept
                LEFT JOIN departemen C ON C.id_dept = b.id_dept 
				LEFT JOIN jabatan D ON A.id_jabatan=D.id_jabatan
                WHERE D.nama_jabatan='ARMY'
                ORDER BY nama";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
            $value[''] = '-- PILIH --';
            foreach ($result as $row){
                $value[$row->nik] = $row->nama;
            }
            return $value;
    }

    public function dropdown_jabatan()
    {
        $query = "SELECT * FROM jabatan ORDER BY nama_jabatan";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
        $value[''] = '-- PILIH --';
        foreach ($result as $row){
            $value[$row->id_jabatan] = $row->nama_jabatan;
        }
        return $value;
    }

    public function dropdown_kondisi()
    {
        $query = "SELECT * FROM kondisi ORDER BY nama_kondisi";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
        $value[''] = '-- PILIH --';
        foreach ($result as $row){
            $value[$row->id_kondisi] = $row->nama_kondisi."  -  (TARGET PROSES ".$row->waktu_respon." "."HARI)";
        }
        return $value;
    }

    public function dropdown_bagian_departemen($id_departemen)
    {
        // $query = "SELECT * FROM bagian_departemen A LEFT JOIN jabatan B ON(A.id_jabatan=B.id_jabatan) WHERE A.id_dept ='$id_departemen' ORDER BY B.nama_jabatan";
		$query = "SELECT * FROM bagian_departemen where id_dept ='$id_departemen' ORDER BY nama_bagian_dept";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
           
        $value[''] = '-- PILIH --';
        foreach ($result as $row){
            $value[$row->id_bagian_dept] = $row->nama_bagian_dept;
        }
        return $value;
    }

    public function dropdown_sub_kategori($id_kategori)
    {
        $query = "SELECT * FROM sub_kategori where id_kategori ='$id_kategori' ORDER BY nama_sub_kategori";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
        $value[''] = '-- PILIH --';
        foreach ($result as $row){
            $value[$row->id_sub_kategori] = $row->nama_sub_kategori;
        }
        return $value;
    }

    function dropdown_teknisi($id_kategori)
    {

        $query = "SELECT A.id_teknisi, B.nama, B.jk, C.nama_kategori, A.status, A.point FROM teknisi A 
                                LEFT JOIN karyawan B ON B.nik = A.nik
                                LEFT JOIN kategori C ON C.id_kategori = A.id_kategori
                                WHERE A.id_kategori ='$id_kategori'";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
        $value[''] = '-- PILIH --';
        foreach ($result as $row){
            $value[$row->id_teknisi] = $row->nama;
        }
        return $value;

    }


    public function dropdown_jk()
    {
        $value[''] = '--PILIH--';            
        $value['LAKI-LAKI'] = 'LAKI-LAKI';
        $value['PEREMPUAN'] = 'PEREMPUAN';           
            
            return $value;
    }
	
	public function dropdown_status()
    {
        $value[''] = '--PILIH--';            
        $value['Y'] = 'ACTIVE';
        $value['N'] = 'INACTIVE';           
        $value['RESIGN'] = 'RESIGN';           
            
            return $value;
    }

    public function dropdown_level()
    {
        $value[''] = '--PILIH--';            
        $value['ADMIN'] = 'ADMIN';
        $value['TEKNISI'] = 'TEKNISI';
        $value['USER'] = 'USER';           
            
            return $value;
    }

	public function credential() {
		// $credential = array(
			// array(
				// "short"=>"ADMIN",
				// "long"=>"ADMINISTRATOR"
			// ),
			// array(
				// "short"=>"OPMAMANGER",
				// "long"=>"OPERATIONAL MANAGER"
			// ),
			// array(
				// "short"=>"CS",
				// "long"=>"CUSTOMER SERVICE"
			// ),
			// array(
				// "short"=>"CLIENT",
				// "long"=>"CLIENT (PERUSAHAAN/BANK)"
			// ),
			// array(
				// "short"=>"FLM",
				// "long"=>"TEKNISI FIRST LEVEL MAINTENANCE"
			// ),
			// array(
				// "short"=>"SLM",
				// "long"=>"TEKNISI SECOND LEVEL MAINTENANCE"
			// ),
			// array(
				// "short"=>"CIT",
				// "long"=>"STAFF CASH IN TRANSIT"
			// ),
			// array(
				// "short"=>"CIR",
				// "long"=>"STAFF CASH IN REPORT"
			// ),
			// array(
				// "short"=>"DIRECTOR",
				// "long"=>"DIREKTUR PERUSAHAAN"
			// ),
			// array(
				// "short"=>"GM",
				// "long"=>"GENERAL MANAGER"
			// ),
			// array(
				// "short"=>"GS",
				// "long"=>"GENERAL STAFF"
			// ),
			// array(
				// "short"=>"SYSMAN",
				// "long"=>"SYSTEM MANAGER"
			// ),
			// array(
				// "short"=>"TS",
				// "long"=>"TECHNICAL SUPPORT"
			// ),
			// array(
				// "short"=>"USER",
				// "long"=>"USER"
			// )
		// );
		
		// USER LEVEL
		 // SYSMAN
		 // ADMIN
		 // LOGMANAGER
		 // OPMANAGER
		 
		$value[''] = '--PILIH--';            
		$value['LEVEL1'] = 'LEVEL 1 [ALL ACCESS]';  
		$value['LEVEL2'] = 'LEVEL 2 [VIEW, CREATE, DELETE]';  
		$value['LEVEL3'] = 'LEVEL 3 [VIEW ONLY]';  
		$value['SYSMAN'] = 'SYSTEM MANAGER';  
        $value['ADMIN'] = 'ADMINISTRATOR';
		$value['DIRECTOR'] = 'DIREKTUR PERUSAHAAN';  
		$value['GM'] = 'GENERAL MANAGER';  
		$value['GS'] = 'GENERAL STAFF';  
		$value['TS'] = 'TECHNICAL SUPPORT';  
		$value['CLIENT'] = 'CLIENT (PERUSAHAAN/BANK)';  
		$value['LOGMANAGER'] = 'LOGISTIK MANAGER';
		$value['LOG'] = 'LOGISTIK';
		$value['OPMANAGER'] = 'OPERATIONAL MANAGER';
		$value['OPR'] = 'OPERATIONAL';
		$value['CPC'] = 'CASH PROCESSING CENTER';
		$value['CS'] = 'CUSTOMER SERVICE';           
		$value['FLM'] = 'TEKNISI FIRST LEVEL MAINTENANCE';  
		$value['SLM'] = 'TEKNISI SECOND LEVEL MAINTENANCE';  
		$value['CIT'] = 'STAFF CASH IN TRANSIT';  
		$value['CIR'] = 'STAFF CASH IN REPORT';  
		$value['CUSTODI'] = 'CUSTODIAN';           
		$value['USER'] = 'USER';           
            
		return $value;
	}

	public function get_batch_code() {
		$query = "SELECT MAX(barcode) AS max_code FROM barcode_batch";

        $row = $query->row_array();

        $max_id = $row['max_code'];
        $max_fix = (int) substr($max_id, 9, 4);

        $max_nik = $max_fix + 1;

        $tanggal = $time = date("d");
        $bulan = $time = date("m");
        $tahun = $time = date("Y");

        $nik = $tahun.$bulan.$tanggal."P".sprintf("%04s", $max_nik);
        return $nik;
	}

	public function get_child_code($code = "P000100000001", $count = 10) {
		$parent = $code;
		$p = substr($parent, 8, 5);
		
		$max_id = $code;
        $max_fix = (int) substr($code, 5, 8);
		
		$data = [];
		for($i=1; $i<=$count; $i++) {
			$max_nik = $i;
			$nik = $p.sprintf("%08s", $max_nik);
			$data[$i] = $nik;
		}
		
        return $data;
	}

	public function dropdown_batch_barcode()
    {
        $query = "SELECT barcode FROM barcode_batch";
        $result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$query), array(CURLOPT_BUFFERSIZE => 10)));
            
            $value[''] = '-- PILIH --';
            foreach ($result as $row){
                $value[$row->barcode] = $row->barcode;
            }
            return $value;
    }
	
	public function suggest_pic() {
		$search = $this->input->post('search');
		
		$sql = "SELECT * FROM user LEFT JOIN karyawan ON(user.username=karyawan.nik) WHERE (user.level='LEVEL1' OR user.level='LEVEL2' OR user.level='LEVEL3') AND karyawan.nama LIKE '%$search%'";
		$sql = "SELECT * FROM user LEFT JOIN karyawan ON(user.username=karyawan.nik)";
		// echo $sql;
		$result = json_decode($this->curl->simple_get(rest_api().'/select/query_all', array('query'=>$sql), array(CURLOPT_BUFFERSIZE => 10)));
		// print_r($result->result());
		
		$list = array();
		if (count($result) > 0) {
			$key=0;
			foreach ($result as $row) {
				$list[$key]['id'] = $row->username;
				$list[$key]['text'] = $row->nama; 
				$key++;
			}
			echo json_encode($list);
		} else {
			echo json_encode($list);
		}
	}
}