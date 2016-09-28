<?php

namespace App;

class EngineMiscFunctions
{
	public static function jsonp_decode($jsonp, $assoc = false) { // PHP 5.3 adds depth as third parameter to json_decode

		$jsonp = substr($jsonp, strpos($jsonp, '('));

		return json_decode(trim($jsonp,'();'), $assoc);
	}

}