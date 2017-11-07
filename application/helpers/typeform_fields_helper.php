<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function textAnswerByField($answers, $field_id){
	for ($a = 0; $a < count($answers); $a++) {
		$answer = $answers[$a];
		$field = $answer["field"];
		$type = $answer["type"];
		// TODO: Add complex types like choice/choices
		if ($field["id"] == $field_id) {
			return $answer[$type];
		}
	}

	return "";
}

function textAnswerByRef($fields, $answers, $ref) {
	for ($f = 0; $f < count($fields); $f++) {
		$field = $fields[$f];
		$field_ref = $field['ref'];
		$field_type = $field['type'];
		if ($field_ref == $ref) {
			$field_id = $field['id'];
			return textAnswerByField($answers, $field_id);
		}
	}
	return "";
}

?>
