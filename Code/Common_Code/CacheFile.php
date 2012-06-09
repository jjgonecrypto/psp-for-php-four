<?php

/** Cache control.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
 
class CacheFile
{
	var $Contents = null;
	
	var $Uri = null;
	
	var $LanguageCode = null;
	
	function CacheFile($c,$u,$l)
	{
		$this->Contents = $c;
		$this->Uri = $u;
		$this->LanguageCode = $l;	
	}
	
}

?>