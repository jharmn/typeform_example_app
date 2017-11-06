<?php

class EntriesApi extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
		
		$this->config->load('form');
		$this->load->helper('typeform_fields');
        }

	private function getEntries() {
		if (isset($_SESSION['access_token'])) {
			$accessToken = $_SESSION['access_token'];
			#echo 'Access Token: ' .$accessToken. "<br>";
		
			$client = new GuzzleHttp\Client();
			$res = $client->request('GET', "https://api.typeform.com/forms/".$this->config->item("form_id")."/responses?completed=true", [
				'headers' => [
				'Authorization' => 'Bearer '.$accessToken,
				'Accept' => 'application/json'	
				]
			]);
			$json = json_decode($res->getBody(), true);
			$data = array();
			foreach ($json['items'] as $item) {
				$answers = $item['answers'];
				$first_name = textAnswerByField($answers, "short_text", $this->config->item("webhook_fname_field_id"));
				$last_name = textAnswerByField($answers, "short_text", $this->config->item("webhook_lname_field_id"));
				$email = textAnswerByField($answers, "email", $this->config->item("webhook_email_field_id"));
				$image = textAnswerByField($answers, "file_url", $this->config->item("webhook_image_field_id"));
				$data[sizeOf($data)] = array(
					"FirstName"=>$first_name,
					"LastName"=>$last_name,
					"Email"=>$email,
					"ImageUrl"=>$image);
			}
			return $data;
		}

	}

        public function index()
        {
		$data['title'] = 'Meet and Greet! (from API)';
		$data['entries'] = $this->getEntries();

        	$this->load->view('templates/header');
        	$this->load->view('entries/index', $data);
        	$this->load->view('templates/footer');
        }
}
