<?php

/** The static website request system, used to load querystring data.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class Request
{
	
	/** Static function to retrieve query string values. 
	 * @param string The query string key
	 * @return mixed String value if the key exists, FALSE otherwise.
	 */
	function Get($key)
	{
		if (isset($_GET[$key]))
		{
			return $_GET[$key];	
		}	
		else
		{
			return false;
		}	
	}	
	
	//static
	/** Function to get the numerica value of a key. 
	 * @param string The query string key
	 * @return mixed Numeric value if the key exists, FALSE otherwise. NOTE: 
	 *				use the !== operator to test if false.
	 */
	function GetNumeric($key)
	{
		$tmp = Request::Get($key);
		if (is_numeric($tmp))
		{
			return $tmp;
		}
		else
		{
			return false;	
		}
	}
	
	function GetUri()
	{
		return $_SERVER['REQUEST_URI'];	
	}
}

?>