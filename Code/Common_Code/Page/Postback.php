<?php

/** The static postback object.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 *
 * @static 
 */
class Postback
{
	//NOTE: for php5
	//public static $PostBackInput = "_psp_POSTBACKER";
	
	
	function GetPostbackVariable()
	{
		return "_psp_POSTBACKER";	
	}
	

	function WasPostback()
	{
		return isset($_POST[Postback::GetPostbackVariable()]) && $_POST[Postback::GetPostbackVariable()] != "";
	}
	
	
	/** Encodes data from an input, adding slashes if necessary, and encoding HTML tags.
	 */
	function CleanPostData()
	{
		$vals =& $_POST;
		
		$noValidateArray = array(Postback::GetPostbackVariable());
		
		foreach ($vals as $key=>$val)
		{
			if (array_search($key,$noValidateArray) !== false)
			{
				continue;
			}
			
			
			$new_value = $val;
			
			if (is_array($val))
			{
				$tmp = array();
				foreach ($val as $v)
				{
					//clean each 	
					$tmp[] = Postback::CleanMe($v);
				}
				
				$new_value = $tmp;
			}
			else
			{
				$new_value = Postback::CleanMe($val);	
			}
			
			
			$vals[$key] = $new_value;
		}
	}
	
	//private static
	function CleanMe($data)
	{
		//see if magic quotes have been enabled
		if (get_magic_quotes_gpc() == 0)
		{
			$newdata = addslashes($data);
		}	
		else
		{
			$newdata = $data;	
		}
		
		return htmlentities($newdata,ENT_NOQUOTES);	
	}
	
	
	/** Undoes the work of the clean, allowing XML data through.
	 * @param string The postback data to be cleaned.
	 */
	function DirtyMe($value)
	{
		$stripped = stripslashes($value);
		
		if (!function_exists("html_entity_decode"))
		{
			// For PHP 4 versions prior to PHP 4.3.0
			return Postback::HtmlEntityDecode(Postback::HtmlEntityDecode($stripped));	
		}
		else
		{
			//NOTE: not sure why we need two, but wasnt working with just one in php 4.4.0
			return html_entity_decode(html_entity_decode($stripped,ENT_NOQUOTES));
			
		}
		
	}
	
	
	// For PHP 4 versions prior to PHP 4.3.0
	//private
	function HtmlEntityDecode($string) 
	{
		// replace numeric entities
		$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
		$string = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $string);
		// replace literal entities
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($string, $trans_tbl);
	}
	
	
	/** Currently: returns clientID of button that caused postback
	 * @return Mixed String value of control client ID or FALSE if not postback.
	 */
	function GetPostbacker()
	{
		if (Postback::WasPostback())
		{
			return $_POST[Postback::GetPostbackVariable()];
		}
		else
		{
			return false;	
		}
	}
	
	//static
	/** Static method to retrieve a postback control.
	 * @var PageControl
	 * @return Mixed String if successful, Array for control InputGroup, boolean FALSE otherwise.
	 */
	function Get($control)
	{
		if ($control->Type == PC_INPUTGROUP && !$control->IsSelectBox())
		{
			$cid = $control->GetGroupName();	
		}
		else
		{
			$cid = $control->GetClientID();
		}
		
		if (isset($_POST[$cid]) && $_POST[$cid] != "")
		{
			return $_POST[$cid];
		}
		else
		{
			return false;
		}
	}	
	
	
	
	
}

?>