<?php

/** Extension of DomDocument object and container of helper functions
 * In PHP4 cannot extend the class easily, so we contain it as such. 
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */

class PspDomDocument extends PspDomElement
{
	//NOTE: private
	/** 
	 * @var DomDocument
	 */
	var $document = null; 
	
	//NOTE: private
	/** Xpath variable (private)
	 *
	 */
	var $xpath = null;
	

	
	/** Constructor: allows the creation of a new document or the loading of 
	 * an existing one.
	 * @param string XML Version (for new docs). Default of 1.0
	 * @param string Encoding (default utf-8). Unused in PHP4.
	 */
	function PspDomDocument($ver="1.0", $encoding="utf-8")
	{
		$this->document = domxml_new_doc($ver);	
		
		$this->SetRoot();
	}

	/** Set this document to another. 
	 * NOTE: protected method
	 * @param DomDocument
	 */
	function SetDocument(&$document)
	{
		$this->document =& $document;	
		
		$this->SetRoot();
	}
	
	/** Set the root element of this document
	 * 
	 */
	function SetRoot()
	{
		parent::PspDomElement($this->document->document_element());	
		
	}
	
	function GetRoot()
	{
		return $this->NodeFactory($this->document->document_element());	
	}
		
	function LoadFile($file)
	{
		$this->document = domxml_open_file($file);	
		
		$this->SetRoot();
	}
	
	function IsEmpty()
	{
		return $this->document == null || $this->document->document_element() == null;	
	}
	
	/**
	 * @return boolean Success.
	 */
	function LoadXML($xml)
	{
		if (trim($xml) != "")
		{
			$this->document = domxml_open_mem($xml);
	
			$this->SetRoot();
			
			return true;
		}
		else
		{
			return false;	
		}
	}

	function GetXML($format=true)
	{
		//dump the xml into a string
		return $this->document->dump_mem($format);		
	}
	
	function GetAsXHTML()
	{
		//dump the xml into a string
		$xml = $this->document->dump_mem(true);	
		
		//NOTE: a bit bodgy
		//remove any CDATA tags 
		$xml = str_replace("]]>","",str_replace("<![CDATA[","",$xml));
		
		//remove leading xml tag
		if (strpos($xml,"<?xml") == 0) 
		{
			list($first,$rest) = split("\n",$xml,2);
			return $rest;
		} 
		else
		{
			return $xml; 
		}		
	}
	
	/** Run an xpath query, returning an array of the results. Not a nodelist.
	 *
	 * @param string XPath query
	 * @param PspDomElement [Optional] The relative node if any.
	 * @return array [int => DocumentElement]
	 */
	function XPathQuery($query, $rel_node = null)
	{
		$this->xpath = null;
		
		$this->xpath = $this->document->xpath_new_context();
		
		//NOTE: means that namespaces in user documents resemble these exactly
		xpath_register_ns($this->xpath, "", "http://www.w3.org/1999/xhtml");
		xpath_register_ns($this->xpath, PCPREFIX, "http://web.justinjmoses.com.au/psp");
			
		if ($rel_node != null)
		{
			$nodes = $this->xpath->xpath_eval($query, $rel_node->DomNode);
		}
		else
		{
			$nodes = $this->xpath->xpath_eval($query);	
		}
		
		$narray = array();
		
		if ($nodes != null)
		{
			foreach($nodes->nodeset	as $n)
			{
				$narray[] = $this->NodeFactory($n);	
			}
		}
			
		return $narray;
	}
	
	/** Run an xpath query, returning the first result.
	 *
	 * @param string XPath query
	 * @param PspDomElement [Optional] The relative node if any.
	 * @param bool Causes error if no node found. Default is TRUE.
	 * @return PspDomElement
	 */
	function XPathQuerySingle($query, $rel_node = null, $strict = true)
	{
		$res = $this->XPathQuery($query, $rel_node);
		
		
		if (count($res) < 1)
		{
			if ($strict)
			{
				trigger_error("XPath single query returned no results. Query: $query .", E_USER_ERROR);		
			}
			else
			{
				return null;
			}
		}
		
		
		return $res[0];
	}
	
	
	/** Inserts some text into a node
	 * @param string the text
	 * @param PspDomNode the node
	 * @return PspDomNode the inserted text node
	 */
	function InsertTextIntoNode($text, $node)
	{
		return $node->AppendChild($this->CreateTextNode($text));
	}
	
	
	
	
	// {{{ DOM functions
	
	
	/** Overwrite the existing PspDomNode->AppendChild function with this, 
	 * to conform with the DOM.
	 * @param PspDomNode
	 * @return PspDomNode
	 */
	function AppendChild($newnode)
	{
		$newappend = $this->document->append_child($newnode->DomNode);
		
		return $this->NodeFactory($newappend);	
	}
	
	/**
	 * @return PspDomElement
	 */
	function CreateElement($name)
	{
		return new PspDomElement($this->document->create_element($name));	
	}
	
	/**
	 * @return PspDomNode
	 */ 
	function CreateTextNode($text)
	{
		return new PspDomNode($this->document->create_text_node($text));	
	}
	
	/**
	 * @return PspDomNode
	 */ 
	function CreateComment($text)
	{
		return new PspDomNode($this->document->create_comment($text));	
	}
	
	/**
	 * @return PspDomAttr
	 */
	function CreateAttribute($name, $value)
	{
		return new PspDomAttr($this->document->create_attribute($name, $value));	
	}
	
	/**
	 * @return PspDomNode
	 */
	function DocumentElement()
	{
		return $this->NodeFactory($this->document->document_element());	
	}
	
	// }}}
	
}


?>