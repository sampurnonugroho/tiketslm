<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Map extends REST_Controller {
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    } 	 	
	
	public function index_get() {
		echo "HAHAHAHA1";
	}
	
	public function index_post() {
		$data = $this->input->post('data');
		$id_user = $this->input->post('id_user');
		$json = json_decode($data, true);
		// echo "time : ".$json['time']."<br>";
		// echo "latitude : ".$json['lat']."<br>";
		// echo "longitude : ".$json['lon']."<br>";
		// echo "accuracy : ".$json['acy']."<br>";
		// echo "battery : ".$json['battery']."<br>";
		
		$data = array(
			'id_user'	=> $id_user,
			'time'		=> $json['time'],
			'lat'		=> $json['lat'],
			'lon'		=> $json['lon'],
			'acy'		=> $json['acy'],
			'bat'		=> $json['battery']
		);
		
		$insert = $this->db->insert('map_tracking', $data);
        if ($insert) {
            echo json_encode($data);
        } else {
            echo json_encode(array('status' => 'fail'));
        }
	}
	
	function update_location_get() {
		$id_user = $this->input->get('id_user');
		$latlng = $this->input->get('latlng');
		$accuracy = $this->input->get('accuracy');
		
		echo "ID USER : ".$id_user."\nLATLNG : ".json_encode($latlng)."\nACCURACY : ".$accuracy." ";
		$data['latlng'] = json_encode($latlng);
		$data['accuracy'] = $accuracy;
		
		$this->db->where('username', $id_user);
        $update = $this->db->update('user', $data);
		
		if ($update) {
			echo "\nUpdate latlng success";
		} else {
			echo "\nUpdate latlng failed";
		}
	}
}