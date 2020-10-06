<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {
    public function __construct() {
        parent::__construct();
		$this->load->library('curl');
    }
	
	public function index() {
		return view('coba', $data);
	}
	
	function json($print = "false"){
		$this->load->library('datatables');
		
		// $this->datatables->add_column('foto', '<img src="http://www.rutlandherald.com/wp-content/uploads/2017/03/default-user.png" width=20>', 'foto');
		// $this->datatables->select('nama_lengkap,email,no_hp');
		// $this->datatables->add_column('action', anchor('karyawan/edit/.$1','Edit',array('class'=>'btn btn-danger btn-sm')), 'id_pegawai');
		// $this->datatables->from('karyawan');
		
		
		$this->datatables->select('nik,id_karyawan,nama,jk');
		$this->datatables->add_column('foto', '<img src="'.base_url().'upload/client/$1" width="100" height="100"></img>', 'picture');
        $this->datatables->add_column('action', anchor('karyawan/edit/$1','Edit',array('class'=>'btn btn-danger btn-sm')), 'nik');
        $this->datatables->from('karyawan');
		
		if($print=="false") {
			return print_r($this->datatables->generate());
		} else {
			echo "<pre>";
			print_r(json_decode($this->datatables->generate()));
		}
    }
	
	function json2($print = "false"){
		// $param['table'] = 'master_seal'; //nama tabel dari database
		$param['query'] = 'SELECT * FROM master_seal'; //nama tabel dari database
		$param['column_order'] = array('kode', 'jenis', 'status'); //field yang ada di table user
		$param['column_search'] = array('kode','jenis','status'); //field yang diizin untuk pencarian 
		$param['order'] = array('id' => 'asc');
		
		$data['param'] = json_encode($param);
		$data['post'] = $_REQUEST;
		
		if($print=="false") {
			echo $this->curl->simple_get(rest_api().'/datatables', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		} else if($print=="show") {
			echo $this->curl->simple_get(rest_api().'/datatables/show', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10));
		} else {
			echo "<pre>";
			print_r(json_decode($this->curl->simple_get(rest_api().'/datatables', array('data'=>$data), array(CURLOPT_BUFFERSIZE => 10))));
		}
    }
	
	function reff() {
		// https://datatables.net/extensions/searchpanes/examples/customFiltering/customPane.html
		// https://makitweb.com/how-to-add-custom-filter-in-datatable-ajax-and-php/
		// https://tondanoweb.com/tutorial-codeigniter-tutorial-cara-menggunakan-jquery-datatables-di-codeigniter-bagian-kedua/
		// https://www.webslesson.info/2018/09/add-server-side-datatables-custom-filter-using-php-with-ajax.html
		// https://belajarphp.net/tutorial-codeigniter-datatables/
		// https://belajarphp.net/tutorial-datatables-php-dan-mysql/
		// https://github.com/cryogenix/Ignited-Datatables
	}
}