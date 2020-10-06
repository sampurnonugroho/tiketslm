<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maps extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	function __construct() {
        parent::__construct();
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	 
	public function index()
	{
		$this->load->view('maps');
	}
	
	public function get_marker() 
	{
	    ini_set('precision', -1);
        ini_set('serialize_precision', -1);
		$q = "SELECT * FROM map_tracking";
		
		$res = $this->db->query($q)->result_array();
		
		$i = 0;
		$item = array();
		foreach($res as $r) {
		    $lat =  floatval($r['lat']);
		    $lon =  floatval($r['lon']);
			$item[$i]['lat'] = $lat;
			$item[$i]['lng'] = $lon;
			$i++;
		}
		
// 		print_r($item);
		
		echo json_encode($item);
	}
}
