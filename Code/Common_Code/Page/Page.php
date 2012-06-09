<?php

/** A website page.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class Page extends BasePage
{

	var $Theme = null;
	
	var $Title = null;
	
	var $Form = null; 
	
	var $Languages = null;
	
	var $CurrentLanguage = null;
	
	var $ConditionalNode = null;
	
	
	function Page($filename)
	{
		//constructor
		parent::BasePage($filename);
		
		$this->Languages = array();	
	}
	

	function Page_Preload()
	{
		
		//get the form for this page
		$this->Form =& WebsiteControl::GetFormControl();
		
		if ($this->Form == null)
		{
			trigger_error("No FORM control found. Each page must contain a single FORM control.",E_USER_ERROR);	
		}
		
		
		//call the base preloader
		parent::Page_Preload();	
		
	}
	
	
	function Page_Load()
	{
		//base load	
		parent::Page_Load();	
	}	
	
	/** Return the page XHTML
	 * @return string
	 */
	function Page_Render()
	{
		//Create postback hidden input 
		//TODO - when you fix the Rendering
		//$this->Form->AddControl(new PageControl_Input());
		
		$document = parent::Page_Render();
		
		//set html lang attribute
		if ($this->CurrentLanguage != null)
		{
			$html_node = null;
			
			$html_node = $document->XPathQuerySingle("//html");
			
			$cult = $this->CurrentLanguage->GetDefaultCulture();
			
			if ($cult != null)
			{
				$code = $cult->Code;	
			}
			else
			{
				$code = $this->CurrentLanguage->Code;	
			}
			
			$html_node->SetAttribute("lang",$code);
			
			$html_node->SetAttribute("xml:lang",$code);
			
			$html_node->RemoveAttribute("xmlns:psp");
		}
		
		$head_node = null;
		
		//Check head exists
		$head_node = $document->XPathQuerySingle("//head", null, false);
		
		//create new if none exists
		if ($head_node == null)
		{
			$head_node = $document->InsertFirst($document->CreateElement("head"));
		}
		
		
		$title_node = null;
		
		//check title exists
		$title_node = $document->XPathQuerySingle("//title", null, false);
		
		if ($title_node == null)
		{
			$title_node = $head_node->AppendChild($document->CreateElement("title"));
		}

		$document->InsertTextIntoNode($this->Title, $title_node);
		
		
		//////////////
		// Add the theme
		///////////////
		
		
		$tfolder = ""; $link_node = null;
		
		if ($this->Theme != "")
		{
			$tfolder = $this->Theme;
		}
		
	
		//ensure directory exists
		if (!is_dir(CSS_PATH.$tfolder))
		{
			trigger_error("Theme of ".$this->Theme." doesn't exist.",E_USER_WARNING);
			$tfolder = "";
		}
		
		//get all css files
		$cssfiles = RecursiveSearch::GetStyleSheets(CSS_PATH.$tfolder.DIRECTORY_SEPARATOR);
		
		foreach ($cssfiles as $cfile)
		{
			$link_node = $document->CreateElement("link");
			$link_node->SetAttribute("rel","stylesheet");
			$link_node->SetAttribute("type","text/css");
			$link_node->SetAttribute("href",CSS_LINK.$tfolder."/".$cfile->RelativePath);
			
			if ($this->ConditionalNode != null)
			{
				$head_node->InsertBefore($link_node, $this->ConditionalNode);	
			}
			else
			{
				$head_node->AppendChild($link_node);
			}
		}

		
		//Append Javascript for the entire site - Postback code 	
		$scriptfiles = RecursiveSearch::GetScripts(JS_PATH);
		
		foreach ($scriptfiles as $sfile)
		{
			$s_content = file_get_contents($sfile->AbsolutePath);
			
			$scr_node = $document->CreateElement("script");
			$scr_node->SetAttribute("language","javascript");
			$scr_node->SetAttribute("type","text/javascript");
			
			$scr_node->AppendChild($document->CreateTextNode($s_content));
			
			//parse the script for any special code insert tags
			Parser::ParseInlineBlocks(&$this, $scr_node);
			
			$head_node->AppendChild($scr_node);
		}
		
		
		
		return $document;
	}
	
	function GetPostbackVariable()
	{
		return Postback::GetPostbackVariable();	
	}
	
	
	/** Set the resource file for this website. Only one per instance. 
	 * 
	 */
	function SetResource($filename)
	{
		WebsiteControl::SetResource($filename);
	}
	
	
	function AddLanguage($language)
	{
		
		$this->Languages[$language->Code] = $language;
		
	}
	
	/** Initialises the language based on the current cookie, else uses the input 
	 * for the default.
	 */
	function InitialiseLanguage($code)
	{
		$cookie = $this->UniqueWebName()."_lang";
		
		if (Cookie::Exists($cookie))
		{
			//set the language without redoing cookie
			$this->SetLanguage(Cookie::Get($cookie),false);
		}
		else
		{
			//set the deafult lang if no cookie set
			$this->SetLanguage($code);	
		}
	}
	
	/** Set the language of the website.
	 * 
	 */ 
	function SetLanguage($code, $cookie = true)
	{
		$lang = $this->Languages[$code];
		
		if ($lang == null)
		{
			trigger_error("The language defined by code: ".$code." isn't installed.",E_USER_ERROR);	
		}
		
		//set the resource 
		$this->SetResource($lang->ResourceFile);
		
		//set the current language
		$this->CurrentLanguage = $lang;
		
		if ($cookie)
		{
			Cookie::Set($this->UniqueWebName()."_lang",$code,time()+60*60*24*30,"/");
		}
	}
	
	
	
	function UniqueWebName()
	{
		return preg_replace("/[^a-zA-Z0-9]+/","",CMS_WEBSITE_NAME);	
	}
	
	/** Notes of the first conditional node in the header and where it is, so that 
	 * when the page renders, it will render the style sheets BEFORE this 
	 * first conditional comment (and therefore not overwriting the style changes for IE)
	 */
	function SetConditional(&$ConditionalNode)
	{
		if ($this->ConditionalNode == null)
		{
			$x = $ConditionalNode->ParentNode();
			
			if (strtolower($x->GetNodeName()) == "head")
			{
				//only the first in the header
				$this->ConditionalNode =& $ConditionalNode;
			}
			
		}
	}
}

?>