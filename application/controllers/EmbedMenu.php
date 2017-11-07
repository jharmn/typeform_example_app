<?php

class EmbedMenu extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->config->load('form');
	}

	public function index() {
		$data['menu_form_id'] = $this->config->item('menu_form_id');
		$data['typeform_username'] = $this->config->item('typeform_username');

		$this->load->view('templates/header');
		$this->load->view('embed/menu_builder', $data);
		$this->load->view('templates/footer');	
	}
}
