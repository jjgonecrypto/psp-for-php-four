<?php

/** Cookie handling class.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class Cookie
{
	/** Get the cookie.
	 * @return Mixed String on success, NULL otherwise.
	 */
	function Get($key)
	{
		if (Cookie::Exists($key))
		{
			return $_COOKIE[$key];
		}
		else
		{
			return null;
		}
	}
	
	/** Check for the existence of a cookie.
	 *
	 */
	function Exists($key)
	{
		return isset($_COOKIE[$key]);	
	}
	
	/** Set or unset the cookie. (Use value param as FALSE to remove cookie).
	 *
	 */
	function Set($key, $value = false, $exp = null, $path = null, $domain = null, $secure = false)
	{
		//add IE6 privacy header
		Response::AddHeader('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
		
		return setcookie($key, $value, $exp, $path, $domain, $secure);
	}
}
?>