<?php

/** Static class to represent
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 * @static 
 */
class Server
{	
	function GetFullUri()
	{
		//NOTE: there is a better way to do this, but too lazy now.
		return new Uri(CMS_WEBSITE_DOMAIN.$_SERVER['REQUEST_URI']);
	}
	
	function Get($key)
	{
		if (isset($_SERVER[$key]))
		{
			return $_SERVER[$key];	
		}	
		else
		{
			return false;
		}		
	}
	
}
?>