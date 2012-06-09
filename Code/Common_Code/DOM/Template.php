<?php

/** A base template system for webpages.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */

class Template
{
	/** The filename and path of the template to open.
	 * @var string
	 */
	var $File = null;
	
	
	/** The template document 
	 * @var PspDomDocument
	 */
	var $Document = null;
	
	
	function Template($file, $type = "XML")
	{
		$this->File = $file;
		
		if (!file_exists($file))
		{
			trigger_error("Cannot find template file: $file.",E_USER_ERROR);
		}
		
		$type = strtoupper($type);
		
		$this->Document = new PspDomDocument();
		
		
		if ($type == "XML")
		{
			$this->Document->LoadFile($file);	
			
		}
		else
		{
			
			$contents = file_get_contents($file);
			
			if ($type == "CSS")
			{	
				//create style block
				$style_node = $this->Document->CreateElement("style");
				$style_node->SetAttribute("type","text/css");
				
				$style_node->AppendChild($this->Document->CreateTextNode($contents));
				
				$this->Document->AppendChild($style_node);
				
			}	
			else if ($type == "JS")
			{
				//create script block
				$script_node = $this->Document->CreateElement("script");
				$script_node->SetAttribute("type","text/javascript");
				$script_node->SetAttribute("language","javascript");
				$script_node->AppendChild($this->Document->CreateTextNode($contents));
				
				$this->Document->AppendChild($script_node);
			}
			else
			{
				trigger_error("Unknown template type: $type",E_USER_ERROR);	
			}
			
		}
		
	}
	
	
}

?>