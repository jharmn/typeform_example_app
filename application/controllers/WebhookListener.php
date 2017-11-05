<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WebhookListener extends CI_Controller {

	public function __construct() {
                parent::__construct();
		$this->load->helper('typeform_fields');
	}

	public function view()
	{
		$this->config->load('form');

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
			$first_name = textAnswerByField($answers, "short_text", $this->config->item("webhook_fname_field_id"));
			$last_name = textAnswerByField($answers, "short_text", $this->config->item("webhook_lname_field_id"));
			$email = textAnswerByField($answers, "email", $this->config->item("webhook_email_field_id"));

			// Store answer/fields in db
			$insert_query = "INSERT INTO Entries (Token, FirstName, LastName, Email) VALUES(".
				$this->db->escape($token).", ".
				$this->db->escape($first_name).", ".
				$this->db->escape($last_name).", ".
				$this->db->escape($email).")";
			if (! $this->db->query($insert_query)) {
				show_error('Unable to store webhook', 500);
			}
		}
	}

	
}
