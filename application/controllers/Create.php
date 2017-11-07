<?php

class Create extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
		
		$this->config->load('form');
		#$this->load->helper('typeform_fields');
        }


	public function index()
	{
		if (isset($_SESSION['access_token'])) {
			$accessToken = $_SESSION['access_token'];
			#echo 'Access Token: ' .$accessToken. "<br>";
	
	

	}
}
