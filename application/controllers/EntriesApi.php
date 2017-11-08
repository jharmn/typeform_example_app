<?php

class EntriesApi extends CI_Controller {

	private $accessToken = "";

        public function __construct()
        {
                parent::__construct();
		
		$this->config->load('form');
		$this->load->helper('typeform_fields');
		
		// Use personal token to get your own data
		$this->accessToken = $this->config->item('typeform_access_token');
		// Switch to OAuth access token stored in session
		#$this->accessToken = $_SESSION['access_token'];
		#echo 'Access Token: ' .$this->accessToken. "<br>";
        }

	private function getFields() {
		$client = new GuzzleHttp\Client();

		// This would be way smarter to cache in a local db and update on occasion
		$res = $client->request('GET', "https://api.typeform.com/forms/".$this->config->item("form_id"), [
			'headers' => [
			'Authorization' => 'Bearer '.$this->accessToken,
			'Accept' => 'application/json'	
			]
		]);
		$form = json_decode($res->getBody(), true);
		return $form['fields'];
	}

	private function getResponses() {
		$client = new GuzzleHttp\Client();
		$res = $client->request('GET', "https://api.typeform.com/forms/".$this->config->item("form_id")."/responses?completed=true", [
			'headers' => [
			'Authorization' => 'Bearer '.$this->accessToken,
			'Accept' => 'application/json'	
			]
		]);
		return json_decode($res->getBody(), true);
	}

	private function getEntries() {
		
		$fields = $this->getFields();	
		$json = $this->getResponses();	
			
		$data = array();
		foreach ($json['items'] as $item) {
			$answers = $item['answers'];
			#$first_name = textAnswerByField($answers, $this->config->item("fname_field_id"));
			#$last_name = textAnswerByField($answers, $this->config->item("lname_field_id"));
			#$email = textAnswerByField($answers, $this->config->item("email_field_id"));
			#$image = textAnswerByField($answers, $this->config->item("image_field_id"));
			$first_name = textAnswerByRef($fields, $answers, $this->config->item("fname_field_ref"));
			$last_name = textAnswerByRef($fields, $answers, $this->config->item("lname_field_ref"));
			$email = textAnswerByRef($fields, $answers, $this->config->item("email_field_ref"));
			$image = textAnswerByRef($fields, $answers, $this->config->item("image_field_ref"));

			$data[sizeOf($data)] = array(
				"FirstName"=>$first_name,
				"LastName"=>$last_name,
				"Email"=>$email,
				"ImageUrl"=>$image);
		}
		return $data;

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
