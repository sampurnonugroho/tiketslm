<?php
    function is_logged_in() {
        // Get current CodeIgniter instance
        $CI =& get_instance();
        // We need to use $CI->session instead of $this->session
        $user = $CI->session->userdata('id_user');
        if (!isset($user)) { return false; } else { return "true"; }
    }
	
	function rest_api() {
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if(get_domain($actual_link)=='pt-bijak.co.id') {
			// $api = "http://www.pt-bijak.co.id/repo_fix/rest_api_release/server/api";
			$api = "http://localhost/deni/tiketslm/rest_api_release/server/api";
		} else {
			// $api = "http://www.pt-bijak.co.id/repo_fix/rest_api_release/server/api";
			$api = "http://localhost/deni/tiketslm/rest_api_release/server/api";
		}
		
		return $api;
	}