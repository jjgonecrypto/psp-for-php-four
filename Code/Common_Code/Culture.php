<?php

/** Culture class, allows specific cultures within a language. Should  
 *  handle DateTimes and Numbers - but doesn't yet.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
 
class Culture
{
	/** The four char code. Eg: "en-AU"
	 * @var String
	 */
	var $Code = null;
	
	/** The (English) name of this culture
	 * @var String
	 */
	var $Name = null;
	
	/** The native name of this culture. Default is Name.
	 * @var String
	 */
	var $NativeName = null;
	
	
	function Culture($code, $name, $native_name = null)
	{
		$this->Code = $code;
		$this->Name = $name;	
		
		if ($native_name != null)
		{
			$this->NativeName = $native_name;
		}
		else
		{
			$this->NativeName = $this->Name;	
		}
	}	
	
	
	
}

?>