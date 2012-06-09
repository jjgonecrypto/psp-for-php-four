<?php


/** Class to profile output.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class Profiler
{
	var $Name;
	
	//private
	var $Start; 
	
	//private
	var $End;
	
	//in seconds
	/**
	 * @var decimal The number of seconds to process
	 */
	var $Time; 
	
	
	
	function Profiler($name)
	{
		$this->Name = $name;	
		
	}
	
	function Start()
	{
		$this->Start = microtime();	
	}	
	
	function Stop()
	{
		$this->End = microtime();
		
		$this->Time = number_format(((substr($this->End,0,9)) + (substr($this->End,-10)) - (substr($this->Start,0,9)) - (substr($this->Start,-10))),4);	
	}
	
	function Factory($name)
	{
			
	}
}

?>