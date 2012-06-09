<?php

/** Generic utilities for the system. 
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 * 
 * @todo This idea is old and should be removed in the next version.
 *
 * @static 
 */
class Utilities
{
	/** Get the current date time in full Y-M-D H:M:S format.
	 */
	function getDatetime() 
	{
		$in_date = date("d/m/y");
		$in_time = date("H:i:s");
		
		$date_ = explode("/",$in_date);
		
		$time_ = explode(":",$in_time);
		
		return date("Y-m-d H:i:s",mktime($time_[0],$time_[1],$time_[2],$date_[1],$date_[0],$date_[2]));
	}
	
	/** Redirect the client to a specific location
	 * @param string The http location to redirect to
	 */
	function immediateRedirect($location)
	{
		if (!headers_sent()) 
		{
			header("Location: ".$location);
			exit;
		}
		else 
		{
			echo "<script language='javascript' type='text/javascript'>window.location.href='".$location."';</script>";
			exit;
		}		
	}
	
	
	
	function retrieveFilename($fullpath)
	{
		$file_parts = explode(DIRECTORY_SEPARATOR, $fullpath);
		return array_pop($file_parts);
	}
	
	function BoolToString($val)
	{
		if ($val === true)
		{
			return "true";	
		}	
		else
		{
			return "false";	
		}
	}
	
	function MoneyFormat($in)
	{
		return number_format($in, 2, '.', ',');	
	}
}
?>
