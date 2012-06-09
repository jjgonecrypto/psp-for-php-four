<?php

/** Class to contain a Uri
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 *
 * @todo Needs reworking.
 */
class Uri
{
	var $Uri = null;
	
	var $Variables = null;
	
	function Uri($uri)
	{
		//NOTE: should really be called with all the parts.
		$this->Uri = $uri;	
		
		$this->Variables = array();
	}	
	
	function AddVariable($variable)
	{
		$this->Variables[] = $variable;
	}

	function Output()
	{
		if (count($this->Variables) > 0)
		{
			$output = $this->Uri;
			
			
			if (strstr($output,"?"))
			{
				$output .= "&";
			}	
			else
			{
				$output .= "?";	
			}
			
			$tmp = array();
			
			foreach ($this->Variables as $var)
			{
				$tmp[] = $var->Name."=".$var->Value;
			}
			
			return $output.implode("&", $tmp);
		}
		else
		{
			return $this->Uri;	
		}
	}
}
?>