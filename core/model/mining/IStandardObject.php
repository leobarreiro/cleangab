<?php
/**
 * CleanGab Framework
 * IStandardObject.php
 * Date: 	2011-07-15
 * Package: model/mining
 * Author: 	Leopoldo Barreiro
 */

interface IStandardObject {
	
	/**
	 * @param strFeature: String that represents the name of an object operation
	 * @param obj1: An object
	 * @return boolean (true if feature is similar, other else false)
	 * @param obj2: Another object 
	 * Compare the return of two objects operation
	 * The objects must be from a same type. 
	 */
	function compareFeature($strFeature, $obj1, $obj2);
	
	
}
?>