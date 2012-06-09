<?php

/** Class to encrypt data.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class Cryption
{
	/** Key to crpyt database credentials
	* 
	*/
	function GetKey()
	{
		return "»‰#93[‰Áﬂ}•";
	}
	
	function GetIV()
	{
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		return mcrypt_create_iv($iv_size, MCRYPT_RAND);
	}
	
	function LoadCrypt()
	{
		// the PHP_SHLIB_SUFFIX constant is available as of PHP 4.3.0
		if (!extension_loaded('mcrypt')) 
		{
			$prefix = (PHP_SHLIB_SUFFIX == 'dll') ? 'php_' : '';
			dl($prefix . 'mcrypt.' . PHP_SHLIB_SUFFIX);
		}
	}
	
	/** Used manually to encrypt data
	 * 
	 */
	function Encrypt($string, $separator = '.')
	{
		Cryption::LoadCrypt();
		
		$crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, Cryption::GetKey(), $string, MCRYPT_MODE_ECB, Cryption::GetIV());
	
		$tmparr = array();
		
		for ($i = 0; $i< strlen($crypt); $i++)
		{
			$tmparr[] = ord(substr($crypt,$i));	
		}
		
		return implode($separator,$tmparr);
	}
	
	/** Used by the system to decrypt credentials
	 *
	 */
	function Decrypt($string,$separator = '.')
	{
		Cryption::LoadCrypt();
		
		$tmparr = explode($separator,$string);
		
		$crypt = "";
		
		foreach ($tmparr as $piece)
		{
			$crypt .= chr($piece);	
		}
		
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, Cryption::GetKey(), $crypt, MCRYPT_MODE_ECB, Cryption::GetIV());	
	}
		
	
}

?>