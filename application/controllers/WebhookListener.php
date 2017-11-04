<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(E_STRICT);


class WebhookListener extends CI_Controller {

	function textAnswerByField($array, $field_type, $field_id){
		for ($a = 0; $a < count($array); $a++) {
			$answer = $array[$a];
			$field = $answer["field"];
			if ($field["id"] == $field_id) {
				if ($field_type == "short_text" || $field_type == "long_text") {
					return $answer["text"];
				} else if ($field_type == "email") {
					return $answer["email"];
				}
			}
		}

    		return "";
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
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
		$query = $this->db->query("SELECT token FROM Webhooks WHERE Token = ".$this->db->escape($token)." LIMIT 1");
		$row = $query->row();
		if (count($row) > 0) {
			show_error('Token exists', 409);
		} else {
			// Retrieve answer/fields from webhook
			$answers = $response["answers"];
			$first_name = $this->textAnswerByField($response["answers"], "short_text", $this->config->item("webhook_fname_field_id"));
			$last_name = $this->textAnswerByField($response["answers"], "short_text", $this->config->item("webhook_lname_field_id"));
			$email = $this->textAnswerByField($response["answers"], "email", $this->config->item("webhook_email_field_id"));

			// Store answer/fields in db
			$insert_query = "INSERT INTO Webhooks (Token, FirstName, LastName, Email) VALUES(".
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