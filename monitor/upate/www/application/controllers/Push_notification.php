<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Push_notification extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
	
	public function index() {
		return view('admin/pushnotification/index', $data);
	}
}