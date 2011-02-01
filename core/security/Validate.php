<?php
/**
 * Clean-Gab Framework
 * Validate.php
 * Date: 	2011-01-28
 * Author: 	Leopoldo Barreiro
 * 
 */

class Validate {
	
	public static function notNull($mixed) {
		if ($mixed == null) {
			throw new UnexpectedValueException("Value can't be null", 1);
		}
	}
	
	public static function objectType($type, $mixed) {
		if (!is_object($mixed)) {
			throw new Exception("Mixed is not an object", 1);
		} else {
			if (!get_class($mixed) == $type) {
				throw new UnexpectedValueException("Value is not the object type expected", 1);
			}
		}
	}
	
}
?>