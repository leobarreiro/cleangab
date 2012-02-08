<?php
/**
 * CleanGab Framework
 * Validation.php
 * Date: 	2011-01-28
 * Author: 	Leopoldo Barreiro
 * 
 */

class Validation {
	
	static function notNull($mixed, $message) {
		if ($mixed == null) {
			throw new UnexpectedValueException($message, 1);
		}
	}
	
	static function objectType($type, $mixed, $message) {
		if (!is_object($mixed)) {
			throw new Exception($message, 1);
		} else {
			if (!get_class($mixed) == $type) {
				throw new UnexpectedValueException($message, 1);
			}
		}
	}
	
	static function notEmpty($mixed, $message) {
		if (strlen($mixed) == 0) {
			throw new UnexpectedValueException($message, 1);
		}
	}
	
}
?>