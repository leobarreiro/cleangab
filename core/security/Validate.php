<?php
/**
 * Clean-Gab Framework
 * Validate.php
 * Date: 	2011-01-28
 * Author: 	Leopoldo Barreiro
 * 
 */

class Validate {
	
	public static function notNull($mixed, $message) {
		if ($mixed == null) {
			throw new UnexpectedValueException($message, 1);
		}
	}
	
	public static function objectType($type, $mixed, $message) {
		if (!is_object($mixed)) {
			throw new Exception($message, 1);
		} else {
			if (!get_class($mixed) == $type) {
				throw new UnexpectedValueException($message, 1);
			}
		}
	}
	
}
?>