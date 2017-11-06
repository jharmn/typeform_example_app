<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function textAnswerByField($array, $field_type, $field_id){
	for ($a = 0; $a < count($array); $a++) {
		$answer = $array[$a];
		$field = $answer["field"];
		if ($field["id"] == $field_id) {
			if ($field_type == "short_text" || $field_type == "long_text") {
				return $answer["text"];
			} else if ($field_type == "email") {
				return $answer["email"];
			} else if ($field_type == "file_url") {
				return $answer["file_url"];
		$field = $answer["field"];
			}
		}
	}

	return "";
}
?>
