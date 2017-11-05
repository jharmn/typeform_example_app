<?php
class Entries extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->model('entries_model');
        }

        public function index()
        {
                $data['entries'] = $this->entries_model->get_entries();
		$data['title'] = 'Meet and Greet!';

        	$this->load->view('templates/header', $data);
        	$this->load->view('entries/index', $data);
        	$this->load->view('templates/footer');
        }
}
