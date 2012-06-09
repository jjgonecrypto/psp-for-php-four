<?php


/** The config object
 * @var Config
 */
global $__psp__Config; 
$__psp__Config = "__psp__Config_global"; 
$GLOBALS[$__psp__Config] = null;


/** Configuration handler. 
 *
 *  This class handles the configuration settings - and 
 *  returns values from the file on request.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */

class Config
{
	
	var $Template = null; 
	
	var $Settings = null; 
	
	var $Keys = null;
	
	/**
	 * @var Array A list of all available languages 
	 */
	var $Languages = null;
		
	
	function Config($file)
	{
		$this->Template = new Template($file);
		
		$this->Settings = array();
		
		$this->Keys = array();
		
		$this->Languages = array();	
	}
	
	//static	
	function Load($file)
	{
		
	//TODO: should check if the config exists already in serialised form
		
		
		
		
		global $__psp__Config; 
		
		$Config = new Config($file);
		
		
		
		$root = $Config->Template->Document->GetRoot();
		
		$children = $rot->ChildNodes();


		//Step 1: Get the current environment name 
		
		//Step 2: Parsing:::: 
		
		//	Parse as normal. Note that when parsing attributes, if find the syntax 
		//	[.?@.?] then replace with appropriate value, or else crash.
			
		foreach ($children as $child)
		{
			$nodename = $children->NodeName();
			
			if ($nodename == "settings")
			{
				
				/*
				
					foreach (setting child)
					{
						$Config->ParseSetting($node);
						
						
					}
				
				*/
				
				
			}
			else if ($nodename == "keys")
			{
				/*
				
					foreach (child)
						$Config->AddKey($name, $value);
				
				*/
			}
			else if ($nodename == "languages")
			{
				
			}
			
		}
			
			
		//2.1 Settings
		/*
		  
		   (NB: Except for "environment" node, nothing else has children.) 
		
		   
		
		   If environment is found and matches the current environment, then 
		   parse its children, setting up any settings there, overiding 
		   anything already set.
		
		   If end environment is found, stop the overide command. 	
		   
			* Note: Database needs to be setup via WebsiteControl when found. 
		
			* Note: Caching, need to setup via WebsiteControl.

			* Note: Errors also via WebsiteControl.
		   
		*/
		
		//2.2 Keys 
		
		//2.3 Languages
		
		/*
		
			Need to parse in the types of languages, setting them up including 
			their cultures. Then set the default Resource via WebsiteControl.
		*/
		
		
		//keep a reference to the config objectr
		$GLOBALS[$__psp__Config] =& $Config;
		 
		return $Config;
		
	}
	
	
	function ParseSetting($node)
	{
		/*
		if (environment)
		{
			if (@name == currentenvironment)
			{
				foreach (child)
					$this->ParseSetting(child)
			}
		}
		else
		{
			parse each attribute if contains special links 
			
			if (database)
				do database
			else if (errors)
				do errors
			else if (caching)
				do caching
			else 
				$this->AddSetting($parentname.@.$attribute, $value)
	
		}
		
		
		*/
	}
	
	//instance method
	function AddSetting($key, $value)
	{
		$this->Settings[$key] = $value;
	}
	
	//instance method
	function AddKey($key, $value)
	{
		$this->Keys[$key] = $value;
	}
	
	//static
	function GetSetting($name)
	{
		//THIS IS STATIC - so it needs to use global variables 
	
		global $__psp__Config;
		
		$Config = $GLOBALS[$__psp__Config];		
		
		return $Config->Settings[$name];	
	}
	
	//static
	function GetKey($name)
	{
		//THIS IS STATIC - so it needs to use global variables 
	}
	
	//static
	function GetLanguage()
	{
		//??	
	}
}

?>