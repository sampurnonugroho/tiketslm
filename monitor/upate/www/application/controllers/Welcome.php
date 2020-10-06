<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Default method
	 */
	public function index()
	{	
		$data = array(
			array(
				'nama' => 'Es Kepal Milo',
				'deskripsi' => 'Deskripsi es kepal...'
			),
			array(
				'nama' => 'Es Cincau',
				'deskripsi' => 'Deskripsi es cincau...'
			),
			array(
				'nama' => 'Es Teler',
				'deskripsi' => 'Deskripsi es teler...'
			),
		);
		
		return view('admin/branch/index', ['posts' => $data]);
	}
}
