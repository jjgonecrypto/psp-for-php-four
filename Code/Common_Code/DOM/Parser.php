<?php

/** A static parsing class
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 *
 * @static 
 */
class Parser 
{
	
	/** Recursively parse some DOM 
	 * @param IHasControls
	 * @param PspDomNode
	 */
	function ParseForControls(&$IHasControls,$node)
	{
		//echo get_class($node)."<br>";
		if (!is_a($node,"PspDomElement"))
		{
			return;	
		}
		else if ($node->GetAttribute("runat") == "server")
		{
		
			//is a control
			$control = PageControl::Factory($node);
			
			//only parse controls that don't have unique children
			if (!$control->ChildrenAreUnique)
			{
				foreach ($node->ChildNodes() as $child)
				{
					Parser::ParseForControls($control,$child);
				}	
			}	
			
			$IHasControls->AddControl($control);
		}
		else
		{
			foreach ($node->ChildNodes() as $child)
			{
				Parser::ParseForControls($IHasControls,$child);
			}			
		}
		
	}
	
	/** Static
	 * @param BasePage
	 */
	function ParseInlineBlocks($IHasControls, $node)
	{
		
		if (is_a($node,"PspDomElement"))
		{
			//TODO check each attribute
			$atts = $node->GetAttributes();
			
			foreach ($atts as $a)
			{
				Parser::ProcessInlineBlocks($IHasControls, $a);
			}
		}
		
		if ($node->IsTextNode())
		{
			
			//check this node
			
			Parser::ProcessInlineBlocks($IHasControls, $node);

		}
		
		//check each child node
		foreach ($node->ChildNodes() as $child)
		{
			Parser::ParseInlineBlocks($IHasControls, $child);	
		}
			
	}
	
	
	//NOTE: private static fnct
	function ProcessInlineBlocks($IHasControls, $node)
	{
		$found = array();
		$nodeval = $node->GetNodeValue();
		
		
		if (preg_match_all("/\[=\s*?[a-zA-Z0-9]*\.[a-zA-Z0-9]*\s*?\]/", $nodeval, $found) <= 0)
		{
			return;	
		}
		
		//print_r($found);
		//echo "<p>";
		
		foreach ($found[0] as $f)
		{
			//echo $f."<br>";	
			$newfound = trim(str_replace("[=","",str_replace("]","",$f)));
			
			//separate
			list($controlID, $propFnc) = explode(".",$newfound);
			
			
			
			$block = new InlineCodeBlock($IHasControls, $controlID, $propFnc);
			
			$block->LoadText();
			
			$node->SetNodeValue(str_replace($f,$block->Text, $nodeval));	
			
			$nodeval = $node->GetNodeValue();
		}
		
	}
	
	
	
	
	
}

?>