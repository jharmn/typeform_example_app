<?php

class Embed extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->config->load('form');
	}

	public function index() {
		$data['form_id'] = $this->config->item('form_id');
		$data['typeform_username'] = $this->config->item('typeform_username');

		$this->load->view('templates/header');
		$this->load->view('embed/index', $data);
		$this->load->view('templates/footer');	
	}
}
