<?php
    function is_logged_in() {
        // Get current CodeIgniter instance
        $CI =& get_instance();
        // We need to use $CI->session instead of $this->session
        $user = $CI->session->userdata('id_user');
        if (!isset($user)) { return false; } else { return "true"; }
    }
	
	function rest_api() {
		$api = "http://www.pt-bijak.co.id/rest_api_dev_minggu/server/api";
		// $api = "http://localhost/bijak/server/api";
		return $api;
	}