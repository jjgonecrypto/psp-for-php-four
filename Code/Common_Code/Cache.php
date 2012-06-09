<?php

/** Cache control.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */

class Cache
{
	var $Files = null;
	
	
	
	function Cache()
	{
		$this->Files = array();	
	}
	
	function Find($uri,$langCode)
	{
		if (isset($this->Files[$uri."|".$langCode]))
		{
			return 	$this->Files[$uri."|".$langCode];
		}
		else
		{
			return false;	
		}
	}
	
	function AddFile($CacheFile)
	{
		$this->Files[$CacheFile->Uri."|".$CacheFile->LanguageCode] = $CacheFile;
	}
	
	
	//static
	function Load($fileLocation)
	{
		if (file_exists($fileLocation))
		{
			$cache_data = file_get_contents($fileLocation);
			
			$Cache = unserialize($cache_data);
			
			return $Cache;
		}
		else
		{
			return false;
		}
		
	} 
	
	function Save($fileLocation, $Cache)
	{
		$handle = fopen($fileLocation,"wb");
		
		
		if (fwrite($handle, serialize($Cache)) === FALSE) {
			trigger_error("Cannot write to file.",E_USER_NOTICE);
		}
		
		fclose($handle);

	}
	
	//empty the cache contents
	function Clear($fileLocation)
	{
		$handle = fopen($fileLocation,"wb");
		
		fclose($handle);
	}
}

?>