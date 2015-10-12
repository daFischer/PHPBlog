<?php
/**
* 
*/
class Logger
{
	static public $errors;

	static public function error($msg)
	{
		$errors .= $msg . "\n";
	}
	
}
?>