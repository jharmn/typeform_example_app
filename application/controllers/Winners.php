<?php
class Winners extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->model('entries_model');
        }

        public function index()
        {
                $data['winners'] = $this->entries_model->get_winners();
		$data['title'] = 'Contest winners!';

        	$this->load->view('templates/header', $data);
        	$this->load->view('entries/index', $data);
        	$this->load->view('templates/footer');
        }
}
