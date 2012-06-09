<?php

/** Language class. Defines a single language that the website is using.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
 
class Language
{
	
	/** The 2 char code. Eg: "en" 
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
	
	
	var $ResourceFile = null;
	
	
	var $Cultures = array();
	
	
	function Language($code, $name, $file, $native_name = null)
	{
		$this->Code = $code;
		$this->Name = $name;
		$this->ResourceFile = $file;	
		
		if ($native_name != null)
		{
			$this->NativeName = $native_name;
		}
		else
		{
			$this->NativeName = $this->Name;	
		}
	
	}	
	
	function AddCulture($Culture)
	{
		$this->Cultures[] = $Culture;
	}
	
	function GetDefaultCulture()
	{
		return $this->Cultures[0];	
	}
	
}

?>