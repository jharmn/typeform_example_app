<?php

class Entries_model extends CI_Model {

	public function __construct()
	{
                parent::__construct();
		$this->load->database();
	}

	public function get_entries() {
		$query = $this->db->get('Entries');
		return $query->result_array();
	}

}

?>
