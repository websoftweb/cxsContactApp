<?php

function dataFactory($json_url) {
	$response = json_decode(file_get_contents($json_url), true);
	if (!is_array($response) || !isset($response['response'])) {
		return NULL;
	} else {
		$_response_array = $response['response']['data'];
		if ($_response_array){
			$someStdClass = new StdClass();
			foreach($_response_array as $key=>$value) {
				$someStdClass->$key = $value;
			}
			$_array = (object)$someStdClass;
		}
		return $_array;
	}
}