<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WebhookListener extends CI_Controller {

	public function __construct() {
                parent::__construct();
		$this->load->helper('typeform_fields');
		$this->config->load('form');
	}

	public function view()
	{
		// Authenticate webhook by checking ?key=xxx
		$auth_key = $this->config->item('webhook_auth_key');
		parse_str(parse_url($_SERVER['REQUEST_URI'])["query"], $query);
		$key = $query['key'];
		if ($key != $auth_key) {
			show_error('Invalid key', 400);
		}

		// Deserialize JSON
 		$data = json_decode(file_get_contents('php://input'), TRUE);
		$response = $data["form_response"];

		// Route based on form_id
		if ($response["form_id"] == $this->config->item('form_id')) {
			// Entries
			$this->insertEntry($response);

		} else if ($response["form_id"] == $this->config->item('menu_form_id')) {
			$this->createMenu($response);
		}

	}

	private function insertEntry($response) {
		// Ensure uniqueness
		$token = $response["token"];
		$this->load->database();
		$query = $this->db->query("SELECT token FROM Entries WHERE Token = ".$this->db->escape($token)." LIMIT 1");
		$row = $query->row();
		if (count($row) > 0) {
			show_error('Token exists', 409);
		} else {
			// Retrieve answer/fields from webhook
			$answers = $response["answers"];
			$fields = $response['definition']['fields'];

			#$first_name = textAnswerByField($answers, $this->config->item("fname_field_id"));
			#$last_name = textAnswerByField($answers, $this->config->item("lname_field_id"));
			#$email = textAnswerByField($answers, $this->config->item("email_field_id"));
			#$image = textAnswerByField($answers, $this->config->item("image_field_id"));
			$first_name = textAnswerByRef($fields, $answers, $this->config->item("fname_field_ref"));
			$last_name = textAnswerByRef($fields, $answers, $this->config->item("lname_field_ref"));
			$email = textAnswerByRef($fields, $answers, $this->config->item("email_field_ref"));
			$image = textAnswerByRef($fields, $answers, $this->config->item("image_field_ref"));

			// Store answer/fields in db
			$insert_query = "INSERT INTO Entries (Token, FirstName, LastName, Email, ImageUrl) VALUES(".
				$this->db->escape($token).", ".
				$this->db->escape($first_name).", ".
				$this->db->escape($last_name).", ".
				$this->db->escape($email).",".
				$this->db->escape($image).")";
			if (! $this->db->query($insert_query)) {
				show_error('Unable to store webhook', 500);
			}
		}
	}

	private function createMenu($response) {
		// Lunch menu generator	
		$answers = $response['answers'];
		$fields = $response['definition']['fields'];

		$timeframe = textAnswerByRef($fields, $answers, 'timeframe');
		$mon_1 = textAnswerByRef($fields, $answers, 'mon_1');
		$mon_2 = textAnswerByRef($fields, $answers, 'mon_2');
		$tues_1 = textAnswerByRef($fields, $answers, 'tues_1');
		$tues_2 = textAnswerByRef($fields, $answers, 'tues_2');
		$weds_1 = textAnswerByRef($fields, $answers, 'weds_1');
		$weds_2 = textAnswerByRef($fields, $answers, 'weds_2');
		$thurs_1 = textAnswerByRef($fields, $answers, 'thurs_1');
		$thurs_2 = textAnswerByRef($fields, $answers, 'thurs_2');
		$fri_1 = textAnswerByRef($fields, $answers, 'fri_1');
		$fri_2 = textAnswerByRef($fields, $answers, 'fri_2');
		$ooo_text = "Out of office";

		$accessToken = $this->config->item('typeform_access_token');
		$create_form_obj = array('title' => 'Lunch menu for '.$timeframe,
			'welcome_screens' => array(
				array('ref' => 'lunch_welcome',
					'title' => 'Please select your lunch choices for '.$timeframe,
					'properties' => array( 
						'show_button' => true,
						'button_text' => 'Let\'s eat!'
					)
				)
			),
			'fields' => array(
				array('title' => 'Monday',
					'ref' => 'mon_choice',
					'type' => 'multiple_choice',
					'properties' => array(
						'allow_multiple_selection' => false,
						'choices' => array(
							array('label' => $mon_1,
							'ref' => 'mon_choice_1'),
							array('label' => $mon_2,
							'ref' => 'mon_choice_2'),
							array('label' => $ooo_text,
							'ref' => 'mon_ooo')
						)
					),
					'validations' => array(
						'required' => true
					)
					
				),
				array('title' => 'Tuesday',
					'ref' => 'tues_choice',
					'type' => 'multiple_choice',
					'properties' => array(
						'allow_multiple_selection' => false,
						'choices' => array(
							array('label' => $tues_1,
							'ref' => 'tues_choice_1'),
							array('label' => $tues_2,
							'ref' => 'tues_choice_2'),
							array('label' => $ooo_text,
							'ref' => 'tues_ooo')
						)
					),
					'validations' => array(
						'required' => true
					)
					
				),
				array('title' => 'Wednesday',
					'ref' => 'weds_choice',
					'type' => 'multiple_choice',
					'properties' => array(
						'allow_multiple_selection' => false,
						'choices' => array(
							array('label' => $weds_1,
							'ref' => 'weds_choice_1'),
							array('label' => $weds_2,
							'ref' => 'weds_choice_2'),
							array('label' => $ooo_text,
							'ref' => 'weds_ooo')
						)
					),
					'validations' => array(
						'required' => true
					),
					
				),
				array('title' => 'Thursday',
					'ref' => 'thurs_choice',
					'type' => 'multiple_choice',
					'properties' => array(
						'allow_multiple_selection' => false,
						'choices' => array(
							array('label' => $thurs_1,
							'ref' => 'thurs_choice_1'),
							array('label' => $thurs_2,
							'ref' => 'thurs_choice_2'),
							array('label' => $ooo_text,
							'ref' => 'thurs_ooo')
						)
					),
					'validations' => array(
						'required' => true
					)
					
				),
				array('title' => 'Friday',
					'ref' => 'fri_choice',
					'type' => 'multiple_choice',
					'properties' => array(
						'allow_multiple_selection' => false,
						'choices' => array(
							array('label' => $fri_1,
							'ref' => 'fri_choice_1'),
							array('label' => $fri_2,
							'ref' => 'fri_choice_2'),
							array('label' => $ooo_text,
							'ref' => 'fri_ooo')
						)
					),
					'validations' => array(
						'required' => true
					)
					
				),
				array('title' => 'Review your lunch selections before submitting\n\nMonday:\n- {{field:mon_choice}}\nTuesday:\n- {{field:tues_choice}}\nWednesday:\n- {{field:weds_choice}}\nThursday:\n- {{field:thurs_choice}}\nFriday:\n- {{field:fri_choice}}\n',
				'ref' => 'final_summary',
				'type' => 'statement')
			)
		);
		$create_form_json = json_encode($create_form_obj, JSON_PRETTY_PRINT);
		#print_r($create_form_json);

		$client = new GuzzleHttp\Client();
		$res= $client->post('https://api.typeform.com/forms', [
			'body' => $create_form_json,
			'headers' => [
				'Authorization' => 'Bearer '.$accessToken,
				'Accept' => 'application/json'	
			]
		]);	

		$json = json_decode($res->getBody(), true);
		$new_form_url = $json['_links']['display'];
		echo $new_form_url;
		
		// TODO: Send email to all classmates
		// TODO: Get email list from db
		/*
		$headers = "From: lunch@restart.network\r\n";
		$headers .= "Reply-To: lunch@restart.network\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$email_subject = 'Order your lunch for '.$timeframe;
		$email_message = '<div>Don\'t forget to order your lunch for next week!</div><div><a href="'.$new_form_url.'">Order here!</a></div>';

		$email_result = mail('jason.harmon@gmail.com', $email_subject, $email_message, $headers);
		echo "Mail status: ".$email_result;
		*/	
	}


	
}
