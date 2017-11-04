<?php

class Entries_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function get_entries() {
		$query = $this->db->get('Entries');
		return $query->result_array();
	}

	public function get_winners() {
		$query = $this->db->get_where('Entries', array('Winner' => 1));
		return $query->result_array();
	}

}

?>
